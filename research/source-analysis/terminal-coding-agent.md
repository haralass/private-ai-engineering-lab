# Source Research Dossier: terminal-coding-agent

## Repository identity

- **Name:** kimi-code
- **Creator:** MoonshotAI (Moonshot AI)
- **GitHub URL:** https://github.com/MoonshotAI/kimi-code
- **Source path:** sources/terminal-coding-agent/upstream/
- **License:** MIT (verified)
- **Import type:** vendored-snapshot
- **Pinned commit:** 3443a00a439da24ded1001d5689a184cd26b21f0
- **Retrieved:** 2026-06-22
- **Removed paths:** `.git/`, `build`

---

## What it actually does

Kimi Code is a full-featured terminal AI coding agent built as a TypeScript monorepo. Unlike the other three sources (which are configuration/methodology layers on top of Claude Code or other agents), kimi-code is the agent runtime itself: a TUI application, a server that hosts agent sessions over REST+WebSocket, a public TypeScript SDK, a plugin manager that installs skills and MCP servers from GitHub or zip URLs, a hook engine with 20 distinct event types, a path security policy engine, a concurrent tool scheduler with resource-conflict detection, and a single-binary distribution via tsdown. It is designed to work with Moonshot AI's Kimi models but is configurable for any compatible OpenAI-API-compatible provider.

---

## Architecture

**Monorepo package structure:**

```
packages/
├── agent-core/         — unified agent engine (core of everything)
│   ├── src/agent/      — Agent class, BackgroundManager
│   ├── src/loop/       — turn loop, tool scheduler, tool-call lifecycle
│   ├── src/session/    — session store, hooks engine, git context
│   ├── src/tools/      — builtin tools (bash, file, web, planning, etc.)
│   │   ├── builtin/    — BashTool, file tools, web tools
│   │   └── policies/   — path-access guard, sensitive file detection
│   ├── src/plugin/     — PluginManager, manifest parsing, GitHub install
│   ├── src/skill/      — skill discovery
│   ├── src/profile/    — agent profile/persona system
│   └── src/flags/      — experimental feature flag registry
├── kosong/             — LLM/provider abstraction layer
├── kaos/               — execution environment (shell, file, process abstractions)
├── node-sdk/           — public TypeScript SDK and harness
├── server/             — REST+WebSocket server hosting agent sessions
├── server-e2e/         — live E2E tests against running server
├── oauth/              — Kimi OAuth and managed auth
└── telemetry/          — client-side telemetry
apps/
├── kimi-code/          — CLI/TUI application (consumes node-sdk)
├── kimi-web/           — browser web UI (Vue 3 + Vite)
└── vis/                — visual debugging tools
```

The separation is intentional and architecturally clean: `apps/kimi-code` depends on `node-sdk`, NOT on `agent-core` directly. The SDK is the public surface. `kimi-web` re-implements wire types locally rather than depending on `agent-core`.

---

## Main modules and important files

| Path | What it does |
|---|---|
| `packages/agent-core/src/session/hooks/engine.ts` | `HookEngine` class: event-based hook dispatch, `trigger()` (async), `triggerBlock()` (blocking for PreToolUse), `fireAndForgetTrigger()` (PostToolUse); dedup by command string; aggregates results to allow/block |
| `packages/agent-core/src/session/hooks/types.ts` | Defines 20 hook event types: `PreToolUse`, `PostToolUse`, `PostToolUseFailure`, `PermissionRequest`, `PermissionResult`, `UserPromptSubmit`, `Stop`, `StopFailure`, `Interrupt`, `SessionStart`, `SessionEnd`, `SubagentStart`, `SubagentStop`, `PreCompact`, `PostCompact`, `Notification` |
| `packages/agent-core/src/session/hooks/runner.ts` | Executes a single hook command as a subprocess with timeout, cwd, AbortSignal support |
| `packages/agent-core/src/loop/tool-access.ts` | `ToolAccesses` type system: `file` (read/write/readwrite/search with recursive) and `all` kinds; `conflict()` method for concurrent scheduling |
| `packages/agent-core/src/loop/tool-scheduler.ts` | `ToolScheduler<Result>`: concurrent tool dispatch with resource-conflict serialization; non-conflicting tool calls execute in parallel |
| `packages/agent-core/src/loop/tool-call.ts` | Tool-call lifecycle: validate → preparation hooks → dispatch `tool.call` → execute (concurrent/serial per scheduler) → dispatch `tool.result` in provider order |
| `packages/agent-core/src/tools/builtin/shell/bash.ts` | `BashTool`: shell execution via Kaos abstraction; foreground (60s/300s timeout) and background modes; stdin closed immediately; two-phase kill (SIGTERM → SIGKILL) owned by BackgroundManager |
| `packages/agent-core/src/tools/policies/path-access.ts` | `PathSecurityError`: blocks reads/writes outside workspace and to sensitive files; lexical canonicalization (no `realpath`); prevents shared-prefix escapes |
| `packages/agent-core/src/tools/policies/sensitive.ts` | `isSensitiveFile()`: hardcoded set of sensitive basenames (`.env`, SSH keys, credential files); ENV prefix detection; rename-shielded variants (`.env.bak`, `id_rsa-old`); explicit exemptions (`.env.example`, public keys) |
| `packages/agent-core/src/plugin/manager.ts` | `PluginManager`: install from local path / zip URL / GitHub; per-plugin MCP server state; `enabledSessionStarts()` for SessionStart skill injection; `enabledMcpServers()` for MCP wiring |
| `packages/agent-core/src/plugin/types.ts` | Plugin manifest schema: name, version, skills (dirs), sessionStart, mcpServers, skillInstructions, interface; `PluginRecord` with state/diagnostics/capabilities |
| `packages/agent-core/src/flags/registry.ts` | Experimental feature flag registry; env-driven (`KIMI_CODE_EXPERIMENTAL_<NAME>`), default off; release by flipping `default: true` |
| `packages/agent-core/src/agent/` | `Agent` class: independent, no forced Session dependency; constructor doesn't require agentId or Session; sessionId as optional request-config hint only |
| `packages/kosong/` | Provider abstraction: wraps OpenAI-compatible APIs; ContentPart types used in hook matcher values |
| `packages/kaos/` | Execution abstraction: shell exec, file/process operations; Windows uses Git Bash; cross-platform path handling |

---

## Core technical patterns

**Pattern 1 — Typed resource-conflict concurrent tool scheduling**
`ToolAccesses` defines file accesses (path, operation: read/write/readwrite/search, recursive) and an `all` kind for arbitrary side effects. `ToolScheduler` uses this to run non-conflicting tool calls in parallel and serialize conflicting ones. Two file reads are always parallel. Any write on an overlapping path serializes against other reads/writes on that path. The `all` kind is globally exclusive.
Location: `packages/agent-core/src/loop/tool-access.ts`, `packages/agent-core/src/loop/tool-scheduler.ts`

**Pattern 2 — 20-event hook system with per-event semantics**
The hook engine supports 20 events vs Claude Code's ~5. Unique additions: `PostToolUseFailure`, `PermissionRequest`, `PermissionResult`, `Interrupt`, `SessionEnd`, `SubagentStart`, `SubagentStop`, `PreCompact`, `PostCompact`. The `triggerBlock()` method (for PreToolUse) vs `trigger()` vs `fireAndForgetTrigger()` (for PostToolUse) separate blocking from non-blocking semantics. Hook dedup prevents the same command from running twice in one event.
Location: `packages/agent-core/src/session/hooks/engine.ts`, `types.ts`

**Pattern 3 — Lexical path canonicalization without realpath**
The path security guard uses lexical normalization (resolves `.`, `..` without filesystem calls) to avoid symlink-following attacks while remaining backend-aware (SSH paths stay POSIX even on Windows hosts). The shared-prefix escape (a path like `/workspace-evil` passing a naive `startswith('/workspace')`) is blocked by requiring a separator or exact equality after the base prefix.
Location: `packages/agent-core/src/tools/policies/path-access.ts`

**Pattern 4 — Sensitive file detection with rename-shielded variant detection**
`isSensitiveFile()` catches not just `.env` and `id_rsa` but also rename-shielded variants: `id_rsa-old`, `id_rsa.bak`, `id_rsa.key`. The logic: for known sensitive basename prefixes, check if a suffix starts with `-` or `_` (indicating a rename) or `.` followed by a known sensitive extension suffix. `.env.example` and public keys are explicitly exempted.
Location: `packages/agent-core/src/tools/policies/sensitive.ts`

**Pattern 5 — Plugin manager with GitHub-native install and per-capability state**
`PluginManager` installs plugins from local paths, zip URLs, or GitHub (resolves to tarball URL via GitHub API). Plugins are stored under `~/.kimi/plugins/managed/<id>/`. Per-plugin MCP server enabled/disabled state (`PluginCapabilityState`) is persisted separately from the installed record. The fallback for `node` command on native binary: substitutes `process.execPath` + `KIMI_NODE_FALLBACK_SUBCOMMAND` so plugins that declare `"command": "node"` work without system Node.js.
Location: `packages/agent-core/src/plugin/manager.ts`, `types.ts`

**Pattern 6 — Experimental feature flag registry with env-driven activation**
Features are registered in `packages/agent-core/src/flags/registry.ts` with `default: false`. Activated via `KIMI_CODE_EXPERIMENTAL_<NAME>` env var or `KIMI_CODE_EXPERIMENTAL_FLAG` to enable all. Release path: flip `default` to `true`. This is a clean, self-documenting feature flag pattern.
Location: `packages/agent-core/src/flags/registry.ts`

---

## Novel or interesting mechanisms

**The Agent class independence constraint (from AGENTS.md):** "The `Agent` class in `packages/agent-core/src/agent` must be usable on its own. The constructor must not force the caller to create a `Session` instance, nor require an `agentId` or `session`." This is an explicit architectural constraint documented as a contributor rule. The Agent is a standalone unit; Session orchestration is optional. This enables embedding the Agent in other contexts (web UI, SDK, subagent) without the full session lifecycle.

**The `SubagentStart` / `SubagentStop` hook events:** These are unique to kimi-code among the four sources. They enable hooks that fire specifically at subagent boundaries — useful for isolation, context reset, security policy enforcement at subagent spawn, and telemetry. No other source in this lab exposes this event type.

**The `PermissionRequest` / `PermissionResult` hook events:** These allow hooks to intercept the permission grant/deny cycle (equivalent to Claude Code's "ask" decision). A hook can observe or modify the permission flow programmatically, not just via interactive user prompts.

**The `HookMatcherValue` type accepting `ContentPart[]`:** Hook matchers can match against structured content (ContentPart arrays from the LLM provider), not just string tool names. This enables pattern-matching against the actual content of a tool call, not just its name — richer policy expression.

**The `tool.call` / `tool.result` event ordering invariant:** Tool results are dispatched in provider order regardless of execution order. If the provider returned tool calls [A, B, C] and B finished last, the results are still dispatched A, B, C. This preserves transcript consistency for conversation history.

**The `isUserCancellation` abort reason:** When the user presses stop, the tool output says: "The user manually interrupted [tool] (and anything else running at the same time). This was a deliberate user action, not a system error, timeout, or capacity limit. Do not retry automatically or guess at the cause — wait for the user's next instruction." This prompt-engineering in the error message prevents automatic retry loops after user interruption.

**The KIMI_NODE_FALLBACK_SUBCOMMAND:** When running as a compiled single binary (no system Node.js), plugins that declare `"command": "node"` in their MCP server config are automatically redirected to `process.execPath [KIMI_NODE_FALLBACK_SUBCOMMAND] [args]`, which re-enters the binary as a Node interpreter. This is a clever distribution trick — single binary that can also serve as a Node.js subprocess.

---

## Data flow

**Tool call lifecycle:**
```
LLM response arrives (streaming)
  → tool-call.ts: collect all tool calls from response
  → validate each tool call's args (Zod schema via compileToolArgsValidator)
  → run PreToolUse hooks (triggerBlock) — if blocked, return error result
  → dispatch tool.call event to loop dispatcher
  → ToolScheduler.add(task): parallel if no resource conflict, serialized if conflict
  → BashTool (or other): execute via Kaos abstraction
  → PathSecurityError if outside workspace or sensitive file
  → collect results
  → run PostToolUse hooks (fireAndForgetTrigger — non-blocking)
  → dispatch tool.result events in provider order
  → results appended to conversation history
```

**Plugin install:**
```
PluginManager.install("github:owner/repo")
  → resolveGithubSource: GitHub API → tarball URL + ref metadata
  → downloadZip, extractZip to tmpDir
  → parseManifest: discover plugin.json / kimi-plugin.json
  → copyPluginToManagedRoot: atomic (tmp → rename)
  → persist to installed.json
  → on next session: pluginSkillRoots() → skill discovery
  → enabledMcpServers() → MCP server wiring with KIMI_PLUGIN_ROOT env
```

---

## Dependencies

| Package | Why |
|---|---|
| Node.js ≥ 24.15.0 | Runtime (strict engines enforcement via .npmrc engine-strict) |
| pnpm 10.33.0 | Workspace management |
| tsdown | Build/bundle (replaces tsup) |
| zod | Tool argument schema validation |
| `@antfu/utils` (createControlledPromise) | Concurrent tool scheduling primitives |
| pathe | Cross-platform path utilities (used in path-access guard) |
| vitest | Test runner |
| Nix + flake.nix | Reproducible build environment (hardcoded workspace paths — a maintenance burden) |

---

## Security model

**Workspace boundary enforcement:** `PathSecurityError` with code `PATH_OUTSIDE_WORKSPACE` blocks all read/write/search outside the configured workspace dir. Lexical canonicalization prevents shared-prefix escapes. The guard is applied at every file tool.

**Sensitive file detection:** `isSensitiveFile()` with explicit exemptions (`.env.example`, public keys, `.env.sample`, `.env.template`). Covers SSH keys, credential files, `.env` variants, and rename-shielded variants.

**PreToolUse blocking:** `HookEngine.triggerBlock()` is synchronous-semantics (async but blocking the tool call loop) — the tool does not execute until all PreToolUse hooks resolve. A hook returning `action: 'block'` stops execution entirely.

**No network-egress firewall:** Unlike gstack's pipelock-inspired egress scanning, kimi-code has no built-in mechanism to scan outbound network traffic from tools. [inference — not observed in the code read]

**Plugin trust:** Plugins are installed with user action (`/plugins install`), and trust level is surfaced at install time (per README). There is no code-signing or sandboxing of plugin MCP servers. Per AGENTS.md: "each install's trust level surfaced up front."

**Experimental feature gating:** Features that are not production-ready are gated behind env flags, preventing accidental exposure. The `default: false` registry is the mechanism.

---

## Testing strategy

- **Unit tests** via vitest: `pnpm test`
- **TypeScript validation**: `pnpm typecheck`
- **Linting**: oxlint (`pnpm lint`)
- **E2E tests** in `packages/server-e2e/`: live tests against a running server (`KIMI_SERVER_URL`, default `http://127.0.0.1:58627`)
- **Nix check**: `scripts/check-nix-workspace.mjs` validates flake.nix workspace sync against pnpm-workspace.yaml — documented as incomplete (only validates the transitive dependency closure of `@moonshot-ai/kimi-code`, not leaf packages)

The AGENTS.md rule "do not add too many new test files; prefer adding tests to the existing test file of the corresponding component" suggests test files are intentionally consolidated. The test infrastructure appears solid at the unit level but the E2E coverage depends on the server being available.

---

## Genuinely reusable elements

**The `HookEngine` class (MIT):** The most complete publicly available TypeScript implementation of an agent hook engine. 20 event types, dedup, blocking vs non-blocking semantics, `HookMatcherValue` as string or ContentPart[], observable via `onTriggered`/`onResolved` callbacks. Directly usable in any TypeScript agent runtime.

**The `ToolAccesses` + `ToolScheduler` pattern (MIT):** Typed resource-conflict detection and concurrent tool scheduling is a clean, independently useful primitive. Any agent that executes multiple tool calls per turn could use this to safely parallelize non-conflicting reads while serializing writes.

**The `PathSecurityError` + `isSensitiveFile()` policy layer (MIT):** A complete, production-grade file access security implementation with rename-shielded variant detection and explicit exemptions. Reusable as-is in any TypeScript file-handling agent.

**The `PluginManager` with GitHub-native install and atomic staging (MIT):** Plugin install with tmp-dir staging and atomic rename-on-success is a clean pattern. The KIMI_NODE_FALLBACK_SUBCOMMAND approach for single-binary plugin compatibility is an unusual but practical distribution trick.

**The experimental feature flag registry pattern (MIT):** Simple, self-documenting, env-driven feature flags with a clear release path. Adaptable to any TypeScript project.

---

## What NOT to reuse

**The Nix flake.nix with hardcoded workspace paths:** The `workspacePaths` and `workspaceNames` lists in `flake.nix` must be manually kept in sync with `pnpm-workspace.yaml`. The automated check only validates the transitive closure of the main app, not leaf packages. This is a documented maintenance burden — do not adopt this pattern without a fix.

**The compiled binary distribution approach (Mach-O arm64 in the snapshot):** The `build` directory was removed from the vendored snapshot. Source requires Node.js ≥ 24.15.0 and pnpm 10.33.0 — a higher runtime requirement than gstack (Bun) or dotclaude (bash + jq).

**The kimi-web Vue 3 UI:** Useful as a reference for a browser-based agent session UI, but it re-implements wire types locally instead of importing from `agent-core`, creating dual-maintenance. Not worth adopting the duplication pattern.

---

## Production-readiness

**Production-ready.** This is the only source among the four that is a complete, shipped production product (available via `brew install kimi-code`, official install scripts, OAuth authentication, versioned SDK). The monorepo structure, strict TypeScript, vitest testing, Zod schema validation, lexical path security, and experimental flag gating all indicate production engineering discipline. The architectural constraints documented in AGENTS.md (Agent class independence, no `agent-core` dependency in apps) are enforced as contributor rules, suggesting these invariants are maintained. The Nix flake maintenance burden is a notable gap.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- The most complete agent runtime architecture among the four sources
- 20-event hook system is significantly richer than Claude Code's hooks
- Resource-conflict concurrent tool scheduling is genuinely novel and correct
- Production-grade path security with rename-shielded variant detection
- Clean monorepo architecture with enforced dependency boundaries
- Single-binary distribution with embedded Node.js fallback for plugins

**Weaknesses:**
- High runtime requirement (Node.js ≥ 24.15.0) vs Bun or bash
- No built-in network-egress security (no equivalent to pipelock or gstack's prompt injection defense) [inference]
- Nix flake maintenance burden (manually synced hardcoded paths)
- kimi-web re-implements wire types — two sources of truth for the API shape

**Technical debt:**
- `flake.nix` workspace sync documented as incomplete — leaf packages can slip out of sync
- `kimi-migration-legacy` and `migration-legacy` packages in the monorepo suggest historical migrations not yet fully cleaned up
- The `subagent-host.ts` in session/ suggests subagent support may be partially implemented [inference — only directory structure read]

---

## Possible clean-room adaptations

**A standalone TypeScript hook engine library:** Extract `HookEngine`, `HookDef`, `HookResult`, and `runner.ts` as a standalone npm package. Add support for the Claude Code JSON protocol (for cross-runtime compatibility) and publish under a permissive license. This would be the definitive cross-agent hook engine implementation.

**A tool-concurrency scheduler library:** Extract `ToolAccesses` + `ToolScheduler` as a standalone npm package. Any agent that receives multiple tool calls per turn and wants safe concurrent execution (reads parallel, writes serialized by path) could use this directly.

**A file security policy library:** Extract `path-access.ts` + `sensitive.ts` as a standalone npm package with a configuration interface for workspace root, additional sensitive patterns, and additional exemptions. Publishable as `@agent/file-policy` or similar.

---

## Business applications

**Enterprise agent SDK:** The `packages/node-sdk` is a public TypeScript SDK already. Packaging this as an enterprise offering with managed authentication, audit logging, centralized hook policy distribution, and SLA would address the enterprise AI coding agent market.

**Agent security layer:** The hook engine + path security + sensitive file detection combination is a deployable agent security layer. A managed service that provides this as a pre-wired, policy-configurable layer for enterprise TypeScript agents (without the full Kimi Code stack) addresses the same market as gstack's security features.

**Multi-agent orchestration runtime:** The `SubagentStart/SubagentStop` hook events and the Agent class independence constraint suggest kimi-code is designed to support subagent orchestration natively. A commercial orchestration runtime built on this foundation (coordinating multiple Kimi/Claude/Gemini agents with unified audit trails and resource conflict detection) would be commercially viable.

---

## Related business ideas in this lab

- `business-research/startup-white-spaces/agent-permission-firewall.md` — kimi-code's hook engine + path security is the production-grade implementation of the firewall concept; this lab's prototype is a simpler version of the same idea

---

## Related sources in this lab

- `sources/deterministic-agent-safety/` (dotclaude): implements a simpler version of the same hook contract in bash; kimi-code is the TypeScript production-grade version
- `sources/structured-agent-development/` (superpowers): Superpowers is explicitly supported in Kimi Code's plugin marketplace; the skill/hook patterns are directly compatible
- `sources/full-product-engineering-agent-stack/` (gstack): gstack also ships a TypeScript browser daemon; kimi-code's architecture is more modular and shows what a production-grade agent runtime looks like under the hood

---

## Open questions

1. What is the actual API surface of `packages/node-sdk` vs `packages/agent-core`? Are there public API stability guarantees?
2. How does the `PermissionRequest`/`PermissionResult` hook event interact with Claude Code's `ask` decision pattern? Are they compatible at the protocol level?
3. Does the `SubagentStart/SubagentStop` hook event support the same tool-call concurrent scheduling? Or do subagents run in fully isolated contexts?
4. What is in `packages/agent-core/src/profile/` — the persona/profile system for agents? Could this be used to create per-deployment agent personalities (e.g., a "security-auditor agent" with a distinct profile)?

---

## Final research conclusion

kimi-code is the most architecturally complete source in this lab: it implements the agent runtime from first principles, with the richest hook system (20 events), the only correct concurrent tool scheduler, and production-grade file security. The `HookEngine`, `ToolAccesses/ToolScheduler`, and `PathSecurityError/isSensitiveFile` modules are directly extractable as standalone libraries under MIT license and would significantly raise the quality floor of any TypeScript agent implementation built on them.
