# agent-safety-firewall

Configurable safety layer for AI agents. Blocks or requires human confirmation for destructive shell commands, protected file writes, dangerous Git operations, and secret-containing staged files.

## Status: prototype

Not production-ready. Tests written. Report-only mode available.

## Source

Derived from study of `sources/deterministic-agent-safety/` (poshan0126/dotclaude).
This is a clean-room reimplementation — no code copied verbatim.

## Structure

```
config/
  protected-paths.yaml     Paths that agents may not modify without confirmation
  dangerous-commands.yaml  Shell command patterns that require blocking or approval
  allowed-actions.yaml     Explicitly permitted actions (bypass list)
hooks/
  before-shell-command.sh  Intercept shell commands
  before-file-write.sh     Intercept file writes
  before-git-operation.sh  Intercept Git operations
src/
  command_analyzer.py      Analyze shell commands against dangerous-commands.yaml
  path_guard.py            Enforce protected-paths.yaml
  policy_engine.py         Evaluate composite policy decisions
tests/
  test_command_analyzer.py
  test_path_guard.py
  test_git_rules.py
examples/
  claude-code/             Integration example for Claude Code
  kimi-code/               Integration example for Kimi Code
  generic-agent/           Generic hook integration example
```

## How to use

1. Configure `config/protected-paths.yaml` for your project
2. Configure `config/dangerous-commands.yaml` (defaults cover most cases)
3. Register hooks in your agent's hook configuration
4. All actions are logged to stdout by default — no automatic blocking in report-only mode

## Blocked patterns (defaults)

- `rm -rf`
- `git reset --hard`
- `git clean -f`
- `git push --force` / `git push -f`
- Direct push to `main`
- `curl | bash` or `wget | sh`
- `chmod 777`
- Package publishing (`npm publish`, `pip publish`, `cargo publish`)
- Destructive SQL (`DROP TABLE`, `TRUNCATE`, `DELETE FROM` without `WHERE`)
- Files matching protected-paths patterns
- Staged files containing probable secrets
