# VSME ESG Data Room

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

VSME ESG Data Room — Structured data collection and reporting tool for SME ESG disclosure

---

## Problem

The VSME (Voluntary SME) standard under ESRS (European Sustainability Reporting Standards)
provides a simplified ESG reporting framework for small and medium enterprises.
SMEs are increasingly asked to report ESG data by: banks (as part of green loan eligibility),
large enterprise customers (supply chain due diligence), and investors.

The problem: SMEs have no structured place to collect, organize, and export ESG data.
They currently do this via spreadsheets and ad-hoc document requests.

---

## Target users

- status: needs-user-input (which SME types? which country/region? which trigger event?)
- Likely: SMEs that have received an ESG questionnaire from a bank or enterprise customer
- Possibly: SME consultants managing ESG reporting for multiple clients

---

## Proposed solution

status: needs-user-input

Possible direction: A structured data room specifically for VSME-aligned ESG data.
SME fills in a guided questionnaire. Output: downloadable report in formats accepted
by the requesting party (PDF, structured CSV, or API export).

Differentiation from full ESG platforms: focus exclusively on VSME scope, not
full ESRS or GRI. Simpler, cheaper, accessible without a sustainability consultant.

---

## Why now

Source: EFRAG, European Commission, verified 2026-06-23. Full citations in `research/domain-synthesis/regulatory-landscape.md`.

**VSME status (verified):**
- EFRAG delivered the final VSME Standard to the European Commission: **17 December 2024**
- Commission adopted as a Recommendation (C(2025) 4984): **30 July 2025** — currently **voluntary**
- EFRAG released XBRL Taxonomy and digital tools: **27 May 2025**
- Expected to become the cap on information requests to SMEs from CSRD-subject companies (proposed Omnibus I package — not yet enacted as of 2026-06-23)

**Who is asking (verified):**
1. Banks — green loan eligibility and EU Taxonomy alignment under the European Green Deal lending framework
2. Large enterprise customers — CSRD supply chain due diligence obligations require ESG data from SME suppliers
3. Investors — SFDR (Sustainable Finance Disclosure Regulation) obligations cascade to portfolio companies

**VSME structure:**
- Basic Module: 11 disclosures (energy, GHG, waste, water, workforce, health & safety, corruption)
- Comprehensive Module: 9 additional disclosures (climate targets, transition planning, human rights, gender diversity)

**Digital tools currently available:**
- EFRAG Excel Template (free)
- ExecutESG: free Basic tier (VSME reporting online, no automation)
- DNK Platform (German-language only, free)
- 23+ VSME tools in market as of 2025 (source: csr-tools.com)

**Why a gap still exists:** The demand trigger is supply chain cascading (bank/enterprise customer sends a questionnaire), not proactive compliance. Tools exist for the reporting part; the gap may be in the workflow integration — i.e., receiving a questionnaire and knowing which VSME disclosures it maps to.

---

## Why it fits the founder

- CS background: can build the product
- Economics background: understands ESG reporting context, SME financial reporting
- B2B, compliance-driven, no hardware
- Clear trigger event: customer or bank sends an ESG questionnaire

---

## Relevant sources in the lab

None. No ESG tooling sources have been imported.

---

## Existing product concepts

None. This is a raw idea.

---

## Relationship to other Category A ideas

- `evidenceops-ai-act-nis2-vsme.md` — overlapping compliance context; VSME is one
  of the three regulatory frameworks named in that idea
- These two ideas may be variants of the same product or separate products;
  decision: status: needs-user-input

---

## Assumptions

- Banks and enterprise customers are actually requiring VSME-compatible data from SMEs
- The gap is tooling, not awareness or willingness
- VSME standard is stable enough to build a product on

---

## Unknowns

- Adoption rate among banks and large enterprises for VSME-format requests (are they using VSME scope or their own custom questionnaires?)
- Whether existing accounting/ERP software (Sage, QuickBooks, etc.) will add VSME modules
- Pricing sensitivity of SMEs for a VSME tool vs. free existing options
- Market size: status: needs-user-input (do not add numbers without source)
- Whether the Omnibus I simplification changes will make VSME mandatory (pending)

---

## Evidence

evidence_level: initial-research

Updated 2026-06-23. VSME standard status verified from EFRAG and European Commission primary sources.
Key finding: VSME is voluntary (as of 2026-06-23) but formally adopted as a Commission Recommendation. Market has 23+ tools, including free tiers. The differentiation gap is integration with banking/customer onboarding workflows rather than standalone reporting.

## Competitor landscape

| Vendor | Approach | Pricing | Notes |
|---|---|---|---|
| ExecutESG | VSME-native SaaS | Free Basic; CSRD Pro from €149/year | Direct VSME competitor |
| Vision Zero Connect | AI-powered VSME | €700/year | Complete VSME solution |
| ESG Lift | SME ESG SaaS | From €792/year | VSME-focused |
| EFRAG Excel Template | Manual template | Free | No automation |
| Consultancies | Done-for-you | €3k–€15k/report | High cost |

Source: executesg.com, csr-tools.com, verified 2026-06-23.

**Key competitive observation:** Free tiers already exist. Competing on price alone is not viable. Differentiation would need to come from workflow integration (embedding into bank onboarding flows, responding to customer questionnaires), not standalone reporting.

---

## Risks

- VSME adoption may be slower than expected
- Large accounting software vendors (Sage, QuickBooks, etc.) may add ESG modules
- Regulatory scope may shift

---

## Possible MVP

status: needs-user-input

---

## Next validation step

- Verify current status of VSME standard adoption
- Find 2–3 SMEs that have received ESG questionnaires and ask what they did
- Check what existing tools handle VSME or simplified ESG (pricing, scope)
- Decide: is this separate from evidenceops or the same product?
