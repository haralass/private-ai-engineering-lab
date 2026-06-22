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
