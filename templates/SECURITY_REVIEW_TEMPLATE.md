# Security Review — [Component/Source Name]

Date: YYYY-MM-DD
Reviewer: (owner)
Status: pending | in-progress | approved | rejected

---

## Scope

What was reviewed:

---

## Checklist

### Secrets and credentials
- [ ] No API keys in source
- [ ] No passwords or tokens
- [ ] No private keys or certificates
- [ ] No real `.env` files

### Execution safety
- [ ] No automatic execution of remote scripts
- [ ] No `curl | bash` or equivalent patterns
- [ ] No auto-install or auto-publish on import

### Hooks and scripts
- [ ] All hooks reviewed line by line
- [ ] No hooks execute without parameters
- [ ] No hooks modify protected paths
- [ ] No hooks bypass git protections

### Prompt injection
- [ ] No CLAUDE.md or `.claude/` that could hijack agent
- [ ] No files designed to look like system instructions
- [ ] No instruction injection in comments or docstrings

### Dependencies
- [ ] Dependencies listed and reviewed
- [ ] No known-vulnerable dependencies
- [ ] No unusual network dependencies

### Permissions
- [ ] No `chmod 777` or world-writable operations
- [ ] No `sudo` without clear justification

---

## Findings

| Severity | File | Description | Resolution |
|---|---|---|---|
| | | | |

---

## Decision

- [ ] Approved — safe to use as-is
- [ ] Approved with conditions — see notes
- [ ] Rejected — do not use

## Notes
