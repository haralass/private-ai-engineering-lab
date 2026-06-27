# emilkowalski/sonner

## Identity
- **Owner**: emilkowalski
- **Repository**: sonner
- **URL**: https://github.com/emilkowalski/sonner
- **Live URL**: https://sonner.emilkowal.ski
- **Commit SHA**: 45d894085af8ca8421912789a8f5a4ac4ac3d0ea
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push December 2025)
- **Transferred**: no

## Relationship classification
full-open-source-product

Evidence: Production-ready npm package. 12,513 stars. Playwright integration test suite. Used in major production applications. Monorepo with separate website. Ships as a complete React component library.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript (React)
- **Frameworks**: React 18+
- **Key dependencies**: none (zero runtime dependencies beyond React itself)
- **Build system**: Turbo (monorepo), pnpm
- **Tests**: yes — Playwright integration tests in `/test/tests/`
- **Architecture**: 
  - `src/state.ts`: Observer/subscriber pattern. A singleton `Observer` class holds the toast queue. The `toast` export function and all variants (`toast.success`, `toast.error`, etc.) call `ToastState.create()`. Subscribers (the `<Toaster>` component) register via `ToastState.subscribe()`.
  - `src/index.tsx`: The `<Toaster>` and `<Toast>` React components. State is managed with `React.useState`/`useEffect`/`useRef`. The auto-dismiss timer uses elapsed-time tracking (`remainingTime.current`, `closeTimerStartTimeRef`) rather than restarting on hover — the timer pauses and resumes from exactly where it left off. Swipe-to-dismiss uses pointer events with velocity detection (`Math.abs(swipeAmount) / timeTaken`). Layout is driven by CSS custom properties (`--index`, `--offset`, `--initial-height`, `--z-index`) set on each toast element's inline style, enabling the stacked/expanded animation to be CSS-driven.
  - `src/styles.css`: All animation via CSS transitions and `data-*` attribute selectors (e.g., `[data-mounted]`, `[data-removed]`, `[data-expanded]`).
  - `src/hooks.tsx`: `useIsDocumentHidden()` — single-hook for `document.visibilitychange` event.
  - `src/types.ts`: Full TypeScript type definitions for the public API.
- **Rendering model**: React client component (uses `'use client'` directive for Next.js compatibility), `ReactDOM.createPortal` for rendering outside component tree

## Useful content (exact files)

### Directly reusable code
- `src/state.ts` — Observable toast state machine. Directly reusable as the state layer for any notification system regardless of rendering framework.
- `src/hooks.tsx` — `useIsDocumentHidden()` React hook. Trivially extractable.
- `src/styles.css` — Full animation CSS using data-attribute selectors. Copy-adaptable for any toast-style animation.

### Adaptable patterns
- **Elapsed-time timer pause** (`src/index.tsx` lines 200–235): On hover/focus, records `lastCloseTimerStartTimeRef` and subtracts elapsed time from `remainingTime`. On resume, starts a new `setTimeout` with the remaining duration. This pattern is correct for any pauseable auto-dismiss timer.
- **Swipe velocity detection** (`src/index.tsx` lines 325–350): `velocity = Math.abs(swipeAmount) / timeTaken`. Dismiss if `Math.abs(swipeAmount) >= 45` OR `velocity > 0.11`. The dual condition (distance OR speed) creates natural feel.
- **CSS custom property layout** (`src/index.tsx` lines 294–304): Stacked layout driven by `--index`, `--offset`, `--initial-height` CSS custom properties set imperatively — lets CSS transitions animate the layout changes.
- **Promise toast** (`src/state.ts` lines 125–241): Handles loading → success/error state transitions, HTTP response detection (`Response.ok`), and both synchronous and async error/success renderers.

### Architecture reference
- The separation of `state.ts` (framework-agnostic observer) from `index.tsx` (React rendering) is an excellent architecture pattern for UI primitives that might need to work across frameworks.
- `data-*` attribute state machine on the toast `<li>` element (`data-mounted`, `data-removed`, `data-swiping`, `data-swipe-out`, `data-expanded`, `data-visible`): enables CSS to handle all visual state transitions without JS-managed className toggling.

### Reference-only
- `test/tests/` — Playwright tests; good reference for testing animated UI components
- `website/` — Next.js documentation site

## Evaluation
**Problem solved**: Toast notification queue with stacking, swipe-to-dismiss, auto-dismiss with hover-pause, promise states, and position management. Does all of this with zero runtime dependencies and a clean imperative API (`toast("message")`).

**Original value**: The implementation quality significantly exceeds most toast libraries. Specific innovations: the elapsed-timer pause (most implementations just clear and restart), the stacked 3D layout using CSS custom properties, and the data-attribute state machine for animation.

**Future project types**: Any React application needing toast notifications. The state.ts layer is reusable for non-toast notification systems (any "ephemeral message queue" use case). The animation patterns are applicable to any enter/exit animation system.

**Do not copy**: The `ReactDOM.createPortal` usage targets `document.body` by default. In server-rendered frameworks (Next.js App Router), the `'use client'` boundary is required. Do not use the state singleton in SSR contexts without hydration consideration.

**Risks**: Singleton state (`ToastState`) means one toast queue per page. For apps with multiple isolated regions needing independent queues, the architecture would need to be extended. No accessibility audit documented (keyboard focus management for dismissed toasts is not explicit in source).

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 5 |
| Originality | 4 |
| General usefulness | 5 |
| Architecture | 5 |
| Design and UX | 5 |
| Accessibility | 3 |
| Performance | 5 |
| Testing | 4 |
| Documentation | 5 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: critical
**Action**: clone
