# White Space: Agent Liability Attribution Engine

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

When an AI agent acting under a shared service account modifies a database record, triggers a
transaction, or generates a regulatory report — and that action causes harm — no existing tooling
can reconstruct the complete causal chain across agent boundaries in a legally admissible form.

**The structural attribution problem:**
- Agents access systems using shared service accounts → no individual attribution
- Agents reason across time, invoke tools dynamically, and adapt instructions — making traditional audit trails structurally incomplete
- The action that caused harm may be 5 agent steps removed from the human instruction that initiated the chain
- When multiple agents in a pipeline each modify a shared artifact, the causal contribution of each step is ambiguous

**Scale (verified, 2026-06-23):**
- 50% of enterprise AI agents run ungoverned with no audit trail (Gartner, May 2026)
- 27% of APIs connecting agents have no audit trail at all (Gartner)
- 1,600+ agents per enterprise projected by end of 2026 (IBM/Gartner)
- EU AI Act Article 12 enforcement starts August 2, 2026: high-risk AI systems must automatically record all events with tamper-evident audit logs retained ≥6 months
- SOX, HIPAA, GDPR all require individual attribution for regulated data modifications — none of which agent service accounts provide

Source: Gartner ("Applying Uniform Governance Across AI Agents Will Lead to Enterprise AI Agent Failure", May 2026), ienable.ai ("Agent Sprawl"), cogentinfo.com, iansresearch.com, verified 2026-06-23.

---

## The gap in existing tools

| Tool | What it captures | What it misses |
|---|---|---|
| Agent frameworks (LangSmith, Langfuse) | Tool calls, latency, token usage | Causal graph across agent boundaries; human instruction provenance |
| SIEM / log aggregation (Splunk, Sentinel) | System events (what happened) | Why it happened; which agent chain caused it; which human instruction |
| EU AI Act-focused audit tools (Vanta, etc.) | Current compliance state | Point-in-time causal chain of specific AI decisions |
| WitnessAI / Zenity | Agent traffic policy | Governance enforcement; not causal attribution for past actions |

**The specific gap**: a tool that reconstructs, on demand, the complete causal chain from a specific harmful outcome backwards to the human instruction that initiated the agent chain — with each step annotated, the agent identity at each step, the data accessed, and the decision made.

This is not a logging problem. It is a **causal inference** problem that requires reasoning about which steps were causally necessary for the outcome.

---

## Target customer

**Primary buyer**: Chief Compliance Officers and General Counsel at financial institutions,
healthcare systems, and EU-based companies deploying agents in regulated workflows.

**Trigger event**: EU AI Act enforcement (August 2, 2026), a SOX or GDPR audit that asks
"which agent did this and why?", or a legal dispute where an AI-generated action is contested.

**Secondary**: Enterprise legal ops teams preparing for litigation where AI agent actions are
material to the dispute.

---

## Proposed product

An **agent causal attribution service**:

1. **Causal event capture**: Instruments agent frameworks at both entry and exit points of each tool call, recording: agent identity, instruction hash, tool inputs, tool outputs, decision rationale, timestamp, upstream context that influenced the decision
2. **Cross-agent graph construction**: Links agent calls into a directed causal graph — when Agent B's action was informed by Agent A's output, this relationship is preserved
3. **Human instruction provenance**: Traces agent chains back to the human-authored instruction or workflow trigger that initiated the chain
4. **Attribution query**: Given a specific outcome (a database modification, a report generated, a transaction executed), returns the complete causal chain as a readable narrative + machine-readable graph, with each step's causal contribution scored
5. **EU AI Act Article 12 compliance export**: Packages the causal chain as a tamper-evident, timestamped audit record in the format required by Article 12 (qualified electronic timestamp per eIDAS)
6. **Multi-agent identity mapping**: Resolves shared service account actions to specific agent runs using correlation IDs + timing + instruction context — even when agents share credentials
7. **Regulatory export**: Generates a structured report suitable for presenting to a regulator, auditor, or court

---

## Why now

- **EU AI Act Article 12 enforcement is August 2, 2026** — 40 days. Every company deploying high-risk AI agents now has a legal obligation to produce this.
- **Agents are in production for the first time at scale**: Until 2025, agents were mostly demos. In 2026, they are executing regulated workflows in finance and healthcare. The liability is real.
- **1,600 agents per enterprise** (IBM/Gartner): at this scale, a human cannot manually trace agent actions — automation is required
- **No tool currently does cross-agent causal attribution**: The logging tools log events; they don't infer causality across agent boundaries
- **Bessemer thesis**: "Securing AI agents is the defining cybersecurity challenge of 2026" — liability attribution is the compliance expression of this

---

## Competitor landscape (verified 2026-06-23)

| Competitor | Approach | Gap |
|---|---|---|
| LangSmith / Langfuse | Trace logging of agent runs | Single-framework; no cross-agent causal inference; not compliance-grade |
| WitnessAI ($58M raised) | Enterprise AI traffic policy | Enforcement focus; not causal attribution for past actions |
| Zenity ($71.5M raised) | Enterprise agent governance (Microsoft, Salesforce) | Governance workflow; not forensic causal attribution |
| Splunk / Microsoft Sentinel | SIEM event logging | General IT events; no agent-specific causal inference |
| Agent audit trail guides (dev.to, medium.com, kognitos.com) | Blog posts explaining the problem | Not a product |

**Gap confirmed: no dedicated cross-agent causal attribution product exists.**

---

## Founder fit

- CS background: causal graph construction from event logs is a well-defined computer science problem; the lab already has an append-only decision log pattern (Pattern 11 in REUSABLE_PATTERNS_CATALOG)
- Experience with AI agents directly (Claude Code, lab prototype)
- EU-based: EU AI Act Article 12 is an EU-specific enforcement trigger; proximity to buyers
- B2B; compliance/legal buyer; pure software
- Strong regulatory forcing function: the August 2 deadline creates urgency without requiring the buyer to feel the pain first

---

## Revenue model

- **Compliance SaaS**: €2,000–€8,000/month per company (tiered by number of agent runs per month)
- **Enterprise**: €30,000–€100,000/year with full EU AI Act Article 12 export, SIEM integration, legal evidence package generation
- **Incident attribution service**: €5,000–€20,000 per incident investigation (forensic report for a specific harmful action)

---

## Technical approach

This builds directly on existing lab infrastructure:
- **Append-only decision log** (Pattern 11 in REUSABLE_PATTERNS_CATALOG): already conceptualized; extend to cross-agent context
- **Fail-closed hook engine** (Pattern 1): instrument at hook points to capture decision context before and after each tool execution
- **FAISS semantic indexing** (Pattern 13): index agent decision rationales for semantic search and causal similarity matching

The core technical novel contribution: a **causal inference layer** that, given a set of logged agent events, constructs a directed acyclic graph of causal dependencies using timing, context overlap, and shared artifact modification patterns.

---

## Risks

- Agent framework vendors (Anthropic, OpenAI, LangChain) could add native causal tracing
- The legal question of "what counts as sufficient attribution" is not yet settled — courts will determine the standard
- Multi-framework support (LangChain + CrewAI + Microsoft Agents simultaneously) requires significant integration work
- Enterprises may use this tool to identify and contest agent vendor liability — making it politically controversial with agent platform vendors

---

## Next validation step

1. Find a General Counsel or Chief Compliance Officer at a financial institution deploying AI agents: "If your AI agent executed a transaction incorrectly, how would you reconstruct which instruction caused it and who was responsible?"
2. Read EU AI Act Article 12 implementing regulations — what specifically must the audit log contain? Is cross-agent causal attribution required or just individual action logging?
3. Build a proof-of-concept: take two LangChain agent runs where Agent B used Agent A's output, reconstruct the causal link from log events alone

---

## Lab sources relevant

- `sources/deterministic-agent-safety/` — fail-closed hook engine; instrumentation points for event capture
- `components/agent-safety-firewall/` — existing Phase 1 implementation; extend with causal event capture
- `research/REUSABLE_PATTERNS_CATALOG.md` — Pattern 11 (append-only decision log), Pattern 1 (fail-closed hook engine)
- `research/domain-synthesis/agent-engineering-and-safety.md` — agent security synthesis

---

## Related ideas

- `agent-permission-firewall.md` — pre-execution enforcement (what agents are ALLOWED to do); this is post-incident attribution (what agents DID and why)
- `nhi-agent-identity-governance.md` — agent authentication identity; this resolves shared credentials to specific agents
- `pld-liability-evidence-vault.md` — PLD litigation defense evidence; agent attribution is one component
