# Source Research Dossier: persistent-agent-memory

## Repository identity

- **Name:** claude-mem
- **Creator:** Alex Newman (thedotmack)
- **GitHub URL:** https://github.com/thedotmack/claude-mem
- **Source path:** sources/persistent-agent-memory/upstream/
- **License:** Apache-2.0 (verified — sources/persistent-agent-memory/upstream/LICENSE)
- **Import type:** Vendored snapshot
- **Pinned commit:** 87e4836a86909bbfd056777a37546db443f90868
- **Retrieved at:** 2026-06-22T05:11:03Z
- **Package version at snapshot:** 13.8.0 (sources/persistent-agent-memory/upstream/package.json)
- **Community size:** 46,100–83,700 GitHub stars (multiple sources; range reflects reporting date differences); 3,500 forks; 92 contributors (https://www.augmentcode.com/learn/claude-mem-46k-stars-persistent-memory-claude-code; https://skillsllm.com/skill/claude-mem)

---

## What it actually does

Claude-mem is a Claude Code plugin that injects a persistent memory layer between Claude Code sessions. It intercepts six Claude Code lifecycle hooks (Setup, SessionStart, UserPromptSubmit, PostToolUse, PreToolUse, Stop) and routes tool-use events to a Bun-managed background worker process. The worker stores observations, session summaries, and user prompts in a local SQLite database (with FTS5 full-text search) and a Chroma vector database for semantic search. On each new session start, the worker compiles a contextual digest and injects it back into Claude's context window via `hookSpecificOutput.additionalContext`, giving Claude continuity of knowledge across disconnected sessions.

The project also exposes 4 MCP tools (search, timeline, get_observations, and a desktop search tool) so users can query memory from inside any Claude conversation, following a 3-layer progressive-disclosure workflow designed to minimize token spend.

---

## Architecture

```
Claude Code CLI
      |
   Hook Scripts (plugin/hooks/hooks.json — 6 lifecycle hooks)
      |
   bun-runner.js → worker-service.cjs (Bun process on port 37777)
      |
   HTTP API (Express + BullMQ queue)
      |
  ┌──────────┬──────────┬──────────────┐
  SQLite DB  Chroma DB   Redis (opt.)   Context Builder
  (FTS5)     (vectors)                  (ContextBuilder.ts)
```

The hook scripts are thin shell wrappers that call `node bun-runner.js worker-service.cjs hook claude-code <handler>`. The worker is a long-lived process; hooks POST events to it over localhost HTTP. If the worker is unreachable, hooks silently succeed (fail-open) so Claude is never blocked.

Context injection uses a token-budget-aware `ContextBuilder` that compiles observations into a layered digest. The `ModeManager` loads behavioral modes from `plugin/modes/` (JSON files), which can be inherited with `--` notation (e.g., `code--zh` inherits from `code` and overrides language).

---

## Main modules and important files

| Path | Purpose |
|------|---------|
| `src/cli/adapters/claude-code.ts` | Normalizes raw Claude Code hook JSON into a `NormalizedHookInput` struct |
| `src/cli/adapters/codex.ts`, `cursor.ts`, `windsurf.ts`, `gemini-cli.ts` | Per-IDE adapters for the same normalization contract |
| `src/cli/handlers/observation.ts` | `PostToolUse` handler — routes tool-use events to worker or server-beta |
| `src/cli/handlers/context.ts` | `SessionStart` handler — fetches and injects context digest |
| `src/cli/handlers/file-context.ts` | `PreToolUse/Read` handler — adds file-access observations |
| `src/services/context/ContextBuilder.ts` | Assembles the per-session context digest with token budgeting |
| `src/services/context/ObservationCompiler.ts` | Formats observations into a human-readable narrative |
| `src/services/domain/ModeManager.ts` | Singleton that loads and resolves mode JSON configs with inheritance |
| `src/servers/mcp-server.ts` | MCP server exposing search/timeline/get_observations tools |
| `src/shared/worker-utils.ts` | Core HTTP client for hook-to-worker communication, with timeout/fallback |
| `src/types/database.ts` | SQLite schema types: `ObservationRecord`, `SessionSummaryRecord`, `UserPromptRecord` |
| `plugin/hooks/hooks.json` | Hook registration config with all 5 lifecycle event entries |
| `plugin/skills/mem-search/` | Skill definition for natural-language memory queries |
| `ragtime/ragtime.ts` | Agentic evaluation harness — runs Claude Agent SDK against a corpus |

---

## Core technical patterns

**1. Hook-to-Worker IPC over localhost HTTP** (`src/shared/worker-utils.ts`)
Hooks are short-lived processes; they POST events to a persistent worker via `fetch` with `AbortSignal.timeout`. On timeout or connection refused, `isWorkerFallback()` returns true and the hook exits with `continue: true` so Claude is never blocked.

**2. Adapter-normalized input** (`src/cli/adapters/`)
Every IDE (Claude Code, Codex, Cursor, Windsurf, Gemini CLI) maps its raw hook JSON through a `PlatformAdapter.normalizeInput()` → `NormalizedHookInput` contract. New IDE support requires only a new adapter file, not changes to handlers.

**3. Progressive-disclosure memory retrieval** (`plugin/skills/mem-search/skill.md`)
The 3-layer pattern (search index → timeline context → full batch fetch) is a deliberate API contract: `search` returns 50-100 tokens per result; `get_observations` returns 500-1000 tokens. The workflow forces callers to filter before expanding, achieving claimed ~10x token savings.

**4. Mode inheritance via double-dash naming** (`src/services/domain/ModeManager.ts`)
Mode IDs like `code--zh` are parsed by splitting on `--`: the first segment is the parent ID, the full ID is the override. The manager merges the parent's config with the override's keys, enabling localization overlays without duplicating base behavior.

**5. IO discipline: pure handler contract** (`src/cli/handlers/observation.ts`, `context.ts`)
All handlers are documented as "PURE" — they must not write to stdout/stderr/console directly. All output goes through `HookResult` fields (`hookSpecificOutput`, `systemMessage`, `exitCode`). `logger.*` calls route to a separate diagnostic stderr path managed by `hook-io.ts`.

**6. Server-beta dual-path** (`src/cli/handlers/observation.ts`, `src/services/hooks/server-beta-client.ts`)
The observation handler checks `resolveRuntimeContext()` — if the runtime is `server-beta`, it sends events to a remote cloud endpoint; on `isFallbackEligible()` errors it falls back to the local worker. This enables a future hosted/SaaS mode.

---

## Novel or interesting mechanisms

**Ragtime agentic eval harness** (`ragtime/ragtime.ts`): Uses the Anthropic Claude Agent SDK (`@anthropic-ai/claude-agent-sdk`) to run Claude against a corpus of Markdown files in automated sessions, with claude-mem's memory hooks active. This is a self-referential evaluation — the memory system is evaluated using a system that itself uses the memory system. The corpus is named `epstein-mode` [inference: refers to an "investigation mode" behavioral profile, possibly for email/document analysis tasks].

**Endless Mode (beta)**: Described in README as a "biomimetic memory architecture for extended sessions" — a beta channel feature accessible via the web viewer at localhost:37777. The implementation details are not in the vendored snapshot's visible source; it may be in a compiled bundle. [inference: Endless Mode likely implements a hierarchical memory compaction strategy, similar to MemGPT's archival/recall tier model, but triggered at session continuity events rather than context-window overflow.]

**Privacy tag stripping** (`src/utils/tag-stripping.ts`): Content wrapped in `<private>` tags is stripped before storage, giving users a lightweight inline privacy control.

**Multi-machine sync** (`src/services/sync/`): A `claude-mem-sync` feature replicates observations and session summaries between machines over SSH. [inference: This is a significant differentiator for teams, enabling shared institutional memory across developer machines.]

---

## Data flow

```
Claude tool use (e.g., Bash/Read/Edit)
  → PostToolUse hook fires
  → observation.ts handler receives NormalizedHookInput
  → POSTs {sessionId, toolName, toolInput, toolResponse, cwd} to worker /api/sessions/observations
  → Worker queues via BullMQ
  → Worker processes: AI summarizes tool event → ObservationRecord written to SQLite + Chroma
  → Worker returns 200 OK (hook continues)

Session start:
  → SessionStart hook fires → context.ts handler
  → GETs /api/context/inject?projects=... from worker
  → ContextBuilder queries SQLite (FTS5) + Chroma (semantic)
  → Returns ranked observation digest within token budget
  → Handler returns {hookSpecificOutput: {additionalContext: <digest>}}
  → Claude Code injects digest into system prompt for new session
```

---

## Dependencies

**Runtime:**
- `@anthropic-ai/claude-agent-sdk` — AI-powered summarization of tool observations
- `@modelcontextprotocol/sdk` — MCP server implementation
- `express` — Worker HTTP API
- `bullmq` + `ioredis` — Job queue for async observation processing (Redis optional; falls back to in-process)
- `better-auth` + `@better-auth/api-key` — Worker API authentication
- `pg` — PostgreSQL support (optional, for server-beta cloud mode)
- `zod` — Schema validation throughout
- `posthog-node` — Telemetry/analytics
- `react` / `react-dom` — Web viewer UI

**Runtime management:**
- Bun (auto-installed) — Worker process runtime
- uv (auto-installed) — Python package manager for Chroma vector DB

**Storage:**
- SQLite 3 with FTS5 (bundled via better-sqlite3)
- Chroma (Python process, via uv)

---

## Security model

The plugin installs hooks that run on every PostToolUse event and every Read tool call. Each hook command is a full shell invocation with PATH manipulation, executing `node bun-runner.js worker-service.cjs hook claude-code <handler>` at the hook event.

**What the hooks see:** Tool name, tool input (file paths, shell commands), tool output (file contents, command results), and cwd.

**Risks:**
- Every file read and every shell command executed by Claude is captured and sent to the local worker. If Chroma's HTTP interface is exposed beyond localhost, observations (including file contents) could leak.
- The hooks have broad `"matcher": "*"` — they fire for all tools unconditionally.
- Telemetry via PostHog (`posthog-node`) is active by default [inference: telemetry settings may be opt-in, but the dependency is present in production].
- The `security_review_status: pending` in SOURCE.yaml confirms this has not been formally reviewed.
- The `<private>` tag mechanism is the only user-level privacy control; there is no per-tool or per-project exclusion beyond `shouldTrackProject()` (which reads a project blocklist from settings).

---

## Testing strategy

The `tests/` directory exists at the project root. The package.json dev dependencies include tree-sitter grammars for multiple languages, suggesting smart-read/parser tests. The `evals/` directory is present alongside `ragtime/`, which provides an end-to-end agentic evaluation pattern. No formal test framework (Jest, Vitest) was visible in dev dependencies; [inference: tests may use Bun's native test runner given the `bunfig.toml` at root].

---

## Genuinely reusable elements

**1. Adapter-normalized hook input pattern** (`src/cli/adapters/`)
The `PlatformAdapter` interface with per-IDE adapters normalizing raw hook JSON to a common struct is a clean, extensible pattern reusable in any multi-IDE hook system. Apache-2.0 license — clean-room adaptation is straightforward.

**2. Progressive-disclosure MCP tool design** (`plugin/skills/mem-search/skill.md`)
The 3-layer search workflow (index → timeline → batch fetch) is a token-efficiency pattern applicable to any memory or knowledge retrieval MCP. It is expressed as a skill spec, not tightly coupled to claude-mem's storage layer.

**3. Fail-open hook architecture**
The pattern of hooks that POST to a localhost worker, with silent fallback to `continue: true` on worker unavailability, ensures Claude Code is never blocked by the memory plugin. This is a robust pattern for any sidecar plugin.

**4. Mode inheritance via double-dash naming** (`src/services/domain/ModeManager.ts`)
A lightweight behavioral configuration inheritance system using filesystem-based config files and a naming convention, avoiding a complex plugin system.

**5. IO discipline for pure handlers**
Strict separation of handler output (via `HookResult`) from diagnostic logging (via `logger.*`) is a testability pattern worth adopting in any hook handler system.

---

## What NOT to reuse

- The `ragtime/` corpus (`epstein-mode` dataset) — context is project-specific and the name carries reputational risk.
- The PostHog telemetry integration — introduces an external dependency and privacy implications.
- The `better-auth` session/API-key infrastructure — significant complexity for a sidecar plugin; only relevant for the server-beta hosted mode.
- The CMEM token integration (README bottom section) — this is a cryptocurrency community token with no engineering value.
- The BullMQ + Redis dependency stack — adds operational complexity; only justified at high observation volumes.

---

## Production-readiness

**MVP-quality to production-ready, depending on deployment scope.**

For a single-developer local install: production-ready. The hook architecture is robust (fail-open), the SQLite + Chroma stack is self-contained, and 223 releases with 92 contributors indicates sustained maintenance.

For enterprise or team use: MVP-quality. The security model has not been formally reviewed (`security_review_status: pending`), telemetry is active, and multi-machine sync via SSH is a beta feature. The Redis/BullMQ stack adds operational overhead not justified by local use.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- Extremely rapid community adoption (46K+ stars in under a year) validates product-market fit
- Fail-open hook design means the plugin never degrades Claude Code's core functionality
- Multi-IDE adapter pattern makes the system broadly applicable
- The 3-layer progressive disclosure MCP design is genuinely novel and token-efficient
- Apache-2.0 license enables commercial embedding

**Weaknesses:**
- Privacy surface is large: every tool call (including file reads and shell commands) is captured
- Chroma's Python subprocess dependency adds startup latency and a Python toolchain requirement
- No formal security review
- The web viewer UI (React) is bundled with the worker, significantly inflating bundle size for local use
- Redis is an optional but architecturally significant dependency — its presence suggests the system was designed for a hosted mode that isn't yet the primary use case

**Technical debt:**
- `security_review_status: pending` — no formal threat model
- The Ragtime evaluation harness (`ragtime/ragtime.ts`) is tightly coupled to a single corpus path and mode; it needs generalization to be useful as a reusable eval framework
- The shell commands in `hooks.json` are dense and fragile (PATH manipulation, cygpath Windows compat, multiple fallback paths all inline)

---

## Novel or differentiated elements

1. **Hook-native memory persistence for agent CLIs** — Unlike Mem0/Zep which require SDK-level integration, claude-mem works purely via Claude Code's hook system, requiring zero changes to agent code.
2. **Progressive-disclosure MCP search** — The 3-layer search workflow is a first-class UX pattern, not just a library function.
3. **Mode-based behavioral profiles** — Changing the observation compression prompt and language via a config file (not code) allows community-contributed behavioral modes.
4. **Ragtime agentic evaluation** — Using the memory system to evaluate itself via Claude Agent SDK sessions is a novel self-referential testing approach.
5. **Claim of ~10x token savings** via the index-filter-fetch pattern — if validated, this is a meaningful efficiency contribution to the broader agent memory space.

---

## Possible clean-room adaptations

- **Generic hook-to-sidecar adapter framework** — Extract the `PlatformAdapter` interface, `NormalizedHookInput`, and the fail-open HTTP dispatch pattern into a standalone library for building Claude Code plugins that use background workers.
- **Token-budget-aware context compiler** — Re-implement `ContextBuilder` / `ObservationCompiler` logic for any RAG system that needs to fit retrieved content within a token budget, without the SQLite/Chroma coupling.
- **Skill-based memory search spec** — The 3-layer MCP search skill spec is a design pattern document more than code; adapt it as a standard for any project that exposes memory retrieval via MCP.

---

## Business applications

1. **Team shared memory for agentic coding workflows** — Extend claude-mem's server-beta mode into a hosted SaaS where teams share observations. Developers query "what decisions did our team make about this module last month?" — without the context window limitations of today's tools.
2. **Compliance audit trail** — Every tool call is already captured; with proper schema design, the observation store becomes an immutable audit log of what an AI coding assistant did, when, and why. Valuable in regulated industries.
3. **Personalized AI coding assistant fine-tuning dataset** — The structured observation records (type: bugfix/feature/decision/refactor) are a labeled dataset of real developer-AI interaction events. A company could use this to fine-tune a model on their own codebase patterns.
4. **Multi-project knowledge graph** — The `project` field in `ObservationRecord` and `SessionSummaryRecord` enables cross-project knowledge linking. A product could surface: "this pattern was used in project A; here's the implementation from 3 months ago."

---

## Related business ideas in this lab

- The RAG pipeline in `sources/modular-rag-learning/` (FAISS + LangChain) could replace or augment Chroma in the claude-mem vector search layer.
- Claude Code skills from `sources/anthropic-skills/` and `sources/vercel-skills/` could be bundled as claude-mem-aware skills (e.g., a standup skill that queries memory for recent work).

---

## Related sources in this lab

- `sources/modular-rag-learning/` — complementary RAG pipeline for document-based retrieval
- `sources/anthropic-skills/` — official skill architecture that claude-mem's skill system (plugin/skills/) aligns with
- `sources/vercel-skills/` — skills CLI tooling that references claude-mem as a known agent target

---

## Open questions

1. What is the actual token budget algorithm in `ContextBuilder.ts`? How does it handle projects with thousands of observations without exceeding context limits?
2. Is the Chroma vector DB queried at every session start, or only when FTS5 recall is insufficient? What is the latency profile at 10K+ observations?
3. What does "Endless Mode" actually implement architecturally? The README describes it as "biomimetic" — is it a hierarchical compression scheme similar to MemGPT's archival tier?
4. How does the server-beta cloud endpoint authenticate? The `better-auth` + `@better-auth/api-key` dependency suggests OAuth/API-key, but the cloud service is not publicly documented.
5. At what point does the BullMQ job queue require Redis vs. running in-process? Is there a concurrency threshold?

---

## Final research conclusion

Claude-mem is the most technically sophisticated and community-validated agent memory plugin for Claude Code as of mid-2026. Its Apache-2.0 license, fail-open architecture, multi-IDE adapter pattern, and progressive-disclosure MCP search design make it a strong candidate for adaptation. The primary risks are its pending security review, broad telemetry footprint, and the operational complexity introduced by Chroma (Python subprocess) and the optional Redis/BullMQ stack.

For this lab, the highest-value extraction target is the **adapter-normalized hook input pattern** and the **3-layer progressive-disclosure MCP search workflow** — both are portable, license-clean, and address real problems in building robust agent plugins.
