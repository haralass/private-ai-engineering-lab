#!/usr/bin/env python3
"""
CI validation: sources/*/SOURCE.yaml is the canonical source of truth.

Every source MUST appear in all three catalog files:
  source-catalog/sources.yaml          (functional_name, upstream_repo, label)
  source-catalog/import-status.yaml   (imports or reference_only)
  source-catalog/license-matrix.yaml  (license_matrix.<name>)

Fields cross-checked for consistency:
  - functional_name present in all three files
  - source_label matches sources.yaml label
  - pinned_commit prefix matches import-status.yaml (vendored sources only)
  - license matches license-matrix.yaml (when matrix has a non-unknown entry)
  - import_mode consistent: vendored sources in imports[], reference-only in reference_only[]

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
REFERENCE_MODES = {"reference-only", "submodule"}


def load_yaml(path: Path) -> dict | list:
    try:
        data = yaml.safe_load(path.read_text()) or {}
        if data is None:
            return {}
        return data
    except Exception as e:
        print(f"ERROR loading {path}: {e}", file=sys.stderr)
        sys.exit(1)


def main() -> None:
    errors: list[str] = []

    # ── Load canonical SOURCE.yaml for every source ──────────────────────────
    source_data: dict[str, dict] = {}
    for source_dir in sorted(SOURCES_DIR.iterdir()):
        if not source_dir.is_dir() or source_dir.name in ("__pycache__",):
            continue
        yaml_file = source_dir / "SOURCE.yaml"
        if not yaml_file.exists():
            continue
        data = load_yaml(yaml_file)
        if isinstance(data, dict):
            source_data[source_dir.name] = data

    if not source_data:
        print("No SOURCE.yaml files found — skipping catalog consistency check")
        sys.exit(0)

    # ── Load catalog files ────────────────────────────────────────────────────
    sources_catalog_path = CATALOG_DIR / "sources.yaml"
    import_status_path = CATALOG_DIR / "import-status.yaml"
    license_matrix_path = CATALOG_DIR / "license-matrix.yaml"

    sources_catalog = load_yaml(sources_catalog_path) if sources_catalog_path.exists() else {}
    import_status = load_yaml(import_status_path) if import_status_path.exists() else {}
    license_matrix = load_yaml(license_matrix_path) if license_matrix_path.exists() else {}

    # ── Build lookup maps ─────────────────────────────────────────────────────

    # sources.yaml: name → entry dict
    catalog_by_name: dict[str, dict] = {}
    if isinstance(sources_catalog, dict):
        for entry in sources_catalog.get("sources", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                catalog_by_name[entry["functional_name"]] = entry

    # import-status.yaml: name → entry dict (imports and reference_only merged)
    import_by_name: dict[str, dict] = {}
    import_mode_in_status: dict[str, str] = {}  # name → "vendored" | "reference-only"
    if isinstance(import_status, dict):
        for entry in import_status.get("imports", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                name = entry["functional_name"]
                import_by_name[name] = entry
                import_mode_in_status[name] = "vendored"
        for entry in import_status.get("reference_only", []):
            if isinstance(entry, dict) and "functional_name" in entry:
                name = entry["functional_name"]
                import_by_name[name] = entry
                import_mode_in_status[name] = "reference-only"

    # license-matrix.yaml: name → entry dict
    license_by_name: dict[str, dict] = {}
    if isinstance(license_matrix, dict):
        for name, data in license_matrix.get("license_matrix", {}).items():
            if isinstance(data, dict):
                license_by_name[name] = data

    # ── Check every source ────────────────────────────────────────────────────
    for name, source in source_data.items():
        mode = source.get("import_mode", "vendored-snapshot")
        is_vendored = mode in VENDORED_MODES
        is_ref = mode in REFERENCE_MODES
        pinned = source.get("pinned_commit", "")
        license_id = source.get("license", "")
        source_label = source.get("source_label", "")

        # ── 1. Must be in sources.yaml ─────────────────────────────────────
        if name not in catalog_by_name:
            errors.append(f"{name}: missing from source-catalog/sources.yaml")
        else:
            cat_entry = catalog_by_name[name]
            cat_label = cat_entry.get("label", "")
            if cat_label and source_label and cat_label != source_label:
                errors.append(
                    f"{name}: label mismatch — SOURCE.yaml={source_label!r} vs "
                    f"sources.yaml={cat_label!r}"
                )

        # ── 2. Must be in import-status.yaml ──────────────────────────────
        if name not in import_by_name:
            errors.append(f"{name}: missing from source-catalog/import-status.yaml")
        else:
            status_mode = import_mode_in_status.get(name, "")

            if is_vendored and status_mode != "vendored":
                errors.append(
                    f"{name}: import_mode={mode!r} (vendored) but found in "
                    f"reference_only[] in import-status.yaml"
                )
            if is_ref and status_mode != "reference-only":
                errors.append(
                    f"{name}: import_mode={mode!r} (reference-only) but found in "
                    f"imports[] in import-status.yaml"
                )

            # Pinned commit consistency for vendored sources
            if is_vendored:
                catalog_commit = import_by_name[name].get("pinned_commit", "")
                if catalog_commit and pinned:
                    if not (catalog_commit.startswith(pinned[:8]) or pinned.startswith(catalog_commit[:8])):
                        errors.append(
                            f"{name}: pinned_commit mismatch — SOURCE.yaml={pinned[:12]} vs "
                            f"import-status.yaml={catalog_commit[:12]}"
                        )

        # ── 3. Must be in license-matrix.yaml ─────────────────────────────
        if name not in license_by_name:
            errors.append(f"{name}: missing from source-catalog/license-matrix.yaml")
        else:
            matrix_license = license_by_name[name].get("license", "")
            # Only fail on a positive license mismatch
            if (
                matrix_license
                and matrix_license not in ("unknown", "NOT-FOUND", "")
                and license_id not in ("NOT-FOUND", "")
                and matrix_license != license_id
            ):
                errors.append(
                    f"{name}: license mismatch — SOURCE.yaml={license_id!r} vs "
                    f"license-matrix.yaml={matrix_license!r}"
                )

    # ── Report ────────────────────────────────────────────────────────────────
    if errors:
        print(f"Catalog consistency check FAILED ({len(errors)} error(s)):")
        for e in errors:
            print(f"  {e}")
        sys.exit(1)

    n_vendored = sum(1 for m in import_mode_in_status.values() if m == "vendored")
    n_ref = sum(1 for m in import_mode_in_status.values() if m == "reference-only")
    print(
        f"Catalog consistency: OK — {len(source_data)} sources checked\n"
        f"  sources.yaml:        {len(catalog_by_name)} entries\n"
        f"  import-status.yaml:  {n_vendored} vendored + {n_ref} reference-only = {len(import_by_name)}\n"
        f"  license-matrix.yaml: {len(license_by_name)} entries"
    )


if __name__ == "__main__":
    main()
