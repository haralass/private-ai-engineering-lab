# Jhey Tompkins

**GitHub**: jh3y
**Website**: https://jhey.dev
**Analysis date**: 2026-06-27

## Profile overview
Jhey Tompkins is a Google Chrome Developer Advocate and CSS animation specialist known primarily for his CodePen work. His GitHub repos are a mix of CSS animation libraries, creative experiments, and developer tools. He has the largest and most varied public footprint of the five people surveyed here, but most repos are demos or personal tools rather than reusable libraries.

## Repository inventory
| Repository | Stars | Language | Status | Decision |
|---|---|---|---|---|
| whirl | 1,832 | SCSS | Stale (2023) | RETAIN |
| meanderer | 224 | JavaScript | Stale (2023) | RETAIN |
| tyto | 678 | JavaScript | Stale (2023) | REJECT (app) |
| ep | 645 | JavaScript | Stale (2017) | REJECT (old) |
| driveway | 641 | HTML | Stale (2017) | REJECT (old) |
| vincent-van-git | 311 | JavaScript | Active (2025) | REFERENCE |
| kody | 140 | JavaScript | Stale | REJECT (dotfiles mgr) |
| a-guide-to-css-animation | 46 | CSS | Stale | REFERENCE |
| move-things-with-css | 21 | CSS | Stale | REFERENCE |
| stationery-cabinet | 17 | Pug | Stale | REJECT (codepen cache) |
| All Houdini demos | <10 | JS | Stale | REJECT (POC) |

## Strongest repositories

**whirl** — A CSS/SCSS loading animation library. 100+ distinct loading animation variants organized as individual SCSS partials (e.g., `scaling-dots.scss`, `infinity-path.scss`, `mexican-wave.scss`, `battery.scss`). Well-organized with a CLI scaffolding tool (`utils/create-whirl.js`). More comprehensive than SpinKit but less polished CSS. Useful as visual reference or direct CSS adaptation source. MIT licensed.

**meanderer** — A JavaScript micro-library (111 lines) for making SVG paths responsive. Takes a hard-coded SVG path string, samples it into data points using `SVGGeometryElement.getPointAtLength()`, then rescales those points to any container dimension using d3-scale and d3-shape. Solves a real problem (SVG paths are not responsive by nature) with a clean class-based API. MIT licensed.

## Key findings
Jhey's work is creative but primarily visual. His most technically reusable contribution is `meanderer`, which solves a concrete problem (responsive SVG motion paths) with a clean algorithm. `whirl` is useful as a CSS animation reference collection. The majority of his other repos are demos, personal tools, or Codepen experiments — they demonstrate CSS capability rather than providing reusable engineering. Honest assessment: his GitHub is more useful as inspiration than as a code library.

## Rejected repositories
- **tyto** — Task management app, not a library
- **ep** — HTML5 progress bar enhancer from 2017, uses jQuery, obsolete
- **driveway** — Pure CSS masonry from 2017, superseded by CSS grid
- **kody** — Personal dotfiles manager
- **stationery-cabinet** — Cache of CodePen source files (Pug templates)
- **vincent-van-git** — Creative GitHub contribution graph art tool; fun but no reuse value
- **Houdini POCs** (houdini-tesla, houdini-noise) — Browser support was experimental, no license
- **gulp-boilerplate**, **parcel-boilerplate**, **node-cli-boilerplate** — Old toolchain boilerplates
- All forks — Not original work
- All personal apps and tools without MIT license
