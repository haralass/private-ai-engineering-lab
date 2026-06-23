#!/usr/bin/env bash
# Hook: before-git-operation
# Called before an agent runs a git command.
#
# Input:  CLAUDE_TOOL_INPUT env var (JSON with "command" field) — passed raw to run_policy.py
#         or positional arguments as the raw git command
#
# Exit 0 = allow
# Exit 2 = block  (enforce mode only; AGENT_SAFETY_MODE=enforce)
# Exit 3 = confirm required (enforce mode only)

set -uo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

_run_policy() {
  printf '%s' "$1" | python3 "$COMPONENT_DIR/hooks/run_policy.py"
}

if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  # run_policy.py sees the raw JSON including "command" field.
  # Non-git commands will be evaluated but will ALLOW (no pattern matches non-git ops).
  _run_policy "$CLAUDE_TOOL_INPUT"
  exit $?
fi

# Positional argument mode: all args joined form the command string
COMMAND="${*:-}"
if [[ -z "${COMMAND:-}" ]]; then
  exit 0
fi

# Only evaluate git commands when called with positional args
case "$COMMAND" in
  git\ *|git) ;;
  *) exit 0 ;;
esac

JSON_INPUT=$(python3 -c \
  "import json,sys; print(json.dumps({'command': sys.argv[1]}))" \
  "$COMMAND") || {
  echo "[AGENT-SAFETY-FIREWALL] ERROR: JSON encoder failed for positional command" >&2
  if [[ "${AGENT_SAFETY_MODE:-report-only}" == "enforce" ]]; then
    exit 2
  fi
  exit 0
}

_run_policy "$JSON_INPUT"
