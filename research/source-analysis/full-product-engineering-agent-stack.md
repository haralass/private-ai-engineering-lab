# Source Research Dossier: full-product-engineering-agent-stack

## Repository identity

- **Name:** gstack
- **Creator:** Garry Tan (garrytan), President & CEO of Y Combinator
- **GitHub URL:** https://github.com/garrytan/gstack
- **Source path:** sources/full-product-engineering-agent-stack/upstream/
- **License:** MIT (verified)
- **Import type:** vendored-snapshot
- **Pinned commit:** 9fd03fae9e74f5daa7a138366aca8f86c7367c5c
- **Retrieved:** 2026-06-22
- **Removed paths:** `.git/`, `lib/diagram-render/dist`

---

## What it actually does

gstack is a comprehensive AI-assisted software development methodology delivered as a collection of 37+ Claude Code slash-command skills, 23+ specialist subagent definitions, standalone CLI binaries, and a persistent Chromium browser daemon. Its core loop is `Think → Plan → Build → Review → Test → Ship → Reflect`, with each stage handled by a dedicated specialist role (YC Office Hours, CEO review, Eng Manager, Senior Designer, QA Lead, CSO, Release Engineer). The browser daemon provides sub-100ms persistent Playwright automation. gstack integrates with 10 different AI coding agents (Claude Code, Codex CLI, Cursor, Factory Droid, etc.), ships prompt-injection defense for its browser sidebar agent, and supports parallel multi-sprint workflows via Conductor-managed workspaces.

---

## Architecture

**Three-tier system:**

1. **Skill/agent layer (Markdown):** SKILL.md files that Claude Code reads as slash commands. These are the UI — all user-facing behavior is expressed here. Generated from `.tmpl` templates by `scripts/gen-skill-docs.ts`.

2. **Browser daemon layer (TypeScript/Bun):** A long-lived Chromium process managed by a Bun HTTP server on a random localhost port. The CLI binary (`browse/dist/browse`) communicates via HTTP POST. State is persisted to `.gstack/browse.json` (PID, port, UUID token, version hash). 30-minute idle auto-shutdown. Version auto-restart on binary upgrade.

3. **Design binary layer (TypeScript/Bun):** A separate compiled binary for GPT Image API calls, comparison board serving, and design variant management.

```
Claude Code session
  → Skill SKILL.md loaded at session start
  → User invokes /qa, /review, /cso, etc.
  → Skill prose describes methodology to Claude
  → Claude executes $B <command> via Bash tool
  → CLI binary reads .gstack/browse.json, HTTP POSTs to browse daemon
  → Browse daemon talks to Chromium via CDP
  → Results returned as plain text to Claude
```

---

## Main modules and important files

| Path | What it does |
|---|---|
| `browse/src/server.ts` | Bun HTTP server: command dispatch, dual-listener tunnel architecture, Unicode sanitization, SSE endpoints, auth |
| `browse/src/commands.ts` | Single source of truth command registry; READ/WRITE/META categorization |
| `browse/src/snapshot.ts` | ARIA tree snapshotting; assigns @ref handles to elements |
| `browse/src/security.ts` | Canary token injection/detection, combineVerdict ensemble logic, attack log |
| `browse/src/security-classifier.ts` | ONNX ML classifier (TestSavantAI) + Claude Haiku transcript classifier for prompt injection |
| `browse/src/content-security.ts` | L1-L3 content security: datamarking, hidden-element strip, ARIA regex, URL blocklist |
| `browse/src/cdp-bridge.ts` | Chrome DevTools Protocol session management |
| `scripts/gen-skill-docs.ts` | Template → SKILL.md generator; resolves 18+ placeholders from source code metadata |
| `scripts/resolvers/preamble.ts` | Shared preamble injected into every skill: update check, session tracking, AskUserQuestion format, writing style |
| `hosts/` | Typed TypeScript config for each supported AI agent host; used by `setup` script |
| `SKILL.md.tmpl` | Main browse skill template (human-written prose + code-derived placeholders) |
| `review/SKILL.md` | Staff Engineer review skill |
| `cso/SKILL.md` | CSO security audit: OWASP Top 10 + STRIDE, 17 FP exclusions, 8/10+ confidence gate |
| `office-hours/SKILL.md` | YC Office Hours: 6 forcing questions, product interrogation |
| `autoplan/SKILL.md` | Meta-skill: chains CEO → design → eng → DX review automatically |
| `bin/gstack-detach` | Daemonization wrapper: `caffeinate -i` + fresh process group, machine-wide eval lock |
| `lib/redact-patterns.ts` | Three-tier secret/PII/legal redaction engine with HIGH/MEDIUM/LOW classification |

---

## Core technical patterns

**Pattern 1 — Template-driven Markdown generation**
SKILL.md files are committed artifacts generated from `.tmpl` templates. `gen-skill-docs.ts` reads command registries and snapshot metadata to fill 18 placeholders. This means: if a `$B` command exists in code it appears in docs; if it doesn't exist, it can't appear. CI validates freshness with `--dry-run` + `git diff --exit-code`.
Location: `scripts/gen-skill-docs.ts`, `SKILL.md.tmpl`, `scripts/resolvers/`

**Pattern 2 — Dual-listener tunnel architecture**
When `pair-agent` starts an ngrok tunnel, the daemon binds two separate TCP sockets: a local listener (full surface) and a tunnel listener (restricted allowlist: `/connect`, `/command` with scoped tokens only, `/sidebar-chat`). Security isolation comes from physical port separation — tunnel callers physically cannot reach `/health` or `/cookie-picker` because those endpoints don't exist on that socket. Header-based origin checks are explicitly rejected as unreliable.
Location: `browse/src/server.ts`, `ARCHITECTURE.md#dual-listener-tunnel-architecture`

**Pattern 3 — Ref system for DOM addressing**
Playwright Locators are generated from the ARIA accessibility tree during `snapshot`, assigned sequential @ref handles (@e1, @e2...). Later commands resolve @ref → Locator. No DOM mutation, no CSP conflicts. Stale refs are detected via async `count()` check before use — fails in ~5ms vs 30-second Playwright timeout.
Location: `browse/src/snapshot.ts`, `ARCHITECTURE.md#the-ref-system`

**Pattern 4 — Layered prompt injection defense**
Six layers: L1-L3 content security (datamarking, hidden element strip, URL blocklist), L4 ONNX ML classifier (22MB TestSavantAI, bundled), L4b Claude Haiku transcript classifier, L5 canary token (random UUID injected into system prompt, rolling-buffer detection in all output streams), L6 ensemble combiner (BLOCK requires 2+ classifiers at ≥0.75, preventing single-model false positives). The canary check is deterministic; ML layers are probabilistic.
Location: `browse/src/security.ts`, `browse/src/security-classifier.ts`, `browse/src/content-security.ts`

**Pattern 5 — Three-tier LLM eval system**
Tier 1: static validation (parse all `$B` commands, validate against registry) — free, <2s. Tier 2: E2E via `claude -p` subprocess (spawn real Claude session, run each skill) — ~$3.85. Tier 3: LLM-as-judge (Sonnet scores docs) — ~$0.15. Tier 1 on every `bun test`. Tiers 2+3 gated behind `EVALS=1`. Diff-based test selection: each E2E test declares its file dependencies; only affected tests run.
Location: `test/`, `CLAUDE.md#two-tier-system`

**Pattern 6 — Taste memory with decay**
`gstack-taste-update` CLI writes design approvals/rejections from `/design-shotgun` to a per-project profile. Decays 5%/week. Feeds back into future variant generation via the `/design-shotgun` loop. This is a simple but deployable approach to preference learning.
Location: `bin/gstack-taste-update`, documented in README

---

## Novel or interesting mechanisms

**The `$PPID`-based parallel session detection:** `session-start.sh` touches `~/.gstack/sessions/$PPID` and counts files modified in the last 2 hours. When 3+ sessions are running, all skills enter "ELI16 mode" — every AskUserQuestion re-grounds the user on project context. This is a lightweight, no-server solution to the context-loss problem in parallel workflows.

**Version auto-restart for the browse daemon:** The binary writes `git rev-parse HEAD` to `browse/dist/.version` at compile time. On each CLI invocation, if the running server's `binaryVersion` doesn't match the current binary version, the CLI kills the old server and starts fresh. This eliminates the entire class of "stale binary" bugs without requiring the user to manually restart anything.

**Unicode sanitization at server egress:** CDP page content can contain lone UTF-16 surrogate halves that `JSON.stringify` emits as `\uD800`-style escapes the Anthropic API rejects with a 400. The fix uses a `JSON.stringify` replacer function applied at every server egress path (four points). Post-stringify regex would fail (the surrogates are already escaped). The architectural invariant is documented in source comments and enforced by a test that fails CI if any new SSE/WebSocket writer bypasses the replacer.

**The "HARD-GATE" pattern in Markdown skills:** Multiple skills (office-hours, cso, review) contain explicit `<HARD-GATE>` XML blocks instructing Claude not to proceed until a specific prerequisite is met. This is a prompt-engineering technique for enforcing ordered behavior in conversational skills — more reliable than prose instructions because the XML wrapper signals constraint rather than suggestion. [inference from reading skills]

**Redact engine as a pre-commit/pre-publish guardrail:** `lib/redact-patterns.ts` implements three tiers (HIGH = block, MEDIUM = confirm, LOW = warn) covering credentials, PII, legal content, and internal identifiers. The `gstack-redact-prepush` CLI is an opt-in git hook. The key design choice: always scan the exact bytes being sent (write to temp file, scan, pass same file to downstream tool) — never scan a string and then re-render.

---

## Data flow

**Normal skill invocation:**
```
User: /qa https://staging.app.com
  → Claude reads qa/SKILL.md, executes methodology prose
  → Runs $B goto https://staging.app.com (via Bash tool)
  → gstack CLI: reads .gstack/browse.json, POST /command to browse daemon
  → Browse daemon: navigates Chromium via CDP, returns snapshot text
  → Claude processes snapshot, executes further $B commands
  → Finds bugs, writes atomic commits with regression tests
```

**Prompt injection defense flow:**
```
Page content arrives via CDP
  → browse/src/content-security.ts: strip hidden elements, apply datamarking
  → browse/src/security-classifier.ts: ONNX scan (L4, local)
  → Claude Haiku transcript scan (L4b, gated by LOG_ONLY 0.40 threshold)
  → security.ts: check canary token in all output streams (L5)
  → combineVerdict (L6): BLOCK if 2+ layers ≥ WARN (0.75)
  → If BLOCK: terminate session, log to attempts.jsonl
```

---

## Dependencies

| Dependency | Why |
|---|---|
| Bun 1.0+ | Single compiled binary, native SQLite for cookie DB, native TypeScript, Bun.serve() |
| Playwright | Chromium automation (CDP abstraction) |
| @huggingface/transformers v4 + onnxruntime-node | ML prompt injection classifier (sidebar agent only, cannot be in compiled binary) |
| ngrok (optional) | Tunnel for pair-agent cross-machine browser sharing |
| OpenAI API | `/design-shotgun` image generation, `/codex` second opinion |
| Anthropic API | All Claude skill executions, Claude Haiku transcript classifier |
| Supabase (optional) | Telemetry, gbrain knowledge base |
| Node.js | Windows fallback for browse server (Bun has bug with Playwright pipe transport on Windows) |

---

## Security model

**Localhost-only daemon:** HTTP server binds to `127.0.0.1`, never `0.0.0.0`. UUIDs as bearer tokens written with mode `0o600`.

**Dual-listener tunnel isolation:** Physical TCP port separation between local and tunnel surfaces. Root token on tunnel returns 403. Tunnel surface: only 26 allowed browser-driving commands with scoped tokens.

**Cookie security:** Cookies decrypted in-process (PBKDF2 + AES-128-CBC), never written to disk. Keychain access requires macOS dialog. DB opened read-only via tmp copy.

**Known gap (documented in ARCHITECTURE.md):** On Windows with App-Bound Encryption v20, cookie-import path launches Chrome with `--remote-debugging-port=<random>`, enabling same-user local process to connect and exfiltrate v20 cookies. Tracked as issue #1136. Fix direction: `--remote-debugging-pipe` instead of TCP.

**Prompt injection defense:** Six-layer defense for the browser sidebar agent (the highest-exposure surface). Canary token is the deterministic backstop.

**Redact engine:** Pre-publish secret/PII/legal scanning with audit trail (`~/.gstack/security/semantic-reviews.jsonl`). Not airtight — `--no-verify` and `GSTACK_REDACT_PREPUSH=skip` bypass it.

---

## Testing strategy

Three-tier eval system:
- **Tier 1 (free, always):** Static skill validation (parse `$B` commands vs command registry), gen-skill-docs quality checks, browse integration tests. <5s.
- **Tier 2 (paid, diff-based):** E2E via `claude -p` subprocess. Each skill is tested end-to-end. Hermetic env (no operator `~/.claude` state, fresh `CLAUDE_CONFIG_DIR`, `--strict-mcp-config`). ~$3.85/run, ~20min.
- **Tier 3 (paid, optional):** LLM-as-judge (Sonnet scores docs on clarity/completeness/actionability). ~$0.15/run.

Gate tier (CI) vs periodic tier (weekly cron) split. Diff-based selection: each test declares touched files in `test/helpers/touchfiles.ts`. The eval infrastructure includes a detached process runner (`bin/gstack-detach`) with `caffeinate -i` to survive macOS idle sleep during long eval runs.

The test infra for NDJSON parsing (`parseNDJSON()`) and eval result persistence (`EvalCollector`) are independently unit-tested. Machine-readable `exit_reason` fields enable `jq` queries over eval results.

---

## Genuinely reusable elements

**The SKILL.md template generation pattern (MIT):** Generating committed markdown from source code metadata is a clean solution to documentation drift. The template/resolver/placeholder architecture in `scripts/gen-skill-docs.ts` is directly adaptable.

**The dual-listener tunnel security pattern (MIT):** Using physical port separation rather than header-based origin checks for tunnel security is correct and non-obvious. This pattern is applicable to any local daemon that needs a network tunnel.

**The layered prompt injection defense stack (MIT):** The six-layer architecture (content security → ONNX classifier → transcript classifier → canary token → ensemble combiner) is the most comprehensive publicly available implementation of browser agent prompt injection defense. Each layer is independently reusable.

**The @ref accessibility tree addressing system (MIT):** Addressing DOM elements via ARIA-tree-derived handles rather than CSS selectors or injected attributes is more robust under CSP and framework reconciliation. Directly adaptable.

**The three-tier eval system (MIT):** The tier classification (static/E2E/LLM-judge) and diff-based test selection are reusable patterns for any AI-adjacent project that needs to gate paid evals.

---

## What NOT to reuse

**The 37+ skills as a monolith:** Individual skills assume the full gstack context (gstack-specific binaries, `~/.gstack/` state, specific CLAUDE.md sections). Extracting individual skills requires stripping dozens of cross-references.

**The browse binary:** The compiled Bun binary is platform-specific (Mach-O arm64 in the vendored snapshot per CLAUDE.md). The source compiles cross-platform but requires Bun and Playwright installation.

**The Windows cookie decryption gap:** App-Bound Encryption v20 issue is documented but unresolved. Do not use the cookie import feature in Windows production environments.

**The slop-scan integration:** `slop-scan.config.json` and the slop metric tracking are gstack-internal quality gates. Importing this into another project without the same culture/process would add noise.

---

## Production-readiness

**Production-ready for individual/small team use. MVP-quality for enterprise.** The browse daemon, security layers, and eval infrastructure are genuinely sophisticated. However: the compiled binaries are platform-specific and must be rebuilt; the Windows cookie decryption gap is unresolved; the system has significant operational complexity (Playwright, Bun, optional ngrok, optional Supabase); and the CLAUDE.md-based configuration assumes teams will maintain their own skill adaptation. Enterprise deployment would require packaging, managed configuration distribution, and audit log export.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- The most complete publicly available AI-assisted development workflow system
- Genuine security depth (prompt injection defense is unusually rigorous)
- Excellent documentation (ARCHITECTURE.md explains every design decision)
- Three-tier eval system with hermetic test environments
- Cross-agent support (10 agents) via typed host configs

**Weaknesses:**
- Large and complex: 37+ skills, multiple compiled binaries, optional cloud integrations
- Significant operational surface: Bun, Playwright, optional ngrok, optional Supabase, optional gbrain
- Windows support is second-class (symlink fallback, cookie decryption gap, Bun/Playwright bug)
- Many skills assume garrytan's personal workflow (YC-specific terminology, personal anecdotes)

**Technical debt:**
- Compiled binaries tracked in git (documented as a historical mistake in CLAUDE.md)
- `allowManagedHooksOnly` gap: no managed distribution mechanism for the hook-equivalent skills
- `SKILL.md` files can exceed 40K tokens for complex skills — documented as a known issue
- `pair-agent` feature (multi-agent browser sharing) has significant complexity that may have edge cases [inference]

---

## Possible clean-room adaptations

**A portable, minimal browse daemon:** Extract the core daemon pattern (HTTP server on random localhost port, Playwright CDP bridge, @ref addressing, dual-listener tunnel) without the full gstack skill stack. This would be a 2,000-line TypeScript library usable by any agent framework.

**A skill template generation system:** The template/resolver/placeholder pattern from `gen-skill-docs.ts` is a clean, independently useful tool for any AI-agent skill author who wants documentation to stay in sync with code.

**A standalone prompt injection defense library:** Extract the six-layer defense stack (`content-security.ts`, `security.ts`, `security-classifier.ts`) as a standalone npm package usable by any browser automation agent.

---

## Business applications

**Enterprise AI-assisted development platform:** Package gstack's sprint methodology (office-hours → plan → build → review → qa → ship → retro) as a managed SaaS with team dashboards, audit trails, and cost controls. The methodology is sound; the monetization path is selling the workflow, not the code.

**Browser automation security layer:** The prompt injection defense stack is independently valuable as a security product for any AI agent that browses the web. Selling this as a standalone library or managed service to enterprise teams running browser-using agents addresses a real security gap.

**AI design system for startups:** The `/design-consultation` + `/design-shotgun` + `/design-html` pipeline is a distinctive feature. A productized version targeting non-technical founders who want to generate production-quality UI would be commercially viable.

---

## Related business ideas in this lab

- `business-research/` — gstack's sprint methodology maps onto enterprise AI coding platform concepts
- `business-research/startup-white-spaces/agent-permission-firewall.md` — the `/careful` and `/guard` patterns are a simplified version of the full firewall concept

---

## Related sources in this lab

- `sources/deterministic-agent-safety/` (dotclaude): shares the hook safety pattern; gstack implements the same idea via skills (`/careful`, `/guard`) rather than hooks
- `sources/structured-agent-development/` (superpowers): shares the subagent orchestration and sprint workflow philosophy; complementary rather than redundant
- `sources/terminal-coding-agent/` (kimi-code): implements a production-grade hook engine in TypeScript; the architecture gstack's browse daemon could use as a model

---

## Open questions

1. Does gstack's multi-agent browser sharing (`/pair-agent`) have enterprise security customers, or is it primarily a developer convenience feature?
2. Is the gbrain knowledge base (persistent cross-session memory for agents) competitively differentiated, or will Claude/Anthropic subsume this natively?
3. The ETHOS.md file is explicitly protected from external contributor modification. What is its actual content and how central is it to the system's behavior? [not read — marked as protected]
4. How much of gstack's productivity claim (810x pace increase vs 2013 baseline) depends on the specific Garry Tan use case vs being reproducible by other builders?

---

## Final research conclusion

gstack is the most architecturally sophisticated open-source AI development stack available, with genuine innovations in browser agent security, cross-agent portability, and sprint methodology formalization. The highest-value extractable elements are the dual-listener tunnel pattern, the @ref ARIA addressing system, and the layered prompt injection defense stack — all independently useful and clean-room adaptable under MIT license.
