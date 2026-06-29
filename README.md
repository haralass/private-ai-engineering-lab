# Private Engineering Research Lab

Personal engineering and research laboratory for studying public code, product patterns, professional websites, business ideas, AI systems, and reusable implementation techniques.

This is **not a production monorepo**. It is a private research lab used to preserve source metadata, document implementation patterns, run small experiments, and incubate ideas. Codex may use the research material freely for private lab prototypes, while the metadata keeps track of what would need review or rewriting before public/commercial release.

---

## Purpose

| Goal | Description |
|---|---|
| Source governance | Audited source records with explicit import modes, license review, attribution, and validation |
| Public code research | Structured dossiers, candidate rankings, licensing notes, and reusable pattern summaries |
| Professional website research | Live/source pairs and professional site implementation patterns |
| Business and product research | Opportunity notes, product concepts, risks, and validation plans |
| Component library | Reusable, tested building blocks adapted from studied patterns or written in-house |
| Experiment environment | Isolated experiments with real implementations |
| AI context base | Searchable research and source metadata for future AI-assisted work |

---

## Current Counts

| Area | Count |
|---|---:|
| Source records | 50 |
| Vendored snapshots | 16 |
| Selected subsystems | 1 |
| Local-research-only sources | 17 |
| Reference-only sources | 16 |
| Public-code manifest repositories | 68 |
| Public-code full dossiers | 48 |
| Public-code summary-only entries | 20 |
| Public-code candidate entries | 107 |

Canonical current status lives in `docs/CURRENT_STATE.md`.

---

## Structure At A Glance

```
sources/                         Source records: vendored, selected, local-research-only, or reference-only
source-catalog/                  Cross-source import status, license matrix, and catalog metadata
research/public-code-library/    Public code research library
research/people/                 Person-scoped repository research
business-research/               Startup research, thematic opportunities, and business idea evaluation
ideas/                           Raw idea inbox before formal spec
product-concepts/                Structured product concepts and validation notes
components/                      Our own reusable components
reference-implementations/       Clean builds or reimplementations of studied patterns
experiments/                     Isolated runtime explorations and trials
workflows/                       Process guides for recurring work
skill-library/                   Candidate, approved, and rejected AI skills
model-and-agent-evaluations/     Benchmark results and evaluation datasets
templates/                       Reusable document and YAML templates
scripts/                         Utility scripts, CI helpers, and validators
tests/                           Component, integration, and security tests
docs/                            Architecture, current state, decisions, policies, roadmap
```

---

## Public Code Library

The public-code research collection lives in `research/public-code-library/`.

| Path | Purpose |
|---|---|
| `manifest.yaml` | Canonical list of 68 researched repositories |
| `repositories/` | 48 full repository dossiers |
| `synthesis/` | Top assets, shortlist, licensing/provenance, and cross-repo summaries |
| `professional-websites/` | Professional website catalogue and verified live/source pairs |
| `rejected/` | Rejected and unverified research notes |
| `data/raw/` | Raw evidence JSON retained for traceability |
| `candidate-pool.yaml` | 107 candidate entries with controlled statuses/actions |

The active licensing summary is `research/public-code-library/synthesis/licensing-and-provenance.md`.

---

## Safe Source Workflow

External research defaults to **local-research-only**. Keep the upstream repository in a gitignored local clone, inspect it locally, and commit only metadata and notes:

1. Verify the upstream URL, commit SHA, license evidence, and useful paths.
2. Prefer `local-research-only` for code-level study without committing upstream files.
3. Use `reference-only` when the license is unclear, absent, restricted, or the repository is useful only as metadata.
4. Use vendoring only for minimal, explicitly approved code with license evidence, attribution, secret scan, file manifest, and CI validation.
5. Do not commit full repositories merely for archival purposes.

For private lab work, `reference-only` and `local-research-only` are still usable by Codex as study material, inspiration, and temporary prototype guidance. The label means "track provenance and review before public/commercial reuse", not "ignore this source".

Run the validators after any source or research change:

```bash
python scripts/validation/validate_source_manifests.py
python scripts/validation/validate_catalog_consistency.py
python scripts/validation/validate_public_code_library.py
```

---

## Source Modes

| Mode | Code in lab? | License requirement | Meaning |
|---|---|---|---|
| `vendored-snapshot` | Yes (`upstream/`) | Clear permission required | Full upstream code copied at a pinned commit after approval |
| `selected-subsystem` | Yes (`upstream/`) | Clear permission required | Specific file set, package, or subdirectory copied after approval |
| `clean-room-reimplementation` | Our code only | License uncertainty allowed if no code is copied | Pattern is studied and reimplemented independently |
| `local-research-only` | No upstream code committed | License recorded; unclear licenses stay non-copyable for release | Code studied in a local clone; usable for private lab guidance; only notes/metadata committed |
| `reference-only` | No upstream code committed | Used when license is unclear or reuse is restricted | Metadata, README, and research pointers; usable as private lab reference and rewrite-before-release guidance |
| `submodule` | Via git | Clear permission required | Rare, for large repos when a submodule is explicitly justified |

## Sources Directory Structure

Sources are stored either flat or nested:

```
sources/<functional-name>/
  SOURCE.yaml
  README.md
  ATTRIBUTION.md
  AUDIT.md                          # vendored/selected only
  FILE_MANIFEST.json                # vendored/selected only
  USEFUL_PATHS.md                   # local-research-only when useful
  upstream/                         # vendored/selected only

sources/people/personN/github/<repo>/
  SOURCE.yaml
  README.md
  ATTRIBUTION.md
  upstream/                         # vendored/selected only
```

The `sources/people/` intermediate directories are not sources; only directories with a `SOURCE.yaml` file are treated as source records.

---

## Component Status

> **Key:** `real` = working code + tests · `planned` = directory only, no implementation · `research` = README + design notes only

| Component | Implementation | Tests | License reviewed | Security reviewed | Approved for reuse |
|---|---|---|---|---|---|
| **agent-safety-firewall** | **real** - command analyzer, path guard, policy engine | **40/40 passing** (component tests only) | MIT (deterministic-agent-safety) | pending manual | no |
| application-security-review-agent | planned | - | - | - | no |
| code-quality-review-agent | planned | - | - | - | no |
| design-generation-and-review-pipeline | planned | - | - | - | no |
| persistent-project-context | planned | - | - | - | no |
| product-context-manager | planned | - | - | - | no |
| protected-path-guard | planned (shares logic with agent-safety-firewall) | - | - | - | no |
| pull-request-review-orchestrator | planned | - | - | - | no |
| secret-and-credential-scanner | planned (shares engine with scripts/security/secret_scan.py) | - | - | - | no |
| skill-evaluation-runner | planned | - | - | - | no |
| trusted-skill-registry | planned | - | - | - | no |

`planned` means a directory exists with a README describing the intended component. It is not an implementation.

---

## Branch And PR Workflow

```
main                 protected - no direct push, no force push
  └── research/*     knowledge base and analysis branches
  └── feature/*      component and workflow development
  └── experiment/*   isolated experiments
```

All changes go through a branch and Pull Request. CI must pass before merge. No automatic merge.

---

## Security Rules

- Never commit real `.env`, tokens, keys, passwords, or certificates
- Run secret scan before any approved vendoring
- No automatic deployment, merge, or publishing
- CI blocks probable secrets, invalid source records, and inconsistent public-code research metadata
- Model weights are never committed; manifests and notes only
