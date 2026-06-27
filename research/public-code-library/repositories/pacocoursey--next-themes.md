# pacocoursey/next-themes

## Identity
- **Owner**: pacocoursey
- **Repository**: next-themes
- **URL**: https://github.com/pacocoursey/next-themes
- **Live URL**: N/A (library)
- **Commit SHA**: a7eeabc39cfb37d74ea3d82eac674d0e1851b1cb
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Published npm package, the standard solution for dark mode in Next.js. No demo site — it is a pure library.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript
- **Frameworks**: React (Next.js compatible, App Router and Pages Router)
- **Key dependencies**: none at runtime (peerDep: react, next)
- **Build system**: tsup
- **Package manager**: pnpm (monorepo workspace with turbo)
- **Tests**: yes — Playwright e2e tests + React Testing Library unit tests
- **CI**: yes — GitHub Actions (test.yml, e2e.yml)
- **Architecture**: React context + localStorage + `matchMedia` + inline script injection. The inline script runs before React hydration to set the theme attribute, preventing flash.
- **State management**: React useState + useEffect, no external state lib
- **Rendering model**: Client-side context with SSR-safe pattern. Uses `'use client'` directive. The `<script>` injection technique is the key: a stringified function is injected as a blocking script to set `data-theme` or CSS class before paint.

## Useful content (exact files)

### Directly reusable code
- `next-themes/src/index.tsx` — Complete implementation (263 lines). Single file, no dependencies. Contains: `ThemeProvider`, `useTheme`, `disableAnimation()` helper, `getSystemTheme()`, localStorage read/write, `matchMedia` listener, attribute management.
- `next-themes/next-themes/src/script.ts` (if present) or inline `script` function — The blocking inline script that prevents flash-of-unstyled-content on theme load.

### Adaptable patterns
- **Flash-prevention via blocking script**: The `script` function stringifies a closure and injects it as a `<script>` tag in the document `<head>`. This runs synchronously before paint and sets the theme attribute. This pattern is directly applicable to any SSR framework (Next.js, Remix, Astro, SvelteKit).
- **Nested provider de-duplication**: `ThemeProvider` checks for existing context (`const context = React.useContext(ThemeContext)`) and returns early if already nested. Simple, clean pattern to prevent double-provider issues.
- **Transition disable/re-enable**: `disableAnimation(nonce)` injects `* { transition: none !important }` as an inline style, forces a reflow, then removes it. Prevents visual transition during initial theme load. Useful pattern for any global DOM mutation that should appear instant.
- **System theme detection**: `getSystemTheme()` and `matchMedia` listener pattern for detecting OS dark/light preference and reacting to changes at runtime.

### Architecture reference
- Single-file library architecture: 263 lines covers a complete, production-grade feature. Good example of scope discipline.
- Support for multiple attribute types (`class`, `data-*`, or array of both) via the `attribute` prop — extensible API design without branching internally.

### Reference-only
- `examples/with-app-dir/` — App Router integration example
- `examples/tailwind/` — Tailwind CSS integration example
- `test/` — Playwright tests for forced-theme, storage-event, system-theme, switch-theme

## Evaluation
**Problem solved**: Dark mode (and arbitrary theme switching) in Next.js without flash of unstyled content, supporting system preference, localStorage persistence, and arbitrary attribute/class strategies.
**Original value**: This is the canonical solution. The flash-prevention via blocking script injection is non-obvious and the implementation handles every edge case (SSR, system theme, forced themes, nested providers, color-scheme CSS property).
**Future project types**: Any Next.js, Remix, or React SSR app requiring theme switching. Directly applicable to Curator's web UI if it ever gains a settings/dark-mode toggle.
**Do not copy**: Do not copy without understanding the blocking script mechanism — dropping the script and using only React state will cause theme flash on page load in SSR environments.
**Risks**: None significant. MIT, active, 6,000+ stars, well-tested.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 4 |
| General usefulness | 5 |
| Architecture | 5 |
| Design and UX | 4 |
| Accessibility | 4 |
| Performance | 5 |
| Testing | 4 |
| Documentation | 4 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: critical
**Action**: clone
