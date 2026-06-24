---
lab_label: person1
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person1 — Open Questions

---

## Answered from vendored code

### forgequeue (durable-background-job-queue) — CLOSED

**Q: What database does it use?**
→ **PostgreSQL.** The README states "durable PostgreSQL-backed job queue." The schema
uses `pgx v5` connection pool, `pgtype.UUID`, `pgtype.Timestamptz`. No Redis or SQLite.

**Q: Does it support priorities and scheduling (cron-style)?**
→ **Priorities: yes.** `jobs` table has a `priority INT` column with a compound partial
index `(run_at, priority DESC WHERE status='pending')`. Workers can claim the
highest-priority ready job in one index scan. Cron-style scheduling: yes via `run_at
TIMESTAMPTZ` — jobs can be scheduled at a future time. No built-in cron expression parser
was present at the pinned commit.

**Q: Is there a retry mechanism with backoff?**
→ **Yes.** Schema has `retry_count` and `max_retries` columns and a two-phase failure
design: `failed` (retryable, retry_count incremented) vs `dead` (exhausted retries,
dead-letter). The backoff interval is not implemented at the pinned commit (Phase 0),
but the schema is designed for it.

**Q: What is the deployment model?**
→ **Standalone service, two binaries.** An HTTP API server (`cmd/api`) and a worker
process (`cmd/worker`). At the pinned commit the worker binary is a scaffold only —
it starts, logs, and waits for shutdown without claiming jobs.

**Q: Has the code been reviewed?**
→ **Yes.** Full dossier at `research/source-analysis/durable-background-job-queue.md`.
Notable finding: `routes.go` with an older `addRoutes` function coexists with the
newer method-based `server.go` — a Phase 0 artefact, minor technical debt.

---

## Still open — requires upstream code review or user input

### sms-platform (asynchronous-job-processing)

- What is the stack? (Python/Node/Go/other?) — inferred Go (same creator as forgequeue) but unconfirmed.
- What job types does it handle? (SMS only, or generic queue?)
- Is there a retry/dead-letter queue mechanism?
- What database does it use for persistence?

### neura-btm-battery-dispatch (business-energy-dispatch)

- What optimization algorithm is used? (LP, MILP, heuristic, rule-based?)
- What is the input data format for PV production and load profiles?
- What tariff models does it support?
- Is there an existing validation dataset or real deployment data?

### whisper-faiss-example (semantic-audio-search)

- What Whisper model size does it use?
- How is audio segmented before indexing? (fixed window? VAD-based?)
- Is the FAISS index persisted to disk or rebuilt each run?
- What is the query interface? (CLI? API? web UI?)

### commitgen (privacy-safe-commit-assistant)

- Does it use an LLM, templates, or a combination?
  (Inferred: local LLM or template-based, given the privacy-safe framing.)
- What diff format does it accept as input?

---

## User decisions still open

- Are any of these repositories expected to be licensed in the future?
  (Would unlock vendoring for sms-platform, neura-btm-battery-dispatch,
  whisper-faiss-example, commitgen.)
- Is there interest in a production-ready version of forgequeue's patterns
  in `components/` or `reference-implementations/`?
