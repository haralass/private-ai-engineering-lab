---
lab_label: person2
research_date: 2026-06-24
---

# person2 — Ideas Derived

---

## 1. LLM-powered SQL error explainer (extension of SQL-Gym)

**Problem:** SQL-Gym evaluates queries correctly/incorrectly but provides no contextual
explanation of what went wrong or how to fix it.

**Source:** `sources/database-query-training/` (SQL-Gym, MIT)

**Pattern used:** existing /evaluate endpoint returns `{correct, feedback}` — feedback
is currently a simple string, not LLM-generated.

**What's missing:** LLM call that receives (user_sql, solution_sql, error_message,
schema_context) and returns a plain-English explanation with a correction hint.

**Potential user:** CS students learning SQL; bootcamp participants.

**Business model:** B2B to coding bootcamps, universities; or direct B2C freemium.

**Technical difficulty:** Low — one LLM call per failed evaluation.

**Assessment:** Incremental improvement, not a standalone product. Could differentiate
an adaptive SQL learning platform from generic tools.

---

## 2. Multi-document RAG study tool (extension of AI-Study-Mate)

**Problem:** AI-Study-Mate handles one document per session. Students often study
from multiple sources (lecture slides + textbook + notes).

**Source:** `sources/modular-rag-learning/` (AI-Study-Mate, MIT)

**Pattern used:** `chunk_and_embed.py` — FAISS stores can be merged across documents.

**What's missing:** multi-document FAISS index management, document-level metadata
in chunks (source tracking), and cross-document synthesis queries.

**Potential user:** University students; professional certification candidates.

**Business model:** SaaS subscription; per-seat for educational institutions.

**Technical difficulty:** Medium — requires FAISS index merging and metadata filtering.

**Assessment:** Real product gap. The existing code is a usable starting point.

---

## 3. Change monitoring as a service (based on WG notifier pattern)

**Problem:** Tracking multiple sources (job boards, regulatory documents, competitor
pricing) requires infrastructure that the WG-Course-Task-Notifier-Bot demonstrates
at small scale.

**Source:** `sources/change-monitoring-notifications/` (reference-only)

**Existing concept:** `product-concepts/intelligent-change-monitoring/`

**Assessment:** The pattern is proven. The product concept already exists in the lab.

---

## Explicit product concept connections

| Idea | Source | Existing concept |
|---|---|---|
| Adaptive SQL learning platform | database-query-training | `product-concepts/adaptive-sql-learning-platform/` |
| Intelligent change monitoring | change-monitoring-notifications | `product-concepts/intelligent-change-monitoring/` |
| Synthetic test data | synthetic-relational-data | `product-concepts/synthetic-test-data-platform/` |
| Multi-document RAG study tool | modular-rag-learning | None — candidate for new concept |
