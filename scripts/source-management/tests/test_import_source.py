"""
Integration tests for import_source.py.

No real network calls: all tests use local git repositories created in tmp_path.
subprocess.run is patched only for the two cases that require fabricated git output
(SHA mismatch) or CalledProcessError injection (invalid commit).
"""

from __future__ import annotations

import json
import os
import subprocess
import sys
import types
from pathlib import Path
from unittest.mock import patch

import pytest

# Make import_source importable from the parent directory.
sys.path.insert(0, str(Path(__file__).parent.parent))
import import_source as imp


# ── helpers ───────────────────────────────────────────────────────────────────

GIT_ENV = {
    **os.environ,
    "GIT_AUTHOR_NAME": "Test",
    "GIT_AUTHOR_EMAIL": "test@example.com",
    "GIT_COMMITTER_NAME": "Test",
    "GIT_COMMITTER_EMAIL": "test@example.com",
}


def make_git_repo(path: Path, files: dict | None = None) -> str:
    """
    Create a minimal local git repo with one commit.
    Returns the full commit SHA.

    files: dict of relative-path → content (str or bytes).
    A MIT LICENSE is included by default; pass files={"LICENSE": ""} to suppress it.
    """
    path.mkdir(parents=True, exist_ok=True)
    _run = lambda cmd: subprocess.run(
        cmd, cwd=path, capture_output=True, env=GIT_ENV, check=True
    )

    _run(["git", "init"])
    _run(["git", "config", "user.email", "test@example.com"])
    _run(["git", "config", "user.name", "Test"])
    # Allow fetching arbitrary SHAs from this repo (needed for git fetch <sha>).
    _run(["git", "config", "uploadpack.allowAnySHA1InWant", "true"])

    default_files: dict = {}
    if files is None or "LICENSE" not in files:
        default_files["LICENSE"] = "MIT License\nCopyright (c) 2024 Test\n"

    all_files = {**default_files, **(files or {})}
    for name, content in all_files.items():
        fp = path / name
        fp.parent.mkdir(parents=True, exist_ok=True)
        if isinstance(content, bytes):
            fp.write_bytes(content)
        else:
            fp.write_text(content)

    _run(["git", "add", "."])
    _run(["git", "commit", "-m", "initial"])

    sha = subprocess.run(
        ["git", "rev-parse", "HEAD"],
        cwd=path,
        capture_output=True,
        text=True,
        check=True,
        env=GIT_ENV,
    ).stdout.strip()
    return sha


@pytest.fixture(autouse=True)
def isolate_sources(tmp_path, monkeypatch):
    """Redirect SOURCES_DIR so tests never touch the real sources/ directory."""
    sources = tmp_path / "sources"
    sources.mkdir()
    monkeypatch.setattr(imp, "SOURCES_DIR", sources)
    return sources


def call_import(url: str, sha: str, name: str = "test-source", **kwargs) -> None:
    """Call import_source() with sensible defaults."""
    imp.import_source(
        url=url,
        commit=sha,
        functional_name=name,
        **kwargs,
    )


# ── SOURCE.yaml / AUDIT.md / FILE_MANIFEST.json generation ───────────────────

class TestOutputFiles:
    def test_import_creates_source_yaml_required_fields(self, tmp_path):
        repo = tmp_path / "origin"
        sha = make_git_repo(repo)
        call_import(f"file://{repo}", sha)

        source_yaml_path = imp.SOURCES_DIR / "test-source" / "SOURCE.yaml"
        assert source_yaml_path.exists()

        import yaml
        data = yaml.safe_load(source_yaml_path.read_text())
        assert data["functional_name"] == "test-source"
        assert data["pinned_commit"].startswith(sha[:8])
        assert data["license"] == "MIT"
        assert data["license_file_verified"] is True
        assert data["security_review_status"] == "pending"
        assert data["license_review_status"] == "pending"
        assert data["decision"] == "candidate"
        assert data["import_mode"] == "vendored-snapshot"

    def test_import_creates_audit_md_sections(self, tmp_path):
        repo = tmp_path / "origin"
        sha = make_git_repo(repo)
        call_import(f"file://{repo}", sha)

        audit = (imp.SOURCES_DIR / "test-source" / "AUDIT.md").read_text()
        assert "## License review" in audit
        assert "## Secret scan" in audit
        assert "## Large files and model weights" in audit
        assert "## Security review" in audit

    def test_import_creates_file_manifest(self, tmp_path):
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"src/main.py": "print('hello')"})
        call_import(f"file://{repo}", sha)

        manifest_path = imp.SOURCES_DIR / "test-source" / "FILE_MANIFEST.json"
        assert manifest_path.exists()
        manifest = json.loads(manifest_path.read_text())
        assert manifest["file_count"] >= 1
        assert isinstance(manifest["files"], dict)


# ── License detection ─────────────────────────────────────────────────────────

class TestLicenseHandling:
    def test_no_license_aborts_in_vendored_mode(self, tmp_path):
        """No LICENSE file and no override → abort in vendored mode."""
        repo = tmp_path / "origin"
        # Override 'LICENSE' with empty string to suppress the default MIT file.
        sha = make_git_repo(repo, files={"LICENSE": "", "README.md": "hi"})
        # Remove the empty LICENSE so detect_license returns NOT-FOUND.
        (repo / "LICENSE").unlink()
        # Re-commit without LICENSE.
        subprocess.run(["git", "add", "."], cwd=repo, check=True, capture_output=True, env=GIT_ENV)
        subprocess.run(["git", "commit", "--allow-empty-message", "-m", "no-license"], cwd=repo, check=True, capture_output=True, env=GIT_ENV)
        sha = subprocess.run(["git", "rev-parse", "HEAD"], cwd=repo, capture_output=True, text=True, check=True).stdout.strip()

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha)
        assert exc.value.code == 1

    def test_license_override_works_without_license_file(self, tmp_path):
        """--license-override=MIT succeeds even when repo has no LICENSE file."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"README.md": "hi"})
        (repo / "LICENSE").unlink(missing_ok=True)
        subprocess.run(["git", "add", "."], cwd=repo, check=True, capture_output=True, env=GIT_ENV)
        subprocess.run(["git", "commit", "--allow-empty", "-m", "no-lic"], cwd=repo, check=True, capture_output=True, env=GIT_ENV)
        sha = subprocess.run(["git", "rev-parse", "HEAD"], cwd=repo, capture_output=True, text=True, check=True).stdout.strip()

        call_import(f"file://{repo}", sha, license_override="MIT")

        import yaml
        data = yaml.safe_load((imp.SOURCES_DIR / "test-source" / "SOURCE.yaml").read_text())
        assert data["license"] == "MIT"
        assert data["license_override_applied"] is True
        assert data["license_file_verified"] is False


# ── Reference-only mode ───────────────────────────────────────────────────────

class TestReferenceOnlyMode:
    def test_reference_only_creates_no_upstream_dir(self, tmp_path):
        """reference-only mode catalogs the source but copies no code."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo)

        call_import(f"file://{repo}", sha, mode="reference-only")

        out = imp.SOURCES_DIR / "test-source"
        assert out.exists()
        assert not (out / "upstream").exists()
        assert (out / "SOURCE.yaml").exists()

    def test_reference_only_no_license_required(self, tmp_path):
        """reference-only mode does not require a LICENSE file."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"README.md": "hi"})
        (repo / "LICENSE").unlink(missing_ok=True)
        subprocess.run(["git", "add", "."], cwd=repo, check=True, capture_output=True, env=GIT_ENV)
        subprocess.run(["git", "commit", "--allow-empty", "-m", "no-lic"], cwd=repo, check=True, capture_output=True, env=GIT_ENV)
        sha = subprocess.run(["git", "rev-parse", "HEAD"], cwd=repo, capture_output=True, text=True, check=True).stdout.strip()

        call_import(f"file://{repo}", sha, mode="reference-only")
        assert (imp.SOURCES_DIR / "test-source" / "SOURCE.yaml").exists()


# ── Commit verification ───────────────────────────────────────────────────────

class TestCommitVerification:
    def test_invalid_commit_sha_aborts(self, tmp_path):
        """A SHA that does not exist in the repo causes abort."""
        repo = tmp_path / "origin"
        make_git_repo(repo)
        fake_sha = "deadbeef" * 5  # 40 hex chars, doesn't exist

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", fake_sha)
        assert exc.value.code == 1

    def test_sha_mismatch_aborts(self, tmp_path):
        """If git rev-parse HEAD returns a different SHA, import aborts."""
        repo = tmp_path / "origin"
        real_sha = make_git_repo(repo)
        original_run = imp.run

        def patched_run(cmd, cwd=None, check=True):
            if len(cmd) >= 3 and cmd[1] == "rev-parse":
                return types.SimpleNamespace(
                    stdout="0000000000000000000000000000000000000000\n",
                    returncode=0,
                )
            return original_run(cmd, cwd=cwd, check=check)

        with patch.object(imp, "run", side_effect=patched_run):
            with pytest.raises(SystemExit) as exc:
                call_import(f"file://{repo}", real_sha)
        assert exc.value.code == 1


# ── Secret scan ───────────────────────────────────────────────────────────────

class TestSecretScan:
    def test_secret_findings_abort_without_reviewed_flag(self, tmp_path, monkeypatch):
        """Unreviewed secret findings abort the import."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo)
        monkeypatch.setattr(
            imp, "secret_scan",
            lambda d: [{"file": "config.py", "label": "aws-access-key", "line": 1, "match_preview": "AKIA***"}],
        )

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha)
        assert exc.value.code == 1

    def test_secret_findings_proceed_with_reviewed_flag(self, tmp_path, monkeypatch):
        """--reviewed-secrets allows import to proceed past the secret scan."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo)
        monkeypatch.setattr(
            imp, "secret_scan",
            lambda d: [{"file": "config.py", "label": "aws-access-key", "line": 1, "match_preview": "AKIA***"}],
        )

        call_import(f"file://{repo}", sha, reviewed_secrets=True)
        assert (imp.SOURCES_DIR / "test-source" / "SOURCE.yaml").exists()
        audit = (imp.SOURCES_DIR / "test-source" / "AUDIT.md").read_text()
        assert "reviewed-secrets" in audit or "reviewed" in audit


# ── Model weights ─────────────────────────────────────────────────────────────

class TestModelWeights:
    def test_model_weight_aborts(self, tmp_path):
        """A .gguf file in the repo always blocks the import."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"model.gguf": b"\x00" * 100})

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha)
        assert exc.value.code == 1

    def test_model_weight_aborts_even_with_allow_large_files(self, tmp_path):
        """--allow-large-files does NOT override the model-weight block."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"weights.safetensors": b"\x00" * 100})

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha, allow_large_files=True)
        assert exc.value.code == 1

    def test_pt_weight_aborts_even_with_allow_large_files(self, tmp_path):
        """Same test for .pt extension."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"checkpoint.pt": b"\x00" * 100})

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha, allow_large_files=True)
        assert exc.value.code == 1


# ── Large files ───────────────────────────────────────────────────────────────

class TestLargeFiles:
    @pytest.fixture()
    def large_file_content(self):
        """11 MB of zeros — above the 10 MB threshold."""
        return b"\x00" * (11 * 1024 * 1024)

    def test_large_file_aborts_without_flag(self, tmp_path, large_file_content):
        """A file >10 MB aborts if --allow-large-files is not set."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"data/large.bin": large_file_content})

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha)
        assert exc.value.code == 1

    def test_large_file_proceeds_with_flag(self, tmp_path, large_file_content):
        """--allow-large-files allows the import and documents the finding in AUDIT.md."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={"fixtures/large.json": large_file_content})

        call_import(f"file://{repo}", sha, allow_large_files=True)

        assert (imp.SOURCES_DIR / "test-source" / "SOURCE.yaml").exists()
        audit = (imp.SOURCES_DIR / "test-source" / "AUDIT.md").read_text()
        assert "large.json" in audit
        assert "requires justification" in audit

    def test_large_file_does_not_block_model_weight_check(self, tmp_path, large_file_content):
        """Having both large files and a model weight always blocks on the weight."""
        repo = tmp_path / "origin"
        sha = make_git_repo(repo, files={
            "fixtures/data.bin": large_file_content,
            "model.ggml": b"\x00" * 100,
        })

        with pytest.raises(SystemExit) as exc:
            call_import(f"file://{repo}", sha, allow_large_files=True)
        assert exc.value.code == 1
