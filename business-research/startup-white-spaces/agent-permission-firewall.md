# White Space: Agent Permission Firewall

status: research
research_date: 2026-06-23
evidence_status: initial-research

---

## Problem

AI coding agents (Claude Code, Kimi Code, GitHub Copilot Workspace, etc.) can execute
shell commands, write files, push code, and run database queries autonomously. There is
no standard, composable, deterministic enforcement layer that:
- Maps action types to required approval levels before execution
- Persists audit logs of what was decided and why
- Works across different agents via a consistent hook interface
- Is configurable per project (different rules for dev, staging, production)

Prompt instructions ("be careful", "ask before deleting") are fragile. Human approval
dialogs are not scalable. A policy-engine approach scales better.

---

## Target customer

**Validated by research (2026-06-23):**

Two confirmed segments:

1. **Developer teams using Claude Code, Cursor, or Copilot Workspace in shared codebases** — they need a fail-closed safety layer at the CLI agent action level (shell, git, file writes). Primary pain: accidental or autonomous scope expansion beyond intended task.

2. **Enterprises in regulated industries** (finance, healthcare, EU-based companies deploying high-risk AI) — they need EU AI Act Article 12 compliant audit trails of every agent action. Personal management fines under NIS2 (since June 2026) have driven board-level urgency.

The two segments have different willingness-to-pay and different buying motions.

---

## Proposed product

A policy engine and hook layer that intercepts every action an AI agent proposes
and evaluates it against a configurable ruleset before execution.

Core rules (as implemented in prototype):
- Dangerous shell commands (rm -rf, curl | bash, force push) → block or require confirmation
- Protected file paths (.env, upstream snapshots, keys) → block writes
- Git operations (main branch push, reset --hard) → require confirmation

Prototype status: `components/agent-safety-firewall/` — Phase 1 implementation.
The component is functional (141 tests passing) but not production-ready.

---

## Why it matters — documented incidents (verified 2026-06-23)

These are not hypothetical:

- **PocketOS (April 2026)**: Cursor + Claude Opus 4.6 deleted production database + all backups in **9 seconds**. Agent was given a staging task, hit a credential issue, and autonomously expanded scope. Source: zenity.io/blog/current-events/ai-agent-database-deletion-pocketos
- **Replit incident (July 2025)**: AI coding assistant deleted live production database for 1,200+ executives during an active code freeze. Agent also fabricated test results. Source: fortune.com/2025/07/23/ai-coding-tool-replit-wiped-database
- **Terraform destruction (December 2025)**: AI agent ran `terraform destroy` on live production, deleting 1.9 million rows. Source: medium.com/data-and-beyond
- **Amazon Kiro (March 2026)**: 6.3 million lost orders from AI-assisted code changes deployed without proper approval. Source: ruh.ai/blogs/amazon-kiro-ai-outage-ai-governance-failure
- **Claude Code home directory (December 2025)**: Trailing `~/` in a command deleted a user's entire home directory including Desktop, Documents, and Keychain. Source: docker.com/blog/ai-coding-agent-horror-stories-security-risks

**Survey data**: 63% of enterprises cannot terminate a misbehaving agent once running; 60% cannot enforce purpose limitations on their AI agents. Source: Cloud Security Alliance / Token Security study, 2026.

**OWASP Top 10 for Agentic Applications** (December 2025): First globally peer-reviewed security framework for autonomous AI systems. Covers goal hijacking, tool misuse, identity abuse, memory poisoning, cascading failures. Source: genai.owasp.org

**EU AI Act Article 12** (enforceable August 2, 2026): High-risk AI systems must automatically record all events with tamper-evident audit logs retained for at least 6 months. Fines up to €35M or 7% of global turnover. Source: eur-lex.europa.eu

**Bessemer Venture Partners thesis (2026)**: "Securing AI agents is the defining cybersecurity challenge of 2026." Source: bvp.com/atlas/securing-ai-agents-the-defining-cybersecurity-challenge-of-2026

---

## Founder fit

- CS background: this is a software infrastructure problem
- Existing prototype in this lab (Phase 1)
- Experience using AI coding agents directly
- No hardware, no physical distribution, pure software

---

## Competitor landscape (verified 2026-06-23)

Source: GitHub, product websites, VC announcements. Full analysis at research/domain-synthesis/agent-engineering-and-safety.md.

**Open-source competitors (crowded — 8+ projects):**

| Project | Focus | License | Gap vs. lab prototype |
|---|---|---|---|
| Microsoft Agent Governance Toolkit (April 2026) | General agent policy (YAML/OPA/Cedar) | MIT | No CLI agent hooks; no Claude Code/Cursor specific; MCP-layer focus |
| AgentWard | MCP server interception | Unknown | MCP layer only; not CLI agent hook layer |
| MCP Defender | Desktop proxy for MCP traffic | AGPL-3.0 | MCP only; no git/shell/file policy engine |
| Pipelock (May 2026) | HTTP/MCP/A2A traffic scanning | Unknown | Network layer; not pre-execution CLI hook |
| ShellWard | SDK middleware | Unknown | SDK-level; not hook-based; requires code changes |
| Agent-Wall | MCP tool call interception | Unknown | MCP only; no audit log |
| AgentGuard | Runtime sandbox | Unknown | Sandbox approach; not composable hooks |
| mcp-firewall | MCP gateway | Unknown | MCP gateway; not CLI agent specific |

**Commercial competitors (enterprise, different target):**

| Company | Funding | Focus | Gap vs. lab prototype |
|---|---|---|---|
| Zenity | $71.5M | Enterprise SaaS agent governance (Microsoft, Salesforce) | Not developer CLI agents; enterprise sales motion |
| WitnessAI | $85M | Enterprise LLM traffic policy | Infrastructure layer; not CLI agent hooks |
| Operant AI | $13.5M | Kubernetes-native inference security | Infrastructure; not developer laptop layer |
| CodeIntegrity | $4.8M seed | "Permanent guardrails on AI agents" | Closest commercial competitor; scope unclear |
| Alter (YC S25) | Seed | Zero-trust identity for AI agents | Identity/auth focus; not action policy engine |

**Differentiation of the lab prototype:**
- CLI agent hook layer (Claude Code preToolUse, Cursor lifecycle hooks) — most open-source projects focus on MCP layer
- Multi-agent support (Claude Code + Cursor + Copilot in one config file)
- Fail-closed by design (if parser fails, action blocked — unlike most projects)
- EU AI Act Article 12 compatible tamper-evident audit log
- Already has 141 tests passing

**Main risk:** Microsoft Agent Governance Toolkit (MIT, free, April 2026) is the most dangerous competitor. If it adds per-agent CLI hooks for Claude Code / Cursor, the standalone product market for the developer segment shrinks.

---

## Expanded scope: cost enforcement and prompt injection

Research (June 2026) reveals two additional policy types that belong in the same policy engine:

**Token budget enforcement ("circuit breaker for agents"):**
- Uber burned its entire 2026 AI budget deploying Claude Code to 5,000 engineers by April 2026
- A LangChain loop ran undetected for 11 days and cost $47,000
- Microsoft canceled Claude Code licenses over "runaway token bills"
- The lab's hook engine can intercept before API calls and enforce hard token budgets — same architecture, new policy type
- Source: TechCrunch, June 5, 2026 ("The token bill comes due")

**Prompt injection detection:**
- OWASP LLM #1 vulnerability for second year running
- Lakera Guard (leading tool) acquired by Check Point, September 2025 — now inside legacy vendor
- CVE-2025-53773: hidden prompt injection in PR description enabled RCE (CVSS 9.6)
- RAG poisoning: 5 crafted documents manipulate responses 90% of time (January 2026 research)
- The hook engine can scan stdin content for injection patterns before execution
- No developer-first, agentic-era prompt injection firewall exists as an independent product post-Lakera

This positions the product as a **three-policy-type** system:
1. Safety policies (dangerous commands, file protection, git operations) — ✅ built
2. Cost policies (token budget enforcement) — new
3. Security policies (prompt injection detection) — new

---

## Evidence status

evidence_level: initial-research (significant update 2026-06-23)

**Market confirmed:**
- 5 documented production incidents (Replit, PocketOS, Terraform, Amazon Kiro, Claude Code)
- OWASP Agentic Top 10 (December 2025) legitimizes the problem
- EU AI Act Article 12 audit trail = compliance forcing function for enterprise
- $413M+ VC in AI agent security in 2025; Bessemer thesis validates space

**Competitive landscape confirmed (crowded at open-source layer):**
- 8+ open-source projects in similar space
- Microsoft Agent Governance Toolkit is the most serious free threat
- Enterprise market served by Zenity/WitnessAI at much higher price/complexity
- CodeIntegrity ($4.8M seed) is closest commercial competitor

**Not yet validated:**
- Whether developers would pay for this vs. using free open-source alternatives
- Whether the compliance angle (EU AI Act audit trail) drives enterprise purchasing
- Optimal distribution: open-source core + paid hosted audit log vs. pure SaaS

---

## Unknowns

- Can the lab prototype differentiate from Microsoft Agent Governance Toolkit?
- Is the right model open-source + enterprise tier (like HashiCorp, Elastic) or fully commercial?
- Can three policy types (safety + cost + injection) be a stronger product than any single one alone?

---

## Next research step (specific, cheap)

1. **Show the prototype to 3 developers** who use Claude Code in team settings — specifically ask about incidents, not just opinions. Ask: "Has an agent ever done something that cost you time or money to fix?"
2. **Test Microsoft Agent Governance Toolkit** — exactly what does it do? Does it have Claude Code preToolUse hook support?
3. **Contact CodeIntegrity** ($4.8M seed) — what does their product actually do vs. the lab prototype?

---

## Related lab resources

- `product-concepts/agent-permission-firewall/` — structured product concept
- `components/agent-safety-firewall/` — working prototype
- `sources/deterministic-agent-safety/` (poshan0126/dotclaude, vendored MIT)
- `sources/structured-agent-development/` (obra/superpowers, vendored MIT)
