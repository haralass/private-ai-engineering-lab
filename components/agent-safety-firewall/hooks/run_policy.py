#!/usr/bin/env python3
"""
Safe policy engine runner for shell hooks.

Reads a JSON object from stdin:
  {"command": "...", "target_path": "..."}

Writes to stdout:
  Line 1: verdict  (allow | block | confirm | warn)
  Line 2: summary  (human-readable reason)

Exit codes:
  0  allow or warn (or any verdict in report-only mode)
  2  block  (enforce mode only)
  3  confirm — requires human approval (enforce mode only)
  4  malformed input
  5  policy engine error in enforce mode (treated as block)

This script is intentionally kept simple so hooks can rely on its exit code
rather than parsing complex output.
"""

from __future__ import annotations

import json
import os
import sys
from pathlib import Path

COMPONENT_DIR = Path(__file__).parent.parent
sys.path.insert(0, str(COMPONENT_DIR / "src"))

ENFORCE = os.environ.get("AGENT_SAFETY_MODE", "report-only").lower().strip() == "enforce"


def _exit(code: int, verdict: str, summary: str) -> None:
    print(verdict, flush=True)
    print(summary, flush=True)
    sys.exit(code)


def main() -> None:
    # Read JSON from stdin
    raw = sys.stdin.read()
    if not raw.strip():
        _exit(0, "allow", "empty-input")

    try:
        data = json.loads(raw)
    except json.JSONDecodeError as e:
        msg = f"malformed-json: {e}"
        print(f"[AGENT-SAFETY-FIREWALL] WARNING: {msg}", file=sys.stderr)
        # Malformed input: in enforce mode block with exit 4; in report-only log and allow with exit 0.
        if ENFORCE:
            _exit(4, "block", msg)
        else:
            _exit(0, "allow", msg)

    command = data.get("command") or None
    # Accept both "target_path" (built by positional-arg code path) and
    # "file_path" (the key Claude Code sends for before-file-write hooks).
    target_path = data.get("target_path") or data.get("file_path") or None

    try:
        from policy_engine import evaluate, Verdict
        v = evaluate(command=command, target_path=target_path)

        if v.verdict == Verdict.BLOCK:
            print(f"[AGENT-SAFETY-FIREWALL] BLOCKED: {v.summary}", file=sys.stderr)
            _exit(2 if ENFORCE else 0, "block", v.summary)

        if v.verdict == Verdict.CONFIRM:
            print(f"[AGENT-SAFETY-FIREWALL] CONFIRM REQUIRED: {v.summary}", file=sys.stderr)
            _exit(3 if ENFORCE else 0, "confirm", v.summary)

        if v.verdict == Verdict.WARN:
            print(f"[AGENT-SAFETY-FIREWALL] WARNING: {v.summary}", file=sys.stderr)

        _exit(0, "allow", v.summary)

    except Exception as e:
        msg = f"policy-engine-error: {e}"
        print(f"[AGENT-SAFETY-FIREWALL] ERROR: {msg}", file=sys.stderr)
        if ENFORCE:
            # In enforce mode a broken policy engine on an unknown action is a block
            _exit(5, "block", msg)
        else:
            _exit(0, "allow", f"report-only: {msg}")


if __name__ == "__main__":
    main()
