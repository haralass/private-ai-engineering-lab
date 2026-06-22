"""Tests for validate_source_manifests.py"""

from __future__ import annotations

import sys
from pathlib import Path

import pytest
import yaml

sys.path.insert(0, str(Path(__file__).parent.parent))
from validate_source_manifests import read_import_mode, REQUIRED_ALL, REQUIRED_VENDORED, VENDORED_MODES


def make_source(tmp_path: Path, name: str, mode: str, with_all: bool = True) -> Path:
    d = tmp_path / name
    d.mkdir()
    source_yaml = {
        "functional_name": name,
        "import_mode": mode,
        "source_url": "https://github.com/example/repo",
        "pinned_commit": "abc123",
        "license": "MIT" if mode != "reference-only" else "NOT-FOUND",
    }
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


# ── read_import_mode ──────────────────────────────────────────────────────────

def test_reads_vendored_mode(tmp_path):
    d = make_source(tmp_path, "my-lib", "vendored-snapshot")
    assert read_import_mode(d) == "vendored-snapshot"


def test_reads_reference_only_mode(tmp_path):
    d = make_source(tmp_path, "ref-lib", "reference-only")
    assert read_import_mode(d) == "reference-only"


def test_defaults_to_vendored_on_error(tmp_path):
    d = tmp_path / "broken"
    d.mkdir()
    (d / "SOURCE.yaml").write_text("not: valid: yaml: [")
    assert read_import_mode(d) == "vendored-snapshot"


# ── required fields ───────────────────────────────────────────────────────────

def test_vendored_modes_set():
    assert "vendored-snapshot" in VENDORED_MODES
    assert "selected-subsystem" in VENDORED_MODES
    assert "reference-only" not in VENDORED_MODES


def test_required_vendored_has_audit():
    assert "AUDIT.md" in REQUIRED_VENDORED


def test_required_vendored_has_manifest():
    assert "FILE_MANIFEST.json" in REQUIRED_VENDORED


# ── integration: run the validator on synthetic sources/ tree ─────────────────

def run_validator(sources_dir: Path) -> tuple[int, str]:
    """Run the validator subprocess against a custom sources dir."""
    import subprocess, os
    env = os.environ.copy()
    script = Path(__file__).parent.parent / "validate_source_manifests.py"
    # Patch SOURCES_DIR by temporarily monkey-patching via env var is complex;
    # instead call main() directly after patching the module-level constant.
    import validate_source_manifests as vm
    original = vm.SOURCES_DIR
    vm.SOURCES_DIR = sources_dir
    import io
    from contextlib import redirect_stdout
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


def test_valid_vendored_source_passes(tmp_path):
    make_source(tmp_path, "good-lib", "vendored-snapshot")
    code, out = run_validator(tmp_path)
    assert code == 0
    assert "OK" in out


def test_valid_reference_only_passes(tmp_path):
    make_source(tmp_path, "ref-lib", "reference-only")
    code, out = run_validator(tmp_path)
    assert code == 0


def test_vendored_missing_audit_fails(tmp_path):
    d = make_source(tmp_path, "bad-lib", "vendored-snapshot")
    (d / "AUDIT.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "AUDIT.md" in out


def test_vendored_missing_upstream_fails(tmp_path):
    d = make_source(tmp_path, "bad-lib", "vendored-snapshot")
    import shutil
    shutil.rmtree(d / "upstream")
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "upstream" in out


def test_reference_only_without_upstream_passes(tmp_path):
    d = make_source(tmp_path, "ref-lib", "reference-only")
    # reference-only sources must NOT have upstream/ required
    assert not (d / "upstream").exists()
    code, _ = run_validator(tmp_path)
    assert code == 0


def test_reference_only_missing_attribution_fails(tmp_path):
    d = make_source(tmp_path, "ref-lib", "reference-only")
    (d / "ATTRIBUTION.md").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "ATTRIBUTION.md" in out


def test_missing_source_yaml_fails(tmp_path):
    d = make_source(tmp_path, "broken-lib", "vendored-snapshot")
    (d / "SOURCE.yaml").unlink()
    code, out = run_validator(tmp_path)
    assert code == 1
    assert "SOURCE.yaml" in out
