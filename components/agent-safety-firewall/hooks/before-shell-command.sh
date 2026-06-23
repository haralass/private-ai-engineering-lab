#!/usr/bin/env bash
# Hook: before-shell-command
# Called before an agent executes a shell command.
#
# Input:  CLAUDE_TOOL_INPUT env var (JSON with "command" field)
#         or first positional argument as the raw command string
#
# Exit 0 = allow
# Exit 2 = block  (enforce mode only; AGENT_SAFETY_MODE=enforce)
# Exit 3 = confirm required (enforce mode only)
#
# Default mode (AGENT_SAFETY_MODE=report-only): always exits 0, logs warnings.

set -uo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

# Extract command string from CLAUDE_TOOL_INPUT (JSON) or positional arg
if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  COMMAND=$(printf '%s' "$CLAUDE_TOOL_INPUT" | \
    python3 -c "import json,sys; d=json.load(sys.stdin); print(d.get('command',''))" || true)
else
  COMMAND="${1:-}"
fi

if [[ -z "${COMMAND:-}" ]]; then
  exit 0
fi

# Build a safe JSON payload without shell interpolation into Python source.
# python3 receives the command as a positional argument (sys.argv[1]),
# never as part of the -c script source.
JSON_INPUT=$(python3 -c \
  "import json,sys; print(json.dumps({'command': sys.argv[1]}))" \
  "$COMMAND")

if [[ -z "$JSON_INPUT" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] ERROR: could not JSON-encode command" >&2
  exit 0
fi

printf '%s' "$JSON_INPUT" | python3 "$COMPONENT_DIR/hooks/run_policy.py"
exit $?
