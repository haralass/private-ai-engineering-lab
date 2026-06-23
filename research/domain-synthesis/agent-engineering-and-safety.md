# Domain Synthesis: Agent Engineering and Safety

## Sources analyzed (4)

| Source | Identity | What it is |
|---|---|---|
| deterministic-agent-safety | dotclaude (poshan0126) | Claude Code safety hooks + workflow skills kit |
| full-product-engineering-agent-stack | gstack (garrytan / Garry Tan, YC) | Full sprint methodology + browser daemon + 37+ skills |
| structured-agent-development | superpowers (obra / Prime Radiant) | Cross-agent methodology plugin (11 agents, zero deps) |
| terminal-coding-agent | kimi-code (MoonshotAI) | Full agent runtime: TypeScript monorepo, TUI, SDK, server |

Commit dates: all retrieved 2026-06-22. All MIT licensed.

---

## Cross-source patterns

### Pattern A — The hook protocol as the universal enforcement layer

All four sources converge on a common mechanism: a shell-command hook that receives a JSON payload, makes a decision, and returns a structured JSON response. The protocol implementations differ in sophistication:

- **dotclaude (bash):** exit code 2 + JSON to stdout; `deny` or `ask` decisions; ~200 lines per hook
- **superpowers (bash):** emit `additionalContext` on SessionStart only; no PreToolUse hooks
- **gstack (skills):** `/careful` and `/guard` as prompt-based equivalents of hooks; no code-level enforcement
- **kimi-code (TypeScript):** `HookEngine` class with 20 event types, blocking/non-blocking semantics, resource dedup, observable callbacks

The four sources form a capability ladder: bash hooks (dotclaude) → cross-platform hooks (superpowers) → prompt-based pseudo-hooks (gstack) → full TypeScript hook runtime (kimi-code).

### Pattern B — The SessionStart bootstrap as the activation mechanism

Three of four sources use a `SessionStart` hook to inject context that activates the rest of the system:
- **dotclaude:** session-start.sh injects branch/dirty state, config drift nudge
- **superpowers:** session-start injects the entire `using-superpowers/SKILL.md` bootstrap
- **gstack:** PREAMBLE resolver injects update check, session tracking, AskUserQuestion format, writing style into every skill
- **kimi-code:** `enabledSessionStarts()` in PluginManager surfaces which plugins have a sessionStart skill; the session infrastructure triggers them

The pattern: don't rely on users to activate skills manually. Bootstrap context injection at session start makes skills auto-trigger.

### Pattern C — Deterministic pre-execution blocking

All sources (except gstack, which uses prompt-based soft guards) implement some form of deterministic blocking before execution:
- dotclaude: exit code 2 from hook script = hard block, no LLM override
- superpowers: `<HARD-GATE>` XML blocks in skill prompts (soft, LLM-dependent)
- kimi-code: `triggerBlock()` in HookEngine + PathSecurityError for file access
- gstack: `/careful` and `/guard` skills (soft, prompt-based) + 22MB ONNX classifier for browser (hard, ML-based)

The divide: dotclaude and kimi-code provide true deterministic enforcement (no LLM path around them). Superpowers and gstack's core skills rely on LLM compliance.

### Pattern D — Sensitive file protection as a baseline

All four sources implement some version of sensitive file protection:
- dotclaude: `protect-files.sh` — pattern list in bash `case` statement
- kimi-code: `sensitive.ts` — TypeScript Set + rename-shielded variant detection
- gstack: `lib/redact-patterns.ts` — three-tier (HIGH/MEDIUM/LOW) with PII/legal/credential coverage
- superpowers: none (methodology-only; delegates to underlying agent's permissions)

kimi-code's rename-shielded variant detection (`id_rsa-old`, `id_rsa.bak`, `.env.key`) is the most complete among the four. gstack's redact engine is the most coverage-complete (includes legal content, PII, connection strings). dotclaude's approach is the most portable (pure bash patterns).

### Pattern E — Subagent orchestration as a first-class primitive

Three of four sources address multi-agent coordination:
- superpowers: `subagent-driven-development` — fresh subagent per task, two-stage review, orchestrating agent preserves context
- gstack: `autoplan`, `pair-agent`, `codex` second opinion — CEO/design/eng/DX review agents + cross-agent browser coordination
- kimi-code: `SubagentStart`/`SubagentStop` hooks, `subagent-host.ts`, `dispatching-parallel-agents` in superpowers support

dotclaude addresses this least directly (its specialist agents are parallel, not orchestrated).

### Pattern F — Cross-agent portability as a design goal

Three sources explicitly target multiple AI coding agents:
- superpowers: 11 agents (Claude Code, Codex, Cursor, Factory Droid, Gemini CLI, GitHub Copilot CLI, Kimi Code, OpenCode, Antigravity, Pi); different JSON formats per agent
- gstack: 10 agents (same set minus Pi, plus GBrain); typed TypeScript host configs
- kimi-code: uses superpowers as a plugin in its marketplace — the two ecosystems are explicitly compatible

dotclaude is Claude Code-specific only.

### Pattern G — Token-budget awareness

Two sources explicitly optimize for token cost:
- dotclaude: path-scoped rules (only load near matching files), `/context-budget` skill, always-loaded vs on-invoke classification
- gstack: per-skill token ceiling (160KB / ~40K tokens), preamble resolver tracks always-on vs on-invoke cost, generated SKILL.md

The other two sources do not explicitly address this, though kimi-code's skill discovery mechanism (per-plugin skill roots) provides natural scoping.

---

## Combinatorial opportunities

### Combination 1: dotclaude + kimi-code — Shell hooks ↔ TypeScript hook engine

The most actionable combination. dotclaude's hook scripts implement the Claude Code JSON protocol over stdout/exit-codes. kimi-code's `HookEngine` implements the same protocol concept in TypeScript with richer semantics. A cross-runtime hook bridge would:
1. Express policies in a shared YAML/JSON format
2. Compile to dotclaude-compatible bash scripts for Claude Code
3. Compile to kimi-code HookDef entries for the TypeScript runtime
4. Run the same policy on both agents

This would give the lab's `components/agent-safety-firewall/` a path to cross-runtime deployment.

### Combination 2: superpowers + kimi-code — Cross-agent bootstrap + production runtime

Superpowers is already supported in kimi-code's plugin marketplace. The combination is: kimi-code provides the production-grade runtime (20-event hooks, path security, concurrent tool scheduling); superpowers provides the cross-agent methodology (brainstorming → TDD → subagent-driven-development). Together: a production-quality, cross-agent coding agent with both workflow methodology and deterministic safety enforcement.

### Combination 3: gstack browser daemon + kimi-code agent runtime

Gstack's Playwright browser daemon (sub-100ms persistent Chromium, @ref ARIA addressing, dual-listener tunnel security, prompt injection defense) is architecturally independent of gstack's skill system. kimi-code's agent runtime lacks a browser tool (its tools are shell/file/web fetch). Integrating the gstack browse daemon as a kimi-code plugin would give kimi-code browser QA capabilities with production-grade security.

### Combination 4: dotclaude hooks + gstack redact engine — Layered secret/command guard

dotclaude's `scan-secrets.sh` covers common credential patterns. gstack's `lib/redact-patterns.ts` covers three tiers including PII, legal content, connection strings, and JWT tokens. A combined layer that uses gstack's more complete pattern set inside a dotclaude-style hook script would be a substantially better secret guard than either source alone.

### Combination 5: All four sources → Universal agent policy framework

A clean-room re-implementation that synthesizes all four:
- Hook protocol from dotclaude (battle-tested Claude Code compliance)
- Cross-platform hook format negotiation from superpowers (11 agents)
- 20-event type system from kimi-code
- Three-tier policy classification from gstack's redact engine
- Layered defense stack from gstack's security architecture

This is the "agent permission firewall" concept in the lab's white-space research.

---

## Gap analysis

### Gap 1 — No cross-agent audit trail
None of the four sources produces a structured, queryable audit log of agent decisions. dotclaude logs blocked action reasons to stdout (ephemeral). gstack logs to `~/.gstack/security/attempts.jsonl` (security events only). kimi-code has telemetry but not a public audit API. A first-class audit trail (JSONL with tool, decision, reason, timestamp, session ID) that works across agents and integrates with SIEM systems is missing from all four.

### Gap 2 — No policy-as-code with declarative syntax
All sources embed policies in bash scripts (dotclaude, superpowers) or TypeScript code (kimi-code) or markdown prose (gstack skills). No source offers a declarative `policy.yaml` that non-engineers can read and modify. An operator who wants to change "block `rm -rf /` but allow `rm -rf ./dist`" has to modify bash code.

### Gap 3 — No network egress policy enforcement
None of the four sources intercepts outbound network calls from tools (only gstack's browser sidebar has egress scanning for browser traffic specifically). Shell commands that `curl` to external endpoints, MCP servers that make network calls, and tool calls that fetch external data are all unmonitored in kimi-code and dotclaude. [inference — not confirmed for kimi-code's web tools]

### Gap 4 — No permission elevation with human-in-the-loop audit
The existing models are binary: block or allow. None supports a "request elevation" pattern: allow a normally-blocked action if a human approves via a separate out-of-band channel (Slack, email, web UI). dotclaude's `ask` decision is the closest, but it is synchronous (blocks the Claude session until approved) and in-CLI.

### Gap 5 — No cross-agent session continuity
Superpowers re-injects context on `startup|clear|compact` but loses it between sessions. gstack has persistent gbrain memory (optional, requires Supabase). kimi-code has session-store but no cross-session memory by default. A durable, cross-session context store that works across all agents is missing from the free/open-source ecosystem.

### Gap 6 — No multi-tenant policy (team-level, not per-developer)
All four sources are per-developer installs. None has a concept of organization-level policy that overrides or extends individual developer settings. Enterprise deployments need central policy distribution (which Claude Code partially addresses with `allowManagedHooksOnly` and managed-settings.json, but this is Claude Code-specific).

---

## Market context

**The agent safety tooling market is early and fragmented (as of 2026-06-23).**

Key public indicators:
- Microsoft released the [Agent Governance Toolkit](https://github.com/microsoft/agent-governance-toolkit) (MIT, Apache 2.0) in April 2026 — 4,500+ stars, 639 forks. First toolkit to claim 10/10 OWASP Agentic Top 10 coverage. YAML/OPA/Cedar-based policy, zero-trust identity, execution sandboxing. Python/TypeScript/.NET/Rust/Go.
- [Pipelock](https://github.com/luckyPipewrench/pipelock) (open-source, Go binary) launched May 2026 — 729 stars. Network-layer egress firewall: 65 credential patterns, 29 injection patterns, 6-pass normalization, signed action receipts. MCP + HTTP + WebSocket scanning.
- [Agent Airlock](https://github.com/sattyamjjain/agent-airlock) — validates tool calls, RBAC, cost tracking, PII masking. Works with LangChain, OpenAI Agents SDK, PydanticAI, CrewAI.
- [MCP Firewall](https://github.com/ressl/mcp-firewall) — policy enforcement, threat detection, audit logging for MCP.
- [Galileo](https://galileo.ai/blog/ai-agent-compliance-governance-audit-trails-risk-management) — commercial platform with real-time guardrails, SIEM integration, decision chain replay.
- [Obsidian Security](https://www.obsidiansecurity.com/blog/security-for-ai-agents) — behavioral analytics, automated policy enforcement, compliance reporting for agents.
- GitHub's agentic security principles blog post (2026): acknowledges the agent tool invocation layer as the primary control surface for unauthorized agent actions.

Context: Claude Code had two CVEs disclosed in 2025-2026:
- CVE-2025-59536 (CVSS 8.7): RCE via malicious repo hooks executing before trust confirmed ([Check Point Research](https://research.checkpoint.com/2026/rce-and-api-token-exfiltration-through-claude-code-project-files-cve-2025-59536/))
- CVE-2026-21852 (CVSS 5.3): API key exfiltration via ANTHROPIC_BASE_URL manipulation

The Atlan [2026 Enterprise Security Guide](https://atlan.com/know/ai-agent-risks-guardrails/) notes: "Most enterprises have no governance at the tool invocation layer — trusted by default, no risk scoring before execution, no audit trails showing what agents are doing." [Source: https://atlan.com/know/ai-agent-risks-guardrails/]

---

## Competitor landscape

| Product | Type | Focus | Stars (approx) | URL |
|---|---|---|---|---|
| Microsoft Agent Governance Toolkit | Open-source (MIT) | Policy enforcement, OWASP 10/10, multi-language | 4,500 | https://github.com/microsoft/agent-governance-toolkit |
| Pipelock | Open-source (Go binary) | Network-layer egress firewall, MCP/HTTP/WS scanning | 729 | https://github.com/luckyPipewrench/pipelock |
| Agent Airlock | Open-source | Tool call validation, RBAC, PII masking, cost tracking | ~50 | https://github.com/sattyamjjain/agent-airlock |
| MCP Firewall | Open-source | MCP-specific policy enforcement, audit logging | ~50 | https://github.com/ressl/mcp-firewall |
| Galileo | Commercial (SaaS) | Runtime guardrails, SIEM integration, decision chain replay | N/A | https://galileo.ai |
| Obsidian Security | Commercial (SaaS) | Behavioral analytics, automated policy enforcement | N/A | https://www.obsidiansecurity.com |
| Harmonic Security | Commercial | Claude Code enterprise deployment guide, DLP | N/A | https://www.harmonic.security |
| Backslash Security | Commercial | Claude Code security best practices | N/A | https://www.backslash.security |
| dotclaude | Open-source (MIT) | Claude Code hooks kit | < 100 (nascent) | https://github.com/poshan0126/dotclaude |
| superpowers | Open-source (MIT) | Cross-agent methodology, zero-deps | ~500 est. | https://github.com/obra/superpowers |
| gstack | Open-source (MIT) | Full stack: sprint + browser + security | ~500 est. | https://github.com/garrytan/gstack |

**Gap in the competitor map:** None of the open-source tools above combines (a) cross-agent portability, (b) declarative policy-as-code, (c) structured audit trail, and (d) human-in-the-loop elevation. Microsoft AGT is the closest but is designed for application-level integration, not per-developer agent configuration.

**The Claude Code-specific gap:** Claude Code's enterprise `allowManagedHooksOnly` setting creates a distribution channel for approved hooks, but no vendor currently offers a managed hook library for purchase. The lab's prototype in `components/agent-safety-firewall/` is closer to this specific gap than any competitor found in research.

---

## Top business opportunities from this domain (scored)

### Opportunity 1 — Managed enterprise Claude Code safety layer
**Commercial potential: HIGH**

Package the hook patterns from dotclaude + kimi-code's path security into a managed enterprise product:
- Centrally managed `policy.yaml` distributed via Claude Code `managed-settings.json`
- Structured JSONL audit trail with SIEM export (Splunk, Datadog, etc.)
- Web dashboard for security team visibility
- Team-level policy overrides (different rules for dev vs staging vs production)

**Why this is real:** CVE-2025-59536 and CVE-2026-21852 forced enterprise Claude Code deployments to address hook security. Enterprises already using Claude Code (the market that exists) need a compliance layer. The `allowManagedHooksOnly` enterprise setting creates a managed distribution channel for hooks. No current product occupies this specific slot.

**Revenue model:** Per-seat SaaS, enterprise contract. The open-source version (dotclaude) creates the awareness funnel; the enterprise product offers compliance reporting, SIEM integration, and centralized policy management.

**Lab resources:** `components/agent-safety-firewall/` (prototype), `sources/deterministic-agent-safety/` (reference)

---

### Opportunity 2 — Cross-agent methodology SaaS (Superpowers commercial tier)
**Commercial potential: MEDIUM-HIGH**

Prime Radiant (superpowers maintainer) already offers commercial support. The opportunity is a SaaS layer on top of the open-source methodology:
- Team-level skill library with org-managed skills (no per-developer configuration)
- Compliance audit trail: which design docs were approved, which test plans were followed
- Centralized usage analytics: which skills trigger most, where teams skip steps
- Integration with JIRA/GitHub for design doc and plan auto-filing

**Why this is real:** Organizations adopting AI coding agents face the "no governance at the workflow layer" problem. Teams that skip brainstorming, skip TDD, or skip code review create risk. A managed workflow enforcement layer addresses this.

**Competitive risk:** Prime Radiant may build this themselves (they already have sales@primeradiant.com). [inference]

---

### Opportunity 3 — Portable browser agent security library
**Commercial potential: MEDIUM**

Extract gstack's six-layer prompt injection defense (content security + ONNX classifier + canary token + ensemble combiner) as a standalone npm library. Sell as a security library to any team building browser-using AI agents.

**Why this is real:** Browser-using agents (AutoGPT, OpenAI Operator, any agent with Playwright access) are the highest-risk class of AI agents. The attack surface for prompt injection from hostile web content is large and largely unaddressed by current tooling. The gstack implementation is the most complete publicly available defense stack.

**Competitive risk:** gstack's browse daemon is MIT licensed and open-source; anyone can extract it. The commercial value would be in packaging, support, and a managed threat intelligence feed (updated injection patterns).

---

### Opportunity 4 — Agent tool scheduling and resource management layer
**Commercial potential: LOW-MEDIUM**

The `ToolAccesses + ToolScheduler` pattern from kimi-code solves a real concurrency problem: agents that execute multiple tool calls per turn need to parallelize reads while serializing writes. This is currently solved individually by each agent runtime. A shared library would raise the quality floor.

**Why this is LOW vs MEDIUM:** This is a library feature that agent runtime vendors (Anthropic, MoonshotAI, Microsoft) are likely to build natively. The commercial path is as an open-source library that establishes the pattern rather than as a product. Better positioned as a contribution to the ecosystem than a standalone business.

---

### Opportunity 5 — Declarative agent policy engine (policy-as-code)
**Commercial potential: HIGH (3-5 year horizon)**

The gap identified above: no source has a declarative `policy.yaml` that non-engineers can read and modify. Build a policy engine that:
- Expresses rules declaratively: `{ tool: "Bash", pattern: "rm -rf *", action: "block", reason: "..." }`
- Compiles to dotclaude-compatible hook scripts, kimi-code HookDef entries, gstack skill restrictions
- Integrates with OPA (Open Policy Agent) for enterprise policy language compatibility
- Provides a web UI for security teams to manage policies without code changes

**Why this is HIGH but 3-5 years:** The market needs to mature. Currently, "hook scripts" is the dominant pattern and the audience understands it. The enterprise governance market (compliance teams who can't read bash) will arrive as adoption scales. Microsoft AGT uses YAML/OPA/Cedar — positioning the policy-as-code approach early creates a lead.

**Competition:** Microsoft AGT is already in this space for LangChain/OpenAI/CrewAI agents. The Claude Code-specific + cross-agent opportunity is less covered.

---

## Recommended next research actions

1. **Validate the enterprise hooks gap:** Find 5 enterprise developers using Claude Code in a regulated industry (finance, healthcare). Ask specifically: "What happened the last time your AI coding agent did something unexpected? How did you detect it? What did you change?" The audit trail gap should surface in these conversations.

2. **Read the Microsoft Agent Governance Toolkit source code:** AGT has 4,500 stars and claims 10/10 OWASP coverage. Its OPA/Cedar-based policy engine may be directly comparable to or competitive with the lab's `components/agent-safety-firewall/` prototype. Read the source before further investment in the firewall concept.
   - URL: https://github.com/microsoft/agent-governance-toolkit

3. **Read kimi-code's full tools/builtin/web implementation:** The web tool category in kimi-code was listed but not read in depth. If kimi-code has an HTTP request tool, does it have egress filtering? This would close the Gap 3 analysis.

4. **Investigate Claude Code's `allowManagedHooksOnly` enterprise setting:** The managed-settings.json distribution mechanism may be the distribution channel for an enterprise hooks product. Understand the exact capability before building: what can be distributed, what can be overridden, what is locked.
   - Reference: https://generalanalysis.com/guides/claude-code-enterprise-security-deployment

5. **Benchmark the lab prototype vs dotclaude:** The lab has `components/agent-safety-firewall/` with 141 tests. Run both against the same test scenarios. Where does the lab prototype have better coverage? Where does dotclaude cover cases the lab prototype misses? This produces a gap list for the next iteration.

6. **Contact Prime Radiant:** sales@primeradiant.com. Understand their commercial offering, pricing, and roadmap. If they are building the team SaaS layer, is there a white-label or API partnership opportunity? If not, is the commercial space open?

7. **Research AI Act and SOC2 requirements for agent governance:** The [Augment Code EU AI Act guide](https://www.augmentcode.com/guides/eu-ai-act-2026) and the SOC2 alignment documented in Microsoft AGT suggest regulatory pressure is creating mandatory requirements for agent audit trails. Understand which specific controls are required and map them to the four sources.
