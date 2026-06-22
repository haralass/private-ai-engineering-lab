# Reference Implementation: Database Query Training Environment

Based on study of: `sources/database-query-training/` (tsembp/SQL-Gym)
Status: design phase

## Our safe re-design

The upstream implementation is a useful reference for the query execution pattern, but its security model should not be used in production without a full re-audit.

Our reference implementation design:

### Security-first sandbox

```
Student query
  ↓
SQL AST parser (only SELECT permitted — reject at parse level)
  ↓
Table allowlist check (only approved tables can be queried)
  ↓
Process-isolated executor (Docker container or subprocess)
  ↓ limits: 5s timeout, 256MB memory, 1000 rows, 500KB output
Query result
  ↓
AI error explanation (if query fails or returns unexpected result)
  ↓
Student
```

### Key differences from upstream

| Property | Upstream | Our design |
|---|---|---|
| SQL validation | Application-level | AST parser — structural |
| Table access | Not restricted | Allowlist only |
| Execution | Direct DB connection | Process-isolated |
| Timeout | Unknown | 5 seconds hard limit |
| Memory | Unknown | 256MB container limit |
| Row limit | Unknown | 1000 rows |
| Error message | Raw DB error | AI-generated explanation |

### Generated datasets

Rather than using real data, each learning environment session uses:
- Synthetically generated, seeded, schema-consistent relational data
- Fresh data per session (avoids cheating via memorization)
- Multiple difficulty tiers of schemas

## Implementation plan

See `product-concepts/adaptive-sql-learning-platform/` for the full product spec.
