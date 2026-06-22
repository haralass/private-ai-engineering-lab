# Decision Log

## 2026-06-22 — Repository structure: functional naming over author naming

Decision: Top-level source folders named by function (`deterministic-agent-safety/`) not by author (`poshan0126/`).

Reason: Author-named folders make the repository dependent on who created the code rather than what it does. When we build components, we want to find "async job queue patterns" not "sgavriil01 work".

Student contributors are labelled `student-1` and `student-2` in `SOURCE.yaml` metadata only.

## 2026-06-22 — First components are report-only

Decision: All agent components in Phase 1 default to `report-only` mode.

Reason: Report-only ensures no irreversible action happens without a human seeing it first. Trust is built incrementally through observed behavior before enabling automatic edits, commits, or pushes.

## 2026-06-22 — Vendored snapshots preferred over submodules

Decision: Import sources as vendored snapshots (copy of code at a pinned commit) rather than Git submodules unless the repository is very large.

Reason: Vendored snapshots are fully searchable by the AI, work offline, and do not disappear if the upstream repository is deleted or made private. Submodules require network access and can break.

## 2026-06-22 — Model weights never committed

Decision: Model weights (.safetensors, .gguf, .pt, etc.) are never stored in this repository.

Reason: Binary files bloat git history permanently, are often gigabytes in size, and provide no benefit over a manifest pointing to the official download location.

## 2026-06-22 — barkod.studio: reference-only, no code copied

Decision: The functional generative design concept (inspired by barkod.studio) is documented as a product concept only. No proprietary code or assets copied.

Reason: No confirmed open-source repository exists. Copying proprietary design work without a license would be a legal and ethical violation.
