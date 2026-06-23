# Source Research Dossier: deterministic-agent-safety

## Repository identity

- **Name:** dotclaude
- **Creator:** poshan0126
- **GitHub URL:** https://github.com/poshan0126/dotclaude
- **Source path:** sources/deterministic-agent-safety/upstream/
- **License:** MIT (verified)
- **Import type:** vendored-snapshot
- **Pinned commit:** e59380ad5e89fb27033c3fa9fc12d1d3d734bba1
- **Retrieved:** 2026-06-22

---

## What it actually does

dotclaude is a complete `.claude/` configuration kit delivered as a Claude Code plugin marketplace. It ships eight shell hook scripts that intercept tool calls before and after execution to enforce deterministic safety policies — blocking dangerous git commands, scanning for secrets, protecting sensitive files, and blocking writes to build artifacts. Alongside the hooks it ships twelve workflow skills (slash commands) and seven specialist subagent definitions. The whole system installs via `/plugin install setupdotclaude@dotclaude`, which deep-scans the project and proposes a tailored installation plan rather than dumping every component in.

---

## Architecture

```
dotclaude/
├── settings.json          — hook wiring; maps hook events to shell scripts
├── hooks/                 — eight bash scripts, each a single-responsibility guard
│   └── tests/             — fixture-based test suite (JSON fixtures → bash runner)
├── rules/                 — six modular markdown rules with path-scoped frontmatter
├── skills/                — twelve slash-command markdown specs
├── agents/                — seven specialist subagent markdown definitions
└── plugins/               — marketplace catalog; plugin.json + symlinks into above dirs
```

The central abstraction is **Claude Code's hook system**: `settings.json` wires each hook script to `PreToolUse` or `PostToolUse` events via a matcher string. Every script reads JSON from stdin (the tool invocation payload), parses with `jq`, makes a deterministic decision, and exits with code 2 (block) or 0 (allow), writing a structured JSON response that Claude Code consumes. The plugin marketplace layer adds distribution: 20 plugins, each a `plugin.json` plus relative symlinks back to the real files — no copies.

---

## Main modules and important files

| Path | What it does |
|---|---|
| `settings.json` | Master hook wiring; also declares `permissions.allow` and `permissions.deny` glob lists for Read/Write/Edit operations |
| `hooks/block-dangerous-commands.sh` | `PreToolUse` guard for Bash: blocks push to protected branches, force push, `rm -rf` on dangerous paths, DROP TABLE/DELETE without WHERE, TRUNCATE, `chmod 777`, curl-pipe-to-shell, raw device writes, `mkfs`/`dd`, `git reset --hard`, `git clean -f`, and accidental `npm publish` |
| `hooks/scan-secrets.sh` | `PreToolUse` guard for Edit/Write: detects AWS keys, GitHub tokens, `sk-...` API keys, Slack tokens, private key PEM blocks, connection strings with credentials, hardcoded password assignments; emits `ask` (not `deny`) so test fixtures can override |
| `hooks/protect-files.sh` | `PreToolUse` guard for Edit/Write: blocks `.env`, `*.pem`, `*.key`, `package-lock.json`, lock files, generated files, `.git/`, `secrets/`, `.claude/hooks/`; requires confirmation for `settings.json` edits |
| `hooks/warn-large-files.sh` | `PreToolUse` guard for Edit/Write: blocks writes to `node_modules/`, `vendor/`, `dist/`, `build/`, `.next/`, `__pycache__/`, `.venv/`, and binary/media file extensions |
| `hooks/format-on-save.sh` | `PostToolUse`: auto-detects and runs Biome, Prettier, Ruff, Black/isort, rustfmt, or gofmt after every file edit; silent on success; formatter must be installed AND configured |
| `hooks/auto-test.sh` | `PostToolUse`: runs the matching test file after edits; silent on success |
| `hooks/session-start.sh` | `SessionStart`: injects current branch, dirty state, last commit, stash count, active PR info; detects config drift via manifest fingerprinting |
| `hooks/notify.sh` | `Notification`: sends native OS notification when Claude needs attention |
| `hooks/tests/run-all.sh` | Fixture-based test runner: iterates JSON fixtures per hook, checks exit codes and stdout substrings |
| `skills/setupdotclaude/SKILL.md` | Bootstrap skill: scans project, proposes evidence-based install plan, runs as gap analysis on re-runs |
| `agents/security-reviewer/` | Subagent: OWASP Top 10 static analysis, injection, auth, data exposure, dependency vulns |
| `agents/silent-failure-hunter/` | Subagent: finds swallowed errors, floating promises, fallbacks that hide breakage |

---

## Core technical patterns

**Pattern 1 — Exit-code protocol for hook decisions**
Every hook script follows the same contract: exit 0 = allow, exit 2 = block, stdout = JSON with `hookSpecificOutput.permissionDecision` (`deny` or `ask`) and `permissionDecisionReason`. This is the Claude Code hook spec; the scripts implement it faithfully with no custom middleware.
Location: all `hooks/*.sh`

**Pattern 2 — Fail-open vs fail-closed stratification**
Safety-critical hooks (`protect-files.sh`, `warn-large-files.sh`) fail closed when `jq` is missing (emit deny JSON, exit 2). Productivity hooks (`scan-secrets.sh`, `format-on-save.sh`) fail open (exit 0). The distinction is explicit in each script's header comment.
Location: first `if ! command -v jq` block in each hook

**Pattern 3 — Path-glob rule frontmatter in markdown rules**
Rule files in `rules/` carry YAML frontmatter with a `paths:` key that scopes the rule to specific file patterns (e.g. `src/billing/**`). Claude Code loads only scoped rules when editing matching files, saving tokens.
Location: `rules/database.md`, `rules/security.md`, `rules/error-handling.sh`, `rules/frontend.md`

**Pattern 4 — Manifest fingerprinting for config drift detection**
`session-start.sh` hashes `package.json` scripts (via `jq -S`) plus other manifests (`pyproject.toml`, `Cargo.toml`, `go.mod`, `Gemfile`, `Makefile`) with `cksum` and compares against a stored fingerprint in `.claude/.dotclaude.json`. Emits a one-line drift nudge only when the hash changed.
Location: `hooks/session-start.sh`, functions `manifest_hash` and drift detection block

**Pattern 5 — Plugin-as-symlink-tree distribution**
Each `plugins/<name>/` dir contains only `plugin.json` and symlinks pointing at the shared files in `hooks/`, `skills/`, `agents/`, `rules/`. Claude Code dereferences symlinks at install time. No file duplication.
Location: `plugins/*/` directories, documented in `CLAUDE.md`

---

## Novel or interesting mechanisms

**The `ask` vs `deny` distinction in `scan-secrets.sh`:** Rather than hard-blocking all secret-pattern matches (which would break test fixtures and example files), the script emits `permissionDecision: "ask"`. Claude Code will prompt the user for explicit confirmation. This is the only hook in the kit that uses `ask` — all others use `deny` or allow. This is a meaningful UX choice: secrets in test code are legitimate; the hook catches accidents, not intent.

**Fixture-based integration testing for bash hooks:** The test runner (`hooks/tests/run-all.sh`) uses plain JSON fixture files with `stdin`, `expect_exit`, `expect_stdout_contains`, and `expect_stdout_not_contains` fields. There is no mocking framework. Each fixture replays an actual hook invocation and checks the real script's output. This is lightweight but genuinely covers the hook contract.

**Token-budget awareness:** The README explicitly teaches token cost by category: always-loaded rules cost tokens every turn, path-scoped rules only when near matched files, skills and agents only when invoked. The `/context-budget` skill quantifies this per-project. This design philosophy — treat context as a scarce resource — is more sophisticated than most dotfiles repos.

---

## Data flow

```
User action in Claude Code
  → Claude proposes tool call (Edit, Write, Bash, etc.)
  → Claude Code invokes PreToolUse hooks matching the tool type
  → Hook script receives JSON payload on stdin (tool_name, tool_input.*)
  → Script parses with jq, applies rule logic
  → Script emits JSON to stdout: { hookSpecificOutput: { permissionDecision: "deny"|"ask", permissionDecisionReason: "..." } }
  → Script exits 2 (block/ask) or 0 (allow)
  → Claude Code acts on the decision (block execution, prompt user, or allow)
  → If allowed: tool executes
  → PostToolUse hooks fire (format-on-save.sh, auto-test.sh)
  → Results returned to Claude
```

---

## Dependencies

| Dependency | Why |
|---|---|
| `jq` | JSON parsing in bash hooks; safety-critical hooks fail closed if missing |
| `git` | Branch detection, dirty state, protected-branch list derivation |
| `gh` | Active PR info in session-start (optional; skipped if not installed) |
| Formatter binaries (biome, prettier, ruff, black, rustfmt, gofmt) | format-on-save.sh; each detected at runtime by binary + config file presence |
| Claude Code plugin system | Distribution via marketplace and `/plugin install` |

No npm/pip/Node.js dependencies. Pure bash + JSON.

---

## Security model

**Trust model:** hooks run as the local user in the same process environment as Claude Code. They cannot create a true sandbox — a determined agent can still do harm if the user approves the block dialog. The model is accident prevention, not adversarial isolation.

**What is enforced deterministically:** push to protected branches, force push, `rm -rf` on dangerous paths, DROP TABLE, delete without WHERE, chmod 777, curl-pipe-shell, raw device writes, `git reset --hard`, `git clean -f`, and accidental package publishing. These are hard-blocks (exit 2, deny decision).

**What requires user confirmation:** secret patterns in file content (exit 2, ask decision), editing `settings.json`. The `ask` decision still blocks execution but presents a confirm dialog.

**What is NOT protected:** read-only operations are not hooked (the `settings.json` deny list handles some read cases declaratively). The hooks themselves can be edited by anyone with file access — `protect-files.sh` blocks edits to `.claude/hooks/*` to partially close this, but `settings.json` only prompts for confirmation on its own modification.

**Known CVE context:** CVE-2025-59536 (CVSS 8.7) was a Claude Code vulnerability where `SessionStart` hooks in malicious repos executed before trust was confirmed. dotclaude's `session-start.sh` is a read-only context injector (no shell commands, only git queries and `cksum`), so it is low-risk on this vector. [inference]

---

## Testing strategy

Fixture-based bash testing under `hooks/tests/fixtures/<hook-name>/`. Each fixture is a JSON file specifying:
- `stdin`: the JSON payload to send to the hook
- `expect_exit`: 0 or 2
- `expect_stdout_contains`: list of required substrings
- `expect_stdout_not_contains`: list of forbidden substrings
- `env`: optional environment variable overrides

CI runs on Linux + macOS. Plugin manifest validation runs via `claude plugin validate . --strict`. Coverage appears solid for the hook contracts but does not test skill or agent behavior (no LLM eval harness).

---

## Genuinely reusable elements

**The hook protocol pattern (MIT license):** The `emit_deny` / `emit` helper functions and the stdin→jq→exit 2 pattern are a clean, reusable blueprint for implementing Claude Code PreToolUse guards. Any team can adapt these scripts verbatim.

**The secret scanning patterns in `scan-secrets.sh`:** The regex set covers AWS keys, GitHub tokens, sk-... API keys, Slack tokens, private key PEM blocks, connection strings, and hardcoded credentials — all high-confidence, low false-positive. Reusable directly or as a reference.

**The `ask` vs `deny` semantic distinction:** Using `ask` for ambiguous cases (secrets in test fixtures) rather than hard-blocking everything is a pattern applicable to any guardrail system.

**The manifest fingerprinting approach in `session-start.sh`:** Hashing manifest files to detect config drift is cheap (~5 tokens per session) and catches the most common staleness case. This pattern is reusable in any agent configuration management system.

---

## What NOT to reuse

**The bash `cksum` approach for fingerprinting** is platform-inconsistent (GNU `cksum` differs from macOS `cksum` on algorithms). A production system would use `sha256sum` or a language-native hash.

**The `jq` dependency with fail-open fallback in non-critical hooks:** fail-open is correct for productivity hooks but the failure mode should be logged; currently it is silent.

**The session-start verbose mode:** opt-in via `DOTCLAUDE_SESSION_VERBOSE=1` adds network calls (`gh pr view`) which can block session startup. Production deployments with strict latency budgets should not use it.

---

## Production-readiness

**MVP-quality.** The hooks are functionally solid and CI-tested. However: the bash-only implementation has platform edge cases (macOS vs Linux `cksum`), the test suite covers hook contracts but not integration across Claude Code versions, and the plugin marketplace pattern depends on Claude Code's undocumented plugin API surface. The security model explicitly relies on user confirmation dialogs rather than true sandboxing — production enterprise deployments would need a deeper enforcement layer.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- Zero runtime dependencies beyond `jq` and `git`
- Deterministic, auditable: every blocked action has a string reason
- Token-budget aware by design
- Good test coverage for the hook contracts
- Clean fail-open / fail-closed stratification

**Weaknesses:**
- Bash-only: complex regex matching is fragile under adversarial input
- Hooks can be bypassed if an agent edits settings.json (mitigated by the protect-files hook asking for confirmation, but not hard-blocked)
- No structured audit log of blocked actions
- Secret scanning uses `grep -qE` with bash string unquoting — edge cases in exotic content possible

**Technical debt:**
- `cksum` fingerprinting is not portable (should use sha256)
- No versioned schema for hook JSON protocol — a Claude Code update could break the contract
- Plugin symlink approach works but creates fragile dependency on Claude Code's internal marketplace behavior

---

## Possible clean-room adaptations

A clean-room implementation could take the **hook event → shell script → JSON protocol** pattern and build a more robust version:

- Replace bash scripts with a compiled binary (Go/Rust) that parses the same JSON protocol but with proper regex libraries and no `jq` dependency
- Add structured JSONL audit logging (timestamp, tool, decision, reason) to a file the user can query
- Add a per-project `policy.yaml` that expresses rules declaratively (allowed commands allowlist, path deny patterns, secret regex patterns) rather than hardcoded in bash
- Add a `--dry-run` mode that logs what would be blocked without actually blocking
- Extend the secret scanner with more patterns (GCP service account keys, Azure SAS tokens, JWT tokens)

None of this would copy dotclaude's code; it would re-implement the protocol it defined.

---

## Business applications

**Enterprise Claude Code safety layer:** Package the hook patterns as a managed enterprise product with a central `policy.yaml`, audit SIEM integration, and team-wide deployment via managed settings. Enterprises running Claude Code at scale face compliance requirements (SOC2, ISO27001) that require evidence of what agents did and did not do. A managed version of this with audit logging and a dashboard would fill a real gap.

**Multi-agent safety middleware:** The hook protocol is Claude Code-specific, but the underlying pattern — intercept tool call, evaluate against policy, allow/block with reason — is generalizable to any agent that supports hooks (Kimi Code, gstack's `/careful` and `/guard` skills, Cursor rules). A cross-agent policy engine built on this pattern could be a standalone product.

---

## Related business ideas in this lab

- `business-research/startup-white-spaces/agent-permission-firewall.md` — directly related; the lab has a working prototype (`components/agent-safety-firewall/`)
- `product-concepts/agent-permission-firewall/` — structured product concept

---

## Related sources in this lab

- `sources/terminal-coding-agent/` (kimi-code): implements its own hook engine in TypeScript with richer event types (PreToolUse, PostToolUse, PostToolUseFailure, PermissionRequest, SubagentStart, SubagentStop, etc.) — the production-grade version of the same pattern
- `sources/full-product-engineering-agent-stack/` (gstack): implements `/careful` and `/guard` safety skills that wrap the same dangerous-command detection logic in a skill rather than a hook
- `sources/structured-agent-development/` (superpowers): uses `SessionStart` hooks to inject context; safety-adjacent but workflow-focused rather than security-focused

---

## Open questions

1. Does Claude Code's `allowManagedHooksOnly` enterprise setting make dotclaude-style hooks the recommended enterprise distribution mechanism? (The CVE research suggests yes, but no official confirmation.)
2. Can the hook protocol be used as a cross-agent interception layer (i.e., can Kimi Code's hook engine consume the same JSON protocol as Claude Code's)? The types in kimi-code suggest compatible intent but different field names.
3. What is the realistic attack surface if a malicious `.claude/` directory is checked into a repo a developer clones? The `SessionStart` hook CVE (CVE-2025-59536) suggests this is non-trivial.

---

## Final research conclusion

dotclaude is the best publicly available reference implementation of the Claude Code hook safety pattern: well-structured, tested, and documented well enough to learn from directly. The highest-value element for this lab is the hook protocol design and the `ask` vs `deny` semantic distinction — both of which are clean-room-adaptable into a more robust, cross-agent safety engine.
