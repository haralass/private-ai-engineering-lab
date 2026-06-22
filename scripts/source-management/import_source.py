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
import os
import re
import shutil
import subprocess
import sys
import tempfile
from datetime import datetime, timezone
from pathlib import Path

import yaml

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

# Secret patterns (high confidence)
SECRET_PATTERNS = [
    (r"(?i)(?:api[_-]?key|apikey)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-]{20,})", "API key"),
    (r"(?i)(?:secret|password|passwd|pwd)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-@!#$%^&*]{8,})", "Password/secret"),
    (r"sk-[a-zA-Z0-9]{32,}", "OpenAI/Anthropic API key"),
    (r"ghp_[a-zA-Z0-9]{36}", "GitHub personal access token"),
    (r"gho_[a-zA-Z0-9]{36}", "GitHub OAuth token"),
    (r"-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----", "Private key"),
    (r"(?i)aws_access_key_id\s*[:=]\s*([A-Z0-9]{20})", "AWS access key"),
    (r"(?i)aws_secret_access_key\s*[:=]\s*([a-zA-Z0-9/+]{40})", "AWS secret key"),
    (r"(?i)(?:token|bearer)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-\.]{20,})", "Token"),
]


def run(cmd: list[str], cwd: Path | None = None, check: bool = True) -> subprocess.CompletedProcess:
    return subprocess.run(cmd, cwd=cwd, capture_output=True, text=True, check=check)


def secret_scan(directory: Path) -> list[dict]:
    """Scan all text files for secret patterns. Returns list of findings."""
    findings = []
    text_extensions = {
        ".py", ".js", ".ts", ".jsx", ".tsx", ".sh", ".bash", ".yaml", ".yml",
        ".json", ".toml", ".ini", ".cfg", ".env", ".md", ".txt", ".conf",
        ".rb", ".go", ".rs", ".java", ".cs", ".php", ".html", ".css",
    }
    for path in directory.rglob("*"):
        if not path.is_file():
            continue
        if path.suffix.lower() not in text_extensions and path.suffix:
            continue
        try:
            content = path.read_text(errors="ignore")
        except (OSError, PermissionError):
            continue
        for pattern, label in SECRET_PATTERNS:
            for match in re.finditer(pattern, content):
                findings.append({
                    "file": str(path.relative_to(directory)),
                    "label": label,
                    "line": content[: match.start()].count("\n") + 1,
                    "match_preview": match.group(0)[:60] + ("…" if len(match.group(0)) > 60 else ""),
                })
    return findings


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
) -> None:
    out_dir = SOURCES_DIR / functional_name
    if out_dir.exists():
        print(f"ERROR: {out_dir} already exists. Remove it first or use a different name.", file=sys.stderr)
        sys.exit(1)

    upstream_dir = out_dir / "upstream"
    adapted_dir = out_dir / "adapted"

    print(f"[import] Cloning {url} at {commit}…")
    with tempfile.TemporaryDirectory() as tmp:
        tmp_path = Path(tmp) / "clone"

        # Shallow clone
        run(["git", "clone", "--depth", "1", url, str(tmp_path)])
        # Checkout the pinned commit
        try:
            run(["git", "fetch", "--depth", "1", "origin", commit], cwd=tmp_path)
            run(["git", "checkout", commit], cwd=tmp_path)
        except subprocess.CalledProcessError:
            print(f"[import] Could not checkout {commit} — using HEAD")

        # Get actual commit SHA
        result = run(["git", "rev-parse", "HEAD"], cwd=tmp_path)
        actual_commit = result.stdout.strip()

        # Detect license before removing anything
        license_id, license_found = detect_license(tmp_path)
        if license_override:
            license_id = license_override
            print(f"[import] License override: {license_id}")
        else:
            print(f"[import] Detected license: {license_id} (file found: {license_found})")

        if not license_found and not license_override:
            print(
                "ERROR: No LICENSE file found and no --license-override given.\n"
                "       Set import mode to reference-only or provide --license-override.",
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
        removed_log: list[str] = []
        git_dir = tmp_path / ".git"
        if git_dir.exists():
            shutil.rmtree(git_dir)
            removed_log.append(".git/")

        remove_ignored(source_root, removed_log)

        # Secret scan
        print("[import] Running secret scan…")
        findings = secret_scan(source_root)
        if findings:
            print(f"\nSECRET SCAN FINDINGS ({len(findings)} potential secrets found):", file=sys.stderr)
            for f in findings:
                print(f"  {f['file']}:{f['line']} [{f['label']}] {f['match_preview']}", file=sys.stderr)
            print(
                "\nImport aborted due to potential secrets.\n"
                "Review the findings, remove secrets, then re-run with the cleaned repo.",
                file=sys.stderr,
            )
            sys.exit(1)
        print("[import] Secret scan: clean")

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
        "license_file_verified": license_found,
        "import_mode": mode,
        "snapshot_path": f"sources/{functional_name}/upstream",
        "adapted_path": f"sources/{functional_name}/adapted",
        "subsystem": subsystem or "",
        "removed_paths": removed_log[:50],
        "local_modifications": [],
        "security_review_status": "pending",
        "license_review_status": "pending",
        "execution_allowed": False,
        "decision": "candidate",
        "notes": "",
    }

    (out_dir / "SOURCE.yaml").write_text(yaml.dump(source_yaml, default_flow_style=False, sort_keys=False))

    # Write ATTRIBUTION.md
    attribution = f"""# Attribution

Source: {url}
Commit: {actual_commit}
Retrieved: {now}
License: {license_id}

This snapshot is used in accordance with the upstream license.
Original copyright belongs to the upstream authors.
See LICENSE file for full license text.
"""
    (out_dir / "ATTRIBUTION.md").write_text(attribution)

    # Write AUDIT.md
    audit = f"""# Audit — {functional_name}

Source: {url}
Commit: {actual_commit}
Audited: (pending)

## License review
- [ ] LICENSE file verified
- [ ] License permits vendoring: {license_id}
- [ ] Copyright notices preserved

## Secret scan
- [x] Automated scan: clean (run at import time)
- [ ] Manual review of configuration files

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

    # Create placeholder adapted/.gitkeep
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
    args = parser.parse_args()

    import_source(
        url=args.url,
        commit=args.commit,
        functional_name=args.name,
        label=args.label,
        mode=args.mode,
        subsystem=args.subsystem,
        license_override=args.license_override,
    )


if __name__ == "__main__":
    main()
