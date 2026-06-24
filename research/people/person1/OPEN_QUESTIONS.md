---
lab_label: person1
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person1 — Open Questions

Evidence labels used in this file:
- `confirmed-from-code` — verified in vendored upstream/ or directly read files
- `confirmed-from-documentation` — verified from README, docs, or comments in the repo
- `strong-inference` — high-confidence conclusion from indirect evidence; not confirmed
- `hypothesis` — plausible but speculative; requires verification
- `still-open` — cannot be answered from available lab content

Only `confirmed-from-code` and `confirmed-from-documentation` are treated as CLOSED.
Items labelled `strong-inference` or `hypothesis` appear under "Inferences requiring confirmation."

---

## Confirmed — forgequeue (durable-background-job-queue) [CLOSED]

**Database:** `confirmed-from-code` — PostgreSQL. The README states "durable PostgreSQL-backed
job queue." The schema uses `pgx v5` connection pool with native `pgtype.UUID`,
`pgtype.Timestamptz` types. No Redis or SQLite involved.

**Priorities:** `confirmed-from-code` — `jobs` table has a `priority INT` column and a
compound partial index `(run_at, priority DESC WHERE status='pending')`. Workers can
claim the highest-priority ready job in a single index scan.

**Delayed scheduling (run_at):** `confirmed-from-code` — `run_at TIMESTAMPTZ` allows
scheduling a job for a future time. No recurring scheduler or cron-expression parser
was found at the pinned commit. This confirms future/delayed scheduling only.

**Retry and dead-letter tracking:** `confirmed-from-code` — The schema supports retry and
dead-letter state tracking (`retry_count`, `max_retries`, `failed`/`dead` status enum).
The pinned commit does not contain a complete retry worker or backoff implementation.

**Deployment model:** `confirmed-from-documentation` — Two standalone binaries: an HTTP API
server (`cmd/api`) and a worker process (`cmd/worker`). At the pinned commit the worker
binary is a scaffold only — it starts, logs, and waits for shutdown without claiming jobs.

**Code reviewed:** `confirmed-from-code` — Full dossier at
`research/source-analysis/durable-background-job-queue.md`.

---

## Inferences requiring confirmation

**sms-platform stack:** `strong-inference` — Likely Go (same creator as forgequeue, which is Go).
The async processing pattern is inferred from the lab label `asynchronous-job-processing`
and the companion forgequeue project. Not confirmed from direct code review.

**commitgen privacy mechanism:** `strong-inference` — The lab label `privacy-safe-commit-assistant`
suggests the tool processes git diffs locally without sending code to external APIs. Whether
it uses a local LLM, a template, or a combination is not confirmed.

---

## Still open — requires upstream code review or user input

### sms-platform (asynchronous-job-processing) [`still-open`]

- What is the stack? (Go/Node/Python/other?)
- What job types does it handle? (SMS only, or generic queue?)
- Does a retry/dead-letter mechanism exist in the implementation?
- What database does it use for persistence?

### neura-btm-battery-dispatch (business-energy-dispatch) [`still-open`]

- What optimization algorithm is used? (LP, MILP, heuristic, rule-based?)
- What is the input data format for PV production and load profiles?
- What tariff models does it support?
- Is there an existing validation dataset or real deployment data?

### whisper-faiss-example (semantic-audio-search) [`still-open`]

- What Whisper model size does it use?
- How is audio segmented before indexing? (fixed window? VAD-based?)
- Is the FAISS index persisted to disk or rebuilt each run?
- What is the query interface? (CLI? API? web UI?)

### commitgen (privacy-safe-commit-assistant) [`still-open`]

- Does it use an LLM, templates, or a combination?
- What diff format does it accept as input?

---

## User decisions still open [`still-open`]

- Are any of these repositories expected to be licensed in the future?
  (Would unlock vendoring for sms-platform, neura-btm-battery-dispatch,
  whisper-faiss-example, commitgen.)
- Is there interest in a production-ready version of forgequeue's patterns
  in `components/` or `reference-implementations/`?
