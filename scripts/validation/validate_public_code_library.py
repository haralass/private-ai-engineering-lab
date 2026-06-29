#!/usr/bin/env python3
"""Validate the public-code-library research collection.

The validator checks structure, reproducible counts, link integrity, and source
governance contradictions. It does not try to prove every research conclusion;
it catches the regression classes that make this lab unsafe or confusing.
"""

from __future__ import annotations

import json
import re
import sys
from collections import Counter
from pathlib import Path
from urllib.parse import unquote, urlparse

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

ALLOWED_ACTIONS = {
    None,
    "clone",
    "reference-only",
    "metadata-only",
    "local-research-only",
}

SAFE_ACTIONS = {"reference-only", "metadata-only", "local-research-only"}
UNSAFE_LICENSE_VALUES = {
    "NONE",
    "NOASSERTION",
    "UNLICENSED",
    "RESTRICTED",
    "UNKNOWN",
    "NOT-FOUND",
    "Codrops",
    "FSL-1.1-ALv2",
    "AGPL-3.0",
}

UNRESTRICTED_REUSE_VALUES = {
    "directly reusable code",
    "adaptable implementation pattern",
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

EXPECTED_COUNT_LABELS = {
    "Source records": "source_records",
    "Vendored snapshots": "vendored_snapshots",
    "Selected subsystems": "selected_subsystems",
    "Local-research-only sources": "local_research_only",
    "Reference-only sources": "reference_only",
    "Public-code manifest repositories": "manifest_repositories",
    "Public-code full dossiers": "full_dossiers",
    "Public-code summary-only entries": "summary_only_manifest_entries",
    "Public-code candidate entries": "candidate_pool_total",
    "Manifest repositories": "manifest_repositories",
    "Full repository dossiers": "full_dossiers",
    "Summary-only manifest entries": "summary_only_manifest_entries",
    "Candidate pool entries": "candidate_pool_total",
}


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


def repo_full_name(repo: dict) -> str:
    return f"{repo.get('owner')}/{repo.get('repository')}"


def source_counts() -> dict[str, int]:
    source_files = list((REPO_ROOT / "sources").rglob("SOURCE.yaml"))
    modes: Counter[str] = Counter()
    for path in source_files:
        try:
            source = load_yaml(path)
        except ValueError:
            continue
        modes[source.get("import_mode", "vendored-snapshot")] += 1
    return {
        "source_records": len(source_files),
        "vendored_snapshots": modes["vendored-snapshot"],
        "selected_subsystems": modes["selected-subsystem"],
        "local_research_only": modes["local-research-only"],
        "reference_only": modes["reference-only"],
    }


def extract_markdown_links(text: str) -> list[str]:
    links = re.findall(r"(?<!!)\[[^\]]+\]\(([^)]+)\)", text)
    images = re.findall(r"!\[[^\]]*\]\(([^)]+)\)", text)
    return links + images


def validate_markdown_links(errors: list[str]) -> None:
    for md_path in [REPO_ROOT / "README.md", *LIB.rglob("*.md")]:
        text = md_path.read_text()
        for raw_target in extract_markdown_links(text):
            target = raw_target.strip().split()[0]
            if not target or target.startswith("#"):
                continue
            parsed = urlparse(target)
            if parsed.scheme in {"http", "https", "mailto"}:
                continue
            if parsed.scheme and parsed.scheme != "file":
                continue
            path_part = unquote(parsed.path if parsed.scheme == "file" else target.split("#", 1)[0])
            if not path_part:
                continue
            linked = Path(path_part)
            if not linked.is_absolute():
                linked = (md_path.parent / linked).resolve()
            if not linked.exists():
                rel = md_path.relative_to(REPO_ROOT)
                errors.append(f"{rel}: broken Markdown link target {target!r}")


def validate_hardcoded_counts(
    errors: list[str], summary: dict[str, int | dict[str, int]]
) -> None:
    generated_counts = {**source_counts()}
    for key in (
        "manifest_repositories",
        "full_dossiers",
        "summary_only_manifest_entries",
        "candidate_pool_total",
    ):
        generated_counts[key] = int(summary[key])  # type: ignore[arg-type]

    for path in [REPO_ROOT / "README.md", REPO_ROOT / "docs" / "CURRENT_STATE.md"]:
        text = path.read_text()
        for label, count_key in EXPECTED_COUNT_LABELS.items():
            pattern = rf"\|\s*{re.escape(label)}\s*\|\s*\**(\d+)\**\s*\|"
            match = re.search(pattern, text)
            if match and int(match.group(1)) != generated_counts[count_key]:
                errors.append(
                    f"{path.relative_to(REPO_ROOT)}: count mismatch for {label}: "
                    f"declared={match.group(1)} expected={generated_counts[count_key]}"
                )


def parse_licensing_report(path: Path) -> dict[str, tuple[str, str, str]]:
    rows: dict[str, tuple[str, str, str]] = {}
    if not path.exists():
        return rows
    for line in path.read_text().splitlines():
        if not line.startswith("| "):
            continue
        cells = [cell.strip() for cell in line.strip().strip("|").split("|")]
        if len(cells) < 4 or cells[0] in {"Repository", "---"}:
            continue
        rows[cells[0]] = (cells[1], cells[2], cells[3])
    return rows


def validate_licensing_report(errors: list[str], repos: list[dict]) -> None:
    report = parse_licensing_report(LIB / "synthesis" / "licensing-and-provenance.md")
    for repo in repos:
        name = repo_full_name(repo)
        if name not in report:
            errors.append(f"licensing-and-provenance.md: missing manifest repository {name}")
            continue
        report_license, _, report_action = report[name]
        manifest_license = str(repo.get("license"))
        if report_license != manifest_license:
            errors.append(
                f"licensing-and-provenance.md: license mismatch for {name}: "
                f"report={report_license!r} manifest={manifest_license!r}"
            )
        if manifest_license in UNSAFE_LICENSE_VALUES and "Study/reference only" not in report_action:
            errors.append(
                f"licensing-and-provenance.md: unsafe license for {name} lacks study/reference action"
            )


def validate_manifest_license_policy(errors: list[str], repos: list[dict]) -> None:
    for repo in repos:
        name = repo_full_name(repo)
        license_value = str(repo.get("license"))
        reuse = str(repo.get("reuse_classification", ""))
        if license_value in UNSAFE_LICENSE_VALUES and reuse in UNRESTRICTED_REUSE_VALUES:
            errors.append(
                f"manifest.yaml: {name} has unsafe license {license_value} but reuse_classification {reuse!r}"
            )
        if license_value in {"NONE", "NOASSERTION", "UNLICENSED", "RESTRICTED"}:
            risks = str(repo.get("known_risks", "")).lower()
            if not any(word in risks for word in ("reference", "do not copy", "study")):
                errors.append(
                    f"manifest.yaml: {name} unsafe license {license_value} lacks explicit safe known_risks"
                )


def validate_candidate_policy(
    errors: list[str], candidates: list[dict], repos: list[dict]
) -> Counter[str]:
    manifest_license_by_repo = {repo_full_name(repo): str(repo.get("license")) for repo in repos}
    candidate_status_counts: Counter[str] = Counter()

    for entry in candidates:
        if not isinstance(entry, dict):
            errors.append("candidate-pool.yaml: candidate entry is not a mapping")
            continue

        repo = entry.get("repo", "<unknown>")
        status = entry.get("status")
        action = entry.get("action")
        license_value = entry.get("license_flag") or manifest_license_by_repo.get(repo)

        if status not in ALLOWED_CANDIDATE_STATUSES:
            errors.append(f"{repo}: invalid status {status!r}")
        else:
            candidate_status_counts[status] += 1

        if action not in ALLOWED_ACTIONS:
            errors.append(f"{repo}: invalid action {action!r}")

        if status == "needs-verification" and action in {"clone", "reject"}:
            errors.append(f"{repo}: needs-verification cannot use action {action!r}")
        if status == "rejected" and action not in {None, "metadata-only", "reference-only"}:
            errors.append(f"{repo}: rejected candidate cannot use action {action!r}")

        if license_value in UNSAFE_LICENSE_VALUES and action not in SAFE_ACTIONS:
            errors.append(
                f"{repo}: unsafe license {license_value} requires reference-only/local-research-only action"
            )

        note = str(entry.get("note", "")).lower()
        if "reference-only" in note and action != "reference-only":
            errors.append(f"{repo}: note says reference-only but action is {action!r}")

    return candidate_status_counts


def validate_source_license_matrix(errors: list[str]) -> None:
    matrix_path = REPO_ROOT / "source-catalog" / "license-matrix.yaml"
    if not matrix_path.exists():
        return
    matrix = load_yaml(matrix_path)
    entries = matrix.get("license_matrix", matrix.get("sources", matrix))
    if not isinstance(entries, dict):
        errors.append("source-catalog/license-matrix.yaml: expected source mapping")
        return
    for name, entry in entries.items():
        if not isinstance(entry, dict):
            continue
        license_value = str(entry.get("license"))
        if license_value in UNSAFE_LICENSE_VALUES:
            if entry.get("vendoring_allowed") is True or entry.get("copy_allowed") is True:
                errors.append(
                    f"source-catalog/license-matrix.yaml: {name} unsafe license {license_value} "
                    "cannot allow vendoring/copying"
                )
        if entry.get("file_present") is False and entry.get("vendoring_allowed") is True:
            errors.append(
                f"source-catalog/license-matrix.yaml: {name} has no root license file but allows vendoring"
            )


def validate_duplicate_canonical_reports(errors: list[str]) -> None:
    active_names = {
        "top-30-lab-assets.md",
        "licensing-and-provenance.md",
        "repository-shortlist.md",
        "useful-assets-catalogue.md",
    }
    for path in LIB.rglob("*.md"):
        if "archive" in path.relative_to(LIB).parts:
            continue
        if path.name in active_names and path.parent != LIB / "synthesis":
            errors.append(
                f"Duplicate active canonical report outside synthesis/: {path.relative_to(LIB)}"
            )
    if (LIB / "reports").exists():
        errors.append("Legacy reports/ directory remains; use synthesis/")


def validate_source_policy(errors: list[str]) -> None:
    policy = REPO_ROOT / "docs" / "SOURCE_POLICY.md"
    text = policy.read_text()
    contradictions = [
        "Commit clean snapshot",
        "commit the snapshot",
        "git clone --depth 1 --branch",
        "`vendored-snapshot` | Repository is small enough",
    ]
    for phrase in contradictions:
        if phrase in text:
            errors.append(f"docs/SOURCE_POLICY.md: source policy contradiction remains: {phrase!r}")
    required = [
        "Use **local-research-only** for external code-level research",
        "Do not vendor a full repository merely for archival purposes",
        "reference-only",
    ]
    for phrase in required:
        if phrase not in text:
            errors.append(f"docs/SOURCE_POLICY.md: missing required governance phrase: {phrase!r}")


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
    valid_repos: list[dict] = []
    for repo in repos:
        if not isinstance(repo, dict):
            errors.append("manifest.yaml: repository entry is not a mapping")
            continue
        valid_repos.append(repo)
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

    candidate_status_counts = validate_candidate_policy(errors, candidates, valid_repos)

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

    summary = {
        "manifest_repositories": len(repos),
        "full_dossiers": full_dossiers,
        "summary_only_manifest_entries": summary_only,
        "candidate_pool_total": len(candidates),
        "candidate_status_counts": dict(sorted(candidate_status_counts.items())),
    }

    validate_manifest_license_policy(errors, valid_repos)
    validate_licensing_report(errors, valid_repos)
    validate_source_license_matrix(errors)
    validate_duplicate_canonical_reports(errors)
    validate_markdown_links(errors)
    validate_hardcoded_counts(errors, summary)
    validate_source_policy(errors)

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
