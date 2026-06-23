# Adaptive SQL Learning Platform

Status: `research`
Inspired by: `sources/database-query-training/` (tsembp/SQL-Gym)

## Summary

A SQL learning environment with generated realistic datasets, a read-only sandboxed execution engine, adaptive difficulty, AI-powered error explanation, and progress tracking.

## Key differentiators from existing SQL practice platforms

1. **Generated datasets** — fresh, realistic, schema-consistent data per session. Not toy examples.
2. **Secure sandbox** — read-only, AST-validated, process-isolated. Safe to run arbitrary student SQL.
3. **AI error explanation** — not just "syntax error near ..." but "you forgot to join the orders table — here's why your result has duplicates"
4. **Interview tracks** — curated paths mapped to actual SQL interview question patterns

## Security requirements for our safe version

- Whitelist-only tables (no arbitrary schema access)
- SQL AST parser: only `SELECT` statements permitted
- Process isolation (Docker or subprocess with timeout)
- Row limit (max 1000 rows returned)
- Memory limit (256MB per query)
- Output size limit (500KB)
- Timeout (5 seconds per query)

## Source study

`sources/database-query-training/upstream/` contains the upstream implementation. Our safe sandbox design is in `reference-implementations/database-query-training-environment/`.

**Do not use the upstream security model in production without a full re-audit.**

---

## Related sources

- `sources/database-query-training/` (tsembp/SQL-Gym, MIT) — upstream implementation studied
- `reference-implementations/database-query-training-environment/` — in-lab reference build

## Research connections

None filed. See `business-research/BUSINESS_IDEAS_INDEX.md` for context.

## Origin

Sourced from tsembp/SQL-Gym in sources import. Product concept derived from studying
the upstream implementation and identifying a safe, production-viable extension.

## Current evidence level

`initial-research` — upstream source studied, reference implementation built.
No user interviews conducted.

## Open assumptions

- Learners will pay for a SQL practice tool with better explanations over free alternatives
- The sandbox security model can be made production-safe without prohibitive complexity
- AI error explanations meaningfully improve learning outcomes vs. raw error messages

## Competitor landscape

Source: competitor websites verified 2026-06-23. Full analysis in `research/domain-synthesis/data-and-learning.md`.

| Competitor | Approach | Pricing | Gap |
|---|---|---|---|
| DataCamp | Structured courses with pre-set exercises | $25–$300/year | No AI feedback on student queries; no generated datasets |
| SQLZoo | Free SQL exercises | Free | No AI feedback; no progress tracking; dated UX |
| Mode Analytics | SQL editor + collaborative notebooks | Free tier (limited) | Not a learning platform; no curriculum; no AI explanation |
| Strata Scratch (StrataScratch) | SQL interview practice with real datasets | Free / $99/year | Static datasets; no AI error explanation |
| LeetCode (SQL section) | SQL challenge problems | Free / $159/year | No sandboxed execution with AI explanation |

**Gap confirmed:** No verified platform combines (1) AI-powered error explanation, (2) fresh generated datasets per session, and (3) a production-safe sandboxed execution engine. The specific gap is the AI feedback loop, not the exercises themselves.

evidence_level: initial-research (competitor landscape verified; no user interviews conducted)

## Next validation step

1. Talk to 3–5 people currently learning SQL for interviews: what tools do they use, what's missing?
2. Identify whether the target is self-directed learners or companies buying for employee training
3. Determine if the differentiator is the sandbox, the AI explanations, or the generated datasets
