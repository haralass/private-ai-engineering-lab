# Security Model

## Threat model

This repository stores code that will be read and executed by AI agents. The threat model considers:

1. **Prompt injection via imported source** — a source file could contain instructions that manipulate the AI
2. **Secret leakage** — a source might accidentally contain API keys or credentials
3. **Malicious hooks** — a skill or hook could execute dangerous commands
4. **Supply chain** — a dependency in an imported source could be compromised
5. **Scope creep** — an AI component could take actions beyond its intended scope

## Mitigations

### Secret scanning
All imports run `scripts/security/secret_scan.py` before commit. CI runs the same scan on every push. A failed scan blocks merge.

### Prompt injection review
`AUDIT.md` in each source includes a prompt-injection review section. Any file with AI-visible instructions is flagged for manual review.

### Hook sandboxing
All hooks from external sources are placed in `sources/<name>/upstream/` and reviewed in `AUDIT.md` before being considered for inclusion in `components/`.

### Report-only default
All agent components default to report-only mode. They log and warn but do not automatically modify, commit, push, or deploy.

### Protected paths
The `components/agent-safety-firewall/config/protected-paths.yaml` defines paths that agents may not modify without explicit human confirmation.

### Dangerous command blocking
The `components/agent-safety-firewall/config/dangerous-commands.yaml` defines shell command patterns that must be blocked or require confirmation.

## Review before production use

Any component with `security_review_status: pending` in its `SOURCE.yaml` must complete a security audit before use in production. See `templates/SECURITY_REVIEW_TEMPLATE.md` for the audit format.
