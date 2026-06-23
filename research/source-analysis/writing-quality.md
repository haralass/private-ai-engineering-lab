# Source Research Dossier: writing-quality

## Repository identity

- **Name:** stop-slop
- **Creator:** Hardik Pandya (hardikpandya)
- **GitHub URL:** https://github.com/hardikpandya/stop-slop
- **Source path:** sources/writing-quality/
- **Pinned commit:** 8da1f030185bdfe8471220585162991eaeb970e9
- **Retrieved:** 2026-06-22
- **License:** MIT (verified)
- **Import type:** vendored-snapshot

---

## What it actually does

Stop Slop is a Claude Code skill that eliminates AI writing patterns from prose. When activated, it applies 8 rules to identify and remove: filler phrases, formulaic structures, passive voice, vague declaratives, narrator-from-a-distance voice, and "quotable" pull-quote language. It includes a scoring rubric (5 dimensions, 1–10 each, 35/50 = revise threshold) and a reference library of banned phrases and structures.

---

## Architecture

```
stop-slop/
├── SKILL.md        — main skill definition with 8 rules, quick checks, scoring rubric
├── references/
│   ├── phrases.md  — list of filler phrases to cut
│   ├── structures.md — formulaic structures to avoid
│   └── examples.md — before/after transformation examples
├── CHANGELOG.md    — version history
└── LICENSE         — MIT
```

The skill is a single SKILL.md plus three reference files. There is no code — it is entirely a knowledge/instruction artifact. The SKILL.md is the main trigger document; the references are cross-linked from within it.

---

## Main modules and important files

| Path | What it does |
|---|---|
| `SKILL.md` | 8 core rules, quick checklist, 5-dimension scoring rubric |
| `references/phrases.md` | Banned filler phrases (e.g., "It's important to note that", "In today's world") |
| `references/structures.md` | Banned structures: binary contrasts, rhetorical setups, dramatic fragmentation |
| `references/examples.md` | Before/after rewrites showing the rules applied |

---

## Core technical patterns

**Checklisted writing rules** — 8 rules expressed as imperatives with specific negative examples. This format is effective for LLM instruction: precise, actionable, structured.

**Scoring rubric** — 5 dimensions (Directness, Rhythm, Trust, Authenticity, Density), each scored 1–10. The 35/50 threshold makes "revise" vs "ship" a deterministic decision, not a judgment call. This is a reusable evaluation pattern for any quality dimension.

**Reference cross-linking** — the skill links to three separate reference files rather than embedding everything in SKILL.md. This keeps the core instructions scannable while allowing depth via references.

---

## Novel or interesting mechanisms

The **scoring rubric with a threshold** is the most reusable mechanism. It converts subjective quality into a reproducible pass/fail gate. The same pattern applies to code quality, design quality, documentation quality — any domain where "good enough" needs to be defined.

The **negative examples pattern** (what NOT to do) is more effective than positive examples alone for training LLM behavior. The phrases.md and structures.md files embody this.

---

## Data flow

```
User requests prose writing or editing
→ Skill triggers (matching trigger: "Writing prose, editing drafts, reviewing content")
→ Claude applies 8 rules
→ Claude runs quick check list before delivery
→ Claude scores on 5-dimension rubric
→ If score < 35/50: revise before delivering
```

---

## Dependencies

None. Pure markdown skill. No code dependencies.

---

## Security model

Not applicable. No code execution, no network, no permissions.

---

## Testing strategy

No automated tests. The skill is evaluated by human review of before/after examples in `references/examples.md`. [inference: quality is maintained via community PRs and creator review]

---

## Genuinely reusable elements

**The scoring rubric pattern** (score N dimensions 1–10, set a threshold): directly applicable to any quality evaluation skill — code review, design review, documentation quality. MIT license.

**The negative-example reference file format** (phrases.md, structures.md): any domain can have a "banned patterns" reference file. The format is simple and effective.

**The skill trigger precision**: the trigger description ("Use when drafting, editing, or reviewing text to eliminate predictable AI tells") is a good example of a narrow, accurate trigger definition.

---

## What NOT to reuse

The specific phrase lists and structure lists are domain-specific to writing. They are not reusable for other skill types.

---

## Production-readiness

**MVP-quality / skill library standard.** This is a well-crafted skill file, not a prototype. Actively maintained (CHANGELOG shows versioning). However, as a skill it is only as good as the LLM's ability to apply it — it cannot be unit-tested.

---

## Strengths

- Precise, actionable rules that leave no room for vague interpretation
- Scoring rubric makes quality measurable
- Reference files keep the main skill concise
- Handles both "write from scratch" and "edit existing" use cases

## Weaknesses

- No automated evaluation mechanism — quality depends on LLM compliance
- Rules are opinionated (some writers may disagree with specific rules)
- Focused on English; would need adaptation for other languages

## Technical debt

None. Small, clean file set.

---

## Novel or differentiated elements

The scoring rubric pattern is differentiated from most skill files that only give instructions without a measurement mechanism. This makes the skill more precise and useful for iterative editing workflows.

---

## Possible clean-room adaptations

- A **code-review quality rubric** skill using the same scoring pattern
- A **documentation quality** skill adapted from the same format
- A **marketing copy quality** skill (though `sources/product-marketing-context/` already covers much of this)

---

## Business applications

**Direct use**: any product that generates or edits written content (blog posts, documentation, emails, reports) can use this skill in the development workflow to improve output quality.

**Product concept**: A writing quality gate as a CI step for documentation — automatically score docs files and fail the PR if below threshold. This is a niche developer tool concept.

---

## Related business ideas in this lab

- Connects to `sources/product-marketing-context/` — marketing copy quality is a sub-domain of writing quality
- `product-concepts/skill-benchmarking-platform/` — this skill's scoring rubric is exactly the type of metric a benchmarking platform would measure

---

## Related sources in this lab

- `sources/product-marketing-context/` — overlapping: marketing copy quality
- `sources/design-quality-and-review/` — same pattern (quality evaluation rubric) applied to design

---

## Open questions

- Is the 35/50 threshold empirically calibrated, or author judgment?
- How does the skill interact with tone requirements that differ by product (formal documentation vs. conversational product copy)?

---

## Final research conclusion

Stop Slop is a high-value, immediately usable writing quality skill. Its main portable contribution is the scoring rubric pattern — applicable beyond writing to any quality evaluation domain. MIT licensed, no code, zero integration overhead. Worth using directly in any workflow involving prose generation.
