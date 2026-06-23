# White Space: GPAI Model Documentation Form Automation

status: research
research_date: 2026-06-23
evidence_level: initial-research
urgency: IMMINENT — 40 days to August 2, 2026

---

## Problem

The EU AI Act GPAI (General Purpose AI) Code of Practice entered enforcement August 2, 2026 —
40 days from today. All GPAI model providers who signed the Code of Practice (26 confirmed
signatories as of August 2025 — Amazon, Anthropic, Google, IBM, Microsoft, OpenAI, Aleph Alpha,
Cohere, Mistral, etc.) must now produce and maintain structured compliance documentation.

The EU AI Office published a mandatory **Model Documentation Form** with **42 metadata attributes**
across 8 categories:
1. Training compute (time, FLOP, energy consumption)
2. Model size and architecture
3. Data collection and curation methods
4. Input/output modalities
5. Use cases and limitations
6. Downstream provider disclosures
7. Copyright policy documentation
8. (Systemic risk models only) Safety & Security Model Report

The form must be:
- Maintained up-to-date with every model release
- Retained in versioned history for **10 years**
- Accessible for EU AI Office inspection on demand
- Accompanied by a separate mandatory Training Data Disclosure Template

**No commercial tool exists** that populates this form automatically from model metadata,
training run logs, or Hugging Face cards — and versions it over time.

---

## The gap in existing tools

| Tool | Gap |
|---|---|
| Legalithm (EU, free until 2028) | Targets deployers of AI systems, not GPAI providers; focuses on risk classification + Annex IV, not the GPAI 42-attribute form |
| eyreACT | EU AI Act risk classification; no GPAI-specific Model Documentation Form integration |
| OneTrust, Holistic AI, Credo AI | Broad AI governance; GPAI-specific form not implemented; €30k–€100k+/year |
| Static Word/PDF templates | Available from Witness Compliance, AuditDraft, arXiv TechOps — not pipeline-integrated, not versioned, not auto-populated |

**The specific gap**: no product ingests model metadata (training run logs, Hugging Face cards, internal registries, compute bills) and produces a versioned, compliant 42-attribute GPAI Transparency Chapter form. Every signatory is currently doing this manually.

Source: code-of-practice.ai, streamlex.eu (GPAI Transparency Chapter Model Documentation Form schema), EU AI Office official template, verified 2026-06-23.

---

## Target customer

**Primary (26 confirmed signatories):**
- GPAI model providers who signed the Code of Practice: Amazon, Anthropic, Google, IBM, Microsoft, OpenAI, Aleph Alpha, Cohere, Mistral AI, and 17 others
- These companies have compliance teams and compliance budgets; they are already being asked to produce documentation

**Secondary (non-signatories seeking conformity presumption):**
- AI companies releasing foundation models or fine-tuned models on a commercial basis who want the "presumption of conformity" safe harbor without signing formally
- Estimated [hypothesis]: 100–300 companies globally operating at a scale where GPAI thresholds apply

**Buyer**: Head of AI Policy / Director of Legal & Compliance / VP Engineering at GPAI providers.

---

## Proposed product

A documentation management platform specifically for GPAI Code of Practice compliance:

1. **Model registry connector**: Ingests metadata from Hugging Face Hub (model card fields), MLflow, W&B, or internal model registries — auto-populates the 42-attribute form where data exists
2. **Training compute integration**: Connects to cloud billing APIs (AWS, GCP, Azure) to extract FLOP estimates, GPU-hours, energy consumption per training run
3. **Copyright chapter workflow**: Guided workflow for documenting robots.txt compliance, copyright policy, and infringing-content safeguard procedures
4. **Version history**: Every form submission timestamped and stored for 10-year retention; diff view between versions
5. **Training data disclosure template**: Separate mandatory EU Commission template auto-generated from data lineage metadata
6. **Systemic risk module**: For models above the 10^25 FLOP threshold — Safety & Security Model Report structured form (applicable to ~5–15 companies globally but these are the largest buyers)
7. **Audit package export**: One-click export of all documentation as a compliance evidence package for EU AI Office inspection

---

## Why now

- **40 days to enforcement**: August 2, 2026 is the hard deadline. Companies are in sprint mode.
- **Schema is defined and stable**: The EU AI Office published the official 42-attribute form; no regulatory ambiguity about what must be produced.
- **26 confirmed signatories need this immediately**: Every one of them has to produce and maintain these forms manually today.
- **10-year retention requirement**: Creates long-term recurring need (not a one-time document), ensuring low churn once adopted.
- **The second-tier GPAI wave**: As mid-tier AI companies grow into GPAI thresholds, the addressable market expands continuously.

Source: EU AI Office official documentation, lw.com (Latham & Watkins GPAI analysis), Euronews (signatory list), WilmerHale (mandatory training data template), verified 2026-06-23.

---

## Competitor landscape (verified 2026-06-23)

No dedicated commercial product found for GPAI Model Documentation Form automation.

Adjacent tools with partial coverage:
- **Citadel AI** — model evaluation, GPAI Code of Practice analysis published, but not a documentation tool
- **eyreACT** — risk classification automation; scope is broader EU AI Act, not GPAI-specific form filling
- **Generic GRC platforms** (OneTrust, Drata) — no GPAI form integration found

**First-mover gap is clear.**

---

## Founder fit

- CS background: pipeline integration (model registries, cloud billing APIs → structured form) is a well-defined engineering problem
- Economics background: regulatory context; EU policy landscape
- EU-based: firsthand regulatory environment proximity; potential access to EU AI Office contacts
- B2B; compliance-driven; pure software
- Natural distribution through compliance consulting networks (law firms already advising GPAI signatories)

---

## Revenue model

- **Per-model SaaS**: €500–€2,000/month per GPAI model family tracked (annual contracts)
- **Enterprise**: €5,000–€20,000/month for large providers with many model variants and systemic risk model reporting

Even at modest penetration of the 26+ signatories, this can reach €1–3M ARR quickly. The market expands as second-tier providers reach GPAI thresholds.

---

## Risks

- EU AI Office may publish a free standardized reporting portal (monitor digital-strategy.ec.europa.eu)
- Large GPAI providers (Google, Microsoft, OpenAI) may build this internally — they have compliance engineering teams
- Regulatory timeline may shift (ongoing Omnibus simplification discussions)
- The 26-company addressable pool is small; need to capture secondary market (companies approaching GPAI thresholds) for meaningful ARR

---

## Next validation step

1. Contact 2–3 compliance officers at mid-tier GPAI providers (Aleph Alpha, Cohere, Mistral) — ask specifically: "How are you producing and maintaining the 42-attribute Transparency Chapter form today?"
2. Check the EU AI Office's current portal status — has a self-service submission portal launched yet?
3. Build a prototype that takes a Hugging Face model card and auto-populates the 42 fields — show it to 3 people at GPAI signatory companies

---

## Related ideas

- `evidenceops-ai-act-nis2-vsme.md` — same regulatory family but targets AI deployers; this targets AI providers
- `business-research/startup-white-spaces/training-data-bill-of-materials.md` — adjacent: training data provenance for copyright chapter compliance
- `business-research/startup-white-spaces/multi-regulation-compliance-platform.md` — could absorb this as a GPAI module

---

## Evidence quality notes

- 26 signatories confirmed from primary sources (Euronews, DataGuidance, EU AI Office)
- 42-attribute form schema confirmed from streamlex.eu and EU AI Office documentation
- August 2, 2026 enforcement date confirmed from EU AI Act text and Latham & Watkins analysis
- [Hypothesis] Market size estimates are directional; no commercial dataset on GPAI provider compliance spend
