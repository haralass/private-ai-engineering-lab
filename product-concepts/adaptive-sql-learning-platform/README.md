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
