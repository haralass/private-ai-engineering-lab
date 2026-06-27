# antfu/antfu.me

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | antfu |
| Repository | antfu.me |
| URL | https://github.com/antfu/antfu.me |
| Live URL | https://antfu.me |
| Commit SHA | 7db9f1eff3037e714d3fe97976a685cbac527965 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active |
| Last Meaningful Push | 2026-05-23 |

## Legal Status

| Field | Value |
|-------|-------|
| License | MIT (code) |
| Content License | CC-BY-NC-SA-4.0 (blog posts in `pages/posts/`) |
| Attribution Required | yes for code; blog content has separate restrictions |
| Asset Licensing | personal content and writings not reusable |
| Code Reuse | code clearly permitted under MIT |
| Reference-Only | no |

Two separate license files: `LICENSE` (MIT, covers code) and `CC-BY-NC-SA-4.0` (covers writing). The code infrastructure — Vite, UnoCSS, Vue 3 composables — is freely reusable.

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | TypeScript + Vue 3 SFCs |
| Framework | Vite + Vue 3 + VitePress-influenced routing |
| Major Dependencies | UnoCSS, @vueuse/core, vite-plugin-pages, vite-plugin-md, unplugin-auto-import, unplugin-vue-components |
| Build System | Vite |
| Test System | none visible |
| Repository Structure | `src/pages/` (routes), `src/components/` (Vue), `data/` (talks, media), `demo/` (mini recorded demos) |
| Architecture | File-system-based routing with Vite, Markdown-as-pages, UnoCSS atomic styling, auto-imported composables |

## Actual Valuable Content

### Architecture Reference

**Vite + UnoCSS + auto-import composition**  
The setup in `vite.config.ts` (and related plugin config) wires together:
- `vite-plugin-pages` for file-system routing
- `vite-plugin-md` for Markdown-as-Vue-components
- `unplugin-auto-import` for auto-importing Vue composables without explicit imports
- `unplugin-vue-components` for auto-resolving components
- UnoCSS for atomic utility classes generated on-demand

This is a reference for ultra-minimal personal sites with zero boilerplate per page.

**`src/composables/dark.ts`**  
Dark mode composable using `@vueuse/core` `useDark()` — simple but canonical pattern for Vue dark mode.

**UnoCSS Configuration**  
The `uno.config.ts` (or `vite.config.ts`) defines custom shortcuts, icons (via @iconify), and web fonts — demonstrates how to build a complete design token system with zero runtime CSS overhead.

### Routing Pattern

`src/pages/` directory uses file-system routing where `.md` files become pages automatically. The pattern is clean and worth studying for static site generation without a framework lock-in.

### Data Pattern

`data/talks.ts` and `data/media.ts` — typed TypeScript data files for structured content (talks list, media appearances). Simple but effective pattern for content that needs type safety without a CMS.

## Value Classification

| Item | Classification |
|------|---------------|
| Vite + UnoCSS + auto-import configuration | architecture reference |
| File-system routing with Markdown pages | architecture reference |
| Dark mode composable | directly reusable code |
| Typed data files for structured content | adaptable implementation pattern |
| Blog post content | reject |
| Personal branding/design | reject |

## General Usefulness

**Problem it solves**: Ultra-minimal personal site with Vite, Vue 3, and UnoCSS that achieves fast iteration and zero boilerplate.

**Why the implementation is notable**: The combination of auto-imports (no explicit `import` statements in SFCs), file-system routing, and UnoCSS means adding a new page requires only creating a file — no routing config, no component imports, no CSS imports. The DX is exceptionally low-friction.

**Future project uses**:
- Reference for any Vue 3 static site that wants minimal boilerplate
- UnoCSS configuration as baseline for utility-first Vue projects
- Dark mode composable for Vue apps

**Smaller isolated part more valuable than whole**: Yes. The Vite plugin configuration and UnoCSS setup are more valuable than the full site. The personal content is not reusable.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 4/5 | Clean, idiomatic Vue 3 composition API |
| Architecture | 4/5 | Elegant auto-import and file-system routing setup |
| Maintainability | 4/5 | Actively maintained; minimal dependencies age well |
| Accessibility | 2/5 | Minimal; not a focus for personal site |
| Performance | 5/5 | Static site with minimal JS; UnoCSS eliminates unused CSS |
| Testing Quality | 1/5 | No tests |
| Documentation | 2/5 | README minimal |
| Dependency Risk | 3/5 | UnoCSS and VueUse are stable; vite-plugin-pages less so |
| Originality | 3/5 | Good integration of well-known tools |
| Long-term Usefulness | 3/5 | More useful as architecture reference than direct reuse |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 4 |
| Originality | 3 |
| General Reusability | 2 |
| Educational Value | 4 |
| Design / UX Quality | 3 |
| Architecture Quality | 4 |
| Documentation Quality | 2 |
| Maintenance Health | 4 |
| Licensing Clarity | 4 |
| Long-term Lab Value | 3 |

**Final Priority**: medium  
**Recommended Action**: clone (done — `external-sources/applications/antfu.me`)
