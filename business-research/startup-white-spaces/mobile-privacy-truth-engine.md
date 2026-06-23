# White Space: Mobile Privacy Truth Engine

status: research
research_date: 2026-06-23
evidence_status: needs-user-input

---

## Problem

Mobile app privacy policies are written in legal language and are rarely accurate
representations of what an app actually does at runtime. A user or enterprise IT
administrator has no practical way to verify:
- Whether an app's network traffic matches its stated data collection claims
- Which third-party SDKs are embedded (analytics, ad networks, crash reporters)
- What permissions are requested vs. what permissions are actually used
- Whether data leaves the device in encrypted form

There are academic tools and security researcher tools (frida, objection, mitmproxy),
but no accessible, non-expert product that gives a plain-language "privacy truth report"
for a specific app version.

---

## Target customer

- status: needs-user-input (who is the primary buyer?)
- Possible: enterprise IT / MDM (mobile device management) teams auditing apps before
  allowing them on employee devices
- Possible: privacy-conscious individuals (lower willingness to pay)
- Possible: app stores or regulatory auditors as B2G

---

## Proposed product

status: needs-user-input

Possible direction: A static + dynamic analysis tool that generates a structured privacy
report for a mobile app binary (.ipa / .apk). Output: list of embedded SDKs, network
destinations reached during instrumented testing, permissions used vs. declared,
data categories observed.

---

## Founder fit

- CS background: static analysis and network monitoring are engineering problems
- Privacy / data protection knowledge from regulatory research context
- No hardware required (cloud analysis pipeline)
- B2B or B2G model

---

## Evidence status

evidence_level: none
No research has been conducted beyond naming this idea.

---

## Unknowns

- Is this a real enterprise pain point or a personal/consumer concern?
- Who currently does app privacy audits for enterprises, and at what cost?
- Are app stores (Apple, Google) building this natively?
- What is the technical feasibility of dynamic analysis at scale in a cloud environment?
- What regulatory frameworks require or incentivize this?

---

## Lab sources potentially relevant

- `sources/privacy-safe-commit-assistant/` (kaycebasques/git-privacy, MIT) — tangentially
  relevant: demonstrates privacy-aware tooling patterns, not mobile-specific

---

## Next research question

1. Talk to 3 enterprise IT administrators: how do they currently vet mobile apps?
2. Check what MobileIron, Jamf, or similar MDM vendors offer for privacy auditing
3. Research whether GDPR or AI Act impose obligations on mobile app data collection
   that would create compliance demand for this tool
