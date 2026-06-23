# Architecture

## Repository layers

```
┌──────────────────────────────────────────────────────────────┐
│  product-concepts/   ideas/                                   │
│  Structured product specs and raw idea inbox                  │
├──────────────────────────────────────────────────────────────┤
│  experiments/   model-and-agent-evaluations/                  │
│  Isolated experiments and benchmark results                   │
├──────────────────────────────────────────────────────────────┤
│  components/   workflows/   skill-library/                    │
│  Our own reusable building blocks                             │
├──────────────────────────────────────────────────────────────┤
│  reference-implementations/                                   │
│  Clean reference builds for key patterns                      │
├──────────────────────────────────────────────────────────────┤
│  sources/                                                     │
│  Audited upstream snapshots + adapted versions                │
└──────────────────────────────────────────────────────────────┘
```

## Component dependency rules

- `components/` may depend on other `components/`
- `components/` must not import from `sources/upstream/` directly — use `sources/adapted/` or copy into the component
- `experiments/` may use anything but must be clearly isolated
- `reference-implementations/` are standalone — no cross-dependencies with our components

## Agent safety stack

```
agent-safety-firewall
  ├── command_analyzer.py      blocks or flags dangerous shell commands
  ├── path_guard.py            enforces protected-paths.yaml
  └── policy_engine.py         evaluates actions against policy config

secret-and-credential-scanner
  └── secret_scan.py           pattern-based scan, fails on high-confidence hits

code-quality-review-agent
  └── report-only reviewer, no automatic edits

application-security-review-agent
  └── report-only reviewer, no automatic edits

pull-request-review-orchestrator
  └── coordinates reviewers, aggregates reports, opens review comment
```

## Product context stack

```
product-context-manager
  └── loads and validates PRODUCT_CONTEXT.yaml
      enforces separation: verified facts vs assumptions vs hypotheses
      blocks AI from inventing metrics, testimonials, or proof points
```

## Skill evaluation pipeline

```
skill-evaluation-runner
  ├── baseline: model without skill
  ├── test: model with skill
  ├── metrics: correctness, latency, tokens, cost
  └── reports: overtriggering, regressions, security issues
```
