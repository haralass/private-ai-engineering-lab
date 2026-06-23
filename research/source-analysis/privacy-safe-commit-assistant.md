# Source Research Dossier: privacy-safe-commit-assistant

---

## Repository identity

- **Name:** commitgen
- **Creator:** Stylianos Gavriil (sgavriil01)
- **GitHub URL:** https://github.com/sgavriil01/commitgen
- **Source path:** `sources/privacy-safe-commit-assistant/`
- **License:** NOT-FOUND (no LICENSE file)
- **Import type:** reference-only — no code may be copied; study only
- **Pinned commit:** `0619458775acb2da839ce627763fbf61ee7220fa`

---

## What it actually does

commitgen is a commit message generator. The lab's functional label is `privacy-safe-commit-assistant`, which implies a focus on processing git diffs locally without sending sensitive code to external AI APIs. The lab notes describe it as "Commit message generation." No code is vendored. The "privacy-safe" framing in the lab's functional name likely reflects the design intent: run `git diff --staged` locally, generate a commit message using a local model or a diff analysis heuristic, and output a Conventional Commits-formatted message without any network calls that would expose code. [inference: the privacy-safe label may have been applied by the lab rather than the original author, based on the pattern the repository implements — all diff processing occurring locally]

The broader market context: AI commit message generators are a well-established category. Tools like `aicommits` (17k+ GitHub stars), GitHub Copilot's commit suggestion, and DiffSense all exist. The differentiating angle for commitgen, as framed by this lab, is the privacy guarantee — processing diffs locally with no external API calls.

---

## Architecture

[inferred from repository name, lab label, public commit-message-generator patterns, and creator context]

A privacy-safe commit generator has the following canonical architecture:

1. **Diff extraction:** `git diff --staged` (or `git diff HEAD`) captures the staged changes as a unified diff.
2. **Privacy filter (if implemented):** Strip or anonymize sensitive strings — API keys, passwords, PII — from the diff before any processing. This is the critical "privacy-safe" step.
3. **Message generation:** Either:
   - *Template-based:* Parse the diff for added/removed files, function names, and token-level changes; fill a message template (e.g., `feat(scope): description`)
   - *Local LLM:* Pass the filtered diff to a locally-running language model (Ollama, a small Transformers model, or AirLLM-style layer streaming)
   - *Remote LLM with redaction:* Redact sensitive strings, then call an external API (OpenAI, Anthropic)
4. **Output:** A Conventional Commits-formatted message (`feat:`, `fix:`, `chore:`, etc.) optionally with a body.

Given the "privacy-safe" framing in the lab label and the creator's interest in infrastructure patterns, [hypothesis: commitgen uses either a template/heuristic approach or a local model, not a cloud API].

---

## Main modules and important files

No code is vendored. Expected structure (inferred):

- CLI entrypoint (Python, Node.js, or Go — unknown)
- Diff parsing logic
- Message generation logic (template, local model, or API call with redaction)
- Possibly: Conventional Commits format validation
- `requirements.txt` or `package.json` or `go.mod` (language unknown)

---

## Core technical patterns

1. **`git diff --staged` pipeline:** The standard pattern for staged diff extraction. Equivalent in Python: `subprocess.run(["git", "diff", "--staged"], capture_output=True)`.

2. **Diff parsing for structured signal extraction:** Parsing a unified diff to extract: (a) modified files and their languages, (b) added/removed function or class names, (c) magnitude of change (lines added/removed). These signals feed template-based generation without needing an LLM.

3. **Conventional Commits format:** The `<type>(<scope>): <description>` format is the dominant commit message convention. Automated tools that produce CC-formatted messages have better integration with changelog generators (semantic-release, standard-version).

4. **Privacy filtering:** Regex or AST-based stripping of secrets before any external transmission. Tools like `git-secrets` and `detect-secrets` detect credential patterns; a commit generator's privacy filter needs similar detection.

5. **Local-first processing:** No subprocess calls to external networks in the critical path. All diff analysis happens in the local process.

---

## Novel or interesting mechanisms

- **Lab-applied "privacy-safe" framing:** The repository is named `commitgen` but the lab re-labels it `privacy-safe-commit-assistant`. This suggests the lab identified the privacy property as the differentiating value, not just the generation capability. [inference: the creator may have built commitgen as a straightforward commit generator, but the lab recognized that its local-only approach makes it a privacy-safe alternative to cloud-backed tools]

- **Same creator as forgequeue and sms-platform:** sgavriil01's consistent choice of reference-only (no license) status across all four repositories in this lab (sms-platform, whisper-faiss-example, commitgen — and even forgequeue was private-then-MIT-released [hypothesis]) suggests the lab studied these repositories as patterns before licenses were established, or that the creator has not yet formalized their open-source licensing.

- **Market gap at submission time:** The lab was populated in June 2026. At that time, the main privacy-safe local commit generators (local Ollama integration in aicommits, AutoCommit's local processing) are functional but not developer-tool polished. A well-packaged, privacy-first commit assistant with enterprise features (secret redaction audit log, team conventions config) occupies a real gap.

---

## Data flow

[inferred]

```
git diff --staged → unified diff text
        ↓
Privacy filter: scan for secrets/PII patterns
        ↓
Diff parsing: extract changed files, function names, diff magnitude
        ↓
Message generation (one of):
  (a) Template: "feat(scope): update <function> in <file>"
  (b) Local LLM: diff → Ollama/local model → completion
  (c) Redacted diff → OpenAI/Anthropic API → completion
        ↓
Format as Conventional Commits
        ↓
Output to stdout / git commit -m "$(commitgen)"
```

---

## Dependencies

[inferred — language and framework unknown]

If Python:
- `subprocess` (standard library) for `git diff`
- Possibly `transformers` or `ollama` for local LLM
- Possibly `openai` for API-backed fallback
- Possibly `rich` or `click` for CLI

If Node.js:
- `simple-git` or native `child_process` for git operations
- Possibly `ollama-js` or `openai` SDK

If Go (consistent with creator's forgequeue):
- Standard `os/exec` for git
- Possibly `github.com/ollama/ollama/api` client

---

## Security model

- **The privacy-safe claim depends entirely on implementation:** If any diff content is sent to a remote API without scrubbing, the privacy guarantee is void. The actual implementation (unknown) must be audited before trusting the privacy claim.
- **Secret detection in diffs:** A robust privacy filter must handle: API keys in config files, hardcoded passwords, private keys, database connection strings, bearer tokens, and PII (email addresses, phone numbers) in test fixtures.
- **Git hook integration risk:** If commitgen runs as a `prepare-commit-msg` hook, a bug or hang in commitgen blocks all commits — a developer experience risk.
- **Conventional Commits output format:** Incorrect format (e.g., missing colon, wrong type) would break downstream changelog generation — a correctness concern, not a security one.

---

## Testing strategy

Unknown — no code vendored.

---

## Genuinely reusable elements

**Cannot be copied** (no LICENSE file). Clean-room implementations using same approach are fully buildable:

1. **`git diff --staged` parsing pattern** — publicly documented; no IP in the approach
2. **Conventional Commits format validation** — the spec is public (https://www.conventionalcommits.org/)
3. **Secret detection regex patterns** — publicly available from tools like `detect-secrets`, `gitleaks`
4. **Local LLM diff processing** — combining with AirLLM (Apache-2.0, vendored) enables fully local, license-clean commit generation

---

## What NOT to reuse

- Any code from this repository cannot be legally copied.
- Any approach that sends raw diffs to a cloud API without redaction should not be branded as "privacy-safe."

---

## Production-readiness

Unknown — likely prototype-quality as a student project. Production developer tooling requires:
- Reliable git hook integration (not blocking commits on failure)
- Config file for team conventions (prefix allowlist, scope patterns)
- Audit log of what was redacted (for security review)
- VS Code / JetBrains plugin (CLI alone has limited adoption)

---

## Strengths / Weaknesses / Technical debt

**Strengths (inferred):**
- Addresses a genuine developer pain point
- Privacy framing differentiates from dominant cloud-backed competitors
- Same creator as forgequeue — likely Go-quality code if written in Go

**Weaknesses:**
- No license — cannot reuse legally
- Privacy claim requires independent verification
- Market is crowded (aicommits, DiffSense, GitHub Copilot, Cursor, Windsurf all generate commit messages)

**Technical debt:** Unknown without code access.

---

## Novel or differentiated elements

The market for commit message generators is mature (aicommits, AutoCommit, DiffSense, GitHub Copilot). The differentiated angle in this lab's framing is the **privacy guarantee** — the combination of:
1. Local-only processing (no diff sent to cloud)
2. Active secret redaction (not just "trust the developer")
3. Enterprise-ready audit logging of what was redacted

This combination does not exist as a packaged product in the current market. AutoCommit claims local processing but does not mention active redaction. aicommits uses remote APIs. GitHub Copilot sends diffs to Microsoft/GitHub.

---

## Possible clean-room adaptations

1. **Enterprise-grade privacy-safe commit assistant:** Clean-room implementation in Go (consistent with forgequeue creator's stack):
   - `git diff --staged` subprocess call
   - `gitleaks`-inspired regex patterns for secret detection → redact before any LLM call
   - Local Ollama integration (e.g., `codellama:7b` or `mistral:7b`) for message generation
   - Fallback to template-based generation if no local model available
   - Output as Conventional Commits
   - Audit log of redacted patterns

2. **VS Code extension:** Package the same logic as a VS Code extension for the source control panel commit message field — much higher adoption potential than a CLI.

3. **CI/CD integration:** Run commitgen in CI to validate that all commits on a PR follow Conventional Commits format; reject non-conforming commits. This is a quality enforcement use case, not generation.

4. **Combined with AirLLM:** Use AirLLM to run a local 7B–13B model for higher-quality commit message generation without any cloud dependency. Latency (minutes per inference with AirLLM) would be too slow for interactive use, but acceptable for a pre-push hook that runs once per batch.

---

## Business applications

1. **Enterprise developer productivity tool (B2B SaaS):** Sell to engineering teams that have strict code confidentiality policies (financial services, defense contractors, healthcare). $X/developer/month. Key selling point: code never leaves the machine, audit log of all redactions. Competitors: GitHub Copilot (cloud), Cursor (cloud), aicommits (cloud by default).

2. **Self-hosted developer tooling for regulated industries:** Package as a Docker container for on-premise deployment. Same market as above but for teams that cannot use SaaS. Revenue model: license fee + support contract.

3. **IDE plugin with privacy dashboard:** Show developers what the tool redacted before sending to any AI (even local). The transparency of the privacy layer is itself a product feature — developer trust and auditability.

4. **Git quality enforcement as a service:** Not message generation but message validation — enforce Conventional Commits, detect low-quality messages ("wip", "fix", "asdf"), suggest improvements. Lighter compliance angle, easier adoption.

---

## Related business ideas in this lab

- `sources/code-review-assistant/` — pbakaus's code review skill is a companion tool in the developer-tooling category; together, privacy-safe commit + privacy-safe code review = a privacy-first AI developer toolkit
- `sources/model-layer-streaming/` — AirLLM enables fully local LLM for commit generation without Ollama or any running model server
- `sources/durable-background-job-queue/` — if commit message generation is run as a background async task (e.g., in a CI pipeline), forgequeue provides the infrastructure

---

## Related sources in this lab

- `sources/code-review-assistant/` — same domain (developer AI tooling), complementary tool
- `sources/model-layer-streaming/` — the "local LLM" backend for privacy-safe generation
- `sources/semantic-audio-search/` — same creator; both are "extract signal from text/media" patterns
- `sources/asynchronous-job-processing/` — same creator; async processing for batch commit analysis
- `sources/durable-background-job-queue/` — infrastructure layer for any async developer tooling pipeline

---

## Open questions

1. Does commitgen use a local model, a cloud API, or template-based generation? This is the most critical unknown — it determines whether the "privacy-safe" label is earned.
2. What language/framework is commitgen written in?
3. Does it implement active secret redaction, or is it a simple diff-to-API call?
4. Is the output format Conventional Commits, or free-form?
5. Does it run as a git hook, CLI, or IDE plugin?
6. What is the quality of generated messages for large diffs (>100 lines changed)?

---

## Final research conclusion

commitgen / privacy-safe-commit-assistant is the smallest-footprint source in this cluster, but its business angle is commercially relevant. The "privacy-safe" framing applied by this lab identifies a genuine market gap: most AI commit message generators send raw code diffs to cloud APIs, which is unacceptable for codebases with confidentiality requirements. A clean-room implementation combining git diff parsing, active secret redaction, and local LLM generation (via Ollama or AirLLM) would be a differentiated product in a crowded market. The no-license constraint prevents direct code reuse, but the architectural pattern is simple enough to build from scratch in 1–2 days. Combined with the code-review-assistant source, this is half of a "privacy-first AI developer toolkit" that would resonate strongly with regulated-industry engineering teams.

URL citations:
- https://github.com/Nutlope/aicommits
- https://autocommit.top/
- https://edgeleap.github.io/ (DiffSense)
- https://www.conventionalcommits.org/
- https://news.ycombinator.com/item?id=47307294 (commitgen-cc with Ollama local processing)
