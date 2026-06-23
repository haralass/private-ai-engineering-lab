#!/usr/bin/env python3
"""
Source importer for private-ai-engineering-lab.

Usage:
    python import_source.py \\
      --url https://github.com/owner/repo \\
      --commit <SHA> \\
      --name <functional-name> \\
      [--label external|student-1|student-2] \\
      [--mode vendored-snapshot|selected-subsystem|reference-only] \\
      [--subsystem <path-within-repo>] \\
      [--license-override MIT]

Produces:
    sources/<functional-name>/
        upstream/       clean snapshot
        adapted/        (empty, ready for our changes)
        README.md
        SOURCE.yaml
        ATTRIBUTION.md
        AUDIT.md
        FILE_MANIFEST.json
"""

from __future__ import annotations

import argparse
import hashlib
import json
import shutil
import subprocess
import sys
import tempfile
from datetime import datetime, timezone
from pathlib import Path

import yaml

# Use the canonical secret scanner — single source of truth for detection patterns.
sys.path.insert(0, str(Path(__file__).parent.parent / "security"))
from secret_scan import scan_directory as _scan_directory

REPO_ROOT = Path(__file__).parent.parent.parent
SOURCES_DIR = REPO_ROOT / "sources"

# Directories always removed before import
ALWAYS_REMOVE = [
    "node_modules",
    ".git",
    "venv",
    ".venv",
    "env",
    "__pycache__",
    ".pytest_cache",
    ".mypy_cache",
    ".ruff_cache",
    "dist",
    "build",
    ".next",
    "out",
    "coverage",
    ".cache",
    "*.egg-info",
]

# File extensions that are build/binary artifacts
REMOVE_EXTENSIONS = {".pyc", ".pyo", ".pyd", ".log", ".DS_Store"}


def run(cmd: list[str], cwd: Path | None = None, check: bool = True) -> subprocess.CompletedProcess:
    return subprocess.run(cmd, cwd=cwd, capture_output=True, text=True, check=check)


def secret_scan(directory: Path) -> list[dict]:
    """Delegate to the canonical scanner in scripts/security/secret_scan.py."""
    raw = _scan_directory(directory, threshold="high")
    return [
        {
            "file": str(Path(f["file"]).relative_to(directory)),
            "label": f["label"],
            "line": f["line"],
            "match_preview": f["preview"],
        }
        for f in raw
    ]


def detect_license(repo_path: Path) -> tuple[str, bool]:
    """Detect license from LICENSE file. Returns (license_spdx, file_found)."""
    candidates = ["LICENSE", "LICENSE.txt", "LICENSE.md", "COPYING", "COPYING.txt"]
    license_map = {
        "MIT License": "MIT",
        "MIT": "MIT",
        "Apache License": "Apache-2.0",
        "Apache-2.0": "Apache-2.0",
        "GNU General Public License": "GPL",
        "BSD 2-Clause": "BSD-2-Clause",
        "BSD 3-Clause": "BSD-3-Clause",
        "ISC License": "ISC",
        "ISC": "ISC",
    }
    for name in candidates:
        path = repo_path / name
        if path.exists():
            content = path.read_text(errors="ignore")
            for keyword, spdx in license_map.items():
                if keyword in content:
                    return spdx, True
            return "UNKNOWN-CHECK-MANUALLY", True
    return "NOT-FOUND", False


def build_file_manifest(directory: Path) -> dict:
    """Build a manifest of all files with SHA-256 hashes."""
    files = {}
    for path in sorted(directory.rglob("*")):
        if not path.is_file():
            continue
        rel = str(path.relative_to(directory))
        try:
            digest = hashlib.sha256(path.read_bytes()).hexdigest()
            size = path.stat().st_size
        except (OSError, PermissionError):
            digest = "ERROR"
            size = -1
        files[rel] = {"sha256": digest, "size": size}
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "file_count": len(files),
        "files": files,
    }


def remove_ignored(directory: Path, removed_log: list[str]) -> None:
    for name in ALWAYS_REMOVE:
        for match in directory.rglob(name):
            if match.exists():
                removed_log.append(str(match.relative_to(directory)))
                if match.is_dir():
                    shutil.rmtree(match, ignore_errors=True)
                else:
                    match.unlink(missing_ok=True)
    for path in list(directory.rglob("*")):
        if path.is_file() and path.suffix.lower() in REMOVE_EXTENSIONS:
            removed_log.append(str(path.relative_to(directory)))
            path.unlink(missing_ok=True)


def import_source(
    url: str,
    commit: str,
    functional_name: str,
    label: str = "external",
    mode: str = "vendored-snapshot",
    subsystem: str | None = None,
    license_override: str | None = None,
    reviewed_secrets: bool = False,
) -> None:
    out_dir = SOURCES_DIR / functional_name
    if out_dir.exists():
        print(f"ERROR: {out_dir} already exists. Remove it first or use a different name.", file=sys.stderr)
        sys.exit(1)

    upstream_dir = out_dir / "upstream"
    adapted_dir = out_dir / "adapted"

    print(f"[import] Cloning {url} at {commit}…")
    removed_log: list[str] = []
    secret_findings: list[dict] = []
    with tempfile.TemporaryDirectory() as tmp:
        tmp_path = Path(tmp) / "clone"

        # Shallow clone
        run(["git", "clone", "--depth", "1", url, str(tmp_path)])

        # Checkout the pinned commit — abort on failure, never silently use HEAD
        try:
            run(["git", "fetch", "--depth", "1", "origin", commit], cwd=tmp_path)
            run(["git", "checkout", commit], cwd=tmp_path)
        except subprocess.CalledProcessError as e:
            print(
                f"ERROR: Could not checkout requested commit {commit}.\n"
                f"       {e.stderr.strip()}\n"
                "       Aborting — do not import from an unverified commit.",
                file=sys.stderr,
            )
            sys.exit(1)

        # Verify actual commit matches what was requested
        actual_commit = run(["git", "rev-parse", "HEAD"], cwd=tmp_path).stdout.strip()
        if not actual_commit.startswith(commit) and not commit.startswith(actual_commit[:len(commit)]):
            print(
                f"ERROR: SHA mismatch — requested {commit!r} but got {actual_commit!r}.\n"
                "       This may indicate the commit was rewritten or the SHA is wrong.",
                file=sys.stderr,
            )
            sys.exit(1)
        print(f"[import] Verified commit: {actual_commit}")

        # Detect license before removing anything
        license_id, license_found = detect_license(tmp_path)
        if license_override:
            license_id = license_override
            # Manual override: license_file_verified stays False — it was not auto-detected
            license_file_verified = False
            print(f"[import] License MANUALLY overridden to: {license_id} (not auto-detected from LICENSE file)")
        else:
            license_file_verified = license_found
            print(f"[import] Detected license: {license_id} (file found: {license_found})")

        # reference-only mode requires no license file (that's the whole point)
        if mode != "reference-only" and not license_found and not license_override:
            print(
                "ERROR: No LICENSE file found and no --license-override given.\n"
                "       Use --mode reference-only for repos without a clear license,\n"
                "       or provide --license-override with the correct SPDX identifier.",
                file=sys.stderr,
            )
            sys.exit(1)

        # Select subsystem if specified
        source_root = tmp_path
        if subsystem:
            source_root = tmp_path / subsystem
            if not source_root.exists():
                print(f"ERROR: Subsystem path {subsystem} not found in repo.", file=sys.stderr)
                sys.exit(1)

        # Remove internal .git and ignored directories
        git_dir = tmp_path / ".git"
        if git_dir.exists():
            shutil.rmtree(git_dir)
            removed_log.append(".git/")

        remove_ignored(source_root, removed_log)

        # Secret scan (always run, even for reference-only, to document findings)
        print("[import] Running secret scan…")
        secret_findings = secret_scan(source_root)
        if secret_findings:
            print(f"\nSECRET SCAN FINDINGS ({len(secret_findings)} potential secrets found):", file=sys.stderr)
            for f in secret_findings:
                print(f"  {f['file']}:{f['line']} [{f['label']}] {f['match_preview']}", file=sys.stderr)
            if reviewed_secrets:
                print(
                    "\n[import] --reviewed-secrets set: proceeding after human review.\n"
                    "         Document each finding and its disposition in AUDIT.md.",
                    file=sys.stderr,
                )
            else:
                print(
                    "\nImport aborted due to potential secrets.\n"
                    "Review findings. If they are test fixtures or false positives,\n"
                    "re-run with --reviewed-secrets and document in AUDIT.md.",
                    file=sys.stderr,
                )
                sys.exit(1)
        else:
            print("[import] Secret scan: clean")

        # For reference-only: catalog entry only, no code copied
        if mode == "reference-only":
            out_dir.mkdir(parents=True)
            manifest = {"file_count": 0, "files": {}, "note": "reference-only — no code vendored"}
        else:
            # Copy to upstream/
            upstream_dir.mkdir(parents=True)
            shutil.copytree(str(source_root), str(upstream_dir), dirs_exist_ok=True)
            adapted_dir.mkdir(parents=True)

            # Copy LICENSE
            for name in ["LICENSE", "LICENSE.txt", "LICENSE.md"]:
                src = tmp_path / name
                if src.exists():
                    shutil.copy(src, out_dir / "LICENSE")
                    break

            # Build manifest
            manifest = build_file_manifest(upstream_dir)

    # Write SOURCE.yaml
    now = datetime.now(timezone.utc).isoformat()
    source_yaml = {
        "display_name": url.split("/")[-1],
        "functional_name": functional_name,
        "source_url": url,
        "source_label": label,
        "upstream_owner": url.split("/")[-2] if "/" in url else "",
        "upstream_repository": url.split("/")[-1] if "/" in url else "",
        "pinned_commit": actual_commit,
        "retrieved_at": now,
        "license": license_id,
        "license_file_verified": license_file_verified,
        "license_override_applied": bool(license_override),
        "import_mode": mode,
        "snapshot_path": f"sources/{functional_name}/upstream" if mode != "reference-only" else "",
        "adapted_path": f"sources/{functional_name}/adapted" if mode != "reference-only" else "",
        "subsystem": subsystem or "",
        "removed_paths": removed_log[:50],
        "local_modifications": [],
        "security_review_status": "pending",
        "license_review_status": "pending",
        "execution_allowed": False,
        "decision": "candidate",
        "notes": "license manually overridden — verify upstream license before use" if license_override else "",
    }

    (out_dir / "SOURCE.yaml").write_text(yaml.dump(source_yaml, default_flow_style=False, sort_keys=False))

    # Write ATTRIBUTION.md
    license_note = (
        f"License: {license_id} (MANUALLY OVERRIDDEN — not auto-detected from LICENSE file)"
        if license_override
        else f"License: {license_id}"
    )
    attribution = f"""# Attribution

Source: {url}
Commit: {actual_commit}
Retrieved: {now}
{license_note}

{"This snapshot is used in accordance with the upstream license. Original copyright belongs to the upstream authors. See LICENSE file for full license text." if mode != "reference-only" else "Reference-only: no code vendored. Study only."}
"""
    (out_dir / "ATTRIBUTION.md").write_text(attribution)

    # Write AUDIT.md — be honest about secret scan status
    if secret_findings and reviewed_secrets:
        secret_status = (
            f"- [ ] Automated scan: {len(secret_findings)} potential secrets found — "
            "reviewed with --reviewed-secrets flag\n"
            "- [ ] **Each finding must be documented below with disposition**\n"
            "- [ ] Manual review of configuration files"
        )
    else:
        secret_status = (
            "- [x] Automated scan: clean — 0 findings at import time\n"
            "- [ ] Manual review of configuration files"
        )

    license_check = (
        f"- [ ] License MANUALLY set to {license_id} — verify upstream license before use"
        if license_override
        else f"- [ ] LICENSE file verified\n- [ ] License permits vendoring: {license_id}\n- [ ] Copyright notices preserved"
    )

    audit = f"""# Audit — {functional_name}

Source: {url}
Commit: {actual_commit}
Import mode: {mode}
Audited: (pending)

## License review
{license_check}

## Secret scan
{secret_status}

## Prompt injection review
- [ ] No files with AI-visible instruction injections
- [ ] No CLAUDE.md or .claude/ directory that could hijack agent behavior
- [ ] Hooks reviewed for safety

## Security review
- [ ] No dangerous hooks enabled
- [ ] No automatic network calls on import
- [ ] Dependencies reviewed

## Notes

(Pending manual review)
"""
    (out_dir / "AUDIT.md").write_text(audit)

    # Write README
    readme = f"""# {functional_name}

Source: [{url.split('/')[-2]}/{url.split('/')[-1]}]({url})
Pinned commit: `{actual_commit}`
License: {license_id}
Import mode: {mode}
Status: candidate (pending review)

## What was imported

{f'Subsystem: `{subsystem}`' if subsystem else 'Full repository'}

See `SOURCE.yaml` for full metadata and `AUDIT.md` for review status.

## upstream/

Clean snapshot at the pinned commit. Do not modify.

## adapted/

Our changes, improvements, and integrations go here.
"""
    (out_dir / "README.md").write_text(readme)

    # Write FILE_MANIFEST.json
    (out_dir / "FILE_MANIFEST.json").write_text(json.dumps(manifest, indent=2))

    # Create placeholder adapted/.gitkeep (vendored modes only)
    if mode != "reference-only":
        (adapted_dir / ".gitkeep").touch()

    print(f"\n[import] Done!")
    print(f"  Directory:   {out_dir}")
    print(f"  Commit:      {actual_commit}")
    print(f"  License:     {license_id}")
    print(f"  Files:       {manifest['file_count']}")
    print(f"  Removed:     {len(removed_log)} paths")
    print(f"\nNext steps:")
    print(f"  1. Review {out_dir}/AUDIT.md and complete the checklist")
    print(f"  2. Update source-catalog/import-status.yaml")
    print(f"  3. Update source-catalog/license-matrix.yaml")
    print(f"  4. Commit the snapshot on a feature branch")


def main() -> None:
    parser = argparse.ArgumentParser(description="Import a source repository into the lab")
    parser.add_argument("--url", required=True, help="GitHub URL of the repository")
    parser.add_argument("--commit", required=True, help="Pinned commit SHA or tag")
    parser.add_argument("--name", required=True, help="Functional name for the source directory")
    parser.add_argument("--label", default="external", choices=["external", "student-1", "student-2"])
    parser.add_argument(
        "--mode",
        default="vendored-snapshot",
        choices=["vendored-snapshot", "selected-subsystem", "reference-only", "clean-room-reimplementation", "submodule"],
    )
    parser.add_argument("--subsystem", default=None, help="Subdirectory within the repo to import")
    parser.add_argument("--license-override", default=None, help="Override license detection (use SPDX identifier)")
    parser.add_argument(
        "--reviewed-secrets",
        action="store_true",
        help="Confirm that secret scan findings have been manually reviewed and are false positives or test fixtures",
    )
    args = parser.parse_args()

    import_source(
        url=args.url,
        commit=args.commit,
        functional_name=args.name,
        label=args.label,
        mode=args.mode,
        subsystem=args.subsystem,
        license_override=args.license_override,
        reviewed_secrets=args.reviewed_secrets,
    )


if __name__ == "__main__":
    main()
