# raunofreiberg/interfaces

## Identity
- **Owner**: raunofreiberg
- **Repository**: interfaces
- **URL**: https://github.com/raunofreiberg/interfaces
- **Live URL**: https://rauno.me/interfaces
- **Commit SHA**: 81f523a5b469ba1ea877fef262588f3b4b65d31f
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: stale (last push September 2023)
- **Transferred**: no

## Relationship classification
visual-or-technical-experiment

Evidence: A curated collection of interaction design experiments with accompanying written analysis. Not a library or component package. No npm package. Deployed as a public-facing website.

## Licensing
- **Code license**: none (no LICENSE file)
- **Attribution required**: yes (assume all rights reserved without explicit license)
- **Asset restrictions**: custom fonts in /public (X-Regular, X-Medium) — likely proprietary
- **Reuse verdict**: study only

## Technical profile
- **Languages**: JavaScript (Next.js App Router)
- **Frameworks**: Next.js (App Router), MDX for content
- **Key dependencies**: next-mdx-remote, gray-matter, rehype-slug, rehype-autolink-headings, remark-gfm
- **Build system**: Next.js
- **Tests**: no
- **Architecture**: MDX-driven content with React components embedded. Each "interaction detail" is a MDX page with live interactive React components. The app/page.js file renders the main index using MDX Remote (RSC). Visual chrome includes gradient fade overlays, ruled lines, and custom `Line` and `VerticalFade` components for the typographic layout.
- **Rendering model**: Next.js App Router (React Server Components + Client Components)

## Useful content (exact files)

### Adaptable patterns
- **Gradient edge fades** (`app/page.js`): `VerticalFade` and `HorizontalFade` components create smooth gradient fades at the edges of scrollable content — CSS `background: linear-gradient()` from transparent to background color.
- **Ruled line decorative elements** (`app/page.js`): `Line` component with `direction` and `variant` props for typographic rule decoration.
- **MDX with RSC** (`app/page.js`): Pattern for loading MDX content server-side and rendering it with custom components using `next-mdx-remote/rsc`.

### Reference-only
- All interaction experiments (the MDX content pages) — these document the *principles* behind good interactions: fluid number inputs, spring animations, cursor effects, tactile button feedback. Read as design research, not as copy-paste code.
- The site itself at rauno.me/interfaces is the canonical form — the repo is the source.

## Evaluation
**Problem solved**: Documents what makes interfaces feel "right" — a curated, argued collection of interaction patterns with live demos. Value is primarily as design research and inspiration, not as a code library.

**Original value**: The written analysis accompanying each demo distinguishes this from a typical component showcase. Rauno articulates *why* each interaction works, not just what it looks like. This is rare in public repos.

**Future project types**: Reference when designing any interaction-heavy UI: hover states, number inputs, button feedback, animation timing. Use as an argument for design decisions, not as copy-paste source.

**Do not copy**: No license — do not copy any code from this repo without permission. The fonts in `/public/` are almost certainly proprietary.

**Risks**: No license means all rights reserved by default. The repo appears to be the deployment source for a personal website, and the author may not have intended for the code to be reused.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 5 |
| General usefulness | 3 |
| Architecture | 3 |
| Design and UX | 5 |
| Accessibility | 2 |
| Performance | 3 |
| Testing | 1 |
| Documentation | 4 |
| Maintenance health | 2 |
| Licensing clarity | 1 |
| Long-term lab value | 4 |

**Priority**: medium
**Action**: reference-only
