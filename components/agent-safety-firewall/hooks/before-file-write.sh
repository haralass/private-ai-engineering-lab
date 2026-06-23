#!/usr/bin/env bash
# Hook: before-file-write
# Called before an agent writes to a file.
#
# Input:  CLAUDE_TOOL_INPUT env var (JSON with "file_path" field)
#         or first positional argument as the raw file path
#
# Exit 0 = allow
# Exit 2 = block  (enforce mode only; AGENT_SAFETY_MODE=enforce)
# Exit 3 = confirm required (enforce mode only)

set -uo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
COMPONENT_DIR="$(dirname "$SCRIPT_DIR")"

if [[ -n "${CLAUDE_TOOL_INPUT:-}" ]]; then
  FILE_PATH=$(printf '%s' "$CLAUDE_TOOL_INPUT" | \
    python3 -c "import json,sys; d=json.load(sys.stdin); print(d.get('file_path',''))" || true)
else
  FILE_PATH="${1:-}"
fi

if [[ -z "${FILE_PATH:-}" ]]; then
  exit 0
fi

JSON_INPUT=$(python3 -c \
  "import json,sys; print(json.dumps({'target_path': sys.argv[1]}))" \
  "$FILE_PATH")

if [[ -z "$JSON_INPUT" ]]; then
  echo "[AGENT-SAFETY-FIREWALL] ERROR: could not JSON-encode file path" >&2
  exit 0
fi

printf '%s' "$JSON_INPUT" | python3 "$COMPONENT_DIR/hooks/run_policy.py"
exit $?
