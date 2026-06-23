# EvidenceOps — AI Act / NIS2 / VSME Compliance

category: A
status: raw
research_date: 2026-06-23

---

## Category A definition

Ideas that fit a founder with a CS and Economics background and can start as a
software / AI / B2B tool, without hardware, factory, municipality permits, or
large initial capital.

---

## Title

EvidenceOps — AI-assisted evidence collection and documentation for regulatory compliance
(EU AI Act, NIS2, VSME/ESG)

---

## Problem

Small and medium enterprises face a growing set of EU regulatory obligations:
- **AI Act**: organizations deploying or developing AI systems must document risk
  assessments, conformity processes, and transparency measures
- **NIS2**: network and information security obligations for a widened set of entities
- **VSME**: voluntary ESG reporting standard for SMEs (ESRS basis)

In each case, the compliance burden is primarily documentation and evidence management,
not technical implementation. SMEs lack the legal/compliance teams that large enterprises
have, and existing GRC (Governance, Risk, Compliance) tools are built for enterprises.

---

## Target users

- status: needs-user-input (who exactly? which sectors? which country first?)
- Likely: SME founders and operations managers who received a compliance questionnaire
  from a client or bank and need to respond
- Possibly: compliance consultants who serve multiple SME clients

---

## Proposed solution

status: needs-user-input

Possible direction: A tool that helps SMEs collect, organize, and export evidence for
specific compliance frameworks. Not a legal adviser. Not a full GRC platform. A structured
evidence organizer that maps regulatory requirements to required documentation.

---

## Why now

Sources: primary regulatory sources, verified 2026-06-23. Full citations in `research/domain-synthesis/regulatory-landscape.md`.

**EU AI Act (Regulation (EU) 2024/1689, EUR-Lex):**
- Prohibited AI practices applied from **2 February 2025** (Article 5)
- GPAI model provider obligations apply from **2 August 2025** (Article 53)
- Full general application for most AI systems: **2 August 2026** (Article 113)
- SMEs are in scope — Article 62 offers supportive measures (reduced fees, sandbox access) but no exemptions
- The August 2026 date creates near-term compliance pressure for SME deployers of high-risk AI systems

**NIS2 Directive (Directive (EU) 2022/2555):**
- Transposition deadline was 17 October 2024; 21/27 EU Member States have enacted national law (as of 2026-06-23)
- **Small enterprises** (under 50 employees, turnover ≤ €10M) are typically **exempt**
- **Medium enterprises** (50–249 employees) in covered sectors (energy, banking, health, digital infrastructure, manufacturing, food, etc.) are in scope
- Article 21 requires documenting: risk analysis policies, incident handling, business continuity, supply chain security, cryptography policy, MFA
- The compliance pain is evidence management, not technical implementation

**VSME (Commission Recommendation C(2025) 4984):**
- EFRAG delivered the final VSME Standard on **17 December 2024**
- European Commission adopted as a Recommendation on **30 July 2025** (currently voluntary)
- Demand drivers: banks (green loan eligibility), large enterprise customers (CSRD supply chain), investors (SFDR)
- 23+ VSME tools already exist in market (see competitor section)

**Cross-framework urgency pattern:**
Documentation burden is the shared compliance cost across all three: AI Act (technical documentation, conformity records), NIS2 (risk assessments, incident logs, Article 21 measure evidence), VSME (sustainability disclosures). The operational challenge converges on structured evidence collection and reporting.

---

## Why it fits the founder

- CS background: can build the software product
- Economics background: familiar with regulatory compliance concepts, SME context
- No hardware required
- B2B model with clear compliance-driven urgency

---

## Relevant sources in the lab

None directly. No regulatory tooling sources have been imported.

---

## Existing product concepts

None. This is a raw idea.

---

## Assumptions

- SMEs actually struggle with AI Act / NIS2 / VSME documentation
- The pain is the evidence collection process, not legal interpretation
- A structured tool (not a legal chatbot) would be valued

---

## Unknowns

- Whether compliance consultants or SME founders are the primary buyer — needs user interviews
- Which single regulation to focus on first (AI Act, NIS2, or VSME)
- Whether medium enterprise IT managers or legal/compliance teams are the day-to-day users
- Market size estimates: status: needs-user-input (do not add numbers without source)

---

## Evidence

evidence_level: initial-research

Updated 2026-06-23. Regulatory facts now verified from primary sources (EUR-Lex, EFRAG, AI Act Service Desk).
Key findings: all three frameworks have near-term active or upcoming obligations. SME exemptions are narrow (NIS2 exempts small enterprises, not medium; EAA exempts microenterprises for services only). No verified product combines all three frameworks in an SME-accessible interface. Enterprise GRC tools cost €30k+ per year. Free tools cover scoping but not evidence collection workflows.

---

## Competitor landscape

Source: `research/domain-synthesis/regulatory-landscape.md`, accessed 2026-06-23.

| Vendor | Coverage | Pricing | SME accessible? |
|---|---|---|---|
| Legalithm | EU AI Act scoping + classification | Free through ~April 2028 | Yes — but limited to scoping, not evidence collection |
| OneTrust | Enterprise GRC | €30k–€100k+/year | No |
| Holistic AI | AI governance | Enterprise (sales call) | No |
| Credo AI | AI governance | Enterprise | No |
| Vanta / Drata | SOC 2, ISO 27001 (some NIS2) | $7k–$80k/year | Borderline |
| ExecutESG | VSME-specific | Free Basic; €149+/year Pro | Yes |
| Vision Zero Connect | AI-powered VSME | €700/year | Yes |

**Gap identified:** No verified product combines AI Act + NIS2 + VSME in a single SME-accessible interface. Enterprise GRC tools are inaccessible at SME price points. The specific gap is ongoing evidence collection workflows (not just scoping checklists).

---

## Risks

- Regulatory timelines could shift (Omnibus simplification package may change EAA/CSRD scope)
- Legal liability if tool gives incorrect compliance guidance
- Legalithm is free through ~April 2028, creating price competition for AI Act scoping
- Large GRC vendors (Vanta, Drata) could add AI Act / VSME modules

---

## Possible MVP

status: needs-user-input

---

## Next validation step

- Research: which of AI Act, NIS2, VSME affects the most SMEs soonest?
- Research: find 3 SMEs that have received a compliance questionnaire and talk to them
- Research: what existing tools handle any part of this? (pricing, target segment)
- Decision: which single regulation to focus on first
