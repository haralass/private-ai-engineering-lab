# Open Questions — Stylianos Gavriil repositories

These questions cannot be answered from the existing lab content alone.
Each requires either direct code review of upstream, or user clarification.

status: needs-user-input

---

## About the repositories

1. **sms-platform (asynchronous-job-processing)**
   - What is the stack? (Python/Node/Go/other?)
   - What job types does it handle? (SMS only, or generic queue?)
   - Is there a retry/dead-letter queue mechanism?
   - What database does it use for persistence?

2. **neura-btm-battery-dispatch (business-energy-dispatch)**
   - What optimization algorithm is used? (LP, MILP, heuristic, rule-based?)
   - What is the input data format for PV production and load profiles?
   - What tariff models does it support?
   - Is there an existing validation dataset or real deployment data?

3. **whisper-faiss-example (semantic-audio-search)**
   - What Whisper model size does it use?
   - How is audio segmented before indexing? (fixed window? VAD-based?)
   - Is the FAISS index persisted to disk or rebuilt each run?
   - What is the query interface? (CLI? API? web UI?)

4. **forgequeue (durable-background-job-queue)**
   - What database does it use for durability? (PostgreSQL? Redis? SQLite?)
   - Does it support priorities and scheduling (cron-style)?
   - Is there a retry mechanism with backoff?
   - What is the deployment model? (library? standalone service?)
   - Has the code been reviewed beyond initial cataloguing?

5. **commitgen (privacy-safe-commit-assistant)**
   - Does it use an LLM, templates, or a combination?
   - What "privacy-safe" mechanism does it apply (if any)?
   - Does it output conventional commits format?
   - What diff format does it accept as input?

---

## About potential next steps

6. Are any of these repositories expected to be licensed in the future?
   (Would unlock vendoring for sms-platform, neura-btm-battery-dispatch,
   whisper-faiss-example, commitgen)

7. Is there interest in a production-ready version of forgequeue's patterns
   in `components/` or `reference-implementations/`?

8. Is business-energy-optimization the only product concept intended to derive
   from these repositories, or are there others planned?
