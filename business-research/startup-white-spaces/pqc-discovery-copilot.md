# White Space: PQC Discovery Copilot

status: research
research_date: 2026-06-23
evidence_status: needs-user-input

---

## Problem

NIST finalized the first post-quantum cryptography (PQC) standards in 2024
(ML-KEM, ML-DSA, SLH-DSA). Organizations are now expected to begin transitioning
from classical cryptography (RSA, ECDH, ECDSA) to quantum-resistant algorithms.

The migration problem is not just "update your library." It requires:
- Discovering every place in a codebase where classical crypto is used
- Understanding what data or key exchange those usages protect
- Prioritizing which usages are "harvest-now-decrypt-later" risks
- Mapping dependencies (TLS stacks, third-party SDKs, HSMs, cloud KMS providers)
  to their PQC readiness status

There is no tool that combines code-level discovery with dependency-level PQC
readiness mapping and produces an actionable migration plan.

---

## Target customer

- status: needs-user-input (which sector first?)
- Probable early adopters: financial services, healthcare, government contractors
  — all have long-lived sensitive data and explicit regulatory pressure to act
- Possible: any software team using encryption that has received a compliance
  questionnaire about quantum readiness

---

## Proposed product

status: needs-user-input

Possible direction: A static analysis tool that scans a codebase and its dependencies
for classical cryptographic usage, classifies by risk category, maps each finding to
the PQC-ready alternative, and checks whether the library/provider used supports
the migration path.

Output: a prioritized migration plan with effort estimates, not just a vulnerability
list.

---

## Founder fit

- CS background: static analysis, cryptography fundamentals
- No hardware required
- B2B model with clear compliance driver
- Technically demanding — differentiates from non-technical founders

---

## Evidence status

evidence_level: none
No research conducted beyond naming this idea.

---

## Unknowns

- How many organizations are actively working on PQC migration today vs. "later"?
- Is this a tooling problem or a knowledge problem? (Do engineers know what to do
  but lack tooling, or do they not know what to do?)
- Do SAST vendors (Semgrep, Snyk, Veracode) already offer crypto-inventory scanning?
- What are the actual regulatory deadlines that would create urgency?
- Is the hard part discovery or the organizational change management of migration?

---

## Lab sources relevant

None directly. No cryptography tooling sources have been imported.

---

## Next research question

1. Research: what have NIST, NSA, and CISA published about migration timelines
   and guidance for private sector organizations?
2. Check: do Semgrep, Snyk, or Veracode have PQC-readiness scanning rules?
3. Find 2–3 security engineers at financial or healthcare companies: are they
   working on crypto inventory today, and what tools are they using?
