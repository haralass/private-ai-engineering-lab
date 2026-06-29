"""Tests for validate_public_code_library.py."""

from __future__ import annotations

import sys
from pathlib import Path

import yaml

sys.path.insert(0, str(Path(__file__).parent.parent))
import validate_public_code_library as vpl


def write(path: Path, text: str = "") -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(text)


def write_yaml(path: Path, data: dict) -> None:
    write(path, yaml.safe_dump(data, sort_keys=False))


def make_lab(tmp_path: Path, monkeypatch) -> Path:
    root = tmp_path / "lab"
    lib = root / "research" / "public-code-library"

    monkeypatch.setattr(vpl, "REPO_ROOT", root)
    monkeypatch.setattr(vpl, "LIB", lib)

    write(root / "sources" / "example-source" / "SOURCE.yaml", yaml.safe_dump({
        "functional_name": "example-source",
        "import_mode": "reference-only",
        "license": "NOT-FOUND",
    }))

    write(root / "README.md", """# Lab

| Area | Count |
|---|---:|
| Source records | 1 |
| Vendored snapshots | 0 |
| Selected subsystems | 0 |
| Local-research-only sources | 0 |
| Reference-only sources | 1 |
| Public-code manifest repositories | 1 |
| Public-code full dossiers | 1 |
| Public-code summary-only entries | 0 |
| Public-code candidate entries | 1 |
""")

    write(root / "docs" / "CURRENT_STATE.md", """# Current State

| Metric | Count |
|---|---:|
| Manifest repositories | 1 |
| Full repository dossiers | 1 |
| Summary-only manifest entries | 0 |
| Candidate pool entries | 1 |
""")

    write(root / "docs" / "SOURCE_POLICY.md", """# Source Governance Policy

Use **local-research-only** for external code-level research.
Use `reference-only` for unclear licenses.
Do not vendor a full repository merely for archival purposes.
""")

    for rel in vpl.REQUIRED_CANONICAL_FILES:
        write(lib / rel, "# placeholder\n")

    for raw in vpl.RAW_DATA_FILES:
        write(lib / "data" / "raw" / raw, "{}")

    write_yaml(lib / "manifest.yaml", {
        "repositories": [{
            "id": "example-repo",
            "owner": "owner",
            "repository": "repo",
            "license": "MIT",
            "reuse_classification": "directly reusable code",
            "analysis_document": "research/public-code-library/repositories/owner--repo.md",
            "known_risks": "Preserve notices.",
        }]
    })
    write(lib / "repositories" / "owner--repo.md", "# owner/repo\n")
    write_yaml(lib / "candidate-pool.yaml", {
        "candidate_pool": [{
            "repo": "owner/repo",
            "status": "accepted",
            "action": "clone",
            "priority": "high",
        }],
        "summary": {
            "total_discovered": 1,
            "accepted": 1,
            "rejected": 0,
            "needs_verification": 0,
            "summary_only": 0,
        },
    })
    write(lib / "synthesis" / "licensing-and-provenance.md", """# Licensing

| Repository | License | Notes | Action |
|---|---|---|---|
| owner/repo | MIT | Preserve notices. | License appears permissive; preserve notices and verify before copying code. |
""")
    write_yaml(root / "source-catalog" / "license-matrix.yaml", {
        "license_matrix": {
            "example-source": {
                "license": "NOT-FOUND",
                "file_present": False,
                "vendoring_allowed": False,
                "copy_allowed": False,
            }
        }
    })
    return root


def test_minimal_valid_lab_passes(tmp_path, monkeypatch):
    make_lab(tmp_path, monkeypatch)

    errors, summary = vpl.validate()

    assert errors == []
    assert summary["manifest_repositories"] == 1
    assert summary["candidate_pool_total"] == 1


def test_unsafe_license_clone_candidate_fails(tmp_path, monkeypatch):
    make_lab(tmp_path, monkeypatch)
    candidate_path = vpl.LIB / "candidate-pool.yaml"
    data = yaml.safe_load(candidate_path.read_text())
    data["candidate_pool"][0]["license_flag"] = "UNLICENSED"
    candidate_path.write_text(yaml.safe_dump(data, sort_keys=False))

    errors, _ = vpl.validate()

    assert any("unsafe license UNLICENSED" in error for error in errors)


def test_needs_verification_reject_action_fails(tmp_path, monkeypatch):
    make_lab(tmp_path, monkeypatch)
    candidate_path = vpl.LIB / "candidate-pool.yaml"
    data = yaml.safe_load(candidate_path.read_text())
    data["candidate_pool"][0]["status"] = "needs-verification"
    data["candidate_pool"][0]["action"] = "reject"
    data["summary"] = {
        "total_discovered": 1,
        "accepted": 0,
        "rejected": 0,
        "needs_verification": 1,
        "summary_only": 0,
    }
    candidate_path.write_text(yaml.safe_dump(data, sort_keys=False))

    errors, _ = vpl.validate()

    assert any("needs-verification cannot use action 'reject'" in error for error in errors)


def test_broken_markdown_link_fails(tmp_path, monkeypatch):
    root = make_lab(tmp_path, monkeypatch)
    write(root / "README.md", (root / "README.md").read_text() + "\n[missing](docs/NOPE.md)\n")

    errors, _ = vpl.validate()

    assert any("broken Markdown link" in error for error in errors)


def test_hardcoded_count_mismatch_fails(tmp_path, monkeypatch):
    root = make_lab(tmp_path, monkeypatch)
    text = (root / "README.md").read_text().replace("| Source records | 1 |", "| Source records | 2 |")
    write(root / "README.md", text)

    errors, _ = vpl.validate()

    assert any("count mismatch for Source records" in error for error in errors)


def test_licensing_report_mismatch_fails(tmp_path, monkeypatch):
    make_lab(tmp_path, monkeypatch)
    text = (vpl.LIB / "synthesis" / "licensing-and-provenance.md").read_text()
    write(vpl.LIB / "synthesis" / "licensing-and-provenance.md", text.replace("| owner/repo | MIT |", "| owner/repo | NONE |"))

    errors, _ = vpl.validate()

    assert any("license mismatch for owner/repo" in error for error in errors)


def test_source_policy_contradiction_fails(tmp_path, monkeypatch):
    root = make_lab(tmp_path, monkeypatch)
    write(root / "docs" / "SOURCE_POLICY.md", (root / "docs" / "SOURCE_POLICY.md").read_text() + "\nCommit clean snapshot\n")

    errors, _ = vpl.validate()

    assert any("source policy contradiction" in error for error in errors)


def test_no_root_license_cannot_allow_vendoring(tmp_path, monkeypatch):
    root = make_lab(tmp_path, monkeypatch)
    matrix_path = root / "source-catalog" / "license-matrix.yaml"
    data = yaml.safe_load(matrix_path.read_text())
    data["license_matrix"]["example-source"]["vendoring_allowed"] = True
    matrix_path.write_text(yaml.safe_dump(data, sort_keys=False))

    errors, _ = vpl.validate()

    assert any("has no root license file but allows vendoring" in error for error in errors)
