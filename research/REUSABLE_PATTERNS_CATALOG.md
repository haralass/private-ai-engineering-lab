# Reusable Patterns Catalog

research_date: 2026-06-23
compiled_from: all 30 source analysis dossiers in research/source-analysis/

This catalog documents specific technical patterns observed in lab sources that are
reusable independently of the source repository. Each entry names the pattern precisely,
identifies where it lives, and explains how and when to reuse it.

Patterns are listed roughly in order of estimated reuse value to this lab.

---

## 1. Fail-closed hook policy engine

**Purpose:** Intercept AI agent tool calls before execution, evaluate against a deterministic ruleset, and block or require confirmation for dangerous actions. Fail to the safe state if parsing fails.

**Source repository:** sources/deterministic-agent-safety/ (poshan0126/dotclaude, MIT)
**Relevant files:**
- `upstream/hooks/block-dangerous-commands.sh` — pattern template
- `upstream/settings.json` — hook wiring
- `upstream/hooks/tests/` — fixture-based testing

**How it works:**
Each hook script reads a JSON payload from stdin (CLAUDE_TOOL_INPUT), parses with jq, evaluates a deterministic condition, and exits with code 2 (block), 3 (confirm), or 0 (allow). If stdin parsing fails → exit 4 in enforce mode, exit 0 in report-only mode.

**Why it's useful:**
Deterministic, composable, configurable per project. Works with any agent that supports pre-tool-use hooks. Already implemented in this lab's `components/agent-safety-firewall/`.

**Known limitations:**
- Bash scripts require jq; adding complex logic gets unwieldy
- No persistent audit log per default; must add explicitly

**Safe reuse method:** Copy pattern from components/agent-safety-firewall/ (already clean-roomed). MIT licensed.

**Possible projects:** agent-permission-firewall product, any agent workflow requiring safety gates

---

## 2. Module-level observable store (imperative UI state)

**Purpose:** Allow imperative state updates (e.g., `toast("message")`) from anywhere in the codebase without React context providers.

**Source repository:** sources/interaction-motion-toast/ (emilkowalski/sonner, MIT)
**Relevant files:**
- `upstream/src/state.ts` — core pattern

**How it works:**
Toast state is stored in a module-level `Map`-backed `ToastState` class with a subscriber pattern. Components subscribe to state changes. Imperative calls (`toast()`) update the module-level state; subscribers are notified synchronously.

**Why it's useful:**
Avoids the context-provider tax. `toast()` works in event handlers, async functions, middleware, service layers — anywhere. Common use case: notification systems, global loading states, error boundaries.

**Known limitations:**
React-only. No server-side rendering support without care.

**Safe reuse method:** Re-implement the pattern (it's a straightforward observer pattern). Or import sonner directly (MIT). No proprietary logic.

**Possible projects:** Any React frontend in the lab

---

## 3. Foundation-skill-first context injection

**Purpose:** Ensure that domain context (product description, target audience, tone) is loaded before any domain-specific skill runs, so each task starts with full context rather than cold.

**Source repository:** sources/product-marketing-context/ (coreyhaines31/marketingskills, MIT)
**Relevant files:**
- `upstream/skills/product-marketing/` — foundation skill template
- `upstream/README.md` — dependency graph documentation

**How it works:**
Every skill in the collection instructs Claude to read `product-marketing/` first. The foundation skill encodes: product name, what it does, target audience, primary value prop, tone/voice, competitors. All subsequent tasks start with this context injected.

**Why it's useful:**
Solves the "context amnesia" problem in multi-skill workflows. Each new task doesn't have to re-derive what the product is. Scales to any domain with shared context (engineering, research, marketing, etc.).

**Known limitations:**
Relies on LLM instruction-following; no hard enforcement of reading order.

**Safe reuse method:** Apply the pattern to any skill ecosystem in the lab. MIT licensed, but the pattern itself is architectural — no copying needed.

**Possible projects:** Lab's skill library, any multi-skill product workflow

---

## 4. SQL execution sandbox (read-only, AST-validated, process-isolated)

**Purpose:** Execute arbitrary user-submitted SQL against a schema safely, without allowing reads outside whitelisted tables, writes, schema access, or resource exhaustion.

**Source repository:** sources/database-query-training/ (tsembp/SQL-Gym, MIT)
**Relevant files:**
- `upstream/backend/` — execution engine
- `upstream/README.md` — security model description

**How it works (from README + source):**
1. Parse submitted SQL with an AST parser
2. Reject any statement not matching SELECT
3. Reject table references outside whitelist
4. Execute in a subprocess with timeout (5s) and memory limit (256MB)
5. Return max 1000 rows, 500KB output

**Why it's useful:**
Any SQL learning product, database sandbox, or SQL practice environment needs this. Re-implementing it from scratch is the highest-risk part of the adaptive-sql-learning-platform concept.

**Known limitations:**
The upstream security model needs a production re-audit before deployment (stated in the product concept README). The upstream may not have all guards implemented; treat the spec as a reference, not the implementation.

**Safe reuse method:** Read the upstream code as a reference for the required security layers. Implement clean-room using the patterns. MIT licensed.

**Possible projects:** product-concepts/adaptive-sql-learning-platform/

---

## 5. Layer-wise LLM inference (memory-efficient large model loading)

**Purpose:** Run inference on 70B+ parameter transformer models on hardware with 4–8 GB VRAM by loading and discarding one layer at a time.

**Source repository:** sources/model-layer-streaming/ (lyogavin/airllm, Apache-2.0)
**Relevant files:**
- `upstream/air_llm/airllm/airllm_base.py` — core `forward()` implementation
- `upstream/air_llm/airllm/` — layer management

**How it works:**
At each forward pass: destroy and reinitialize the model, build a flat sequence of layers, load each layer from disk to GPU sequentially, run the forward pass for that layer, discard from GPU, load the next. Intermediate activations live in CPU RAM. Optional 4-bit/8-bit quantization further reduces per-layer size.

**Why it's useful:**
Enables running large open-weight models (LLaMA 70B, Kimi-K2's 1T-total parameters at the right active param count) on commodity hardware. Relevant for any local AI inference scenario where GPU memory is constrained.

**Known limitations:**
Very slow — 30+ seconds per inference on some models. Not suitable for interactive latency-sensitive applications. Apache-2.0 license requires license notice.

**Safe reuse method:** Import airllm directly (Apache-2.0) or use as reference for implementing layer-wise loading in another framework.

**Possible projects:** Local AI inference, privacy-preserving AI developer tool

---

## 6. BM25 + CSV hybrid search over structured domain knowledge

**Purpose:** Enable ranked retrieval from a structured knowledge base (styles, guidelines, patterns) without requiring a vector database or embedding model.

**Source repository:** sources/ui-ux-reference/ (nextlevelbuilder/ui-ux-pro-max-skill, MIT)
**Relevant files:**
- `upstream/src/ui-ux-pro-max/scripts/core.py` — BM25 + regex hybrid search
- `upstream/src/ui-ux-pro-max/data/*.csv` — knowledge base structure

**How it works:**
Each knowledge domain is a CSV with consistent schema (name, description, keywords, examples). The search engine uses `rank_bm25` for relevance scoring and regex for exact matching. Domain auto-detection routes queries to the right CSV.

**Why it's useful:**
Zero ML inference cost. Fast. Editable (add rows to CSV without code changes). Domain-specific knowledge retrieval without the complexity of embeddings or vector stores.

**Known limitations:**
BM25 is term-frequency based — poor at semantic similarity for out-of-vocabulary terms. Doesn't understand "pleasant" = "warm" for color palettes.

**Safe reuse method:** Copy the core.py pattern with a different CSV schema. `rank_bm25` is MIT licensed. MIT overall.

**Possible projects:** Business idea search, source analysis catalog search, any domain-specific knowledge tool

---

## 7. Durable SQL-backed job queue with optimistic locking

**Purpose:** Persist background jobs in PostgreSQL with status tracking, priority scheduling, retry logic, and worker-safe claiming (SELECT FOR UPDATE SKIP LOCKED).

**Source repository:** sources/durable-background-job-queue/ (sgavriil01/forgequeue, MIT)
**Relevant files:**
- `upstream/` — Go implementation
- Database schema: jobs table with status enum (pending/running/completed/failed/dead/cancelled)
- Config: FORGEQUEUE_DATABASE_URL, FORGEQUEUE_HTTP_ADDR env vars

**How it works:**
Jobs are PostgreSQL rows. Workers claim jobs with `SELECT ... FOR UPDATE SKIP LOCKED` — prevents multiple workers from claiming the same job atomically. Status transitions are tracked with timestamps. Retry count and max_retries fields handle failure recovery. JSONB payload stores typed job data.

**Why it's useful:**
SQL-backed queues are zero-dependency additions to any PostgreSQL-using application. No additional infrastructure (no Redis, no Kafka). SKIP LOCKED is the correct primitive for concurrent workers in PostgreSQL.

**Known limitations:**
Worker process is not yet implemented in the pinned commit (Phase 0 — API only). High throughput scenarios have SQL polling limits.

**Safe reuse method:** MIT licensed. The data model and API layer are clean references. Implement the worker independently using the SKIP LOCKED pattern.

**Possible projects:** AI task queue, any async processing system

---

## 8. Scoring rubric with pass/fail threshold for quality evaluation

**Purpose:** Convert subjective quality assessment into a reproducible pass/fail gate using a multi-dimension rubric with explicit thresholds.

**Source repository:** sources/writing-quality/ (hardikpandya/stop-slop, MIT)
**Relevant files:**
- `upstream/SKILL.md` — rubric definition (5 dimensions × 1–10, threshold 35/50)

**How it works:**
Rate each quality dimension on a 1–10 scale. Sum the scores. Below threshold (35/50) → revise before delivering. Dimensions: Directness, Rhythm, Trust, Authenticity, Density.

**Why it's useful:**
The pattern generalizes to any quality gate: code review quality, design quality, documentation quality, accessibility compliance. By making the threshold explicit, it removes the subjective "is this good enough?" decision from each evaluation.

**Known limitations:**
Threshold is author judgment, not empirically calibrated. May not match all users' quality standards.

**Safe reuse method:** The pattern is architectural. Re-implement with domain-specific dimensions and threshold. MIT licensed.

**Possible projects:** code-review-assistant, design quality gate, documentation quality CI

---

## 9. Plugin marketplace with symlink-based distribution

**Purpose:** Distribute modular configuration components (skills, hooks, agents) as opt-in plugins using symlinks and a catalog, without duplicating files.

**Source repository:** sources/deterministic-agent-safety/ (poshan0126/dotclaude, MIT)
**Relevant files:**
- `upstream/plugins/` — 20 plugin directories, each a plugin.json + symlinks

**How it works:**
Each plugin has a `plugin.json` describing its components. Components are symlinks to real files in `hooks/`, `skills/`, or `agents/`. Installing a plugin means resolving the symlinks into the target `.claude/` directory. No file duplication; updates to the source update all users.

**Why it's useful:**
Solves the distribution problem for skill/hook collections. A user installs the plugin collection and gets exactly the components they opted into. New components can be added without changing the install process.

**Known limitations:**
Symlinks can break across filesystem boundaries. Requires a plugin install script to resolve correctly.

**Safe reuse method:** MIT licensed. The plugin.json schema + symlink approach is directly adaptable for any skill/hook distribution system.

**Possible projects:** product-concepts/trusted-skill-marketplace/, lab skill distribution

---

## 10. Multi-agent workflow with specialist subagent delegation

**Purpose:** Structure a complex engineering task as a workflow in which a coordinator agent delegates to specialist subagents, each with a focused responsibility and access constraints.

**Source repository:** sources/full-product-engineering-agent-stack/ (garrytan/gstack, MIT)
**Relevant files:**
- `upstream/ARCHITECTURE.md` — workflow design
- `upstream/AGENTS.md` — agent definitions
- `upstream/CLAUDE.md` — coordinator instructions

**How it works:**
A coordinator agent receives a high-level task, decomposes it into subtasks, and spawns specialist subagents (e.g., "backend engineer", "frontend engineer", "code reviewer") with task-specific context and constraints. Each subagent works in isolation; the coordinator integrates results.

**Why it's useful:**
Long-horizon engineering tasks exceed single-agent context windows. Specialist agents can be given narrower permissions and context, reducing risk and improving focus.

**Known limitations:**
Coordination overhead. Subagents may diverge from each other's assumptions without explicit shared state management.

**Safe reuse method:** MIT licensed. The AGENTS.md + CLAUDE.md coordinator pattern is directly reusable. Adapt specialist definitions to the target domain.

**Possible projects:** Any complex multi-step AI engineering workflow

---

## 11. Append-only decision log for agent actions

**Purpose:** Record every agent decision (proposed action, policy evaluation result, reason) as an append-only audit log for later review or compliance.

**Source repository:** sources/deterministic-agent-safety/ (poshan0126/dotclaude, MIT) + lab's components/agent-safety-firewall/
**Relevant pattern:** PostToolUse hook writing structured JSON to a log file.

**How it works:**
After each tool call evaluation, the hook appends a JSON record to a log file:
`{ "timestamp", "tool", "input_hash", "decision", "reason", "policy_version" }`

**Why it's useful:**
Compliance, debugging, and audit. Any regulated deployment of an AI agent needs to demonstrate what actions were taken and why.

**Known limitations:**
Log files grow unbounded without rotation. Sensitive inputs must be hashed, not logged raw.

**Safe reuse method:** Already implemented in lab components. MIT licensed.

**Possible projects:** agent-permission-firewall, any compliance-regulated AI deployment

---

## 12. Read-only SQL AST validation

**Purpose:** Reject SQL statements that are not pure SELECT before they reach the execution engine.

**Source repository:** sources/database-query-training/ (tsembp/SQL-Gym, MIT)
**Pattern:** Parse submitted SQL, walk the AST, reject if any non-SELECT statement is found.

**How it works:**
Use a SQL parser library (sqlparse, pglast, or similar) to parse the input into an AST. Walk the tree to find statement types. Any statement type other than SELECT → reject with an error message before execution.

**Why it's useful:**
Defense in depth for any SQL sandbox. Lighter weight than process isolation alone. Catches `DROP`, `DELETE`, `UPDATE`, `INSERT`, `CREATE` before they reach the database.

**Known limitations:**
Parser must be correct for the target SQL dialect. SQL injection via valid SELECT (e.g., time-based blind injection) is not caught by AST type checking alone.

**Safe reuse method:** MIT licensed. The pattern is language-agnostic; choose parser library per tech stack.

**Possible projects:** adaptive-sql-learning-platform, any user-submitted SQL execution system

---

## 13. FAISS semantic indexing over chunked text

**Purpose:** Encode a document corpus into dense vector embeddings, store in a FAISS index, and retrieve semantically similar chunks at query time.

**Source repository:** sources/modular-rag-learning/ (tsembp/AI-Study-Mate, MIT) + sources/semantic-audio-search/ (ref-only)
**Relevant files:**
- `upstream/app.py` (modular-rag-learning) — ingestion and query pipeline

**How it works:**
Documents are chunked. Chunks are encoded via a sentence-transformer model into dense vectors. Vectors are stored in a FAISS index (flat or HNSW). At query time, the query is encoded with the same model; FAISS retrieves the K nearest neighbors. Top chunks are passed to the LLM as context.

**Why it's useful:**
High-frequency pattern in this lab (appears in persistent-agent-memory, modular-rag-learning, semantic-audio-search, and Curator). The core index-build and query cycle is reusable across domains.

**Known limitations:**
Flat FAISS indices do not scale past ~1M vectors without IVF/HNSW. Embedding quality determines retrieval quality — model choice matters.

**Safe reuse method:** MIT licensed. FAISS is open-source (Meta, MIT). sentence-transformers is Apache-2.0.

**Possible projects:** modular-rag-learning, persistent-agent-memory, Curator, any semantic search feature

---

## 14. Privacy-aware diff redaction

**Purpose:** Process a git diff to identify and redact sensitive content (API keys, credentials, PII) before sending to an external AI API.

**Source repository:** sources/privacy-safe-commit-assistant/ (sgavriil01/commitgen, ref-only, no license)
**Note:** Reference-only — pattern cannot be copied from this source. Described here as a named pattern.

**How it works (inferred from pattern name and lab context):**
Run `git diff --staged` locally. Pass the diff through a regex/entropy scanner to identify high-probability credentials and PII. Redact (replace with `[REDACTED]`) or reject before sending to the AI API. Process the cleaned diff locally.

**Why it's useful:**
Any AI-assisted developer tool that processes code diffs must decide how to handle sensitive content. The redact-before-API pattern is the most conservative approach.

**Known limitations:**
Regex-based detection has false positives and false negatives. No scanner catches 100% of credential patterns.

**Safe reuse method:** The pattern is describable independently of any specific codebase. Implement using existing credential scanner libraries (gitleaks, truffleHog patterns). Do not copy from commitgen (no license).

**Possible projects:** Any developer tool that sends diffs to external APIs

---

## 15. Two-phase PR review: batch fix → watch loop

**Purpose:** Efficiently close all PR review comments in a structured sequence: first fix all existing comments in batch, then poll until no new bot comments arrive.

**Source repository:** sources/design-agent-reviews/ (pbakaus/agent-reviews, MIT)
**Relevant files:**
- `upstream/` — skill files defining the two-phase protocol

**How it works:**
Phase 1 (synchronous): Fetch all existing PR comments via GitHub GraphQL API. Classify bot vs human. Fix all actionable comments. Commit and push.
Phase 2 (asynchronous): Enter a polling loop with configurable interval. Fetch new comments. If new actionable comments exist, fix and re-enter loop. Exit when poll returns no new comments.

**Why it's useful:**
Eliminates the manual "check the PR, see a new comment, fix it, push, wait, check again" cycle. Applicable to any CI bot (CodeRabbit, Copilot Review, Sourcery, etc.).

**Known limitations:**
Requires GitHub access token. Polling adds latency.

**Safe reuse method:** MIT licensed. The two-phase pattern is architectural; re-implement the GitHub API calls independently.

**Possible projects:** Any agentic CI/CD workflow

---

*Patterns 16–25 (additional patterns identified but lower priority): workspace-scoped tool sandbox, provider-aware skill installation, CSS custom properties themability, design system generation from description, multi-platform skill template distribution, semantic search over audio transcripts, fail-silent vs. fail-loud hook configuration, OWASP-focused security subagent, silent-failure detection subagent, context inheritance in monorepos.*
