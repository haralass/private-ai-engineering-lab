#!/usr/bin/env python3
"""
CI validation: check that all SOURCE.yaml files under sources/ have required files.

Discovers SOURCE.yaml files recursively (supports both flat and nested structures):
  sources/<functional-name>/SOURCE.yaml
  sources/people/personN/github/<repo>/SOURCE.yaml

Import mode requirements:

  vendored-snapshot / selected-subsystem / clean-room-reimplementation:
    Required: SOURCE.yaml, README.md, ATTRIBUTION.md, AUDIT.md, FILE_MANIFEST.json
    Required: non-empty upstream/ directory
    Must NOT have upstream/ missing or empty.

  local-research-only:
    Required: SOURCE.yaml, README.md, ATTRIBUTION.md
    Required: research_dossier field in SOURCE.yaml pointing to a research file
    Must NOT have upstream/ directory (no code committed for unlicensed sources).

  reference-only / submodule:
    Required: SOURCE.yaml, README.md, ATTRIBUTION.md
    No AUDIT.md, FILE_MANIFEST.json, or upstream/ required.

Exit 1 if any required file is missing. Exit 0 on success.
"""

from __future__ import annotations

import sys
from pathlib import Path

import yaml

REPO_ROOT = Path(__file__).parent.parent.parent
SOURCES_DIR = REPO_ROOT / "sources"

REQUIRED_ALL = ["SOURCE.yaml", "README.md", "ATTRIBUTION.md"]
REQUIRED_VENDORED_EXTRA = ["AUDIT.md", "FILE_MANIFEST.json"]

VENDORED_MODES = {"vendored-snapshot", "selected-subsystem", "clean-room-reimplementation"}
LOCAL_RESEARCH_MODES = {"local-research-only"}
REFERENCE_MODES = {"reference-only", "submodule"}


def read_source_data(yaml_file: Path) -> dict:
    try:
        data = yaml.safe_load(yaml_file.read_text()) or {}
        return data if isinstance(data, dict) else {}
    except Exception:
        return {}


def main() -> None:
    errors: list[str] = []
    counts = {"vendored": 0, "local-research-only": 0, "reference-only": 0, "other": 0}

    for yaml_file in sorted(SOURCES_DIR.rglob("SOURCE.yaml")):
        # Skip SOURCE.yaml files inside upstream/ (they belong to the vendored source itself)
        if "upstream" in yaml_file.parts:
            continue

        source_dir = yaml_file.parent
        # Use a display name relative to SOURCES_DIR for error messages
        rel = source_dir.relative_to(SOURCES_DIR)
        label = str(rel)

        data = read_source_data(yaml_file)
        mode = data.get("import_mode", "vendored-snapshot")

        # ── All modes: SOURCE.yaml, README.md, ATTRIBUTION.md ─────────────────
        for required in REQUIRED_ALL:
            if not (source_dir / required).exists():
                errors.append(f"MISSING: {label}/{required}")

        if mode in VENDORED_MODES:
            counts["vendored"] += 1
            # Additional required files
            for required in REQUIRED_VENDORED_EXTRA:
                if not (source_dir / required).exists():
                    errors.append(f"MISSING: {label}/{required}")
            # Non-empty upstream/
            upstream = source_dir / "upstream"
            if not upstream.exists() or not any(upstream.iterdir()):
                errors.append(f"MISSING or EMPTY: {label}/upstream/")

        elif mode in LOCAL_RESEARCH_MODES:
            counts["local-research-only"] += 1
            # Must have research_dossier field
            if not data.get("research_dossier"):
                errors.append(
                    f"MISSING field: {label}/SOURCE.yaml must have 'research_dossier'"
                )
            # Must NOT have upstream/ (no code committed for unlicensed sources)
            upstream = source_dir / "upstream"
            if upstream.exists() and any(upstream.iterdir()):
                errors.append(
                    f"FORBIDDEN: {label}/upstream/ exists for local-research-only source "
                    f"(no code may be committed without a license)"
                )

        elif mode in REFERENCE_MODES:
            counts["reference-only"] += 1
            # No extra requirements beyond REQUIRED_ALL

        else:
            counts["other"] += 1

    total = sum(counts.values())

    if errors:
        print("Source manifest validation FAILED:")
        for e in errors:
            print(f"  {e}")
        sys.exit(1)

    print(
        f"Source manifest validation: OK ({total} sources checked)\n"
        f"  vendored:            {counts['vendored']}\n"
        f"  local-research-only: {counts['local-research-only']}\n"
        f"  reference-only:      {counts['reference-only']}"
        + (f"\n  other:               {counts['other']}" if counts["other"] else "")
    )


if __name__ == "__main__":
    main()
