# Agent Permission Firewall

Status: `research`
Category: agent safety infrastructure

## Summary

A runtime permission layer that intercepts and evaluates every action an AI agent proposes — shell commands, file writes, git operations, SQL queries, network requests, package installations — before execution.

## Core insight

Current AI coding agents have no structured way to express "this action is destructive and irreversible." A human approval prompt is good for one-off cases, but a policy engine that maps action types to required approval levels scales better.

## Problem

AI agents increasingly take actions that are hard to undo (force push, drop table, mass delete). Prompt instructions ("be careful") are fragile. We need a principled, configurable enforcement layer.

## Differentiation

Unlike general-purpose "human in the loop" approaches, this is:
- Deterministic (config-driven, not AI judgment)
- Composable (works with any agent via hooks)
- Auditable (every decision logged with rationale)
- Configurable per project (different rules for prod vs dev)

## Components built

- `components/agent-safety-firewall/` — prototype

## Next steps

See `PRODUCT_SPEC.md` (to be created in this directory) for full spec.

---

## Related sources

- `sources/deterministic-agent-safety/` (poshan0126/dotclaude, MIT) — Claude Code hook patterns
- `sources/structured-agent-development/` (obra/superpowers, MIT) — agent extension architecture
- `components/agent-safety-firewall/` — working Phase 1 prototype

## Research connections

- `business-research/startup-white-spaces/agent-permission-firewall.md` — market research notes
- `research/people/stylianos-gavriil/IDEAS_DERIVED.md` — no direct connection documented

## Origin

Derived from in-lab experience using Claude Code: the need for a principled enforcement
layer became clear when building with AI agents on shared codebases. Prototype built as
Phase 1 of the lab's component library.

## Current evidence level

`initial-research` — prototype working (141 tests), integrated with Claude Code hook system.
No external user validation.

## Open assumptions

- Development teams using AI agents actually want a configurable policy layer
- Teams would adopt an external tool rather than write their own hook scripts
- The hook interface is stable enough that this won't be superseded by the agent vendor

## Competitor landscape

Source: verified from product websites and GitHub, 2026-06-23. Full analysis in `research/domain-synthesis/agent-engineering-and-safety.md`.

| Competitor | Approach | Scope | Gap |
|---|---|---|---|
| Claude Code built-in permissions | Native allow/deny lists for tool categories | Claude Code only; not composable | No policy engine; no audit log; not configurable per project |
| Cursor Rules / .cursorrules | Instruction-based guidelines | Cursor only; prompt-based, not enforced | No deterministic enforcement; prompt injection possible |
| GitHub Copilot Workspace | Human approval dialogs | GitHub Copilot only | Per-action approval, not policy-based; no audit log |
| dotclaude (poshan0126) | Bash hook collection (vendored in this lab) | Claude Code only | Framework, not a product; no configuration UI |
| None found | Composable multi-agent policy engine | — | This gap has no verified product |

**No verified competitor** offers a composable, multi-agent, deterministic policy enforcement layer with audit logging that works across agent vendors. The main competitive risk is the AI agent vendors building this natively.

evidence_level: initial-research (competitor landscape verified; no user interviews conducted)

## Next validation step

1. Find 5 developers using Claude Code or similar in team settings: what risky actions have occurred?
2. Check: does any vendor (Cursor, GitHub Copilot Workspace, Codeium) offer native policy enforcement?
3. Define whether the product is open-source (community adoption) or commercial (enterprise sale)
