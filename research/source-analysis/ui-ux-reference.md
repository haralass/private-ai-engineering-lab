# Source Research Dossier: ui-ux-reference

## Repository identity

- **Name:** ui-ux-pro-max-skill
- **Creator:** nextlevelbuilder
- **GitHub URL:** https://github.com/nextlevelbuilder/ui-ux-pro-max-skill
- **Source path:** sources/ui-ux-reference/
- **Pinned commit:** 53d670cd3050dcbfa19acb0cf0d9e631422f7ca2
- **Retrieved:** 2026-06-22
- **License:** MIT (verified)
- **Import type:** vendored-snapshot
- **Version:** 2.0 (as of pinned commit)

---

## What it actually does

UI UX Pro Max is a design intelligence toolkit that functions as both a Claude Code skill and a CLI tool. It provides a structured, searchable database of: 161 reasoning rules (UX guidelines and anti-patterns), 67 UI styles (glassmorphism, brutalism, etc.), 57 font pairings (with Google Fonts imports), 161 color palettes (by product type), 25 chart types, and stack-specific guidelines for 16 frameworks. The search engine (BM25 + regex hybrid) allows querying any domain by keyword. A design system generator can produce a complete tailored design system from a project description.

---

## Architecture

```
src/ui-ux-pro-max/
├── data/                        — CSV databases (source of truth)
│   ├── products.csv             — 161 product type recommendations
│   ├── styles.csv               — 67 UI styles with AI prompts and CSS keywords
│   ├── colors.csv               — 161 color palettes by product type
│   ├── typography.csv           — 57 font pairings
│   ├── charts.csv               — 25 chart types
│   ├── ux-guidelines.csv        — UX best practices and anti-patterns
│   ├── landing.csv              — page structure and CTA strategies
│   ├── app-interface.csv        — app interface patterns
│   ├── google-fonts.csv         — Google Fonts catalog
│   └── stacks/                  — 16 framework-specific guidelines
├── scripts/
│   ├── search.py                — CLI entry: query → ranked results
│   ├── core.py                  — BM25 + regex hybrid search engine
│   └── design_system.py         — design system generation from prompt
└── templates/
    ├── base/                    — skill-content.md, quick-reference.md
    └── platforms/               — platform configs (claude.json, cursor.json, ...)

cli/                             — npm CLI installer (uipro-cli)
.claude/skills/ui-ux-pro-max/    — Claude Code skill (symlinks to src/)
```

The architecture separates data (CSV), search engine (Python), and skill distribution (markdown + platform configs). The CLI installer bundles all assets (~564KB) for offline use.

---

## Main modules and important files

| Path | What it does |
|---|---|
| `src/ui-ux-pro-max/scripts/search.py` | CLI entry point: parses args, calls core.py, formats output |
| `src/ui-ux-pro-max/scripts/core.py` | BM25 + regex hybrid search, domain auto-detection |
| `src/ui-ux-pro-max/scripts/design_system.py` | Design system generator: takes project description → full system recommendation |
| `src/ui-ux-pro-max/data/styles.csv` | 67 UI styles with keywords, AI prompts, CSS classes |
| `src/ui-ux-pro-max/data/colors.csv` | 161 color palettes with hex values, product type mapping |
| `src/ui-ux-pro-max/data/ux-guidelines.csv` | 161 UX reasoning rules: principle, application, anti-pattern |
| `src/ui-ux-pro-max/data/typography.csv` | 57 font pairings with Google Fonts import strings |

---

## Core technical patterns

**BM25 + regex hybrid search over CSV** — the search engine in `core.py` uses BM25 (Okapi BM25 ranking) combined with regex matching for design knowledge retrieval. Domain auto-detection routes queries to the right CSV without requiring explicit `--domain` flags.

**CSV as structured knowledge base** — all design knowledge is stored in CSVs with consistent schemas. This is a simple, editable, version-controlled alternative to a database. New entries can be added via CSV row without code changes.

**Design system generator** — `design_system.py` takes a natural language description and generates: color palette, typography pairing, UI style, key effects, anti-patterns to avoid. This is a complete design system recommendation in a single CLI call.

**Multi-platform skill distribution** — the same data is packaged for Claude Code, Cursor, Windsurf, and other platforms via platform-specific config files in `templates/platforms/`. Skill content is authored once, distributed to multiple platforms.

---

## Novel or interesting mechanisms

The **BM25 search over a CSV knowledge base** is an efficient pattern for structured domain knowledge retrieval. BM25 provides relevance ranking without requiring a vector store or embedding model — just Python and the `rank_bm25` library. This pattern is reusable for any domain with structured knowledge stored in rows.

The **design system generator** is the most immediately commercially valuable feature: from a one-line description ("Serenity Spa") it produces a complete, actionable design system with palette, typography, style, and anti-patterns. This is a polished user-facing feature, not just a lookup tool.

---

## Data flow

```
User query ("glassmorphism dashboard")
→ search.py parses query, detects domain (style)
→ core.py BM25 scores rows in styles.csv
→ top N results returned with keywords, AI prompts, CSS classes

User describes project ("B2B SaaS analytics platform")
→ design_system.py analyzes description
→ returns: recommended style, color palette, typography, key effects, anti-patterns
```

---

## Dependencies

- Python 3.x
- `rank_bm25` — BM25 implementation
- No heavy ML dependencies (no transformers, no vector store)
- npm (for CLI installer distribution only)

---

## Security model

No security concerns. Python search scripts run locally on CSV files. No network access, no external APIs, no user data.

---

## Testing strategy

No automated tests visible in the vendored snapshot. Quality is maintained via CSV data curation and community contributions (PRs to add/correct entries).

---

## Genuinely reusable elements

**BM25 + CSV knowledge base pattern**: reusable for any domain requiring ranked lookup over structured knowledge. Zero heavy dependencies. MIT license.

**Design system generation pattern**: the logic for mapping a project description to a style/palette/typography combination is a clean, reusable approach for any design-adjacent tool.

**Multi-platform skill distribution**: the template system that generates platform-specific skill files from a single source is directly applicable to any multi-platform skill collection.

---

## What NOT to reuse

The Python scripts depend on a specific CSV schema. Reusing the search engine requires maintaining compatible CSV data.

---

## Production-readiness

**Production-ready as a skill/tool.** v2.0 with a CLI installer on npm (uipro-cli). Actively maintained with GitHub releases. The creator has a broader ecosystem (NextLevelBuilder.io, ClaudeKit.cc). Not a prototype.

---

## Strengths

- 161 reasoning rules + 67 styles + 57 font pairs + 161 palettes — comprehensive coverage
- BM25 search is fast and dependency-light
- Design system generator is immediately commercially useful
- Multi-platform skill distribution is architecture-forward
- MIT license

## Weaknesses

- Python requirement adds friction (vs. pure markdown skills)
- Quality of CSV data depends on curator — some entries may be generic
- No automated quality testing of the design recommendations

## Technical debt

CSV data requires ongoing curation to remain current (design trends shift). The sync script (`_sync_all.py`) suggests there is some automated data maintenance.

---

## Novel or differentiated elements

The BM25 + CSV pattern is the clearest differentiation from pure-markdown skills: it provides programmatic, ranked retrieval rather than static inclusion. This scales better as the knowledge base grows. No other skill in this lab uses this pattern.

---

## Possible clean-room adaptations

- A **business idea knowledge base** using the same BM25 + CSV pattern for searching across product concepts, market data, and regulatory requirements
- A **source pattern catalog** using the same pattern to search across research/source-analysis/ files
- Any domain-specific knowledge retrieval tool that doesn't need vector embeddings

---

## Business applications

**Direct use**: immediately useful as a design intelligence layer for any product the lab builds.

**Adapted use**: the BM25 + CSV search pattern could power a searchable catalog of the lab's own research dossiers — a meta-application.

**Commercial concept**: A "design intelligence API" using this approach, curated for specific verticals (fintech design patterns, healthcare UI guidelines, etc.) — a focused version of the general design knowledge base.

---

## Related business ideas in this lab

- `product-concepts/functional-generative-design/README.md` — design generation tools share a conceptual space
- `product-concepts/skill-benchmarking-platform/README.md` — how would you measure if this skill improves UI quality?

---

## Related sources in this lab

- `sources/design-taste/` — complementary design quality skill
- `sources/design-quality-and-review/` — design review skill that references similar principles
- `sources/interaction-and-motion-design/` — Emil Kowalski's motion design skills

---

## Open questions

- Is the `rank_bm25` Python dependency already available in the lab environment?
- Has the design system generator been tested on lab product concepts?

---

## Final research conclusion

UI UX Pro Max is a production-ready, immediately usable design intelligence tool with two reusable architectural patterns: BM25 + CSV for knowledge retrieval, and design system generation from a description. Both patterns are extractable and applicable beyond design. Directly usable as a Claude Code skill for any frontend work in the lab.
