# White Space: Vibe Security Gate

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

AI-assisted development has crossed a threshold: 41% of commits are now AI-generated or
AI-assisted (Cursor, Claude Code, GitHub Copilot). But existing SAST tools (Snyk, SonarQube,
Semgrep) were built for human-written code patterns and do not detect the specific failure modes
of AI-generated code.

**Documented scale of the problem (verified sources, 2026-06-23):**
- **53% of AI-generated code ships with at least one security vulnerability**
  (AI Vyuh CodeQA analysis, June 2026)
- **35 new CVEs in March 2026** directly attributable to AI-generated code (up from 6 in January)
- AI-assisted developers produce commits **3–4x faster** but introduce security findings at **10x the rate**
- **CVE-2025-53773**: hidden prompt injection in a PR description enabled RCE via GitHub Copilot (CVSS 9.6)
- **Claude Code CVE-2026-21852**: Claude Code GitHub Action flaw (reported June 2026)
- 63% of developers report spending more time debugging AI-generated code than writing manually

Sources: sqmagazine.co.uk (AI coding security statistics), Cloud Security Alliance (vibe coding security debt research note), codeqa.aivyuh.com, thehackernews.com/2026/06/claude-code-github-action-flaw, verified 2026-06-23.

---

## The gap in existing tools

Current SAST tools have a taxonomy problem: they look for known vulnerability patterns in code
that humans write deliberately. AI-generated code introduces a different failure mode set:

1. **Credential hardcoding patterns** — AI models generate test credentials and forget to remove them; the entropy profile is different from hand-coded credentials
2. **Prompt-injectable comment blocks** — AI-generated code often includes explanatory comments that can be weaponized as prompt injection surfaces in systems that process code as input
3. **Logic errors at boundary conditions** — AI confidently generates plausible-but-wrong validation code that passes obvious tests but fails edge cases (e.g., off-by-one in auth checks)
4. **Over-permissive defaults** — AI defaults to permissive configurations (CORS wildcard, no rate limiting, debug mode left on)
5. **Vibe-copied vulnerable patterns** — AI trained on old code reproduces patterns from deprecated or vulnerable library versions

**No existing SAST tool has a taxonomy for these AI-specific patterns.** Snyk and SonarQube flag issues they are trained to detect; they are not trained on the failure modes of model-assisted generation.

---

## Target customer

**Primary buyer:** Security engineers and DevSecOps teams at companies where AI coding tools are deployed.
**Secondary buyer:** Engineering leads at companies using Cursor/Claude Code/Copilot at scale (50+ engineers).
**Enterprise buyer:** CISO at companies with compliance requirements (EU AI Act, SOC 2, ISO 27001).

Any company where AI coding tools are deployed to developers writing security-sensitive code is in scope.

---

## Proposed product

A CI/CD security gate that scans specifically for AI-code vulnerability patterns:

1. **AI-code detection**: Identifies which files/hunks are AI-generated (author metadata, generation comments, model signature patterns)
2. **AI-specific vulnerability scan**: Runs a specialized ruleset targeting AI generation failure modes (credential hardcoding entropy patterns, prompt-injectable comments, over-permissive defaults, deprecated pattern reuse)
3. **PR-level gate**: Blocks or flags PRs that contain AI-generated code with unresolved findings
4. **One-click remediation**: Generates specific fix suggestions for each finding type (not just "fix this" but the exact corrected code)
5. **CVE tracking**: Monitors the "AI-generated CVE" taxonomy maintained by Georgia Tech's Vibe Security Radar project

Integration: GitHub App + GitHub Actions; GitLab CI support; VS Code extension for pre-commit.

---

## Why now

- **53% vulnerability rate in AI-generated code** is a documented, public number as of June 2026
- TechCrunch, Cloud Security Alliance, Georgia Tech all covering this in 2025–2026
- The "Vibe Security Radar" project (Georgia Tech, started May 2025) is building the CVE taxonomy that can power the ruleset — no commercial product has productized it yet
- EU AI Act Article 12 audit requirements (August 2026) and revised PLD (December 2026) both create liability exposure for AI-generated defects — compliance forcing function
- Every team using Cursor/Claude Code/Copilot in 2026 is deploying AI-generated code without a security gate specialized for it

---

## Competitor landscape

Source: GitHub, product websites, Cloud Security Alliance, verified 2026-06-23.

| Competitor | Approach | AI-specific rules | Gap |
|---|---|---|---|
| Snyk | Dependency and SAST scanning | No | Not trained on AI generation failure modes |
| SonarQube/SonarCloud | Code quality + security rules | No | General SAST; no AI-specific pattern detection |
| Semgrep | Rule-based SAST | Some community rules | Community rules for LLM outputs exist but are not a product |
| Cycode | Application security posture management | No | No AI-generated code specialization |
| Checkmarx | Enterprise SAST | No | Enterprise only; no AI-specific patterns |
| GitAutoReview | AI PR review | Narrow | Code review bot, not security scanner |
| VibeGuard | arxiv paper (May 2025) | Research only | Not a commercial product; research taxonomy |

**Gap confirmed:** No commercial SAST product as of 2026-06-23 has a ruleset specialized for AI-generated code failure modes. Semgrep has community-contributed rules for some LLM output patterns but this is not a maintained product feature. The academic taxonomy exists (Georgia Tech); the commercial product does not.

---

## Founder fit

- CS background: rule-based SAST and pattern detection is a well-defined engineering problem
- Experience using AI coding tools directly — knows the failure modes firsthand
- EU-based: EU AI Act Article 12 (audit logging of AI system decisions) and revised PLD create EU-specific compliance angle
- No hardware required; pure software
- B2B; natural DevSecOps buyer

---

## Revenue model

- GitHub App: freemium for public repos; $15–40/user/month for private repos (benchmarked against Snyk, CodeRabbit pricing)
- Enterprise: $50–100/user/month with SOC 2 audit trail and SIEM integration

---

## Technical approach

1. Build initial ruleset targeting the 5 AI-specific failure mode categories
2. Integrate with GitHub Actions — scan diff on PR open/update
3. Track VibeGuard/Georgia Tech CVE taxonomy as the vulnerability research input
4. For AI-code detection: use author metadata + common AI comment patterns as heuristics (not perfect, but sufficient for flagging)

---

## Risks

- Snyk/SonarQube/Semgrep adding AI-specific rules would close the gap quickly (these vendors have large rule engineering teams)
- Distinguishing AI-generated from human-written code is imperfect — false positive risk
- The AI-code vulnerability rate may decrease as model quality improves
- Market may conflate this with general AI code review tools (CodeRabbit, Qodo)

---

## Next validation step

1. Run Semgrep + Snyk on a sample of 50 AI-generated functions and measure what % of AI-specific vulnerabilities they catch (baseline)
2. Interview 3 DevSecOps engineers: are they treating AI-generated code differently from human-generated code in their security scanning?
3. Check if Georgia Tech's Vibe Security Radar project has an accessible CVE taxonomy that can seed the ruleset

---

## Lab sources relevant

- `sources/deterministic-agent-safety/` — fail-closed hook engine pattern; the "block before execution" principle applies to CI security gates as well
- `research/REUSABLE_PATTERNS_CATALOG.md` — Pattern 8 (scoring rubric with threshold) applicable to vulnerability severity scoring

---

## Related ideas

- `agent-permission-firewall.md` — adjacent: both address risks introduced by AI agent actions; different layer (CI vs. runtime)
- `business-research/startup-white-spaces/accessibility-source-fix-ci.md` — parallel CI gate concept; different domain
