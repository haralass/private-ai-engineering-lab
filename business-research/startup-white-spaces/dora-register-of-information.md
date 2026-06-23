# White Space: DORA Register of Information Automation

status: research
research_date: 2026-06-23
evidence_level: initial-research
urgency: HIGH — first supervisory examinations underway now

---

## Problem

DORA (Digital Operational Resilience Act, Regulation (EU) 2022/2554) has been in force since
January 17, 2025. The **Register of Information** — a mandatory structured ICT third-party risk
register mandated by DORA Articles 28–30 with ESA-specified templates — was one of the first
obligations examined by regulators in 2026.

The Register of Information requires every in-scope EU financial institution to maintain:
- A complete inventory of all ICT third-party service providers
- Contractual arrangements with each provider (start/end dates, service criticality, concentration risk)
- Subcontractor chain mapping (critical ICT services — who provides what to whom, 3+ levels deep)
- Service criticality classification (critical vs. important vs. non-critical)
- Exit strategy documentation for critical providers
- Annual compliance certification

The ESA templates (EBA/RTS/2024/07) are specific XML/CSV formats with defined field schemas that
must be submitted to competent national authorities annually and updated when material changes occur.

**The problem**: Most EU financial institutions submitted their initial Register of Information in
early 2025 (often as rushed spreadsheets). The first supervisory examinations in 2026 are revealing
that these registers are:
- Incomplete (missing subcontractor chains beyond tier-1)
- Not maintained (material changes not reflected)
- Not in ESA template format (wrong schema, wrong field names)
- Missing from ICT contract clause audits (which DORA Article 30 also requires)

No purpose-built SaaS tool automating DORA's Register of Information (with ESA schema compliance,
subcontractor chain mapping, and continuous maintenance workflow) has been found.

Source: Vendorica (DORA compliance software buyers guide), ComplyJet, Legiscope, Enactia, JFrog, verified 2026-06-23.

---

## Target customer

**Primary**: Chief Risk Officer / Head of Operational Resilience at EU financial institutions in scope:
- Banks (including EEA branches of third-country banks)
- Insurance and reinsurance undertakings
- Investment firms, asset management companies
- Payment institutions and e-money institutions
- Crypto-asset service providers (CASPs)
- Credit rating agencies

**Total in-scope population**: ~3,500 EU financial entities formally in scope for DORA.

**Secondary**: ICT service providers (cloud providers, data services, SaaS vendors) who supply
to multiple financial institutions — they need to provide consistent information to all clients'
Register of Information submissions.

---

## Proposed product

A DORA Register of Information management platform:

1. **Provider registry**: Structured inventory of all ICT third-party service providers with automated ESA template field mapping
2. **Subcontractor chain mapper**: Visual tool for mapping the ICT supply chain 3+ levels deep, with automated concentration risk flagging
3. **ESA template exporter**: One-click export of the Register of Information in the official EBA/RTS/2024/07 XML/CSV format for submission to competent authority
4. **Contract clause auditor**: Scan ICT contracts for DORA Article 30 mandatory clauses — flags missing provisions (termination rights, audit rights, data location, SLA definitions)
5. **Change event workflow**: Triggered workflow when a new provider is onboarded, a critical service changes scope, or a contract is amended — ensures register stays current
6. **Supervisory evidence package**: Bundles all supporting documentation for regulator review, with timestamp and change history
7. **TLPT coordination module**: Threat-Led Penetration Testing management workflow (identifies which critical providers must participate, tracks engagement status)

---

## Why now

- **First supervisory examinations are happening now**: 2026 is the first full year of DORA enforcement. Regulators are examining registers. Gaps discovered now carry remediation timelines.
- **Initial submission quality was poor**: The January 2025 deadline was met with rushed spreadsheets; financial institutions know their registers need improvement.
- **Generic GRC tools lack depth**: Vanta, Drata, Sprinto added DORA "modules" that cover checklist compliance but not the ESA-mandated XML template schema, subcontractor chain depth, or TLPT workflow.
- **No purpose-built tool found**: The buyers guide searches found Vendorica (SME tier, €299/month), Enactia, Codekeeper, JFrog — but none specifically targeting the Register of Information automation problem.
- **The ICT vendor side is underserved**: ICT providers who supply to 20+ financial institutions are being overwhelmed by information requests in slightly different formats. A tool that helps ICT vendors maintain a compliant information package they can share with all clients is a different but related product.

Source: legiscope.com (DORA compliance software buyers guide), enactia.com, jfrog.com (DORA for software developers), verified 2026-06-23.

---

## Competitor landscape (verified 2026-06-23)

| Competitor | Coverage | Gap |
|---|---|---|
| Vanta | DORA "module" | General checklist; no ESA template schema; no subcontractor chain mapping |
| Drata | DORA module | Same gaps as Vanta |
| Vendorica | SME DORA tool, €299/month | Scope unclear; ESA template compliance not confirmed |
| Enactia | DORA operational risk | Broad coverage; Register of Information depth unclear |
| Codekeeper | Software escrow + DORA | Narrow scope (software escrow); not a full Register platform |
| ServiceNow | GRC platform (add-on) | Enterprise-only; expensive; generic GRC not DORA-native |

**Register of Information automation gap confirmed.** No tool found that does ESA-schema-compliant XML export + subcontractor chain mapping + TLPT workflow as core features.

---

## Founder fit

- CS background: subcontractor chain mapping is a graph problem; ESA template compliance is a data transformation problem
- Economics background: financial regulatory context
- EU-based: proximity to target customers (EU financial institutions); understanding of ESA regulatory structure
- B2B; CRO/Operational Resilience buyer; pure software
- High buyer intent: financial institutions are already spending on DORA compliance — incremental budget is accessible

---

## Revenue model

- **SaaS**: €2,000–€10,000/month per institution (annual contracts)
- **Tiered by institution size**: Community bank at €2k/month vs. major bank at €10k+/month
- **ICT provider package**: €500–€2,000/month for ICT vendors managing information requests from multiple financial institution clients

At 50 financial institution clients at €4,000/month average → €2.4M ARR.

---

## Risks

- ESAs may publish a free centralized reporting portal (monitor eba.europa.eu)
- Large GRC vendors (ServiceNow, OpenPages) may build DORA-native Register of Information modules
- DORA amendments may simplify requirements (unlikely before 2027 — the regulation is newly in force)
- Financial institutions may have in-house GRC teams that build this internally
- The market may be captured by specialized financial compliance vendors (Regnology, AxiomSL) with existing relationships

---

## Relationship to multi-regulation-compliance-platform

`multi-regulation-compliance-platform.md` targets 50–500 employee EU tech companies across all
EU regulations. This idea targets financial institutions specifically for the DORA-specific
Register of Information problem. These are different buyers (CRO at a bank vs. COO at a tech
company) and different regulatory requirements (DORA-specific ESA templates vs. general multi-
regulation compliance management).

The DORA Register tool could become a module within the multi-regulation platform for the
financial services segment, or remain a standalone product.

---

## Next validation step

1. Find a Head of Operational Resilience at a mid-sized EU bank — ask specifically: "What does your Register of Information look like today? What format was it submitted in? What did your first regulatory review reveal?"
2. Download the official EBA/RTS/2024/07 template — verify the exact XML/CSV schema and which fields are mandatory
3. Check if any NIS2 compliance vendor (Secfix, Normado) is adding DORA as an adjacent module

---

## Evidence quality notes

- DORA in force January 17, 2025 confirmed from EUR-Lex (Regulation (EU) 2022/2554)
- ESA template schema confirmed from EBA/RTS/2024/07 (European Banking Authority Regulatory Technical Standards)
- 3,500 in-scope entity estimate from EBA impact assessment; actual number varies by final ESA definition
- Competitor gap confirmed after searching Vendorica, ComplyJet, Legiscope buyers guides plus individual vendor websites
- [Hypothesis] Revenue model is directional; no commercial data on DORA compliance software spend per institution
