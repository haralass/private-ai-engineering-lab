#!/usr/bin/env python3
"""
Standalone secret scanner for the lab.
Scans a directory or file for probable secrets.

Usage:
    python secret_scan.py <path>
    python secret_scan.py <path> --threshold high
    python secret_scan.py <path> --json

Exit codes:
    0  No findings
    1  Findings detected
"""

from __future__ import annotations

import argparse
import json
import re
import sys
from pathlib import Path

PATTERNS = {
    "high": [
        (r"sk-[a-zA-Z0-9]{32,}", "Anthropic/OpenAI API key"),
        (r"ghp_[a-zA-Z0-9]{36}", "GitHub personal access token"),
        (r"gho_[a-zA-Z0-9]{36}", "GitHub OAuth token"),
        (r"-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----", "Private key"),
        (r"(?i)aws_access_key_id\s*[:=]\s*([A-Z0-9]{20})", "AWS access key ID"),
        (r"(?i)aws_secret_access_key\s*[:=]\s*([a-zA-Z0-9/+]{40})", "AWS secret access key"),
        (r"AKIA[0-9A-Z]{16}", "AWS access key ID (raw)"),
    ],
    "medium": [
        (r"(?i)(?:api[_-]?key|apikey)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-]{20,})", "API key pattern"),
        (r"(?i)(?:secret)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-@!#$%^&*]{12,})", "Secret pattern"),
        (r"(?i)(?:token|bearer)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-\.]{20,})", "Token pattern"),
        (r"(?i)(?:password|passwd|pwd)\s*[:=]\s*['\"]?([^\s'\"]{8,})", "Password pattern"),
    ],
    "low": [
        (r"(?i)(?:auth|credential|cred)\s*[:=]\s*['\"]?([a-zA-Z0-9_\-@!]{8,})", "Credential pattern"),
    ],
}

TEXT_EXTENSIONS = {
    ".py", ".js", ".ts", ".jsx", ".tsx", ".sh", ".bash",
    ".yaml", ".yml", ".json", ".toml", ".ini", ".cfg",
    ".env", ".md", ".txt", ".conf", ".rb", ".go", ".rs",
    ".java", ".cs", ".php", ".html", ".css", ".xml",
}

SKIP_DIRS = {".git", "node_modules", "venv", ".venv", "__pycache__", "dist", "build"}


def scan_file(path: Path, threshold: str) -> list[dict]:
    if path.suffix.lower() not in TEXT_EXTENSIONS and path.suffix:
        return []
    try:
        content = path.read_text(errors="ignore")
    except (OSError, PermissionError):
        return []

    levels = ["high"]
    if threshold in ("medium", "low"):
        levels.append("medium")
    if threshold == "low":
        levels.append("low")

    findings = []
    for level in levels:
        for pattern, label in PATTERNS.get(level, []):
            for match in re.finditer(pattern, content):
                line_num = content[: match.start()].count("\n") + 1
                findings.append({
                    "file": str(path),
                    "line": line_num,
                    "level": level,
                    "label": label,
                    "preview": match.group(0)[:80],
                })
    return findings


def scan_directory(root: Path, threshold: str) -> list[dict]:
    findings = []
    for path in root.rglob("*"):
        if any(part in SKIP_DIRS for part in path.parts):
            continue
        if path.is_file():
            findings.extend(scan_file(path, threshold))
    return findings


def main() -> None:
    parser = argparse.ArgumentParser(description="Scan for secrets")
    parser.add_argument("path", help="File or directory to scan")
    parser.add_argument("--threshold", default="high", choices=["high", "medium", "low"])
    parser.add_argument("--json", action="store_true", dest="as_json")
    args = parser.parse_args()

    target = Path(args.path)
    if not target.exists():
        print(f"ERROR: {target} does not exist", file=sys.stderr)
        sys.exit(2)

    if target.is_file():
        findings = scan_file(target, args.threshold)
    else:
        findings = scan_directory(target, args.threshold)

    if args.as_json:
        print(json.dumps(findings, indent=2))
    else:
        if findings:
            print(f"FINDINGS: {len(findings)} potential secret(s) detected\n")
            for f in findings:
                print(f"  [{f['level'].upper()}] {f['file']}:{f['line']} — {f['label']}")
                print(f"    Preview: {f['preview']}")
        else:
            print("Secret scan: clean — no findings")

    sys.exit(1 if findings else 0)


if __name__ == "__main__":
    main()
