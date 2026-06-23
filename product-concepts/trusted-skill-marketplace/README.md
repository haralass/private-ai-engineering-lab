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

## Competitor landscape

Source: verified from product websites and GitHub, 2026-06-23. Full analysis in `research/domain-synthesis/agent-engineering-and-safety.md`.

| Platform | Type | Scope | Gap |
|---|---|---|---|
| Anthropic Skills (anthropics/skills) | Official skill collection | Claude Code only; no marketplace | No security vetting, no benchmarks, no community submissions |
| Vercel Labs Skills (vercel-labs/skills) | Framework skills | Claude Code / Next.js | Framework-specific; no registry or security audit |
| Claude Code Plugin registry (hypothetical) | Anthropic may build | Not yet announced | Risk: Anthropic builds official registry |
| npm / PyPI | General package registries | Any code | No AI-skill-specific fields (permissions, hooks, prompt injection) |
| Model Context Protocol (Anthropic MCP) | Tool plugin protocol | MCP-compatible agents | Protocol only; no registry with security vetting |

**Key observation:** No verified skill registry with security vetting, benchmark data, and permission auditing exists as of 2026-06-23. The gap is real. The main competitive risk is Anthropic building an official skill store — this is a likely outcome given the MCP ecosystem growth.

**Mitigation strategy:** Build the internal version first (already underway via skill-library/). If Anthropic builds official registry, the lab's internal pipeline remains valuable as an enterprise-grade review tool.

evidence_level: initial-research (competitor landscape verified; no external user validation)

## Next validation step

1. Find 3–5 teams using Claude Code or similar in shared codebases: do they share skills,
   and what problems have they had with untrusted skills?
2. Check if Anthropic or other agent vendors plan to build an official skill registry
3. Determine: open-source community registry vs. commercial enterprise approval tool
