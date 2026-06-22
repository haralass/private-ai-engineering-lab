#!/usr/bin/env bash
# Hook: before-shell-command
# Called before an agent executes a shell command.
# Input via CLAUDE_TOOL_INPUT env var (JSON with "command" field)
# Exit 0 = allow, exit 2 = block (with message to stderr)
#
# In report-only mode this hook always exits 0 but prints warnings.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

# Parse command from CLAUDE_TOOL_INPUT if available
if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  COMMAND=$(echo "$CLAUDE_TOOL_INPUT" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('command',''))" 2>/dev/null || echo "")
else
  COMMAND="${1:-}"
fi

if [[ -z "$COMMAND" ]]; then
  exit 0
fi

# Run the policy engine
RESULT=$(python3 -c "
import sys
sys.path.insert(0, '$COMPONENT_DIR/src')
from policy_engine import evaluate
v = evaluate(command='$COMMAND')
print(v.verdict.value)
print(v.summary)
" 2>/dev/null || echo "allow
Policy engine unavailable — allowing by default")

VERDICT=$(echo "$RESULT" | head -1)
SUMMARY=$(echo "$RESULT" | tail -1)

if [[ "$VERDICT" == "block" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] BLOCKED: $SUMMARY" >&2
  # In report-only mode: comment out the exit 2 below
  # exit 2
fi

if [[ "$VERDICT" == "confirm" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] REQUIRES CONFIRMATION: $SUMMARY" >&2
fi

exit 0
