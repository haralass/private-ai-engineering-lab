# Skill Benchmarking Platform

Status: `research`

## Summary

Measures the real impact of an AI skill on model performance: correctness, latency, token cost, overtriggering rate, and regressions on unrelated tasks.

## Why this matters

Installing a skill can improve the model on target tasks but degrade it elsewhere (overtriggering), increase latency (larger context), or increase cost (more tokens). No existing tool measures this systematically.

## Measurement design

For each skill being evaluated:

1. **Baseline**: run a standardized task suite without the skill
2. **Intervention**: run the same suite with the skill installed
3. **Metrics collected**:
   - Correctness rate (task-specific assertions)
   - Tests passed
   - Latency (p50, p95)
   - Token usage (prompt + completion)
   - Cost estimate
   - Overtriggering: skill fires on tasks where it shouldn't
   - Regressions: tasks that passed baseline but fail with skill
   - Security issues: skill causes unsafe actions in test environment

## Internal implementation

`components/skill-evaluation-runner/` — prototype

---

## Related sources

- `sources/anthropic-skills/` — Claude Code official skills (reference material for benchmark targets)
- `sources/vercel-skills/` — Vercel-published Claude Code skills
- `sources/algorithm-benchmarking/` — algorithmic benchmarking patterns (tangentially relevant)
- `components/skill-evaluation-runner/` — working prototype

## Research connections

- `product-concepts/trusted-skill-marketplace/README.md` — this platform provides the benchmark
  data that a marketplace would require for skill approval

## Origin

Derived from internal need: building and evaluating skills for the lab's own Claude Code
setup required a systematic way to measure whether a skill improved or degraded performance.
The platform generalized from that internal use case.

## Current evidence level

`initial-research` — prototype built, used internally. No external user validation.

## Open assumptions

- Other AI tool users face the same benchmarking problem (not just this lab)
- The measurement methodology is valid (correctness delta is the right primary metric)
- Skills cause meaningful regressions often enough to justify a dedicated tool

## Next validation step

1. Dogfood the prototype on 3–5 lab skills: does the benchmark output match intuition?
2. Share with 2–3 other Claude Code users: do they have the same measurement problem?
3. Determine if this is useful standalone or only as a component of the skill marketplace
