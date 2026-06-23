# Source Research Dossier: anthropic-skills

## Repository identity

- **Name:** skills (Anthropic Agent Skills)
- **Creator:** Anthropic (anthropics organization)
- **GitHub URL:** https://github.com/anthropics/skills
- **Source path:** sources/anthropic-skills/ (no upstream/ directory — reference-only)
- **License:** NOT-FOUND at root (no LICENSE file in root); individual skills are Apache-2.0; source-available skills noted in README; overall license status is mixed
- **Import type:** Reference-only (copy_allowed: false)
- **Pinned commit:** 57546260929473d4e0d1c1bb75297be2fdfa1949
- **Retrieved at:** 2026-06-22T05:14:16Z
- **Security review:** pending
- **Decision:** reference-only — study only, do not copy

---

## What it actually does

The `anthropics/skills` repository is Anthropic's official public reference implementation of the Agent Skills specification. It serves two purposes: (1) it defines the canonical Agent Skills spec (in `spec/agent-skills-spec.md`), which is a portable open standard for Claude Code and other AI tools; and (2) it provides a curated set of first-party and community reference skills, including document editing skills (docx, pdf, pptx, xlsx), a skill-creator meta-skill, a frontend-design skill, and others.

Skills in this repository are Markdown-based instruction packages — a skill is a folder containing at minimum a `SKILL.md` file with YAML frontmatter and Markdown body. Skills teach Claude how to complete specific tasks in a repeatable, invocable way. The repository also contains a `template/` directory with a canonical skill scaffolding guide.

Agent Skills launched on October 16, 2025, and the Anthropic repository is the normative reference for the spec and the official skills catalog (https://github.com/anthropics/skills).

---

## Architecture

```
anthropics/skills/
  ├── spec/
  │   └── agent-skills-spec.md    ← Canonical specification document
  ├── template/                   ← Skill scaffolding template
  ├── skills/
  │   ├── skill-creator/
  │   │   └── SKILL.md            ← Meta-skill for creating new skills
  │   ├── docx/
  │   │   └── SKILL.md            ← Word document editing skill
  │   ├── pdf/
  │   │   └── SKILL.md            ← PDF manipulation skill
  │   ├── pptx/
  │   │   └── SKILL.md            ← PowerPoint editing skill
  │   └── xlsx/
  │       └── SKILL.md            ← Excel editing skill
  └── README.md
```

Each skill is self-contained in its own folder. Skills can contain: a `SKILL.md` (required), optional helper scripts or data files, and optionally a `AGENTS.md` for multi-agent compositions. The spec defines frontmatter fields including `name`, `description`, and an optional trigger mechanism (user-invoked vs. auto-loaded by Claude based on relevance).

---

## Main modules and important files

Based on web research (ref-only; no upstream/ directory in this lab):

| File | Purpose |
|------|---------|
| `spec/agent-skills-spec.md` | Normative specification for the Agent Skills format; defines SKILL.md schema, directory layout, frontmatter fields, and agent behavior contracts |
| `template/` | Canonical skill template with guidance on structuring a new skill |
| `skills/skill-creator/SKILL.md` | A skill that teaches Claude how to create, evaluate, and optimize other skills; this is the meta-layer for the ecosystem |
| `skills/docx/SKILL.md` | Source-available (non-Apache) skill for creating and editing Word documents; noted in SOURCE.yaml as "studied" |
| `skills/pdf/SKILL.md` | Source-available skill for PDF manipulation |
| `skills/pptx/SKILL.md` | Source-available skill for PowerPoint |
| `skills/xlsx/SKILL.md` | Source-available skill for Excel/spreadsheet manipulation |
| `README.md` | Overview of the repository, linking to the spec and usage documentation |

Note: The skills referenced in the current Claude Code session's system-reminder (pdf, docx, pptx, xlsx, skill-creator, schedule, etc.) are the installed versions of Anthropic skills in the local Claude Code installation — these match the patterns described in this repository.

---

## Core technical patterns

**1. SKILL.md as the universal skill contract** (from spec)
A skill is defined entirely by its `SKILL.md` file. The frontmatter (YAML) declares the skill's identity and trigger conditions; the Markdown body is the instruction set Claude follows when executing the skill. This is a plain-text, version-control-friendly format that requires no build step.

**2. Trigger declaration: user-invoked vs. auto-loaded** (from spec + web research)
Skills declare in their frontmatter whether they are invoked explicitly (e.g., `/skill-name`) or loaded automatically by Claude when it determines the skill is relevant to the user's request. The `description` frontmatter field is the key signal Claude uses for auto-load decisions.

**3. Skill directory structure** (from spec + https://code.claude.com/docs/en/skills)
Skills can be installed at three scopes:
- Project scope: `.claude/skills/` (relative to project root)
- User scope: `~/.claude/skills/` (global for a user)
- Plugin scope: bundled inside a Claude Code plugin

The spec defines these as the standard discovery paths so that any compliant agent can find and load skills without runtime configuration.

**4. Source-available document skills** (README note)
The docx/pdf/pptx/xlsx skills are described as "source-available" rather than Apache-2.0 — they are visible for study but have restricted copying rights. This two-tier licensing model (Apache for some skills, source-available for complex document skills) is significant for this lab's reuse decisions.

**5. Skill-creator meta-skill** (`skills/skill-creator/SKILL.md`)
The skill-creator skill is Anthropic's reference implementation of a self-improving skill ecosystem. It provides Claude with instructions for generating, testing, and refining other skills. This is a practical demonstration of meta-learning at the skill layer.

---

## Novel or interesting mechanisms

**The Agent Skills specification as an open interoperability standard**
The spec is designed to be portable across AI tools — not just Claude Code. The vercel-labs/skills project implements a CLI toolchain based on the same spec. Third-party marketplaces (skillsmp.com, claudeskills.info, skills.pawgrammer.com) host skills conforming to the same format. This creates an ecosystem effect where skills written for Claude Code can in principle run on any compliant agent.

**Source-available licensing as a forcing function for an ecosystem**
By making the complex document skills source-available (not permissively open), Anthropic creates a situation where the reference implementation is visible but must be reimplemented for commercial use. This is a common strategy for ecosystem-building: the spec is open, the reference is visible, commercial users need to build their own.

**The skill-creator skill as a self-bootstrapping mechanism** [inference]
A skill that teaches Claude to create new skills means the ecosystem can grow without Anthropic authoring every skill. The skill-creator skill likely forms the basis for the `skill-creator` skill currently active in this Claude Code session (visible in the system-reminder). [inference: Anthropic expects a flywheel where skill-creator generates community skills that are then shared via the marketplace.]

---

## Data flow

Skills do not have a runtime data flow in the traditional sense — they are instruction documents. The data flow at execution time is:

```
User invokes /skill-name (or Claude auto-loads relevant skill)
  → Claude Code reads SKILL.md from .claude/skills/<skill-name>/
  → SKILL.md body is prepended to or appended to Claude's context for the interaction
  → Claude follows the instructions in SKILL.md
  → If the skill includes helper scripts (e.g., Python scripts, shell scripts),
    Claude may call Bash tool to execute them as directed by SKILL.md
  → Skill execution completes when the task described in SKILL.md is done
```

---

## Dependencies

Skills themselves have no runtime dependencies beyond Claude Code. Individual skills may reference external tools (e.g., the docx skill uses a Python library for Word document manipulation; the pdf skill uses PDF libraries). These are tool-level dependencies, not framework dependencies.

The spec itself depends on:
- Claude Code (or a compatible agent runtime)
- The `~/.claude/skills/` or `.claude/skills/` directory convention

---

## Security model

**What permissions/hooks do they install?**
Skills themselves do not install hooks. They are instruction documents loaded into Claude's context. However:

- Skills that include shell scripts can trigger arbitrary code execution via Claude's Bash tool
- Skills are loaded from the filesystem, so any process that can write to `.claude/skills/` can inject skill instructions into Claude's context — a supply chain risk
- The Anthropic-published skills are source-available, so their contents can be audited; community marketplace skills cannot be audited without manual review
- No sandboxing mechanism is defined in the spec for skill execution — skills that instruct Claude to run scripts have the full permissions of the Claude Code process

From https://code.claude.com/docs/en/skills: skills are "trusted" — they execute in the same security context as Claude Code's built-in instructions.

---

## Testing strategy

From web research: Anthropic's `skill-creator` skill includes evaluation capabilities ("run evals to test a skill, benchmark skill performance with variance analysis"). The spec does not mandate a testing framework for skills. Individual skills may include example inputs/outputs in their SKILL.md. The `skill-creator` meta-skill can be used to generate and run evals programmatically [inference: this is the pattern described in the skill-creator skill's trigger description in the session system-reminder].

---

## Genuinely reusable elements

Since this is reference-only (copy_allowed: false), the reusable elements are:

**1. The Agent Skills specification** (Apache-2.0 for the spec itself per https://github.com/anthropics/skills)
The SKILL.md format, frontmatter schema, directory layout, and trigger conventions are open for implementation. Any skill written conforming to the spec is compatible with the ecosystem without copying Anthropic's skill bodies.

**2. The skill composition pattern**
The pattern of a `SKILL.md` file with a `description:` field used for auto-loading is directly applicable to skills built for this lab. This pattern is in use in this very Claude Code session.

**3. The skill-creator meta-skill concept**
The idea of a skill that teaches Claude to create other skills is a design pattern — adapt the concept without copying the content.

**4. The three-scope installation hierarchy**
Project, user, and plugin scopes for skill discovery is a clean architecture for skill management. Applicable to any plugin or extension system built on top of Claude Code.

---

## What NOT to reuse

- The source-available document skills (docx, pdf, pptx, xlsx) — copy_allowed: false
- Any SKILL.md bodies from this repository — reference-only means study the pattern, not copy the content
- The Anthropic brand identity and skill names — these are proprietary

---

## Production-readiness

**Production-ready as a specification and reference.**

The Agent Skills ecosystem launched on October 16, 2025 and is actively used by hundreds of thousands of developers (https://medium.com/@markchen69/claude-code-has-a-skills-marketplace-now-a-beginner-friendly-walkthrough-8adeb67cdc89). The spec is stable and multiple compliant implementations exist (claude-mem's plugin/skills/, vercel-labs/skills CLI, third-party marketplaces). Anthropic's own first-party skills are production-ready.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- The SKILL.md format is the simplest possible representation of a reusable instruction package — just Markdown
- The spec is designed as an open standard, creating ecosystem compatibility
- Source-available document skills are visible for study even if not copyable
- The skill-creator meta-skill enables community self-service skill development
- First-party skills demonstrate best practices and serve as templates

**Weaknesses:**
- No LICENSE file at repository root creates ambiguity for the whole repository
- The two-tier licensing (Apache for some, source-available for document skills) creates friction for developers who want to understand what they can use
- No formal security model for skill execution in the spec — skills with embedded scripts are implicitly trusted
- No versioning mechanism in the spec — a skill update silently changes behavior for all users

**Technical debt:**
- The absence of a root LICENSE file is a significant omission for an Anthropic-published repository; this was noted in SOURCE.yaml and is why copy_allowed is false
- Skills marketplace fragmentation (skillsmp, claudeskills.info, skills.pawgrammer.com, claudemarketplaces.com) suggests no official centralized marketplace exists yet

---

## Novel or differentiated elements

1. **Agent Skills as an open interoperability standard** — By publishing the spec openly and designing it to work across multiple agents (Claude Code, Codex, Cursor via vercel-labs/skills), Anthropic is attempting to create a cross-agent skills ecosystem rather than a Claude-proprietary extension system. This is differentiated from OpenAI's GPT plugins (which were ChatGPT-specific).
2. **Source-available reference skills** — Making complex document-editing skills visible but not copyable creates an unusual hybrid: a learning resource that cannot be directly reused. This is a deliberate ecosystem strategy.
3. **Meta-skill (skill-creator) in the official repository** — The presence of a skill that creates other skills means the Anthropic repo ships its own growth mechanism.

---

## Possible clean-room adaptations

- **Write original skills conforming to the Agent Skills spec** for this lab's use cases (flashcard generation, memory search, study assistant) using the SKILL.md format — this is explicitly what the spec is designed to enable.
- **Build a skill management system** using the three-scope hierarchy (project / user / plugin) as the discovery mechanism, without copying any skill bodies.
- **Implement the skill-creator concept** — write an original skill-creator skill for this lab's specific skill types (study skills, memory skills, RAG skills) that uses the same meta-skill concept without copying Anthropic's instructions.

---

## Business applications

1. **Skill marketplace for vertical industries** — A curated, validated set of Claude Code skills for specific industries (legal, medical, finance) where quality and correctness matter more than quantity. The spec is open; the value is curation and validation.
2. **Enterprise skill management platform** — A system for enterprise teams to create, version, test, and deploy custom skills across their developer fleet, with access controls, audit logging, and skill review workflows.
3. **Skill-as-a-service** — Charge for high-quality, professionally maintained skills (similar to how npm packages are monetized via hosted services) — the skill marketplace model is currently under-commercialized.
4. **Skills bundled with SaaS products** — A SaaS company bundles proprietary skills with their product (e.g., a Jira integration skill, a Salesforce data skill) as a differentiated developer experience layer.

---

## Related business ideas in this lab

- claude-mem (`sources/persistent-agent-memory/`) ships its own skill library (plugin/skills/) including mem-search, standup, timeline-report — these are production examples of custom skills built on top of claude-mem's memory layer.
- The modular-rag-learning pipeline could be packaged as a set of skills (flashcard-generator, quiz-creator, document-summarizer) conforming to the Agent Skills spec.

---

## Related sources in this lab

- `sources/vercel-skills/` — Implements a CLI toolchain for managing skills conforming to the same spec; represents the "tooling layer" above the spec
- `sources/persistent-agent-memory/` — claude-mem's `plugin/skills/` directory contains production skills built on the Agent Skills format

---

## Open questions

1. What is the exact schema of `spec/agent-skills-spec.md`? Specifically: what frontmatter fields are mandatory vs. optional, and what are the exact trigger semantics for auto-loading?
2. Are the document skills (docx/pdf/pptx/xlsx) source-available under a specific named license, or is it an ad-hoc "visible but not copyable" restriction? The SOURCE.yaml says `license_file_verified: false` — there may be no formal license statement.
3. How does Anthropic plan to commercialize the skills ecosystem? Is there an official marketplace planned, or is the ecosystem intentionally decentralized?
4. Does the spec define any sandboxing, capability declaration, or permission model for skills that include executable scripts?
5. How does skill versioning work in the spec? If a skill is updated, do users get the update automatically or only on manual reinstall?

---

## Final research conclusion

The `anthropics/skills` repository is primarily valuable to this lab as a specification source and architectural reference, not as code to copy. The Agent Skills spec defines the format for all skill-based work in this lab and in the broader Claude Code ecosystem. The key insight is that the spec is an open interoperability standard — skills written conforming to it work across multiple agents, not just Claude Code. The source-available document skills (docx/pdf/pptx/xlsx) are visible for studying best practices in complex skill design. The skill-creator meta-skill concept is the most generatively valuable idea: a skill that enables the ecosystem to self-expand. The correct posture for this lab is to write original skills conforming to the spec, study the Anthropic reference implementations for structural patterns, and not copy any skill bodies due to the ambiguous license status.
