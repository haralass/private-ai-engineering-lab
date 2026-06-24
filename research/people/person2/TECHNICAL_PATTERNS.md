---
lab_label: person2
research_date: 2026-06-24
sources_with_full_code: [database-query-training, modular-rag-learning]
sources_reference_only: [change-monitoring-notifications, synthetic-relational-data, algorithm-benchmarking, data-structure-search-engine]
---

# person2 — Technical Patterns

---

## Pattern 1 — SQL challenge evaluation engine

**Source:** `sources/database-query-training/upstream/` (vendored, MIT)

The evaluation loop:
1. Accept user SQL string via POST /evaluate
2. Execute user SQL against live SQLite DB with `sqlite3.connect()`
3. Execute reference solution SQL against same DB
4. Load both results into pandas DataFrames
5. Compare: column names match + row content match (order-insensitive)
6. Return `{correct: bool, feedback: str}`

Challenge definition format:
```python
{
    "id": int,
    "title": str,
    "prompt": str,
    "difficulty": "BASIC" | "INTERMEDIATE" | "ADVANCED",
    "solution_sql": str,
    "expected_columns": [str],
    "hints": [str],
    "example_data": {table_name: [{col: val}]}
}
```

**Reuse:** This challenge schema and evaluation loop can be extracted directly for any
SQL learning product. The pandas comparison pattern handles ordering correctly.

**Risk:** `sqlite3.connect(DB_PATH)` with user-provided SQL — no sandboxing.
Any SQL that modifies data or reads from system tables is accepted.

---

## Pattern 2 — Modular RAG pipeline (local embeddings + FAISS)

**Source:** `sources/modular-rag-learning/upstream/` (vendored, MIT)

Pipeline stages:
```
PDF file → LangChain document loader → RecursiveCharacterTextSplitter(500, 100)
→ HuggingFaceEmbeddings("all-MiniLM-L6-v2") → FAISS.from_documents()
→ FAISS index saved to disk → similarity_search() for retrieval
```

Key characteristics:
- Embeddings are local (HuggingFace, no API cost)
- FAISS index persisted locally under `db/`
- Chunking is fixed-size (not semantic)
- Single document at a time (session-scoped)

**Reuse:** `chunk_and_embed.py` is a clean, self-contained function pair:
- `chunk_documents(documents, chunk_size=500, chunk_overlap=100)` → chunks
- `embed_and_store(chunks, persist_path="db")` → FAISS vector DB

These can be dropped into any RAG application as a starting point.

---

## Pattern 3 — Streamlit session state for multi-step workflows

**Source:** `sources/modular-rag-learning/upstream/` (vendored, MIT)

app.py initializes 12 session state keys to manage a multi-step workflow:
document upload → processing → mode selection → content generation.

The pattern handles: re-uploads (filename comparison), mode persistence, and
sequential state advancement. Reusable for any Streamlit prototype with multi-step flow.

---

## Pattern 4 — Telegram change-notification bot (README-inferred)

**Source:** `sources/change-monitoring-notifications/` (reference-only)

Monitors a course task portal via periodic HTTP polling, diffs the response,
and sends Telegram messages on changes. The pattern (poll → diff → notify) is
directly applicable to: job listing monitors, price trackers, regulatory update watchers.

---

## Reuse summary

| Pattern | Usability | License |
|---|---|---|
| SQL challenge schema + FastAPI eval loop | Direct copy | MIT |
| chunk_and_embed.py (FAISS + HuggingFace) | Direct copy | MIT |
| Streamlit session state multi-step pattern | Study/adapt | MIT |
| Telegram poll-diff-notify | Clean-room reimplementation | No license |
