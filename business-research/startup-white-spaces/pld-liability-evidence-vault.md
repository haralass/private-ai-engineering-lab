# White Space: PLD Liability Evidence Vault

status: research
research_date: 2026-06-23
evidence_level: initial-research
urgency: HIGH — 6 months to December 9, 2026

---

## Problem

The revised EU Product Liability Directive (PLD, EU 2024/2853) treats software and AI systems
as "products" from **December 9, 2026** — 169 days from today. Key provisions:

1. **Strict liability**: Injured parties can claim compensation for physical harm and non-trivial data loss from defective AI/software — no need to prove negligence
2. **Eased burden of proof**: Courts presume defectiveness if a claimant shows the product failed to perform as expected — the defendant must rebut
3. **AI Act defectiveness link**: Non-compliance with the EU AI Act is treated as evidence of product defectiveness. This means every EU AI Act compliance gap creates PLD liability exposure.
4. **Contractual exclusions void**: Companies cannot contract out of PLD liability; supply chain indemnities must be restructured
5. **Cybersecurity update failures**: Failure to provide timely security updates can constitute a product defect

**The problem**: EU AI and software companies have ~6 months to establish a defensible,
timestamped compliance posture before the first PLD claims can be filed. After December 9, 2026,
if an AI system causes harm, the company must prove it was compliant with the AI Act at the
time of harm — not just now. That requires **point-in-time evidence records** that most companies
do not have.

Source: Gibson Dunn ("EU Product Liability Directive: Responding to Software, AI and Complex Supply Chains"), Freshfields ("How the New PLD Turns AI Act Compliance into a Liability Issue"), Pinsent Masons, Reed Smith, verified 2026-06-23.

---

## The gap in existing tools

| Tool | Gap |
|---|---|
| Generic GRC platforms (Vanta, Drata, Sprinto) | Track current compliance state; do not create timestamped evidence snapshots for litigation defense |
| EU AI Act compliance tools (Legalithm, eyreACT) | Risk classification and documentation; not designed for point-in-time evidence archiving with litigation-grade provenance |
| Document management (SharePoint, Notion) | Storage only; no versioning with cryptographic timestamps; no AI Act article mapping |
| Legal firms (Gibson Dunn, Freshfields, Reed Smith) | Generating awareness; not selling a product |

**No tool positions explicitly around PLD defense readiness** — creating a first-mover opportunity.

---

## Target customer

**Primary**: General Counsel + CTO at EU tech companies with AI products (50–500 employees) who:
- Have received a customer questionnaire or legal notice about AI Act compliance
- Are reviewing their product liability exposure in light of December 2026
- Deploy AI systems in any of the Annex III high-risk categories

**Secondary**: Non-EU companies (US, UK, APAC) with EU customer bases deploying AI systems — they
face PLD claims in EU courts if their AI causes harm to EU residents.

**Trigger event**: A law firm memo, a customer contract renewal with a PLD clause, or a CISO/GC
reading a news article about the December 2026 deadline.

---

## Proposed product

A **compliance evidence vault** that creates point-in-time, cryptographically timestamped
snapshots of an AI system's compliance posture — specifically designed as a litigation defense
instrument:

1. **AI system inventory**: Register each AI system by risk category (Annex III high-risk vs. limited risk vs. general purpose)
2. **Compliance snapshot**: At user-defined intervals (monthly, quarterly, or event-triggered), snapshot the current state of:
   - EU AI Act Annex IV technical documentation (risk classification, data governance, accuracy metrics, human oversight measures)
   - GPAI Code of Practice documentation (if applicable)
   - Vulnerability disclosure records (CRA compliance)
   - Model changelog (what changed between versions and when)
   - Third-party AI component provenance (who supplied what AI component at what version)
3. **Cryptographic timestamping**: Each snapshot is RFC 3161 / qualified timestamp authority (QTA) signed — legally defensible proof that the record existed at that moment in time
4. **AI Act ↔ PLD defectiveness mapping**: Audit view showing which AI Act articles apply to each system, evidence of compliance for each, and gaps flagged
5. **Incident response trigger**: If a user reports a harm event, instantly packages the compliance snapshot from the relevant date range into a legal evidence bundle
6. **Supply chain provenance**: Tracks which AI components came from which vendors with what compliance representations — critical for "state of the art" and "contributory cause" defenses

---

## Why now

- **December 9, 2026 is hard and cannot be pushed**: Unlike EU AI Act annex obligations (extended by Digital Omnibus), PLD national transposition deadline is December 9, 2026. No further extensions expected.
- **AI Act creates the compliance baseline**: AI Act non-compliance = PLD defectiveness. Every company that cares about PLD must already be tracking AI Act compliance — the vault is a natural extension.
- **Evidence must be contemporaneous**: If a company starts documenting compliance after a harm event, the court will not accept it as evidence of the system's state at the time of harm. Evidence must be accumulated proactively starting now.
- **Law firms are generating awareness but not selling tools**: Gibson Dunn, Freshfields, Pinsent Masons have all published guidance — they are creating demand that no tool is capturing yet.
- **No purpose-built PLD readiness SaaS found** (verified from product website searches, 2026-06-23)

---

## Competitor landscape (verified 2026-06-23)

No dedicated PLD evidence vault product found. The closest adjacent tools:

| Competitor | Approach | Gap |
|---|---|---|
| Vanta / Drata / Sprinto | Continuous compliance monitoring | Track current state; no timestamped litigation-grade evidence snapshots; no PLD-specific framing |
| Legalithm | EU AI Act risk classification | Free self-serve; no evidence vault, no timestamping, no incident response packaging |
| Witness Compliance | Static Annex IV templates | Document generation only; no versioning, no timestamping |
| OneTrust | Broad AI governance | Enterprise only; no PLD-specific evidence vault |

**First-mover gap confirmed.**

---

## Founder fit

- CS background: cryptographic timestamping + document versioning is a well-defined engineering problem
- Economics/regulatory background: EU PLD + AI Act intersection is a compliance problem requiring regulatory literacy
- EU-based: firsthand knowledge of the regulatory environment; proximity to affected companies
- B2B; General Counsel + CTO buyer; pure software
- Natural channel: EU AI Act compliance consultants and law firms (they recommend tools to their clients)

---

## Revenue model

- **SaaS**: €500–€2,000/month per company (depending on number of AI systems registered)
- **Incident response bundle add-on**: €5,000–€20,000 per incident (packaging evidence on demand)
- **Annual contracts**: compliance tools are sticky — once embedded in GRC workflow, churn is low

---

## Technical requirements

- Document versioning with RFC 3161 qualified timestamping (e.g., via DigiCert or a EU QTSP — Qualified Trust Service Provider — for legally defensible timestamps under eIDAS Regulation)
- Structured AI system registry with EU AI Act Annex IV article mapping
- Evidence package export (PDF + raw data in legally admissible format)
- Access control (General Counsel needs different view than engineering team)

---

## Risks

- The compliance baseline companies need to document must first exist — if they haven't done the AI Act compliance work, a vault has nothing to timestamp. Product may require onboarding consulting.
- PLD claims may not materialize at scale before 2028+ (courts are slow; first filed cases will take years to reach judgment)
- Large GRC vendors (ServiceNow, OneTrust) may add PLD modules
- Revised AI Liability Directive (withdrawn October 2025) may be revived or replaced, changing the landscape

---

## Relationship to EvidenceOps

`evidenceops-ai-act-nis2-vsme.md` covers ongoing compliance management (AI Act + NIS2 + VSME).
This concept focuses specifically on **evidence archiving for litigation defense** rather than
ongoing compliance workflow. These may be the same product (EvidenceOps with a "litigation vault"
add-on module) or a standalone product for General Counsel buyers (vs. CISO/compliance officer
buyers for EvidenceOps).

---

## Next validation step

1. Find a General Counsel at an EU AI company — ask: "Are you tracking your AI system compliance state at a point-in-time level for PLD defense? What does that look like today?"
2. Contact one of the EU law firms (Gibson Dunn, Freshfields) that published PLD guidance — ask if their clients are asking for tooling referrals
3. Check if any RFC 3161 timestamping service has a developer API suitable for building this on top of (DigiCert, GlobalSign, Comodo)

---

## Evidence quality notes

- December 9, 2026 deadline confirmed from EU 2024/2853 directive text and law firm analysis
- PLD defectiveness = AI Act non-compliance link confirmed from Freshfields and Gibson Dunn analysis
- AI Liability Directive withdrawal (October 2025) confirmed — revised PLD is now the operative instrument
- No competitor product found after systematic search (law firm sites, GRC vendor sites, ProductHunt, G2)
- [Hypothesis] Market size and ARR estimates are directional
