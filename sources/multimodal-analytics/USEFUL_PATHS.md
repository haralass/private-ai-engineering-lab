# Useful Path Index - multimodal-analytics

## Source Record

- Upstream: [mitdbg/multimodal-analytics](https://github.com/mitdbg/multimodal-analytics)
- Pinned commit: `de8fd18e20bbbcd13786dc20841cb3ea859b5335`
- Default branch inspected: `main`
- Date inspected: 2026-06-27
- License status: `MIT`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `systems/reasoners/rag_reasoner.py` - reference for composing retrieval, prompting, and answer generation behind a reasoner interface.
- `systems/retrievers/rag_retriever.py` - retrieval boundary pattern that keeps indexing/query logic separate from evaluation.
- `systems/chunkers/markdown_chunker.py` and `systems/chunkers/pdf_chunker.py` - file-type-specific chunker pattern under a common abstraction.
- `systems/evaluators/f1_evaluator.py` and `systems/evaluators/llm_evaluator.py` - mixed deterministic/LLM evaluation interfaces.
- `systems/storage/faiss_storage.py` and `systems/storage/sqlite_storage.py` - swappable storage adapters for vector and relational persistence.
- `evaluate.py` - experiment runner shape for producing comparable evaluation outputs.

## Risks And Limitations

- Data archives and generated outputs should stay local unless explicitly curated.
- `.envexample` exists upstream; any future vendoring would still require a fresh secret scan.
