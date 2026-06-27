# Tobias Ahlin

**GitHub**: tobiasahlin
**Website**: https://tobiasahlin.com
**Analysis date**: 2026-06-27

## Profile overview
Tobias Ahlin is a Swedish designer and engineer, formerly at GitHub and Spotify. His public repos are historically important CSS animation resources that have influenced web design broadly. His output is small and focused on CSS motion design — SpinKit in particular is one of the most-starred pure-CSS repositories ever created.

## Repository inventory
| Repository | Stars | Language | Status | Decision |
|---|---|---|---|---|
| SpinKit | 19,347 | CSS | Unmaintained (last push 2020) | RETAIN (reference) |
| moving-letters | 571 | HTML | Unmaintained (last push 2020) | RETAIN (reference) |
| bendable | 73 | CSS | Stale (last push 2023) | REFERENCE |
| animated-menu | 72 | CSS | Unmaintained (last push 2016) | REJECT (trivial) |
| infinite-jekyll | 229 | JavaScript | Stale | REJECT (Jekyll-specific) |
| docs | 0 | MDX | Stale | REJECT |
| three.js | 6 | — | Fork | REJECT |

## Strongest repositories

**SpinKit** — 19,347 stars. The most comprehensive pure-CSS loading spinner collection. Single CSS file (spinkit.css), uses CSS custom properties (`--sk-size`, `--sk-color`) for theming, 10 distinct animation patterns. Historical benchmark for CSS animation quality. Not actively maintained but the CSS is timeless — browser support is now universal for all techniques used. MIT licensed. Value is as visual reference and direct CSS copy-paste source.

**moving-letters** — 16 self-contained HTML files, each demonstrating a distinct text animation pattern using anime.js. Each file is a complete, runnable experiment. No build step, no dependencies beyond CDN-loaded anime.js. Value is as animation pattern reference — specific easing curves, stagger timing, and per-character animation approaches are worth studying. MIT licensed.

**bendable** — CSS utility library for fluid/adaptive typography. Uses CSS clamp()-based fluid type scales with composable utility classes. Small (73 stars) but technically current — CSS clamp() is now widely supported. MIT licensed.

## Key findings
Tobias's work is primarily visual inspiration and CSS reference material. SpinKit is the most historically significant — its CSS patterns have been widely copied and adapted. None of these repositories are "reusable libraries" in the software engineering sense; they are CSS demonstrations and snippets. The correct classification for all of them is reference-only or visual inspiration.

## Rejected repositories
- **animated-menu** — Three CSS hamburger-to-X animations from 2016. Trivial implementation, no novel technique
- **infinite-jekyll** — Jekyll plugin, highly specific to static site generator
- **docs** — Empty MDX repo
- **three.js fork** — Not original work
