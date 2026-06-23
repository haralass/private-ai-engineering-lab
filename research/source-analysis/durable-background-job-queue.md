# Source Research Dossier: durable-background-job-queue

---

## Repository identity

- **Name:** ForgeQueue
- **Creator:** Stylianos Gavriil (sgavriil01)
- **GitHub URL:** https://github.com/sgavriil01/forgequeue
- **Source path:** `sources/durable-background-job-queue/`
- **License:** MIT (verified, copy allowed with attribution)
- **Import type:** vendored-snapshot (pinned commit `a08a6f9caf24a929db50fac00f8096983a8a8f1f`)

---

## What it actually does

ForgeQueue is a PostgreSQL-backed durable background job queue written in Go. Jobs are persisted as rows in a `jobs` table with a typed status enum (`pending`, `running`, `completed`, `failed`, `dead`, `cancelled`), a JSONB payload, priority integer, scheduled `run_at` timestamp, retry tracking (`retry_count`, `max_retries`), and optimistic locking fields (`locked_by`, `locked_until`). The system is split into two binaries: an HTTP API server that exposes job management endpoints, and a worker process (scaffold only at the pinned commit) that is expected to claim and execute jobs. The API server responds to `/healthz` (liveness) and `/readyz` (readiness, DB ping) and manages graceful shutdown on OS signals.

**Important**: At the pinned commit, the project is at "Phase 0" — the data model, DB layer, API scaffold, and config loading are solid, but the worker process does not yet implement actual job claiming or execution. The `worker/main.go` simply starts, logs, and waits for shutdown. The job-claiming SQL (`SELECT ... FOR UPDATE SKIP LOCKED`) is not yet written.

---

## Architecture

**Binary 1 — API server (`cmd/api/main.go`):**
- Reads config via `config.Load()` using environment variables (`FORGEQUEUE_DATABASE_URL`, `FORGEQUEUE_HTTP_ADDR`, `FORGEQUEUE_ENV`, `FORGEQUEUE_SHUTDOWN_TIMEOUT`)
- Creates a `pgxpool.Pool` (connection pool via pgx v5)
- Wires `db.Queries` → `jobs.Service` → `httpapi.Server`
- Registers chi router with Recoverer middleware
- Runs `http.Server.ListenAndServe()` in a goroutine; selects on shutdown signal vs. server error
- On SIGINT/SIGTERM: calls `server.Shutdown()` with a configurable timeout (default 10s)

**Binary 2 — Worker (`cmd/worker/main.go`):**
- Scaffold only: loads config, logs start, waits for shutdown signal
- Job claim/execute loop is not yet implemented

**Database layer (sqlc-generated):**
- `internal/db/sqlc/` contains sqlc v1.30.0 generated code
- `jobs.sql.go`: typed `CreateJob`, `GetJobByID`, `ListJobs`, `ListJobsByStatus`, `CancelPendingJob`, `Ping`
- `models.go`: `Job` struct with pgtype fields; `JobStatus` string type with `Scan`/`Value` implementations

**Service layer (`internal/jobs/service.go`):**
- Thin validation wrapper over `db.Queries`
- Validates kind (non-empty), payload (valid JSON object, must be `{...}`), max_retries (0–10 range)
- Exposes: `CreateJob`, `GetJob`, `ListJobs` (with optional status filter), `CancelJob` (atomic — only cancels `pending` jobs), `Ping`
- `ParseJobStatus()` maps raw string to typed `JobStatus` const

**HTTP layer:**
- `httpapi.Server` uses `go-chi/chi/v5` router with `middleware.Recoverer`
- Two handlers at present: `handleHealthz` (always 200), `handleReadyz` (pings DB)
- `writeJSON` / `writeError` helpers for consistent JSON envelope
- Request logging middleware (`logRequests`) and panic recovery (`recoverPanic`) are present but not wired into chi routes in the server.go — there is an older `routes.go` with the `addRoutes` pattern that is not used in `Server.Routes()`

**Migrations (`golang-migrate`):**
- `migrations/000001_create_jobs_table.up.sql`: creates `job_status` enum, `jobs` table with `pgcrypto` UUIDs, and three indexes
- Partial indexes: `idx_jobs_pending` (on `run_at, priority DESC WHERE status='pending'`) and `idx_jobs_running_locked_until` (on `locked_until WHERE status='running'`) are thoughtful for worker polling performance

---

## Main modules and important files

| File | Purpose |
|------|---------|
| `cmd/api/main.go` | API server entrypoint — signal handling, pool init, server wire-up |
| `cmd/worker/main.go` | Worker entrypoint scaffold (not yet implemented) |
| `internal/config/config.go` | Env-var config loading with defaults |
| `internal/db/queries/jobs.sql` | SQL source of truth (sqlc input) |
| `internal/db/sqlc/models.go` | Go types for Job, JobStatus |
| `internal/db/sqlc/jobs.sql.go` | sqlc-generated Go functions |
| `internal/jobs/service.go` | Domain service layer — validation + DB calls |
| `internal/httpapi/server.go` | HTTP server, chi router, health endpoints |
| `internal/httpapi/response.go` | JSON response helpers |
| `internal/httpapi/middleware.go` | logRequests + recoverPanic middleware |
| `migrations/000001_create_jobs_table.up.sql` | DB schema |
| `docker-compose.yml` | Postgres 16 dev container (port 5433) |
| `sqlc.yaml` | sqlc code generation config |

---

## Core technical patterns

1. **SELECT FOR UPDATE SKIP LOCKED (designed but not yet implemented):** The `locked_by` + `locked_until` columns in the schema clearly anticipate advisory locking for worker job claiming. The pattern is: `UPDATE jobs SET status='running', locked_by=$worker_id, locked_until=NOW()+interval WHERE id IN (SELECT id FROM jobs WHERE status='pending' AND run_at <= NOW() ORDER BY priority DESC FOR UPDATE SKIP LOCKED LIMIT 1)`. This is the standard PostgreSQL-native job queue pattern (used by River, pgq, etc.).

2. **sqlc code generation:** All database interaction is generated from `.sql` files using sqlc v1.30.0. This eliminates ORM overhead and produces type-safe Go functions, with zero reflection at query time.

3. **pgx v5 connection pool:** Uses `pgxpool.Pool` for connection pooling rather than `database/sql`, enabling full use of pgx native types (`pgtype.UUID`, `pgtype.Timestamptz`, `pgtype.Text`) without scanning overhead.

4. **Partial indexes for worker efficiency:** `idx_jobs_pending` filters on `status='pending'` — only pending jobs consume index space. `idx_jobs_running_locked_until` supports stale lock detection queries.

5. **Graceful shutdown pattern:** Uses `signal.NotifyContext` for clean OS signal handling, then `server.Shutdown()` with a timeout. Worker would use the same pattern for drain-and-exit.

6. **Payload enforced as JSON object:** The service layer validates that payload is valid JSON and specifically that it starts with `{` and ends with `}` — arrays and primitives are rejected. This is a deliberate design constraint.

---

## Novel or interesting mechanisms

- **Priority + run_at compound index** (`run_at, priority DESC WHERE status='pending'`): By combining scheduled execution time with priority in a single partial index, workers can do a single index scan to find the highest-priority ready job. This is more efficient than two separate queries.

- **Dead status distinction:** The schema distinguishes `failed` (retryable failure, retry count incremented) from `dead` (exhausted retries, moved to dead letter). This two-phase failure design enables dead-letter queue inspection without losing audit trail.

- **Duplicate middleware stubs:** There is a `routes.go` with an older `addRoutes` function alongside the newer method-based `server.go`. The older file is a Phase 0 artefact that was not deleted. [inference: the project was refactored mid-scaffold and the old file remains. This is technical debt.]

---

## Data flow

```
Client HTTP → chi Router → Server.handleHealthz / handleReadyz
                                      ↓
                              jobs.Service.Ping()
                                      ↓
                         db.Queries.Ping() → pgxpool → PostgreSQL

Job Creation (not yet wired to API routes, only service layer exists):
Caller → jobs.Service.CreateJob(kind, payload, runAt, maxRetries)
              → validates kind/payload/retries
              → db.Queries.CreateJob() → INSERT INTO jobs → returns Job row

Worker (planned):
Goroutine → SELECT FOR UPDATE SKIP LOCKED → SET status=running
         → execute handler → SET status=completed/failed
         → on retry exhaustion → SET status=dead
```

---

## Dependencies

| Dependency | Version | Purpose |
|---|---|---|
| `github.com/go-chi/chi/v5` | v5.3.0 | HTTP router |
| `github.com/jackc/pgx/v5` | v5.10.0 | PostgreSQL driver + connection pool |
| `github.com/jackc/puddle/v2` | v2.2.2 | Connection pool internals (indirect) |
| `golang.org/x/sync` | v0.17.0 | Synchronization primitives (indirect) |
| Go standard library | 1.25.9 | net/http, log/slog, context, encoding/json |
| sqlc v1.30.0 | (build tool) | SQL → Go code generation |
| golang-migrate | (implied) | DB migrations |

No external job queue dependencies (no Redis, no RabbitMQ, no Kafka). Pure PostgreSQL.

---

## Security model

- **No authentication on API endpoints** (Phase 0 — health checks only). Production deployment would require auth middleware.
- **SQL injection:** Zero risk — all queries are parameterized via sqlc-generated code using `pgx` prepared statements.
- **Panic recovery:** `recoverPanic` middleware exists but is not wired into the chi router in the current `server.go`; `middleware.Recoverer` from chi is wired instead. [inference: the chi middleware covers the same concern but the custom one was not removed]
- **Locked_by / locked_until:** Supports multi-worker deployments safely via DB-level locking, no distributed lock manager needed.
- **No TLS configuration** in the current codebase — deployment assumes a reverse proxy handles TLS.
- **FORGEQUEUE_DATABASE_URL required** — server fails fast if not set, no silent misconfiguration.

---

## Testing strategy

- **Integration test** (`internal/db/sqlc/jobs_integration_test.go`): Real PostgreSQL test at `localhost:5433`. Creates a job, reads it back, asserts on fields. Requires a live DB. Uses `TEST_DATABASE_URL` env var with a default pointing to the docker-compose instance.
- **Unit test** (`internal/config/config_test.go`): Tests config loading logic (not read, but referenced in the file listing).
- **Server test** (`internal/httpapi/server_test.go`): Tests HTTP handler behavior (not read, assumed to test health endpoints).
- No mocking framework. Integration tests use real DB. No test fixtures or factories.
- **CI:** `.github/workflows/ci.yml` present (not read) — likely runs tests against a Postgres service container.

---

## Genuinely reusable elements

(License: MIT — reuse allowed with attribution)

1. **DB schema** (`migrations/000001_create_jobs_table.up.sql`): The jobs table design with priority, run_at, retry fields, locked_by/locked_until, and partial indexes is production-ready and directly reusable in any PostgreSQL-backed Go service.

2. **`jobs.Service` validation logic** (`internal/jobs/service.go`): The payload validation pattern (JSON object enforcement, retry range clamping) is a clean pattern for any job queue API.

3. **`config.go` pattern**: The `getDefault` helper with `getenv func(string) string` injection makes config testable without environment variable mutation. Reusable as a Go config pattern.

4. **Graceful shutdown pattern** (`cmd/api/main.go`): `signal.NotifyContext` + `server.Shutdown()` with timeout is idiomatic Go and worth copying directly.

5. **sqlc + pgx pattern**: The sqlc → pgx v5 stack without any ORM is a clean, high-performance pattern for Go + PostgreSQL services.

---

## What NOT to reuse

- **`worker/main.go`**: Empty scaffold. Contains no actual job processing logic.
- **`internal/httpapi/routes.go`**: Orphaned file from earlier scaffold iteration; superseded by `server.go`.
- **`addRoutes` function in routes.go**: Dead code — the server uses `Server.Routes()` with chi instead.
- **The project as a drop-in library**: ForgeQueue has no package-importable API surface. It is a service, not a library. To use the patterns, you'd extract and adapt them.

---

## Production-readiness

**MVP-quality** for the data model and API scaffold. The DB schema and indexing strategy are production-grade. The worker is not implemented. Missing for production:
- Worker job-claiming loop
- API endpoints for creating/listing/cancelling jobs (service exists, but no HTTP routes beyond health checks)
- Authentication/authorization on the API
- Distributed tracing / metrics
- Job handler registry (type → handler function mapping)
- Dead-letter queue management endpoints
- Stale lock reclaim (worker restart detection)

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- Clean Go idioms; proper error wrapping with `%w`
- sqlc codegen eliminates SQL string bugs at compile time
- Thoughtful DB schema with correct partial indexes
- Graceful shutdown is correct
- Config loading is testable (dependency injection via `getenv`)

**Weaknesses:**
- Worker is a stub — the most critical part is missing
- No HTTP routes for job management (only health)
- The panic recovery middleware is defined but not correctly wired (chi's `Recoverer` covers it, but the custom middleware in `middleware.go` is dead code)
- `routes.go` is an orphaned file

**Technical debt:**
- Orphaned `health.go` with `handleHealthz`/`handleReadyz` functions (functional pattern using `http.Handler` return type) that differ from the method-based pattern in `server.go` — two implementations of the same handlers exist. The functional version in `health.go` uses `encode()` (not defined in the read files — possibly from a `json.go` file), while `server.go` uses `writeJSON`. [inference: two approaches were explored and neither was cleaned up]

---

## Novel or differentiated elements

- The choice to implement a job queue as a standalone Go HTTP service (rather than a library) is unusual; most Go job queue libraries (River, pgq) are importable packages. ForgeQueue is opinionated: it exposes a REST API, enabling polyglot job producers.
- The `locked_by` field stores a worker identity string, enabling per-worker monitoring without a separate worker registry table.

---

## Possible clean-room adaptations

1. Extract the DB schema and `SELECT FOR UPDATE SKIP LOCKED` worker query pattern and implement in any language targeting PostgreSQL.
2. Adapt the `jobs.Service` validation logic for use in a job queue microservice in a different tech stack.
3. Use the graceful shutdown + signal handling pattern verbatim in other Go services.
4. Extend the HTTP API to add `POST /jobs`, `GET /jobs/{id}`, `DELETE /jobs/{id}` routes — the service layer already supports all these operations.

---

## Business applications

1. **AI pipeline job scheduler:** Any product that runs LLM inference, embedding generation, or async AI tasks benefits from a durable queue. ForgeQueue's schema directly supports delayed jobs (`run_at`), retries, and priority — critical for managing expensive AI API calls.
2. **Webhook delivery system:** E-commerce or SaaS platforms that must reliably deliver webhooks with retries and dead-letter inspection.
3. **Notification dispatch:** Email/SMS/push notification pipelines where order, deduplication, and retry behavior matter.
4. **ETL / data pipeline orchestration:** The priority + scheduling columns naturally model ETL batches that must not overlap.

---

## Related business ideas in this lab

- `sources/asynchronous-job-processing/` (sms-platform) — same creator, builds on similar async patterns at application level
- `product-concepts/intelligent-change-monitoring/` — noted as potential beneficiary of job queue infrastructure
- `product-concepts/adaptive-sql-learning-platform/` — noted as potential beneficiary of job queue infrastructure

---

## Related sources in this lab

- `sources/asynchronous-job-processing/` — sgavriil01's sms-platform provides an application-level example of the patterns forgequeue provides as infrastructure
- `sources/model-layer-streaming/` — AirLLM could use a job queue for managing long-running inference requests
- `sources/code-review-assistant/` — code review workflows could benefit from async job processing for large PRs

---

## Open questions

1. What specific worker job-claim SQL was the author planning? The schema has `locked_by`/`locked_until` suggesting `FOR UPDATE SKIP LOCKED`, but no SQL for it exists yet.
2. Is there a plan for job handler registration (mapping `kind` strings to handler functions)?
3. Why is the API server exposing no job management routes despite the service layer supporting them? Is that intentional gating for Phase 1?
4. The `go.mod` specifies `go 1.25.9` — a future Go version. Is this a typo or does it reflect the actual development environment?

---

## Final research conclusion

ForgeQueue is a well-structured scaffold for a PostgreSQL-backed Go job queue with strong foundations: correct DB schema, type-safe SQL via sqlc, clean service validation, and proper Go idioms. At the pinned commit it is Phase 0 — the worker does not execute jobs, and no HTTP routes for job management exist beyond health checks. The MIT license makes it the only directly copyable source in this cluster. Its highest value to the lab is as a blueprint for the DB schema and Go architecture pattern; the clean-room adaptation path (extract schema + claim query, implement worker loop, add HTTP routes) is straightforward and requires approximately 200–400 lines of new Go code. It directly complements `sources/asynchronous-job-processing/` (sms-platform), which shows the application-layer consumer of these patterns.
