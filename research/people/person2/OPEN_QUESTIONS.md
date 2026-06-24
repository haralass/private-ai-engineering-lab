---
lab_label: person2
research_date: 2026-06-24
---

# person2 — Open Questions

---

## About SQL-Gym (database-query-training)

1. Are all 30+ challenges hardcoded, or is there an admin interface for adding challenges?
   The current code has them as Python dicts — is that the intended long-term approach?

2. Is the SQL injection risk in `/tables/{table_name}/sample` known and intentional
   (academic demo scope) or an oversight?

3. Is there a frontend repository not included in the lab? The `frontend/` directory
   contains a React app that was not analyzed in detail.

## About AI-Study-Mate (modular-rag-learning)

4. What LLM is used for quiz generation, flashcard generation, and summarization?
   The `quiz_generator.py`, `flashcard_generator.py`, and `summarizer.py` modules
   were not analyzed in detail — which LLM provider (OpenAI, Anthropic, local)?

5. Is there a `.env.example` or `requirements.txt` hint for the LLM provider?

6. Is the `dummy-data.txt` a real document or a test fixture?

## About reference-only repositories

7. What does the WG-Course-Task-Notifier-Bot monitor exactly — is "WG" a course
   abbreviation or a specific platform?

8. What is the scale of one-stop-ride-hail's synthetic data — one schema, or does it
   include generated data rows?

9. Is EPL231 the data structures course at the University of Cyprus?
