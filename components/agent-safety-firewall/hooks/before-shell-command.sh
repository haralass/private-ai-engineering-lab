#!/usr/bin/env bash
# Hook: before-shell-command
# Called before an agent executes a shell command.
#
# Input:  CLAUDE_TOOL_INPUT env var (JSON with "command" field) — passed raw to run_policy.py
#         or first positional argument as the raw command string
#
# Exit 0 = allow
# Exit 2 = block  (enforce mode only; AGENT_SAFETY_MODE=enforce)
# Exit 3 = confirm required (enforce mode only)
#
# Fail-closed design:
#   - CLAUDE_TOOL_INPUT is piped raw to run_policy.py (no shell-side parsing with || true)
#   - run_policy.py owns all JSON parsing and fail-closed behavior
#   - JSON encoder failure for positional-arg path: block in enforce, allow in report-only

set -uo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

_run_policy() {
  printf '%s' "$1" | python3 "$COMPONENT_DIR/hooks/run_policy.py"
}

if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  # Pass raw JSON directly — run_policy.py handles all parsing and fail-closed behavior.
  # No || true here: a broken policy runner in enforce mode must not silently exit 0.
  _run_policy "$CLAUDE_TOOL_INPUT"
  exit $?
fi

# Positional argument mode: first arg is the raw command string
COMMAND="${1:-}"
if [[ -z "${COMMAND:-}" ]]; then
  exit 0
fi

# Safe JSON encoding: command passed as sys.argv[1], never interpolated into Python source.
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
