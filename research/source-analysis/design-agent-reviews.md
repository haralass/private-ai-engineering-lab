# Source Research Dossier: design-agent-reviews

---

## Repository identity

- **Name**: agent-reviews
- **Creator**: Paul Bakaus (pbakaus)
- **GitHub URL**: https://github.com/pbakaus/agent-reviews
- **Source path**: sources/design-agent-reviews/upstream/
- **License**: MIT
- **Import type**: vendored-snapshot (pinned commit a2f56449)
- **Vendor version**: 1.0.2
- **Related source in this lab**: design-quality-and-review (also pbakaus, see creator notes below)

---

## What it actually does

agent-reviews is a Node.js CLI and set of Claude Code skills that bridge the gap between GitHub PR review comments and AI coding agents. It fetches all PR comments (inline review comments, issue comments, and review bodies) from GitHub's API in a unified format, with bot/human classification, body cleanup, and thread resolution via GraphQL. The three included skills teach an AI agent a two-phase protocol: fetch and fix all existing comments first (synchronous), then enter a polling watch loop until no new bot comments arrive (async). It explicitly handles known CI bots — Copilot, CodeRabbit, Cursor Bugbot, Sourcery, Codacy, SonarCloud, Gemini Code Assist — filtering meta-comment noise and surfacing only actionable findings.

---

## Architecture

The project is structured as a small Node.js CommonJS package with a CLI entry point, three library modules, three skill definitions, and a Claude Plugin marketplace manifest. There is no build step; skills are installed to the local `.claude/skills/` folder via `node scripts/install-skills.js` which runs automatically on `npm install`.

```
bin/agent-reviews.js          CLI entry point, arg parsing, command routing
lib/github.js                 Auth, proxy-aware fetch, repo/branch detection
lib/comments.js               GitHub API calls, comment processing, bot classification, thread resolution
lib/format.js                 CLI output formatting with ANSI colors
skills/resolve-reviews/       Handles both human + bot PR comments
skills/resolve-agent-reviews/ Handles bot comments only
skills/resolve-human-reviews/ Handles human comments only
.claude-plugin/               Marketplace manifest for Claude Code plugin ecosystem
scripts/install-skills.js     Post-install skill deployer
test/                         Vitest tests for github.js and comments.js
website/                      SvelteKit marketing site
```

---

## Main modules and important files

- `sources/design-agent-reviews/upstream/bin/agent-reviews.js` — 590-line CLI with `list`, `reply`, `detail`, `watch` commands and full flag parsing
- `sources/design-agent-reviews/upstream/lib/comments.js` — Core module. Defines `DEFAULT_META_FILTERS` (an array of predicate functions filtering known-noisy bots), `KNOWN_BOT_LOGINS` (hardcoded set), `cleanBody()` (HTML comment + Cursor link stripping), `processComments()` (unified normalization of three comment types), `filterComments()`, `replyToComment()`, `resolveThread()` (GraphQL mutation)
- `sources/design-agent-reviews/upstream/lib/github.js` — `getProxyFetch()` (falls through: undici ProxyAgent → curl subprocess → native fetch), `getGitHubToken()` (env → .env.local → `gh auth token`), `getRepoInfo()` (SSH/HTTPS remote URL parsing)
- `sources/design-agent-reviews/upstream/skills/resolve-agent-reviews/SKILL.md` — The most precise skill; Phase 1 (fetch+fix) + Phase 2 (poll watch loop) protocol
- `sources/design-agent-reviews/upstream/skills/resolve-reviews/SKILL.md` — Same protocol extended for human comments
- `sources/design-agent-reviews/upstream/test/comments.test.js` — Vitest tests

---

## Core technical patterns

**Observer/Pub-sub for state (in state.ts analogy)**: Not applicable here — the CLI is stateless across runs by design. Each invocation re-fetches.

**Proxy-aware fetch chain** (lib/github.js lines 48–125): Checks `HTTPS_PROXY`, attempts `undici` ProxyAgent, falls back to a `curl`-based polyfill that writes headers and body to temp files, parses the last HTTP header block, and returns a compatible response object. This is a rare pattern that enables the CLI to work inside corporate proxies and cloud agent environments without native proxy support.

**Meta-comment filter array** (lib/comments.js lines 127–171): An array of `(user, body) => boolean` predicates, each targeting a specific bot's signature. This is extensible — callers can pass additional `metaFilters` to `processComments()`. The pattern cleanly separates noisy infrastructure notifications (Vercel deploy status, Supabase branch status) from actionable code findings.

**Dual REST + GraphQL for resolve** (lib/comments.js lines 429–531): Replies use GitHub REST API v3. Thread resolution (marking a review thread as "resolved") requires GitHub's GraphQL API (`resolveReviewThread` mutation), because the REST API has no endpoint for it. The code paginates through review threads to find the node ID matching a database comment ID, then calls the mutation. This represents genuine non-obvious API usage.

**Watch loop with grace period** (bin/agent-reviews.js lines 230–384): The watch mode exits immediately on new comments rather than looping internally, so the calling agent can restart it after processing. It also waits 5 seconds after first detection to catch same-batch stragglers, then re-fetches.

**Bot detection heuristics** (lib/comments.js lines 178–204): Checks `username.endsWith('[bot]')` OR membership in `KNOWN_BOT_LOGINS` (handles cases where GitHub API omits the `[bot]` suffix).

---

## Novel or interesting mechanisms

1. **The two-phase skill protocol** (fetch-fix-commit-reply / then watch-loop) teaches the AI agent a structured PR review resolution workflow that correctly sequences side effects: fix code before replying, reply with commit hash, then watch for regressions. This sequencing prevents "reply before push" bugs and gives reviewers and bots the actual commit reference.

2. **JSON sidecar output in watch mode**: When new comments are found, the CLI prints both human-readable formatted output and a raw `JSON.stringify(newComments)` block between `--- JSON for processing ---` markers. This dual output is explicitly designed for AI agent parsing — the agent can read the JSON block without parsing color codes.

3. **Grace period on batch detection**: The 5-second grace window is documented as a response to real bots (like CodeRabbit) that post multiple comments in a short burst. Without it, an agent could start processing before all comments in a batch have arrived.

4. **`--resolve` flag semantics**: The skill instructs the agent to use `--resolve` only when closing a conversation (false positive, already-addressed), NOT for genuine fixes awaiting re-review. This respects GitHub's PR review flow where unresolved threads block merge.

---

## Data flow

```
User/agent invokes CLI
  → parseArgs() parses flags
  → getGitHubToken() resolves token (env → .env.local → gh CLI)
  → getRepoInfo() detects owner/repo from git remote
  → getCurrentBranch() + findPRForBranch() → prNumber
  → fetchPRComments(owner, repo, prNumber) → parallel REST calls
      → fetchAllPages() for review_comments, issue_comments, reviews
  → processComments(rawData) → unified sorted array with bot detection + cleanBody
  → filterComments(processed, options) → filtered subset
  → formatOutput() / JSON.stringify() → stdout
```

For reply: same flow, then `replyToComment()` via REST, optionally `resolveThread()` via GraphQL.

---

## Dependencies

- **Runtime**: none (intentional). The `gh` CLI and `GITHUB_TOKEN` are external prerequisites, not npm deps.
- **Optional runtime**: `undici` (only loaded if `HTTPS_PROXY` is set; falls back to curl if not available)
- **DevDependencies**: `vitest ^4.0.18` for testing
- **External system requirements**: Node.js ≥18, git, GitHub token or `gh` CLI auth

---

## Security model

The skill's `allowed-tools` frontmatter restricts the agent to a narrow set of Bash commands:
```
Bash(npx agent-reviews *)
Bash(pnpm dlx agent-reviews *)
Bash(yarn dlx agent-reviews *)
Bash(bunx agent-reviews *)
Bash(git config *)
Bash(git add *)
Bash(git commit *)
Bash(git push *)
```

This prevents the agent from running arbitrary shell commands while resolving reviews. The GitHub token is read from env or `gh auth token`; it is never written to files. The `--resolve` action requires two API calls (GraphQL query + mutation) and only acts on threads the token owner has permission to resolve.

One notable omission: the skill does not scrub `GITHUB_TOKEN` from watch-mode JSON output. The token is never embedded in output, but this is worth verifying in environments where stdout is logged.

---

## Testing strategy

Vitest tests exist for `github.js` (proxy fetch, URL parsing, header parsing) and `comments.js` (meta-comment filter matching, bot detection, body cleaning). The test suite uses a `GITHUB_API_URL` environment variable to point at a local mock server, enabling offline testing without hitting GitHub. No E2E tests for the watch loop or GraphQL resolution exist in the snapshot.

---

## Genuinely reusable elements

1. **Proxy-aware fetch chain** (lib/github.js `getProxyFetch()`): The curl subprocess fallback is valuable for any Node.js tool that must work in corporate or cloud agent environments. The response shape is compatible with the native `fetch` API. License: MIT.

2. **Meta-comment filter pattern** (lib/comments.js `DEFAULT_META_FILTERS`): The predicate-array approach to filtering known-noisy bot comments is directly applicable to any system that aggregates GitHub notifications or PR data. The hardcoded list of known bots is itself useful research data. License: MIT.

3. **Dual REST+GraphQL pattern for GitHub PR threads**: The pattern of using REST for data reads and GraphQL for mutations (specifically thread resolution) is a documented technique but rarely implemented cleanly. This implementation is copy-paste worthy. License: MIT.

4. **Bot detection heuristics** (`isBot()`): Handles the `[bot]` suffix inconsistency across REST/GraphQL/OAuth app login formats. Small but practically important. License: MIT.

---

## What NOT to reuse

- The watch loop exit-on-detection pattern is specific to agent orchestration (restart-loop model). Standard user-facing tools should use a continuous loop with real-time updates instead.
- The hardcoded `KNOWN_BOT_LOGINS` set will become stale as new CI bots emerge. Any adaptation should make this configurable.
- The curl fallback is a last resort; do not use it as the primary HTTP mechanism.

---

## Production-readiness

**MVP-quality.** The CLI is functional and tested for its core use case. The watch loop's "exit on detect, restart manually" model works for agent orchestration but would be unintuitive for direct human use. There is no rate-limit handling for GitHub's REST API (the 5000 req/hr authenticated limit is rarely hit in PR scenarios, but it could be in high-volume repos). No retry logic on network failures. The GraphQL pagination for thread resolution is correct but does not handle GraphQL rate limits. The Vitest test suite is thin — it tests happy paths in parsing/filtering but not the async watch loop or error conditions.

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- Zero runtime dependencies is a genuine differentiator for a CLI tool
- The two-phase skill protocol is well-designed and operationally sound
- Proxy support is unusually thorough (undici → curl → native)
- The bot/human classification and meta-filter system is pragmatically excellent

**Weaknesses**:
- No GitHub API rate-limit handling or retry logic
- Bot list (`KNOWN_BOT_LOGINS`) is statically hardcoded — new bots require code changes
- Watch loop does not self-restart; requires the calling agent to re-invoke
- No support for GitHub Enterprise outside of env var override

**Technical debt**:
- `bin/agent-reviews.js` is 590 lines of mixed arg parsing + command dispatch; could be split into command modules
- The GraphQL thread resolution query paginates but does not cache results between calls in the same session

---

## Novel or differentiated elements

The core novelty is the **agent-first design philosophy**: the tool explicitly outputs JSON alongside human-readable text, documents how agents should restart the watcher, and provides skill files that encode a complete PR review resolution workflow as agentic instructions. Most PR review tools are designed for humans using web UIs; this is designed for agents using CLI tools. The `--bots-only` filter is a clean separation that allows agents to handle automated feedback independently from human feedback.

---

## Possible clean-room adaptations

- A **GitHub notification triage agent** that classifies mentions/assignments/reviews by urgency and routes them to different response workflows, using the same bot/human detection and meta-filter pattern
- A **CI failure resolution agent** that watches GitHub Actions checks the same way agent-reviews watches PR comments, using the watch-loop exit-on-detect model
- A **multi-repository PR review dashboard** for solo developers managing many small projects, using the unified comment format and JSON output
- The proxy-aware fetch chain is extractable as a standalone npm module (e.g., `proxy-aware-fetch`) with genuine utility

---

## Business applications

1. **AI code review assistant SaaS**: Wrap agent-reviews logic in a GitHub App that automatically triages PR comments for teams, routing bot findings to a fix-queue and human comments to a discussion-queue. Target: engineering teams shipping 10+ PRs per week. Commercial rationale: reduces context-switching cost; review comment resolution is a known developer productivity pain point.
2. **CI/CD agent layer**: Embed in a GitHub Actions workflow step that runs an AI agent to self-heal PRs by resolving bot comments without human intervention. Target: devtools platforms (Linear, Vercel, etc.). Commercial rationale: reduces PR cycle time metrics.
3. **PR analytics product**: The unified comment format and bot classification are a clean foundation for measuring bot-to-human comment ratios, false-positive rates per bot, and time-to-resolve. Target: engineering managers. Commercial rationale: ROI justification for code review tooling investment.

---

## Related business ideas in this lab

- Connects to the agent-permission-firewall concept (tool allowlisting in skill files is directly relevant)
- Connects to the code-review-assistant source in this lab
- The structured agent workflow (fetch → evaluate → fix → reply → watch) is a reusable agentic pattern applicable to other code quality domains

---

## Related sources in this lab

- **design-quality-and-review** (pbakaus/impeccable): Same creator, different domain. agent-reviews handles the PR review loop; impeccable handles the design quality loop. Together they form a complete AI-assisted code+design quality workflow.
- **writing-quality** (stop-slop): Similar "detect and fix AI tells" pattern applied to prose rather than PR comments.

---

## Open questions

1. How does the skill behave when a repo has hundreds of open PR review comments? Is there a practical upper bound on API pagination?
2. The `--resolve` flag uses the token owner's identity to resolve threads. In teams, does this cause "resolved by bot" attribution that confuses human reviewers?
3. Is the `KNOWN_BOT_LOGINS` set maintained by the community or solely by pbakaus? What is the update cadence?
4. The watch loop exits on first detection batch. For repos with high-frequency bot activity (e.g., CodeRabbit re-reviews after each push), would the agent be caught in a continuous restart loop?

---

## Final research conclusion

agent-reviews is a well-scoped, production-functional CLI that fills a genuine gap in AI-assisted PR workflows: making GitHub review comments machine-consumable without losing the human-readable context. Its proxy-aware HTTP layer and two-phase skill protocol are technically careful; its zero-dependency stance makes it easy to embed. The primary limitation is that it is a single-developer maintenance surface with a statically hardcoded bot list, which will require ongoing curation as the CI bot ecosystem evolves.

---
