#!/usr/bin/env python3
"""
CI validation: check that all directories under sources/ have required files.
Exit 1 if any required file is missing.
"""

from __future__ import annotations

import sys
from pathlib import Path

REPO_ROOT = Path(__file__).parent.parent.parent
SOURCES_DIR = REPO_ROOT / "sources"

REQUIRED_FILES = ["SOURCE.yaml", "ATTRIBUTION.md", "AUDIT.md", "README.md"]


def main() -> None:
    errors: list[str] = []

    for source_dir in sorted(SOURCES_DIR.iterdir()):
        if not source_dir.is_dir() or source_dir.name == "__pycache__":
            continue
        if source_dir.name == "README.md":
            continue

        for required in REQUIRED_FILES:
            if not (source_dir / required).exists():
                errors.append(f"MISSING: {source_dir.name}/{required}")

        upstream = source_dir / "upstream"
        if not upstream.exists():
            errors.append(f"MISSING: {source_dir.name}/upstream/")

    if errors:
        print("Source manifest validation FAILED:")
        for e in errors:
            print(f"  {e}")
        sys.exit(1)
    else:
        print(f"Source manifest validation: OK ({sum(1 for d in SOURCES_DIR.iterdir() if d.is_dir())} sources checked)")


if __name__ == "__main__":
    main()
