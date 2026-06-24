---
lab_label: person2
research_date: 2026-06-24
---

# person2 — Repositories Reviewed

---

## 1. database-query-training (SQL-Gym)

**Import mode:** vendored-snapshot (MIT)
**Source path:** `sources/database-query-training/upstream/`
**Pinned commit:** cf16081778edddd663d333b0fc2c1287b1af98e1
**Stack:** Python, FastAPI, SQLite, pandas, React frontend (separate)

### Architecture

Backend (`backend/`):
- `main.py` — FastAPI app with 4 routes:
  - `GET /schema` — returns all table names and column definitions from SQLite
  - `GET /tables/{table_name}/sample` — returns up to N rows from any table
  - `GET /challenges` — lists all challenges, filterable by difficulty
  - `GET /challenge/{challenge_id}` — returns single challenge with prompt, hints, example_data
  - `POST /evaluate` — accepts `{challenge_id, user_answer}`, executes user SQL against DB, compares result columns and row content against solution SQL
- `challenge_definitions.py` — hardcoded list of 30+ challenge dicts with: id, title, prompt, difficulty (BASIC/INTERMEDIATE/ADVANCED), solution_sql, expected_columns, hints, example_data
- `db/models.py` — SQLite schema definitions
- `db/db_seeder.py` — seeds the database with sample data (users, transactions, merchants)

Database schema: e-commerce domain — users, transactions, merchants tables.

Evaluation logic: executes both user_answer and solution_sql, normalizes results with pandas,
compares column sets and row content. Returns `{correct: bool, feedback: str}`.

Frontend (`frontend/`): React app (likely Vite/CRA), not analyzed in detail.

### What is done well
- Clean separation between challenge definitions and evaluation logic
- FastAPI is appropriate for a simple eval API
- CORS middleware enabled for local dev
- Difficulty levels (BASIC/INTERMEDIATE/ADVANCED) make this teachable

### What is weak
- Challenge definitions are hardcoded Python dicts — no DB-backed challenge management
- Evaluation uses naive column/row comparison (ordering issues possible)
- No authentication — anyone can POST /evaluate
- SQL injection risk in `/tables/{table_name}/sample` (table_name not sanitized)
- No user accounts, no progress tracking
- No LLM-based error explanation

### Production readiness
Not production-ready. Suitable as a teaching prototype.

### What's worth keeping
- Challenge definition schema (id, title, prompt, difficulty, solution_sql, expected_columns, hints, example_data)
- Evaluation pattern (run both SQLs, compare with pandas)
- FastAPI route structure for a SQL learning backend

---

## 2. modular-rag-learning (AI-Study-Mate)

**Import mode:** vendored-snapshot (MIT)
**Source path:** `sources/modular-rag-learning/upstream/`
**Pinned commit:** 0e9fc7fd69b3aa0f993f09b09a54381865a28d1b
**Stack:** Python, Streamlit, LangChain, HuggingFace, FAISS, PyMuPDF (likely)

### Architecture

`app.py` — Streamlit entrypoint. Session state management for: document_processed, vector_db,
selected_mode, study_mode_selected, flashcards, questions, current_card_index, show_answer,
file_already_uploaded, current_filename.

`src/` modules:
- `document_loader.py` — loads PDF/text files (likely PyMuPDF or LangChain loaders)
- `chunk_and_embed.py` — splits with `RecursiveCharacterTextSplitter(chunk_size=500, chunk_overlap=100)`,
  embeds with `HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")`, stores in FAISS,
  saves FAISS index to `db/` directory
- `quiz_generator.py` — generates MCQs from document content (likely LLM-based)
- `flashcard_generator.py` — generates Q&A flashcards
- `summarizer.py` — summarizes document content
- `dummy_data.py` — generates sample PDF for demo

Pipeline: PDF upload → `load_document()` → `chunk_documents()` → `embed_and_store()` →
FAISS vector DB in session state → user selects mode (quiz/flashcard/summary) → generates content.

### What is done well
- Clean modular pipeline (load → chunk → embed → query)
- FAISS index persisted locally (`db/`)
- HuggingFace embeddings are free/local (no API key for embeddings)
- Session state management handles re-uploads correctly (filename comparison)
- Mode separation (quiz vs flashcard vs summary)

### What is weak
- Streamlit session state is inherently single-user
- Torch JIT profiling disabled globally (`torch._C._jit_set_profiling_mode(False)`) — quick fix
- No evaluation of generated quiz answers
- No multi-document support
- No chunking strategy beyond fixed size (no semantic chunking)
- No reranker

### Production readiness
Prototype/demo quality. Requires multi-user session management, proper auth, and LLM cost
control before production use.

### What's worth keeping
- `chunk_and_embed.py` pattern (RecursiveCharacterTextSplitter + FAISS + local persistence)
- `all-MiniLM-L6-v2` as a lightweight free embedding model
- Mode separation pattern (same document, different generation tasks)

---

## 3. change-monitoring-notifications (WG-Course-Task-Notifier-Bot)

**Import mode:** reference-only (no LICENSE file)
**Coverage:** README description only

Telegram bot that monitors an external website (course task portal) for changes and sends
notifications. Pattern: periodic polling → change detection → Telegram notification.

Relevant for `product-concepts/intelligent-change-monitoring/`.

---

## 4. synthetic-relational-data (one-stop-ride-hail)

**Import mode:** reference-only (no LICENSE file)
**Coverage:** README description only

Ride-hailing application schema with synthetic relational data. The repository name suggests
a ride-hailing domain model (drivers, riders, trips, payments).

Relevant for `product-concepts/synthetic-test-data-platform/`.

---

## 5. algorithm-benchmarking (Hitting-Set-Problem)

**Import mode:** reference-only (no LICENSE file)
**Coverage:** README description only

Algorithmic implementation of the Hitting Set Problem (NP-hard combinatorial optimization).
Academic/CS theory work.

---

## 6. data-structure-search-engine (EPL231-GroupAssignment)

**Import mode:** reference-only (no LICENSE file)
**Coverage:** README description only

Group assignment for EPL231 (data structures course). Likely implements a search engine
using foundational data structures (hash tables, trees, etc.). Academic project.
