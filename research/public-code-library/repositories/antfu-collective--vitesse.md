# antfu-collective/vitesse

## Identity
- **Owner**: antfu-collective
- **Repository**: vitesse
- **URL**: https://github.com/antfu-collective/vitesse
- **Live URL**: https://vitesse.netlify.app
- **Commit SHA**: 8a01bc9
- **Analysis date**: 2026-06-27
- **Original / Fork**: original (transferred from antfu/vitesse to antfu-collective/vitesse)
- **Status**: active
- **Transferred**: yes — moved from antfu/vitesse to antfu-collective/vitesse

## Relationship classification
`production-starter`

Evidence: An opinionated Vue 3 + Vite starter template maintained by Anthony Fu and the antfu-collective organization. Designed to be cloned and built upon. Not a demo — the live site demonstrates the installed result.

## Licensing
- **Code license**: MIT (LICENSE confirmed)
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript, Vue 3 (SFC), CSS
- **Frameworks**: Vue 3, Vite, UnoCSS, Pinia, Vue Router, Vue I18n, VueUse
- **Key dependencies**: vue, vite, unocss, pinia, vue-router, @vueuse/core, vue-i18n, vite-ssg, vite-plugin-vue-layouts, vite-plugin-pages, @antfu/eslint-config
- **Build system**: Vite (with Vitest for testing, Cypress for E2E)
- **Package manager**: pnpm (pnpm workspace)
- **Tests**: Vitest (unit), Cypress (E2E)
- **CI**: GitHub Actions
- **Architecture**: Vue 3 SFC with auto-import (components, composables, APIs — no explicit `import` needed). File-based routing via `vite-plugin-pages`. Layout system via `vite-plugin-vue-layouts`. SSG via `vite-ssg`. UnoCSS for atomic CSS (Tailwind-compatible). Pinia for state.
- **State management**: Pinia
- **Rendering model**: SSG (static site generation) via vite-ssg, with optional hydration

## Useful content (exact files)

### Directly reusable code

- `src/composables/` — Vue composable examples: `dark.ts` (dark mode toggle with `@vueuse/core`). Shows the correct pattern for VueUse-based composables.
- `src/stores/user.ts` — Minimal Pinia store example. Shows the correct Pinia store pattern (Options API style).
- `src/pages/` — File-based routing structure. `index.vue`, `[...all].vue` (404), `hi/[name].vue` (dynamic routes). Demonstrates correct vite-plugin-pages routing conventions.
- `src/layouts/` — Layout system with `default.vue`. Shows how vite-plugin-vue-layouts works with `<RouterView>`.
- `uno.config.ts` — UnoCSS configuration: presetUno, presetAttributify, presetIcons. Reference for UnoCSS setup with attributify mode.
- `vite.config.ts` — Complete Vite configuration: auto-import plugins, pages, layouts, SSG, PWA, markdown support. The most complete public reference for a Vite + Vue production configuration.

### Adaptable patterns

- **Auto-import pattern** (`vite.config.ts` — `unplugin-auto-import`, `unplugin-vue-components`): No explicit imports needed for Vue APIs, VueUse composables, or local components. This pattern significantly reduces boilerplate in large Vue projects.
- **File-based routing** (`src/pages/`, `vite.config.ts` — `vite-plugin-pages`): Routes are inferred from file structure. Dynamic segments use `[param]` naming convention. Directly adoptable for any Vue 3 + Vite project.
- **UnoCSS with attributify mode** (`uno.config.ts`): Atomic CSS utilities applied as HTML attributes (`text="xl blue-400"`) rather than class strings. An alternative to Tailwind that many prefer for readability.
- **Pinia + Persist pattern**: State management with optional localStorage persistence via `@pinia/plugin-persistedstate`.

### Architecture reference

Vitesse is the definitive reference configuration for a production Vue 3 + Vite application. The combination of auto-imports + file routing + UnoCSS + Pinia + SSG is the most influential Vue starter template (it has spawned dozens of variants including vitesse-nuxt, vitesse-lite, vitesse-solid, vitesse-webext, etc.).

### Reference-only

- `cypress/` — E2E test setup for a Vue project with Cypress. Study for Vue Cypress configuration.
- `README.md` — Documents every feature and the motivation behind each choice. Worth reading fully.

## Evaluation

**Problem solved**: The setup-paralysis problem for Vue 3 + Vite projects. Provides a fully configured, opinionated starter with every production concern addressed (routing, state, CSS, i18n, PWA, testing, SSG).

**Original value**: Vitesse established the auto-import pattern as a mainstream Vue 3 convention. The combination of auto-imports + file-based routing + UnoCSS is the most-copied Vue 3 starter architecture. The `vite.config.ts` is alone worth studying as a complete reference for Vite plugin composition.

**Future project types**: Any Vue 3 + Vite project, Vue component libraries, Vue SSG sites, browser extensions (see vitesse-webext).

**Do not copy**: The placeholder content, demo pages, sample composables (they're minimal examples, not production utilities).

**Risks**: The antfu-collective organization is community-maintained; Anthony Fu is the primary driver. Nuxt 3 is now preferred for full-stack Vue — Vitesse is best for SPA/SSG scenarios. The auto-import pattern can make codebases harder to navigate for developers unfamiliar with the setup.

## Scores (1–5)

| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 4 |
| Architecture | 5 |
| Design and UX | 3 |
| Accessibility | 3 |
| Performance | 5 |
| Testing | 4 |
| Documentation | 5 |
| Maintenance health | 4 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: high
**Action**: clone
