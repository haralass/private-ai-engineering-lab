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
