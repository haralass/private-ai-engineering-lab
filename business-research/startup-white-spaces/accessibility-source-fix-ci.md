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

## Evidence status

evidence_level: none
No research conducted beyond naming this idea.

---

## Unknowns

- How much of a WCAG violation backlog is actually deterministically fixable?
- Are axe-core + GitHub Actions already "good enough" for most teams?
- Is the real bottleneck detecting violations or prioritizing and fixing them?
- What is the European Accessibility Act enforcement timeline and scope for SMEs?
- Who is the actual buyer: engineering lead, design lead, or legal/compliance?

---

## Lab sources relevant

None directly. No accessibility tooling sources have been imported.

---

## Next research question

1. Find 3 frontend developers: what do they do when CI reports an axe-core violation?
2. Check: does any existing tool (Deque, Level Access, Accessibility Insights) offer
   automated fix suggestions in CI?
3. Research: which product categories are in scope for the European Accessibility Act
   and what is the deadline for compliance?
