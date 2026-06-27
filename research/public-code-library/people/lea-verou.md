# Lea Verou

**GitHub**: LeaVerou
**Website**: https://lea.verou.me
**Analysis date**: 2026-06-27

## Profile overview
Lea Verou is a CSS Working Group Invited Expert, co-creator of numerous CSS specs, and a researcher at MIT CSAIL. Her GitHub is a direct window into the evolution of web standards — many repos predate or directly informed features now shipping in browsers. She combines academic rigor with deeply practical open-source tooling.

## Repository inventory
| Repository | Stars | Language | Status | Decision |
|---|---|---|---|---|
| awesomplete | 6,979 | JavaScript | Active | RETAIN |
| parsel | 396 | TypeScript | Active | RETAIN |
| style-observer | 538 | JavaScript | Active | RETAIN |
| stretchy | 1,273 | JavaScript | Active | RETAIN |
| bliss | 2,395 | JavaScript | Active | REFERENCE |
| animatable | 2,578 | HTML | Unmaintained | REJECT (demo) |
| prefixfree | 3,790 | JavaScript | Archived | REJECT (archived, superseded) |
| dabblet | 826 | JavaScript | Active | REJECT (app, not library) |
| css3patterns | 1,454 | HTML | Stale | REJECT (demo gallery) |
| conic-gradient | 485 | HTML | Active | REFERENCE |
| multirange | 608 | CSS | Active | REFERENCE |
| blissful-hooks | 10 | JavaScript | Active | REJECT (WIP) |
| rety | 402 | JavaScript | Active | REFERENCE |
| md-block | 355 | JavaScript | Active | REFERENCE |
| leaverou.github.io | 12 | — | Data repo | REJECT (not site source) |
| wpt | 1 | — | Fork | REJECT |
| dpi | 771 | JavaScript | Active | REJECT (tool, not library) |

## Strongest repositories

**style-observer** — Technically the most novel. Implements a MutationObserver-style API for observing any CSS property change on any element, using CSS transitions as the underlying detection mechanism. Unique engineering: hooks into CSS `transition` events via injected `--style-observer-transition` custom property, handles Shadow DOM hosts via adopted stylesheets, contains per-browser bug workarounds (Safari transitionrun loop, adopted-stylesheet quirks). Zero dependencies. Fully tested.

**parsel** — The only serious standalone CSS selector parser in TypeScript. Single-file, ~400 lines, tokenizes and builds an AST for full CSS4 selector syntax including recursive pseudo-classes (`:not()`, `:has()`, `:is()`). Actively maintained (last push June 2026). Directly reusable for selector analysis, specificity calculation, selector transformation.

**awesomplete** — Zero-dependency autocomplete with 6,979 stars. Clean, accessible implementation. Notably minimal: one JS file, one CSS file, MutationObserver for DOM sync. Mature and stable.

**stretchy** — Form element auto-sizing (textarea, input, select) with zero dependencies. Handles textarea height, input width-to-content, and select natural width via a clever hidden `_` element clone. MutationObserver-based for dynamically added elements.

## Key findings
Verou's most valuable contribution to this library is `style-observer`, which solves a genuinely hard problem (observing any CSS computed property change without polling) using an innovative transition-event trick. `parsel` is the only reliable CSS selector AST parser in TypeScript — useful any time CSS selectors need to be analyzed programmatically. Both repos demonstrate her dual strength: deep browser API knowledge combined with clean, ergonomic JavaScript APIs.

## Rejected repositories
- **prefixfree** — Archived; browser prefixes are now obsolete
- **animatable** — Demo page, not a library
- **css3patterns** — Gallery of CSS patterns, not importable code
- **dabblet** — Interactive CSS playground app, not a utility
- **blissful-hooks** — WIP, minimal content
- **leaverou.github.io** — Self-described "just a data repo"
- All forks — Not original work
- Personal/hobby apps (mygraine, zoe-eats, forkgasm, expenses, etc.)
- Archived proposal repos
