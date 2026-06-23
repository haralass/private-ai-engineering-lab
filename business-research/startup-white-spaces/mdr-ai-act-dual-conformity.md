# White Space: MDR + AI Act Dual Conformity Platform (Medical AI)

status: research
research_date: 2026-06-23
evidence_level: initial-research
urgency: MEDIUM-HIGH — Notified Body queue requires 12–18 month lead time; 2028 enforcement

---

## Problem

AI-enabled medical devices and Software as a Medical Device (SaMD) with AI/ML components
face two overlapping regulatory regimes simultaneously:

1. **EU Medical Device Regulation (MDR, Regulation 2017/745)**: Full enforcement since May 2021. SaMD must undergo conformity assessment by a Notified Body. Technical documentation includes software lifecycle, risk management (ISO 14971), and software classification.

2. **EU AI Act**: SaMD with AI/ML components are classified as **high-risk AI systems** under Annex III (§5 — medical devices that use AI for diagnostic, monitoring, or treatment purposes). Full AI Act obligations for high-risk AI apply from **August 2, 2028** (extended by Digital Omnibus). But:
   - Article 49 registration (EU AI database): **August 2, 2026** — 40 days
   - Notified Body pre-submission meetings are already including AI Act questions (2026)

**The dual compliance burden**:
- MDR requires: software lifecycle documentation, risk management file (ISO 14971), clinical evaluation, performance data
- AI Act requires: Annex IV technical documentation (data governance, accuracy metrics, human oversight), post-market monitoring (Article 72), logging of AI system decisions (Article 12), registration in EU AI database (Article 49)

**The gap**: These requirements overlap significantly (both require risk documentation, both require software change management) but use **different schemas, different article references, and different submission formats**. Manufacturers are commissioning two parallel compliance workstreams — expensive, slow, and error-prone.

**No SaaS platform integrates MDR technical file structure with AI Act Annex IV requirements** as of 2026-06-23.

Source: MedDeviceGuide (MDR + AI Act dual compliance guide, 2026), IntuitionLabs, QuickBird Medical, Petrie-Flom Center (Harvard Law) analysis, verified 2026-06-23.

---

## Target customer

**Primary**: Regulatory Affairs Director at EU medtech companies (50–500 employees) developing SaMD.

**Segments**:
- Established medical device companies adding AI/ML components to existing products (highest urgency — they must retrospectively map existing MDR files to AI Act)
- AI-native SaMD startups building AI diagnostic tools from scratch (they must do both from day one)
- Digital health companies with AI features embedded in wellness or monitoring apps (edge cases — classification depends on intended use)

**Secondary**: Notified Bodies themselves (they need checklists and assessment workflows for AI Act requirements during MDR conformity assessment — they are being asked AI Act questions but have no standardized assessment framework).

**Total market [hypothesis]**: ~3,000–5,000 EU SaMD manufacturers subject to both MDR and AI Act.

---

## Proposed product

An integrated MDR × AI Act conformity documentation platform:

1. **Dual regulatory mapper**: For each requirement in the MDR technical file (Annex II), automatically identifies the corresponding AI Act obligation (if any) and the overlap area — showing where one document can satisfy both
2. **Gap analysis**: Given an uploaded MDR technical file, identifies which AI Act Annex IV requirements are not yet addressed — generates a gap list with specific article references and recommended evidence
3. **Unified documentation structure**: A document template system that produces artifacts that simultaneously satisfy MDR and AI Act requirements — avoiding duplicate documentation
4. **Article 49 registration assistant**: Structured workflow for registering the AI system in the EU AI database by August 2, 2026 — includes auto-fill of required fields from the technical file
5. **Post-market monitoring integration**: Connects MDR post-market surveillance (PMS) data to AI Act Article 72 post-market monitoring requirements — same event stream, different report formats
6. **Notified Body Q&A tracker**: Logs all AI Act questions raised by Notified Bodies during pre-submission and assessment — builds a shared knowledge base of what NBs are actually asking
7. **Regulatory timeline dashboard**: Tracks which AI Act articles apply, which MDR obligations are already met, and which remain open — with deadlines and submission targets

---

## Why now

- **Article 49 registration in 40 days** (August 2, 2026): Even with the 2028 Annex III extension, EU AI database registration is still August 2026 — companies need to know what to register now
- **Notified Body pre-submission meetings** are already including AI Act questions in 2026: manufacturers who aren't prepared are losing pre-submission meeting time to compliance gaps
- **The Notified Body queue is 12–18 months**: A company that hasn't started AI Act integration into their MDR documentation now will not be conformant by 2028
- **No tooling exists** for the dual documentation problem: advisory firms (MDxCRO, QuickBird, IntuitionLabs) are filling this with consulting — a productizable workflow
- **Digital Omnibus (2026) did NOT reduce obligations**, just extended the timeline: the work still needs to be done

---

## Competitor landscape (verified 2026-06-23)

| Competitor | Approach | Gap |
|---|---|---|
| MDxCRO | Medical device regulatory consulting | Consulting, not SaaS; expensive; not scalable |
| IntuitionLabs | AI Act + pharma/meddev advisory | Advisory content + some tooling; not a full platform |
| QuickBird Medical | MDR compliance SaaS | MDR-focused; AI Act integration not yet built |
| DQS Global | MDR + AI Act certification services | Certification body; not a compliance platform |
| Legalithm | EU AI Act classification | AI Act only; no MDR integration |
| MedDeviceGuide | Guides and templates | Content/advisory; not a product |

**Gap confirmed: no integrated MDR × AI Act SaaS platform exists.**

---

## Founder fit

- CS background: document gap analysis + structured template generation is an engineering problem
- Economics background: regulatory compliance market context
- EU-based: proximity to affected medtech companies; access to Notified Bodies and regulatory consultants
- B2B; Regulatory Affairs buyer; pure software
- High-value niche: medtech companies have large compliance budgets

---

## Revenue model

- **SaaS**: €3,000–€10,000/month per manufacturer (annual contracts)
- **Notified Body license**: €500–€2,000/month per Notified Body assessor (they need the gap analysis tools for their own assessment workflow)
- **One-time gap analysis**: €5,000–€20,000 for a retrospective mapping of an existing MDR technical file to AI Act requirements

---

## Risks

- The EU Commission may publish unified guidance that simplifies the dual documentation requirement (ongoing Digital Omnibus process)
- Notified Bodies may develop their own assessment templates, crowding out the tool from that side
- The 2028 deadline feels distant to manufacturers focused on near-term obligations; sales cycle may be long
- The addressable market (~3,000–5,000 companies) is small; pricing must compensate

---

## Next validation step

1. Talk to one Regulatory Affairs Director at an EU SaMD company: "Are you treating MDR and AI Act compliance as separate parallel workstreams? How many hours/month does the overlap cost you?"
2. Attend or review notes from a recent MDR pre-submission meeting — what AI Act questions did the Notified Body ask?
3. Download the official AI Act Annex IV template + a typical MDR Annex II technical file — manually map the overlap to validate the product concept

---

## Related ideas

- `evidenceops-ai-act-nis2-vsme.md` — AI Act compliance for general SMEs; this is a vertical slice for medtech
- `pld-liability-evidence-vault.md` — AI Act non-compliance = PLD defectiveness; medical AI PLD exposure is extreme
- `multi-regulation-compliance-platform.md` — this is a vertical version of the multi-regulation platform for healthcare

---

## Evidence quality notes

- MDR + AI Act dual compliance burden confirmed from primary regulatory texts and multiple law firm analyses
- August 2, 2026 Article 49 registration deadline confirmed from AI Act text (Article 49(2))
- August 2, 2028 Annex III high-risk extension confirmed from Digital Omnibus package (May 2026)
- Notified Body queue: 12–18 month estimate from MedDeviceGuide and regulatory consultant interviews published online
- [Hypothesis] 3,000–5,000 SaMD manufacturers: from EMA SaMD survey extrapolation; actual number not officially published
