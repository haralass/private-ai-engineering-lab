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
