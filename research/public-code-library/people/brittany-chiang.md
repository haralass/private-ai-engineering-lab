# Brittany Chiang

**GitHub**: bchiang7
**Website**: https://brittanychiang.com
**Analysis date**: 2026-06-27

## Profile overview
Brittany Chiang is a software engineer and front-end developer, formerly at Upstatement, known for clean, visually polished portfolio sites and React applications. She is best known for her v4 portfolio (8,200+ stars), which has become one of the most-referenced developer portfolio templates on GitHub. She also created the Halcyon VS Code theme and several well-implemented React apps integrating external APIs (Spotify, GitHub). Her work exemplifies systematic styled-components usage with scroll-driven animations.

## Repository inventory
| Repository | Type | Stars | Status | Decision |
|-----------|------|-------|--------|----------|
| v4 | Original | 8,262 | Active | Clone |
| spotify-profile | Original | 720 | Active (2023) | Clone |
| bchiang7.github.io | Original | 849 | Active | Reference |
| halcyon-vscode | Original | 238 | Active | Reference |
| octoprofile | Original | 501 | Active | Reference |
| halcyon-site | Original | 55 | Active | Reference |
| halcyon-iterm | Original | 65 | Active | Reference |
| dotfiles | Original | 56 | Active | Reject |
| time-to-have-more-fun | Original | 77 | Active | Reject |
| google-keep-vue-firebase | Original | 60 | Active | Reject |
| v3 | Original | 57 | Active | Reject |
| v1, v2 | Original | 53, 32 | Active | Reject |
| react-profile | Original | 49 | Active | Reject |
| CS3200-Project | Original | 8 | Active | Reject |
| pin-simple | Original | 4 | Active | Reject |
| Halcyon (Sublime) | Original | 25 | Active | Reject |
| halcyon-atom-syntax | Original | 3 | Active | Reject |
| halcyon-hyper | Original | 8 | MIT | Reject |
| react-boilerplate | Original | 18 | Unmaintained | Reject |
| eleventy-base | Original | 9 | Active | Reject |
| All forks | Fork | — | — | All rejected |

## Original vs forks vs transferred
The substantial original repos are v4, spotify-profile, bchiang7.github.io, octoprofile, and the Halcyon theme family. Forks present (react-resizable-panels, intl-tel-input, publish-extensions, Syntax, Advanced-React, Learn-Node, gatsby, etc.) are all contributions to upstream projects or course materials — all rejected.

## Strongest repositories
- **v4**: The most-starred developer portfolio on GitHub in its category. Built with Gatsby + styled-components + ScrollReveal + anime.js. Full section structure (hero, about, jobs, featured projects, other projects, contact), with two custom hooks (`useScrollDirection`, `usePrefersReducedMotion`). Systematic styled-components mixin system (`theme.mixins.*`), CSS custom properties for theming, content-driven via markdown files. Honest assessment: highly cloned, not unique code, but the implementation patterns are clean and worth studying. Cloned.
- **spotify-profile**: React + Express full-stack Spotify API integration. OAuth flow, data visualization with Chart.js, displays listening history, top tracks/artists, playlist analysis. No license file in root (package.json says ISC). Reference for Spotify OAuth + data visualization patterns. Cloned for reference despite ISC.
- **octoprofile**: GitHub profile visualizer with Next.js and GitHub API. Reference only (501 stars).
- **halcyon-vscode**: MIT-licensed dark blue VS Code theme. Active and maintained (last push April 2026). Reference only.

## Key findings
Brittany's v4 portfolio is broadly copied, which dilutes its originality rating — but the actual implementation quality is high. The styled-components mixin system in `src/styles/mixins.js` is worth extracting as a pattern: reusable flex utilities, button resets, link styling, and responsive helpers all defined as template literal mixins consumed via `${({ theme }) => theme.mixins.flexCenter}`. The `useScrollDirection` hook is clean and practical. The `usePrefersReducedMotion` hook is the correct way to respect accessibility preferences in JS animations. None of this is novel, but it is all done correctly.

`spotify-profile` is the most technically interesting repo: it demonstrates the full Spotify OAuth PKCE-less flow (auth code + backend token swap), API pagination, track audio feature radar charts, and a clean client/server split.

## Rejected repositories
- All older portfolio versions (v1, v2, v3) — superseded by v4
- `react-profile` — Simple HTML/React resume, no depth
- `google-keep-vue-firebase` — Vue/Firebase clone, educational
- `time-to-have-more-fun` — Personal Next.js/Firebase toy
- `dotfiles` — Shell dotfiles, not code
- `halcyon-atom-syntax`, `halcyon-hyper`, `Halcyon` (Sublime) — Theme format ports
- All forks — upstream contributions, not original work
- `CS3200-Project`, `WebDevSpring2016`, `Calendr`, etc. — University coursework
- `react-boilerplate` — Outdated boilerplate
- `eleventy-base`, `nativescript-*` — Personal experiments
