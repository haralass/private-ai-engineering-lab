# Domain Synthesis: Design, UI/UX and Marketing

research_date: 2026-06-23
sources_analyzed: 8

---

## Sources analyzed

| Source | Creator | Type | License |
|---|---|---|---|
| design-agent-reviews (agent-reviews) | Paul Bakaus (pbakaus) | VENDORED | MIT |
| design-quality-and-review (impeccable) | Paul Bakaus (pbakaus) | VENDORED | Apache-2.0 |
| design-taste | Leonxlnx | VENDORED | MIT |
| interaction-and-motion-design (emilkowalski/skills) | Emil Kowalski | REF-ONLY | none |
| interaction-motion-toast (sonner) | Emil Kowalski | VENDORED | MIT |
| ui-ux-reference (ui-ux-pro-max-skill) | nextlevelbuilder | VENDORED | MIT |
| product-marketing-context (marketingskills) | Corey Haines | VENDORED | MIT |
| writing-quality (stop-slop) | Hardik Pandya | VENDORED | MIT |

---

## Creator connections

**Paul Bakaus (pbakaus)** contributed 2 sources:
- `agent-reviews`: bridges GitHub PR review comments to AI agents — a developer workflow tool
- `impeccable`: comprehensive AI skill for production-grade UI/frontend quality

These two sources are complementary: impeccable teaches quality standards; agent-reviews closes the feedback loop by surfacing quality violations from CI bots and human reviewers back to the agent. Together they form a complete AI-driven UI quality loop.

**Emil Kowalski (emilkowalski)** contributed 2 sources:
- `sonner`: production React toast library — code
- `interaction-and-motion-design`: motion/animation skills — reference-only, no license

Emil represents a practitioner whose taste-level work has been studied both as code (sonner) and as skill instructions (motion design). The connection: his open-source UI components embody the same motion principles his skill instructions encode. Two manifestations of the same design philosophy.

---

## Cross-source patterns

**Quality-with-measurement pattern** (writing-quality, design-quality-and-review):
Both sources use a scoring rubric to convert subjective quality into a measurable gate. `stop-slop` scores writing on 5 dimensions (35/50 threshold). `impeccable` has multi-stage checklists. This pattern — quality rubric with a pass/fail threshold — is reusable for any domain.

**Foundation-skill-first pattern** (product-marketing-context):
The marketingskills ecosystem uses a foundation skill (product-marketing) that all other skills load first for context injection. This is the most scalable architecture for large multi-skill collections. Not yet present in the lab's own skill library.

**BM25 + CSV retrieval pattern** (ui-ux-reference):
The only source in the design domain that uses programmatic retrieval over structured knowledge. All other sources are static markdown. This hybrid approach scales better as the knowledge base grows.

**Practitioner-authored taste standards** (design-taste, interaction-and-motion-design, writing-quality):
Multiple sources encode a practitioner's taste as machine-readable instructions. The underlying insight: LLMs default to generic output; injecting high-specificity taste rules shifts the output distribution toward higher quality. This is the core value proposition of the design skill category.

---

## Combinatorial opportunities

**Combination 1: Full AI frontend quality stack**
```
impeccable (design quality standards)
+ agent-reviews (CI feedback loop)
+ interaction-motion-toast (polished notification UX)
+ writing-quality (UI copy quality)
= End-to-end AI-driven frontend quality assurance
```
What this enables: an AI coding agent that writes frontend code at near-professional quality, receives automated feedback, and iteratively improves — with all quality dimensions covered.

**Combination 2: Design-to-marketing skill pipeline**
```
ui-ux-reference (design intelligence)
+ product-marketing-context (marketing context)
+ writing-quality (copy quality)
= AI-assisted product + marketing system for early-stage products
```
What this enables: a founder using Claude Code gets design recommendations AND marketing copy for the same product in a coherent context-aware workflow.

**Combination 3: Design taste injection stack**
```
design-taste (visual taste rules)
+ interaction-and-motion-design (motion quality)
+ impeccable (full UI quality framework)
= High-fidelity taste instruction set
```
What this enables: Claude Code produces UI code that is less obviously AI-generated.

---

## Gap analysis

1. **No testing skill** for design — no source covers automated visual regression, screenshot diffing, or Playwright-based UI testing. Agent-reviews handles CI feedback but not automated visual testing.

2. **No dark mode / accessibility depth** — ui-ux-reference has accessibility flags, but no source focuses deeply on WCAG compliance automation or dark mode system design.

3. **No design token system** — no source covers design tokens (CSS custom properties as a system). The impeccable skill uses them, but no source teaches designing a token architecture.

4. **Single-creator taste** — design-taste and interaction-and-motion-design encode individual practitioners' taste. No source represents consensus standards (e.g., Apple HIG, Material Design, WCAG).

---

## Market context

**AI UI generation tools** (inferred from general knowledge, not web-searched in this session):
The market for AI design tools is growing rapidly (Figma AI, v0 by Vercel, Bolt, Lovable). The unique angle of this lab's design sources is the **quality gate layer** — not generation, but quality assurance of AI-generated output. This is the differentiated position: agent-reviews + impeccable represent a QA layer, not a generation layer.

**Marketing automation tools** (inferred):
The marketingskills source competes in the same conceptual space as Jasper, Copy.ai, and general-purpose AI writing tools. The differentiator is the skill-graph architecture: context-aware, not isolated prompt-by-prompt.

---

## Top business opportunities from this domain

**High commercial potential:**
1. **AI design quality audit as a CI service** — using impeccable standards + agent-reviews as a cloud service for frontend PRs. Every team using an AI coding agent has this problem. Precedent: Deque's axe-core CI integration.

2. **Founder-complete marketing content system** — combining product-marketing-context + writing-quality into a coherent product launch tool for solo founders / early-stage startups. Target: founders who can't hire a marketing team yet.

**Medium commercial potential:**
3. **Design intelligence API** — ui-ux-reference's BM25 + CSV approach exposed as an API, with curated vertical-specific databases (fintech UI patterns, healthcare UI guidelines). Niche but monetizable as a developer tool.

**Lower commercial potential:**
4. **Writing quality CI gate** — automated slop detection in documentation or PR descriptions. Real problem, but narrow addressable market.

---

## Recommended next research actions

1. Validate whether the "AI design quality gate in CI" concept has real demand: find 5 frontend teams using AI coding agents, ask what they do about UI quality control
2. Research whether Figma AI, v0, or Vercel are building quality assessment layers (competitive risk for opportunity #1)
3. Test impeccable + agent-reviews combination in a real product build to validate the quality delta
4. Write the lab's own version of interaction-and-motion-design skill (clean-room, since the original has no license)
