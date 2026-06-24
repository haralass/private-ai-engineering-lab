---
lab_label: person2
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person2 — Open Questions

Evidence labels used in this file:
- `confirmed-from-code` — verified in vendored upstream/ or directly read files
- `confirmed-from-documentation` — verified from README, docs, or comments in the repo
- `strong-inference` — high-confidence conclusion from indirect evidence; not confirmed
- `hypothesis` — plausible but speculative; requires verification
- `still-open` — cannot be answered from available lab content

Only `confirmed-from-code` and `confirmed-from-documentation` are treated as CLOSED.

---

## Confirmed [CLOSED]

**Change monitoring — existing concept:** `confirmed-from-code` — `product-concepts/intelligent-change-monitoring/`
already exists in the lab. The WG-Course-Task-Notifier-Bot (reference-only) is source
evidence for that concept. No duplication — `IDEAS_DERIVED.md` correctly points to
the existing concept.

---

## Inferences requiring confirmation

**EPL231 — UCY Data Structures course:** `strong-inference` — The repository name
`EPL231-GroupAssignment` and the presence of UCY CS department URLs in the sample data
strongly suggest this is a UCY (University of Cyprus) EPL231 Data Structures assignment.
No explicit course specification or README confirmation was found at the pinned commit.
Not confirmed from direct code review (reference-only source, no code in lab).

---

## Still open — requires upstream code review or user input

### SQL-Gym (database-query-training) [`still-open`]

- Are the 30+ challenges hardcoded as Python dicts, or is there an admin interface?
  (The current code has them as Python dicts — is that the intended long-term approach?)
- Is the SQL injection risk in `/tables/{table_name}/sample` known and intentional
  (academic demo scope) or an oversight?
- Is there a frontend repository separate from the `frontend/` directory in the lab?

### AI-Study-Mate (modular-rag-learning) [`still-open`]

- What LLM provider is used for quiz generation, flashcard generation, and summarization?
  (`quiz_generator.py`, `flashcard_generator.py`, `summarizer.py` were not analyzed
  in detail — which LLM provider: OpenAI, Anthropic, local?)
- Is there a `.env.example` or `requirements.txt` hint for the LLM provider?
- Is `dummy-data.txt` a real document or a test fixture?

### Reference-only repos [`still-open`]

- What does WG-Course-Task-Notifier-Bot monitor exactly — is "WG" a course
  abbreviation or a specific platform?
- What is the scale of one-stop-ride-hail's synthetic data — one schema, or does it
  include generated data rows?
- Is EPL231 confirmed as the UCY Data Structures course? (currently `strong-inference`)
