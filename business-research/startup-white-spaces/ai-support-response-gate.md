# White Space: AI Support Response Pre-Send Gate

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

AI customer support agents (Intercom Fin, Zendesk AI, Salesforce Einstein, Freshdesk Freddy)
are auto-resolving 50–70% of support tickets — often composing and **sending** responses without
human review.

Existing QA tools (MaestroQA, Klaus/Zendesk QA, Level AI, Lorikeet Coach) score interactions
**after** they are sent. They operate retrospectively: analyze → coach → train. By the time a
problem is caught, the bad response has already reached the customer.

**The unmet need**: A pre-send quality gate that evaluates a drafted AI response **before
delivery** for:
- **Factual accuracy**: Is the response factually correct against the company's knowledge base?
- **Brand policy compliance**: Does the response match the company's tone, prohibited phrases, and commitment policies?
- **Regulatory compliance**: For financial services, healthcare, or insurance — does the response contain regulated claims, financial advice, or medical suggestions that require disclosure or escalation?
- **Hallucination detection**: Is the response fabricating product features, pricing, or capabilities that don't exist?
- **Commitment enforcement**: Is the response making a promise (refund, free tier, extension) that exceeds agent authorization?

**The gap**: All existing tools are retrospective. No product sits in-line, pre-send, as an
enforcement gate — blocking or flagging before the response is delivered.

Source: intryc.com (AI QA Software Buyers Guide 2026), lorikeetcx.ai (AI QA tools 2026), crescendo.ai, verified 2026-06-23.

---

## Why this matters more in 2026

- **AI auto-resolution rates are crossing 60–70%**: At this level, the blast radius of a single bad AI response multiplied across thousands of interactions is material
- **Regulated industries have compliance exposure**: A financial services AI composing and sending investment advice, or a health insurance AI making coverage determinations, without human review, is a regulatory violation
- **EU AI Act Article 13 (transparency)**: Users must be informed when interacting with AI — an AI that sends a response claiming to be human violates this
- **Consumer trust**: A single viral bad AI response (wrong refund promise, factually incorrect diagnosis, discriminatory output) causes brand damage disproportionate to the incident

---

## Target customer

**Primary**: CX operations / Head of Support at companies with AI-assisted support in high-stakes industries:
- Financial services (investment advice, account changes, claims)
- Healthcare (symptom questions, coverage questions, prescription information)
- Insurance (claims, coverage, deductibles)
- E-commerce (returns, pricing disputes, fraud claims)

**Secondary**: Platform teams building their own AI support workflows (not using Intercom/Zendesk out of the box).

**Trigger event**: A compliance audit, a regulatory inquiry about AI-composed customer communications, or a high-profile AI support failure that reaches the news.

---

## Proposed product

A pre-send evaluation layer that integrates with AI support systems as a middleware step:

1. **Knowledge base fact-check**: Before sending, vectorizes the drafted response and checks every factual claim against the company's official knowledge base — flags any claim not grounded in the KB
2. **Brand policy scanner**: Configurable ruleset of prohibited phrases, required disclosures, tone requirements — blocks or flags policy violations before send
3. **Commitment scope enforcement**: Configurable authorization matrix defining what commitments an AI agent is permitted to make (e.g., "max refund: $50 without human approval") — blocks or escalates over-authorized responses
4. **Regulatory classifier**: For regulated industries, classifies the response category (financial advice, medical suggestion, legal interpretation) and enforces escalation or disclosure requirements
5. **Hallucination detection**: Uses the company's own product/pricing database to verify that all stated facts (features, prices, policies) are accurate
6. **Human escalation router**: When a response fails any check, routes to a human queue with the failure reason highlighted — not just "flagged", but "flagged for: unauthorized refund commitment of $200"
7. **Audit trail**: Every pre-send evaluation logged with pass/fail, reason, and disposition — for regulatory review and model fine-tuning

**Integration surface**: Webhook API that sits between AI support draft and delivery — works with any underlying support AI system.

---

## Competitor landscape (verified 2026-06-23)

| Tool | Approach | Gap |
|---|---|---|
| MaestroQA | Post-hoc interaction scoring and coaching | Retrospective only; no pre-send interception |
| Klaus / Zendesk QA | Post-hoc AI quality assurance | Retrospective; no pre-send enforcement |
| Level AI | Real-time agent assist + post-hoc QA | Agent assist (suggestions to human agents) but not a pre-send gate for AI responses |
| Lorikeet Coach | QA + coaching for CX teams | Retrospective analysis and coaching; no pre-send enforcement |
| Crescendo | Automated QA for CX | Scoring after delivery; no pre-send gate |

**Gap confirmed**: No product intercepts and evaluates AI-generated support responses before they are sent to the customer.

This is distinct from the `ai-frontend-quality-gate` concept (code linting for AI-generated code). This is CX-layer, customer-facing, real-time response enforcement.

---

## Founder fit

- CS background: webhook middleware + fact-checking against a vector knowledge base is an engineering problem
- EU-based: EU AI Act Article 13 transparency requirement creates a compliance angle specifically for AI-composed customer communications in the EU
- B2B; CX operations buyer; pure software
- Natural distribution: integrate with Intercom, Zendesk, Freshdesk marketplaces as an app/integration

---

## Revenue model

- **Per-evaluation SaaS**: $0.01–$0.05 per AI response evaluated (usage-based)
- **SaaS tier**: $500/month (up to 10,000 evaluations), $2,000/month (up to 100,000 evaluations)
- **Enterprise**: $10,000–$50,000/year with custom regulatory classifier, SIEM integration, audit export

---

## Technical approach

1. Webhook endpoint that receives draft AI response + conversation context
2. Fact-check pipeline: embed response → cosine similarity against KB embeddings → flag low-similarity factual claims
3. Policy scanner: rule-based + LLM-as-judge for nuanced brand policy; configurable ruleset JSON
4. Response: JSON with {pass: bool, issues: [], escalate: bool, reason: string} — consuming system decides disposition
5. Latency target: <500ms end-to-end (acceptable for pre-send interception; humans don't notice a half-second delay in chat responses)

---

## Risks

- Intercom/Zendesk/Freshdesk could build this natively into their AI support products
- The latency requirement (<500ms) may be hard to meet for complex regulatory classification
- "Pre-send gate" creates friction in AI auto-resolution workflows — CX teams may resist adoption if false positive rate is high
- The value is highest in regulated industries; selling to financial services and healthcare has long enterprise sales cycles

---

## Next validation step

1. Find a Head of CX at a financial services company using Intercom Fin or Zendesk AI — ask: "How confident are you that your AI support agent never sends something it shouldn't? How would you know if it did?"
2. Build a proof-of-concept: Intercom webhook → LLM fact-checker against a small KB → Slack alert when claim fails. Test it in 1 day.
3. Check Intercom App Store and Zendesk Marketplace — is there any app that does pre-send quality checking? (Confirming the gap via their app ecosystems)

---

## Related ideas

- `business-research/startup-white-spaces/evidenceops-ai-act-nis2-vsme.md` — adjacent compliance angle for AI systems in production
- `research/REUSABLE_PATTERNS_CATALOG.md` — Pattern 5 (fail-closed hook engine) applicable: pre-send gate is a pre-execution enforcement hook at the CX layer
