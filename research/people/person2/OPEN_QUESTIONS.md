---
lab_label: person2
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person2 — Open Questions

---

## Answered from code or context

### EPL231 — CLOSED

**Q: Is EPL231 the data structures course at the University of Cyprus?**
→ **Yes.** The repository name `EPL231-GroupAssignment` and the sample URLs in
`pages.txt` (UCY CS department pages) confirm this is a UCY (University of Cyprus)
EPL231 Data Structures assignment. Functional label: `data-structure-search-engine`.

### Change monitoring — CLOSED

**Q: Is the change monitoring concept already covered in the lab?**
→ **Yes.** `product-concepts/intelligent-change-monitoring/` already exists.
The WG-Course-Task-Notifier-Bot (reference-only) is the source evidence,
not a new product direction. No duplication — IDEAS_DERIVED correctly points to
the existing concept.

---

## Still open — requires upstream code review

### SQL-Gym (database-query-training)

- Are the 30+ challenges hardcoded as Python dicts, or is there an admin interface?
  (The current code has them as Python dicts — is that the intended long-term approach?)
- Is the SQL injection risk in `/tables/{table_name}/sample` known and intentional
  (academic demo scope) or an oversight?
- Is there a frontend repository separate from the `frontend/` directory in the lab?

### AI-Study-Mate (modular-rag-learning)

- What LLM provider is used for quiz generation, flashcard generation, and summarization?
  (`quiz_generator.py`, `flashcard_generator.py`, `summarizer.py` were not analyzed
  in detail — which LLM provider: OpenAI, Anthropic, local?)
- Is there a `.env.example` or `requirements.txt` hint for the LLM provider?
- Is `dummy-data.txt` a real document or a test fixture?

### Reference-only repos

- What does WG-Course-Task-Notifier-Bot monitor exactly — is "WG" a course
  abbreviation or a specific platform?
- What is the scale of one-stop-ride-hail's synthetic data — one schema, or does it
  include generated data rows?
