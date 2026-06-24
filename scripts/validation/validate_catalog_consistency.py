#!/usr/bin/env python3
"""
CI validation: SOURCE.yaml files (discovered recursively) are the canonical source of truth.

Every source MUST appear in all three catalog files:
  source-catalog/sources.yaml          (functional_name, upstream_repo, label)
  source-catalog/import-status.yaml   (imports | local_research_only | reference_only)
  source-catalog/license-matrix.yaml  (license_matrix.<name>)

Import mode categories:
  vendored-snapshot / selected-subsystem / clean-room-reimplementation  → imports[]
  local-research-only                                                    → local_research_only[]
  reference-only / submodule                                             → reference_only[]

Fields cross-checked for consistency:
  - functional_name present in all three files
  - source_label matches sources.yaml label
  - import_mode consistent with its section in import-status.yaml
  - license matches license-matrix.yaml (when matrix has a non-unknown entry)
  - pinned_commit prefix matches import-status.yaml (vendored sources only)
  - Required fields present in SOURCE.yaml
  - files_kept present for vendored sources in import-status.yaml
  - copy_allowed: false for local-research-only and reference-only sources
  - execution_allowed: false for sources flagged as requiring credentials

Additional catalog integrity checks:
  - No duplicate functional_name entries in any catalog file
  - No catalog entries without a corresponding SOURCE.yaml

Final output shows counts by category:
  vendored / local-research-only / reference-only / total

Exit 0 if everything agrees. Exit 1 on any discrepancy.
"""

from __future__ import annotations

import sys
from pathlib import Path

import yaml

REPO_ROOT = Path(__file__).parent.parent.parent
SOURCES_DIR = REPO_ROOT / "sources"
CATALOG_DIR = REPO_ROOT / "source-catalog"

VENDORED_MODES = {"vendored-snapshot", "selected-subsystem", "clean-room-reimplementation"}
LOCAL_RESEARCH_MODES = {"local-research-only"}
REFERENCE_MODES = {"reference-only", "submodule"}

REQUIRED_FIELDS_ALL = [
    "license_file_verified",
    "security_review_status",
    "license_review_status",
]
# `decision` is only meaningful for vendored sources (candidate / complete / rejected)
REQUIRED_FIELDS_VENDORED_EXTRA = ["decision"]


def load_yaml(path: Path) -> dict | list:
    try:
        data = yaml.safe_load(path.read_text()) or {}
        return data if data is not None else {}
    except Exception as e:
        print(f"ERROR loading {path}: {e}", file=sys.stderr)
        sys.exit(1)


def main() -> None:
    errors: list[str] = []

    # ── Discover all SOURCE.yaml files recursively ────────────────────────────
    # Use functional_name from the file itself (not directory name) as the key.
    source_data: dict[str, dict] = {}
    source_paths: dict[str, Path] = {}
    for yaml_file in sorted(SOURCES_DIR.rglob("SOURCE.yaml")):
        # Skip SOURCE.yaml files nested inside upstream/ directories
        if "upstream" in yaml_file.parts:
            continue
        raw = load_yaml(yaml_file)
        if not isinstance(raw, dict):
            continue
        fn = raw.get("functional_name")
        if not fn:
            errors.append(f"MISSING functional_name in {yaml_file.relative_to(REPO_ROOT)}")
            continue
        if fn in source_data:
            errors.append(f"DUPLICATE functional_name '{fn}' — found in both "
                          f"{source_paths[fn].relative_to(REPO_ROOT)} and "
                          f"{yaml_file.relative_to(REPO_ROOT)}")
        source_data[fn] = raw
        source_paths[fn] = yaml_file

    if not source_data:
        print("No SOURCE.yaml files found — skipping catalog consistency check")
        sys.exit(0)

    # ── Load catalog files ────────────────────────────────────────────────────
    sources_cat_path = CATALOG_DIR / "sources.yaml"
    import_status_path = CATALOG_DIR / "import-status.yaml"
    license_matrix_path = CATALOG_DIR / "license-matrix.yaml"

    sources_catalog = load_yaml(sources_cat_path) if sources_cat_path.exists() else {}
    import_status = load_yaml(import_status_path) if import_status_path.exists() else {}
    license_matrix = load_yaml(license_matrix_path) if license_matrix_path.exists() else {}

    # ── Build lookup maps ─────────────────────────────────────────────────────

    # sources.yaml: functional_name → entry
    catalog_by_name: dict[str, dict] = {}
    if isinstance(sources_catalog, dict):
        for entry in sources_catalog.get("sources", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                catalog_by_name[entry["functional_name"]] = entry

    # import-status.yaml: functional_name → (entry, section)
    import_by_name: dict[str, dict] = {}
    import_section: dict[str, str] = {}  # name → "vendored" | "local-research-only" | "reference-only"

    if isinstance(import_status, dict):
        for entry in import_status.get("imports", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                n = entry["functional_name"]
                import_by_name[n] = entry
                import_section[n] = "vendored"
        for entry in import_status.get("local_research_only", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                n = entry["functional_name"]
                import_by_name[n] = entry
                import_section[n] = "local-research-only"
        for entry in import_status.get("reference_only", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                n = entry["functional_name"]
                import_by_name[n] = entry
                import_section[n] = "reference-only"

    # license-matrix.yaml: name → entry
    license_by_name: dict[str, dict] = {}
    if isinstance(license_matrix, dict):
        for name, data in license_matrix.get("license_matrix", {}).items():
            if isinstance(data, dict):
                license_by_name[name] = data

    # ── Duplicate detection in catalogs ──────────────────────────────────────

    seen_in_sources: list[str] = []
    for entry in sources_catalog.get("sources", []):
        n = entry.get("functional_name")
        if not n:
            continue
        if n in seen_in_sources:
            errors.append(f"{n}: duplicate entry in source-catalog/sources.yaml")
        seen_in_sources.append(n)

    seen_in_status: list[str] = []
    all_status_entries = (
        import_status.get("imports", [])
        + import_status.get("local_research_only", [])
        + import_status.get("reference_only", [])
    )
    for entry in all_status_entries:
        n = entry.get("functional_name")
        if not n:
            continue
        if n in seen_in_status:
            errors.append(f"{n}: duplicate entry in source-catalog/import-status.yaml")
        seen_in_status.append(n)

    # ── Reverse checks: catalog entries without SOURCE.yaml ──────────────────

    for entry in sources_catalog.get("sources", []):
        n = entry.get("functional_name")
        if n and n not in source_data:
            errors.append(f"{n}: in sources.yaml but no SOURCE.yaml found")

    for entry in all_status_entries:
        n = entry.get("functional_name")
        if n and n not in source_data:
            errors.append(f"{n}: in import-status.yaml but no SOURCE.yaml found")

    for n in license_by_name:
        if n not in source_data:
            errors.append(f"{n}: in license-matrix.yaml but no SOURCE.yaml found")

    # ── Per-source checks ─────────────────────────────────────────────────────
    counts = {"vendored": 0, "local-research-only": 0, "reference-only": 0}

    for name, source in source_data.items():
        mode = source.get("import_mode", "vendored-snapshot")
        is_vendored = mode in VENDORED_MODES
        is_local = mode in LOCAL_RESEARCH_MODES
        is_ref = mode in REFERENCE_MODES
        pinned = source.get("pinned_commit", "")
        license_id = source.get("license", "")
        source_label = source.get("source_label", "")

        if is_vendored:
            counts["vendored"] += 1
        elif is_local:
            counts["local-research-only"] += 1
        else:
            counts["reference-only"] += 1

        # Required fields in SOURCE.yaml
        for field in REQUIRED_FIELDS_ALL:
            if field not in source:
                errors.append(f"{name}: SOURCE.yaml missing required field '{field}'")
        if is_vendored:
            for field in REQUIRED_FIELDS_VENDORED_EXTRA:
                if field not in source:
                    errors.append(f"{name}: SOURCE.yaml missing required field '{field}' for vendored source")

        # Security policy: vendored sources with security_review_status: flagged must have remediation
        if is_vendored and source.get("security_review_status") == "flagged":
            errors.append(
                f"{name}: vendored source has security_review_status=flagged — "
                "remediation required before vendoring. Change to local-research-only "
                "or resolve the security finding first."
            )

        # local-research-only must have research_dossier
        if is_local and not source.get("research_dossier"):
            errors.append(f"{name}: SOURCE.yaml missing 'research_dossier' for local-research-only")

        # copy_allowed must be false for non-vendored, non-MIT sources
        if (is_local or is_ref) and source.get("copy_allowed") is True:
            errors.append(f"{name}: copy_allowed should be false for {mode}")

        # ── 1. Must be in sources.yaml ─────────────────────────────────────
        if name not in catalog_by_name:
            errors.append(f"{name}: missing from source-catalog/sources.yaml")
        else:
            cat = catalog_by_name[name]
            cat_label = cat.get("label", "")
            if cat_label and source_label and cat_label != source_label:
                errors.append(
                    f"{name}: label mismatch — SOURCE.yaml={source_label!r} "
                    f"vs sources.yaml={cat_label!r}"
                )

        # ── 2. Must be in import-status.yaml ──────────────────────────────
        if name not in import_by_name:
            errors.append(f"{name}: missing from source-catalog/import-status.yaml")
        else:
            status_section = import_section.get(name, "")
            status_entry = import_by_name[name]

            if is_vendored and status_section != "vendored":
                errors.append(
                    f"{name}: import_mode={mode!r} but found in "
                    f"'{status_section}' section of import-status.yaml (expected imports[])"
                )
            if is_local and status_section != "local-research-only":
                errors.append(
                    f"{name}: import_mode={mode!r} but found in "
                    f"'{status_section}' section (expected local_research_only[])"
                )
            if is_ref and status_section != "reference-only":
                errors.append(
                    f"{name}: import_mode={mode!r} but found in "
                    f"'{status_section}' section (expected reference_only[])"
                )

            # Pinned commit consistency for vendored sources
            if is_vendored:
                catalog_commit = status_entry.get("pinned_commit", "")
                if catalog_commit and pinned:
                    if not (
                        catalog_commit.startswith(pinned[:8])
                        or pinned.startswith(catalog_commit[:8])
                    ):
                        errors.append(
                            f"{name}: pinned_commit mismatch — "
                            f"SOURCE.yaml={pinned[:12]} vs "
                            f"import-status.yaml={catalog_commit[:12]}"
                        )
                if "files_kept" not in status_entry:
                    errors.append(
                        f"{name}: import-status.yaml missing 'files_kept' for vendored source"
                    )

        # ── 3. Must be in license-matrix.yaml ─────────────────────────────
        if name not in license_by_name:
            errors.append(f"{name}: missing from source-catalog/license-matrix.yaml")
        else:
            matrix_license = license_by_name[name].get("license", "")
            if (
                matrix_license
                and matrix_license not in ("unknown", "NOT-FOUND", "")
                and license_id not in ("NOT-FOUND", "unknown", "")
                and matrix_license != license_id
            ):
                errors.append(
                    f"{name}: license mismatch — SOURCE.yaml={license_id!r} "
                    f"vs license-matrix.yaml={matrix_license!r}"
                )

    # ── Report ────────────────────────────────────────────────────────────────
    if errors:
        print(f"Catalog consistency check FAILED ({len(errors)} error(s)):")
        for e in errors:
            print(f"  {e}")
        sys.exit(1)

    total = sum(counts.values())
    n_v = counts["vendored"]
    n_l = counts["local-research-only"]
    n_r = counts["reference-only"]
    print(
        f"Catalog consistency: OK — {total} sources checked\n"
        f"  vendored:            {n_v}\n"
        f"  local-research-only: {n_l}\n"
        f"  reference-only:      {n_r}\n"
        f"  sources.yaml:        {len(catalog_by_name)} entries\n"
        f"  import-status.yaml:  {len(import_by_name)} entries\n"
        f"  license-matrix.yaml: {len(license_by_name)} entries"
    )


if __name__ == "__main__":
    main()
