# White Space: Training Data Bill of Materials (DBOM) SaaS

status: research
research_date: 2026-06-23
evidence_level: initial-research
urgency: HIGH — EU AI Act enforcement August 2, 2026 + ongoing litigation exposure

---

## Problem

Two simultaneous forcing functions are driving demand for **machine-readable training data provenance**:

**1. Litigation exposure:**
- **Bartz v. Anthropic** settled for **$1.5 billion** — the largest AI copyright settlement in US history (June 2026). Source: complexdiscovery.com
- Ongoing cases: Getty Images v. Stability AI, NYT v. OpenAI, multiple music copyright suits
- Every ML team fine-tuning or training on scraped data faces potential liability
- Without a structured provenance record, a company cannot defend its data sourcing choices or demonstrate opt-out compliance

**2. EU AI Act enforcement:**
- High-risk AI system providers must document training data provenance per Annex IV §2 (characteristics of training, validation, and testing data sets) — enforceable August 2, 2026
- GPAI model providers must produce a mandatory Training Data Disclosure Template (EU Commission published the template, WilmerHale confirmed, June 2026)
- Failure to document data provenance is evidence of non-compliance, which under the revised PLD is evidence of defectiveness

**The gap**: Companies fine-tuning or training models rely on spreadsheets and manual legal review to track data sources. No tool generates a structured "data bill of materials" (DBOM) that maps each training record to its license, acquisition method, copyright status, and opt-out compliance.

Source: complexdiscovery.com ("The $1.5B Reckoning"), scalevise.com (EU AI Act 2026 training data rules), WilmerHale (EU Commission mandatory training data template), verified 2026-06-23.

---

## The gap in existing tools

| Tool | Gap |
|---|---|
| Data catalog tools (Atlan, Alation) | Track operational/production data; not trained data rights, copyright status, or opt-out compliance |
| Data version control (DVC, LakeFS) | Track dataset versions; no licensing or rights metadata layer |
| Hugging Face dataset cards | Manual self-reported; no machine-verifiable provenance; no license compliance checking |
| SPDX (Software Bill of Materials standard) | Covers software dependencies; no equivalent standard for training data |
| Legal review (internal teams + law firms) | Expensive, slow, unscalable, not machine-readable |

**No product generates a structured, machine-readable DBOM** that maps training data to license, acquisition method, copyright status, robots.txt compliance, and opt-out/withdrawal requests.

---

## Target customer

**Primary**: ML platform team / legal / compliance at companies that:
- Fine-tune foundation models (LLaMA, Mistral, etc.) on proprietary or collected data
- Train models on web-scraped or licensed datasets
- Must demonstrate EU AI Act data provenance compliance
- Have significant litigation exposure from data sourcing

**Segments by size**:
- **Enterprise AI teams** (Google, Microsoft, Adobe, enterprise software vendors) — they have compliance budgets but build internally; hard to sell to
- **Mid-tier AI companies** (100–2,000 employees doing model training) — clear compliance need, no internal tooling team for this problem
- **AI-native startups** (fine-tuning on collected data, aware of litigation risk, no dedicated legal ops)

**Buyer**: Head of ML Platform, Legal Counsel, or VP Engineering at companies training or fine-tuning models.

---

## Proposed product

A **Training Data Bill of Materials** platform:

1. **Dataset registry**: Structured inventory of all training datasets with metadata: source URL/API, acquisition date, license type (CC-BY, MIT, Apache, proprietary, scrape), acquisition method (licensed, API, scrape, purchased, synthetic), and data controller information
2. **License compliance checker**: Automated scan of dataset licenses against intended use (commercial training, fine-tuning, distillation) — flags restrictions (non-commercial only, no derivative works, ShareAlike)
3. **Robots.txt audit**: For web-scraped data, verifies that robots.txt was respected at the time of crawl (using Wayback Machine or crawl logs)
4. **Opt-out tracking**: Records any copyright owner opt-out or withdrawal request and links to which training runs used the relevant data
5. **DBOM generator**: Produces a structured machine-readable DBOM (modeled on SPDX or CycloneDX format) covering each dataset component with full provenance metadata
6. **EU AI Act Annex IV export**: Generates the EU Commission's mandatory Training Data Disclosure Template from the DBOM automatically
7. **Litigation evidence package**: If a copyright claim is filed, generates a timestamped evidence record showing what data was used in which training run and under what license

---

## Why now

- **$1.5B Bartz settlement** (June 2026) is the market-changing event — every ML team has now been told by legal to audit their training data
- **EU AI Act enforcement starts August 2, 2026** — high-risk AI providers must have this documentation in place; Annex IV §2 is not optional
- **EU Commission published mandatory Training Data Disclosure Template** (2026) — the schema exists; companies need tooling to populate it
- **No tool exists** to produce a machine-readable DBOM — all solutions are manual today
- **Model training is accelerating**: more companies fine-tuning = larger addressable market over time

---

## Competitor landscape (verified 2026-06-23)

| Tool | Approach | Gap |
|---|---|---|
| Atlan / Alation | Data catalog for operational data | No rights/license tracking; not designed for training data |
| DVC / LakeFS | Dataset versioning | No provenance or license layer |
| Hugging Face | Dataset cards (manual) | Self-reported; not machine-verifiable; no license compliance checking |
| Common Crawl tooling | Dataset filtering | No rights/license mapping; provenance only for CC data |
| Internal legal teams | Manual review | Expensive, slow, no machine-readable output |

**Gap confirmed: no DBOM SaaS product exists.**

---

## Founder fit

- CS background: dataset registry + license checking is a data engineering problem; DBOM generation is a structured data transformation problem
- EU-based: firsthand context on EU AI Act Annex IV requirements; proximity to buyers facing enforcement
- B2B; ML platform buyer; pure software
- EU regulatory advantage: EU AI Act creates a compliance mandate that US-based tool builders may underestimate

---

## Revenue model

- **SaaS**: $1,000–$5,000/month per company (annual contracts)
- **Enterprise**: $20,000–$80,000/year for companies with large dataset inventories and litigation exposure
- **One-time DBOM audit**: $5,000–$20,000 one-time fee for companies needing a retrospective audit (high-urgency, quick revenue)

---

## Technical approach

1. Build a structured dataset registry (PostgreSQL-backed, API-first)
2. License classification layer: integrate SPDX license list; add ML-specific license variations (RAIL, RAIL-M, AI2-ImpACT)
3. Robots.txt verification: Wayback Machine API integration for historical crawl policy verification
4. DBOM output format: adapt CycloneDX or SPDX format for training data (there is no published standard yet — first-mover advantage in defining the de facto format)
5. EU AI Act Annex IV mapping: structured export of the mandatory Training Data Disclosure Template

---

## Risks

- An open standard for training data provenance (DBOM equivalent of SPDX) may be defined by a standards body — making tooling commoditized
- Hugging Face may add provenance and license compliance checking as a native platform feature
- Litigation risk may decrease if major copyright cases are settled out of court (reducing urgency)
- Technical complexity: retrospective provenance for data collected over years is hard to reconstruct

---

## Next validation step

1. Find an ML platform engineer at a company that fine-tunes models — ask: "How do you currently track which datasets were used in which training runs, under what license?"
2. Read the official EU Commission mandatory Training Data Disclosure Template — identify the exact fields that must be populated
3. Check if SPDX or CycloneDX working groups have a training data subgroup — if so, contributing to the standard definition creates distribution

---

## Related ideas

- `gpai-documentation-form-automation.md` — overlapping buyer; the GPAI Transparency Chapter includes training data disclosure requirements that DBOM would address
- `evidenceops-ai-act-nis2-vsme.md` — broader EU AI Act compliance platform; DBOM could be a module
- `pld-liability-evidence-vault.md` — PLD defense uses DBOM as its training data component

---

## Evidence quality notes

- $1.5B Bartz v. Anthropic settlement confirmed from complexdiscovery.com (June 2026)
- EU AI Act Annex IV §2 training data documentation requirement confirmed from EUR-Lex text
- EU Commission mandatory Training Data Disclosure Template confirmed from WilmerHale analysis (2026)
- No DBOM SaaS competitor found after systematic search (ProductHunt, G2, GitHub, data catalog vendor sites)
- [Hypothesis] Revenue model and TAM are directional estimates
