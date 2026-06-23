# Synthetic Test Data Platform

Status: `research`
Inspired by: `sources/synthetic-relational-data/` (tsembp/one-stop-ride-hail)

## Summary

Generates consistent, privacy-safe, schema-aware relational datasets for testing, development, and demo purposes. Entities are internally consistent (a customer on order 1 is the same person on order 2).

## Problem

Real data cannot be used in dev/test for privacy reasons. Naive random data breaks because:
- Customer IDs on orders don't match any customer record
- Dates are inconsistent (order before customer creation)
- Business logic invariants are violated (total != sum of line items)

## Core capabilities

1. Schema inference from existing database or migration files
2. Consistent entity generation (seeded, reproducible)
3. Relationship-aware generation (foreign keys always valid)
4. Business logic constraints (configurable)
5. Volume control (generate N customers, M orders per customer)
6. Format output: SQL INSERT, CSV, JSON, Parquet
7. Snapshot management (named seeds for reproducible test suites)

## Inspired by

The tsembp/one-stop-ride-hail project includes synthetic data generation for a ride-hail scenario. We generalize the pattern to any schema.

See `reference-implementations/synthetic-relational-data-generation/` for the reference build.

---

## Related sources

- `sources/synthetic-relational-data/` (tsembp/one-stop-ride-hail, MIT) — upstream data generation patterns
- `reference-implementations/synthetic-relational-data-generation/` — in-lab reference build

## Research connections

- `business-research/category-a/synthetic-regulatory-document-ai.md` — a compliance-specific
  vertical of this same platform concept

## Origin

Sourced from tsembp/one-stop-ride-hail, which includes synthetic ride-hail data generation
as a component. Product concept generalizes the relational consistency pattern to arbitrary schemas.

## Current evidence level

`initial-research` — upstream source studied, reference implementation planned.
No user interviews conducted.

## Open assumptions

- Development teams using real data in dev/test environments have a real compliance or privacy
  problem they want to solve with a tool (vs. just using simplified fake data)
- Schema inference from existing migrations is reliable enough to be the primary input method
- Relationship-aware generation is the key differentiator over existing tools (Faker, Mockaroo)

## Competitor landscape

Source: competitor websites verified 2026-06-23. Full analysis in `research/domain-synthesis/data-and-learning.md`.

| Competitor | Approach | Pricing | Gap |
|---|---|---|---|
| Faker (Python/JS) | Random fake data per field | Free, open-source | No relational consistency; no schema inference |
| Mimesis (Python) | Type-aware fake data | Free, open-source | No relational consistency |
| Mockaroo | Web UI for fake data generation | Free (limited) / $50/month | Limited relational consistency; no schema inference from migrations |
| Tonic.ai | Production data anonymization + synthetic data | Enterprise; contact sales | Enterprise pricing (>$10k); not accessible to SME dev teams |
| Mostly AI | Statistically faithful synthetic data (ML-based) | Free trial / enterprise | ML-based; overkill for test data; expensive |
| Snaplet (now Supabase) | Postgres snapshot + fake data | Acquired; shifting scope | Postgres-only; integration with Supabase dependency |

**Gap confirmed:** Faker/Mimesis are too simple (no relational consistency). Tonic.ai/Mostly AI are enterprise-priced. Mid-market gap: a tool that does relationship-aware generation with schema inference at $50–$200/month for a dev team. No verified product fills this.

evidence_level: initial-research (competitor landscape partially verified; no user interviews conducted)

## Next validation step

1. Talk to 3–5 backend developers: how do they handle test data today? What breaks?
2. Check: what does Faker/Mimesis/Mockaroo offer for relational consistency? Where do they fall short?
3. Determine whether the compliance angle (privacy-safe test data) or the correctness angle
   (internally consistent data) is the stronger hook
