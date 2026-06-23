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

## Regulatory context

Source: NIST CSRC, verified 2026-06-23. Full citations in `research/domain-synthesis/regulatory-landscape.md`.

**NIST PQC finalized standards (published 13 August 2024):**
- **FIPS 203** — ML-KEM (Module-Lattice Key Encapsulation) — replaces RSA/ECDH key exchange
- **FIPS 204** — ML-DSA (Module-Lattice Digital Signature) — replaces RSA and ECDSA signatures
- **FIPS 205** — SLH-DSA (Stateless Hash-Based Digital Signature) — conservative alternative

**NIST IR 8547 migration timeline (Initial Public Draft, November 2024):**
- RSA-2048, ECDH-P224 (112-bit): deprecated after 2030, disallowed after 2035
- RSA-3072, ECDH-P256, AES-128 (128-bit+): disallowed after 2035
- NIST recommendation (August 2024): "start integrating them into their systems immediately, because full integration will take time"

**NSA CNSA 2.0:** 2030 mandatory migration deadline for National Security Systems (US government and cleared contractors).

**No EU-level private-sector mandate found:** NIS2 Article 21(2)(h) requires appropriate cryptography measures but has not been enforced as a PQC-specific obligation as of 2026-06-23. [inference: this may change as ENISA updates guidance]

**Migration size signal:** ML-DSA signatures are 2,420–4,595 bytes vs RSA-2048 at ~256 bytes. SLH-DSA is 7,856–49,856 bytes. These size increases mean PQC migration has measurable performance and storage implications — not a pure config change.

---

## Evidence status

evidence_level: initial-research

Updated 2026-06-23. NIST PQC standards and migration timeline verified from NIST CSRC primary sources. Competitor landscape researched.

---

## Competitor landscape

Source: helpnetsecurity.com, ibm.com/docs, qramm.org, verified 2026-06-23.

| Tool | Type | Scope | Gap |
|---|---|---|---|
| pqcscan (Anvil Secure) | Open-source, Rust | SSH/TLS server PQC support detection | Scans servers, not source code |
| IBM Quantum Safe Explorer | Enterprise source code scanner | Code-level crypto discovery | Enterprise pricing; no dependency mapping |
| QRAMM tools (CryptoScan, TLS Analyzer, CryptoDeps) | Open-source collection | Multiple scopes | No combined tool, no migration plan output |
| PQScan (pqscan.io) | SaaS | Unclear scope | Limited public information |
| Semgrep, Snyk, Veracode | General SAST | General security scanning | No verified PQC-specific rules as of 2026-06-23 |

**Gap confirmed:** pqcscan scans servers for declared PQC support. IBM Quantum Safe Explorer scans source code but is enterprise-priced with no dependency mapping. No tool produces a combined code inventory + dependency PQC readiness map + prioritized migration plan. This is the specific gap this product concept addresses.

---

## Unknowns

- How many security teams are actively working on PQC migration vs. deferring until 2028–2030?
- Is the bottleneck awareness, tooling, or organizational prioritization?
- Will IBM Quantum Safe Explorer address the SME market?
- EU: will ENISA or Member State supervisors issue PQC-specific NIS2 guidance?

---

## Lab sources relevant

None directly. No cryptography tooling sources have been imported.

---

## Next research question

1. Find 2–3 security engineers at financial services companies: are they doing crypto inventory now? What is blocking them?
2. Test IBM Quantum Safe Explorer pricing — is there a free tier or trial?
3. Check if NIS2 national implementing laws in Germany, France, and Netherlands reference PQC obligations specifically
