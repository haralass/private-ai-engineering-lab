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

| Component | Status | Source | License | Security review | Approved |
|---|---|---|---|---|---|
| agent-safety-firewall | prototype | deterministic-agent-safety | MIT | pending | no |
| secret-and-credential-scanner | prototype | deterministic-agent-safety | MIT | pending | no |
| code-quality-review-agent | research | deterministic-agent-safety | MIT | pending | no |
| application-security-review-agent | research | deterministic-agent-safety | MIT | pending | no |
| pull-request-review-orchestrator | research | deterministic-agent-safety | MIT | pending | no |
| trusted-skill-registry | research | — | — | pending | no |
| skill-evaluation-runner | research | anthropic-skills | — | pending | no |
| persistent-project-context | research | persistent-agent-memory | MIT | pending | no |
| product-context-manager | research | product-marketing-context | — | pending | no |
| design-generation-and-review-pipeline | research | design-quality-and-review | — | pending | no |

Statuses: `research` · `prototype` · `experimental` · `candidate` · `approved` · `rejected`

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

| Mode | Meaning |
|---|---|
| `vendored-snapshot` | Full upstream code copied at a pinned commit |
| `selected-subsystem` | Specific subdirectory or module copied |
| `clean-room-reimplementation` | Our own implementation inspired by the source |
| `reference-only` | Study only — no code copied, no license issue |
| `submodule` | Git submodule (large repos only) |

---

## Security rules

- Never commit real `.env`, tokens, keys, passwords, or certificates
- Run secret scan before every import
- No automatic deployment, merge, or publishing
- CI blocks commits with probable secrets or missing attribution
- Model weights are never committed — manifests only
