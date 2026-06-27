# MaximeHeckel/blog.maximeheckel.com

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | MaximeHeckel |
| Repository | blog.maximeheckel.com |
| URL | https://github.com/MaximeHeckel/blog.maximeheckel.com |
| Live URL | https://blog.maximeheckel.com |
| Commit SHA | c3ef52f16ac0479ad7b575e4578bf1d68799f647 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active |
| Last Meaningful Push | 2026-06-25 |

## Legal Status

| Field | Value |
|-------|-------|
| License | MIT (code) |
| Content License | separate — `content/LICENSE` restricts post text |
| Attribution Required | yes for code |
| Asset Licensing | blog post content and author assets not reusable |
| Code Reuse | clearly permitted under MIT |
| Reference-Only | no — code is freely usable |

The repository has two distinct licenses: the MIT license at root covers source code. A separate `content/LICENSE` restricts the blog posts (MDX files). Do not copy or adapt blog post content. Components, hooks, and infrastructure are freely reusable.

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | TypeScript |
| Framework | Next.js 15 (App Router) |
| Major Dependencies | @react-three/fiber, @react-three/drei, framer-motion, @shikijs/rehype, @radix-ui/react-*, stitches, sandpack |
| Build System | Next.js + Turbo |
| Test System | Vitest (unit), Playwright (integration) |
| Repository Structure | `core/` (all components/hooks), `content/` (MDX), `app/` (Next.js routes), `public/` (assets) |
| Architecture | Next.js App Router with heavy MDX + React Server Components + client interactive islands |

## Actual Valuable Content

### Directly Reusable Components

**`core/components/CommandMenu/`**
- `CommandMenu.tsx` — Full keyboard-navigable command palette with search, grouping, and keyboard shortcut display. Built without a dependency on cmdk. Uses Framer Motion for animated presence. Custom `CommandMenuContext.tsx` provides open/close state via React context.
- High value: independent command menu implementation without external dependency lock-in.

**`core/components/Code/`**
- `CodeBlock.tsx` — Syntax-highlighted code blocks with Shiki, line highlighting, copy-to-clipboard, filename tabs.
- `Sandpack.tsx` — Live in-browser code editor/preview powered by CodeSandbox Sandpack.
- `components/` — Token-level highlight animation, diff highlighting components.

**`core/components/Charts/LineChart.tsx`**
- Custom SVG line chart with animated drawing effect. No chart library dependency. Pure SVG path + CSS animation.

**`core/components/DynamicTOC/`**
- `DynamicTOC.tsx` — Scroll-aware table of contents that highlights active section.
- `useIntersectionObserver.tsx` — Clean IntersectionObserver hook for active heading detection.
- `MiniProgressCircular.tsx` — SVG circular reading progress indicator.

**`core/components/Glow/Glow.tsx`**
- Radial gradient glow effect following cursor position. Uses CSS custom properties set via `style`. Lightweight, no canvas.

**`core/components/BeforeAfterImage/`**
- Drag-handle slider comparing two images. Pointer event handling for touch+mouse.

**`core/components/OG/`**
- `OG.tsx`, `DotMatrix.tsx`, `BlendedText.tsx` — Custom OG image generation components using `@vercel/og`.

**`core/components/ScrambledText.tsx`**
- Text scramble animation effect: cycles through random characters before settling on target text.

**`core/components/DotMatrixTicker.tsx`**
- Dot-matrix LED ticker effect in CSS/SVG.

**`core/components/Dock.tsx`**
- macOS-style dock with magnetic cursor enlargement via mouse proximity. Uses CSS custom properties + Framer Motion spring physics.

### Hooks

**`core/hooks/`**
- `useGPUTier.ts` — Detects GPU tier on client via WebGL renderer string for progressive enhancement of 3D/canvas features.
- `useViewTransitionNavigation.ts` — Wraps Next.js router to use View Transitions API when available.

### Build/MDX Infrastructure

**`core/components/MDX/`**
- `MDXComponents.tsx` — Comprehensive MDX component registry mapping HTML elements to custom components.
- `Widgets/` — Interactive MDX widgets for specific blog posts (animation playground, GPU tier demo, etc.).

## Value Classification

| Item | Classification |
|------|---------------|
| CommandMenu | directly reusable code |
| CodeBlock with Shiki | directly reusable code |
| DynamicTOC + IntersectionObserver hook | directly reusable code |
| Glow component | directly reusable code |
| Dock with magnetic physics | adaptable implementation pattern |
| BeforeAfterImage | directly reusable code |
| SVG LineChart | adaptable implementation pattern |
| ScrambledText | directly reusable code |
| OG generation components | adaptable implementation pattern |
| useGPUTier hook | directly reusable code |
| useViewTransitionNavigation | directly reusable code |
| Blog post content | reject |
| Author branding/design | reject |

## General Usefulness

**Problem it solves**: Demonstrates how to build a highly interactive, custom-designed technical blog with MDX, shader experiments, and interactive component demos.

**Why the implementation is notable**: The blog goes far beyond typical Next.js blog templates. The GPU tier detection, View Transition API integration, fully custom command menu without dependency, and the animated SVG chart without a chart library all demonstrate precise, targeted implementations rather than reaching for libraries.

**Future project uses**:
- Command menu pattern for any app needing keyboard navigation
- CodeBlock + Sandpack for documentation sites or learning platforms
- DynamicTOC + IntersectionObserver for any long-form reading experience
- Glow and ScrambledText for landing pages
- Dock with magnetic physics for creative UI experiments
- GPU tier detection for any 3D/canvas progressive enhancement
- OG generation pattern for any Next.js site

**Smaller isolated part more valuable than whole**: Yes. The `core/` directory is the valuable part. The blog content, personal branding, and specific page layouts are not reusable.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 5/5 | Excellent TypeScript discipline, small focused files |
| Architecture | 5/5 | Clear separation: core components / MDX content / Next.js routes |
| Maintainability | 5/5 | Actively maintained, uses current Next.js APIs |
| Accessibility | 4/5 | CommandMenu has good keyboard support; some interactive demos lack ARIA |
| Performance | 5/5 | RSC for static pages, GPU tier gating for heavy 3D |
| Testing Quality | 3/5 | Vitest for utilities, Playwright for integration; component tests sparse |
| Documentation | 3/5 | No JSDoc; code is self-documenting |
| Dependency Risk | 3/5 | Heavy R3F/Framer Motion dependency; these are stable but large |
| Security | 5/5 | No server-side vulnerabilities visible |
| Originality | 5/5 | Custom implementations throughout |
| Implementation Age | 5/5 | Current (2025-2026 commits) |
| Long-term Usefulness | 5/5 | Core patterns are framework-agnostic |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 5 |
| Originality | 5 |
| General Reusability | 4 |
| Educational Value | 5 |
| Design / UX Quality | 5 |
| Architecture Quality | 5 |
| Documentation Quality | 3 |
| Maintenance Health | 5 |
| Licensing Clarity | 4 |
| Long-term Lab Value | 5 |

**Final Priority**: critical  
**Recommended Action**: clone (done — `external-sources/blog.maximeheckel.com`)
