# White Space: Accessibility Source-Fix CI

status: research
research_date: 2026-06-23
evidence_status: needs-user-input

---

## Problem

Accessibility violations (WCAG 2.1/2.2) in web and mobile products are systematically
discovered at the wrong moment: after release, often via customer complaint or audit.

Existing CI tools (axe-core, Lighthouse) detect violations but do not fix them.
A developer seeing "color-contrast: fail" in a CI log still has to manually identify the
component, understand the fix, and apply it — there is no automated remediation path.

The gap: a CI step that not only reports accessibility violations but generates a concrete
code fix (a pull request or inline patch) for each category of violation it can handle
reliably.

---

## Target customer

- status: needs-user-input (which team type? B2C web product teams? enterprise software?)
- Possible: product engineering teams at companies with legal accessibility obligations
  (US federal contractors: Section 508; EU: EAA European Accessibility Act)
- Possible: design system teams responsible for shared component libraries

---

## Proposed product

status: needs-user-input

Possible direction: A CI bot that runs on pull requests, identifies accessibility
violations by category, applies deterministic fixes for categories with low false-positive
rates (color contrast, missing alt text, button labeling), and opens a sub-PR or adds
inline comments with the fix. The human reviews and approves; the bot does not
auto-merge.

Scope constraints: only categories where the fix is deterministic and safe. Do not
attempt to auto-fix complex semantic structure violations.

---

## Founder fit

- CS background: parsing, AST manipulation, CI integration — core engineering skills
- Product knowledge: accessibility matters for any web product
- No hardware required

---

## Regulatory context

Source: primary EU regulatory sources, verified 2026-06-23. Full citations in `research/domain-synthesis/regulatory-landscape.md`.

**European Accessibility Act (Directive (EU) 2019/882):**
- Enforcement date: **28 June 2025** — already in effect for newly placed products and services
- **In-scope digital services:** e-commerce websites/apps, electronic communications, banking consumer interfaces, audio-visual media services, transport ticketing/information
- **Microenterprise exemption for services:** companies with fewer than 10 employees and annual turnover/balance sheet ≤ €2M providing services are exempt
- **No broader SME exemption** — companies with 10+ employees providing in-scope services must comply
- Technical standard: EN 301 549 v3.2.1 (conformance presumption) references WCAG 2.1 for web content
- Transition period: existing service infrastructure (in use before 28 June 2025) has until **28 June 2030** to comply
- Enforcement: consumers can file complaints; national authorities can impose fines (amounts set by Member States)

**Key product implication:** The EAA is already in force. E-commerce companies, fintechs, and digital media services with 10+ employees must comply. This is an active compliance obligation, not a future one. The 2030 transition window for existing infrastructure means teams have time but a known deadline.

---

## Evidence status

evidence_level: initial-research

Updated 2026-06-23. EAA enforcement date, scope, and SME treatment verified from primary regulatory sources. Competitor landscape researched.

---

## Competitor landscape

Source: Deque, AccessProof websites, verified 2026-06-23. Full details in `research/domain-synthesis/regulatory-landscape.md`.

| Tool | Type | Pricing | Auto-fix capability |
|---|---|---|---|
| axe-core (Deque) | Open-source CI engine | Free | None — detection only |
| Axe DevTools (Deque) | Commercial CI/CD + IDE | Free (limited) / Enterprise | "One-click fixes" in IDE (human-reviewed suggestions, not automated PRs) |
| Lighthouse (Google) | Open-source audit | Free | None — detection only |
| Level Access | Enterprise platform | Enterprise (contact sales) | Monitoring + auditing, no automated fix generation |
| AccessProof | axe-core-based SaaS | $0–$199/mo | Monitoring + reporting, no automated fixes |

**Gap confirmed:** Axe DevTools Enterprise offers human-reviewed fix suggestions in IDE. No verified vendor offers fully automated pull-request generation for deterministic accessibility violations. This is the specific gap this product concept addresses.

---

## Unknowns

- How much of a WCAG violation backlog is actually deterministically fixable? (axe-core classifies violations — need to measure what % have deterministic fixes)
- Is the real bottleneck detection or the remediation step? (needs developer interviews)
- Who is the actual buyer: engineering lead, design lead, or legal/compliance? (likely depends on team size)
- What percentage of EAA-in-scope companies are currently non-compliant? (likely high, given the EAA just entered force)

---

## Lab sources relevant

None directly. No accessibility tooling sources have been imported.

---

## Next research question

1. Find 3 frontend developers at e-commerce or fintech companies: have they received EAA compliance questions from legal teams?
2. Get data from axe-core's violation taxonomy: what % of WCAG 2.1 violations fall into categories with deterministic fixes (color contrast, missing alt text, ARIA labels)?
3. Test Axe DevTools Enterprise trial to understand exactly what "one-click fixes" means — is the gap really as large as assumed?
