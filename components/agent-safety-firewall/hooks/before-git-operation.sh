#!/usr/bin/env bash
# Hook: before-git-operation
# Wraps git commands for policy evaluation before execution.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  COMMAND=$(echo "$CLAUDE_TOOL_INPUT" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('command',''))" 2>/dev/null || echo "")
else
  COMMAND="${*:-}"
fi

if [[ -z "$COMMAND" ]]; then
  exit 0
fi

# Only evaluate git commands
if [[ "$COMMAND" != git* ]]; then
  exit 0
fi

RESULT=$(python3 -c "
import sys
sys.path.insert(0, '$COMPONENT_DIR/src')
from policy_engine import evaluate
v = evaluate(command='$COMMAND')
print(v.verdict.value)
print(v.summary)
" 2>/dev/null || echo "allow
Policy engine unavailable")

VERDICT=$(echo "$RESULT" | head -1)
SUMMARY=$(echo "$RESULT" | tail -1)

if [[ "$VERDICT" == "block" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] GIT OPERATION BLOCKED: $SUMMARY" >&2
  # exit 2  # uncomment to enforce blocking
fi

if [[ "$VERDICT" == "confirm" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] GIT OPERATION REQUIRES CONFIRMATION: $SUMMARY" >&2
fi

exit 0
