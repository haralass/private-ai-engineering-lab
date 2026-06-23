# Source to Opportunity Matrix

research_date: 2026-06-23
covers: all 30 lab sources (15 vendored, 15 reference-only)

Each row answers: what does this source contribute technically, what business opportunities
does it enable, and what is the recommended action?

Technical Value — 1 (low) to 5 (high) for reuse potential in a product.
Business Relevance — 1 (low) to 5 (high) for impact on an identified opportunity.
Priority — how urgently to engage with this source for product development.

---

## Agent Engineering and Safety

| Source | License | Type | Technical Value | Key Patterns | Business Applications | Related Ideas | Market Evidence | Reuse Status | Priority | Recommended Action |
|---|---|---|---|---|---|---|---|---|---|---|
| deterministic-agent-safety (poshan0126/dotclaude) | MIT | VENDORED | 5/5 | Fail-closed hook engine, plugin marketplace, append-only audit log, fixture-based hook tests | Agent permission firewall product; compliance audit layer for any AI deployment | agent-permission-firewall | Real problem: every dev team using agents needs this. No off-shelf product verified. | Directly reusable — already clean-roomed in components/ | CRITICAL | Core of the agent-permission-firewall product; already in prototype stage. Build the product. |
| full-product-engineering-agent-stack (garrytan/gstack) | MIT | VENDORED | 4/5 | Multi-agent coordinator/specialist pattern, AGENTS.md spec format | Multi-agent engineering service; complex coding agent workflow product | agent-permission-firewall (safety layer) | Enterprise interest in autonomous engineering workflows is documented | Reusable coordinator pattern — adapt AGENTS.md | HIGH | Study coordinator pattern; adapt for lab's own multi-agent engineering workflows. |
| structured-agent-development (obra/superpowers) | MIT | VENDORED | 3/5 | Skill extension framework, provider-aware skill routing | Skill marketplace; agent customization product | trusted-skill-marketplace | Skill ecosystems growing with Claude Code adoption | Reusable framework patterns | MEDIUM | Reference for skill marketplace architecture; not a standalone product. |
| terminal-coding-agent (MoonshotAI/kimi-code) | MIT | VENDORED | 4/5 | Terminal agent scaffold integrated with Kimi-K2 model; tool use implementation | Foundation for terminal AI coding tool or IDE plugin | (connected with kimi-model-family) | Kimi-K2: 65.8% SWE-bench Verified, $200M+ ARR at parent | Legal review needed: Kimi K2 display obligation at scale | HIGH | Use as reference for terminal coding agent patterns; do not ship without EU legal review of Kimi-K2 license at scale. |
| anthropic-skills (Anthropic) | — | REF-ONLY | 3/5 | Official skill patterns, prompt templates | Baseline for all skill development in lab | All skill-based products | Anthropic's own patterns — highest credibility | Reference only; no local code | MEDIUM | Use as canonical reference for any Claude Code skill developed in lab. |
| vercel-skills (vercel-labs) | — | REF-ONLY | 3/5 | Framework-specific skill patterns, Next.js integration | Framework-specific skill library; developer tooling | trusted-skill-marketplace | Vercel ecosystem very large; Next.js dominant for web | Reference only; no local code | MEDIUM | Reference for creating framework-specific skills. |

---

## Memory, Context, and Evaluation

| Source | License | Type | Technical Value | Key Patterns | Business Applications | Related Ideas | Market Evidence | Reuse Status | Priority | Recommended Action |
|---|---|---|---|---|---|---|---|---|---|---|
| persistent-agent-memory (thedotmack/claude-mem) | Apache-2.0 | VENDORED | 3/5 | File-based persistent memory store, FAISS indexing over session history | Agent memory product; context persistence for long-horizon AI workflows | (supports Curator's memory design) | Memory limitations are a top pain point with AI agents | Reusable FAISS + file-store pattern | MEDIUM | Study memory storage design; apply to Curator's memory layer. |
| modular-rag-learning (tsembp/AI-Study-Mate) | MIT | VENDORED | 4/5 | FAISS + chunked RAG pipeline, modular document ingestion, multi-subject index | AI learning platform; RAG-as-a-service; adaptive tutoring | adaptive-sql-learning-platform | EdTech AI market active; student tools growing | Reusable RAG pipeline; MIT | HIGH | RAG pipeline is the strongest reusable module; base adaptive-sql-learning-platform backend on it. |
| code-review-assistant (pbakaus) | None | REF-ONLY | 3/5 | Code review automation with LLM; quality gate pattern | Developer tool: AI code review service | (no product concept yet) | GitHub Copilot Review, CodeRabbit, Sourcery all active and paid | Reference only; cannot copy | LOW | Gap may not exist — market has strong players. Skip unless differentiating angle found. |
| design-agent-reviews (pbakaus/agent-reviews) | MIT | VENDORED | 4/5 | Two-phase PR review loop, bot/human comment classification, GitHub GraphQL integration | Automated CI bot that closes PR review comments; AI quality assurance service | (no product concept yet — opportunity) | Every team using PR review bots needs this automation | Directly reusable; MIT | HIGH | Build the two-phase PR review pattern into lab's own workflow first; then evaluate as product. |
| design-quality-and-review (pbakaus/impeccable) | Apache-2.0 | VENDORED | 5/5 | 23-command UI lifecycle review, AI slop detection taxonomy, scoring rubric | AI design quality audit as CI service; UX quality gate | functional-generative-design | No verified product combines LLM design review + CI gate + automated fix | Reusable review framework; Apache-2.0 | HIGH | High commercial potential as CI-integrated design review. Combine with agent-reviews for full quality loop. |

---

## Design, UI/UX, and Marketing

| Source | License | Type | Technical Value | Key Patterns | Business Applications | Related Ideas | Market Evidence | Reuse Status | Priority | Recommended Action |
|---|---|---|---|---|---|---|---|---|---|---|
| ui-ux-reference (nextlevelbuilder/ui-ux-pro-max-skill) | MIT | VENDORED | 4/5 | BM25+CSV knowledge base search, design system generator, 161 color palettes, 67 UI styles | Design system tooling; UI knowledge base product | functional-generative-design | Designer tooling market growing; Figma/Framer ecosystems large | Directly reusable CSV+BM25 pattern; MIT | HIGH | BM25+CSV pattern is most portable. Use for any domain-specific knowledge retrieval in lab products. |
| writing-quality (hardikpandya/stop-slop) | MIT | VENDORED | 3/5 | Scoring rubric with threshold (5-dim × 10 = 50 pts, threshold 35), AI slop detection rules | Writing quality gate; documentation quality CI | (no product concept yet) | Content quality tools exist (Grammarly, Hemingway) but no AI slop gate | Directly reusable rubric pattern; MIT | MEDIUM | Port the threshold rubric pattern to other quality gates (code review, design). Not a standalone product. |
| product-marketing-context (coreyhaines31/marketingskills) | MIT | VENDORED | 3/5 | Foundation-skill-first context injection, 30+ marketing skills, 7-cluster dependency graph | Marketing skill library; AI marketing assistant | (no product concept yet) | AI marketing tools very crowded (Jasper, Copy.ai, etc.) | Reusable architectural pattern; MIT | MEDIUM | Apply foundation-skill-first pattern to lab's own skill ecosystem. Marketing use case too crowded. |
| interaction-motion-toast (emilkowalski/sonner) | MIT | VENDORED | 5/5 | Module-level observable store, CSS custom properties themability, Promise toast pattern | Production-ready toast notifications for any React product in lab | Any React frontend product | 13k+ GitHub stars; standard in React ecosystem | Import directly (MIT) or replicate pattern | CRITICAL | Use directly in any React product the lab builds. Zero switching cost. |
| interaction-and-motion-design (emilkowalski) | None | REF-ONLY | 3/5 | Motion design principles, microinteraction patterns | UI quality standards | functional-generative-design | No LICENSE — cannot copy | Clean-room from Emil's public blog (emilkowal.ski) | LOW | Reference for design principles only. Do not copy skill files. |
| design-taste (Leonxlnx) | MIT | VENDORED | 2/5 | Taste standards for AI-generated UI | AI design quality gate | functional-generative-design | Taste standards are subjective; hard to productize | Reference only; low specificity | LOW | Use as inspiration; not a core reusable pattern. |

---

## Infrastructure and Developer Tools

| Source | License | Type | Technical Value | Key Patterns | Business Applications | Related Ideas | Market Evidence | Reuse Status | Priority | Recommended Action |
|---|---|---|---|---|---|---|---|---|---|---|
| durable-background-job-queue (sgavriil01/forgequeue) | MIT | VENDORED | 5/5 | SQL-backed job queue, SKIP LOCKED optimistic claiming, typed status enum, JSONB payload, Go API scaffold | AI task queue as a service; background job processing for AI pipelines | (no product concept yet — opportunity) | Job queue space: Sidekiq ($2k+/month enterprise), Hatchet ($79-$799/month), BullMQ (open-source) | Reusable data model and API patterns; MIT. Worker not implemented yet. | HIGH | Implement the worker layer; evaluate as infrastructure for Curator and other lab async workflows. |
| asynchronous-job-processing (sgavriil01/sms-platform) | None | REF-ONLY | 3/5 | SMS delivery workflow, queue-worker pattern | Pattern demonstrates the use case forgequeue was built to solve | (hypothesis: motivated forgequeue) | No license — cannot copy | Reference for understanding queue patterns only | LOW | Reference only. Confirms forgequeue is motivated by real use case. |
| semantic-audio-search (sgavriil01) | None | REF-ONLY | 3/5 | Whisper transcription + FAISS indexing pipeline | Audio search product; meeting intelligence tool | (no product concept yet) | Otter.ai, Fireflies.ai, Grain active in meeting intelligence | Reference only; no license | LOW | Gap filled by existing products. Monitor but no immediate action. |
| privacy-safe-commit-assistant (sgavriil01/commitgen) | None | REF-ONLY | 3/5 | Privacy-aware diff redaction before API call | Privacy-preserving developer tool | (no product concept yet) | Gitprivacy, local LLM inference alternatives | Reference pattern only; no license | LOW | Privacy-aware diff redaction pattern is valuable. Implement independently with a credential scanner library. |
| change-monitoring-notifications (tsembp) | None | REF-ONLY | 2/5 | Polling + notification bot for platform changes | Change monitoring service | intelligent-change-monitoring | StatusPage, Datadog, custom webhooks fill this space | Reference only; no license | LOW | Market well-served. Intelligent-change-monitoring product concept needs differentiation beyond simple polling. |

---

## Data, Learning, and Student Repositories

| Source | License | Type | Technical Value | Key Patterns | Business Applications | Related Ideas | Market Evidence | Reuse Status | Priority | Recommended Action |
|---|---|---|---|---|---|---|---|---|---|---|
| database-query-training (tsembp/SQL-Gym) | MIT | VENDORED | 4/5 | Read-only SQL sandbox (AST validation + process isolation), exercise schema, frontend+backend scaffold | Adaptive SQL learning platform; SQL sandbox as a service | adaptive-sql-learning-platform | DataCamp ($25-$300/yr), SQLZoo (free), Mode Analytics (free SQL editor) — none with AI feedback | Reusable sandbox patterns; MIT. Security model needs production re-audit. | HIGH | SQL sandbox is the technically hardest part of the learning platform. Start here. |
| synthetic-relational-data (tsembp/one-stop-ride-hail) | None | REF-ONLY | 3/5 | Ride-hail domain relational schema, synthetic data generation patterns | Synthetic data platform; compliance document generation | synthetic-test-data-platform | Mostly (free Faker, Mimesis) to (enterprise Tonic.ai, Mostly AI) | Reference only; no license | MEDIUM | Relational schema design is reusable as reference; implement independently. |
| data-structure-search-engine (tsembp/EPL231) | None | REF-ONLY | 1/5 | Basic data structure implementations | Academic only | (no product application) | No commercial application | Reference only; no license | SKIP | No actionable pattern for product development. |
| algorithm-benchmarking (tsembp/Hitting-Set-Problem) | None | REF-ONLY | 1/5 | NP-hard problem benchmarking | Academic only | (no product application) | No commercial application | Reference only; no license | SKIP | No actionable pattern for product development. |
| business-energy-dispatch (sgavriil01) | None | REF-ONLY | 2/5 | PV+battery dispatch optimization model | Energy optimization tool; industrial IoT | business-energy-optimization | EnergyHub, AutoGrid, Stem fill enterprise; Voltaware for residential | Reference only; no license | LOW | Niche market; high domain expertise required. Deprioritize unless founder has energy domain knowledge. |

---

## Model Runtimes

| Source | License | Type | Technical Value | Key Patterns | Business Applications | Related Ideas | Market Evidence | Reuse Status | Priority | Recommended Action |
|---|---|---|---|---|---|---|---|---|---|---|
| model-layer-streaming (lyogavin/airllm) | Apache-2.0 | VENDORED | 4/5 | Layer-wise LLM inference (4-8GB VRAM for 70B models), quantization support, Apple Silicon (MLX) | Local AI inference product; privacy-preserving AI tool for SMEs | (no product concept yet — opportunity) | Local AI inference: Ollama, LM Studio (free) + privacy angle (no cloud) | Import directly (Apache-2.0). Slow — 30s+ per inference | MEDIUM | Strong for privacy-preserving AI angle. Too slow for interactive products; useful for batch processing. |
| glm-model-family (Zhipu AI) | Apache-2.0 / MIT | REF-ONLY | 3/5 | 744B/40B MoE architecture, 200K context (1M with v5.2), self-hosted on EU infra for GDPR compliance | EU-compliant self-hosted AI API alternative | (Curator backend option) | Zhipu IPO Jan 2026; backed by Alibaba, Tencent, Saudi Aramco | Use via EU self-hosted instance only; API with EU personal data is non-compliant | MEDIUM | Viable EU-compliant alternative IF self-hosted in EU. Evaluate for Curator backend alongside Claude API. |
| kimi-model-family (MoonshotAI) | Modified MIT | REF-ONLY | 4/5 | 1T/32B MoE, 65.8% SWE-bench Verified, Muon optimizer, strong coding benchmark | Foundation model for coding tool; agent backend | terminal-coding-agent | $20B valuation, $200M+ ARR; most competitive open-weight coding model | Use API; if product exceeds 100M MAU or $20M/month revenue → must display "Kimi K2" in UI | HIGH | Best open-weight model for coding tasks. Evaluate as backend for coding-focused products. License threshold unlikely to bind at lab scale. |

---

## Summary by Priority

**CRITICAL (use immediately, foundational):**
- deterministic-agent-safety — agent-permission-firewall product + all lab agent safety
- interaction-motion-toast (sonner) — use in every React frontend

**HIGH (core to named product concepts):**
- modular-rag-learning — adaptive-sql-learning-platform backend
- database-query-training — SQL sandbox (hardest technical piece of SQL platform)
- design-quality-and-review (impeccable) — AI design CI service
- design-agent-reviews — two-phase PR loop automation
- ui-ux-reference — BM25+CSV retrieval pattern
- full-product-engineering-agent-stack — multi-agent coordinator pattern
- terminal-coding-agent + kimi-model-family — coding agent with SOTA model
- durable-background-job-queue — async infrastructure for all lab products

**MEDIUM (reuse patterns, monitor):**
- structured-agent-development, anthropic-skills, vercel-skills — skill ecosystem references
- persistent-agent-memory — memory layer for Curator
- synthetic-relational-data — schema reference for synthetic data platform
- writing-quality — rubric pattern for other quality gates
- product-marketing-context — architectural pattern (not product)
- model-layer-streaming — local/private AI inference
- glm-model-family — EU self-hosted alternative to Claude API

**LOW (reference only, no direct reuse):**
- interaction-and-motion-design, design-taste — design principles reference
- asynchronous-job-processing, privacy-safe-commit-assistant, semantic-audio-search — reference patterns
- change-monitoring-notifications, business-energy-dispatch — low commercial signal
- code-review-assistant — market crowded

**SKIP (academic, no product application):**
- data-structure-search-engine, algorithm-benchmarking
