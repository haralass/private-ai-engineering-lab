# Knowledge Map

This document explains where each type of information lives in this repository and how sources, research, product ideas, validation, and implementation work connect.

---

## Directory Purposes

| Directory | What goes here |
|---|---|
| `sources/` | Source records: vendored snapshots, selected subsystems, local-research-only dossiers, and reference-only metadata |
| `source-catalog/` | Cross-source catalog metadata, import status, and license matrix |
| `research/` | Analysis of people, repositories, public code, and topics |
| `research/public-code-library/` | Public-code research library: manifest, dossiers, synthesis, candidates, raw evidence |
| `business-research/` | Startup research, thematic opportunities, and structured business idea evaluation |
| `ideas/` | Personal raw business ideas before formal evaluation |
| `product-concepts/` | Ideas that survived initial evaluation and have structured concept files |
| `components/` | Our own reusable code, built and tested in this repo |
| `experiments/` | Isolated runtime explorations and single-purpose trials |
| `reference-implementations/` | Adapted or reimplemented patterns from studied sources |
| `skill-library/` | AI skill candidates, approved skills, rejected skills, and evaluation notes |
| `workflows/` | Documented multi-step processes and agent workflows |
| `scripts/validation/` | Validators for source records, catalog consistency, and public-code-library integrity |
| `docs/` | Lab-wide documentation: current state, architecture, decisions, policies, roadmap |

---

## Public-Code Library Map

| Path | Purpose |
|---|---|
| `research/public-code-library/README.md` | Public-code-library overview and active counts |
| `research/public-code-library/manifest.yaml` | Canonical 68-repository research manifest |
| `research/public-code-library/candidate-pool.yaml` | 107 candidate entries with controlled statuses and actions |
| `research/public-code-library/repositories/` | 48 full repository dossiers |
| `research/public-code-library/synthesis/` | Cross-repo synthesis: top assets, shortlist, licensing, rejected repos, useful assets |
| `research/public-code-library/professional-websites/` | Professional website catalogue and verified live/source pairs |
| `research/public-code-library/rejected/` | Rejected and unverified notes retained outside active synthesis |
| `research/public-code-library/data/raw/` | Raw JSON evidence preserved for traceability |
| `research/public-code-library/archive/` | Historical reports and superseded documents |

Validation entrypoint: `scripts/validation/validate_public_code_library.py`.

---

## Source Catalog Map

| Path | Purpose |
|---|---|
| `source-catalog/sources.yaml` | Source registry keyed by functional source name |
| `source-catalog/import-status.yaml` | Import mode, decision, and source-level status |
| `source-catalog/license-matrix.yaml` | License evidence, review state, and copy/vendoring decisions |
| `sources/**/SOURCE.yaml` | Per-source canonical source manifest |
| `sources/**/ATTRIBUTION.md` | Attribution and source URL record |
| `sources/**/AUDIT.md` | Vendored/selected-source audit notes |
| `sources/**/FILE_MANIFEST.json` | Vendored/selected-source file manifest |
| `sources/**/USEFUL_PATHS.md` | Local-research-only useful path notes |

Validation entrypoints:

```bash
python scripts/validation/validate_source_manifests.py
python scripts/validation/validate_catalog_consistency.py
```

---

## Problem → Sources → Research → Ideas

### Agent Safety

**Problem:** AI coding agents take irreversible actions without structured enforcement.

→ `sources/deterministic-agent-safety/` (vendored MIT)  
→ `sources/structured-agent-development/` (vendored MIT)  
→ `components/agent-safety-firewall/`  
→ `product-concepts/agent-permission-firewall/`  
→ `business-research/startup-white-spaces/agent-permission-firewall.md`

### Public Code And Professional Websites

**Problem:** High-quality public implementations contain useful architecture, interaction, and content patterns, but licensing and provenance need strict separation from inspiration.

→ `research/public-code-library/repositories/`  
→ `research/public-code-library/synthesis/top-30-lab-assets.md`  
→ `research/public-code-library/synthesis/licensing-and-provenance.md`  
→ `research/public-code-library/professional-websites/catalogue.md`  
→ `research/public-code-library/professional-websites/verified-live-source-pairs.md`

### Business And Product Research

**Problem:** Product ideas need a trail from observed technical patterns to market hypotheses and risk analysis.

→ `business-research/`  
→ `ideas/`  
→ `product-concepts/`  
→ relevant `sources/` and `research/` dossiers

### Synthetic Data

**Problem:** Real data cannot be used safely in dev/test, and naive random data violates relational integrity.

→ `sources/synthetic-relational-data/` (reference-only)  
→ `reference-implementations/synthetic-relational-data-generation/`  
→ `product-concepts/synthetic-test-data-platform/`

### Database / SQL Learning

**Problem:** SQL practice platforms often use toy data and weak feedback loops.

→ `sources/database-query-training/` (vendored MIT)  
→ `reference-implementations/database-query-training-environment/`  
→ `product-concepts/adaptive-sql-learning-platform/`

### Change Monitoring And Notifications

**Problem:** Tracking many sources requires polling, deduplication, and importance filtering.

→ `sources/change-monitoring-notifications/` (reference-only)  
→ `reference-implementations/monitored-source-notifications/`  
→ `product-concepts/intelligent-change-monitoring/`

### Business Energy Optimization

**Problem:** Commercial buildings with solar and batteries need accessible dispatch optimization and ROI analysis.

→ `sources/business-energy-dispatch/` (reference-only)  
→ `reference-implementations/business-energy-dispatch-simulation/`  
→ `product-concepts/business-energy-optimization/`

### Background Job Processing

**Problem:** Reliable async job queues are a recurring backend infrastructure need.

→ `sources/asynchronous-job-processing/` (reference-only)  
→ `sources/durable-background-job-queue/` (vendored MIT)  
→ `reference-implementations/asynchronous-job-processing/`  
→ `reference-implementations/durable-background-job-queue/`

### AI Skill Infrastructure

**Problem:** AI agent skills need auditing, benchmarking, and verification before deployment.

→ `sources/anthropic-skills/` (reference-only)  
→ `sources/vercel-skills/` (reference-only)  
→ `skill-library/`  
→ `components/skill-evaluation-runner/`  
→ `product-concepts/skill-benchmarking-platform/`  
→ `product-concepts/trusted-skill-marketplace/`

### Design Quality And UI Systems

**Problem:** Maintaining visual and interaction quality at scale needs systematic review tools.

→ `sources/design-quality-and-review/` (vendored Apache-2.0)  
→ `sources/design-agent-reviews/` (vendored MIT)  
→ `sources/design-taste/` (vendored MIT)  
→ `sources/ui-ux-reference/` (vendored MIT)  
→ `sources/interaction-and-motion-design/` (reference-only)  
→ `sources/interaction-motion-toast/` (selected subsystem, MIT)  
→ `research/public-code-library/repositories/`  
→ `components/design-generation-and-review-pipeline/`

### Model Inference And Streaming

**Problem:** Large models are expensive and slow when loaded conventionally; layer-by-layer loading can reduce VRAM requirements.

→ `sources/model-layer-streaming/` (vendored Apache-2.0)  
→ `sources/kimi-model-family/` (reference-only)  
→ `sources/glm-model-family/` (reference-only)  
→ `sources/terminal-coding-agent/` (vendored MIT)  
→ `experiments/terminal-coding-agent-runtime/`

---

## Research People Index

| Directory | Lab label | Repos | Research depth |
|---|---|---:|---|
| `research/people/person1/` | person1 | 5 | Reference-heavy analysis; 1 vendored source |
| `research/people/person2/` | person2 | 6 | Code-level for 2 vendored repos |
| `research/people/person3/` | person3 | 5 | Code-level/local research for 4 repos; 1 vendored source |
| `research/people/person4/` | person4 | 3 | Code-level/local research for 2 repos; no vendored source |

---

## Navigation Shortcuts

| I want to… | Go to |
|---|---|
| See the canonical current status | `docs/CURRENT_STATE.md` |
| Understand source import rules | `docs/SOURCE_POLICY.md` |
| Inspect source metadata | `sources/<name>/SOURCE.yaml` and `source-catalog/` |
| Find public-code repository dossiers | `research/public-code-library/repositories/` |
| Read public-code synthesis | `research/public-code-library/synthesis/` |
| Check professional website research | `research/public-code-library/professional-websites/` |
| Inspect rejected/unverified public-code items | `research/public-code-library/rejected/` |
| See raw public-code evidence | `research/public-code-library/data/raw/` |
| Find business research | `business-research/` |
| Read product concepts | `product-concepts/` |
| Understand CI and safety infrastructure | `components/agent-safety-firewall/`, `docs/SECURITY_MODEL.md`, `.github/workflows/ci.yml` |
