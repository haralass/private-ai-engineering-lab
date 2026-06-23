# Source Research Dossier: modular-rag-learning

## Repository identity

- **Name:** AI-Study-Mate (stud(ai))
- **Creator:** tsembp (student developer; built before Summer 2025 internship at CYENS Centre of Excellence)
- **GitHub URL:** https://github.com/tsembp/AI-Study-Mate
- **Source path:** sources/modular-rag-learning/upstream/
- **License:** MIT (verified — sources/modular-rag-learning/upstream/LICENSE)
- **Import type:** Vendored snapshot
- **Pinned commit:** 0e9fc7fd69b3aa0f993f09b09a54381865a28d1b
- **Retrieved at:** 2026-06-22T05:12:45Z

---

## What it actually does

AI-Study-Mate is a Streamlit web application that lets students upload PDF or DOCX study documents and then interact with that content through four modes: Flashcard generation, Multiple-choice quiz generation, Ask-me-anything Q&A, and Document summarization with PDF export. The document is chunked, embedded using `all-MiniLM-L6-v2` (HuggingFace Sentence Transformers), and stored in a FAISS local vector store. On each feature invocation, the top-5 most relevant chunks are retrieved and passed as context to OpenAI `gpt-3.5-turbo` via LangChain prompt chains, which generates structured JSON output (flashcards/MCQs) or Markdown prose (summaries). The summarizer additionally renders the LLM output as a downloadable PDF via ReportLab.

---

## Architecture

```
Streamlit frontend (app.py)
  ├── File upload → document_loader.py (PyPDF / UnstructuredWordDocument)
  ├── Chunking + embedding → chunk_and_embed.py
  │     RecursiveCharacterTextSplitter(500 chars, 100 overlap)
  │     HuggingFaceEmbeddings("all-MiniLM-L6-v2")
  │     FAISS.from_documents() → saved to local ./db/
  │
  └── Study mode dispatch (session_state.selected_mode):
        flashcards  → flashcard_generator.py  → ChatOpenAI (gpt-3.5-turbo)
        quiz        → quiz_generator.py       → ChatOpenAI (gpt-3.5-turbo)
        ask         → (UI only, not yet wired to LLM in vendored snapshot)
        summarize   → summarizer.py           → ChatOpenAI (gpt-3.5-turbo) + ReportLab
```

The entire application is single-process, stateless between Streamlit rerenders, and uses `st.session_state` for UI state management. The FAISS index is saved to a local `./db/` directory and reloaded from disk on subsequent runs if the same file is re-uploaded.

---

## Main modules and important files

| Path | Purpose |
|------|---------|
| `app.py` | Main Streamlit app; file upload handler, UI routing, session state |
| `src/document_loader.py` | Loads PDF (PyPDFLoader) or DOCX (UnstructuredWordDocumentLoader) into LangChain Documents |
| `src/chunk_and_embed.py` | RecursiveCharacterTextSplitter + HuggingFaceEmbeddings + FAISS store/persist |
| `src/flashcard_generator.py` | Retrieves top-5 chunks → ChatOpenAI → JSON list of `{front, back}` objects |
| `src/quiz_generator.py` | Retrieves top-5 chunks → ChatOpenAI → JSON list of `{question, options{A/B/C/D}, correct_option}` |
| `src/summarizer.py` | Retrieves top-5 chunks → ChatOpenAI → Markdown summary → ReportLab PDF |
| `requirements.txt` | Dependencies: streamlit, langchain, openai, faiss-cpu, sentence-transformers, reportlab, etc. |
| `assets/` | App banner image |
| `dummy-data.txt` | Sample study content for testing |

---

## Core technical patterns

**1. Retrieval-Augmented Generation (RAG) pipeline** (`src/chunk_and_embed.py`, `src/flashcard_generator.py`)
Documents are chunked with 100-character overlap, embedded with a local HuggingFace model, stored in FAISS, then retrieved at query time via `vector_db.as_retriever(search_kwargs={"k": 5})`. The retriever uses a fixed generic query string ("generate comprehensive flashcards") rather than a user-specific semantic query — this is a simplified pattern more suited to summarization tasks than precision retrieval.

**2. Structured JSON output via prompt engineering** (`src/flashcard_generator.py`, `src/quiz_generator.py`)
Both generators use a `ChatPromptTemplate` with a `SystemMessagePromptTemplate` that specifies exact JSON output format. The response is parsed directly via `json.loads(response.content.strip())` — no validation or retry logic is present. This works reliably with `gpt-3.5-turbo` at low temperature (0.5) but will fail on malformed output.

**3. Streamlit session state as application state manager** (`app.py`)
The application uses `st.session_state` to persist `vector_db`, `flashcards`, `questions`, `summary`, and UI state (current card index, show/hide answer) across Streamlit rerenders. This is standard Streamlit practice; the state is in-memory per session and not persisted to disk.

**4. Markdown-to-PDF rendering** (`src/summarizer.py`)
LLM-generated Markdown is parsed line-by-line: lines starting with `#` become `Heading2`, all-uppercase lines become `Heading3`, others become `Normal`. This is a custom lightweight Markdown renderer using ReportLab's `Paragraph`/`Spacer` components. It does not handle lists, bold, italic, or tables.

---

## Novel or interesting mechanisms

There are no novel mechanisms. The project is a well-structured but introductory-level implementation of a standard RAG + LLM pipeline for education. Its value is in demonstrating a clean, modular decomposition of the problem space: document loading, chunking/embedding, retrieval, and generation are all separate modules with clear interfaces.

The Streamlit-based interactive flashcard navigation (prev/next/show-answer callbacks) is a clean UX pattern for card-based learning interfaces, though it uses standard Streamlit components.

---

## Data flow

```
User uploads PDF/DOCX
  → load_document(file_path) → list[LangChain Document]
  → chunk_documents(docs) → list[Document chunks, ~500 chars each]
  → embed_and_store(chunks) → FAISS vector store (in-memory + persisted to ./db/)
  → st.session_state.vector_db = vector_db

User clicks "Generate Flashcards" (n=8):
  → retriever = vector_db.as_retriever(k=5)
  → docs = retriever.get_relevant_documents("generate comprehensive flashcards")
  → content = " ".join([doc.page_content for doc in docs])
  → ChatOpenAI (gpt-3.5-turbo, temp=0.5).invoke(prompt + content)
  → json.loads(response.content) → list[{front, back}]
  → st.session_state.flashcards = flashcards → rendered as Streamlit card UI
```

---

## Dependencies

- **streamlit** — UI framework
- **langchain**, **langchain-openai**, **langchain-huggingface**, **langchain-community** — LLM chain and document processing orchestration
- **openai** — ChatOpenAI (gpt-3.5-turbo) via LangChain
- **faiss-cpu** — Local vector similarity search
- **sentence-transformers** — `all-MiniLM-L6-v2` embedding model (runs locally, no API key required)
- **torch** — Required by sentence-transformers; JIT profiling disabled at startup
- **PyPDF2**, **pymupdf** — PDF loading
- **unstructured**, **docx2txt** — DOCX loading
- **reportlab** — PDF generation for summaries
- **chromadb** — Listed in requirements.txt but NOT used in vendored source; [inference: was used in an earlier version or was planned but not implemented in this snapshot]
- **tiktoken** — Token counting utility; not visibly used in vendored source [inference: used by LangChain internals or was planned for token budget awareness]
- **python-dotenv** — API key loading from `.env`
- **beautifulsoup4** — Not visibly used; [inference: leftover from a scraped-content feature that was not implemented or was removed]

---

## Security model

- OpenAI API key is loaded from a `.env` file via `python-dotenv`. No key management beyond this.
- Uploaded files are written to a local `./data/` directory and persist on disk across sessions.
- No authentication, authorization, or rate limiting on the Streamlit interface — the app assumes a trusted single-user local environment.
- No input sanitization on file uploads beyond the file type check (`.pdf` or `.docx` by extension).
- `requirements.txt` does not pin versions, creating dependency risk.

---

## Testing strategy

No test files are present in the vendored snapshot. No testing framework is configured. This is consistent with the project's stated purpose as a learning project built for a single developer's pre-internship upskilling.

---

## Genuinely reusable elements

**1. Clean modular RAG pipeline decomposition** (`src/`)
The five-module structure (loader, chunker/embedder, flashcard gen, quiz gen, summarizer) is a textbook-clean separation of concerns for a document-based RAG application. MIT license — adaptation is unrestricted.

**2. Structured JSON output prompt pattern** (`src/flashcard_generator.py`, `src/quiz_generator.py`)
The prompt template that enforces JSON output format and the direct `json.loads()` parse pattern is a minimal, functional pattern for structured LLM output without the overhead of Pydantic or LangChain output parsers.

**3. FAISS persist-and-reload pattern** (`src/chunk_and_embed.py`)
The `FAISS.save_local(persist_path)` pattern with `os.makedirs` guard is a portable local vector store pattern that eliminates the need for a running vector DB service.

**4. MCQ data schema** (`src/quiz_generator.py`)
The `{question, options: {A, B, C, D}, correct_option}` JSON schema is a clean, minimal quiz data model suitable for adaptation into any learning application.

---

## What NOT to reuse

- The fixed generic retrieval query strings ("generate comprehensive flashcards", "generate comprehensive multiple choice questions") — these bypass semantic retrieval and return the same chunks regardless of what the user actually wants to learn. Any production system needs user-intent-aware retrieval.
- The `json.loads(response.content.strip())` with no error handling — will raise `json.JSONDecodeError` on malformed LLM output. Needs retry logic or Pydantic validation.
- The simple line-by-line Markdown-to-PDF renderer in `summarizer.py` — handles only 3 cases; will produce incorrect output for documents with lists, code blocks, or tables.
- The `chromadb` and `beautifulsoup4` requirements that are not used — dead dependencies.
- The "Ask Me" mode — the UI button exists but the feature is not implemented in the vendored snapshot (the mode branch is present in `app.py` but calls no LLM).
- Unpinned `requirements.txt` — dependency versions are uncontrolled.

---

## Production-readiness

**Prototype.**

This is explicitly a learning project by a student developer. It lacks: error handling on LLM output parsing, test coverage, authentication, input validation beyond file type, pinned dependencies, deployment configuration, and a working Q&A mode. The FAISS index is stored to `./db/` relative to the working directory — a server deployment would collide across users.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- Very clean module decomposition for the problem domain — each feature is self-contained
- Uses a local embedding model (`all-MiniLM-L6-v2`) so document content never leaves the user's machine at embedding time (only at LLM generation time via OpenAI API)
- MIT license — maximum reuse freedom
- Compact codebase (~350 lines total across all modules) — easy to understand, fork, and extend

**Weaknesses:**
- No error handling on JSON parsing — will crash on malformed LLM output
- Fixed retrieval queries return the same chunks regardless of the user's intent
- The "Ask Me" feature is not implemented — the most natural use case for a study assistant is missing
- No session persistence — a browser refresh loses all state
- Single-user assumption — FAISS index stored to a shared `./db/` path would break in multi-user deployment
- Hard-coded to `gpt-3.5-turbo` — no model configurability

**Technical debt:**
- Dead dependencies (`chromadb`, `beautifulsoup4`, `tiktoken`) suggest the requirements.txt was never cleaned up
- `retriever.get_relevant_documents()` is deprecated in newer LangChain versions (the `summarizer.py` already handles this with a try/except for `.invoke()` vs `.get_relevant_documents()`, but the other modules do not)

---

## Novel or differentiated elements

None that are technically novel. The value is in the modular structure and completeness as a reference implementation. Among RAG education tools, this is a minimal viable pattern rather than a differentiated system.

---

## Possible clean-room adaptations

- **Document-to-flashcard microservice** — Extract the chunking, embedding, and flashcard/quiz generation pipeline as a standalone REST API (FastAPI). Clients (web, mobile, Claude Code skill) can POST a document and receive structured learning content. Replace fixed retrieval queries with user-intent embeddings.
- **Multi-document knowledge base for a study assistant** — Extend `embed_and_store` to accumulate multiple documents into one FAISS index (with document-level metadata for source attribution), building toward a personal study knowledge base.
- **Model-agnostic adapter layer** — Replace the hard-coded `ChatOpenAI(model="gpt-3.5-turbo")` with an adapter pattern (similar to claude-mem's `PlatformAdapter`) so the generation backend can be swapped to Claude, Gemini, or a local model without code changes.
- **Spaced repetition layer on top of flashcards** — The `{front, back}` flashcard schema is compatible with SM-2 (Anki-style) spaced repetition. Adding a persistence layer (SQLite) and a repetition scheduler on top of the existing generator produces a meaningfully differentiated learning product.

---

## Business applications

1. **Personal study assistant for professional certifications** — Professionals studying for certifications (AWS, PMP, bar exam, medical licensing) upload their study materials and get custom flashcard decks and practice tests without manual content authoring. The FAISS local storage ensures sensitive materials (legal documents, medical notes) are not sent to a cloud vector DB.
2. **University lecture-to-quiz pipeline** — Professors upload lecture PDFs and receive ready-to-use MCQ question banks for LMS platforms (Canvas, Moodle). The structured JSON output maps cleanly to LMS question import formats.
3. **Corporate L&D knowledge extraction** — HR/L&D teams upload policy documents, onboarding materials, or compliance documentation and auto-generate assessments for employee training.
4. **Tutoring service backend** — A tutoring platform can use this pipeline as the back-end for AI-generated homework help: students upload textbook chapters and receive context-aware explanations and practice problems.

---

## Related business ideas in this lab

- Claude-mem (`sources/persistent-agent-memory/`) + this RAG pipeline: a study assistant that remembers what a student has already studied across sessions, adapting new flashcard generation to avoid redundancy.
- The anthropic-skills and vercel-skills skill patterns could be used to expose the flashcard/quiz generation as Claude Code skills for developers building learning content.

---

## Related sources in this lab

- `sources/persistent-agent-memory/` — claude-mem uses Chroma for vector search; this project uses FAISS. Both implement the embed-store-retrieve pattern; the two approaches are complementary study material.
- `sources/anthropic-skills/` — The flashcard/quiz generation logic could be packaged as an Anthropic-style skill.

---

## Open questions

1. Why is `chromadb` in `requirements.txt` but unused? Was Chroma originally used and replaced by FAISS, or was it added speculatively?
2. What was the intended implementation for the "Ask Me" mode? The UI branch exists but calls no LLM. Was it meant to use a conversational chain with chat history?
3. How does the `all-MiniLM-L6-v2` embedding quality compare to text-embedding-3-small (OpenAI) for educational content retrieval? For a production version, which embedding model produces better flashcard relevance?
4. The `torch._C._jit_set_profiling_mode(False)` call at startup — what issue was this working around? Is it still needed with current PyTorch versions?

---

## Final research conclusion

AI-Study-Mate is a clean, minimal, MIT-licensed reference implementation of a document-based RAG pipeline for educational content generation. Its primary value to this lab is as a structural template: the five-module decomposition (load → chunk/embed → generate per-mode) is a sound architecture for any document-to-learning-content product. The code itself is prototype-quality and requires significant hardening (error handling, model abstraction, persistence, authentication) before production use. The MIT license imposes no restrictions on adaptation. The highest-value clean-room target is the modular pipeline decomposition applied to a more robust backend with user-intent-aware retrieval, structured output validation, and a spaced repetition layer.
