# Current State

Last updated: 2026-06-27

This is the canonical current-status document for the lab. `DELIVERY_REPORT.md` is historical Phase 1 context only.

---

## Repository Status

Draft PR #5 (`codex/import-mit-external-sources`) is the active cleanup and integration PR. It corrects oversized upstream imports, preserves archived research and raw evidence, and keeps the new MIT/external additions as safe local-research-only records unless a later PR approves minimal vendoring.

PR #5 status: **draft, not merged**.

---

## Source Record Counts

Total sources: **50**.

| Import mode | Count |
|---|---:|
| `vendored-snapshot` | 16 |
| `selected-subsystem` | 1 |
| `local-research-only` | 17 |
| `reference-only` | 16 |

Exact 50-source breakdown by source label:

| Source label | Total | Vendored snapshot | Selected subsystem | Local research only | Reference only |
|---|---:|---:|---:|---:|---:|
| external | 28 | 12 | 1 | 9 | 6 |
| person1 | 5 | 1 | 0 | 0 | 4 |
| person2 | 6 | 2 | 0 | 0 | 4 |
| person3 | 5 | 1 | 0 | 3 | 1 |
| person4 | 3 | 0 | 0 | 2 | 1 |
| student-collab | 3 | 0 | 0 | 3 | 0 |

Exact license breakdown:

| License value | Count |
|---|---:|
| MIT | 17 |
| Apache-2.0 | 6 |
| BSD-3-Clause | 2 |
| NOT-FOUND | 21 |
| UNKNOWN | 1 |
| unknown | 3 |

---

## Public Code Library Counts

The public-code research library lives in `research/public-code-library/`.

| Metric | Count |
|---|---:|
| Manifest repositories | 68 |
| Full repository dossiers | 48 |
| Summary-only manifest entries | 20 |
| Candidate pool entries | 107 |
| Accepted candidate entries | 75 |
| Rejected candidate entries | 19 |
| Needs-verification candidate entries | 4 |
| Summary-only candidate entries | 9 |

Canonical public-code licensing summary: `research/public-code-library/synthesis/licensing-and-provenance.md`.

---

## Current Coverage Limitations

- Local-research-only records preserve useful implementation notes but do not include upstream code in Git.
- Reference-only records may contain metadata and README-level observations only.
- Repositories with no root license, unclear license evidence, restricted terms, AGPL terms, Codrops terms, FSL terms, or no assertion are safe study/reference material only unless a later review verifies a permitted reuse path.
- Public-code-library dossiers are research outputs, not legal opinions and not automatic approval to copy code.
- Raw evidence in `research/public-code-library/data/raw/` is retained for traceability and is not a curated public API.
- Archived reports in `research/public-code-library/archive/` are historical context and may contain older paths or superseded recommendations.
- Component directories other than `agent-safety-firewall` remain planned/research unless their own tests and review state say otherwise.

---

## Source Governance

Current source policy:

- Prefer `local-research-only` for external code-level research.
- Use `reference-only` for unclear, absent, restricted, or non-commercial licenses.
- Permit vendoring only when the license and scope are explicitly approved.
- Do not commit full repository snapshots merely for archival purposes.

Relevant validators:

```bash
python scripts/validation/validate_source_manifests.py
python scripts/validation/validate_catalog_consistency.py
python scripts/validation/validate_public_code_library.py
```

---

## Component Status

| Component | Status |
|---|---|
| agent-safety-firewall | prototype - tests written, not production-ready |
| All others | research / stub unless documented otherwise in the component directory |
