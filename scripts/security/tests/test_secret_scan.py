"""Tests for secret_scan.py"""

from __future__ import annotations

import json
import sys
import tempfile
from pathlib import Path

import pytest

sys.path.insert(0, str(Path(__file__).parent.parent))
from secret_scan import is_excluded, scan_directory, scan_file


# ── is_excluded ──────────────────────────────────────────────────────────────

def test_exact_path_excluded(tmp_path):
    f = tmp_path / "foo" / "bar.py"
    f.parent.mkdir()
    f.touch()
    assert is_excluded(f, tmp_path, ["foo/bar.py"])


def test_directory_prefix_excluded(tmp_path):
    f = tmp_path / "sources" / "my-lib" / "upstream" / "secret.py"
    f.parent.mkdir(parents=True)
    f.touch()
    assert is_excluded(f, tmp_path, ["sources/my-lib/upstream"])


def test_glob_star_excluded(tmp_path):
    f = tmp_path / "sources" / "any-lib" / "upstream" / "deep" / "file.py"
    f.parent.mkdir(parents=True)
    f.touch()
    assert is_excluded(f, tmp_path, ["sources/*/upstream"])


def test_non_matching_not_excluded(tmp_path):
    f = tmp_path / "components" / "firewall" / "src" / "engine.py"
    f.parent.mkdir(parents=True)
    f.touch()
    assert not is_excluded(f, tmp_path, ["sources/*/upstream"])


# ── scan_file ─────────────────────────────────────────────────────────────────

def test_detects_github_pat(tmp_path):
    f = tmp_path / "creds.py"
    f.write_text('token = "ghp_' + "A" * 36 + '"')
    findings = scan_file(f, "high")
    assert len(findings) == 1
    assert "GitHub personal access token" in findings[0]["label"]


def test_detects_private_key(tmp_path):
    f = tmp_path / "key.pem"
    # Split to avoid triggering the scanner on this source file itself
    content = "-----BEGIN RSA PRIVATE KEY" + "-----\nMIIEfake..."
    f.write_text(content)
    findings = scan_file(f, "high")
    assert any("Private key" in x["label"] for x in findings)


def test_detects_aws_key(tmp_path):
    f = tmp_path / "config.yml"
    # AKIA prefix split to avoid self-detection
    f.write_text("aws_access_key_id: AKIA" + "IOSFODNN7EXAMPLE")
    findings = scan_file(f, "high")
    assert len(findings) >= 1


def test_clean_file_no_findings(tmp_path):
    f = tmp_path / "clean.py"
    f.write_text("def greet(name):\n    return f'Hello {name}'")
    assert scan_file(f, "high") == []


def test_non_text_extension_skipped(tmp_path):
    f = tmp_path / "binary.exe"
    f.write_bytes(b"ghp_" + b"A" * 36)
    assert scan_file(f, "high") == []


def test_openai_key_detected(tmp_path):
    f = tmp_path / "config.py"
    f.write_text('OPENAI_KEY = "sk-' + "a" * 32 + '"')
    findings = scan_file(f, "high")
    assert any("API key" in x["label"] for x in findings)


# ── scan_directory ────────────────────────────────────────────────────────────

def test_scan_directory_finds_secrets(tmp_path):
    bad = tmp_path / "bad.py"
    bad.write_text('x = "ghp_' + "B" * 36 + '"')
    findings = scan_directory(tmp_path, "high")
    assert len(findings) >= 1


def test_scan_directory_excludes_path(tmp_path):
    upstream = tmp_path / "sources" / "some-lib" / "upstream"
    upstream.mkdir(parents=True)
    secret_file = upstream / "test_fixture.json"
    secret_file.write_text('{"key": "ghp_' + "C" * 36 + '"}')

    findings = scan_directory(tmp_path, "high", excludes=["sources/*/upstream"])
    assert len(findings) == 0


def test_scan_directory_still_finds_outside_exclude(tmp_path):
    upstream = tmp_path / "sources" / "some-lib" / "upstream"
    upstream.mkdir(parents=True)
    (upstream / "test_fixture.json").write_text('{"key": "ghp_' + "C" * 36 + '"}')

    our_code = tmp_path / "components" / "firewall"
    our_code.mkdir(parents=True)
    (our_code / "leaked.py").write_text('token = "ghp_' + "D" * 36 + '"')

    findings = scan_directory(tmp_path, "high", excludes=["sources/*/upstream"])
    assert len(findings) == 1
    assert "components" in findings[0]["file"]


def test_skip_dirs_respected(tmp_path):
    pycache = tmp_path / "__pycache__"
    pycache.mkdir()
    (pycache / "secret.py").write_text('x = "ghp_' + "E" * 36 + '"')
    findings = scan_directory(tmp_path, "high")
    assert len(findings) == 0


def test_medium_threshold_detects_api_pattern(tmp_path):
    f = tmp_path / "config.py"
    f.write_text('api_key = "supersecretkeyvalue12345678"')
    findings = scan_directory(tmp_path, "medium")
    assert len(findings) >= 1
