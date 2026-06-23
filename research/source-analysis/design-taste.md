# Source Research Dossier: design-taste

---

## Repository identity

- **Name**: taste-skill (repository: Leonxlnx/taste-skill)
- **Creator**: Leonxlnx (GitHub user, real name not publicly established in available sources)
- **GitHub URL**: https://github.com/Leonxlnx/taste-skill
- **Source path**: sources/design-taste/upstream/
- **License**: MIT
- **Import type**: vendored-snapshot (pinned commit 06d6028b)
- **Sponsors**: Emil Kowalski (animations.dev) appears as a named sponsor in the repository

---

## What it actually does

taste-skill is a large AI skill collection for frontend design, centered on anti-slop landing page and portfolio generation. The repository's primary artifact is `skills/taste-skill/SKILL.md` — a 1,207-line comprehensive instruction set for AI coding agents that want to produce non-generic, non-AI-looking frontend interfaces. It includes a "three dials" configuration system (DESIGN_VARIANCE, MOTION_INTENSITY, VISUAL_DENSITY), a 70+ item pre-flight checklist, a 700+ item anti-pattern list, a scroll animation library (GSAP + Motion canonical skeletons), a redesign protocol, and a reference vocabulary of 70+ named design patterns. Secondary skills include a redesign-specific skill, brutalist-skill, minimalist-skill, soft-skill, imagegen skills, and a stitch-skill. The repository also contains structured research on LLM laziness (truncation root causes, prompt stimuli effects, empirical results from 2025 controlled studies).

---

## Architecture

```
skills/
├── taste-skill/SKILL.md          Primary 1,207-line anti-slop frontend skill
├── taste-skill/blocks/           Block library schema (implementations TBD)
├── redesign-skill/SKILL.md       Redesign-specific protocol (178 lines)
├── brutalist-skill/SKILL.md      Brutalist aesthetic variant
├── minimalist-skill/SKILL.md     Minimalist aesthetic variant
├── soft-skill/SKILL.md           Soft/consumer aesthetic variant
├── output-skill/SKILL.md         Output format specification
├── gpt-tasteskill/SKILL.md       GPT-optimized variant
├── stitch-skill/SKILL.md         (with DESIGN.md)
├── brandkit/SKILL.md             Brand kit extraction
├── image-to-code-skill/SKILL.md  Image-to-code conversion
├── imagegen-frontend-web/SKILL.md  Web image generation
├── imagegen-frontend-mobile/SKILL.md  Mobile image generation
research/
├── laziness/                     LLM laziness research (root causes + remediation)
│   ├── findings/empirical-results.md
│   ├── findings/references.md
│   ├── root-causes/              cognitive-shortcuts.md, output-limits.md, etc.
│   └── remediation/              architectural-patterns.md, prompt-engineering.md
skill.sh                          Shell installer script
.claude-plugin/                   Claude Code plugin manifest
```

---

## Main modules and important files

- `sources/design-taste/upstream/skills/taste-skill/SKILL.md` — Core artifact. 1,207 lines. Sections: Brief Inference (Section 0), Three Dials (Section 1), Design System Map (Section 2), Default Architecture (Section 3), Design Engineering Directives (Section 4, 11 sub-sections), Context-Aware Proactivity (Section 5, including GSAP skeletons), Performance & A11y Guardrails (Section 6), Dial Definitions (Section 7), Dark Mode Protocol (Section 8), AI Tells Catalogue (Section 9), Reference Vocabulary (Section 10), Redesign Protocol (Section 11), Block Library Schema (Section 12), Pre-Flight Checklist (Section 14), Appendices with actual install commands
- `sources/design-taste/upstream/skills/redesign-skill/SKILL.md` — Redesign-specific protocol with audit-first approach, 7 upgrade technique categories
- `sources/design-taste/upstream/research/laziness/findings/empirical-results.md` — Documents a December 2025 controlled study on LLM output truncation, Microsoft Research stimulus effects, seasonal ChatGPT output variation
- `sources/design-taste/upstream/research/laziness/remediation/prompt-engineering.md` — Prompt strategies to counter truncation
- `sources/design-taste/upstream/research/laziness/root-causes/rlhf-and-compute.md` — RLHF-based explanation of laziness

---

## Core technical patterns

**Three-Dial configuration** (SKILL.md Section 1): `DESIGN_VARIANCE` (1–10 symmetry↔chaos), `MOTION_INTENSITY` (1–10 static↔cinematic), `VISUAL_DENSITY` (1–10 airy↔cockpit). Each dial maps to specific CSS/layout/animation choices throughout the document. The dials are inferred from the design brief automatically (inference table in Section 1.A) but can be overridden conversationally. This is a parameterized design system rather than a style preset.

**Brief Inference protocol** (SKILL.md Section 0): Before any code, the agent must state a one-line "Design Read" that identifies page kind, audience, vibe words, reference signals, brand assets, and quiet constraints. This forces context capture before generation, counteracting the LLM tendency to jump to defaults.

**Anti-Default Discipline** (Section 0.D): Explicit named defaults to avoid — AI-purple gradients, centered hero over dark mesh, three equal feature cards, glassmorphism, Inter + slate-900. Named in the brief-inference section so the prohibition fires before code generation, not after.

**GSAP Canonical Skeletons** (Section 5): Two complete TypeScript/React code skeletons for `StickyStack` and `HorizontalPan` scroll patterns, with the exact critical point annotated: `start: "top top"`, `pin: true`, `end: "+=${distance}"`. These are working implementations, not pseudocode.

**Pre-flight checklist** (Section 14): 70+ boolean checks that must pass before delivery. Items include mechanical counts (eyebrow count ≤ ceil(sectionCount / 3)), contrast checks, CTA wrap checks, and specific pattern bans. The checklist is organized so mechanical checks are separable from judgment calls.

**`PREMIUM-CONSUMER PALETTE BAN`** (Section 4.2): Specifies exact hex families that are banned as defaults for premium-consumer briefs, with exact alternatives organized by palette family (Cold Luxury, Forest, Black and Tan, Cobalt+Cream, etc.). This level of specificity — actual hex values banned — is unusual for a design system.

---

## Novel or interesting mechanisms

1. **The "Palette-rotation rule"**: If the previous premium-consumer project used the beige+brass family, this one MUST use a different family. This addresses the AI homogenization problem at the session level, not just the project level. [inference: this relies on context memory from earlier in the conversation, which may not survive session boundaries in all agent harnesses]

2. **LLM laziness research corpus**: The `research/` directory contains structured empirical notes on LLM truncation behavior, including a quantified table of prompt stimulus effects from a Microsoft Research study (e.g., `$200 tip framing → +45% output quality`). This is not typical skill content; it is meta-research on how to extract more complete outputs from language models. This research directly motivates the pre-flight checklist's non-negotiable framing.

3. **Seasonal output variation finding**: The empirical results document notes that ChatGPT outputs are measurably shorter during December (correlated with holiday period training data patterns) and that explicitly stating a non-winter month in the system prompt increases output length. This is an operationalizable finding.

4. **The em-dash total ban** (Section 9.G): More extreme than impeccable's em-dash guidance. Zero tolerance, not "use sparingly." Justified by calling it "the single most-violated Tell" in production tests. The section documents previous failed attempts at "use sparingly" framing and explains why binary prohibition is more effective.

5. **Block Library contract** (Section 12): Defines the schema for a block library that does not yet exist — each block must have frontmatter (dial_compatibility, when_to_use, not_for, stack), a visual sketch, props API, code sketch, mobile fallback, motion variants, dark-mode notes, anti-patterns, and references. This is a specification for future work, not implemented content.

6. **`MARQUEE MAX-ONE-PER-PAGE`**: Named as mandatory. Marquees are common in AI-generated pages; the one-per-page limit addresses the over-use pattern.

---

## Data flow

```
User submits design brief to agent with taste-skill loaded
  → Section 0: Brief Inference
      → State Design Read (one line, mandatory)
      → Set three dial values from inference table or use-case presets
  → Section 2: Select design system (official package or aesthetic family)
  → Section 3: Configure default stack (React/Next.js, Tailwind v4, Motion)
  → Section 4-9: Apply design engineering directives and avoid AI tells
  → Section 5: Use context-aware proactivity (GSAP skeletons when MOTION_INTENSITY > 5)
  → Section 11: Apply redesign protocol if applicable
  → Section 14: Run pre-flight checklist before outputting code
  → Deliver production-ready code
```

---

## Dependencies

Skill-level (instruction file only): none.

Recommended stack from skill instructions:
- React / Next.js (Server Components default)
- Tailwind v4 (via `@tailwindcss/postcss` or Vite plugin)
- Motion (`motion/react`, formerly Framer Motion)
- GSAP + ScrollTrigger (for pinned/scrubbed scroll)
- `@phosphor-icons/react`, `hugeicons-react`, `@radix-ui/react-icons`, `@tabler/icons-react`

Optional design systems (per brief): `@fluentui/react-components`, `@material/web`, `@carbon/react`, `@radix-ui/themes`, shadcn/ui, etc.

---

## Security model

This is a pure instruction file with no code execution. The `.claude-plugin/` manifest enables installation via Claude's plugin marketplace. No permissions are requested. No hooks are installed by default. No external API calls are made by the skill itself.

---

## Testing strategy

No test suite exists in the snapshot. The research/ directory contains empirical notes on LLM behavior, but these are not automated tests. The skill is validated through manual design reviews and the pre-flight checklist process itself.

---

## Genuinely reusable elements

1. **Three-Dial configuration system** (DESIGN_VARIANCE, MOTION_INTENSITY, VISUAL_DENSITY): The parameterized approach to design configuration is a clean, reusable interface for any AI design system. The inference table (brief signals → dial values) is directly adaptable. License: MIT.

2. **Anti-pattern taxonomy** (Section 9): 700+ words of named, categorized anti-patterns with specific CSS signatures and mechanical detection cues. This is reusable as training data, documentation, or a lint ruleset. License: MIT.

3. **GSAP canonical skeletons** (Section 5.A and 5.B): The `StickyStack` and `HorizontalPan` components are working TypeScript/React code with the correct `start: "top top"`, `pin: true` parameters that most implementations get wrong. License: MIT.

4. **Pre-flight checklist structure**: The 70+ item checklist is directly adaptable to other design domains. The mechanical/judgment separation is particularly reusable. License: MIT.

5. **LLM laziness research** (research/ directory): The structured notes on stimulus effects, truncation root causes, and seasonal variation are useful meta-knowledge for anyone building AI skill systems. License: MIT.

6. **Brief Inference one-liner requirement**: The "state the Design Read before touching code" protocol is reusable in any domain where the AI needs to demonstrate context capture before generation.

---

## What NOT to reuse

- The specific font prohibitions (`Fraunces`, `Instrument_Serif`, `Inter` as defaults) reflect a specific moment in the AI design landscape and will become less relevant as model behavior evolves.
- The premium-consumer hex family bans are valid today but require periodic review as aesthetic trends shift.
- The `Block Library` schema is aspirational — no actual blocks are implemented in the snapshot. Do not treat it as a library.

---

## Production-readiness

**MVP-quality (for the skill file itself).** The skill is functionally complete and detailed, but it is not institutionally backed (unlike impeccable). There are no automated tests, no CI system, no version management beyond a CHANGELOG.md. The research/ directory is in note form, not peer-reviewed. Emil Kowalski is named as a sponsor, which suggests community credibility, but this is a solo creator project. The skill itself is large enough that it may hit context window limits in some agent harnesses. The block library section describes future work that is not implemented.

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- The most complete and specific anti-slop system for landing pages in this lab
- GSAP canonical skeletons are a genuine practical resource (the `start: "top top"` fix alone is worth adoption)
- LLM laziness research is well-organized and operationalizable
- MIT license reduces adoption friction
- Emil Kowalski sponsorship signals design community credibility

**Weaknesses**:
- No test suite; quality is entirely self-referential (the skill instructs the agent to check itself)
- The 1,207-line SKILL.md may exceed context windows in some harnesses
- Block Library (Section 12) is schema-only; the blocks don't exist yet
- "Palette-rotation rule" requires session-level memory that may not work across agent restarts
- Creator background and maintenance commitment unclear

**Technical debt**:
- Appendix A (install commands) contains real version-specific package names that will become stale as ecosystems evolve
- The explicit Apple Liquid Glass warning ("There is no official `liquid-glass.css`") is time-bound; Apple may formalize this in a future web standard
- Section numbering is inconsistent (Section 13 is missing; checklist is Section 14 after "Out of Scope" at Section 13)

---

## Novel or differentiated elements

The most novel element relative to impeccable is the **LLM laziness research corpus**. This is not typical skill content — it is a structured investigation of why AI agents truncate outputs and what prompt strategies counteract this. The empirical results (Microsoft Research stimulus quantification, seasonal output variation) are specifically useful for skill authors. The three-dial system is also more explicit and operator-friendly than impeccable's register approach. The GSAP canonical skeletons represent "battle-tested code that most developers get wrong" — a high-value contribution in a domain where subtle parameter errors cause silent failures.

---

## Possible clean-room adaptations

- **Design brief analyzer**: A tool that takes a design brief as input and outputs dial values plus a design system recommendation, implementing the Section 0 + Section 1 logic as a standalone service.
- **Anti-pattern CSS linter**: Convert the Section 9 anti-pattern catalogue into a PostCSS or Stylelint plugin with the specific CSS signatures as detection rules.
- **GSAP skeleton generator**: A code generator that produces correctly-parameterized `StickyStack` or `HorizontalPan` components from simple configuration, preventing the `start: "top center"` bug that the skill documents as the most common mistake.
- **Design pre-flight CI check**: Convert the 70+ item pre-flight checklist into an automated audit tool that parses React component files for checklist violations.

---

## Business applications

1. **Anti-slop design linter SaaS**: A service that analyzes landing page code or screenshots for AI-tell anti-patterns (premium-consumer palette bans, em-dash, eyebrow overuse, etc.) and scores outputs. Target: marketing agencies and indie founders using AI page builders. Commercial rationale: "AI-slop" is a well-understood pain point with a growing market of users who want to avoid it.
2. **Design system parameterization layer**: Package the three-dial system as a configurable design token generator that takes brand inputs and outputs Tailwind/CSS token sets calibrated to the variance/intensity/density profile. Target: design tool vendors. Commercial rationale: current design tokens are either manual or fully automated; parameterized generation is a useful middle ground.
3. **Animation component marketplace**: The GSAP canonical skeletons and Reference Vocabulary (Section 10) define 70+ named patterns. A component marketplace selling these as verified, accessible, reduced-motion-compliant implementations would have clear demand among developers who want premium motion without building it from scratch.

---

## Related business ideas in this lab

- Directly connects to the functional-generative-design concept in this lab
- The anti-slop taxonomy connects to the design-quality-and-review (impeccable) anti-pattern detector
- The LLM laziness research is relevant to skill-benchmarking-platform concept

---

## Related sources in this lab

- **design-quality-and-review** (impeccable/pbakaus): Direct parallel — impeccable is more institutionally backed with a detector and tests; taste-skill is more opinionated with GSAP skeletons and laziness research. Both target anti-slop design.
- **interaction-and-motion-design** (emilkowalski/skills): Emil Kowalski sponsors taste-skill and is the original creator of interaction motion skills; there is a direct intellectual lineage.
- **interaction-motion-toast** (emilkowalski/sonner): Sonner's animation philosophy (CSS transitions over keyframes, sub-300ms durations) is reflected in taste-skill's motion guidance.
- **ui-ux-reference**: Complements with a searchable database; taste-skill provides the behavioral framework.

---

## Open questions

1. Is Leonxlnx the same person as or affiliated with Emil Kowalski, or is Emil purely a financial sponsor? The relationship matters for understanding the intellectual provenance.
2. The empirical results in research/laziness/findings/empirical-results.md cite specific 2025 studies. Are these real studies, or are they [inference: constructed examples used for prompt engineering effectiveness]? The lack of citations in the findings file is concerning.
3. How does the Block Library (Section 12) get populated — is this a community contribution model, or a solo creator maintenance item?
4. The skill file at 1,207 lines may hit context window limits. Has this been tested against specific model token windows?

---

## Final research conclusion

taste-skill is the most technically detailed anti-slop frontend skill in this lab, with genuine innovations in the three-dial parameterization system, GSAP canonical skeletons, and LLM laziness research. Its MIT license and design community credibility (Emil Kowalski sponsorship) make it attractive for adaptation. The absence of automated testing and the unimplemented Block Library reduce confidence in its maintenance trajectory, but the core skill content is valuable enough to adapt clean-room as a foundation for a production-grade design system.

---
