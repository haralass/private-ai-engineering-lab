#!/usr/bin/env python3
"""Validate the public-code-library research collection.

This script intentionally validates structure and provenance metadata, not the
truth of every research conclusion. It makes counts reproducible and catches the
cleanup regressions this collection is prone to: broken dossier links, stale
candidate counts, raw data mixed with synthesis, and uncontrolled statuses.
"""

from __future__ import annotations

import json
import sys
from collections import Counter
from pathlib import Path

import yaml

REPO_ROOT = Path(__file__).parent.parent.parent
LIB = REPO_ROOT / "research" / "public-code-library"

ALLOWED_CANDIDATE_STATUSES = {
    "accepted",
    "accepted-reference-only",
    "accepted-asset",
    "full-dossier",
    "summary-only",
    "needs-verification",
    "rejected",
    "superseded",
    "duplicate",
    "archived",
}

REQUIRED_CANONICAL_FILES = [
    "README.md",
    "manifest.yaml",
    "candidate-pool.yaml",
    "synthesis/top-30-lab-assets.md",
    "synthesis/licensing-and-provenance.md",
    "synthesis/repository-shortlist.md",
    "synthesis/useful-assets-catalogue.md",
    "professional-websites/catalogue.md",
    "professional-websites/verified-live-source-pairs.md",
    "rejected/rejected-and-unverified.md",
    "data/raw/README.md",
]

RAW_DATA_FILES = [
    "additional_candidates_raw.json",
    "all_profile_repos.json",
    "repo_metadata.json",
    "repo_shas.json",
]


def load_yaml(path: Path) -> dict:
    try:
        data = yaml.safe_load(path.read_text())
    except Exception as exc:  # pragma: no cover - message matters in CI
        raise ValueError(f"{path}: YAML parse failed: {exc}") from exc
    if not isinstance(data, dict):
        raise ValueError(f"{path}: expected a YAML mapping")
    return data


def load_json(path: Path) -> object:
    try:
        return json.loads(path.read_text())
    except Exception as exc:  # pragma: no cover - message matters in CI
        raise ValueError(f"{path}: JSON parse failed: {exc}") from exc


def validate() -> tuple[list[str], dict[str, int | dict[str, int]]]:
    errors: list[str] = []
    summary: dict[str, int | dict[str, int]] = {}

    if not LIB.exists():
        return [f"Missing library directory: {LIB}"], summary

    for rel in REQUIRED_CANONICAL_FILES:
        if not (LIB / rel).exists():
            errors.append(f"Missing canonical file: research/public-code-library/{rel}")

    for raw in RAW_DATA_FILES:
        if (LIB / raw).exists():
            errors.append(f"Raw data file remains at library root: {raw}")
        raw_path = LIB / "data" / "raw" / raw
        if not raw_path.exists():
            errors.append(f"Missing raw data file: data/raw/{raw}")
        elif raw_path.suffix == ".json":
            try:
                load_json(raw_path)
            except ValueError as exc:
                errors.append(str(exc))

    try:
        manifest = load_yaml(LIB / "manifest.yaml")
    except ValueError as exc:
        errors.append(str(exc))
        manifest = {"repositories": []}

    repos = manifest.get("repositories", [])
    if not isinstance(repos, list):
        errors.append("manifest.yaml: repositories must be a list")
        repos = []

    ids = [repo.get("id") for repo in repos if isinstance(repo, dict)]
    duplicate_ids = [item for item, count in Counter(ids).items() if item and count > 1]
    for repo_id in duplicate_ids:
        errors.append(f"manifest.yaml: duplicate repository id {repo_id}")

    full_dossiers = 0
    summary_only = 0
    for repo in repos:
        if not isinstance(repo, dict):
            errors.append("manifest.yaml: repository entry is not a mapping")
            continue
        repo_id = repo.get("id", "<missing-id>")
        doc = repo.get("analysis_document")
        status = repo.get("analysis_status")
        if doc:
            doc_path = REPO_ROOT / str(doc)
            if not doc_path.exists():
                errors.append(f"{repo_id}: analysis_document does not exist: {doc}")
            else:
                full_dossiers += 1
        else:
            if status not in {"summary-only", "pending"}:
                errors.append(
                    f"{repo_id}: missing analysis_document requires analysis_status "
                    "summary-only or pending"
                )
            else:
                summary_only += 1

    try:
        candidate_pool = load_yaml(LIB / "candidate-pool.yaml")
    except ValueError as exc:
        errors.append(str(exc))
        candidate_pool = {"candidate_pool": [], "summary": {}}

    candidates = candidate_pool.get("candidate_pool", [])
    if not isinstance(candidates, list):
        errors.append("candidate-pool.yaml: candidate_pool must be a list")
        candidates = []

    candidate_repos = [entry.get("repo") for entry in candidates if isinstance(entry, dict)]
    duplicate_candidates = [
        item for item, count in Counter(candidate_repos).items() if item and count > 1
    ]
    for repo_name in duplicate_candidates:
        errors.append(f"candidate-pool.yaml: duplicate candidate {repo_name}")

    candidate_status_counts: Counter[str] = Counter()
    for entry in candidates:
        if not isinstance(entry, dict):
            errors.append("candidate-pool.yaml: candidate entry is not a mapping")
            continue
        status = entry.get("status")
        if status not in ALLOWED_CANDIDATE_STATUSES:
            errors.append(f"{entry.get('repo', '<unknown>')}: invalid status {status!r}")
        else:
            candidate_status_counts[status] += 1

    declared = candidate_pool.get("summary", {})
    expected_summary = {
        "total_discovered": len(candidates),
        "accepted": candidate_status_counts["accepted"],
        "rejected": candidate_status_counts["rejected"],
        "needs_verification": candidate_status_counts["needs-verification"],
        "summary_only": candidate_status_counts["summary-only"],
    }
    for key, expected in expected_summary.items():
        if declared.get(key) != expected:
            errors.append(
                f"candidate-pool.yaml summary mismatch for {key}: "
                f"declared={declared.get(key)!r} expected={expected!r}"
            )

    if (LIB / "top-30-lab-assets.md").exists():
        errors.append("Duplicate active top-30-lab-assets.md remains at library root")
    if (LIB / "reports").exists():
        errors.append("Legacy reports/ directory remains; use synthesis/")

    summary = {
        "manifest_repositories": len(repos),
        "full_dossiers": full_dossiers,
        "summary_only_manifest_entries": summary_only,
        "candidate_pool_total": len(candidates),
        "candidate_status_counts": dict(sorted(candidate_status_counts.items())),
    }
    return errors, summary


def main() -> None:
    errors, summary = validate()
    if errors:
        print("Public code library validation FAILED:")
        for error in errors:
            print(f"  {error}")
        sys.exit(1)

    print("Public code library validation: OK")
    print(f"  manifest repositories: {summary['manifest_repositories']}")
    print(f"  full dossiers: {summary['full_dossiers']}")
    print(f"  summary-only manifest entries: {summary['summary_only_manifest_entries']}")
    print(f"  candidate pool total: {summary['candidate_pool_total']}")
    for status, count in summary["candidate_status_counts"].items():  # type: ignore[index]
        print(f"  candidates.{status}: {count}")


if __name__ == "__main__":
    main()
