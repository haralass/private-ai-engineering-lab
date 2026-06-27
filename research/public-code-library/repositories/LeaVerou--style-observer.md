# LeaVerou/style-observer

## Identity
- **Owner**: LeaVerou
- **Repository**: style-observer
- **URL**: https://github.com/LeaVerou/style-observer
- **Live URL**: https://projects.verou.me/style-observer/ (inferred from repo structure)
- **Commit SHA**: 972e75a2eae0a3f77924feb6a50375682cf65a82
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push December 2025)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Published as an npm package. API is modeled after MutationObserver/IntersectionObserver. Has a comprehensive test suite (11 test files covering transitions, shadow DOM, disconnected elements, nested observers, reflow). Zero runtime dependencies. Explicitly designed for external consumption.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: JavaScript (ES modules)
- **Frameworks**: none (vanilla JS)
- **Key dependencies**: none (zero runtime deps)
- **Build system**: Eleventy (for site only), npm for package
- **Tests**: yes — 11 test files in `/tests/`, browser-runnable HTML test runner
- **Architecture**: Multi-class observer pattern. `StyleObserver` (public API) manages a WeakMap of `ElementStyleObserver` instances per target. `ElementStyleObserver` (per-element) injects a CSS custom property (`--style-observer-transition`) into the element's inline style or adopted shadow stylesheet, which adds a 1ms `step-start` transition for every observed property. CSS `transitionstart`/`transitionend` events fire when any observed property changes, at which point `getComputedStyle` reads the new values. A `RenderedObserver` (ResizeObserver-backed) catches changes that don't trigger transitions.
- **Rendering model**: browser DOM (no SSR concerns)

## Useful content (exact files)

### Directly reusable code
- `src/style-observer.js` — Public-facing multi-target API class (StyleObserver)
- `src/element-style-observer.js` — Core per-element observer; contains the transition injection trick, Shadow DOM host handling, and browser bug workarounds
- `src/util/MultiWeakMap.js` — WeakMap keyed to multiple values; useful utility for shared-state-by-element patterns
- `src/util/gentle-register-property.js` — Safe `CSS.registerProperty()` wrapper that handles already-registered and unsupported cases
- `src/util/adopt-css.js` — Shadow DOM adopted stylesheet utility

### Adaptable patterns
- **Transition-event CSS property change detection** (`element-style-observer.js` lines 175–220): Injecting a zero-duration `step-start` transition to trigger events on any CSS property change. Adaptable to any scenario needing CSS property reactivity without polling.
- **Shadow DOM adopted stylesheet injection** (`element-style-observer.js` lines 330–362): Clean approach to setting styles on shadow host elements without polluting their `style` attribute.
- **Per-browser bug detection system** (`src/util/bugs/`): Pattern for async browser feature detection with resolved Promises; reusable for any polyfill/workaround system.

### Architecture reference
- Observer pattern matching MutationObserver API shape: constructor takes callback + options, `.observe()` / `.unobserve()` methods, records array passed to callback. Worth studying as a template for custom observer APIs.

### Reference-only
- `tests/` — Test suite is browser-runnable HTML; interesting as an example of lightweight browser test infrastructure without a test runner dependency

## Evaluation
**Problem solved**: Observing changes to any CSS computed property (including custom properties, display, color, font-size — anything `getComputedStyle` returns) without polling. Fills a gap between MutationObserver (DOM attributes only) and ResizeObserver (size only).

**Original value**: The CSS transition trick is a genuinely clever hack — it repurposes the browser's existing transition event infrastructure to detect computed property changes. This approach was not obvious and predates any native browser API for this use case.

**Future project types**: Any project needing reactive CSS: theme-switching with JS callbacks, CSS-driven component state machines, polyfills for CSS-at-JS interop, design tools that need to react to computed style changes.

**Do not copy**: The browser bug workaround logic in `src/util/bugs/` is highly browser-version-specific (targets Safari < 18.2 `transitionrun` loop bug and specific Firefox adopted-stylesheet bugs). Do not copy these verbatim without re-checking browser support at time of use.

**Risks**: Injects inline `transition` style on observed elements. If target elements already have carefully managed inline transitions, this could interfere. The library handles most cases but documents this limitation.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 4 |
| Architecture | 5 |
| Design and UX | 4 |
| Accessibility | 3 |
| Performance | 4 |
| Testing | 5 |
| Documentation | 4 |
| Maintenance health | 4 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: critical
**Action**: clone
