# Source Research Dossier: vercel-skills

## Repository identity

- **Name:** skills (Vercel Agent Skills CLI)
- **Creator:** Vercel Labs (vercel-labs organization)
- **GitHub URL:** https://github.com/vercel-labs/skills
- **Source path:** sources/vercel-skills/ (no upstream/ directory — reference-only)
- **License:** NOT-FOUND (no LICENSE file; copy_allowed: false)
- **Import type:** Reference-only
- **Pinned commit:** e5c075e3a84b37c5eb398ab74e581558d3fceb0e
- **Retrieved at:** 2026-06-22T05:14:16Z
- **Security review:** pending
- **Decision:** reference-only — study only, do not copy
- **Notes from SOURCE.yaml:** "No LICENSE file — reference-only. find-skills pattern studied."

---

## What it actually does

`vercel-labs/skills` is a CLI toolchain (`npx skills`) for managing Agent Skills across multiple AI coding agents. Rather than maintaining skills as repositories of instruction documents, this project provides the operational layer: installing skills from registries, symlinking them into agent-specific directories, and managing which skills are active for which agents. It is the package manager layer for the Agent Skills ecosystem, analogous to npm for Node modules or pip for Python packages — but for AI agent instruction sets.

The tool understands the skill directory conventions for 70+ AI agents (Claude Code, OpenCode, Codex, Cursor, Gemini CLI, Copilot, Windsurf, and more) and provides a unified `npx skills add/remove/list` interface that installs skills into the correct location for each agent. It is part of the Vercel OSS Program (visible in the claude-mem README which displays a Vercel OSS badge).

---

## Architecture

Based on web research (ref-only; no upstream/ directory in this lab):

```
npx skills (CLI entry point)
  ├── install resolver
  │   ├── skill registry lookup (github.com or local)
  │   ├── agent detection (-a claude-code / --agent flag or auto-detect)
  │   └── directory placement:
  │         Claude Code:  ~/.claude/skills/<skill-name>/  (global)
  │                       .claude/skills/<skill-name>/    (project)
  │         OpenCode:     ~/.opencode/skills/<skill-name>/
  │         Universal:    ~/.agents/skills/<skill-name>/
  │                       .agents/skills/<skill-name>/
  │
  ├── skill discovery (find-skills pattern)
  │   └── scans known agent directories + ~/.agents/skills/ canonical path
  │
  └── skill management
      ├── add <skill-name> [-g] [-a <agent>]
      ├── remove <skill-name>
      └── list
```

Key architectural tension (from GitHub issues): Claude Code is a "non-universal" agent with its own skills directory (`~/.claude/skills/`) rather than using the canonical `~/.agents/skills/`. The Vercel CLI installs to `~/.agents/skills/` by default and was initially failing to create a symlink to `~/.claude/skills/`, causing Claude Code to not see installed skills (Issues #693, #851, #1045, #1355 — https://github.com/vercel-labs/skills/issues/).

---

## Main modules and important files

Based on web research and GitHub issue analysis (ref-only):

| Component | Purpose |
|-----------|---------|
| CLI entry (`npx skills`) | Main entry point; `skills add`, `skills remove`, `skills list` subcommands |
| Agent registry | Maps agent names to their skill directory paths and symlink conventions |
| Skill resolver | Fetches skills from GitHub repos or local paths |
| find-skills pattern | Discovery algorithm that scans known skill directories; explicitly noted as "studied" in SOURCE.yaml |
| Directory symlinking | Creates cross-agent compatibility links (`.agents/skills/` ↔ `.claude/skills/`) |
| Plugin bundling detection | Feature request (Issue #718) — discovering skills bundled inside Claude Code plugins like claude-mem |

---

## Core technical patterns

**1. Universal skills directory + per-agent symlinks**
The canonical location for installed skills is `~/.agents/skills/<skill-name>/` (global) or `.agents/skills/<skill-name>/` (project). For agents like Claude Code that use a different path, the CLI creates a symlink from `~/.claude/skills/<skill-name>` → `~/.agents/skills/<skill-name>`. This allows a single `npx skills add` invocation to work across all agents without the user knowing each agent's directory convention.

**2. Agent-name-keyed capability map**
The CLI maintains an internal map from agent names (strings like "claude-code", "codex", "cursor") to their `globalSkillsDir` and `projectSkillsDir` paths. This is the "find-skills pattern" noted as studied in SOURCE.yaml — a lookup table approach to multi-agent compatibility.

**3. Scope flags: global vs. project**
Skills can be installed with `-g` (global, `~/.agents/skills/`) or without (project-scoped, `.agents/skills/`). This maps to the Agent Skills spec's two user-facing scopes (user-level and project-level), with the third scope (plugin-bundled) being discovered separately.

**4. CLI-first distribution model**
`npx skills` is a zero-install entry point. This mirrors how Claude-mem distributes itself (`npx claude-mem install`) — the Vercel skills pattern is the CLI paradigm for the skills ecosystem rather than a manual copy-files approach.

---

## Novel or interesting mechanisms

**find-skills pattern** (explicitly studied per SOURCE.yaml)
This is a skill discovery algorithm that scans multiple standard locations to find installed skills, regardless of which agent or which scope installed them. The pattern is relevant because it provides a portable way to answer "what skills are currently available to agent X?" without the agent needing to hardcode its own directory paths.

**Cross-agent portability by symlink**
Rather than requiring each agent to support a universal path, the Vercel CLI uses the OS symlink mechanism to bridge agent-specific directories to the universal path. This is a pragmatic compatibility shim that allows new agents to be added to the ecosystem without modifying existing agents.

**Unified CLI across 70+ agents** [inference based on web research claims]
The claim that the tool supports "Claude Code, OpenCode, Codex, Cursor, Gemini CLI, Copilot, Windsurf, and more" suggests the agent registry is actively maintained and represents a community coordination point for the skills ecosystem.

---

## Data flow

```
Developer runs: npx skills add tsembp/study-skills -a claude-code -g

  1. CLI resolves "tsembp/study-skills" from GitHub
     (GET https://api.github.com/repos/tsembp/study-skills or local path)
  2. Downloads/copies skill SKILL.md + supporting files
  3. Determines install path for claude-code:
       global → ~/.agents/skills/study-skills/   (canonical install)
              → creates symlink: ~/.claude/skills/study-skills → ~/.agents/skills/study-skills/
  4. Claude Code now discovers the skill at ~/.claude/skills/study-skills/SKILL.md
     on next session start
```

---

## Dependencies

Based on web research (no source available for inspection):
- Node.js / npm (executed via npx)
- GitHub API (for fetching skills from repos) [inference]
- OS symlink support (ln -s or equivalent)
- No persistent daemon or background process [inference]

---

## Security model

**What permissions/hooks do they install?**

The Vercel skills CLI does not install Claude Code hooks. It only manages files in skills directories. However:

- `npx skills add <source>` fetches and places arbitrary files into `~/.claude/skills/` — these files will be loaded by Claude Code as trusted instructions
- The skill installation is effectively a supply chain trust decision: installing a skill from an untrusted GitHub repository places untrusted instructions into Claude's context
- No signature verification, sandboxing, or permission review workflow is mentioned in web research
- The no-LICENSE-file status of the repository means the tool itself cannot be audited under a formal open source framework

From a security perspective, the Vercel skills CLI is a skill package manager with no currently documented security verification model — analogous to early npm with no integrity checksums. [inference]

---

## Testing strategy

Unknown from reference-only access. Given it is a CLI tool by Vercel Labs, it likely uses standard Node.js/TypeScript testing. The bug tracker (GitHub issues) indicates active maintenance and bug discovery through community use rather than comprehensive automated tests — several confirmed bugs around Claude Code symlink behavior (Issues #693, #851, #1045, #1355) were filed by users, not caught by CI. [inference]

---

## Genuinely reusable elements

Since this is reference-only (copy_allowed: false):

**1. The find-skills pattern concept** (studied, noted in SOURCE.yaml)
The algorithm for discovering installed skills across multiple known directories (per-agent paths + universal canonical path) is a portable discovery pattern. This can be re-implemented in any system that needs to find skills for a given agent without knowing in advance what agents are installed.

**2. Agent-name-to-directory registry concept**
Maintaining a lookup table mapping agent names to their skill directory conventions is a simple but valuable abstraction. A clean-room implementation would be a JSON/YAML config file mapping agent identifiers to path templates.

**3. Scope-keyed installation pattern**
The global/project scope distinction (with the universal canonical directory as the install target and per-agent symlinks as compatibility shims) is a clean architecture for any multi-agent tool distribution system.

---

## What NOT to reuse

- The CLI source code itself — copy_allowed: false, no LICENSE
- The internal agent registry data — this may be proprietary to Vercel Labs
- Any npm package names or the `npx skills` command interface — potential trademark conflicts

---

## Production-readiness

**MVP-quality.**

The project has active issues (1182+ filed per GitHub issue numbering at pinned commit) indicating a large and engaged user base, but also significant known bugs in the Claude Code integration path specifically (multiple issues about missing symlinks filed within weeks of each other). The core functionality works for universal agents but Claude Code support is known-broken in edge cases at the pinned commit. No license means formal production deployment has legal risk.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- Solves a real coordination problem: how do users install skills without knowing each agent's directory convention?
- Zero-install `npx` distribution lowers the barrier to entry
- Active GitHub community (1182+ issues, active issue filing) indicates real user adoption
- Designed as an open standard tool (not Vercel-proprietary), promoting ecosystem health

**Weaknesses:**
- No LICENSE file — the tool cannot be legally incorporated into other projects
- Claude Code symlink bugs present at the pinned commit — a key use case is broken
- No skill integrity verification — installing from arbitrary GitHub repos is a supply chain risk
- Does not discover skills bundled inside Claude Code plugins (Issue #718 — a common pattern, as seen with claude-mem's plugin/skills/)
- The agent registry is likely a hardcoded or frequently-updated list — scalability concern as the number of AI agents grows

**Technical debt:**
- The Claude Code non-universal directory issue represents a structural compatibility debt — symlinks are a workaround, not a resolution; the spec itself needs to define a migration path to the universal directory
- The missing symlink bugs (filed as separate issues across project and global scope) suggest the symlink logic was added as an afterthought rather than designed in from the start

---

## Novel or differentiated elements

1. **Cross-agent skill package manager** — No other known tool provides a unified CLI for installing Agent Skills across 70+ AI agents. This is the package management layer the ecosystem was missing.
2. **Symlink-based compatibility shim** — Using OS symlinks to bridge per-agent and universal skill paths is a clever, implementation-agnostic compatibility mechanism.
3. **find-skills discovery algorithm** — Scanning multiple well-known paths to discover skills without configuration is a zero-config pattern applicable to any agent skill discovery problem.

---

## Possible clean-room adaptations

- **Build a skills-aware installer for this lab's skill library** — An original CLI (or shell script) that installs this lab's custom skills into the correct directory for Claude Code (and optionally other agents), using the same scope/symlink concepts but with an original implementation.
- **Agent capability registry** — A JSON config that maps agent names to their skill directory paths, which can be used programmatically by skill management tooling. This is a data asset that can be created clean-room from documentation.
- **find-skills algorithm reimplementation** — Write an original implementation of the skill discovery pattern for use in a Claude Code plugin that needs to introspect what skills are installed.

---

## Business applications

1. **Enterprise skills governance platform** — A managed version of the skills CLI with: private skill registries (like npm Enterprise), skill approval workflows, usage auditing, and version pinning. Enterprise teams cannot safely use public skill registries without a governance layer.
2. **Skills package marketplace with integrity verification** — A hosted service that signs skills packages and provides checksum verification, addressing the supply chain security gap in the current ecosystem. Analogous to what PyPI does for Python packages.
3. **AI agent onboarding automation** — A product that automatically detects which AI coding agents a developer uses and installs a standardized set of approved skills for their organization, as part of a developer environment setup tool.
4. **Skill analytics and usage telemetry** — A managed skills manager that collects aggregated data on which skills are used, how often, and which produce the best outcomes, enabling data-driven skill quality rankings.

---

## Related business ideas in this lab

- Claude-mem (`sources/persistent-agent-memory/`) is already distributed via `npx claude-mem install` — the same distribution pattern as `npx skills`. Building a unified installer that sets up both claude-mem and custom skills in one step would reduce developer friction.
- The modular-rag-learning pipeline could be packaged as skills distributed via this mechanism.

---

## Related sources in this lab

- `sources/anthropic-skills/` — Defines the spec that `vercel-labs/skills` implements the tooling for; these two sources are complementary layers (spec + tools)
- `sources/persistent-agent-memory/` — claude-mem's `plugin/skills/` directory contains skills that could be distributed via `npx skills add thedotmack/claude-mem-skills`; Issue #718 in vercel-labs/skills specifically mentions discovering plugin-bundled skills

---

## Open questions

1. What is the exact structure of the agent registry in the tool's source code? Is it a JSON file, a TypeScript map, or something else? This would inform a clean-room reimplementation.
2. How does the tool handle skill updates — does `npx skills add` overwrite an existing skill or version-lock it?
3. Is there a mechanism for skill namespacing (to avoid collisions when two skills from different sources have the same name)?
4. What does the `find-skills` pattern return when both `~/.claude/skills/` and `~/.agents/skills/` exist — does it deduplicate?
5. Does the tool have any integration with the Anthropic skills marketplace, or is it purely GitHub-based for skill source resolution?
6. The Vercel OSS Program badge on claude-mem's README suggests a relationship — is there a formal partnership or integration between claude-mem and the Vercel skills CLI?

---

## Final research conclusion

`vercel-labs/skills` is the operational tooling layer of the Agent Skills ecosystem — the package manager that makes skills installable without manual file copying. Its primary value to this lab is the `find-skills` discovery pattern (explicitly noted as studied in SOURCE.yaml) and the architectural concept of a cross-agent compatibility registry using symlinks. The absence of a LICENSE file prevents any direct code reuse. The correct posture is to use the studied patterns as design inputs for original implementations: a find-skills discovery function, an agent-capability registry, and a scope-aware installer. The Claude Code symlink bugs at the pinned commit are a known limitation; any clean-room reimplementation should design the Claude Code path integration properly from the start using `~/.claude/skills/` as the primary path rather than a symlinked fallback.
