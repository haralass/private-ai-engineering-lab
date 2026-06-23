#!/usr/bin/env bash
# Hook: before-git-operation
# Called before an agent runs a git command.
#
# Input:  CLAUDE_TOOL_INPUT env var (JSON with "command" field)
#         or positional arguments as the raw git command
#
# Exit 0 = allow
# Exit 2 = block  (enforce mode only; AGENT_SAFETY_MODE=enforce)
# Exit 3 = confirm required (enforce mode only)

set -uo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  COMMAND=$(printf '%s' "$CLAUDE_TOOL_INPUT" | \
    python3 -c "import json,sys; d=json.load(sys.stdin); print(d.get('command',''))" || true)
else
  COMMAND="${*:-}"
fi

if [[ -z "${COMMAND:-}" ]]; then
  exit 0
fi

# Only evaluate git commands
case "$COMMAND" in
  git\ *|git) ;;
  *) exit 0 ;;
esac

JSON_INPUT=$(python3 -c \
  "import json,sys; print(json.dumps({'command': sys.argv[1]}))" \
  "$COMMAND")

if [[ -z "$JSON_INPUT" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] ERROR: could not JSON-encode command" >&2
  exit 0
fi

printf '%s' "$JSON_INPUT" | python3 "$COMPONENT_DIR/hooks/run_policy.py"
exit $?
