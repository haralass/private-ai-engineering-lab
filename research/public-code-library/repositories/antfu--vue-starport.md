# antfu/vue-starport

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | antfu |
| Repository | vue-starport |
| URL | https://github.com/antfu/vue-starport |
| Live URL | https://vue-starport.netlify.app |
| Commit SHA | a6a0e9b40d7f5addd9e7def5f3a5c9b8c5dc17a0 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | stale (last push September 2023) |
| Last Meaningful Push | 2023-09-14 |

## Legal Status

| Field | Value |
|-------|-------|
| License | MIT |
| Attribution Required | yes |
| Code Reuse | clearly permitted |
| Reference-Only | no |

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | TypeScript |
| Framework | Vue 3 |
| Major Dependencies | Vue 3, @vueuse/core |
| Build System | Vite |
| Test System | Cypress |
| Repository Structure | `src/` (the library), `playground/` (Vue demo app), `cypress/` (E2E tests) |
| Architecture | Vue 3 composition plugin. Uses a portal (teleport) approach to keep a single component instance alive across route transitions, animating it between its positions in the previous and next routes. |

## Actual Valuable Content

### The Core Concept: Shared Element Transitions

Vue Starport enables "shared element transitions" — a component that appears in both the origin and destination routes is not unmounted and remounted; instead, it is kept alive and smoothly animated between its positions.

**`src/`** — Core library implementing:

1. **Portal mechanism**: The actual component DOM node lives outside the page tree (a teleport target). Route-aware wrappers show/hide it where it should appear.

2. **FLIP animation**: Before-After-Invert-Play — reads the element's bounding rect at the current position, teleports it to the destination, then animates the difference using CSS `transform`. The same FLIP technique used by Framer Motion's `layoutId` prop.

3. **`<StarportCarrier>`**: The invisible persistent element that travels between routes.

4. **`<StarportCraft>`**: The visual wrapper in each route that the carrier animates to/from.

The library is framework-specific (Vue) but the FLIP shared-element transition concept is directly applicable in any framework.

### The Concept Pattern (extractable)

The FLIP animation algorithm (`graphs/graph1.png`, `graphs/graph2.png`, `graphs/graph3.png` show the mechanism visually):
1. Record `getBoundingClientRect()` of element in origin.
2. Move element to destination in DOM.
3. Record `getBoundingClientRect()` in destination.
4. Apply an initial CSS transform that maps destination → origin.
5. Animate the transform to `identity` (no transform).
Result: element appears to fly from origin to destination.

## Value Classification

| Item | Classification |
|------|---------------|
| FLIP shared-element transition concept | adaptable implementation pattern |
| Vue Teleport + route-transition coordination | architecture reference |
| Diagram assets (graph1, graph2, graph3) | educational reference |
| Playground Vue demo | visual inspiration |

## General Usefulness

**Problem it solves**: Smooth layout animations when the same logical element appears on multiple pages — avoiding the jarring disappear/appear that happens on route changes.

**Why the implementation is notable**: The FLIP approach is the correct, performant way to do shared-element transitions. This implementation makes it declarative in Vue. The concept is React-equivalent to Framer Motion's `layoutId` feature. The playground shows it working across multiple routes.

**Future project uses**:
- The FLIP algorithm is directly implementable in any framework
- Reference for building a similar mechanism in React or SwiftUI
- Applicable to any multi-page app where the user expects a shared element to animate between views

**Caveats**: Vue-specific. Stale (2023). The FLIP concept is the valuable transferable insight.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 4/5 | Clean Vue 3 composition |
| Originality | 5/5 | Innovative application of FLIP to Vue routing |
| Implementation Age | 3/5 | 2023; Vue 3 and Vite are still current |
| Testing Quality | 3/5 | Cypress E2E present |
| Educational Value | 5/5 | Excellent for learning FLIP transitions |
| Long-term Usefulness | 4/5 | FLIP concept is timeless; Vue implementation is dated |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 4 |
| Originality | 5 |
| General Reusability | 3 |
| Educational Value | 5 |
| Design / UX Quality | 4 |
| Architecture Quality | 4 |
| Documentation Quality | 3 |
| Maintenance Health | 2 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 4 |

**Final Priority**: medium  
**Recommended Action**: reference-only (FLIP concept is the key insight; Vue-specific code not directly reusable in non-Vue projects)
