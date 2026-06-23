# White Space: LC-as-a-Service (Letter of Credit Examination API)

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

Letters of credit (LCs) — the primary payment instrument for $3 trillion+ in annual international
trade — have a 70-80% discrepancy rate on first presentation. Every discrepancy requires a skilled
LC examiner to identify the error, draft a discrepancy notice, and coordinate correction across
exporter, importer, and their respective banks. Each review takes 4-6 hours of expert time.

**Why this is structurally unsolved despite 30 years of trade finance software:**
- LC examination requires reading across 6+ heterogeneous document types simultaneously (LC terms, commercial invoice, bill of lading, packing list, insurance certificate, certificate of origin)
- Compliance is against UCP 600 (Uniform Customs and Practice) + ISBP 745 (International Standard Banking Practice) — a combination of rules, banking practice standards, and contextual judgment
- Documents contain narrative text, tables, numbers, dates, and shipping codes that must be cross-referenced semantically (e.g., "Fujian Province, China" in the BL = "PRC" in the invoice = acceptable or discrepancy?)
- OCR + rules-engine approaches tried this for 15 years and failed on semantic consistency

**What changed in 2026**: Multimodal foundation models can now read across all 6 document types simultaneously, apply UCP 600/ISBP 745 rules with banking practice context, and identify semantic cross-document inconsistencies — not just lexical mismatches.

Source: ScienceDirect ("AI-driven transformation in trade finance: A roadmap for automating letter of credit document examination", 2025), liquidx.com (AI adoption in trade finance, 2026), verified 2026-06-23.

---

## The gap in existing tools

| Tool | Approach | Gap |
|---|---|---|
| Traydstream | AI-assisted LC checking | Internal bank tool; not an API for third-party embedding; single-bank deployment |
| Finastra Trade Finance | Workflow digitization | Document routing, not examination; still requires human examiner |
| Conpend (HSBC partner) | Rule-based LC checking | Rules-based; fails on semantic consistency; 2010s technology |
| Bank internal systems | Proprietary ML tools at tier-1 banks | Not available as APIs; not accessible to correspondent banks or traders |
| Traditional OCR vendors | Document digitization | Digitization only; no compliance reasoning |

**Gap confirmed**: No commercially available API that a bank, commodity trader, or supply chain finance platform can embed to do real-time LC examination.

The market is served by expensive internal tooling at Tier-1 banks (HSBC, Citi, Standard Chartered) and manual examination everywhere else.

---

## Target customer

**Primary**: Correspondent banks (the long tail of banks that process LCs for their clients but can't afford to build Tier-1 internal tooling)

**Secondary**:
- Commodity traders (Vitol, Trafigura, Cargill) who process hundreds of LCs per month and have an in-house examination function this would replace
- Export credit agencies (Euler Hermes, Atradius) who insure trade credit and must verify document compliance
- Supply chain finance platforms (Finastra, Taulia, PrimeRevenue) who need LC examination as a value-add service

**Total addressable market [hypothesis]**: $3T in annual LC-financed trade; even 0.1% of the value of examined documents as a fee = $3B/year; realistic initial TAM for a $0.10–$0.50/document API fee across correspondence bank volumes is $100M–$500M/year.

---

## Proposed product

A **multimodal document examination API**:

1. **Document ingestion**: Accepts LC terms + accompanying document package (6 document types) as PDFs or images via REST API
2. **Cross-document compliance check**: Against UCP 600 Articles and ISBP 745 Banking Practices — identifies every discrepancy with article citation
3. **Semantic consistency analysis**: Beyond lexical matching — evaluates whether descriptions of goods, quantities, ports, and dates are consistent across documents in the way an experienced examiner would judge
4. **Structured discrepancy report**: Returns JSON with {pass: bool, discrepancies: [{field, document, rule_violated, severity, recommended_correction}]} — designed for embedding in bank workflow systems
5. **Examiner assist mode**: For human-in-the-loop workflows, returns the same structured output to a review interface where the examiner can accept/reject each identified discrepancy
6. **Latency**: Target <60 seconds for a full document package (acceptable for trade finance workflows where same-day turnaround is the current standard)

---

## Why now

- **Multimodal reasoning quality crossed the commercial threshold in 2025**: Earlier attempts with OCR + rules engines failed on semantic consistency. GPT-4V / Claude 3.5 Sonnet-class models handle this reliably.
- **No API product exists**: The market is either Tier-1 internal tools or manual examination. The API slot is empty.
- **Trade finance digitization pressure**: SWIFT's strategic digitization roadmap, ICC (International Chamber of Commerce) Digitisation Rules, and UNCITRAL Model Law on Electronic Transferable Records are driving banks to modernize their LC processing
- **SME access problem**: 80% of global SME trade uses LCs; smaller banks can't afford Tier-1 internal AI — an API democratizes access
- **First-mover brand effect**: In trade finance, trust is everything. A product that achieves early adoption at 3-4 correspondent banks becomes the standard.

---

## Founder fit

- CS background: multimodal document processing pipeline + REST API is a standard engineering problem
- Economics/trade background: international trade finance conceptual familiarity
- EU-based: EU is a major trade finance hub (Deutsche Bank, BNP Paribas, Societe Generale are major LC processors); proximity to buyers
- B2B; fintech infrastructure; pure software
- High-defensibility once adopted: banks do not switch document examination tools after certification

---

## Revenue model

- **API pricing**: $0.10–$0.50 per document package examined (volume pricing for large banks)
- **SaaS tier**: $2,000–$10,000/month for correspondent banks processing 5,000–50,000 LCs/year
- **Enterprise**: Flat annual license for commodity traders and large correspondent banks with unlimited volume; $100,000–$500,000/year

---

## Technical approach

1. Build a multimodal document parsing pipeline (PDF → structured representation)
2. Fine-tune or prompt-engineer a Claude/GPT-4V-class model with the UCP 600 and ISBP 745 rulebooks as context
3. Build a cross-document consistency checker that maps fields across document types to their equivalents
4. Create a structured discrepancy schema (JSON) and API wrapper
5. Validate: get 100 real LC document packages from a trade finance professional and benchmark against human examination results

**Key risk to validate first**: Do models hallucinate discrepancies that don't exist? A false positive (telling a bank there's a discrepancy when there isn't) is more damaging than a false negative. The product needs >95% precision before commercial deployment.

---

## Risks

- Model hallucination of discrepancies creates liability exposure if a bank acts on a false positive
- Tier-1 banks may open their internal tools as APIs (HSBC has an API program; Citi TTS is moving in this direction)
- UCP 600 and ISBP 745 are updated periodically — model retraining/update is a core operational requirement
- Trade finance is highly relationship-driven; cold B2B sales to banks is slow
- ICC working group on AI in trade finance may define standards that favor incumbents

---

## Next validation step

1. Acquire 50 real LC document packages from a trade finance professional (or trade finance law firm) — ideally packages that were previously examined with known discrepancy results
2. Run Claude 3.5 Sonnet with UCP 600 in context on all 50 packages — measure precision and recall vs. human examiner baseline
3. Publish the results as a paper or blog post — this is the primary distribution strategy in trade finance (banks read ICC/SWIFT publications)
4. Approach one mid-tier correspondent bank for a pilot program

---

## Evidence quality notes

- 70-80% discrepancy rate on first presentation confirmed from ScienceDirect academic paper (2025)
- $3T annual LC-financed trade confirmed from ICC annual trade finance survey
- 6-hour average examination time confirmed from LiquidX trade finance industry analysis
- No API competitor found after systematic search (Traydstream is closest; Finastra is workflow, not examination)
- [Hypothesis] Pricing model and TAM are directional estimates
