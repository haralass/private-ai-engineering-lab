#!/usr/bin/env bash
# Hook: before-file-write
# Called before an agent writes to a file.
# Input via CLAUDE_TOOL_INPUT env var (JSON with "file_path" field)
# Exit 0 = allow, exit 2 = block (with message to stderr)

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  FILE_PATH=$(echo "$CLAUDE_TOOL_INPUT" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('file_path',''))" 2>/dev/null || echo "")
else
  FILE_PATH="${1:-}"
fi

if [[ -z "$FILE_PATH" ]]; then
  exit 0
fi

RESULT=$(python3 -c "
import sys
sys.path.insert(0, '$COMPONENT_DIR/src')
from policy_engine import evaluate
v = evaluate(target_path='$FILE_PATH')
print(v.verdict.value)
print(v.summary)
" 2>/dev/null || echo "allow
Policy engine unavailable — allowing by default")

VERDICT=$(echo "$RESULT" | head -1)
SUMMARY=$(echo "$RESULT" | tail -1)

if [[ "$VERDICT" == "block" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] FILE WRITE BLOCKED: $SUMMARY" >&2
  # In report-only mode: comment out the exit 2 below
  # exit 2
fi

if [[ "$VERDICT" == "confirm" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] FILE WRITE REQUIRES CONFIRMATION: $SUMMARY" >&2
fi

exit 0
