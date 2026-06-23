# Cross-Source Combination Analysis

research_date: 2026-06-23
question_answered: which pairs or groups of lab sources combine into something greater than the sum of their parts?

This file documents specific combinations that have been identified across lab sources.
For each combination: what problem it solves, which sources contribute, what's still missing,
and what product it could become.

---

## Combination 1: End-to-End AI Frontend Quality Loop

**Problem it solves:**
AI-generated frontends and PR-reviewed frontends have quality issues at three levels:
(1) incorrect functionality, (2) poor design/UX, (3) poor writing quality.
No single tool catches all three simultaneously.

**Sources combined:**
- `design-quality-and-review` (impeccable) — 23-command UI lifecycle review, detects design issues
- `design-agent-reviews` (agent-reviews) — two-phase PR review loop, bot comment handling
- `writing-quality` (stop-slop) — writing quality rubric (35/50 threshold)
- `interaction-motion-toast` (sonner) — production-ready toast/notification UI component

**How they combine:**
1. A PR is opened with new frontend code
2. impeccable runs a full UI lifecycle review: layout, accessibility, copy quality, interaction design
3. The scoring rubric from stop-slop gates the PR on writing quality
4. agent-reviews's two-phase loop handles any review comments until all are resolved
5. The UI itself uses sonner for notifications with CSS custom properties theming

**What's still missing:**
- A CI runner that orchestrates all three tools in sequence
- A shared scoring threshold across tools (currently each uses its own scoring)
- Automated fix generation (currently tools detect but most don't fix)

**Possible product:**
AI Frontend Quality Gate — a CI service that runs impeccable + stop-slop + agent-reviews
on every PR containing frontend changes. Reports pass/fail with specific findings.
Generates fix suggestions for deterministic issues. Targets: any team shipping UI with AI assistance.

**Technical feasibility:** HIGH — all tools have MIT/Apache-2.0 licenses. Integration is CI orchestration.
**Business relevance:** MEDIUM-HIGH — market for automated code quality is established (Codacy, SonarCloud),
but AI-specific design quality gate is a gap.

---

## Combination 2: AI Task Queue Infrastructure

**Problem it solves:**
AI workflows require durable, async job execution. LLM calls are slow (1–30s), APIs have
rate limits, and tasks may fail and need retry. Most teams bolt on Redis or Kafka, adding
operational complexity. A PostgreSQL-native queue removes the dependency.

**Sources combined:**
- `durable-background-job-queue` (forgequeue) — PostgreSQL job queue, API, typed status enum
- `asynchronous-job-processing` (sms-platform) — demonstrates the production use case pattern

**How they combine:**
The sms-platform demonstrates a working async job processing system. forgequeue provides
the reusable infrastructure for exactly that pattern: submit a job, have a worker pick it
up reliably, track status, retry on failure.

The combination: forgequeue's data model and API layer + a worker implementation inspired
by the sms-platform's processing pattern = a complete AI-ready job queue.

**What's still missing:**
- forgequeue's worker process (not implemented at pinned commit a08a6f9)
- AI-specific job types (LLM call, embedding generation, etc.)
- Rate-limit-aware scheduling (exponential backoff, provider-specific limits)

**Possible product:**
AI Task Queue — a drop-in background job system for AI applications. PostgreSQL-backed
(no additional infra). Typed job definitions. Rate-limit-aware retry policy.
Dashboard for job status monitoring.

**Technical feasibility:** HIGH — implement the worker layer (straightforward Go); add AI-specific primitives.
**Business relevance:** MEDIUM — Hatchet ($79-$799/month) and Sidekiq exist, but no AI-native queue
with built-in LLM rate limit awareness has been identified.

---

## Combination 3: SQL Learning Platform with AI Feedback

**Problem it solves:**
Learning SQL requires immediate feedback on queries. Existing platforms (DataCamp, SQLZoo)
are passive. The learner writes SQL, sees the result, but doesn't know *why* their approach
was suboptimal. AI-powered explanation + hints + query optimization feedback is missing.

**Sources combined:**
- `database-query-training` (SQL-Gym) — read-only SQL sandbox (AST validation + process isolation),
  exercise framework, frontend + backend scaffold
- `modular-rag-learning` (AI-Study-Mate) — FAISS + chunked RAG pipeline for study material retrieval
- `terminal-coding-agent` / `kimi-model-family` — coding-capable AI model for query explanation

**How they combine:**
1. SQL-Gym provides the secure execution sandbox — the technically hardest component
2. AI-Study-Mate's RAG pipeline indexes SQL concepts, patterns, and explanations
3. The LLM (Kimi-K2 via API or Claude) explains why the query is wrong or suboptimal,
   suggests improvements, and retrieves relevant concept explanations from the RAG index

**What's still missing:**
- Exercise authoring system (currently SQL-Gym has hand-crafted exercises)
- Curriculum sequencing (which exercise to show next based on skill level)
- LLM integration layer connecting the sandbox execution result to the explanation model

**Possible product:**
Adaptive SQL Learning Platform — a web app where the learner writes SQL against a sandboxed
schema, gets instant AI feedback on correctness and efficiency, and receives concept explanations
from a curated knowledge base.

**Technical feasibility:** HIGH — all MIT/Apache-2.0. Core sandbox is the only novel technical piece.
**Business relevance:** MEDIUM — DataCamp and SQLZoo are well-established but do not offer AI feedback.
The specific gap (AI explanation + curriculum tracking) is real.

---

## Combination 4: Safe Multi-Agent Engineering Workflow

**Problem it solves:**
Multi-agent engineering workflows (e.g., "build this feature autonomously") have no
enforcement layer. Subagents can run rm -rf, push to main, expose credentials. The multi-agent
framework (gstack) and the safety layer (dotclaude) exist separately; combined, they form
a complete safe autonomous engineering system.

**Sources combined:**
- `full-product-engineering-agent-stack` (gstack) — coordinator + specialist subagents,
  AGENTS.md spec, multi-agent task decomposition
- `deterministic-agent-safety` (dotclaude) — fail-closed hook policy engine, protect-files,
  block-dangerous-commands, scan-secrets, append-only audit log
- `structured-agent-development` (superpowers) — skill extension framework

**How they combine:**
1. gstack provides the workflow: coordinator decomposes, specialists execute
2. dotclaude provides the safety layer: every specialist's tool call is intercepted before execution
3. superpowers provides additional skills that specialists can use

Each specialist agent runs inside the fail-closed hook engine. The coordinator's decisions
are also logged via the audit log hook. The result: an autonomous engineering workflow
where every action is policy-checked before execution.

**What's still missing:**
- Shared state management between coordinator and specialists
- Cross-agent audit log aggregation (each agent has its own log; need consolidated view)
- Policy version management (which policy applies to which agent tier?)

**Possible product:**
Safe Autonomous Engineering Workflow — a configurable multi-agent system for engineering
tasks with built-in policy enforcement. For enterprise teams: each agent tier gets specific
permissions. Audit log aggregated to a dashboard. Useful for regulated industries deploying
AI engineering tools.

**Technical feasibility:** HIGH — all MIT. Integration is configuration.
**Business relevance:** MEDIUM-HIGH — enterprise demand for safe AI automation is real.
The combination is novel; no product currently ships this.

---

## Combination 5: Synthetic Data Platform for Compliance Testing

**Problem it solves:**
Compliance software vendors need realistic test data (KYC files, ESG reports, AI Act
documentation) without using real personal or business data. Generic synthetic data tools
(Faker) don't produce schema-coherent documents. No tool combines schema-awareness with
compliance-domain knowledge.

**Sources combined:**
- `synthetic-relational-data` (one-stop-ride-hail) — relational schema consistency patterns,
  entity relationships, foreign key coherence in synthetic generation
- `database-query-training` (SQL-Gym) — schema design + execution environment for validation
- `modular-rag-learning` (AI-Study-Mate) — RAG pipeline for compliance document knowledge retrieval

**How they combine:**
1. RAG pipeline indexes compliance standards (VSME, AI Act templates, NIS2 evidence requirements)
2. Schema-consistent synthetic generation produces relational datasets that match the standard's data model
3. The SQL sandbox validates the generated data conforms to the schema

**What's still missing:**
- Compliance-specific schema definitions (VSME fields, AI Act documentation fields)
- LLM integration for coherent text generation within schema constraints (e.g., realistic company names + matching revenue figures)

**Possible product:**
Compliance-Specific Synthetic Data Generator — input a compliance framework (VSME, AI Act,
NIS2), get a complete synthetic dataset with coherent entities, realistic figures, and
correct field types. For compliance software vendors and auditors.

**Technical feasibility:** MEDIUM — relational coherence + schema generation requires engineering.
**Business relevance:** MEDIUM — niche market; overlap with general synthetic data tools.
Differentiator is compliance schema depth, not generation technology.

---

## Combination 6: Agent Memory + RAG for Context-Aware Agents

**Problem it solves:**
Agents lose context between sessions. Persistent memory exists (claude-mem) but it's
keyword-searchable; RAG retrieval (AI-Study-Mate) is semantic but not session-aware.
Combining both creates a hybrid context system: recent session memory (temporal, explicit)
+ long-term semantic knowledge (persistent, implicit).

**Sources combined:**
- `persistent-agent-memory` (claude-mem) — file-based session memory store, FAISS indexing
- `modular-rag-learning` (AI-Study-Mate) — chunk-indexed semantic retrieval
- `deterministic-agent-safety` (dotclaude) — audit log provides a structured record of past actions

**How they combine:**
1. Claude-mem stores explicit session facts (user preferences, decisions, context)
2. AI-Study-Mate's RAG pipeline stores domain knowledge (docs, specifications, history)
3. The dotclaude audit log provides a structured timeline of past agent actions

At query time: the agent retrieves from all three sources based on recency + relevance.

**What's still missing:**
- Unified retrieval API across all three stores (currently separate indexing)
- Conflict resolution when session memory contradicts knowledge base
- Privacy: the audit log contains action inputs that may be sensitive

**Possible product:**
Not a standalone product — this combination is the memory architecture for Curator
and any agent product. It's a technical component, not a user-facing product.

**Technical feasibility:** HIGH — all MIT/Apache-2.0. The work is integration, not research.
**Business relevance:** N/A (infrastructure).

---

## Summary Table

| Combination | Sources | Missing Piece | Possible Product | Feasibility | Commercial Potential |
|---|---|---|---|---|---|
| AI Frontend Quality Loop | impeccable + agent-reviews + stop-slop + sonner | CI orchestrator | Frontend quality gate CI | HIGH | MEDIUM-HIGH |
| AI Task Queue | forgequeue + sms-platform | Worker layer | AI-native job queue | HIGH | MEDIUM |
| SQL Learning Platform | SQL-Gym + AI-Study-Mate + Kimi | LLM integration + curriculum | Adaptive SQL platform | HIGH | MEDIUM |
| Safe Multi-Agent Engineering | gstack + dotclaude + superpowers | Cross-agent audit aggregation | Safe autonomous engineering | HIGH | MEDIUM-HIGH |
| Compliance Synthetic Data | one-stop-ride-hail + SQL-Gym + AI-Study-Mate | Compliance schemas | Compliance test data generator | MEDIUM | MEDIUM |
| Agent Memory + RAG | claude-mem + AI-Study-Mate + dotclaude | Unified retrieval API | Curator memory architecture | HIGH | Infrastructure |
