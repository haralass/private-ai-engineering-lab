# Source Research Dossier: structured-agent-development

## Repository identity

- **Name:** superpowers
- **Creator:** Jesse Vincent / obra, Prime Radiant Inc.
- **GitHub URL:** https://github.com/obra/superpowers
- **Source path:** sources/structured-agent-development/upstream/
- **License:** MIT (verified)
- **Import type:** vendored-snapshot
- **Pinned commit:** 896224c4b1879920ab573417e68fd51d2ccc9072
- **Retrieved:** 2026-06-22

---

## What it actually does

Superpowers is a cross-agent software development methodology delivered as a plugin for 11 different AI coding agents (Claude Code, Codex, Cursor, Factory Droid, Gemini CLI, GitHub Copilot CLI, Kimi Code, OpenCode, Antigravity, Pi). Its core mechanism is a `SessionStart` hook that injects the `using-superpowers` bootstrap into every new session, causing skills to auto-trigger at the right moments without user intervention. The methodology enforces a strict sequence: design before code (brainstorming), isolated git worktrees, detailed task plans, subagent-driven parallel implementation with two-stage review (spec compliance + code quality), strict TDD, systematic debugging, and branch completion workflow. It ships 14 skills and is designed as a zero-dependency plugin.

---

## Architecture

```
Plugin root
├── skills/                    — 14 SKILL.md files; each describes a methodology step
│   ├── using-superpowers/     — bootstrap: lists all skills, when to use each
│   ├── brainstorming/         — pre-code design gate (HARD-GATE enforced)
│   ├── writing-plans/         — implementation plan generation
│   ├── subagent-driven-development/ — parallel subagent orchestration
│   ├── executing-plans/       — batch execution with human checkpoints
│   ├── test-driven-development/    — enforced RED-GREEN-REFACTOR
│   ├── systematic-debugging/  — 4-phase root cause process
│   └── [8 more skills]
├── hooks/
│   ├── session-start          — bash script: injects using-superpowers into every session
│   ├── hooks.json             — Claude Code hook wiring (SessionStart matcher)
│   ├── hooks-codex.json       — Codex-specific hook wiring
│   └── hooks-cursor.json      — Cursor-specific hook wiring
├── tests/                     — plugin infrastructure tests
├── docs/                      — harness-specific READMEs
└── scripts/                   — build/test scripts
```

The key architectural insight: skills are useless unless they auto-trigger. The `SessionStart` hook injects `using-superpowers/SKILL.md` into the agent's context at the start of every session. This bootstrap tells Claude about all other skills and when to invoke them automatically. Without the bootstrap, skills are dead weight on disk.

The `AGENTS.md` / `CLAUDE.md` content is identical and explicitly addresses AI agents contributing to the repo — notable for its 94% PR rejection rate warning and detailed rules about what constitutes agent slop.

---

## Main modules and important files

| Path | What it does |
|---|---|
| `hooks/session-start` | Bash script: reads `using-superpowers/SKILL.md`, JSON-escapes it, emits platform-specific hook JSON; handles Claude Code (`hookSpecificOutput.additionalContext`), Cursor (`additional_context`), and SDK standard (`additionalContext`) |
| `hooks/hooks.json` | Claude Code hook config: `SessionStart` with matcher `startup|clear|compact` |
| `skills/using-superpowers/SKILL.md` | Bootstrap: lists all 14 skills with trigger conditions; injected at session start |
| `skills/brainstorming/SKILL.md` | Pre-code design gate with `<HARD-GATE>` enforcement; Socratic design flow; writes `docs/superpowers/specs/YYYY-MM-DD-<topic>-design.md` |
| `skills/subagent-driven-development/SKILL.md` | Orchestration: dispatches fresh implementer subagent per task, task-reviewer subagent after each, final code-reviewer subagent at end; dot-graph process diagrams |
| `skills/test-driven-development/SKILL.md` | Enforces RED-GREEN-REFACTOR; "Iron Law": delete pre-written code before tests; no exceptions documented |
| `skills/writing-plans/SKILL.md` | Task plan format: each task has exact file paths, complete code, verification steps; 2-5 minute granularity |
| `skills/systematic-debugging/SKILL.md` | 4-phase root cause process: reproduce, isolate, hypothesize, verify |
| `skills/writing-skills/SKILL.md` | Meta-skill: creates new skills following superpowers conventions |
| `gemini-extension.json` | Gemini CLI extension config (alternative to plugin.json for that platform) |

---

## Core technical patterns

**Pattern 1 — SessionStart bootstrap injection**
The session-start hook reads the `using-superpowers/SKILL.md` file, JSON-escapes it with fast bash parameter substitution (not a character loop), and emits it as `additionalContext` in the platform-appropriate format. This is the mechanism by which skills auto-trigger without user action — Claude knows about all skills from the first message.
Location: `hooks/session-start`, `hooks/hooks.json`

**Pattern 2 — Multi-platform hook format negotiation**
The session-start hook detects the platform via environment variables (`CURSOR_PLUGIN_ROOT`, `CLAUDE_PLUGIN_ROOT`, `COPILOT_CLI`) and emits different JSON structures for each:
- Cursor: `{ "additional_context": "..." }`
- Claude Code: `{ "hookSpecificOutput": { "hookEventName": "SessionStart", "additionalContext": "..." } }`
- SDK standard / Copilot CLI: `{ "additionalContext": "..." }`
This is the reference implementation for cross-agent hook compatibility.
Location: `hooks/session-start` lines 38-47

**Pattern 3 — Fresh subagent per task in subagent-driven-development**
Each implementation task gets a completely fresh subagent with precisely constructed context. The orchestrating agent does NOT share its session history with subagents. Two-stage review: spec compliance check, then code quality check. A fix subagent handles Critical/Important findings before the next task begins. The flow is documented as dot-graph process diagrams inside the SKILL.md.
Location: `skills/subagent-driven-development/SKILL.md`

**Pattern 4 — HARD-GATE XML blocks**
Skills that enforce ordering use `<HARD-GATE>` XML wrapper blocks with explicit prohibitions: "Do NOT invoke any implementation skill, write any code, scaffold any project, or take any implementation action until you have presented a design and the user has approved it." Applied universally: "A todo list, a single-function utility, a config change — all of them."
Location: `skills/brainstorming/SKILL.md`, `skills/test-driven-development/SKILL.md`

**Pattern 5 — Spec self-review before user review**
After writing the design doc, brainstorming performs an inline spec self-review (check for placeholders, contradictions, ambiguity, scope creep) before presenting it to the user. This reduces the user's review burden by one pass. The self-review is part of the documented checklist, not an optional step.
Location: `skills/brainstorming/SKILL.md`, checklist items 7-8

---

## Novel or interesting mechanisms

**The `using-superpowers` bootstrap as the activation mechanism:** Most skill systems require the user to remember and invoke skills. Superpowers flips this: the agent knows about all skills from session start and invokes them automatically when context triggers them. The bootstrap is the entire distribution model — it transforms passive skill files into active behavior. This is the most important design decision in the system.

**Platform-detection via env vars in a bash hook:** The hook uses `CURSOR_PLUGIN_ROOT`, `CLAUDE_PLUGIN_ROOT`, and `COPILOT_CLI` to detect the host platform and emit the right JSON format. This is fragile but works across all 11 platforms without requiring platform-specific code. A single bash script serves as the cross-platform adapter layer.

**The "human partner" terminology:** Skills consistently use "your human partner" instead of "the user." The CLAUDE.md explicitly forbids changing this terminology without eval evidence. This is a deliberate framing choice: it positions the AI agent as having responsibility for protecting the human's interests (as in the anti-slop-PR section), not just responding to commands. The language choice shapes agent behavior.

**94% PR rejection rate transparency:** The CLAUDE.md/AGENTS.md explicitly states the rejection rate and explains it is mostly from AI agents submitting slop PRs. The anti-slop requirements (identify model/harness/version, search for existing PRs, verify the problem is real, get human approval of the diff) are behavioral guardrails that use the repo's own governance as a mechanism to improve agent behavior.

**Dot-graph process diagrams in SKILL.md files:** Skills like `brainstorming` and `subagent-driven-development` include Graphviz dot-notation flow diagrams embedded in markdown. These serve as machine-readable process specifications that Claude can follow as a flowchart. This is unusual and may improve following complex multi-step processes versus prose alone.

**The `using-git-worktrees` skill as isolation primitive:** Every development task starts in a fresh git worktree on a new branch with `using-git-worktrees`. This creates parallel workspace isolation natively — multiple features can be developed simultaneously without polluting the main branch. The skill runs `project setup` and verifies a clean test baseline before implementation begins.

---

## Data flow

```
User opens agent session
  → SessionStart hook fires
  → session-start script reads using-superpowers/SKILL.md
  → JSON-escaped content injected as additionalContext
  → Claude knows all 14 skills and their trigger conditions from message 1

User: "Let's make a React todo list"
  → Claude auto-triggers brainstorming (HARD-GATE prevents code)
  → Brainstorming: explore project, ask questions, propose approaches
  → User approves design → design doc written to docs/superpowers/specs/
  → brainstorming invokes writing-plans
  → writing-plans: creates granular task list (2-5 min per task, file paths, code, verification)
  → User approves plan → subagent-driven-development invoked
  → For each task: fresh implementer subagent → code + tests + commit
  → task-reviewer subagent: spec compliance + code quality
  → If issues: fix subagent dispatched
  → All tasks done: final code-reviewer subagent
  → finishing-a-development-branch: verify tests, merge/PR/keep/discard options
```

---

## Dependencies

**Zero runtime dependencies by design.** No npm packages, no external services, no binaries. The plugin installs as pure Markdown + bash.

| Component | Requirement |
|---|---|
| `jq` | Not required (hook uses bash string manipulation) |
| git | For `using-git-worktrees` skill (runtime tool, not build dep) |
| Agent's subagent capability | For `subagent-driven-development`; degrades to `executing-plans` if unavailable |

The eval harness (`evals/`) depends on [superpowers-evals](https://github.com/prime-radiant-inc/superpowers-evals/) which uses tmux to drive real agent sessions, but this is a development dependency only.

---

## Security model

**Trust model:** Superpowers is a methodology enforcement system, not a security enforcement system. It shapes agent behavior via LLM prompt injection at session start rather than via deterministic code execution. All safety properties depend on the agent correctly following the skill instructions.

**What it enforces (soft, not hard):** Design before code, TDD, systematic debugging, confirmation before merging. These are workflow constraints, not OS-level restrictions.

**What it does NOT provide:** No shell command blocking, no file access restrictions, no secret scanning. A misbehaving agent can ignore the skills entirely — there is no exit-code enforcement mechanism (unlike dotclaude's hooks).

**The TDD "Iron Law":** "Write code before the test? Delete it. Start over." This is enforced by prose instructions to the LLM, not by filesystem monitoring. The strength of enforcement depends entirely on the model's compliance with strong instructions.

**Notable:** The CLAUDE.md explicitly tells AI agents not to hide their model/harness identity in PRs. This is a transparency norm, not a technical control.

---

## Testing strategy

Two test tracks:
1. **Plugin infrastructure tests** (`tests/`): test the hook scripts, plugin manifest structure, and cross-agent compatibility. Run via `npm test` / `run-*.sh` scripts.
2. **Skill behavior evals** (`evals/`): uses the `superpowers-evals` harness (external dependency) which drives real tmux sessions of Claude Code/Codex/Gemini CLI and judges skill compliance with an LLM verifier. This is described as "Drill" — adversarial pressure testing across multiple sessions with before/after eval results required for skill content changes.

The eval harness approach (driving real terminal sessions) is more realistic than most AI skill testing but also more expensive and slower. The 94% PR rejection rate suggests the maintainers hold the bar high for eval evidence before accepting skill changes.

---

## Genuinely reusable elements

**The SessionStart bootstrap injection pattern (MIT):** The mechanism of injecting a "meta-skill" at session start to activate all other skills automatically is the most important reusable insight from this source. Any agent framework that supports SessionStart hooks can use this pattern.

**The cross-platform hook format negotiation (MIT):** The env-var platform detection + JSON format switching in `session-start` is a clean reference implementation for writing hooks that work across Claude Code, Cursor, Copilot CLI, and other agents.

**The subagent orchestration methodology (MIT):** Fresh subagent per task + two-stage review (spec + quality) + fix subagent loop is a proven orchestration pattern that reduces context pollution and improves quality. Adaptable as a methodology specification for any multi-agent system.

**The HARD-GATE XML pattern (MIT):** Using XML wrapper blocks to enforce ordering constraints in LLM prompts is a prompt engineering technique applicable to any agentic skill system.

**The dot-graph process documentation style (MIT):** Embedding Graphviz flow diagrams in skill markdown as machine-readable process specs is independently useful for any complex multi-step skill.

---

## What NOT to reuse

**The bash JSON-escaping approach:** The current approach (bash parameter substitution) works but is documented as replacing a slower character loop. For production systems, use a proper JSON tool or language-native serialization.

**The skill content verbatim for other agents:** Superpowers' skill content is tuned for specific real-world behavior across 11 agents. The content ("your human partner," Red Flags tables, rationalization lists) is carefully tuned and fragile. Modifying it without the eval harness risks degrading behavior.

**The gemini-extension.json format:** Gemini CLI extension format may change; treat as implementation detail.

---

## Production-readiness

**Production-ready for individual developer use. MVP-quality for enterprise.** The plugin infrastructure is solid and cross-platform. However: skill enforcement depends entirely on LLM compliance, not deterministic code; the eval harness is an external dependency; and enterprise environments may need more rigorous controls (the HARD-GATE blocks are soft promises, not enforcement mechanisms). The stated 94% PR rejection rate and active commercial support offering (sales@primeradiant.com) suggest this is a serious project with genuine production usage.

---

## Strengths / Weaknesses / Technical debt

**Strengths:**
- Zero dependencies — installs as pure Markdown + bash on 11 different agents
- Genuinely cross-agent: the platform detection in session-start is clean
- The subagent-driven-development methodology is the most complete public specification of multi-agent orchestration
- The CLAUDE.md/AGENTS.md anti-slop guidance is unusually clear and useful

**Weaknesses:**
- All enforcement is soft (LLM-prompt-based), not hard (code-enforced)
- Skill behavior depends on model compliance — a model update could break behavior silently
- The eval harness is external (`superpowers-evals`), not included — testing requires significant setup
- No structured logging of which skills triggered or what decisions were made

**Technical debt:**
- The platform detection in session-start is fragile (depends on undocumented env vars from agent runtimes)
- `gemini-extension.json` format is separate from the plugin.json standard — dual maintenance
- The `hooks-codex.json` and `hooks-cursor.json` variations add maintenance surface for each new format change

---

## Possible clean-room adaptations

**A universal bootstrap injector:** A standalone tool that, given a `SessionStart` hook payload from any AI agent, injects a specified context string in the correct format. Built on the platform detection logic from `session-start`, but generalized to support arbitrary context injection for any plugin.

**A cross-agent skill registry:** A system where skills are defined in a canonical format and auto-translated to the correct SKILL.md/extension.json/etc format per agent. Superpowers demonstrates that the content is the same across agents but the container format differs — a registry that manages this translation would be independently useful.

**A methodology-as-code framework:** Extract the Superpowers workflow (brainstorm → plan → subagent-implement → review → TDD → debug → complete) into a framework that any agent can reference without needing Superpowers itself installed. Publish as a methodology specification rather than a skill system.

---

## Business applications

**Enterprise agent workflow standardization:** Organizations adopting AI coding agents struggle with inconsistent behavior across teams (some agents skip design, some don't write tests). A managed Superpowers deployment with audit trails (which skills triggered, which design docs were approved, which tests were written first) addresses compliance requirements for AI-assisted development.

**AI developer onboarding tool:** For new engineers working on a codebase, the brainstorming + writing-plans + TDD loop enforces good practices from day one. A productized version targeting bootcamps, onboarding programs, or junior developer teams has a clear value proposition.

**Cross-agent methodology layer:** The cross-agent compatibility (11 agents) positions Superpowers as a methodology vendor rather than a tool vendor — the value is the workflow, not the specific agent. A commercial version with team dashboards, compliance reporting, and centrally managed skill libraries addresses the enterprise market.

---

## Related business ideas in this lab

- `business-research/startup-white-spaces/agent-permission-firewall.md` — the HARD-GATE pattern in Superpowers is a prompt-based version of the policy enforcement problem; the firewall concept addresses the same need via deterministic code

---

## Related sources in this lab

- `sources/deterministic-agent-safety/` (dotclaude): same hook mechanism but for safety enforcement rather than workflow enforcement; complementary
- `sources/full-product-engineering-agent-stack/` (gstack): shares the sprint methodology philosophy; gstack is more comprehensive but Superpowers is more portable
- `sources/terminal-coding-agent/` (kimi-code): kimi-code explicitly supports Superpowers installation in its marketplace, suggesting practical interoperability

---

## Open questions

1. What is the actual behavior difference between `subagent-driven-development` and `executing-plans` when a model doesn't fully support subagents? Does it degrade gracefully?
2. How does the `using-superpowers` bootstrap interact with context compaction in long sessions? The `startup|clear|compact` matcher suggests it re-injects on compact — does this cause behavior discontinuities?
3. Does the commercial support offering (sales@primeradiant.com) have paying customers? If so, what do they pay for beyond the MIT-licensed core?
4. The anti-slop PR requirements are unusual. What is the actual PR workflow for the maintainers — do they use an internal agent with different rules?

---

## Final research conclusion

Superpowers is the most portable and pedagogically clear publicly available AI agent methodology system. Its most reusable insight — the SessionStart bootstrap injection pattern — is the most important cross-agent architectural primitive in the four sources analyzed, and its cross-platform hook format negotiation is the best available reference implementation for that problem.
