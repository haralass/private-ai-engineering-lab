# Knowledge Map

This document explains where each type of information lives in this repository,
and how problems, sources, research, and ideas connect to each other.

---

## Directory purposes

| Directory | What goes here |
|---|---|
| `sources/` | External repositories and reference code — vendored snapshots or reference-only catalogs |
| `research/` | Analysis of people, repositories, and topics — observations and patterns, not product specs |
| `business-research/` | Startup research, thematic opportunities, and structured business idea evaluation |
| `ideas/` | Personal raw business ideas — short-form inbox before formal evaluation |
| `product-concepts/` | Ideas that have survived initial evaluation — structured concept files |
| `components/` | Our own reusable code, built and tested in this repo |
| `experiments/` | Isolated test runs, runtime explorations, single-purpose trials |
| `reference-implementations/` | Adapted or reimplemented patterns from studied sources |
| `skill-library/` | Claude Code skill candidates and approved skills |
| `workflows/` | Documented multi-step processes and agent workflows |
| `docs/` | Lab-wide documentation: architecture, decisions, policies, roadmap |

---

## Problem → sources → research → ideas

### Agent safety

**Problem:** AI coding agents take irreversible actions (force push, drop table, mass delete) with no structured enforcement layer.

→ `sources/deterministic-agent-safety/` (poshan0126/dotclaude, vendored MIT)
→ `sources/structured-agent-development/` (obra/superpowers, vendored MIT)
→ `components/agent-safety-firewall/` (prototype built in Phase 1)
→ `product-concepts/agent-permission-firewall/`
→ `business-research/startup-white-spaces/agent-permission-firewall.md`

---

### Synthetic data

**Problem:** Real data can't be used in dev/test (privacy). Naive random data violates relational integrity.

→ `sources/synthetic-relational-data/` (tsembp/one-stop-ride-hail, reference-only)
→ `reference-implementations/synthetic-relational-data-generation/`
→ `product-concepts/synthetic-test-data-platform/`
→ `business-research/category-a/synthetic-regulatory-document-ai.md`

---

### Database / SQL learning

**Problem:** SQL practice platforms use toy data and don't explain errors in context.

→ `sources/database-query-training/` (tsembp/SQL-Gym, vendored MIT)
→ `reference-implementations/database-query-training-environment/`
→ `product-concepts/adaptive-sql-learning-platform/`

---

### Change monitoring and notifications

**Problem:** Tracking multiple sources (job listings, regulatory updates, pricing) requires polling, deduplication, and importance filtering.

→ `sources/change-monitoring-notifications/` (tsembp/WG-Course-Task-Notifier-Bot, reference-only)
→ `reference-implementations/monitored-source-notifications/`
→ `product-concepts/intelligent-change-monitoring/`

---

### Business energy optimization

**Problem:** Commercial buildings with solar + battery installations lack accessible dispatch optimization and ROI analysis tools.

→ `sources/business-energy-dispatch/` (sgavriil01/neura-btm-battery-dispatch, reference-only)
→ `reference-implementations/business-energy-dispatch-simulation/`
→ `product-concepts/business-energy-optimization/`

---

### Background job processing

**Problem:** Reliable async job queues are a recurring infrastructure need in most product backends.

→ `sources/asynchronous-job-processing/` (sgavriil01/sms-platform, reference-only)
→ `sources/durable-background-job-queue/` (sgavriil01/forgequeue, vendored MIT)
→ `reference-implementations/asynchronous-job-processing/`
→ `reference-implementations/durable-background-job-queue/`

---

### AI skill infrastructure

**Problem:** No standard exists for auditing, benchmarking, or verifying AI agent skills before deployment.

→ `sources/anthropic-skills/` (anthropics/skills, reference-only)
→ `sources/vercel-skills/` (vercel-labs/skills, reference-only)
→ `skill-library/`
→ `components/skill-evaluation-runner/` (stub)
→ `product-concepts/skill-benchmarking-platform/`
→ `product-concepts/trusted-skill-marketplace/`

---

### Design quality and UI systems

**Problem:** Maintaining visual and interaction quality at scale requires systematic review tools, not ad-hoc feedback.

→ `sources/design-quality-and-review/` (pbakaus/impeccable, vendored Apache-2.0)
→ `sources/design-agent-reviews/` (pbakaus/agent-reviews, vendored MIT)
→ `sources/design-taste/` (Leonxlnx/taste-skill, vendored MIT)
→ `sources/ui-ux-reference/` (nextlevelbuilder/ui-ux-pro-max-skill, vendored MIT)
→ `sources/interaction-and-motion-design/` (emilkowalski/skills, reference-only)
→ `sources/interaction-motion-toast/` (emilkowalski/sonner, vendored MIT)
→ `components/design-generation-and-review-pipeline/` (stub)

---

### Commit and code quality

**Problem:** Commit messages and PR descriptions are often low-quality or leak sensitive information.

→ `sources/privacy-safe-commit-assistant/` (sgavriil01/commitgen, reference-only)
→ `reference-implementations/privacy-safe-commit-assistant/`
→ `components/pull-request-review-orchestrator/` (stub)
→ `components/code-quality-review-agent/` (stub)

---

### Audio search and transcription

**Problem:** Finding moments in audio recordings semantically (not keyword search) is technically solved but not productized for small teams.

→ `sources/semantic-audio-search/` (sgavriil01/whisper-faiss-example, reference-only)
→ `reference-implementations/semantic-audio-search/`

---

### Model inference and streaming

**Problem:** Large language models are expensive and slow when loaded conventionally; layer-by-layer loading can reduce VRAM requirements.

→ `sources/model-layer-streaming/` (lyogavin/airllm, vendored Apache-2.0)
→ `sources/kimi-model-family/` (MoonshotAI/Kimi-K2, reference-only)
→ `sources/glm-model-family/` (zai-org/GLM-5, reference-only)
→ `sources/terminal-coding-agent/` (MoonshotAI/kimi-code, vendored MIT)
→ `experiments/terminal-coding-agent-runtime/`
→ `experiments/multi-model-comparison/`

---

### Retrieval-Augmented Generation (RAG)

**Problem:** Building production RAG pipelines requires combining chunking, embedding, retrieval, and reranking in a maintainable way.

→ `sources/modular-rag-learning/` (tsembp/AI-Study-Mate, vendored MIT)

---

### Regulatory compliance (EU AI Act, NIS2, VSME/ESG)

**Problem:** SMEs face new compliance obligations (AI Act, NIS2, VSME ESG reporting) with limited tooling.

→ `business-research/category-a/evidenceops-ai-act-nis2-vsme.md`
→ `business-research/category-a/vsme-esg-data-room.md`
→ `business-research/category-a/synthetic-regulatory-document-ai.md`

---

## Research people index

| Directory | Lab label | Repos | Research depth |
|---|---|---|---|
| `research/people/person1/` | person1 | 5 | Reference-only analysis (1 vendored, 4 metadata-only) |
| `research/people/person2/` | person2 | 6 | Code-level for 2 vendored repos |
| `research/people/person3/` | person3 | 5 | Code-level for 4/5 repos (1 vendored MIT) |
| `research/people/person4/` | person4 | 3 | Code-level for 2/3 repos (no vendored) |

---

### Appointment scheduling / SaaS infrastructure

**Problem:** Service businesses (salons, clinics, tutors) need affordable scheduling with
automated reminders and online booking pages.

→ `sources/people/person3/github/noshowly/` (person3/noshowly, vendored MIT)
→ `research/people/person3/IDEAS_DERIVED.md` (appointment SaaS for non-salon verticals)

---

### LLM structured output generation

**Problem:** LLMs generating structured JSON/YAML/XML produce outputs that violate
referential integrity and semantic constraints that JSON Schema alone cannot catch.

→ `research/people/person4/TECHNICAL_PATTERNS.md` (dual validation, repair loop, injectable client)
→ `research/people/person4/IDEAS_DERIVED.md` (generalizable structured generation framework)

---

### Cyprus electronics price comparison

**Problem:** No unified price comparison product exists for Cyprus electronics retailers.

→ `research/people/person3/` (timicy-scrapers: 6-store scraper infrastructure with MPN normalization)
→ `research/people/person3/IDEAS_DERIVED.md`

---

## Navigation shortcuts

| I want to… | Go to |
|---|---|
| Understand what's in a specific source | `sources/<name>/SOURCE.yaml` and `sources/<name>/README.md` |
| Find a business idea | `business-research/BUSINESS_IDEAS_INDEX.md` |
| See raw idea inbox | `ideas/` |
| Read a structured product concept | `product-concepts/<name>/README.md` |
| Find research on a person | `research/people/<name>/` |
| Find research on a business category | `business-research/` |
| Understand CI and safety infrastructure | `components/agent-safety-firewall/`, `docs/SECURITY_MODEL.md` |
| Understand source import policy | `docs/SOURCE_POLICY.md`, `scripts/source-management/import_source.py` |
