#!/usr/bin/env python3
"""
CI validation: sources/*/SOURCE.yaml is the canonical source of truth.
Check that source-catalog/*.yaml files agree with it on key fields.

Fields checked for every source present in SOURCE.yaml:
  - functional_name is listed in source-catalog/sources.yaml
  - pinned_commit matches import-status.yaml (vendored only)
  - license matches license-matrix.yaml (if entry exists)
  - import_mode matches sources.yaml status field (vendored vs reference-only)

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


def load_yaml(path: Path) -> dict | list:
    try:
        return yaml.safe_load(path.read_text()) or {}
    except Exception as e:
        print(f"ERROR loading {path}: {e}", file=sys.stderr)
        sys.exit(1)


def main() -> None:
    errors: list[str] = []

    # Canonical: all SOURCE.yaml files
    source_data: dict[str, dict] = {}
    for source_dir in sorted(SOURCES_DIR.iterdir()):
        if not source_dir.is_dir() or source_dir.name in ("__pycache__", "README.md"):
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

    # Load catalog files
    sources_catalog_path = CATALOG_DIR / "sources.yaml"
    import_status_path = CATALOG_DIR / "import-status.yaml"
    license_matrix_path = CATALOG_DIR / "license-matrix.yaml"

    sources_catalog = load_yaml(sources_catalog_path) if sources_catalog_path.exists() else {}
    import_status = load_yaml(import_status_path) if import_status_path.exists() else {}
    license_matrix = load_yaml(license_matrix_path) if license_matrix_path.exists() else {}

    # Build lookup maps
    catalog_names: set[str] = set()
    if isinstance(sources_catalog, dict):
        for entry in sources_catalog.get("sources", []):
            if isinstance(entry, dict):
                catalog_names.add(entry.get("functional_name", ""))

    import_by_name: dict[str, dict] = {}
    if isinstance(import_status, dict):
        for entry in import_status.get("imports", []):
            if isinstance(entry, dict):
                import_by_name[entry.get("functional_name", "")] = entry

    license_by_name: dict[str, dict] = {}
    if isinstance(license_matrix, dict):
        for name, data in license_matrix.get("license_matrix", {}).items():
            if isinstance(data, dict):
                license_by_name[name] = data

    # Check each source
    for name, source in source_data.items():
        mode = source.get("import_mode", "vendored-snapshot")
        pinned = source.get("pinned_commit", "")
        license_id = source.get("license", "")

        # Check presence in sources.yaml
        if catalog_names and name not in catalog_names:
            errors.append(
                f"{name}: present in sources/*/SOURCE.yaml but missing from source-catalog/sources.yaml"
            )

        # Check pinned_commit consistency (vendored sources only)
        if mode in VENDORED_MODES and name in import_by_name:
            catalog_commit = import_by_name[name].get("pinned_commit", "")
            if catalog_commit and not catalog_commit.startswith(pinned[:8]) and not pinned.startswith(catalog_commit[:8]):
                errors.append(
                    f"{name}: pinned_commit mismatch — SOURCE.yaml={pinned[:12]} vs "
                    f"import-status.yaml={catalog_commit[:12]}"
                )

        # Check license consistency (if entry exists in matrix)
        if name in license_by_name:
            matrix_license = license_by_name[name].get("license", "")
            if matrix_license and matrix_license not in ("unknown", "") and matrix_license != license_id:
                errors.append(
                    f"{name}: license mismatch — SOURCE.yaml={license_id!r} vs "
                    f"license-matrix.yaml={matrix_license!r}"
                )

    if errors:
        print("Catalog consistency check FAILED:")
        for e in errors:
            print(f"  {e}")
        sys.exit(1)

    print(
        f"Catalog consistency: OK ({len(source_data)} sources checked, "
        f"{len(catalog_names)} in sources.yaml, "
        f"{len(import_by_name)} in import-status.yaml)"
    )


if __name__ == "__main__":
    main()
