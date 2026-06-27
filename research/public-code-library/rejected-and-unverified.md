# Rejected and Unverified Repositories

**Public Code Research Library**
**Date**: 2026-06-27

Documents all candidates that were rejected during evaluation or remain unverified. Provides the reasoning so future reviewers do not re-evaluate the same repositories unnecessarily.

---

## Rejected — Explicit Exclusions from Task Brief

These were excluded by explicit instruction in the task brief.

| Repository | Instruction | Notes |
|-----------|-------------|-------|
| LeaVerou/lea.verou.me | Task brief: do not include | Lea Verou's personal site source; excluded by instruction |
| raunofreiberg/primitives | Task brief: not Rauno's original work | This is the Radix Primitives upstream repo; Rauno submitted PRs to it but did not author it |
| emilkowalski/vaul | Task brief: treat as reference-only if unmaintained | Actually active (push Oct 2025); reclassified as low-priority clone |
| Old portfolio repos without proof | Task brief: no old portfolios without current deployment proof | Several personal sites were downgraded to reference-only without confirmed live deployment |
| 4Akera/openkairo | Task brief: keep as candidate-unverified | Ownership unverified; no live-site relationship confirmed; cannot verify maturity |

---

## Rejected — License Incompatibility

| Repository | License | Rejection Reason |
|-----------|---------|-----------------|
| brunosimon/threejs-template-complex | UNLICENSED | All rights reserved explicitly declared; no reuse permitted of any kind; use folio-2019 (MIT) instead |

---

## Rejected — Deprecated Technology

| Repository | Deprecated Technology | Notes |
|-----------|----------------------|-------|
| 14islands/webvr-boilerplate | WebVR API | WebVR was superseded by WebXR in 2019; all WebVR repos from 14islands' 2016–2018 period excluded |
| 14islands/webvr-workshop | WebVR API | Same as above |
| tobiasahlin/animated-menu | 2016-era CSS techniques | Retained as low-priority clone for educational value but would be rejected if not for Tobias Ahlin track coverage |
| bchiang7/octoprofile | Create React App | CRA is officially deprecated; no license; no architectural value |

---

## Rejected — Out of Scope (Technology)

| Repository | Technology | Reason |
|-----------|-----------|--------|
| nhsuk/wagtail-nhsuk-frontend | Python/Django/Wagtail | Lab scope is TypeScript/Swift/React; Python CMS out of scope |
| nhsuk/nhsuk-react-components | React component library | 444MB, superseded by nhsuk-frontend which covers the same components |
| Shopify/restyle | React Native | React Native styling library; lab does not target React Native as primary platform |
| Shopify/polaris-viz | Shopify-specific | Shopify admin chart library; too domain-specific for general lab use |
| vercel/next.js | Framework source | The framework itself, not an application built with it |
| radix-ui/primitives | Foundation library | Install via npm; studying source not necessary |
| tailwindlabs/tailwindcss | Framework | Install via npm; framework, not application |
| shadcn-ui/ui | Component registry | Commodity; already used as dependency in many projects; no unique insight |

---

## Rejected — Insufficient Quality

| Repository | Stars | Last Push | Rejection Reason |
|-----------|-------|-----------|-----------------|
| bchiang7/octoprofile | ~12k | 2023-01-05 | No license, no architectural novelty, CRA stack |
| brunosimon/keppler | 6.7k | 2021-09-14 | Screen-sharing presentation tool; out of scope; no web/app patterns |
| maxboeck/whimsical | 242 | 2023-05-10 | Visual inspiration only; no reusable code; Eleventy hobby project |
| tobiasahlin/bendable | 73 | 2023-09-22 | CSS elastic scroll experiment; too niche; minimal stars |
| antfu/vue-starport | 2k | 2023-09-14 | Experimental shared-element-transitions concept; not maintained; superseded by View Transition API |
| antfu/vscode-file-nesting-config | 3.6k | 2026-06-24 | Configuration file only (nesting rules in JSON); no executable code — RETAINED as reusable dataset but not as code |

---

## Rejected — Personal/Branding Content

| Repository | Reason |
|-----------|--------|
| sindresorhus/sindresorhus.github.com | Simple GitHub profile page; no architectural content |
| bchiang7/Halcyon | VS Code color theme; not code architecture; out of scope |
| MaximeHeckel/linear-vaporwave-react-three-fiber | Blog post companion demo; retained as reference-only only because it shows R3F patterns |

---

## Rejected — Commodity Tools (Install via npm)

These tools are valuable and MIT-licensed but are better used as npm dependencies than studied as source. Including them in the research library would not add insight.

| Package | npm | Reason to Install Not Study |
|---------|-----|---------------------------|
| Shopify/draggable | @shopify/draggable | Best-in-class drag-and-drop; study via docs, not source |
| Shopify/react-native-skia | @shopify/react-native-skia | React Native 2D rendering; use via npm |
| Shopify/flash-list | @shopify/flash-list | High-performance RN list; use via npm |
| nhsuk/nhsuk-frontend | nhsuk-frontend | 35+ healthcare components at 444MB; use via npm |
| vercel/geist-font | geist | Variable font; CSS import |

---

## Rejected — Competitive Conflict

| Repository | License | Conflict |
|-----------|---------|---------|
| saleor/storefront | FSL-1.1-ALv2 | Retained for study but reuse restricted if building a competing commerce product |
| webstudio-is/webstudio | AGPL-3.0 | Retained for study but AGPL copyleft prevents integration into proprietary products |

---

## Unverified — Pending Decision

### 4Akera/openkairo
**Status**: candidate-unverified
**Reason held**: Task brief explicitly flagged this as candidate-unverified. Author identity and relationship to any live product not independently confirmed. No GitHub stars or public deployment evidence at time of analysis.
**Action required**: Re-evaluate when the project has a public launch announcement or verifiable author identity. Do not clone until verified.

### brunosimon/folio-2025
**Status**: unverified
**Reason held**: Newer personal portfolio from Bruno Simon. Not yet inspected (not cloned). License unknown.
**Action required**: Inspect license before cloning. If MIT, would likely qualify as medium-priority 3D reference.

### emilkowalski/motion-primitives
**Status**: unverified
**Reason held**: Discovered via agents' new-candidates-discovered report. Framer Motion component library. Not inventoried in main pass.
**Action required**: Check license, maintenance status, and whether patterns differ meaningfully from sonner/vaul patterns already captured.

### raunofreiberg/bezel
**Status**: unverified
**Reason held**: Device frame component discovered by agents. Not inventoried in main pass.
**Action required**: Check license, stars, maintenance. If MIT + maintained, would fit the medium-priority UI component category.

---

## Classification Notes

- "Rejected" does not mean low quality. Several rejected repos (shadcn/ui, Shopify/draggable) are excellent tools. They were excluded because the library should contain code worth studying at source level, not merely listing good npm packages.
- "Unverified" means evaluation was inconclusive. These candidates should be re-evaluated on the next pass rather than assumed rejected.
- "No license" repositories are retained as study-only where the architectural value justifies it (pacocoursey/paco, raunofreiberg/interfaces) but rejected where the value does not (octoprofile, vincent-van-git as main candidate).
