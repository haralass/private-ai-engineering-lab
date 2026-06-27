# Paco Coursey

**GitHub**: pacocoursey
**Website**: https://paco.me
**Analysis date**: 2026-06-27

## Profile overview
Paco Coursey is a front-end engineer and interface designer known for minimal, polished web tools and React libraries. He is best known for creating cmdk (the command menu React component, now under the `dip` org), and next-themes. His work is characterized by deep attention to constraint-driven architecture, zero-dependency preferences, and clean implementation under unusual complexity — particularly around component composition and rendering correctness in concurrent React.

## Repository inventory
| Repository | Type | Stars | Status | Decision |
|-----------|------|-------|--------|----------|
| next-themes | Original | 6,295 | Active | Clone |
| writer | Original | 579 | Unmaintained (2021) | Reference |
| xslt | Original | 323 | Active (2025) | Reference |
| Opus | Original | 313 | Active | Reference |
| paco | Original | 316 | Archived (2022) | Clone (personal site) |
| next-unused | Original | 423 | Archived | Reject |
| use-descendants | Original | 114 | Archived | Reject |
| use-delayed-render | Original | 168 | Active | Reference |
| state | Original | 125 | Unmaintained | Reject |
| Dusk | Original | 170 | Active | Reject (icon assets) |
| thoughtless | Original | 39 | Active | Reject |
| fable | Original | 4 | Active (2025) | Reject |
| next.js | Fork | 1 | — | Reject |
| styled-jsx-system | Original | 12 | Unmaintained | Reject |
| svgcache | Original | 16 | Old demo | Reject |
| next-cascade-layers | Original | 6 | Active | Reject |
| dusk-react | Original | 8 | Unmaintained | Reject |

Note: `cmdk` lives at `dip/cmdk` — Paco is the original author, the repo moved to the `dip` GitHub org. Cloned under `dip` attribution.

## Original vs forks vs transferred
- All repositories above are original works by Paco Coursey, except `next.js` (fork of vercel/next.js).
- `cmdk` is original but now maintained under the `dip` org. The `pacocoursey/cmdk` name does not exist; the canonical home is `dip/cmdk`.
- `paco` (personal site) is archived but represents a full working Next.js + MDX site.

## Strongest repositories
- **cmdk** (dip/cmdk): The definitive command palette React component. 12,701 stars. Compound component architecture, custom fuzzy scoring, ARIA-compliant, Radix primitives. The `ARCHITECTURE.md` is one of the best documented explanations of React compound component trade-offs ever written. Cloned.
- **next-themes**: The standard dark mode library for Next.js. 6,295 stars. Small (263 lines), handles SSR flash prevention via inline script injection, supports `data-*` and `class` attributes, system preference detection. Cloned.
- **paco** (personal site): Archived but contains clean Next.js + Tailwind + MDX patterns including custom markdown rendering, RSS generation, and view count tracking. Cloned.
- **use-delayed-render**: A genuinely useful React hook for delaying mount/unmount around CSS animations. 168 stars, no dependencies. Reference only.
- **writer**: Minimal plain-text editor. Reference only — interesting for simplicity study.

## Key findings
What distinguishes Paco's work is intellectual rigor about component architecture under real constraints. The `cmdk` ARCHITECTURE.md documents multi-year decisions about why `React.Children` iteration was rejected, why render props were avoided, and how DOM order became the source of truth for selection state. His libraries tend to be tiny, dependency-light, and solve one problem correctly. `next-themes` in particular is a solved-once-and-done implementation that every serious Next.js project reaches for.

## Rejected repositories
- `next-unused` — Archived, superseded by built-in tools and Knip
- `use-descendants` — Archived, abandoned due to React Strict Mode incompatibility (documented in cmdk ARCHITECTURE.md)
- `state` — Unmaintained experiment merging Zustand/SWR/Valtio concepts
- `fable` — Too early-stage (4 stars)
- `next-cascade-layers` — Narrow CSS Layers utility
- `Dusk`/`dusk-react` — Custom icon set, no code value for the lab
- `thoughtless` — Personal productivity app, narrow utility
- `styled-jsx-system` — Superseded, unmaintained
- `svgcache` / `next.js` (fork) — Demo and fork respectively
