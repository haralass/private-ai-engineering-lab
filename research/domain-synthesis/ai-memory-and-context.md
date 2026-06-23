# Domain Synthesis: AI Memory, Context and Skills

## Sources analyzed (4)

| Source | Type | License | Key domain |
|--------|------|---------|-----------|
| `sources/persistent-agent-memory/` (claude-mem) | Vendored, Apache-2.0 | Memory persistence via hooks + SQLite/Chroma + MCP |
| `sources/modular-rag-learning/` (AI-Study-Mate) | Vendored, MIT | Document RAG pipeline for educational content generation |
| `sources/anthropic-skills/` (Anthropic skills) | Reference-only, unclear | Agent Skills specification + first-party skills |
| `sources/vercel-skills/` (Vercel skills CLI) | Reference-only, no license | Cross-agent skills package management toolchain |

---

## Cross-source patterns

### Pattern 1: Hook-native vs. SDK-native integration

Claude-mem integrates with Claude Code via lifecycle hooks (`PostToolUse`, `SessionStart`, etc.) — it requires zero changes to agent code and works by interception. The Anthropic and Vercel skills systems integrate via file placement in `~/.claude/skills/` — also zero-code changes. The RAG learning project, by contrast, requires explicit SDK integration (LangChain chains, FAISS API calls).

The hook-native and file-placement approaches represent a convergent pattern: extend AI agent behavior without modifying the agent. This is the correct model for tooling that wants broad adoption without requiring agent developers to install libraries.

### Pattern 2: Progressive disclosure as a shared token-efficiency principle

Claude-mem's 3-layer MCP search (index → timeline → batch fetch) is an explicit implementation of progressive disclosure for token efficiency. The same principle appears implicitly in the RAG study assistant: retrieve top-5 chunks before calling the LLM. The Anthropic Skills spec enables the same principle at the skill level — Claude loads a skill's description first (minimal tokens) and only reads the full SKILL.md body when the skill is invoked (full tokens).

All four sources reflect the same underlying constraint: LLM context windows are expensive, so systems should reveal information incrementally rather than front-loading everything.

### Pattern 3: Modular separation of storage, retrieval, and generation

All four sources decompose the problem into:
- **Storage:** SQLite + Chroma (claude-mem), FAISS on disk (RAG learning), filesystem (skills)
- **Retrieval:** FTS5 + semantic vector search (claude-mem), FAISS similarity (RAG), directory scan (skills)
- **Generation:** Claude Agent SDK (claude-mem), ChatOpenAI via LangChain (RAG), Claude via skill context injection (skills)

The storage and retrieval layers are cleanly separable from the generation layer in all four cases. This is a design pattern — not an accident — and suggests that the correct architecture for any system combining memory + RAG + skills has these three layers as separate concerns.

### Pattern 4: Adapter/registry pattern for multi-agent compatibility

Claude-mem uses `PlatformAdapter` implementations per IDE (claude-code, codex, cursor, windsurf, gemini-cli). The Vercel skills CLI uses an agent registry mapping agent names to skill directory paths. Both are responses to the same problem: the ecosystem has multiple AI coding agents with incompatible integration points, and tools need to support all of them without per-agent forks.

The adapter/registry pattern is the dominant solution for multi-agent compatibility in this ecosystem. Any new tool built for this lab should adopt it from day one.

### Pattern 5: Fail-open / graceful degradation

Claude-mem's hooks are explicitly fail-open: if the worker is unavailable, hooks return `continue: true` silently. The RAG study assistant's `summarizer.py` falls back from `retriever.invoke()` to `retriever.get_relevant_documents()` if the newer API is unavailable. The Vercel skills CLI falls back to `~/.agents/skills/` when per-agent directories fail.

All four sources demonstrate that tools in the AI agent ecosystem must degrade gracefully — the AI agent workflow must never be blocked by a memory or context system failure.

---

## Combinatorial opportunities

### Combination A: claude-mem + RAG study assistant = Persistent learning memory

**What it is:** A system where a student's study sessions are captured by claude-mem-style hooks (not just code tool use, but document reads and Q&A interactions), and the accumulated observations are searchable via a RAG pipeline tuned for educational content rather than code. Flashcard and quiz generation would be seeded from the user's personal observation history, not just the current document.

**Technical approach:**
- Extend claude-mem's `ObservationRecord` type with an `education` observation type
- Replace claude-mem's generic observation summarization prompt with a mode (in `plugin/modes/`) tuned for study material extraction
- Add the RAG study assistant's FAISS embedding pipeline as an alternative vector store layer alongside Chroma
- Create a `study-session` skill (Agent Skills spec) that combines `mem-search` (query what was studied before) with flashcard generation (generate new cards on unfamiliar material)

**Why it's valuable:** Students lose context across study sessions just as developers lose context across coding sessions. The same architecture solves both problems. The differentiation is the observation mode and the UI.

### Combination B: Anthropic skills spec + claude-mem = Memory-aware skills

**What it is:** Skills that are context-aware, querying the user's past work history before taking action. For example, a `smart-refactor` skill that queries claude-mem for past refactoring decisions on the same codebase before proposing changes — avoiding undoing a decision made three months ago.

**Technical approach:**
- Skills that begin with a `mem-search` MCP tool call to retrieve relevant past observations
- The skill's SKILL.md body defines the retrieval query and how to incorporate past context into the generation prompt
- The `plugin/skills/standup/` skill in claude-mem is an early example of this pattern (it queries memory for recent work)

**Why it's valuable:** Today's skills are stateless — they act on the current context window only. Memory-aware skills represent a qualitative improvement: the skill "knows" what the user has done before.

### Combination C: Vercel skills CLI + custom skills library = Installable lab skill ecosystem

**What it is:** A curated set of skills for common lab workflows (research, code review, document analysis, study material generation) distributed as a single installable package via `npx <lab-skills>` or via the Vercel skills registry.

**Technical approach:**
- Write original skills in SKILL.md format for: flashcard generation from code docs, RAG-based Q&A over uploaded documents, memory-aware standup, cross-project knowledge search
- Package them using the same distribution pattern as claude-mem (`npx install` + symlink registration)
- Use the find-skills pattern to auto-detect and register the skills across all installed agents

**Why it's valuable:** Skills are currently siloed — users must manually discover and install them. A curated, installable bundle reduces friction to zero.

### Combination D: claude-mem observation types + RAG flashcard schema = Knowledge extraction pipeline

**What it is:** A pipeline where claude-mem's structured observations (`type: bugfix | feature | decision | discovery | change`) are used as labeled training data for a knowledge extraction model, and the RAG flashcard schema (`{front, back}`) is used to auto-generate learning material from code history.

**Technical approach:**
- Extend `ObservationRecord` to output `{front: "What did we do when X?", back: <observation narrative>}` pairs
- Weekly automated skill execution that queries the last N observations and generates a "codebase knowledge flashcard deck" for developer onboarding or knowledge transfer
- The RAG pipeline (FAISS + all-MiniLM-L6-v2) serves as the retrieval layer for finding relevant observations to include in a given flashcard topic

**Why it's valuable:** Developer knowledge transfer is a known pain point. An auto-generated flashcard deck from actual project history is more valuable than documentation written by hand because it reflects what was actually done.

---

## Gap analysis

### Gap 1: No cross-session user intent tracking

Claude-mem captures tool use (what Claude did) but not user intent (what the user wanted). The `UserPromptRecord` captures the literal prompt text but doesn't extract intent. A memory system that also models "what problems is this user consistently trying to solve?" would enable proactive assistance.

**Opportunity:** Add an intent extraction pass to the `UserPromptSubmit` hook — use the LLM to classify the user's intent (debugging, exploration, feature development, learning) alongside the prompt text. Over time, the system builds a user goal model, not just an action log.

### Gap 2: No structured knowledge graph

Both claude-mem (SQLite + Chroma) and the RAG study assistant (FAISS) use flat vector stores. Zep's temporal knowledge graph (https://codepointer.substack.com/p/agent-memory-systems-and-knowledge) represents a more sophisticated model where facts have timestamps and state changes are modeled, not just the latest value. Neither vendored source implements this.

**Opportunity:** Add a lightweight knowledge graph layer (Neo4j or SQLite with explicit entity/relation tables) on top of claude-mem's existing SQLite store, extracting entity relationships from observations rather than storing them as unstructured text.

### Gap 3: No spaced repetition / forgetting curve model

All four sources treat memory as append-only — they add observations but never model decay or relevance over time. A human-inspired memory system would down-weight old memories that haven't been reinforced and surface them periodically for review (spaced repetition).

**Opportunity:** Add a `last_accessed_at` and `access_count` field to `ObservationRecord`. Implement an SM-2-style scheduler that surfaces observations for review at increasing intervals. The same mechanism applies to both code knowledge (developer learning) and study flashcards.

### Gap 4: No document-based memory ingestion in claude-mem

Claude-mem captures tool-use events but not documents. If a developer reads an architecture doc or a specification PDF outside of Claude Code, that knowledge is not captured. The RAG study assistant knows how to ingest PDFs and DOCX files but doesn't persist them across sessions.

**Opportunity:** A combined system would add a document ingestion endpoint to the claude-mem worker (accepting file paths or URLs), embed the documents using the same HuggingFace model pattern from the RAG study assistant, and store them alongside tool-use observations in the FAISS/Chroma layer with appropriate metadata (source, timestamp, project).

### Gap 5: No skill performance measurement framework

The Anthropic skill-creator skill mentions "benchmark skill performance with variance analysis" — but neither the anthropic-skills nor vercel-skills sources contain a formal skill evaluation framework. The Ragtime harness in claude-mem is the closest thing, but it is specific to claude-mem's own memory evaluation.

**Opportunity:** Build a general skill evaluation harness (inspired by Ragtime's Claude Agent SDK pattern) that runs a skill against a test corpus and scores outputs on defined criteria. This is infrastructure that would benefit the entire skill ecosystem.

---

## Market context

### AI memory infrastructure market

The market for AI memory infrastructure is estimated to reach $2.4 billion by 2028, with dedicated commercial players emerging rapidly (https://mem0.ai/blog/state-of-ai-agent-memory-2026).

Key commercial products and their status as of mid-2026:
- **Mem0:** $24M Series A (October 2025, Basis Set Ventures + Peak XV); 41,000+ GitHub stars; selected as exclusive memory provider in AWS Agent SDK (https://theaiinsider.tech/2025/11/17/mem0-raises-24m-to-launch-universal-ai-memory-platform-for-apps-and-agents/)
- **Zep:** Temporal knowledge graph ("Graphiti") architecture; most mature on structured/relational memory; production-ready (https://codepointer.substack.com/p/agent-memory-systems-and-knowledge)
- **Letta (MemGPT):** OS-inspired three-tier memory (core/recall/archival); strongest for agent self-management; commercial enterprise offering (https://agentmarketcap.ai/blog/2026/04/10/agent-memory-vendor-landscape-2026-letta-zep-mem0-langmem)
- **LangMem SDK:** LangChain-native, supports episodic/semantic/procedural memory types; tight ecosystem integration (https://thenewstack.io/memory-for-ai-agents-a-new-paradigm-of-context-engineering/)
- **claude-mem (thedotmack):** 46K–83K GitHub stars; hook-native (no SDK changes required); Apache-2.0; dominant in the Claude Code ecosystem (https://www.augmentcode.com/learn/claude-mem-46k-stars-persistent-memory-claude-code)

**Claude-mem's differentiation:** Unlike Mem0/Zep/Letta which require SDK-level integration, claude-mem operates entirely via hooks — it is invisible to the agent code. This is a meaningful architectural advantage for the developer tooling use case but a limitation for general-purpose agent applications.

### AI education tools market

The AI tutoring services market is estimated at $X billion (precise market size withheld — sources provide only directional figures); key players include Khanmigo (built on Anthropic Claude, launched to all free-tier users March 1, 2026 after a pilot with 2 million students), Duolingo Max (built on OpenAI GPT-4o, launched 148 new AI-generated courses in April 2025), and Google Guided Learning (Gemini, rolled out August 2025) (https://minssam.com/en/blog/edtech-ai-revolution-khanmigo-duolingo-2026/, https://callsphere.ai/blog/ai-agents-education-khan-academy-duolingo-autonomous-tutoring).

Academic research has validated RAG-based tutoring assistants in real educational settings, including medical education (https://www.nature.com/articles/s41746-025-02022-1) and campus environments (https://www.mdpi.com/2079-9292/14/17/3402). EduRAG (https://scholarworks.sjsu.edu/etd_projects/1527/) addresses domain accuracy and personalized learning support.

**AI-Study-Mate's position:** It is a prototype-quality reference for the RAG-based study assistant pattern. It does not compete with Khanmigo or Duolingo at scale, but it demonstrates the core RAG pipeline needed for a document-centric personal study assistant.

### Claude Code skills ecosystem

Agent Skills launched October 16, 2025. Key marketplace metrics:
- SkillsMP: 1,788,261 SKILL.md files indexed (https://skillsmp.com/)
- claudeskills.info: 658+ skills (https://claudeskills.info/skills/)
- 300,000+ monthly developer visits (from SkillsMP data)
- Third-party marketplaces: claudemarketplaces.com, skills.pawgrammer.com (287+ community skills)

The market is currently fragmented — no single canonical marketplace. Anthropic has not announced an official centralized marketplace.

---

## Competitor landscape

### Memory persistence

| Product | Architecture | Integration model | License | Status |
|---------|-------------|------------------|---------|--------|
| claude-mem | SQLite + Chroma + hooks | Hook-native (no SDK) | Apache-2.0 | 46K+ stars, active |
| Mem0 | Vector + optional graph | SDK integration | Apache-2.0 (open source) | $24M funded, AWS partnership |
| Zep | Temporal knowledge graph (Graphiti) | SDK integration | Apache-2.0 | Production-ready |
| Letta | Three-tier OS-style | SDK integration | Apache-2.0 | Enterprise offering |
| LangMem | Episodic/semantic/procedural | LangChain-native | Unclear | Beta |
| MemGPT (predecessor to Letta) | Context-window management | SDK integration | Apache-2.0 | Superseded by Letta |

### Educational AI / study assistants

| Product | Architecture | Scale | Differentiation |
|---------|-------------|-------|----------------|
| Khanmigo | Multi-agent + Claude | 2M+ pilot users | Socratic method, structured curriculum |
| Duolingo Max | GPT-4o persistent agent | 700K+ students, 148 courses | Language learning, spaced repetition |
| Google Guided Learning | Gemini | Classroom integration | Google Workspace integration |
| AI-Study-Mate | RAG + FAISS + LangChain | Single developer | Open source, MIT, document-centric |
| EduRAG | RAG-based | Research | Academic accuracy focus |

### Skills ecosystem

| Tool | Role | Coverage | Status |
|------|------|----------|--------|
| Anthropic skills repo | Spec + first-party skills | Claude Code | Official, active |
| vercel-labs/skills | Cross-agent CLI installer | 70+ agents | Active, some Claude Code bugs |
| claude-mem skills (plugin/skills/) | Memory-aware skills bundle | Claude Code | Production in claude-mem |
| SkillsMP | Marketplace directory | 1.7M+ skills | Third-party, active |

---

## Top business opportunities (scored: high/medium/low)

### 1. Developer knowledge persistence for teams — HIGH

**Concept:** A hosted version of claude-mem's observation architecture with team-scoped storage, access controls, and cross-developer memory sharing. Developers query "what did my team decide about the auth module last sprint?" and get structured answers from a shared observation corpus.

**Why high:** The memory infrastructure market is in its formative stage ($2.4B estimated by 2028); claude-mem has demonstrated the core hook-native architecture works and has 46K+ GitHub stars validating demand; the missing layer is team/organization-scoped storage with proper authentication and access controls. claude-mem's `server-beta` mode (dual-path in observation handler) suggests this is already planned.

**Moat:** Apache-2.0 codebase that can be adapted; hook-native architecture that competitors (Mem0, Zep) cannot easily replicate without Claude Code extension support; potential first-mover advantage in the Claude Code developer segment.

**Build risk:** Requires the `server-beta` cloud infrastructure to be production-ready; multi-tenant SQLite/Chroma is non-trivial; competition from Mem0 and Zep is well-funded.

### 2. Document-to-learning-content pipeline as an API — HIGH

**Concept:** A REST API that accepts PDF/DOCX uploads and returns structured educational content: flashcards, MCQs, summaries, and study plans. Expose as a B2B API for EdTech platforms (LMS vendors, tutoring companies, certification prep companies) that need to auto-generate content from their existing document libraries.

**Why high:** RAG-based content generation from documents is validated (EduRAG, NeuroBot TA in medical education, NVIDIA's concept-driven teaching assistant); the AI-Study-Mate pipeline demonstrates the core is implementable in ~350 lines of Python; the B2B API model avoids direct competition with Khanmigo/Duolingo; EdTech companies have large existing document libraries but no good way to extract structured learning content at scale.

**Moat:** Proprietary prompt engineering for high-quality structured output (MCQ format, flashcard quality); domain-specific fine-tuning data generated from real learner interactions; integrations with LMS platforms (Canvas, Moodle, Blackboard).

**Build risk:** Quality of AI-generated educational content needs careful evaluation; content accuracy in professional/medical domains requires human review workflows; OpenAI/Claude API costs at scale.

### 3. Enterprise skills governance platform — MEDIUM

**Concept:** A private skills registry + approval workflow + audit dashboard for enterprises deploying Claude Code at scale. Enterprises need to: approve which skills their developers can install, version-pin skills, audit what skills are running, and ensure skills don't exfiltrate sensitive information.

**Why medium:** The skills ecosystem is growing (1.7M+ skills in public registries) but lacks enterprise governance; the supply chain security risk is real (any GitHub repo can be a skill); enterprise Claude Code deployments will need this before they can safely authorize the skills feature at scale. However, the market is early — most enterprises haven't deployed Claude Code widely enough yet to feel this pain.

**Moat:** First enterprise governance product for the skills category; tight Anthropic partnership potential.

**Build risk:** Market timing — the enterprise pain point may not be acute until 2027; Anthropic may build this natively.

### 4. Spaced repetition layer for developer knowledge — MEDIUM

**Concept:** Extend claude-mem's observation system with a spaced repetition scheduler (SM-2 algorithm). Observations that a developer hasn't "reviewed" in a while are surfaced in new session context. The system asks: "Three months ago you decided X about Y — is that still true?" This prevents knowledge decay and architecture drift.

**Why medium:** The technical implementation is straightforward (add `last_accessed_at`, `access_count`, SM-2 interval calculation to `ObservationRecord`); the concept is validated in EdTech (Anki, Duolingo); the application to developer knowledge is novel. However, developer adoption of spaced repetition tools (Anki, etc.) historically requires strong discipline; the value proposition needs to be demonstrated empirically.

**Moat:** Combination of agent memory + spaced repetition is novel; Apache-2.0 claude-mem codebase as foundation.

**Build risk:** User behavior change required; not immediately obvious to developers that they need this.

### 5. Personal study assistant with cross-session memory — MEDIUM

**Concept:** Combine the AI-Study-Mate RAG pipeline with claude-mem's cross-session memory persistence into a personal study assistant that remembers what a student has studied, avoids generating redundant flashcards, and adapts difficulty based on past performance.

**Why medium:** The market is competitive (Khanmigo, Duolingo, Quizlet) but they are platform-specific; a Claude Code-native study assistant that works with the developer's existing document library (textbooks, papers, documentation) is differentiated. The target user is a CS student or developer upskilling, not a K-12 student — a narrower but more technically sophisticated segment.

**Moat:** Cross-session memory is genuinely differentiated from existing study apps; local FAISS storage means sensitive study materials (proprietary docs, exam prep) never leave the user's machine at the retrieval layer.

**Build risk:** The "Ask Me" mode (Q&A) in AI-Study-Mate is not implemented — the most critical feature for a study assistant is the most incomplete; requires significant engineering to reach Khanmigo's level of pedagogical quality.

### 6. Cross-agent skill discovery and portability tool — LOW

**Concept:** A clean-room reimplementation of the vercel-labs/skills find-skills pattern, extended with skill integrity verification, version management, and Claude Code plugin detection (the gap identified in vercel-labs Issue #718).

**Why low:** The market is small (Claude Code developers who install many skills); the vercel-labs/skills tool already exists and is actively maintained; the core bugs (Claude Code symlinks) are being fixed incrementally; the opportunity is to improve an existing tool rather than build a new market.

**Moat:** First tool with skills integrity verification; potential partnership with Anthropic for official marketplace support.

**Build risk:** Vercel Labs may resolve the Claude Code integration issues before a new tool can establish itself; no license on vercel-labs/skills means clean-room reimplementation is required, which is significant engineering effort.

---

## Recommended next research actions

### Immediate (before any build decision)

1. **Deep-read claude-mem's `ContextBuilder.ts` and `ObservationCompiler.ts`** (`sources/persistent-agent-memory/upstream/src/services/context/`) — The token budget algorithm is the core IP of the memory injection system and was not fully read in this pass. Understanding exactly how observations are ranked and compressed determines whether the system is usable at scale.

2. **Read the Agent Skills spec in full** (`spec/agent-skills-spec.md` at https://github.com/anthropics/skills/blob/main/spec/agent-skills-spec.md) — The normative spec for all skill-based work. Mandatory reading before writing any original skills for this lab.

3. **Evaluate Mem0 vs. claude-mem for the team memory use case** — Read Mem0's architecture docs (https://mem0.ai/blog/state-of-ai-agent-memory-2026) and compare their three-level hierarchy (user/session/agent) to claude-mem's project-scoped SQLite approach. The choice between building on claude-mem vs. integrating Mem0 depends on this comparison.

### Near-term (within 2 weeks)

4. **Prototype the document-ingestion gap** — Build a minimal Python function (`ingest_document(file_path, project_name)`) that uses the AI-Study-Mate chunking + FAISS pipeline to index a document, and expose it as an endpoint on the claude-mem worker's HTTP API. This closes the most concrete gap (Gap 4) with the least engineering effort and validates the combination.

5. **Write one original memory-aware skill** — Implement a `study-session` skill (SKILL.md) that calls `mem-search` to retrieve past study observations before generating new flashcards. This validates the Combination A architecture (claude-mem + RAG + skills) end-to-end without building infrastructure.

6. **Review the server-beta client in claude-mem** (`src/services/hooks/server-beta-client.ts`) — The dual-path architecture (local worker vs. server-beta cloud) is the foundation for the team memory business opportunity. Understanding the current server-beta protocol determines how much work remains to build a hosted version.

### Longer-term (before committing to a business opportunity)

7. **Conduct a formal security review of claude-mem's hook scope** — Every tool invocation (including file reads) is captured. Before deploying in any context that handles sensitive data, map the full data flow from tool call → observation storage → Chroma and determine whether PII, credentials, or proprietary code could be stored and exfiltrated.

8. **Research the EdTech API market** — Identify 5 LMS vendors or tutoring platforms that would be natural B2B customers for a document-to-learning-content API. Validate the pricing model (per-API-call vs. per-document vs. subscription) before building.

9. **Benchmark all-MiniLM-L6-v2 vs. OpenAI text-embedding-3-small for code and documentation retrieval** — The RAG study assistant uses all-MiniLM-L6-v2 (local, free); claude-mem uses Chroma with no specified embedding model. Before building a combined system, understand the quality/cost tradeoff for the target document types.

---

*Research completed: 2026-06-23*
*Sources: see individual dossiers for full citation lists*

**Key URLs cited in this synthesis:**
- https://mem0.ai/blog/state-of-ai-agent-memory-2026 (Mem0 state of AI memory report)
- https://theaiinsider.tech/2025/11/17/mem0-raises-24m-to-launch-universal-ai-memory-platform-for-apps-and-agents/ (Mem0 Series A)
- https://agentmarketcap.ai/blog/2026/04/10/agent-memory-vendor-landscape-2026-letta-zep-mem0-langmem (agent memory vendor landscape)
- https://codepointer.substack.com/p/agent-memory-systems-and-knowledge (Zep/Graphiti/Letta/Mem0 comparison)
- https://thenewstack.io/memory-for-ai-agents-a-new-paradigm-of-context-engineering/ (agent memory overview)
- https://machinelearningmastery.com/the-6-best-ai-agent-memory-frameworks-you-should-try-in-2026/ (memory frameworks comparison)
- https://github.com/anthropics/skills (Anthropic skills repository)
- https://code.claude.com/docs/en/skills (Claude Code skills documentation)
- https://github.com/vercel-labs/skills (Vercel skills repository)
- https://skillsmp.com/ (skills marketplace)
- https://claudeskills.info/skills/ (Claude skills catalog)
- https://medium.com/@markchen69/claude-code-has-a-skills-marketplace-now-a-beginner-friendly-walkthrough-8adeb67cdc89 (skills ecosystem overview)
- https://www.augmentcode.com/learn/claude-mem-46k-stars-persistent-memory-claude-code (claude-mem 46K stars)
- https://minssam.com/en/blog/edtech-ai-revolution-khanmigo-duolingo-2026/ (Khanmigo/Duolingo 2026)
- https://callsphere.ai/blog/ai-agents-education-khan-academy-duolingo-autonomous-tutoring (AI agents in education)
- https://www.nature.com/articles/s41746-025-02022-1 (RAG in medical education)
- https://scholarworks.sjsu.edu/etd_projects/1527/ (EduRAG project)
- https://www.mdpi.com/2079-9292/14/17/3402 (RAG teaching assistant research)
- https://github.com/thedotmack/claude-mem (claude-mem repository)
- https://github.com/tsembp/AI-Study-Mate (AI-Study-Mate repository)
