# agent-safety-firewall: Claude Code integration

## How to wire up the hooks in Claude Code

Add to your `.claude/settings.json`:

```json
{
  "hooks": {
    "PreToolUse": [
      {
        "matcher": "Bash",
        "hooks": [
          {
            "type": "command",
            "command": "bash /path/to/components/agent-safety-firewall/hooks/before-shell-command.sh"
          }
        ]
      },
      {
        "matcher": "Write",
        "hooks": [
          {
            "type": "command",
            "command": "bash /path/to/components/agent-safety-firewall/hooks/before-file-write.sh"
          }
        ]
      },
      {
        "matcher": "Edit",
        "hooks": [
          {
            "type": "command",
            "command": "bash /path/to/components/agent-safety-firewall/hooks/before-file-write.sh"
          }
        ]
      }
    ]
  }
}
```

## Report-only mode (default)

The hooks exit 0 in all cases but print warnings to stderr. You will see `[AGENT-SAFETY-FIREWALL]` lines in the Claude Code output when a dangerous action is detected.

## Enforcement mode

To actually block actions, uncomment the `exit 2` lines in each hook script. Claude Code interprets exit 2 from a PreToolUse hook as a block.
