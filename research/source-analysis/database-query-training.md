# Source Research Dossier: database-query-training

research_date: 2026-06-23
analyst: deep-analysis agent
import_type: vendored (upstream/ contains real code)

---

## Repository identity

- **Display name**: SQL-Gym
- **Functional name**: database-query-training
- **Creator**: tsembp (Panagiotis Tsembekis)
- **GitHub URL**: https://github.com/tsembp/SQL-Gym
- **Source path**: `sources/database-query-training/`
- **License**: MIT (verified — `upstream/LICENSE` present)
- **Import type**: vendored-snapshot (full code available at `upstream/`)
- **Creator context**: CS student. tsembp appears on GitHub primarily as a student builder — all four of his repositories in this lab are academic/personal projects. SQL-Gym is the only one with a license file and is the most complete end-to-end product.
- **Pinned commit**: cf16081778edddd663d333b0fc2c1287b1af98e1

---

## What it actually does

SQL-Gym is a self-hosted interactive SQL challenge platform built around a single fintech-flavored SQLite database (users, transactions, refunds, subscriptions). Users are presented with 15 natural-language challenges at four difficulty levels (BASIC → HARD) and type their SQL into a Monaco editor in the browser. The FastAPI backend runs the submitted query, runs the canonical solution query, and returns a success/failure decision by comparing Pandas DataFrames. The database is populated once by a seeder script using Faker and then served read-only for challenge evaluation.

---

## Architecture

The system is a two-process local application: a Python/FastAPI backend and a React/TypeScript/Vite frontend. There is no authentication, no persistent state per user, and no database write path for learners. The backend exposes a REST API over localhost:8000; the frontend calls it from the browser.

**Backend process**: FastAPI app at `backend/main.py`. Connects directly to `db/sqlgym.db` (SQLite file on disk) via `sqlite3` (raw) for schema inspection and `pd.read_sql_query` for challenge evaluation. SQLAlchemy is only used by the seeder, not the API.

**Frontend process**: React SPA served by Vite dev server. Two pages: `Home.tsx` (challenge list with difficulty filter) and `Challenge.tsx` (Monaco editor + results display). API calls via Axios in `api/apiService.ts` to `http://localhost:8000`.

**Data flow for evaluation**:
1. User writes SQL in Monaco editor, clicks "Run Query"
2. `POST /evaluate` → `{ challenge_id, user_answer }`
3. Backend checks for banned keywords (`drop`, `delete`, `update`, `insert`, `alter`)
4. Runs the hardcoded solution SQL → `expected_df`
5. Runs the user's SQL → `user_df`
6. Compares column names first (exact list match), then `DataFrame.equals()` for row-level equality
7. Returns `{ success, error?, preview }` (preview = first 5 rows of user result)

---

## Main modules and important files

| File | Role |
|------|------|
| `backend/main.py` | FastAPI app, all 5 endpoints, evaluation logic |
| `backend/challenge_definitions.py` | Static Python list of 15 challenge dicts — the curriculum |
| `backend/db/db_seeder.py` | Faker-based one-shot SQLite population script |
| `backend/db/models.py` | SQLAlchemy ORM models (User, Transaction, Refund, Subscription) |
| `frontend/tailwindcss4/src/pages/Challenge.tsx` | Monaco editor, submit handler, results display |
| `frontend/tailwindcss4/src/pages/Home.tsx` | Challenge list with difficulty filter |
| `frontend/tailwindcss4/src/api/apiService.ts` | Axios wrapper around all 6 API endpoints |
| `requirements.txt` | Python deps: fastapi, uvicorn, sqlite3, pandas, sqlalchemy, faker, streamlit (unused?), altair (unused?) |

---

## Core technical patterns

**1. Canonical-solution comparison** (`backend/main.py` lines 116–133): The evaluation model does not attempt to semantically understand the user's SQL. Instead, it runs the known-correct SQL, runs the user's SQL, and checks if the DataFrames are equal. This means two semantically different queries that produce the same rows will both pass — which is correct behavior for output-based SQL grading.

**2. Keyword blocklist injection guard** (`main.py` line 109): The only security mechanism is a case-insensitive substring check for `drop`, `delete`, `update`, `insert`, `alter` on the raw SQL string before execution. This is the most security-critical weakness in the codebase (see Security model section).

**3. Challenge-as-dict curriculum** (`challenge_definitions.py`): Each challenge is a Python dict with fields: `id`, `title`, `prompt`, `difficulty`, `solution_sql`, `expected_columns`, `hints`, `example_data`. The `example_data` field is a small inline sample shown as preview tables in the UI, not the actual database. Challenges cover: SELECT *, column selection, DISTINCT, GROUP BY + aggregate, LEFT JOIN, self-join, date arithmetic with `strftime`/`julianday`, HAVING, subqueries.

**4. Faker-seeded fintech schema** (`db_seeder.py`): 500 users, 5–20 transactions each (~4,000–10,000 total), ~150 refunds, 70% subscription rate. Timestamps respect user signup date order (transactions after signup). Amounts include negatives (refunds? anomalies?), multi-currency (USD/EUR/GBP).

---

## Novel or interesting mechanisms

- The `example_data` field in each challenge dict is a minimal inline dataset shown as preview tables in the UI. This is a clean UX pattern: the user can reason about the challenge structure without querying the live database. This is independent of the actual evaluation DB and could be replaced with generated per-challenge examples in a more sophisticated version.
- The difficulty labels (BASIC/EASY/MEDIUM/HARD) are purely editorial — they are not enforced by any adaptive logic. They are color-coded in the frontend (blue/green/yellow/red) and used as a client-side filter.
- The `expected_columns` field is checked separately before DataFrame equality, giving a more precise error message ("column mismatch" vs "wrong result").

---

## Data flow

```
[db_seeder.py] → sqlgym.db (SQLite file on disk)
                         ↑
[FastAPI main.py] ← reads via sqlite3 / pandas
        ↓
[REST API :8000]
        ↓
[React frontend :5173] ← Axios apiService.ts
        ↓
[Challenge.tsx] → Monaco editor → POST /evaluate
```

---

## Dependencies

**Backend** (from requirements.txt):
- `fastapi==0.115.12`, `uvicorn==0.34.3` — ASGI server + framework
- `pandas==2.3.0` — DataFrame comparison (the evaluation engine)
- `sqlalchemy==2.0.41` — only used by seeder, not the API
- `Faker==37.4.0` — seeder
- `pydantic==2.11.7` — request/response validation
- `streamlit==1.45.1`, `altair==5.5.0` — present in requirements.txt but NOT used by the app [inference: leftover from a prototype or an alternate visualization approach that was abandoned]
- `numpy==2.3.0`, `pyarrow==20.0.0` — pandas dependencies

**Frontend** (from package.json — inferred from imports):
- React + TypeScript + Vite
- `@monaco-editor/react` — SQL editor with syntax highlighting
- `axios` — HTTP client
- Tailwind CSS v4
- React Router DOM

---

## Security model

**Current upstream model is NOT production-safe.** Specific weaknesses:

1. **Keyword blocklist is bypassable**: The check `any(keyword in request.user_answer.lower() for keyword in ["drop", "delete", "update", "insert", "alter"])` can be bypassed with SQL comments (`DROP/**/TABLE`), Unicode substitution, or by simply naming a column alias with a blocked word inside a string literal. It is a naive string scan, not AST-level analysis.

2. **No process isolation**: The user's SQL executes in the same Python process as the server, in the same SQLite connection. There is no timeout, no memory limit, and no resource cap. A slow query (e.g., a large Cartesian join) will block the event loop.

3. **No row/result size limit**: A query that returns the entire database will be loaded into memory as a Pandas DataFrame with no cap.

4. **No authentication**: The `/challenge/{id}/solution` endpoint returns the canonical solution SQL without any auth check. Any user can retrieve the answer immediately.

5. **CORS is fully open** (`allow_origins=["*"]`): fine for a local dev tool, unacceptable for a hosted service.

6. **Table name injection in sample endpoint**: `GET /tables/{table_name}/sample` interpolates `table_name` directly into `SELECT * FROM {table_name} LIMIT {limit}` without sanitization. This is a SQL injection vector.

**The product concept README at `product-concepts/adaptive-sql-learning-platform/README.md` already identifies these issues and correctly states: "Do not use the upstream security model in production without a full re-audit."** The reference implementation at `reference-implementations/database-query-training-environment/` is where the safe version is being designed.

---

## Testing strategy

No test files are present in `upstream/`. The project has no unit tests, no integration tests, and no test runner configuration. Evaluation correctness is verified informally by running the app and checking outputs. The screenshots in `upstream/screenshots/` include one showing SQL injection protection (a blocked DDL command), confirming the author manually tested at least the keyword blocker.

---

## Genuinely reusable elements

1. **Challenge dict schema** (`challenge_definitions.py`): The structure — id, prompt, difficulty, solution_sql, expected_columns, hints, example_data — is a clean, minimal curriculum format. It is easy to extend, version-control, and programmatically generate. Can be directly adapted.

2. **Faker-seeded relational fintech schema** (`db_seeder.py` + `models.py`): The four-table schema (users, transactions, refunds, subscriptions) with Faker population and temporal ordering constraints (transactions after signup, refunds after transactions) is a well-thought-out minimal fintech dataset. Reusable as a test database template.

3. **DataFrame-equality evaluation pattern** (`main.py` evaluate endpoint): The two-query comparison approach (run solution, run user query, compare DataFrames) is the correct approach for output-based SQL grading. It handles multiple valid queries that produce the same result. This is the evaluation kernel to keep.

4. **Monaco editor + hint toggle UX pattern** (`Challenge.tsx`): The split-column layout (challenge info + example data left, editor + results right) is a clean, proven SQL playground layout. The toggle-to-reveal hints pattern avoids spoiling the challenge.

---

## What NOT to reuse

1. **The security model**: The keyword blocklist, the direct sqlite3 execution in-process, the open CORS, and the unauthenticated solution endpoint are all prototype-quality and unsafe for a hosted service.

2. **Static hardcoded challenges** without a database backing: For a real product, challenges should live in a database or structured file store with versioning, not a static Python list.

3. **The `/tables/{table_name}/sample` endpoint**: Direct SQL injection vector — do not port.

4. **Streamlit/Altair dependencies in requirements.txt**: These are dead weight; they appear to be leftover from an earlier prototype or exploration.

---

## Production-readiness

**Not production-ready.** This is a working local development tool / student project. It demonstrates the core idea correctly and has good frontend polish, but it lacks authentication, safe SQL execution, resource limits, persistence, and tests. It would require a full security re-architecture before being usable as a hosted service.

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- Clean frontend UX with Monaco editor, difficulty badges, example data tables, and real-time feedback
- Correct evaluation approach (DataFrame comparison, not regex-based SQL parsing)
- Well-structured challenge dict format with good coverage of SQL patterns (basic → HARD includes self-join, window-like patterns)
- MIT license — cleanly usable

**Weaknesses**:
- Security model is prototype-quality with multiple bypassable controls
- No user accounts, progress tracking, or session state
- 15 challenges only — not a complete curriculum
- All challenges target the same fintech schema — no domain variety
- No tests
- Solution endpoint is unauthenticated

**Technical debt**:
- Unused streamlit/altair in requirements.txt
- SQLAlchemy imported in models.py but not used by the API (only by seeder)
- Hard-coded `http://localhost:8000` in apiService.ts (no env var config)
- `allow_origins=["*"]` in CORS middleware

---

## Novel or differentiated elements

The combination of Monaco editor + inline example_data preview tables + DataFrame-equality evaluation in a single-file FastAPI backend is elegant for its simplicity. Most SQL learning tools either use managed cloud execution or complex sandbox infrastructure; this demonstrates that a local SQLite-backed evaluation engine can be built in ~140 lines of Python. The "example_data" pattern — inline minimal examples that match the challenge prompt but are independent of the real evaluation DB — is genuinely clever.

---

## Possible clean-room adaptations

1. **Safe evaluation kernel**: Extract the two-query comparison logic (solution_sql vs user_answer → DataFrame.equals()), wrap it in proper process isolation (subprocess + timeout + memory limit), add AST-level SQL parsing to reject non-SELECT statements. This is the core of the `reference-implementations/database-query-training-environment/` work.

2. **Challenge format**: Use the challenge dict structure as the curriculum data model, but store it in a database with metadata (topic tags, prerequisite challenges, expected learning outcome). Add a programmatic challenge generator that produces variations of a given template.

3. **Fintech schema as a training fixture**: The 4-table schema with Faker population is a reusable test fixture for any SQL practice environment. It can be extended with more tables (products, events, sessions) for a richer challenge set.

4. **Frontend layout pattern**: The split-column Monaco editor layout is a proven pattern that can be ported to a React component with cleaner state management (React Query instead of raw useEffect).

---

## Business applications

This source directly inspires the `product-concepts/adaptive-sql-learning-platform/` concept. Specific extensions toward commercial value:

- **AI error explanation layer**: Replace the raw "Wrong result" message with an LLM explanation of why the query fails (e.g., "Your result has 423 rows instead of 12 — you're missing a GROUP BY on merchant")
- **Interview track curation**: Map challenges to actual patterns from FAANG/fintech SQL interviews — this is the differentiator over SQLZoo/LearnSQL
- **Team licensing**: Companies buy licenses for cohorts of employees learning SQL for data roles
- **Progress persistence**: Track which challenges a user has solved, their attempt history, and their improvement trajectory

---

## Competitor landscape

From web research (2026-06-23):

- **SQLZoo** (https://sqlzoo.net/) — Free, established since 1999. Interactive SQL with immediate feedback. No interview-track orientation. Limited personalization.
- **LearnSQL** (https://learnsql.com/) — Paid subscription (individual and team plans). "All Forever" lifetime purchase available. Interactive exercises, structured courses. No Monaco editor.
- **SQLBolt** (https://sqlbolt.com/) — Free. Simple, clean, but no real database execution sandbox — exercises use a controlled in-browser engine.
- **DataLemur** (https://datalemur.com/) — SQL + data science interview prep. Real company question catalog. Has a paid Premium tier. Most directly competes with an interview-oriented SQL Gym product.
- **StrataScratch** (https://www.stratascratch.com/) — Similar to DataLemur: real interview questions, Python and SQL, paid subscription.
- **SQLpad.io** (https://sqlpad.io/) — SQL/Python/R interview prep. $151.8K ARR as of 2024 with a 1-person team (per getlatka.com). Demonstrates the market exists at small scale.
- **Codecademy Learn SQL** (https://www.codecademy.com/learn/learn-sql) — Broad platform, SQL is one course among hundreds. Paid subscription.
- **Mode Analytics SQL Tutorial** (https://mode.com/sql-tutorial/) — Free, uses real-world data, targets analytics professionals.

**Gap**: None of the above have AI-powered error explanation that goes beyond raw SQL error messages. DataLemur and StrataScratch are closest to the interview-prep angle but focus on question catalog breadth over explanation quality.

---

## Related business ideas in this lab

- `product-concepts/adaptive-sql-learning-platform/README.md` — direct product concept derived from this source
- `reference-implementations/database-query-training-environment/README.md` — in-lab reference implementation
- `business-research/BUSINESS_IDEAS_INDEX.md` — context on evidence level and next steps
- `sources/synthetic-relational-data/` — the ride-hail source also generates relational data; a more sophisticated SQL-Gym could use domain-varied datasets (not just fintech)

---

## Related sources in this lab

- `sources/synthetic-relational-data/` (tsembp/one-stop-ride-hail) — also by tsembp; the ride-hail schema could serve as an alternative domain for SQL challenges
- `sources/data-structure-search-engine/` (tsembp/EPL231-GroupAssignment) — same creator; also a CS coursework project

---

## Open questions

1. Does tsembp use this in a university course or personal project context? The "WG" prefix in the notifier bot suggests a course ("Web Group"?), so SQL-Gym may also be coursework.
2. The `streamlit==1.45.1` in requirements.txt — was there a Streamlit-based prototype before the React frontend? Worth understanding to see if there are additional patterns not in the current codebase.
3. The challenge set (15 challenges, fintech schema only) — is this the intended full curriculum, or was the project abandoned at this point?
4. There is no `.env` or configuration file — the DB path is hardcoded as `"db/sqlgym.db"` relative to the working directory. Was there a planned Docker or configuration layer?

---

## Final research conclusion

SQL-Gym is a well-executed, clean student project that demonstrates the minimal viable architecture for an interactive SQL challenge platform. The evaluation kernel (DataFrame comparison), the challenge dict format, the Monaco editor layout, and the Faker-seeded fintech schema are all genuinely reusable elements. The security model is prototype-only and must be fully replaced before any hosted deployment. The MIT license makes this the cleanest source in the lab for adaptation. The product concept it inspires (`adaptive-sql-learning-platform`) addresses a real, validated market (SQL interview prep), with DataLemur and StrataScratch as direct competitors and a clear differentiator opportunity in AI-powered error explanation. Confidence: high that the technical approach is sound; medium that the business differentiation is sufficient without user research.
