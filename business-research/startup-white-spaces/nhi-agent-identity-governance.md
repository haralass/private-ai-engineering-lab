# White Space: Non-Human Identity (NHI) Governance for AI Agents

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

AI agents authenticate to external systems using credentials — API keys, service tokens,
OAuth scopes, database passwords. Today, agents either:
1. **Share human credentials** (agent runs as a human user's identity — catastrophic if the agent is compromised)
2. **Use long-lived service tokens** (API keys that never expire, with broad permissions)
3. **Have no audit trail** of what they authenticated as, when, and what they accessed

**Scale of the problem (verified, 2026-06-23):**
- 92% of enterprises have no real-time visibility into what their AI agents are doing or who they authenticate as (Cloud Security Alliance / Token Security study, 2026)
- 95% cannot detect a compromised AI agent (same source)
- Gartner predicts 40% of enterprise applications will have embedded AI agents by end of 2026
- OWASP LLM Top 10 #6: "Excessive Agency" — agents with overly broad permissions is a top-10 LLM security risk
- The NSA MCP Security Advisory (May 2026) specifically calls out authentication propagation as a critical gap

Source: CSA Labs (AI Agent Governance Gap research note, April 2026), iansresearch.com ("AI Agents Are Creating an Identity Security Crisis in 2026"), NSA MCP Security Advisory, verified 2026-06-23.

---

## The gap in existing tools

IAM vendors are aware of the problem but have not fully productized the solution:

| Tool | Gap |
|---|---|
| Okta | Added XAA protocol support; enterprise sales; not developer-friendly for per-task ephemeral credentials |
| SailPoint | Added "agentic IAM" awareness; identity governance for agents is an add-on; no agent-specific lifecycle management |
| Silverfort / Opal | Non-human identity (service accounts, API keys); not agent-aware (no per-task scoping, no ephemeral credential issuance) |
| HashiCorp Vault | Dynamic secrets for infrastructure; requires significant engineering to extend to per-agent-task ephemeral credentials |
| Alter (YC S25) | Zero-trust identity for AI agents; identity/auth focus; early stage; limited public information |

**The specific missing product**: An agent-native credential broker that issues **short-lived, task-scoped credentials** to agents at the start of each task — credentials that expire when the task ends, covering exactly the permissions needed for that task (least privilege), with a full audit log of what each agent accessed and when.

---

## Target customer

**Primary**: CISO / IAM team at enterprises deploying >10 AI agents in production.

**Secondary**: Platform engineering teams building multi-agent systems where agents access databases, APIs, and internal services.

**Trigger event**:
- A security review that discovers agents using human credentials or shared service tokens
- A SOC 2 / ISO 27001 audit that flags agent authentication as a finding
- An EU AI Act Article 9 (risk management system) requirement to demonstrate access controls on AI systems

---

## Proposed product

An **AI agent credential broker**:

1. **Agent registry**: Register each agent with its identity, task scope, and required permissions
2. **Task-scoped credential issuance**: When an agent starts a task, it requests credentials from the broker. The broker issues short-lived (15-minute) tokens covering exactly the permissions needed for that task — no more.
3. **Automatic expiry**: Credentials expire when the task completes or after max TTL — no persistent tokens
4. **Audit log**: Every credential issuance, every API call made under that credential, every access event — tamper-evident, queryable by SIEM
5. **SIEM integration**: Real-time event streaming to Splunk, Microsoft Sentinel, or QRadar
6. **Anomaly detection**: Flag when an agent requests permissions outside its registered scope, or when credential usage patterns deviate from baseline
7. **MCP-aware**: Understands MCP tool calls as first-class events (which MCP server, which tool, which arguments) — not just generic API calls

---

## Why now

- **Agent deployment is scaling fast**: 40% of enterprise apps will have embedded agents by end of 2026 (Gartner)
- **Current auth is a liability**: Shared service tokens with broad permissions are the default today — this is the infrastructure debt that needs to be paid
- **NSA advisory (May 2026)**: Government explicitly flagging authentication propagation as critical; creates compliance urgency for enterprises in regulated industries
- **Okta / SailPoint are moving**: They are adding agent awareness but enterprise sales cycles are 12–18 months. A developer-first tool can own the mid-market before they arrive.
- **OWASP Agentic Top 10** legitimizes the problem space for any security team

---

## Competitor landscape (verified 2026-06-23)

| Company | Status | Gap |
|---|---|---|
| Okta | Enterprise IAM giant adding XAA; enterprise-only | No developer-friendly SDK; 12-18 month sales cycle |
| SailPoint | Identity governance + agentic awareness | Enterprise GRC focus; not per-task ephemeral credential issuance |
| Silverfort | Service account / NHI protection | Infrastructure-level; not agent-task-aware |
| Alter (YC S25) | Seed-stage, zero-trust agent identity | Closest; early-stage; limited public information on product depth |
| HashiCorp Vault | Dynamic secrets (excellent infrastructure tool) | Requires significant engineering to apply to agent-specific use cases; not agent-aware out of the box |

**The developer-first, agent-native, zero-engineering-setup slot is open.**

---

## Founder fit

- CS background: credential broker + token lifecycle management is a well-understood infrastructure engineering problem
- Experience with AI agents (Claude Code, lab prototype)
- EU-based: EU AI Act Article 9 (risk management) and NIS2 personal management liability create EU-specific compliance angles
- B2B; CISO/IAM buyer; pure software

---

## Revenue model

- **Per-agent SaaS**: $50–$200/month per registered agent (annual contracts)
- **Enterprise**: $20,000–$80,000/year for unlimited agents, SIEM integration, custom policy engine
- **Freemium**: Up to 3 agents, basic audit log, free — commercial above that

---

## Risks

- Okta / SailPoint / Microsoft Entra could release agent-native credential management that captures enterprise market
- Cloud providers (AWS IAM, GCP IAM) could extend their IAM to native AI agent credential lifecycle
- The per-task scoping problem may be solved at the MCP protocol layer rather than requiring a third-party broker
- Enterprises may solve this with Vault + custom scripts rather than a dedicated product

---

## Next validation step

1. Find a CISO at a company running >10 agents in production — ask: "How do your agents authenticate to internal systems? Are those credentials human accounts or service tokens? How do you revoke them if an agent is compromised?"
2. Check if Alter (YC S25) has published any product details or released a public demo
3. Test HashiCorp Vault Dynamic Secrets — how many lines of code does it take to make it per-agent-task-scoped? That gap is the product.

---

## Related ideas

- `agent-permission-firewall.md` — adjacent: permission firewall controls WHAT agents can do; this controls WHO agents authenticate as. Different layer, same agent security problem domain.
- `research/domain-synthesis/agent-engineering-and-safety.md` — agent security synthesis
