# bchiang7/v4

## Identity
- **Owner**: bchiang7
- **Repository**: v4
- **URL**: https://github.com/bchiang7/v4
- **Live URL**: https://brittanychiang.com
- **Commit SHA**: 539cef0bf60cad438499a69d76dba2c27cc16c9a
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push July 2024)
- **Transferred**: no

## Relationship classification
exact-live-site-source

Evidence: The source for brittanychiang.com, confirmed by gatsby-config.js `siteUrl: 'https://brittanychiang.com'`.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: Personal content (resume PDF, project descriptions, images) should not be reused. Code structure is MIT.
- **Reuse verdict**: directly reusable (code patterns); personal content is out of scope

## Technical profile
- **Languages**: JavaScript (React)
- **Frameworks**: Gatsby 3, styled-components 5, React 17
- **Key dependencies**: styled-components, react-transition-group, scrollreveal, animejs, gatsby-source-filesystem, gatsby-transformer-remark, prismjs
- **Build system**: Gatsby (webpack under the hood)
- **Package manager**: yarn
- **Tests**: no
- **CI**: yes — Netlify deployment (gatsby-plugin-netlify)
- **Architecture**: Gatsby static site with content-driven sections (hero, about, jobs/experience, featured projects, other projects, contact). Content stored as markdown files in `content/`. Sections are discrete components in `src/components/sections/`. Custom hooks for scroll behavior and animation preferences. All styling via styled-components with theme mixin system.
- **State management**: React useState (local only, no global state)
- **Rendering model**: SSG (Gatsby static generation). Some client-side JavaScript for scroll animations and loader.

## Useful content (exact files)

### Directly reusable code
- `src/hooks/useScrollDirection.js` — Returns 'up'/'down' based on scroll direction. Used to show/hide the navbar on scroll. Clean, dependency-free, ~30 lines.
- `src/hooks/usePrefersReducedMotion.js` — Returns `true` if the user has `prefers-reduced-motion: reduce` set. Used to disable all scroll/entrance animations. Correct accessibility pattern.
- `src/hooks/useOnClickOutside.js` — Calls a handler when a click occurs outside a given ref. Standard pattern for dismissing modals/dropdowns.
- `src/styles/mixins.js` — Styled-components mixin library: `flexCenter`, `flexBetween`, `flexAround`, `link`, `inlineLink`, `button`, `bigButton`, `smallButton`, `boxShadow`, `transition`. All as tagged template literals for use via `${({ theme }) => theme.mixins.flexCenter}`.
- `src/styles/variables.js` — CSS custom property declarations: font sizes (fz-xxs through fz-xxl), font families, transitions, border radius, navy color palette.
- `src/styles/GlobalStyle.js` — Global CSS reset + base styles using createGlobalStyle.
- `src/config.js` — Single config file: nav links, social media links, email, colors. Clean pattern for centralizing site configuration.

### Adaptable patterns
- **Mixin system via styled-components theme**: `theme.mixins.flexCenter` injects a `display: flex; justify-content: center; align-items: center;` block. The mixin system is defined once in `mixins.js`, added to the theme object, and consumed anywhere via destructuring. Eliminates repetitive flexbox declarations across components.
- **ScrollReveal integration pattern**: `src/components/sections/` components use `sr.reveal(revealRef.current, srConfig())` to register entrance animations. Config in `src/utils/sr.js`. The `usePrefersReducedMotion` hook disables this entirely when the OS accessibility setting is on — the correct implementation.
- **Content-driven portfolio pattern**: Project content lives in `content/projects/*.md` with frontmatter. `gatsby-node.js` creates pages. Adding a new project means adding one markdown file, not touching component code. Directly applicable to any content-driven portfolio.
- **Loader animation pattern**: `src/components/loader.js` renders an SVG logo with CSS animation, then sets a `isLoading: false` state after a timeout. Combines with page-level `isMounted` state for entrance animation sequencing.
- **Animated nav hide/show on scroll**: The `nav` component uses `useScrollDirection` + `scrolledToTop` to apply `transform: translateY(-100px)` when scrolling down and restore when scrolling up. CSS transition handles the animation.

### Architecture reference
- Section decomposition: each `src/components/sections/*.js` file exports a single section component with its own styled-component definitions. Sections are composed in `src/pages/index.js`. Flat, readable structure with no nested routing complexity.
- Theme object structure: `src/styles/theme.js` combines colors, fonts, transitions, breakpoints, and mixins into a single theme object passed to `ThemeProvider`.

### Reference-only
- `content/projects/*.md` — Personal project descriptions (Brittany's actual work, not reusable)
- `static/resume.pdf` — Her resume
- `src/fonts/` — Licensed fonts (Calibre, SF Mono) — not redistributable
- `gatsby-config.js` — Site-specific Gatsby plugin configuration

## Evaluation
**Problem solved**: Personal developer portfolio with animated entrance reveals, markdown-driven content, dark navy aesthetic, clean typography, and mobile responsiveness.
**Original value**: Low-to-medium. The patterns (Gatsby SSG, styled-components, ScrollReveal, content in markdown) are all well-established. What is well-executed here is the consistency of the mixin system, the accessibility hook, and the content-data separation. The v4 design is widely recognized and copied — the implementation is sound but not novel.
**Future project types**: Developer portfolio, personal site, agency landing page, product feature showcase. The section decomposition pattern is applicable to any single-page marketing site.
**Do not copy**: Personal content, resume, font files, or design identity. The mixin system is generic; the sections are personal.
**Risks**: Gatsby 3 is now two major versions behind (Gatsby 5 is current). ScrollReveal may conflict with some React 18 hydration patterns. The site was last updated July 2024; Gatsby is no longer the preferred static site generator for most new projects.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 4 |
| Originality | 2 |
| General usefulness | 4 |
| Architecture | 3 |
| Design and UX | 5 |
| Accessibility | 4 |
| Performance | 3 |
| Testing | 1 |
| Documentation | 3 |
| Maintenance health | 3 |
| Licensing clarity | 4 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
