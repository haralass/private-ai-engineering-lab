#!/usr/bin/env python3
"""
CI validation: check that all directories under sources/ have required files.

Reference-only sources (no LICENSE found, import_mode=reference-only) are not
vendored — they intentionally have no upstream/ dir or AUDIT.md.

Vendored sources (vendored-snapshot, selected-subsystem) require the full set.

Exit 1 if any required file is missing.
"""

from __future__ import annotations

import sys
from pathlib import Path

import yaml

REPO_ROOT = Path(__file__).parent.parent.parent
SOURCES_DIR = REPO_ROOT / "sources"

REQUIRED_ALL = ["SOURCE.yaml", "README.md"]
REQUIRED_VENDORED = ["ATTRIBUTION.md", "AUDIT.md", "FILE_MANIFEST.json"]
VENDORED_MODES = {"vendored-snapshot", "selected-subsystem", "clean-room-reimplementation"}


def read_import_mode(source_dir: Path) -> str:
    try:
        data = yaml.safe_load((source_dir / "SOURCE.yaml").read_text())
        return data.get("import_mode", "vendored-snapshot")
    except Exception:
        return "vendored-snapshot"


def main() -> None:
    errors: list[str] = []
    checked = 0

    for source_dir in sorted(SOURCES_DIR.iterdir()):
        if not source_dir.is_dir() or source_dir.name in ("__pycache__", "README.md"):
            continue

        checked += 1
        mode = read_import_mode(source_dir)

        for required in REQUIRED_ALL:
            if not (source_dir / required).exists():
                errors.append(f"MISSING: {source_dir.name}/{required}")

        if mode in VENDORED_MODES:
            for required in REQUIRED_VENDORED:
                if not (source_dir / required).exists():
                    errors.append(f"MISSING: {source_dir.name}/{required}")

            upstream = source_dir / "upstream"
            if not upstream.exists() or not any(upstream.iterdir()):
                errors.append(f"MISSING or EMPTY: {source_dir.name}/upstream/")
        else:
            # reference-only: must have ATTRIBUTION.md explaining the no-license decision
            if not (source_dir / "ATTRIBUTION.md").exists():
                errors.append(f"MISSING: {source_dir.name}/ATTRIBUTION.md")

    if errors:
        print("Source manifest validation FAILED:")
        for e in errors:
            print(f"  {e}")
        sys.exit(1)
    else:
        print(f"Source manifest validation: OK ({checked} sources checked)")


if __name__ == "__main__":
    main()
