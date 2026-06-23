# Source Research Dossier: product-marketing-context

## Repository identity

- **Name:** marketingskills
- **Creator:** Corey Haines (coreyhaines31)
- **GitHub URL:** https://github.com/coreyhaines31/marketingskills
- **Source path:** sources/product-marketing-context/
- **Pinned commit:** 8bfcdffb655f16e713940cd04fb08891899c47db
- **Retrieved:** 2026-06-22
- **License:** MIT (verified)
- **Import type:** vendored-snapshot

---

## What it actually does

marketingskills is a comprehensive collection of 30+ Claude Code skills covering every layer of B2B/B2C product marketing: SEO, CRO, copywriting, A/B testing, paid ads, email sequences, cold outreach, growth, churn prevention, competitor analysis, launch, pricing, and more. The architecture is skill-graph based: `product-marketing` is the foundation skill read by all others first. Skills reference each other, creating a dependency graph where richer context (from earlier skills) informs subsequent tasks.

---

## Architecture

```
marketingskills/
├── AGENTS.md           — agents configuration
├── CLAUDE.md           — project configuration for Claude Code
├── README.md           — skill catalog and architecture diagram
├── VERSIONS.md         — changelog
├── skills/             — 30+ skill subdirectories, each with a skill.md
│   ├── product-marketing/  — FOUNDATION: read by all other skills first
│   ├── seo-audit/
│   ├── cro/
│   ├── copywriting/
│   ├── cold-email/
│   ├── competitor-profiling/
│   ├── ab-testing/
│   ├── ads/
│   ├── analytics/
│   └── [~25 more skills]
├── tools/              — supporting tool definitions
└── validate-skills*.sh — validation scripts
```

The architecture diagram in README.md shows `product-marketing` at the top of a tree, with 7 skill clusters below it (SEO, CRO, Content, Paid, Growth, Sales, Strategy). Skills within clusters reference each other horizontally (e.g., `copywriting ↔ cro ↔ ab-testing`). This creates a coherent skill ecosystem rather than isolated independent files.

---

## Main modules and important files

| Path | What it does |
|---|---|
| `skills/product-marketing/` | Foundation: product description, target audience, positioning, tone — loaded first by every other skill |
| `skills/copywriting/` | Landing pages, homepage, value propositions — reads product-marketing first |
| `skills/cro/` | Conversion optimization: forms, CTAs, landing page structure |
| `skills/seo-audit/` | Technical SEO + content SEO audit |
| `skills/ai-seo/` | Optimize for AI search engines and LLM-generated answers |
| `skills/cold-email/` | B2B outreach sequences |
| `skills/competitor-profiling/` | Research and profile competitors from URLs |
| `skills/analytics/` | Analytics setup, tracking, measurement |
| `skills/launch/` | Product launch planning |
| `skills/pricing/` | Pricing strategy |
| `validate-skills.sh` | Validates skill file format and structure |

---

## Core technical patterns

**Skill dependency graph** — the product-marketing foundation skill is loaded first by every other skill. This injects product context into every subsequent task, avoiding the "context amnesia" problem where each skill invocation starts cold. [Most valuable architectural pattern in this source]

**Cross-skill referencing** — skills explicitly reference related skills in their frontmatter or content. This allows skills to compose: a cold-email task naturally pulls from copywriting patterns and competitor insights.

**Cluster-based organization** — skills are grouped into 7 functional clusters (SEO/Content, CRO, Paid, Growth, Sales, Strategy). Each cluster is internally coherent.

---

## Novel or interesting mechanisms

The **foundation skill pattern** (product-marketing read first by all others) is the most transferable architectural insight. It solves the context injection problem for skill ecosystems: instead of each skill needing to ask "what is this product?", the foundation skill pre-loads that context. This pattern is reusable for any domain with a common context layer (e.g., a "project-context" foundation skill for engineering tasks).

---

## Data flow

```
User invokes any marketing skill
→ Claude reads skills/product-marketing/ first (product context injection)
→ Claude reads the requested skill file
→ Claude may reference related skills (e.g., copywriting → cro for landing page)
→ Output benefits from product context + skill-specific methodology
```

---

## Dependencies

Pure markdown skill files. No code dependencies. Compatible with Claude Code, Cursor, Windsurf, and any agent supporting the Agent Skills spec.

---

## Security model

Not applicable. No code execution. Skill files only.

---

## Testing strategy

`validate-skills.sh` and `validate-skills-official.sh` — shell scripts that validate skill file format/structure. No functional testing of skill output quality.

---

## Genuinely reusable elements

**Foundation skill pattern**: any multi-skill ecosystem should have a foundation skill that loads shared context. MIT license, directly reusable.

**Skill cluster organization**: grouping 30+ skills into 7 functional clusters with a clear dependency graph is an architectural pattern for any large skill collection.

**Individual skills**: any skill in the collection (copywriting, competitor-profiling, analytics, etc.) can be used directly or adapted. MIT license.

---

## What NOT to reuse

The skills are authored with B2B SaaS marketing assumptions. They may produce suboptimal output for other contexts (consumer apps, hardware, services) without adaptation.

---

## Production-readiness

**MVP-quality to production-ready** depending on the skill. The core skills (copywriting, CRO, SEO) are mature and well-structured. Newer additions may be less tested. Creator (Corey Haines) runs a conversion optimization agency (Conversion Factory), so the marketing fundamentals are practitioner-quality.

---

## Strengths

- 30+ skills covering the full marketing funnel — comprehensive coverage
- Foundation skill pattern solves context injection elegantly
- MIT license, actively maintained
- Built by a practitioner, not a generalist

## Weaknesses

- English-only, B2B/SaaS orientation
- No automated quality evaluation of skill output (unlike writing-quality which has a scoring rubric)
- Skills may drift from best practices as marketing channels evolve

## Technical debt

VERSIONS.md tracks changes but no semantic versioning. Some skills may be more current than others.

---

## Novel or differentiated elements

The foundation-skill-first architecture is differentiated from ad-hoc skill collections. It creates a coherent ecosystem where context compounds across skill invocations.

---

## Possible clean-room adaptations

- A **product-context foundation skill** for engineering tasks (mirrors product-marketing for technical context)
- A **research-context foundation skill** for this lab's business research workflows
- Domain-specific skill collections using the same cluster + dependency graph architecture

---

## Business applications

**Direct use**: immediately useful for any product the lab builds that requires marketing content, launch planning, or growth work.

**Product concept**: A marketing skill marketplace (similar in concept to `product-concepts/trusted-skill-marketplace/`) focused on marketing skills. Corey Haines has already built a de facto marketplace — the competitive question is whether a curated, benchmarked marketplace is differentiated.

---

## Related business ideas in this lab

- `product-concepts/trusted-skill-marketplace/README.md` — marketingskills demonstrates the demand for domain-specific skill collections
- `product-concepts/skill-benchmarking-platform/README.md` — how would you measure if a marketing skill actually improves conversion rates?

---

## Related sources in this lab

- `sources/writing-quality/` — writing quality is a sub-domain of marketing quality
- `sources/anthropic-skills/` and `sources/vercel-skills/` — comparable skill collection sources from different domains
- `sources/design-agent-reviews/`, `sources/design-quality-and-review/` — same skill ecosystem model applied to design

---

## Open questions

- Does the lab intend to use these marketing skills actively for its own product launches?
- Which individual skills in this collection are most immediately relevant to the lab's current product concepts?

---

## Final research conclusion

marketingskills is a high-quality, immediately usable skill collection for any product requiring marketing work. The foundation-skill architecture pattern is the most portable architectural insight. Beyond direct use, it demonstrates the demand for domain-specific, well-organized skill ecosystems — relevant context for evaluating the `trusted-skill-marketplace` product concept.
