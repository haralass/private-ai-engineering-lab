"""Tests for validate_source_manifests.py"""

from __future__ import annotations

import sys
from pathlib import Path

import pytest
import yaml

sys.path.insert(0, str(Path(__file__).parent.parent))
from validate_source_manifests import (
    REQUIRED_ALL,
    REQUIRED_VENDORED_EXTRA,
    VENDORED_MODES,
    LOCAL_RESEARCH_MODES,
    REFERENCE_MODES,
    read_source_data,
)


# ── Helpers ───────────────────────────────────────────────────────────────────

def make_source(
    tmp_path: Path,
    name: str,
    mode: str,
    *,
    nested: bool = False,
    with_all: bool = True,
    research_dossier: str = "research/people/person3/REPOSITORIES_REVIEWED.md",
) -> Path:
    """Create a synthetic source directory at tmp_path/<name> (or nested path)."""
    if nested:
        d = tmp_path / "people" / "person3" / "github" / name
    else:
        d = tmp_path / name
    d.mkdir(parents=True)

    source_yaml: dict = {
        "functional_name": name,
        "import_mode": mode,
        "pinned_commit": "abc123def456",
        "license": "MIT" if mode in VENDORED_MODES else "NOT-FOUND",
        "license_file_verified": mode in VENDORED_MODES,
        "security_review_status": "clean",
        "license_review_status": "reviewed",
        "decision": "candidate",
    }
    if mode in LOCAL_RESEARCH_MODES:
        source_yaml["research_dossier"] = research_dossier

    (d / "SOURCE.yaml").write_text(yaml.dump(source_yaml))

    if with_all:
        (d / "README.md").write_text("# readme")
        (d / "ATTRIBUTION.md").write_text("# attribution")
        if mode in VENDORED_MODES:
            (d / "AUDIT.md").write_text("# audit")
            (d / "FILE_MANIFEST.json").write_text("{}")
            upstream = d / "upstream"
            upstream.mkdir()
            (upstream / "main.py").write_text("print('hello')")
    return d


def run_validator(sources_dir: Path) -> tuple[int, str]:
    """Run validate_source_manifests.main() against a custom sources dir."""
    import io
    from contextlib import redirect_stdout
    import validate_source_manifests as vm

    original = vm.SOURCES_DIR
    vm.SOURCES_DIR = sources_dir
    buf = io.StringIO()
    exit_code = 0
    try:
        with redirect_stdout(buf):
            vm.main()
    except SystemExit as e:
        exit_code = e.code or 0
    finally:
        vm.SOURCES_DIR = original
    return exit_code, buf.getvalue()


# ── Constant checks ───────────────────────────────────────────────────────────

def test_vendored_modes_include_snapshot():
    assert "vendored-snapshot" in VENDORED_MODES


def test_vendored_modes_include_selected_subsystem():
    assert "selected-subsystem" in VENDORED_MODES


def test_local_research_modes():
    assert "local-research-only" in LOCAL_RESEARCH_MODES


def test_reference_modes():
    assert "reference-only" in REFERENCE_MODES


def test_required_vendored_extra_has_audit():
    assert "AUDIT.md" in REQUIRED_VENDORED_EXTRA


def test_required_vendored_extra_has_manifest():
    assert "FILE_MANIFEST.json" in REQUIRED_VENDORED_EXTRA


# ── read_source_data ──────────────────────────────────────────────────────────

def test_read_source_data_returns_mode(tmp_path):
    d = tmp_path / "s"
    d.mkdir()
    (d / "SOURCE.yaml").write_text("import_mode: local-research-only\n")
    data = read_source_data(d / "SOURCE.yaml")
    assert data["import_mode"] == "local-research-only"


def test_read_source_data_returns_empty_on_error(tmp_path):
    d = tmp_path / "s"
    d.mkdir()
    (d / "SOURCE.yaml").write_text("not: valid: yaml: [")
    assert read_source_data(d / "SOURCE.yaml") == {}


# ── Flat vendored source ──────────────────────────────────────────────────────

def test_flat_vendored_source_passes(tmp_path):
    make_source(tmp_path, "good-lib", "vendored-snapshot")
    code, out = run_validator(tmp_path)
    assert code == 0
    assert "OK" in out


def test_flat_vendored_missing_audit_fails(tmp_path):
    d = make_source(tmp_path, "bad-lib", "vendored-snapshot")
    (d / "AUDIT.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "AUDIT.md" in out


def test_flat_vendored_missing_manifest_fails(tmp_path):
    d = make_source(tmp_path, "bad-lib", "vendored-snapshot")
    (d / "FILE_MANIFEST.json").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "FILE_MANIFEST.json" in out


def test_flat_vendored_empty_upstream_fails(tmp_path):
    import shutil
    d = make_source(tmp_path, "bad-lib", "vendored-snapshot")
    shutil.rmtree(d / "upstream")
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "upstream" in out


def test_flat_vendored_missing_attribution_fails(tmp_path):
    d = make_source(tmp_path, "bad-lib", "vendored-snapshot")
    (d / "ATTRIBUTION.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "ATTRIBUTION.md" in out


# ── Nested vendored source ────────────────────────────────────────────────────

def test_nested_vendored_source_passes(tmp_path):
    make_source(tmp_path, "noshowly", "vendored-snapshot", nested=True)
    code, out = run_validator(tmp_path)
    assert code == 0


def test_nested_vendored_missing_audit_fails(tmp_path):
    d = make_source(tmp_path, "noshowly", "vendored-snapshot", nested=True)
    (d / "AUDIT.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "AUDIT.md" in out


# ── Nested local-research-only source ────────────────────────────────────────

def test_nested_local_research_only_passes(tmp_path):
    make_source(tmp_path, "scrapers", "local-research-only", nested=True)
    code, out = run_validator(tmp_path)
    assert code == 0


def test_nested_local_research_only_missing_attribution_fails(tmp_path):
    d = make_source(tmp_path, "scrapers", "local-research-only", nested=True)
    (d / "ATTRIBUTION.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "ATTRIBUTION.md" in out


def test_nested_local_research_only_missing_dossier_field_fails(tmp_path):
    d = make_source(
        tmp_path, "scrapers", "local-research-only",
        nested=True, research_dossier=""
    )
    # Rewrite SOURCE.yaml without research_dossier key
    data = yaml.safe_load((d / "SOURCE.yaml").read_text())
    data.pop("research_dossier", None)
    (d / "SOURCE.yaml").write_text(yaml.dump(data))
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "research_dossier" in out


def test_local_research_only_with_upstream_fails(tmp_path):
    """local-research-only must NOT have upstream/ code committed."""
    d = make_source(tmp_path, "scrapers", "local-research-only", nested=True)
    upstream = d / "upstream"
    upstream.mkdir()
    (upstream / "main.py").write_text("# unlicensed")
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "upstream" in out


# ── Nested reference-only source ─────────────────────────────────────────────

def test_nested_reference_only_passes(tmp_path):
    make_source(tmp_path, "profile", "reference-only", nested=True)
    code, out = run_validator(tmp_path)
    assert code == 0


def test_nested_reference_only_missing_attribution_fails(tmp_path):
    d = make_source(tmp_path, "profile", "reference-only", nested=True)
    (d / "ATTRIBUTION.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "ATTRIBUTION.md" in out


def test_reference_only_no_audit_required(tmp_path):
    """reference-only must NOT require AUDIT.md."""
    make_source(tmp_path, "ref-lib", "reference-only")
    # Confirm no AUDIT.md was created
    d = tmp_path / "ref-lib"
    assert not (d / "AUDIT.md").exists()
    code, _ = run_validator(tmp_path)
    assert code == 0


def test_reference_only_no_upstream_required(tmp_path):
    """reference-only must NOT require upstream/."""
    make_source(tmp_path, "ref-lib", "reference-only")
    d = tmp_path / "ref-lib"
    assert not (d / "upstream").exists()
    code, _ = run_validator(tmp_path)
    assert code == 0


# ── Intermediate directory (not a source) ────────────────────────────────────

def test_intermediate_directory_not_treated_as_source(tmp_path):
    """A directory like sources/people/ that has no SOURCE.yaml is not a source."""
    # Create a nested source under people/person3/github/repo/
    nested_dir = tmp_path / "people" / "person3" / "github" / "my-repo"
    nested_dir.mkdir(parents=True)
    source_yaml = {
        "functional_name": "my-repo",
        "import_mode": "reference-only",
        "pinned_commit": "abc123",
        "license": "NOT-FOUND",
        "license_file_verified": False,
        "security_review_status": "not-required",
        "license_review_status": "reviewed",
        "decision": "reference-only",
    }
    (nested_dir / "SOURCE.yaml").write_text(yaml.dump(source_yaml))
    (nested_dir / "README.md").write_text("# readme")
    (nested_dir / "ATTRIBUTION.md").write_text("# attribution")

    # The intermediate directories (people/, person3/, github/) have no SOURCE.yaml
    # and must not cause errors
    code, out = run_validator(tmp_path)
    assert code == 0


# ── Missing SOURCE.yaml ───────────────────────────────────────────────────────

def test_missing_source_yaml_not_discovered(tmp_path):
    """Directories without SOURCE.yaml are ignored by rglob discovery."""
    d = tmp_path / "mystery-lib"
    d.mkdir()
    (d / "README.md").write_text("# readme")
    # No SOURCE.yaml → not discovered → no error (not a known source)
    code, out = run_validator(tmp_path)
    assert code == 0


# ── Counter output ────────────────────────────────────────────────────────────

def test_output_shows_mode_counts(tmp_path):
    make_source(tmp_path, "v-lib", "vendored-snapshot")
    make_source(tmp_path, "l-lib", "local-research-only", nested=True)
    make_source(tmp_path, "r-lib", "reference-only")
    code, out = run_validator(tmp_path)
    assert code == 0
    assert "vendored" in out
    assert "local-research-only" in out
    assert "reference-only" in out
