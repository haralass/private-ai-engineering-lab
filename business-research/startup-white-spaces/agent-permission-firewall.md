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

- Development teams using AI coding agents in production or staging environments
- Organizations with security or compliance requirements (finance, healthcare, regulated sectors)
- status: needs-user-input (are teams currently asking for this? what segment first?)

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

## Why it may matter

AI agent adoption in development teams is accelerating. Every team that uses an agent
in a shared codebase faces this problem. Currently each team either:
- Accepts the risk
- Manually adds ad-hoc restrictions
- Disables agent autonomy entirely

A standard, shareable solution doesn't exist yet as an off-the-shelf product.

---

## Founder fit

- CS background: this is a software infrastructure problem
- Existing prototype in this lab (Phase 1)
- Experience using AI coding agents directly
- No hardware, no physical distribution, pure software

---

## Evidence status

evidence_level: initial-research

Facts (from repo):
- Prototype built: `components/agent-safety-firewall/`
- Hooks work with Claude Code's hook system
- 141 tests passing
- Policy engine: deterministic, configurable, auditable

Not yet validated:
- Whether development teams want to pay for this
- What specific policies teams actually need
- Whether Claude / other vendors will build this natively (competitive risk)

---

## Unknowns

- Are teams actually concerned about agent-caused incidents?
- What is the right distribution model: open-source library, SaaS, or enterprise product?
- Would AI agent vendors build this natively, eliminating the market?
- What compliance frameworks explicitly require this?

---

## Next research question

1. Find 5 developers who use AI coding agents in a team setting: what safety problems have they had?
2. Check: does Claude Code, Copilot Workspace, or Cursor have native policy enforcement?
3. Check: does any startup already sell an agent safety layer?

---

## Related lab resources

- `product-concepts/agent-permission-firewall/` — structured product concept
- `components/agent-safety-firewall/` — working prototype
- `sources/deterministic-agent-safety/` (poshan0126/dotclaude, vendored MIT)
- `sources/structured-agent-development/` (obra/superpowers, vendored MIT)
