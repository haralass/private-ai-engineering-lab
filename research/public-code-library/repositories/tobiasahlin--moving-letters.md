# tobiasahlin/moving-letters

## Identity
- **Owner**: tobiasahlin
- **Repository**: moving-letters
- **URL**: https://github.com/tobiasahlin/moving-letters
- **Live URL**: https://tobiasahlin.com/moving-letters/
- **Commit SHA**: 57b2ec66f59894b42030bc707747247ec42b743b
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: unmaintained (last push October 2020)
- **Transferred**: no

## Relationship classification
visual-or-technical-experiment

Evidence: A collection of 16 self-contained HTML files, each a standalone text animation demo. No package structure, no exports, no API. Not designed for import — designed to be read and adapted.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable (copy-paste the animation snippets)

## Technical profile
- **Languages**: HTML + JavaScript (inline)
- **Frameworks**: anime.js (CDN-loaded)
- **Key dependencies**: anime.js (CDN)
- **Build system**: none
- **Tests**: no
- **Architecture**: 16 standalone `.html` files. Each file: (1) contains a headline text element with letters split into individual `<span>` elements (via JS or pre-rendered), (2) uses anime.js to animate per-letter or per-word using `anime.timeline()` with staggered `anime.stagger()` delays.
- **Rendering model**: static HTML + vanilla JS

## Useful content (exact files)

### Adaptable patterns
- **Letter splitting** (various files): Splitting text into individual `<span>` elements per character before animating — the prerequisite for per-character animation. The pattern (split on `''`, wrap in spans) is copy-pasteable.
- **anime.js timeline with stagger** (all files): `anime.timeline({ loop: true }).add({ targets: '.element span', translateY: [-100, 0], delay: anime.stagger(50) })` — demonstrates different easing curves and stagger timing values for different animation feels.
- **Specific patterns per file**:
  - `01-thursday.html` — Slide up with fade, staggered, looping
  - `02-slow-mornings.html` — Letter rotation with perspective
  - `03-great-thinkers.html` — Word-level fade with blur
  - `05-signal-and-noise.html` — Elastic overshoot
  - `08-hey.html` — Scale + opacity cascade

### Reference-only
- The full collection at tobiasahlin.com/moving-letters/ is the canonical reference — better to visit the live site where each animation plays continuously.

## Evaluation
**Problem solved**: Provides ready-to-study examples of text animation patterns: slide, fade, scale, rotate, elastic — all parameterized as anime.js configs that can be directly adapted.

**Original value**: Not technically novel (anime.js does the heavy lifting) but curatorially valuable: these are the "right" timing values for each animation type, arrived at through aesthetic judgment. The specific duration/delay/easing combinations are the value, not the code structure.

**Future project types**: Marketing pages, hero sections, loading screens, any context needing animated typographic entrance effects.

**Do not copy**: anime.js is loaded from CDN in each file. For modern projects, install anime.js via npm and adapt the animation configs. Do not copy the HTML file structure directly.

**Risks**: anime.js v3 API (used here) has an incompatible v4 API. Verify anime.js version when adapting these configs.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 3 |
| Originality | 3 |
| General usefulness | 3 |
| Architecture | 2 |
| Design and UX | 5 |
| Accessibility | 1 |
| Performance | 3 |
| Testing | 1 |
| Documentation | 3 |
| Maintenance health | 1 |
| Licensing clarity | 5 |
| Long-term lab value | 3 |

**Priority**: low
**Action**: reference-only
