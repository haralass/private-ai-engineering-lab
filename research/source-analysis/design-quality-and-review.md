# Source Research Dossier: design-quality-and-review

---

## Repository identity

- **Name**: impeccable
- **Creator**: Paul Bakaus (pbakaus)
- **GitHub URL**: https://github.com/pbakaus/impeccable
- **Source path**: sources/design-quality-and-review/upstream/
- **License**: Apache-2.0
- **Import type**: vendored-snapshot (pinned commit 609bbfbd)
- **Version at snapshot**: 3.8.0 (skill frontmatter)
- **Related source in this lab**: design-agent-reviews (also pbakaus — see creator notes)

---

## What it actually does

Impeccable is a comprehensive AI skill (agent instruction set) that teaches a language model to produce production-grade frontend UI code by giving it a shared design vocabulary, deterministic quality checks, a context-gathering protocol, and 23 named sub-commands. It is not a code generator in isolation — it is a behavioral system installed into an agent harness (Claude Code, Cursor, Windsurf, etc.) that transforms the agent into a design-aware frontend engineer. The skill covers the full UI lifecycle: project initialization, critiquing existing designs, building new features, auditing for accessibility and performance, animating, typesetting, colorizing, hardening for production, and live in-browser iteration. It explicitly targets "AI slop" detection — a curated taxonomy of anti-patterns that make AI-generated UIs identifiable as AI output.

---

## Architecture

Impeccable is a multi-layer system:

```
skill/                         Source of truth (SKILL.src.md + reference/*.md)
├── SKILL.src.md               Master skill file with {{placeholder}} substitutions
├── reference/                 One .md per sub-command (audit.md, polish.md, etc.)
│   ├── brand.md               Register reference for marketing/brand surfaces
│   ├── product.md             Register reference for app/dashboard surfaces
│   ├── animate.md, bolder.md, clarify.md, ... (23 command references)
│   └── hooks.md               Design detector hook system
├── scripts/
│   ├── context.mjs            Reads PRODUCT.md + DESIGN.md, prints project context
│   ├── context-signals.mjs    Emits JSON signals (setup.hasDesign, git.changedFiles, etc.)
│   ├── detect.mjs             Anti-pattern detector over local HTML/CSS files
│   ├── palette.mjs            Brand seed color generator
│   └── pin.mjs                Creates/removes shortcut skill shims
scripts/
├── build.js                   Transpiles SKILL.src.md to provider-specific SKILL.md files
└── lib/
    ├── transformers/          Per-provider placeholder substitution
    └── utils.js               IMPECCABLE_SUB_COMMANDS canonical list
cli/                           npm package 'impeccable' (install/update/detect CLIs)
extension/                     Chrome extension with DOM-based detector
tests/
├── live-e2e.test.mjs          Full-cycle live-mode E2E (Playwright)
└── skill-behavior/            LLM-backed scenario tests (Claude + GPT + Gemini)
```

The `.agents/skills/impeccable/` directory (what this lab has as upstream) is the pre-built output for the `agents` harness format. It is generated from `skill/` by the build system and committed as a tracked artifact.

---

## Main modules and important files

- `sources/design-quality-and-review/upstream/.agents/skills/impeccable/SKILL.md` — The 3.8.0 skill definition. 160+ lines of routing logic, design guidance (color, typography, layout, motion), absolute bans, the "AI slop test", and 23-command router table
- `sources/design-quality-and-review/upstream/.agents/skills/impeccable/reference/audit.md` — The `$impeccable audit` command: 5-dimension scoring (A11y, Performance, Theming, Responsive, Anti-Patterns), P0-P3 severity taxonomy, recommended action mapping
- `sources/design-quality-and-review/upstream/.agents/skills/impeccable/reference/` — Full suite of 23 command references (not all visible in snapshot, but audit.md confirms the pattern)
- `sources/design-quality-and-review/upstream/.agents/skills/impeccable/agents/impeccable_asset_producer.toml` — Agent config for asset generation sub-agent
- `sources/design-quality-and-review/upstream/.agents/skills/impeccable/agents/openai.yaml` — OpenAI-format agent config

---

## Core technical patterns

**Register bifurcation** (SKILL.md Setup step 4): Every design task is classified as either `brand` (design IS the product: marketing, landing pages) or `product` (design SERVES the product: app UI, dashboards). The register determines which reference file loads, what quality bar applies, and what anti-patterns are most relevant. This is a sophisticated context-routing mechanism that prevents the skill from applying marketing aesthetics to product UIs and vice versa.

**Context-gathering protocol** (SKILL.md Setup steps 1-5): Before any design work, the skill requires running `context.mjs` to read `PRODUCT.md` (project context) and `DESIGN.md` (design system). If neither exists, the skill blocks and routes to `init`. This creates a persistent project memory layer that survives across sessions.

**Deterministic detector** (scripts/detect.mjs, 44 rules): A rule-based HTML/CSS anti-pattern scanner that runs post-edit as a hook. Rules are classified as `slop` (AI aesthetic tells) or `quality` (genuine design/a11y issues). Examples: `side-stripe-borders`, `gradient-text`, `ghost-card-pattern`, `oversized-border-radius`. The detector runs in both a Node.js/jsdom environment and a browser DOM environment (two separate adapter paths per rule).

**Provider-agnostic build** (scripts/build.js): The skill source uses `{{model}}`, `{{config_file}}`, `{{command_prefix}}` placeholders that are substituted per provider. This single-source model generates Claude, Cursor, Windsurf, Gemini, and 10+ other harness formats from one authoring surface.

**LLM-backed skill-behavior tests** (tests/skill-behavior/): Nine scenarios verify that the skill text actually drives correct agent behavior (not just that it's syntactically valid). Tests run against Claude Sonnet, GPT-5.5, and Gemini Flash simultaneously. Assertions are on tool-call traces (which files the agent reads), not free-form output. This is a non-standard but genuinely valuable testing methodology for prompt-engineering work.

**Pin system** (scripts/pin.mjs): Creates lightweight redirect shims so `$audit` invokes `$impeccable audit` directly. This reduces the "/ menu pollution" problem in Claude Code.

---

## Novel or interesting mechanisms

1. **The "AI slop test" as a first-class quality gate** (SKILL.md line ~100): The skill explicitly defines and tests for whether an interface "could be identified as AI-made without doubt." This is an introspective quality standard — the AI is instructed to critique its own outputs for AI-tells. The ban list includes specific hex families, named CSS patterns, and copy phrases (em-dash, "seamless," numbered eyebrows). This is notable because it operationalizes aesthetic anti-pattern detection in natural language.

2. **Absolute ban list with override paths** (SKILL.md "Absolute bans"): Rules are not just suggestions but `match-and-refuse` directives. Each ban includes an override condition (e.g., gradient text is banned, but a solid-color alternative is specified). This is more rigorous than typical style guides.

3. **Dial system** (taste-skill cross-reference; the SKILL.md also references DESIGN_VARIANCE/MOTION_INTENSITY/VISUAL_DENSITY conceptually): The skill uses continuous numeric parameters to parameterize design output rather than discrete categorical styles. This creates a design space rather than a style preset system.

4. **Pre-flight checklist** (taste-skill has 70+ items; impeccable's audit has a 5-dimension score): Every output must pass a mechanical checklist before delivery. This converts subjective design quality into a verifiable checklist, enabling the agent to self-audit before responding.

5. **UPDATE_AVAILABLE directive**: The `context.mjs` script can emit `UPDATE_AVAILABLE` to prompt users to update. The skill behavior tests specifically verify that the agent surfaces this without auto-running `npx impeccable skills update`. This is a thoughtful autonomy constraint.

---

## Data flow

```
User invokes $impeccable [command] [target]
  → Setup protocol runs (once per session)
      → context.mjs → reads PRODUCT.md + DESIGN.md → prints project context
      → If NO_PRODUCT_MD → routes to reference/init.md
      → Selects register (brand/product) → loads reference/brand.md or reference/product.md
      → Reads at least one project code file (CSS/tokens/component)
      → If new project → palette.mjs → brand seed color
  → Command routing
      → First word matches command table → loads reference/<command>.md
      → Follows reference instructions for that command
  → For audit command:
      → Reads target files
      → Runs detect.mjs (or references anti-pattern detector output)
      → Scores 5 dimensions (0-4 each, /20 total)
      → Generates P0-P3 issue list
      → Recommends follow-up commands
  → Output produced as production-ready code or structured report
```

---

## Dependencies

The installed skill itself has no runtime dependencies — it is pure markdown/instruction files plus JavaScript utility scripts. The build system (for maintainers) uses:
- `bun` (runtime for build scripts and dev server)
- `@anthropic-ai/sdk`, `openai` (for skill-behavior tests)
- `playwright` (for live-mode E2E tests)
- `jsdom` (for detector fixture tests; note: must use `node`, not `bun`, for jsdom due to bun slowness)
- `astro` (marketing website)
- `sharp` (OG image generation)

---

## Security model

The skill files are read-only instructions; they install no hooks by default. The optional `$impeccable hooks on` command installs a post-edit hook that runs the detector after direct UI file edits. The hook is scoped to the project directory. The `context.mjs` script reads local files but does not write or execute anything. No credentials are required. The Chrome extension for live browser iteration operates in the browser's DOM context; it does not have server-side access.

The skill's CLAUDE.md contains a prose validator (`validateProse`) that enforces a denylist of banned words in marketing copy. This runs at build time (not at agent runtime) and produces rationale messages rather than silent failures.

---

## Testing strategy

Three-tier testing:

1. **Unit tests** (`bun test`): Tests for build orchestration and detector logic (BM25 engine, rule matching).
2. **Fixture tests** (`node --test`): jsdom-based HTML detection using `tests/fixtures/antipatterns/`. Each fixture has `SHOULD_FLAG` and `SHOULD_PASS` columns. Tests use `node` specifically because `bun test` is too slow with jsdom.
3. **Live E2E tests** (`bun run test:live-e2e`): Full Playwright cycle against 19 framework fixtures (React, Next.js, SvelteKit, Astro, Nuxt, etc.). Boots real dev servers, uses a fake agent emitting canned variant JSON, tests the full variant-accept-carbonize workflow.
4. **Skill-behavior tests** (`bun run test:skill-behavior`): LLM-backed; runs 9 scenarios against 3 providers (Claude Sonnet, GPT-5.5, Gemini Flash). Assertions on tool-call traces. Costs ~$0.50-1.50 per full sweep.

---

## Genuinely reusable elements

1. **Register bifurcation pattern** (brand vs. product register): This is a clean architectural choice for any design system or AI skill that needs to serve different surfaces. The idea that "marketing pages need distinctiveness" and "product UIs need earned familiarity" is a reusable mental model. License: Apache-2.0 (must preserve attribution).

2. **Absolute ban list with override paths**: The pattern of defining design anti-patterns as `match-and-refuse` directives with explicit override conditions is reusable in prompt engineering for any visual AI system. License: Apache-2.0.

3. **Provider-agnostic build system** ({{placeholder}} substitution): The approach to maintaining one skill source and generating per-provider variants is directly applicable to any multi-platform AI skill. License: Apache-2.0.

4. **Deterministic HTML/CSS anti-pattern detector logic**: The 44 detection rules and their jsdom/DOM dual-adapter structure are extractable as a standalone linter. License: Apache-2.0 (with attribution).

5. **P0-P3 severity taxonomy for design audits**: The priority scheme (P0=blocking, P1=major/WCAG AA, P2=minor, P3=polish) is reusable in any design quality framework. License: Apache-2.0.

---

## What NOT to reuse

- The specific hardcoded palette family bans (beige+brass+espresso hex values) are opinion-based and opinionated to pbakaus's aesthetic philosophy — they should not be adopted without understanding the rationale.
- The `context.mjs` / `palette.mjs` scripts depend on PRODUCT.md and DESIGN.md conventions that are impeccable-specific. They would need redesign for other project structures.
- The Chrome extension for live iteration (`$impeccable live`) is tightly coupled to the impeccable variant format and should not be adapted piecemeal.

---

## Production-readiness

**Production-ready (for the skill itself).** The skill has 40,000+ GitHub stars, 160,000+ installs through skills.sh, a multi-provider build system, LLM-backed behavior tests, and published a16z-backed commercial backing through Renaissance Geek Inc. The detector has 44 rules with dual DOM adapters and fixture-tested false-positive shapes. The skill behavior tests run against three production LLMs simultaneously. This is the most mature source in this lab by significant margin. The CLI tooling (`npx impeccable`) is npm-published and production-grade.

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- Most sophisticated prompt engineering artifact in this lab: 23 commands, register system, context persistence, detector hook, pin system, and LLM behavior tests
- a16z backing + GitHub partnership signals institutional-grade commitment to maintenance
- Multi-provider build system means the skill degrades gracefully across different AI harnesses
- The anti-pattern detector provides objective quality gates alongside subjective skill guidance

**Weaknesses**:
- Apache-2.0 license (not MIT) requires attribution and preservation of NOTICE files in any distribution — more friction than MIT sources
- The skill is opinionated to pbakaus's aesthetic sensibility (particularly the anti-serif stance and premium-consumer palette bans); teams with different brand guidelines must carefully override rather than adopt wholesale
- Context system (PRODUCT.md + DESIGN.md) requires project setup investment; cold-start experience is weaker than it should be
- The detector runs only on local files; it cannot audit production URLs without the Chrome extension

**Technical debt**:
- `scripts/build.js` counts commands in multiple places and has a validator to catch stale counts — a sign that the command count is manually maintained in too many locations
- The legacy URL redirect system (`/skills → /docs`) creates multiple redirect chains that need maintenance
- The `skill/SKILL.src.md` uses placeholder substitution at build time; this means the source file is not directly usable and requires a build step for any testing

---

## Novel or differentiated elements

The core novelty is the **operationalization of design taste as machine-readable rules**. Most design guidelines are aspirational prose; impeccable converts them into ban lists, numeric dials, pre-flight checklists, and detector rules. This is a genuine research contribution to the field of AI-assisted design. The "AI slop test" concept — explicitly testing whether AI output could be identified as AI-made — is a novel quality metric with potential application far beyond frontend design. The LLM-backed skill-behavior tests are also an unusual contribution: testing prompt engineering with LLM assertions rather than unit tests.

---

## Possible clean-room adaptations

- **Design quality API**: Expose the 44-rule detector as an HTTP service that audits URLs or uploaded HTML, scoring outputs on anti-pattern density. Useful for design QA pipelines in CI.
- **Brand register module**: Package the brand/product register bifurcation concept as a reusable design context layer for any AI-assisted design system.
- **Taste score as CI gate**: Integrate the P0-P3 audit output into a CI pipeline that blocks merges with P0 design issues (low contrast, broken a11y). Analogous to how ESLint blocks code merges.
- **Anti-pattern taxonomy as training data**: The structured anti-pattern descriptions with visual examples could serve as preference data for fine-tuning or RLHF on UI generation models.

---

## Business applications

1. **Design quality CI service**: A GitHub App that runs the impeccable detector on every PR touching frontend files, posting inline review comments with P0-P3 severity. Target: product teams shipping UI code without dedicated design reviewers. Commercial rationale: fills the design review gap in teams that cannot afford a senior designer per PR.
2. **AI frontend quality layer**: A wrapper around existing AI code generators (Lovable, v0, Cursor) that post-processes their output through the impeccable anti-pattern detector and applies corrections automatically. Target: teams using vibe-coding tools. Commercial rationale: the "AI slop" problem is widely reported; impeccable's taxonomy makes it tractable.
3. **Design taste API**: Expose the detector and scoring as an API that returns a quality score for any frontend code. Target: code editors, design tools, CI platforms. Commercial rationale: developer tooling that prevents design regressions has clear ROI.

---

## Related business ideas in this lab

- Directly relates to the functional-generative-design product concept in this lab
- The detector rules are directly applicable to the design-taste source (Leonxlnx/taste-skill)
- Creator overlap with design-agent-reviews: pbakaus is building a complete AI-assisted development quality layer (code review + design quality)

---

## Related sources in this lab

- **design-agent-reviews** (also pbakaus): Handles PR review workflow; impeccable handles design quality. Complementary halves of pbakaus's AI development quality system.
- **design-taste** (Leonxlnx/taste-skill): Overlapping goal (anti-slop design), different approach (more opinionated, more technical depth in impeccable). The design-taste skill appears to be inspired by or derivative of impeccable's approach.
- **ui-ux-reference** (nextlevelbuilder): Complements impeccable with a searchable reference database rather than prescriptive rules.

---

## Open questions

1. Renaissance Geek Inc. (pbakaus's company) has a16z backing and a GitHub partnership. What is the commercial model? Is impeccable itself the product, or is it the top-of-funnel for a paid service?
2. The 44 detector rules are described as catching "AI tells." How were these rules empirically validated? Is there a corpus of human-reviewed designs against which false-positive rates were measured?
3. The skill supports Claude, Cursor, Windsurf, Gemini, and 10+ other harnesses. Are there known behavioral differences across harnesses? The skill-behavior tests show per-provider divergence — what are the known failure modes on non-Claude models?
4. The PRODUCT.md context file is project-specific. For multi-service repos or monorepos, how does context scoping work?

---

## Final research conclusion

impeccable is the most architecturally mature and commercially backed source in this lab — a genuine system-level contribution to AI-assisted frontend development, not just a useful snippet. Its core insight (operationalizing design taste as deterministic detector rules and structured behavioral constraints) is novel and applicable far beyond its current scope. The Apache-2.0 license and pbakaus's explicit commercial development mean clean-room adaptation (rather than direct reuse) is the appropriate approach for any derived product.

---
