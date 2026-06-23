# White Space: AI Agent Cost Circuit Breaker

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

Autonomous AI agents (Claude Code, Cursor, LangChain agents, LangGraph workflows) can consume
unbounded token budgets. Current tooling offers alerting and dashboards — but no **hosted,
zero-infrastructure, pre-call enforcement** that stops an agent from making the next API call
when a budget ceiling is reached.

**Documented incidents (verified, 2026-06-23):**

- **Uber (April 2026)**: Burned their entire 2026 AI budget by deploying Claude Code to 5,000 engineers by April. Microsoft canceled Claude Code licenses for similar reasons. Source: TechCrunch, June 5, 2026 ("The token bill comes due")
- **$47K LangChain loop (2025)**: A LangChain agent ran for 11 days undetected, accumulating $47,000 in API costs before anyone noticed. This is now the canonical example cited in every "AI cost enforcement" article.
- **Pattern**: Claude Code averages $150–$250/developer/month at enterprise scale. At 500 developers, that's $75,000–$125,000/month — with no per-agent enforcement layer.

Source: techsifted.com ("Claude Code Enterprise Costs April 2026"), fountaincity.tech, baristalabs.io, verified 2026-06-23.

---

## The gap in existing tools

| Tool | Approach | Gap |
|---|---|---|
| LiteLLM | Virtual keys with `fail_closed_budget_enforcement`, per-session iteration caps | Requires self-hosted proxy + Redis + engineering setup; not plug-and-play |
| Helicone | Observability, dashboards, per-user attribution | Explicitly does **not** enforce or block API calls |
| Bifrost (Maxim AI) | Open-source Go gateway, circuit breaker logic | OSS; requires self-hosting; no managed cloud offering |
| Braintrust / Langfuse | Tracing and analytics | Observability only; no enforcement |
| Waxell | Agent governance SDK with kill-switch policies | Early-stage; no notable funding or public traction signals |
| Anthropic Console | Workspace spend limits | Subscription-cap level; not per-agent, per-session, or per-task |

**The gap is specific**: a **hosted** circuit breaker — drop in by rotating a single API key, real-time enforcement before the LLM call exits, per-agent/per-session/per-user ceilings, works with Claude Code/Cursor/LangChain without code changes.

Source: relayplane.com ("RelayPlane vs LiteLLM vs Helicone vs Bifrost"), waxell.ai, verified 2026-06-23.

---

## Target customer

**Primary: DevOps/Platform engineering** at any company deploying Claude Code, Cursor, or
autonomous agents to production at scale (50+ developers using AI coding tools).

**Secondary: Finance/IT** managing per-engineer AI spend across multiple agent products.

**Trigger events**:
- An unexpected invoice or budget overrun from an AI API provider
- A loop that ran for hours or days undetected
- Finance/engineering alignment on "what are we actually spending on AI agents?"
- A board-level request to control AI costs before further deployment

**Willingness to pay**: High. The value proposition is "prevent a $47K incident" — at $5K–$15K/month, the ROI justification is trivial. Enterprises that just burned six figures on runaway agents will sign before the next monthly cycle.

---

## Proposed product

A hosted API proxy that acts as the circuit breaker between any AI coding agent or LLM
application and the underlying model provider.

**Core enforcement capabilities:**
1. **Per-agent budget ceilings**: Define max spend per Claude Code session, per Cursor workspace, per LangGraph agent run — enforced hard (fail closed when limit reached)
2. **Per-developer/team allocation**: Monthly token budgets per user or team, with automatic reset
3. **Per-task iteration caps**: Maximum number of LLM calls per defined task scope (prevents infinite loops)
4. **Real-time enforcement**: Budget check happens before the API call exits — not after the invoice arrives
5. **Zero-code integration**: Rotate API key to point at the proxy; no SDK changes, no config files, no infrastructure
6. **Workspace spend dashboard**: Real-time visibility into per-agent, per-developer, per-project spend vs. budget
7. **Alert + block modes**: Alert-only mode (notify when threshold hit) or circuit-break mode (hard stop)

**Compatibility**: Claude Code, Cursor, Copilot (via proxy), any LangChain/LangGraph/CrewAI agent that calls OpenAI, Anthropic, or Google APIs.

---

## Why now

- **Uber story (June 2026)**: TechCrunch piece was the mainstream trigger — "the token bill comes due" shifted this from an obscure pain to a board-level conversation
- **$47K LangChain incident**: Widely circulated; every developer team with agents has heard it
- **Claude Code at $150–$250/developer/month**: As enterprises deploy to hundreds of engineers, costs scale faster than budgets
- **LiteLLM's enforcement is real but requires engineering**: The market will pay for a hosted solution that requires no setup
- **No incumbent owns this position**: The hosted, plug-and-play enforcement slot is empty

---

## Competitor landscape (verified 2026-06-23)

| Competitor | Status | Gap vs. proposed product |
|---|---|---|
| LiteLLM (BerriAI) | OSS proxy with enforcement | Self-hosted; requires Redis, engineering; not plug-and-play |
| Bifrost (Maxim AI) | OSS gateway with circuit breaker | No hosted managed service; enterprise focus unclear |
| Waxell | Early-stage startup, no notable funding | Unknown traction; no public customer evidence |
| Helicone | Well-funded observability tool | Explicitly does not enforce; alert-only |
| Anthropic Console spend limits | Platform-native | Subscription-level only; not per-agent granularity |

**Gap confirmed**: No hosted, zero-infrastructure, per-agent circuit breaker as a commercial product.

---

## Founder fit

- CS background: API proxy with budget enforcement is a well-defined infrastructure engineering problem
- Experience using Claude Code directly — knows the failure modes from personal usage
- EU-based: EU AI Act Article 12 (tamper-evident audit logs for AI system actions) is a compliance angle for the audit trail feature
- B2B; DevOps/platform engineering buyer; pure software

---

## Revenue model

- **Usage-based**: $0.50–$2 per $100 of AI spend managed (0.5–2% of flow-through)
- **Tiered SaaS**: $500/month (up to 10 developers), $2,000/month (50 developers), $8,000/month (200+ developers)
- **Enterprise**: Annual contracts with SOC 2 audit trail; $30,000–$100,000/year

At 100 enterprise customers at $2,000/month average → $2.4M ARR.

---

## Technical approach

1. Build hosted API proxy (Cloudflare Workers or Railway/Fly.io for low latency)
2. Redis-backed counter per (API-key, agent-session, user, month) — same architecture as LiteLLM's enforcement
3. Intercept request → check budget → pass through or fail closed → record usage delta
4. Dashboard: next.js + tRPC frontend; Stripe for billing
5. Differentiation: managed service + zero-code key rotation + Claude Code native integration docs

---

## Risks

- LiteLLM releases a managed cloud service (they have a commercial entity; this is a real risk)
- Anthropic adds per-agent budget enforcement natively to Claude Code
- Model providers (Anthropic, OpenAI) build cost controls into their own developer portals at sufficient granularity
- Trust barrier: enterprises may not want a third-party proxy sitting between them and their AI providers (data residency, latency concerns)

---

## Relationship to agent-permission-firewall

`agent-permission-firewall.md` covers **safety** enforcement (blocking dangerous shell commands,
protecting files, enforcing approval workflows). This idea covers **cost** enforcement (blocking
API calls when token budget is exhausted). Both use a pre-execution enforcement architecture.

Decision: these can be the same product (the permission firewall expands its "policy type" taxonomy
to include cost policies, as noted in `agent-permission-firewall.md`'s "Expanded scope" section),
or the cost enforcement product can stand alone as a simpler, faster-to-market product with a cleaner pitch.

The standalone pitch is easier: "Prevent runaway AI agent costs" is a CFO-level pitch that
requires no security jargon. This may be the better wedge into enterprise.

---

## Next validation step

1. **Interview 5 DevOps/platform engineers** at companies with 50+ Claude Code users — ask specifically: "What happened the last time an agent ran longer than expected? How did you find out? How did you stop it?"
2. **Build a prototype** in 2 days: Cloudflare Worker proxy with Redis budget enforcement, single env-var config, deployed at a public URL. Show it to 3 developers.
3. **Price test**: Offer early access at $200/month to 5 companies via cold email to engineering leads who have publicly posted about AI cost management
4. **LiteLLM watch**: Monitor for announcement of LiteLLM Cloud (managed service) — this would close the gap quickly

---

## Evidence quality notes

- Uber/Microsoft budget burn confirmed via TechCrunch June 2026 article
- $47K LangChain incident confirmed via fountaincity.tech and baristalabs.io (multiple independent citations)
- LiteLLM enforcement architecture verified from official docs (docs.litellm.ai)
- Helicone "does not enforce" confirmed from relayplane.com comparison and official Helicone docs
- Waxell existence confirmed (waxell.ai); funding status unverified
- [Hypothesis] Revenue model and market size are directional estimates
