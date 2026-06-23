# Trusted Skill Marketplace

Status: `research`

## Summary

A registry and review platform for AI agent skills, with enforced provenance tracking, license verification, permission auditing, prompt-injection review, and benchmark results before a skill can be marked "approved for use."

## Problem

The current ecosystem for AI skills (Claude Code skills, agent plugins, custom system prompts) has no standard for:
- What permissions a skill requests
- Whether a skill has been security-audited
- Whether a skill degrades model performance in unrelated tasks (overtriggering)
- What license governs the skill

## Our internal version

`skill-library/` in this repo implements a private version of this. All skills go through:
`candidates/ → security audit → benchmark → approved/ or rejected/`

## Key registry fields

- Provenance: origin URL, pinned version, author
- Permissions: shell access, file write, git operations, network, package install
- Hooks: list of all hooks the skill installs
- Prompt injection: has the skill been reviewed for instruction injection risks?
- Benchmark: correctness delta, latency delta, token cost, overtriggering rate
- License: SPDX identifier, verified
- Conflict: known conflicts with other skills
- Agent compatibility: Claude Code, Kimi Code, generic

---

## Related sources

- `sources/anthropic-skills/` — Claude Code official skills (candidate inputs for the registry)
- `sources/vercel-skills/` — Vercel-published Claude Code skills
- `sources/deterministic-agent-safety/` — safety hook patterns relevant to skill security audit
- `skill-library/` — this lab's private implementation of the approval pipeline

## Research connections

- `product-concepts/skill-benchmarking-platform/README.md` — provides the benchmark data
  required by the approval pipeline

## Origin

Derived from building the lab's internal skill approval pipeline (`skill-library/`).
The internal version handles provenance, license, security review, and benchmark gating
for skills used in this lab. The product concept extends this to a shared public registry.

## Current evidence level

`assumption` — internal version in use. No external validation of whether other teams
want or need a shared registry.

## Open assumptions

- The skill ecosystem is large enough to justify a registry (enough skills being published)
- Security and performance vetting are blockers for skill adoption in team settings
- This is a community/ecosystem problem, not just a personal productivity problem

## Next validation step

1. Find 3–5 teams using Claude Code or similar in shared codebases: do they share skills,
   and what problems have they had with untrusted skills?
2. Check if Anthropic or other agent vendors plan to build an official skill registry
3. Determine: open-source community registry vs. commercial enterprise approval tool
