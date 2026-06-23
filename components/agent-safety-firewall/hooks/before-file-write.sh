#!/usr/bin/env bash
# Hook: before-file-write
# Called before an agent writes to a file.
#
# Input:  CLAUDE_TOOL_INPUT env var (JSON with "file_path" field) — passed raw to run_policy.py
#         or first positional argument as the raw file path
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
  _run_policy "$CLAUDE_TOOL_INPUT"
  exit $?
fi

FILE_PATH="${1:-}"
if [[ -z "${FILE_PATH:-}" ]]; then
  exit 0
fi

JSON_INPUT=$(python3 -c \
  "import json,sys; print(json.dumps({'target_path': sys.argv[1]}))" \
  "$FILE_PATH") || {
  echo "[AGENT-SAFETY-FIREWALL] ERROR: JSON encoder failed for positional file path" >&2
  if [[ "${AGENT_SAFETY_MODE:-report-only}" == "enforce" ]]; then
    exit 2
  fi
  exit 0
}

_run_policy "$JSON_INPUT"
