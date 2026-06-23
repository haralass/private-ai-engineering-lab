# Synthetic Regulatory Document AI

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

Synthetic Regulatory Document AI — Generate privacy-safe, schema-consistent synthetic
datasets and documents for compliance testing, system validation, and regulatory demos

---

## Problem

Organizations building compliance software (AML, KYC, ESG reporting, AI Act documentation)
need realistic test data that:
- Cannot use real personal or business data (GDPR, data protection)
- Must be schema-consistent and internally coherent (valid IDs, realistic dates, correct formats)
- Must cover edge cases and stress scenarios (synthetic but meaningful)

Current alternatives:
- Use real data in test (GDPR risk)
- Use trivially fake data (breaks tests, unusable for demos)
- Build custom data generators (expensive, one-off)

---

## Target users

- status: needs-user-input (who exactly?)
- Possible: compliance software vendors building AML/KYC/ESG tools that need test data
- Possible: regulators or auditors who need realistic synthetic examples for guidance docs
- Possible: companies preparing for regulatory audits who need to test their tooling
  without exposing real data

---

## Proposed solution

status: needs-user-input

Possible direction: A synthetic data generation tool specialized for compliance
document schemas. Input: a schema or document type (e.g., "VSME ESG report", "AI Act
risk assessment", "KYC file"). Output: realistic synthetic documents and datasets
that pass format validation and are internally consistent.

Built on the patterns studied in `sources/synthetic-relational-data/` (relational
consistency) and adapted for document-level synthesis.

---

## Why now

status: needs-user-input
- GDPR enforcement: document why real test data is not acceptable
- AI Act, NIS2, VSME: compliance software market growing
- Do not add specific market size claims without a source

---

## Why it fits the founder

- CS background: synthetic data generation is a technical problem
- Economics background: understands compliance document formats and regulatory context
- No hardware required
- B2B, clear technical buyer (software vendor, compliance team)

---

## Relevant sources in the lab

- `sources/synthetic-relational-data/` (tsembp/one-stop-ride-hail, reference-only)
  — core relational data synthesis patterns studied here
- `product-concepts/synthetic-test-data-platform/` — general synthetic data platform
  concept; this idea is a more focused vertical application of the same approach

---

## Existing product concepts

Closely related to: `product-concepts/synthetic-test-data-platform/`

**Relationship:** synthetic-test-data-platform is the general-purpose version.
This idea is a compliance-specific vertical — same generation engine,
different schema domain and target customer.

Decision needed: is this a product concept of its own, or a use-case within
synthetic-test-data-platform? status: needs-user-input

---

## Assumptions

- Compliance software vendors need test data
- The regulatory document schemas are well-defined enough to generate against
- Synthetic data quality needs to be high enough for demo and test (not production)

---

## Unknowns

- What specific document schemas would be most valuable first?
- Is there an existing synthetic data tool for compliance use cases?
- Who pays: the software vendor, the compliance team, or the regulator?
- Evidence level: none

---

## Evidence

evidence_level: none
source_path: sources/synthetic-relational-data/ (technical pattern only, not market evidence)

---

## Risks

- General synthetic data tools (Faker, Mimesis, Synthea) may already solve this
- Specialized compliance schemas may change as regulations evolve
- Legal liability if synthetic data is mistaken for real compliance evidence

---

## Possible MVP

status: needs-user-input

---

## Next validation step

- Talk to one compliance software vendor: do they generate their own test data or buy it?
- Check if any existing synthetic data product targets compliance document schemas
- Decide: vertical of synthetic-test-data-platform, or separate product?
