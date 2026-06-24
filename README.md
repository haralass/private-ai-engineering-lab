# Private AI Engineering Lab

Personal AI engineering laboratory — source archive, component library, experimentation environment, and product idea incubator.

This is **not a production monorepo**. It is a private research lab used to study, archive, adapt, and incubate ideas across AI agent engineering, design systems, and product development.

---

## Purpose

| Goal | Description |
|---|---|
| Source archive | Audited, pinned snapshots of useful upstream repositories |
| Component library | Reusable, tested building blocks adapted from studied sources |
| Experiment environment | Isolated experiments with real implementations |
| Product incubator | Structured product concepts with specs, risks, and validation plans |
| AI context base | Searchable source so future AI sessions have full implementation context |

---

## Structure at a glance

```
sources/          Vendored upstream snapshots and adapted versions
components/       Our own reusable components (derived or original)
reference-implementations/  Clean reference builds for studied patterns
experiments/      Isolated experiments, not production code
product-concepts/ Structured product ideas with full spec documents
workflows/        Step-by-step process guides for recurring work
skill-library/    Candidate, approved, and rejected AI skills
model-and-agent-evaluations/ Benchmark results and evaluation datasets
ideas/            Raw idea inbox before formal spec
templates/        Reusable document and YAML templates
scripts/          Utility scripts for source management, CI, validation
tests/            Component tests, integration tests, security tests
docs/             Architecture, decisions, policies, roadmap
```

---

## Component status

> **Key:** `real` = working code + tests · `planned` = directory only, no implementation · `research` = README + design notes only

| Component | Implementation | Tests | License reviewed | Security reviewed | Approved for reuse |
|---|---|---|---|---|---|
| **agent-safety-firewall** | **real** — command analyzer, path guard, policy engine | **40/40 passing** (component tests only) | MIT (deterministic-agent-safety) | pending manual | no |
| application-security-review-agent | planned | — | — | — | no |
| code-quality-review-agent | planned | — | — | — | no |
| design-generation-and-review-pipeline | planned | — | — | — | no |
| persistent-project-context | planned | — | — | — | no |
| product-context-manager | planned | — | — | — | no |
| protected-path-guard | planned (shares logic with agent-safety-firewall) | — | — | — | no |
| pull-request-review-orchestrator | planned | — | — | — | no |
| secret-and-credential-scanner | planned (shares engine with scripts/security/secret_scan.py) | — | — | — | no |
| skill-evaluation-runner | planned | — | — | — | no |
| trusted-skill-registry | planned | — | — | — | no |

**`planned`** means a directory exists with a README describing the intended component.
It is **not an implementation**. Do not treat it as functional.

Statuses: `real` · `planned` · `research` · `experimental` · `candidate` · `approved` · `rejected`

---

## How to add a new source

1. Verify the license file exists in the upstream repository
2. Pin a commit SHA
3. Run the import script:
   ```bash
   python scripts/source-management/import_source.py \
     --url <github-url> \
     --commit <sha> \
     --name <functional-name>
   ```
4. Review the generated `SOURCE.yaml` — correct any auto-detected fields
5. Complete `AUDIT.md` and `ATTRIBUTION.md`
6. Run secret scan: `python scripts/security/secret_scan.py sources/<name>/upstream/`
7. Open a branch, commit the snapshot, open a PR — do not push directly to `main`

---

## Branch and PR workflow

```
main              protected — no direct push, no force push
  └── research/*  initial knowledge base and analysis branches
  └── feature/*   component and workflow development
  └── experiment/*  isolated experiments
```

All changes go through a branch and Pull Request. CI must pass before merge. No automatic merge.

---

## Source modes

| Mode | Code in lab? | License required? | Meaning |
|---|---|---|---|
| `vendored-snapshot` | Yes (`upstream/`) | Yes | Full upstream code copied at a pinned commit |
| `selected-subsystem` | Yes (`upstream/`) | Yes | Specific subdirectory or module copied |
| `clean-room-reimplementation` | Yes (`upstream/`) | Recommended | Our own reimplementation of a studied pattern |
| `local-research-only` | No | No | Code studied locally; patterns documented; no files committed |
| `reference-only` | No | No | Metadata and README only — no code studied locally |
| `submodule` | Via git | Yes | Git submodule (large repos only) |

## Sources directory structure

Sources are stored either flat or nested:

```
sources/<functional-name>/          # flat — most external and person1/person2 sources
  SOURCE.yaml
  README.md
  ATTRIBUTION.md
  AUDIT.md                          # vendored only
  FILE_MANIFEST.json                # vendored only
  upstream/                         # vendored only — pinned upstream code

sources/people/personN/github/<repo>/  # nested — person3 and person4 sources
  SOURCE.yaml
  README.md
  ATTRIBUTION.md
  upstream/                         # vendored only (e.g., noshowly)
```

The `sources/people/` intermediate directories are NOT sources — only directories
with a `SOURCE.yaml` file are treated as sources.

---

## Security rules

- Never commit real `.env`, tokens, keys, passwords, or certificates
- Run secret scan before every import
- No automatic deployment, merge, or publishing
- CI blocks commits with probable secrets or missing attribution
- Model weights are never committed — manifests only
