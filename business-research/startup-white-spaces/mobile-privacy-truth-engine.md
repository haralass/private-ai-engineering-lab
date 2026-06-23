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

## Regulatory context

Source: GDPR (Regulation (EU) 2016/679), EDPB guidelines, Apple/Google developer docs, verified 2026-06-23. Full citations in `research/domain-synthesis/regulatory-landscape.md`.

**GDPR obligations relevant to mobile apps:**
- **Article 5(c) data minimisation** — data must be "adequate, relevant and limited to what is necessary"
- **Article 5(f) integrity and confidentiality** — appropriate security measures required
- **Article 25 data protection by design and by default** — controllers must implement data minimisation by design
- **EDPB Guidelines 3/2022 on dark patterns** — six categories of prohibited design patterns under Article 5(1)(a) (overloading, skipping, stirring, obstructing, faking, hindering)
- **EDPB DeepSeek precedent:** Italy banned DeepSeek and EDPB formed a task force for data transfer issues. This enforcement signal applies equally to any app collecting EU personal data and routing it to non-adequate countries.

**Apple App Store (mandatory, verified from Apple developer docs):**
- Privacy Nutrition Labels: required for all apps — 15 data categories must be disclosed
- Privacy Manifests: required from 1 May 2024 for new/updated apps using certain SDKs
- "Collect" = transmitting data off-device for longer than real-time request servicing

**Google Play (mandatory, verified from Google developer docs):**
- Data Safety Form: required for all apps — 14 data categories must be disclosed
- Inaccurate declarations → blocked app updates or app removal

**Key regulatory implication:** App stores now enforce privacy disclosure at the platform level. This is more immediately credible enforcement than DPA action for many mobile developers. The gap this product addresses: verifying that what developers disclose in privacy nutrition labels / data safety forms accurately reflects what the app actually does.

---

## Evidence status

evidence_level: initial-research

Updated 2026-06-23. GDPR mobile requirements and Apple/Google platform requirements verified from primary sources. Competitor landscape researched.

---

## Competitor landscape

Source: pts-project.org, corellium.com, verified 2026-06-23. Full details in `research/domain-synthesis/regulatory-landscape.md`.

| Tool | Type | Target buyer | Gap |
|---|---|---|---|
| PiRogue Tool Suite | Open-source, NGO | Privacy researchers, NGOs | Not a SaaS product; requires security expertise |
| Corellium | Enterprise cloud mobile testing | Security teams, compliance | Enterprise pricing; requires security expertise |
| SentinelOne Singularity Mobile | Enterprise MDM security | Enterprise IT security | Ongoing monitoring, not privacy audit reports |
| Jamf Pro | MDM (Apple-focused) | Enterprise IT | Device management, not privacy auditing |
| Didomi | Consent management SDK | App developers | Consent UI only — does not audit existing apps |

**Gap confirmed:** No verified self-serve product delivers a plain-language "privacy truth report" (static + dynamic analysis combined) for an arbitrary app binary that is accessible to enterprise IT teams without security researcher expertise. PiRogue is the closest but is an NGO research tool, not a SaaS product.

---

## Unknowns

- Is the primary buyer enterprise IT (MDM team vetting business apps) or developers (verifying their own apps comply)?
- What is the technical feasibility of cloud-hosted dynamic analysis for iOS? (Apple's sandboxing makes this harder than Android)
- Are Apple and Google building more automated disclosure verification natively?
- Is there a B2G opportunity with Data Protection Authorities or app store regulators?

---

## Lab sources potentially relevant

- `sources/privacy-safe-commit-assistant/` — privacy-aware developer tooling pattern (not mobile-specific)

---

## Next research question

1. Talk to 3 enterprise IT/MDM administrators: what is their current workflow for vetting apps before approving for employee devices?
2. Investigate PiRogue Tool Suite in depth: could it be the technical foundation for a commercial product?
3. Clarify: is the harder problem static analysis (finding SDK usage) or dynamic analysis (verifying network traffic)?
