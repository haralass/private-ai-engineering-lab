# Source Research Dossier: code-review-assistant

---

## Repository identity

- **Name:** code-review-assistant
- **Creator:** Paul Bakaus (pbakaus)
- **GitHub URL:** https://github.com/pbakaus/code-review-assistant
- **Source path:** `sources/code-review-assistant/`
- **License:** NOT-FOUND (no LICENSE file)
- **Import type:** reference-only — no code may be copied; study only
- **Pinned commit:** `5e801967db2cba84c0f69faff4e896538a31ae77`

---

## What it actually does

code-review-assistant is a Claude Code skill for AI-powered code review. The lab's README for this source states only: "Source: https://github.com/pbakaus/code-review-assistant / No code copied. Study only." No code is vendored. Web research establishes that Paul Bakaus is a prolific Claude Code skill creator — he is primarily known for `impeccable` (a design language skill for AI frontends, 15,000+ GitHub stars) and `agent-reviews` (token-efficient automated GitHub review workflows). `code-review-assistant` at the pinned commit is a separate repository from these more famous projects.

Claude Code skills are markdown-based instruction sets (stored in `.claude/` directories or as skill packages) that provide agents with specialized domain knowledge and review protocols. A code-review skill typically defines: what to look for (correctness bugs, security issues, style violations, performance anti-patterns), how to report findings (severity, file:line references, suggested fix), and optionally what to automate (PR comment posting, auto-fix application).

---

## Architecture

[inferred from: lab notes, knowledge of Paul Bakaus's other Claude Code skills, the Claude Code skill ecosystem, and web research]

A Claude Code skill repository typically has:

1. **`CLAUDE.md` or skill instruction file:** Markdown file containing agent instructions — the "system prompt fragment" that tells the agent how to perform code review. Includes review checklist, severity levels, output format requirements.

2. **Optionally: slash commands** (`.claude/commands/` or similar): Custom `/review`, `/security-review`, `/quick-check` commands that invoke the review with specific scope.

3. **Optionally: reference files:** Example good/bad code patterns, language-specific style guides, project conventions templates.

4. **Optionally: scripts or hooks:** Shell scripts for automating PR comment posting, integration with GitHub Actions, or git hook integration.

Paul Bakaus's other skills (impeccable, agent-reviews) follow this pattern: a well-structured markdown instruction set with deterministic rules and a clear output format. `agent-reviews` specifically provides "token-efficient, automated GitHub review workflows" and describes itself as skills that "work with any agent that supports Agent Skills (Claude Code, Cursor, Codex, etc.)."

[inference: code-review-assistant is likely a precursor to or complement of agent-reviews — possibly the simpler, single-file review skill that agent-reviews expanded upon]

---

## Main modules and important files

No code is vendored. Expected structure (inferred from Claude Code skill conventions):

- `CLAUDE.md` or `skill.md` — main skill instruction document
- Possibly: `.claude/commands/review.md` — slash command definition
- Possibly: `README.md` — usage instructions
- Possibly: `examples/` — sample review outputs

---

## Core technical patterns

1. **Structured review checklist:** Code review skills enumerate categories (correctness, security, performance, style) with specific checks per category. The output format is typically `[SEVERITY] file:line — description — suggested fix`.

2. **Diff-scoped review:** The skill instructs the agent to review only changed lines (the diff) rather than the entire codebase, reducing token consumption.

3. **Severity tiering:** Findings are classified as `critical` (must fix before merge), `major` (should fix), `minor` (nice to have), `nit` (style). This helps reviewers prioritize.

4. **Inline comment format:** Output structured for direct posting as GitHub PR review comments — file path + line number + comment body.

5. **False-positive mitigation:** Skilled review instructions include context about when a pattern is acceptable (e.g., "unchecked error is acceptable in tests") to reduce noise.

---

## Novel or interesting mechanisms

- **Creator's established quality bar:** Paul Bakaus is one of the most visible Claude Code skill creators. His `impeccable` skill for design reviews has 15,000+ GitHub stars and introduced the pattern of "deterministic detector rules" (44 rules in impeccable) alongside agent instructions. [inference: code-review-assistant likely follows a similar structure — a ruleset plus agent-readable instruction prose, not just a vague prompt]

- **Agent-reviews as a more evolved version:** pbakaus's `agent-reviews` (https://github.com/pbakaus/agent-reviews) is described as "token-efficient, automated GitHub review workflows and bug fixing that actually understand your codebase." This suggests `code-review-assistant` is an earlier, simpler version, and `agent-reviews` represents the matured form of the same idea.

- **Skills-ecosystem context:** The Claude Code skills ecosystem (as of mid-2026) has become a meaningful distribution channel for developer tools. Skills are discovered via Claude Code's `import skill` mechanism and community lists. A well-structured review skill from a credible creator (pbakaus) would have genuine adoption potential even without a formal product.

---

## Data flow

[inferred for a typical Claude Code review skill]

```
Developer: /review (or Claude Code picks up skill on PR)
        ↓
Agent reads skill instructions from code-review-assistant
        ↓
Agent runs: git diff HEAD~1 (or PR diff)
        ↓
Agent applies review checklist to diff:
  - Correctness bugs (logic errors, off-by-one, null dereferences)
  - Security (injection, auth bypass, secret exposure)
  - Performance (N+1 queries, unnecessary allocations)
  - Style/convention violations
        ↓
Agent formats findings: [SEVERITY] file.ext:line — description — suggestion
        ↓
Output to terminal / PR comments / auto-fix application
```

---

## Dependencies

No runtime dependencies for a skill (it is instructions, not executable code). Depends on:
- Claude Code or compatible agent (Cursor, Codex CLI, etc.)
- Git for diff extraction
- Optionally: GitHub CLI (`gh`) for PR comment posting

---

## Security model

- **No code execution:** Skills are instruction documents; they do not execute code themselves.
- **Agent trust boundary:** The skill's instructions are trusted by the agent. A malicious skill could instruct an agent to exfiltrate code or apply harmful changes. The lab's reference-only status means no execution risk.
- **Diff privacy:** Code review involves sending diff content to the LLM (Claude API). For cloud API calls, diff content leaves the developer's machine — the same privacy concern as commit generators.
- **Auto-fix risk:** If the skill includes auto-fix instructions, agent-applied changes bypass human review — a correctness risk that requires human validation of auto-applied fixes.

---

## Testing strategy

Unknown — skills are typically tested manually by running them against real codebases. No automated test infrastructure expected for a skill repository.

---

## Genuinely reusable elements

**Cannot be copied** (no LICENSE file). However:

1. **Review checklist structure** — the categories and severity tiers used in Claude Code review skills are publicly documented in the Claude Code documentation and numerous open-source skills.
2. **Inline comment format convention** — the `file:line — description — suggestion` format is standard.
3. **Agent-reviews (separate repo, also by pbakaus)** — also has no license per the lab's research; same constraint.
4. **Claude Code's built-in `/code-review` skill** — the official Claude Code code review skill is from Anthropic itself and is Apache-2.0 licensed; it is directly usable (https://github.com/anthropics/claude-code/blob/main/plugins/code-review/README.md).

---

## What NOT to reuse

- Any code or instruction text from this repository cannot be legally copied.
- Auto-fix features should not be applied without human review — agent-generated code changes have significant correctness risk.

---

## Production-readiness

Skills are inherently "production-ready" in the sense that they are instruction documents, not software. The quality of the review outputs depends on the quality of the instruction document and the underlying model. Without reading the actual skill file, production-readiness cannot be assessed. [inference: given the creator's reputation and the existence of the more mature agent-reviews, code-review-assistant is likely a clean, well-structured skill that is production-usable for developer teams.]

---

## Strengths / Weaknesses / Technical debt

**Strengths (inferred):**
- Credible creator with established track record in the Claude Code skill ecosystem
- Part of a broader developer tools portfolio (impeccable + agent-reviews + code-review-assistant)
- Skill format is language-agnostic — works for any codebase

**Weaknesses:**
- No license — cannot be copied
- Skill quality is unverifiable without reading the actual file
- All AI code review tools share the same fundamental limitation: they miss context that a human reviewer knows from working on the codebase for months

**Technical debt:** N/A for a skill document.

---

## Novel or differentiated elements

Within the Claude Code skills ecosystem, code review skills are common. Paul Bakaus's differentiation is his **deterministic rule approach** — rather than relying solely on the LLM's judgment, his skills (especially impeccable) encode deterministic checks that the agent applies mechanically before exercising judgment. [inference: code-review-assistant likely follows the same pattern for code quality, encoding specific anti-patterns as deterministic checks.]

The broader differentiation of a privacy-aware code review skill (one that processes diffs locally without cloud API calls) does not appear to exist in the current market as a packaged product — a gap this lab could address by combining the review skill pattern with AirLLM's local inference.

---

## Possible clean-room adaptations

1. **Privacy-safe code review skill:** A Claude Code skill that runs entirely locally using an Ollama or AirLLM backend — no diff content sent to cloud APIs. Combine the review checklist structure (from public Claude Code skill documentation) with a local inference backend.

2. **Language-specific review skill:** A specialized review skill for a single language (Go, Python, TypeScript) with deterministic checks specific to that language's anti-patterns and idioms.

3. **Security-focused review skill:** A review skill focused specifically on OWASP Top 10, injection patterns, authentication flaws, and secret exposure — a narrower scope with higher precision than a general code review.

4. **CI-integrated review skill:** A skill packaged as a GitHub Action that runs on every PR, posts structured review comments, and blocks merge on `critical` findings. Complements the commit message validation use case from privacy-safe-commit-assistant.

5. **Combined developer AI toolkit skill:** A meta-skill that orchestrates commit generation (privacy-safe-commit-assistant pattern), code review (this pattern), and PR summary generation — a complete developer AI workflow in a single skill package.

---

## Business applications

1. **Enterprise AI code review product (B2B SaaS):** A polished, enterprise-ready AI code review tool that integrates with GitHub/GitLab/Bitbucket, supports on-premise deployment (for regulated industries), and offers team-level configuration of review rules. Market: enterprise engineering teams. Competitors: CodeRabbit, Sourcery, GitHub Copilot's review features. Differentiator: privacy/local deployment + deterministic rules.

2. **Developer productivity platform:** Bundle commit generation + code review + PR summary into a single subscription tool. Lower price point, broader market. Target: individual developers and small teams. Competitors: aicommits (commit only), Sourcery (review only).

3. **Language-specific code quality SaaS:** Deep specialization in a single language ecosystem (e.g., Go — consistent with creator's forgequeue stack) with community-maintained rule sets. The "Golangci-lint for AI" — not replacing linters but adding semantic/logic-level checks.

4. **Privacy-as-a-feature agency tool:** For agencies and consultancies working with multiple clients, a privacy-safe review tool ensures no client code leaks to another client's LLM context. Strong professional services angle.

---

## Related business ideas in this lab

- `sources/privacy-safe-commit-assistant/` — direct companion tool; together they form a "privacy-first AI developer toolkit"
- `sources/model-layer-streaming/` — AirLLM enables fully local code review inference (slow but private)
- `sources/durable-background-job-queue/` — async code review job queue for large PRs
- `sources/asynchronous-job-processing/` — async pattern for code review pipeline

---

## Related sources in this lab

- `sources/privacy-safe-commit-assistant/` — same domain (AI developer tooling), complementary tool
- `sources/model-layer-streaming/` — local inference backend for privacy-safe review
- `sources/durable-background-job-queue/` — async infrastructure for review job processing

---

## Open questions

1. What specific review checklist categories does the skill define? (Language-agnostic? Security-focused? General?)
2. Does the skill include deterministic rules (like impeccable's 44 detector rules) or relies solely on LLM judgment?
3. What is the relationship to `agent-reviews` (pbakaus's more recent, more prominent project)? Is code-review-assistant a precursor, a simpler version, or a different scope?
4. Does the skill include auto-fix capabilities or review-only?
5. What is the output format — inline PR comments, terminal output, or a structured report?
6. Is it designed for Claude Code specifically, or for any LLM agent?

---

## Final research conclusion

code-review-assistant is a reference-only Claude Code skill from Paul Bakaus, a credible and prolific skill creator. Without reading the actual skill file, the precise contents are unknown, but the creator's track record (impeccable, agent-reviews) strongly suggests a well-structured, deterministic-rules-plus-agent-instruction approach. The no-license constraint prevents direct use. The strongest opportunity this source identifies is the **privacy gap in AI code review**: all major AI code review products (CodeRabbit, Sourcery, GitHub Copilot review, agent-reviews) require sending diff content to cloud APIs. A locally-runnable review skill (combining this pattern with AirLLM or Ollama) would be a differentiated, commercially relevant product for regulated-industry engineering teams. Combined with privacy-safe-commit-assistant, this source is one half of a coherent "privacy-first AI developer toolkit" product concept.

URL citations:
- https://github.com/pbakaus/impeccable
- https://github.com/pbakaus/agent-reviews
- https://github.com/pbakaus
- https://dev.to/heraldofsolace/the-6-best-ai-code-review-tools-for-pull-requests-in-2025-4n43
- https://medium.com/@piyalidas.it/best-ai-code-review-agents-for-github-prs-2026-ac4c86ef3a63
- https://findskill.ai/skills/claude-code/ai-code-review/
