# White Space: Multi-Regulation EU Compliance Platform

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

By end of 2026, a mid-sized EU technology company (50–500 employees) is simultaneously subject to:

| Regulation | Status | Enforcement body |
|---|---|---|
| GDPR | Fully enforced since 2018 | National DPAs |
| NIS2 | Live in 22/27 member states; personal fines since June 2026 | National CSIRT / Competent authority |
| EU AI Act | Article 49 registration August 2026; full enforcement August 2026–December 2027 | EU AI Office + national market surveillance |
| CRA | Vulnerability reporting September 2026; full product obligations December 2027 | National market surveillance |
| DORA | Enforced since January 2025 (financial sector) | EBA / ESMA / EIOPA |
| EU Data Act | Applicable since September 2025; new product obligations September 2026 | National data authorities |
| Revised PLD | December 2026 (software = "product" for liability) | Courts / product safety authorities |

Each regulation:
- Is administered by a different authority
- Has different deadlines
- Requires different documentation artifacts
- Has different incident reporting chains and classification schemas

A mid-market company cannot hire the compliance expertise to manage all of these simultaneously.
Existing tools are siloed by regulation. No single EU-native platform covers all of them.

---

## The multi-regulation convergence problem

**DORA + NIS2 dual reporting** (most immediate pain): Financial institutions in Germany, France,
and Netherlands are subject to BOTH DORA (ESAs: EBA/ESMA/EIOPA) AND NIS2 (national CSIRTs).
A cyber incident triggers two parallel reporting chains with different thresholds, different formats,
and different deadlines. The 26 May 2026 NIS Cooperation Group standard incident templates
(soon mandatory) add a third schema layer.

**NIS2 + CRA overlap**: A company that discovers a vulnerability in its software product faces
NIS2 incident reporting (if it is an essential/important entity) AND CRA vulnerability reporting
(within 24h to ENISA + national CSIRT). Different schemas, different portals, different timelines.
Deduplication without tooling is error-prone and expensive.

**AI Act + GDPR overlap**: AI systems processing personal data require GDPR DPIA (Data Protection
Impact Assessment) AND AI Act risk classification. The documentation artifacts overlap but the
schemas differ. Many companies are commissioning two parallel compliance workstreams.

**Compliance rate reality (verified, June 2026):**
- Only 16% of NIS2-in-scope businesses are fully compliant (CyberSmart research, April 2026)
- 96% of financial firms are behind on AI Act + NIS2 + DORA simultaneously (Kiteworks research)
- 84% of organizations facing active NIS2 enforcement self-report as not ready

Source: itsecurityguru.org, cybersmart.co.uk, kiteworks.com, verified 2026-06-23.

---

## Target customer

**Primary:** EU tech companies, 50–500 employees, in sectors with regulatory exposure:
- Fintech (DORA + NIS2 + AI Act + GDPR)
- Healthtech (AI Act + NIS2 + GDPR)
- IoT/hardware (CRA + AI Act + GDPR + Data Act)
- SaaS B2B selling to regulated industries (NIS2 supply chain + AI Act + GDPR)

**Trigger event:** One of the following usually starts the conversation:
- A customer or bank sends a compliance questionnaire (NIS2 supply chain or VSME)
- Legal team sends an AI Act notice
- CISO gets a personal liability warning (NIS2 management fines)
- Auditor asks for documentation evidence

**Primary buyer:** COO, Head of Legal/Compliance, or CISO (NIS2 personal liability creates board-level urgency).

---

## Proposed product

A multi-regulation compliance management platform for EU mid-market companies:

**Core capabilities:**
1. **Regulatory exposure calculator**: Input company profile (size, sector, country, products) → output which regulations apply and at what obligation level
2. **Unified obligation calendar**: All deadlines across all applicable regulations in one timeline with customized alerts
3. **Evidence library**: Structured repository for compliance artifacts (policies, risk assessments, incident logs, technical documentation) tagged by regulation and article
4. **Cross-regulation deduplication**: Identifies where an artifact satisfies multiple regulations simultaneously (e.g., an incident log format that satisfies both NIS2 Article 23 and AI Act Article 12)
5. **Incident reporting hub**: Multi-authority incident reporting with automatic routing, schema translation, and deadline tracking (NIS2 CSIRT + DORA ESA + CRA ENISA in one workflow)
6. **Audit trail**: Tamper-evident log of all compliance activities exportable for regulator review

**Not included (out of scope for initial product):**
- Legal advice
- Full GRC platform (no risk scoring engine, no third-party risk management)
- Implementation consulting

---

## Why now

The convergence of five major EU regulations reaching enforcement simultaneously is unprecedented:
- No incumbent has captured the multi-regulation mid-market position
- US GRC vendors (Vanta, Drata, OneTrust) do not understand EU-specific regulatory structure, authority routing, or enforcement timelines
- EU-native competitors are mostly single-regulation vertical tools
- The personal management liability provisions of NIS2 (June 2026) create board-level urgency
- RegTech market growing at 20% CAGR globally; EU specifically at 22.9% CAGR

Source: futuremarketinsights.com, proxymity.io, verified 2026-06-23.

---

## Competitor landscape

Source: product websites, kla.digital, orbiqhq.com, verified 2026-06-23.

| Competitor | Coverage | Target | Gap |
|---|---|---|---|
| OneTrust | GDPR, AI governance, DORA | Enterprise | €30k+; US-origin; complex; not EU-native regulatory routing |
| Vanta | SOC 2, ISO 27001, AI Act module | Scale-up | $10k+; US-origin; AI Act module is add-on; no NIS2 incident routing |
| Drata | SOC 2, ISO 27001, NIS2 module | Scale-up | US-origin; NIS2 module limited; no CRA or Data Act |
| EuroComply | AI Act, GDPR, NIS2, DORA, CRA | SME | €0–€149/month; covers frameworks but evidence management depth unclear |
| Secfix | DACH SMEs, NIS2-native | DACH region | NIS2 focused; no AI Act or CRA coverage |
| Kertos / DataGuard | Germany, all-in-one | German market | German language focus; no multi-country routing |
| Legalithm | EU AI Act only | SME | AI Act only; free through April 2028 |

**Key competitive finding**: EuroComply (€0–€149/month) is the closest multi-framework EU-native competitor. It covers AI Act + GDPR + NIS2 + DORA + CRA in concept, but evidence management depth and incident routing sophistication are unclear. The mid-market gap (50–500 employees, €20k–€80k compliance budget) between checklist tools and enterprise GRC is confirmed.

---

## Differentiation

1. **EU-native routing**: Only platform that routes incidents to the correct authority for each regulation simultaneously (ENISA for CRA, ESA for DORA, national CSIRT for NIS2)
2. **Cross-regulation deduplication**: One artifact submission that satisfies multiple regulatory schemas
3. **Mid-market pricing**: €10k–€60k/year — above checklist tools, below enterprise GRC
4. **Enforcement timeline tracking**: Live tracking of which regulations are actively being enforced in which member states, so companies prioritize correctly

---

## Founder fit

- CS background: evidence management platform is a web product engineering problem
- Economics background: regulatory compliance context, EU market knowledge
- EU-based: firsthand regulatory environment understanding; proximity to buyers
- B2B; compliance-driven; no hardware
- Multi-framework knowledge already built through this research

---

## Revenue model

- **Mid-market SaaS**: €2,000–€8,000/month per company (annual contracts)
- Tiered by regulations covered and company size
- Consulting partnerships: white-label or referral arrangements with compliance consultants (they bill €5k–€20k per engagement; this replaces/augments their toolstack)

---

## Risks

- Market is served by more tools than expected; EuroComply at low price could expand
- Large GRC vendors (ServiceNow, OneTrust) can add EU-specific regulatory routing
- Regulatory landscape is evolving rapidly (Omnibus simplification, NIS2 amendments) — staying current is a core operational cost
- Selling to 50–500 employee companies requires high-volume, low-touch sales motion (not enterprise)

---

## Evidence quality notes

- Market size estimates are [hypothesis] from analyst reports, not primary regulatory data
- Competitor pricing verified from official websites; EuroComply evidence management depth not independently verified
- Compliance rate statistics from CyberSmart (self-reported survey) — directional signal, not audited data
- Next step: interview 3 COOs at 50–200 person EU tech companies about their current compliance spend and tool stack

---

## Next validation step

1. Interview 3 COOs / compliance leads at EU tech companies (50–200 employees): what does their current compliance stack look like? What does it cost?
2. Test EuroComply free tier: how deep is its evidence management? Does it actually route incidents or just provide checklists?
3. Find one NIS2 + DORA dual-subject financial institution: how are they handling the dual reporting problem today?
4. Decide: is this a separate product from EvidenceOps (AI Act/NIS2/VSME) or the same product at a different scale/scope?

---

## Relationship to EvidenceOps

`evidenceops-ai-act-nis2-vsme.md` covers AI Act + NIS2 + VSME for smaller SMEs.
This concept is a superset: adds CRA, DORA, Data Act, PLD — and targets mid-market (50–500 employees)
rather than micro-SMEs. These may be the same product at different tiers, or separate products.
Decision pending on customer validation.
