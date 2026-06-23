# System Overview

This repository is a **private AI engineering laboratory**. Its purpose is to create a persistent, searchable knowledge base and component library that an AI assistant can use across all future projects.

## Why a single lab repository

Instead of re-deriving knowledge from scratch in every new project session, the lab repository gives the AI assistant:
- Full implementation context (actual source code, not just links)
- Audited, licensed, pinned snapshots it can legally study
- Components already designed with safety properties in mind
- Accumulated decisions, experiments, and product concepts

## Main layers

```
Layer 1: Sources
  External repositories imported as audited, pinned snapshots
  Each has a license, attribution, and security review status

Layer 2: Components
  Reusable building blocks we build from studying the sources
  Tested, configurable, report-only by default

Layer 3: Reference Implementations
  Clean implementations of specific patterns (jobs, queues, search, etc.)
  Used as starting points, not copied verbatim

Layer 4: Experiments
  Isolated environments for trying full stacks or configurations
  Not production code — clearly labelled

Layer 5: Product Concepts
  Structured product ideas with problem, spec, risks, validation plan

Layer 6: Skill Library
  Candidate and approved AI skills with audit trail and benchmarks
```

## AI safety properties enforced

- All agent components are report-only in first versions
- No automatic push, merge, deploy, or publish
- Protected paths prevent destructive operations
- Secret scanning on all imports
- Human confirmation required for irreversible actions

## Primary use cases

1. Start a new project → load relevant components from this lab
2. Study a pattern → read the audited upstream snapshot + our adapted version
3. Evaluate an AI skill → use the skill-evaluation-runner component
4. Develop a product idea → use the product-concept template and product-context-manager
