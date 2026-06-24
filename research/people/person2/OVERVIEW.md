---
lab_label: person2
research_date: 2026-06-24
last_updated: 2026-06-24
status: initial-research
---

# person2 — Research Overview

## Repositories catalogued

| Functional name | Upstream repo | Import mode | License | Notes |
|---|---|---|---|---|
| database-query-training | tsembp/SQL-Gym | vendored-snapshot | MIT | Full code in `sources/database-query-training/upstream/` |
| modular-rag-learning | tsembp/AI-Study-Mate | vendored-snapshot | MIT | Full code in `sources/modular-rag-learning/upstream/` |
| change-monitoring-notifications | tsembp/WG-Course-Task-Notifier-Bot | reference-only | NOT-FOUND | Metadata and README only |
| synthetic-relational-data | tsembp/one-stop-ride-hail | reference-only | NOT-FOUND | Metadata and README only |
| algorithm-benchmarking | tsembp/Hitting-Set-Problem | reference-only | NOT-FOUND | Metadata and README only |
| data-structure-search-engine | tsembp/EPL231-GroupAssignment | reference-only | NOT-FOUND | Metadata and README only |

**2 vendored-snapshot, 4 reference-only.**

---

## Technical coverage

person2's public work covers: SQL learning platforms (FastAPI + SQLite + challenge engine),
modular RAG pipelines (Streamlit + LangChain + FAISS + HuggingFace embeddings), Telegram
notification bots for course monitoring, ride-hailing synthetic data schemas, algorithm
implementations (Hitting Set Problem), and academic data structure projects.

---

## Most important repositories

**SQL-Gym** (`sources/database-query-training/`) — MIT, vendored.
A SQL challenge platform with FastAPI backend, SQLite database, 30+ hardcoded challenges
across BASIC/INTERMEDIATE/ADVANCED difficulty levels. Includes /schema, /tables/{name}/sample,
/challenges, and /evaluate endpoints.

**AI-Study-Mate** (`sources/modular-rag-learning/`) — MIT, vendored.
A Streamlit-based RAG study tool. Pipeline: PDF upload → LangChain document loading →
RecursiveCharacterTextSplitter (500/100 chunks) → HuggingFace all-MiniLM-L6-v2 embeddings →
FAISS vector store → quiz generation, flashcard generation, summarization.

---

## Product concept connections

- `database-query-training` → `product-concepts/adaptive-sql-learning-platform/`
- `change-monitoring-notifications` → `product-concepts/intelligent-change-monitoring/`
- `synthetic-relational-data` → `product-concepts/synthetic-test-data-platform/`
- `modular-rag-learning` → no explicit product concept yet
