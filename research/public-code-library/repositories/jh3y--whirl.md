# jh3y/whirl

## Identity
- **Owner**: jh3y
- **Repository**: whirl
- **URL**: https://github.com/jh3y/whirl
- **Live URL**: https://whirl.netlify.app
- **Commit SHA**: a43ebd524ca890e87d651e2530c5596ab1f0a563
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: stale (last push January 2023)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Published as npm package. Structured as individual SCSS partials with a CLI scaffolding system. 1,832 stars.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: SCSS
- **Frameworks**: none (outputs plain CSS)
- **Key dependencies**: PostCSS, Babel (build only)
- **Build system**: npm scripts + PostCSS
- **Tests**: no
- **Architecture**: Each animation lives in a separate SCSS file under `src/whirls/` (e.g., `scaling-dots.scss`, `infinity-path.scss`, `battery.scss`, `mexican-wave.scss`). A main `src/index.scss` imports them all. A CLI utility (`utils/create-whirl.js`) scaffolds new animation templates. `utils/add-whirl.js` and `utils/order-config.js` manage the registry. The SCSS compiles to plain CSS for distribution.
- **Rendering model**: pure CSS

## Useful content (exact files)

### Directly reusable code
- `src/whirls/*.scss` — Individual animation files. Each is a self-contained SCSS partial that can be extracted and compiled standalone.
- Any specific whirl can be extracted: e.g., `infinity-path.scss` for an infinity loop animation, `battery.scss` for a battery fill animation, `mexican-wave.scss` for a wave stagger.

### Adaptable patterns
- **SCSS partial per animation**: Each animation is a complete, self-contained unit. Good reference for how to structure a CSS animation library for tree-shaking/selective import.
- **CSS `animation-delay` stagger patterns**: Multiple variants of nth-child stagger with different timing ratios — more varied than SpinKit.

### Architecture reference
- `utils/create-whirl.js` — CLI scaffolding template: generates a new SCSS file with the correct structure. Worth referencing as a pattern for library scaffolding tools.

### Reference-only
- `public/` — Built static site for the live demo

## Evaluation
**Problem solved**: More comprehensive CSS loading animation collection than SpinKit, with more unusual/creative patterns (battery, infinity path, Mexican wave, rotating rings, sliding tiles).

**Original value**: The diversity of animation approaches is higher than SpinKit. Some patterns (battery level fill animation, split animation) are genuinely creative and not commonly found elsewhere. Value is primarily as a visual inspiration and direct CSS copy source.

**Future project types**: Loading states, progress indicators, playful UI flourishes. More suitable for product-style UIs where SpinKit feels too plain.

**Do not copy**: The SCSS partials reference SCSS variables that need the parent index.scss context. If extracting individual animations, convert to plain CSS first or ensure SCSS variable resolution.

**Risks**: Stale since 2023. No tests. No formal CSS output (SCSS source only) — need to compile before use. Browser compatibility is universally good for all used CSS animation techniques.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 3 |
| Originality | 4 |
| General usefulness | 4 |
| Architecture | 3 |
| Design and UX | 4 |
| Accessibility | 1 |
| Performance | 4 |
| Testing | 1 |
| Documentation | 3 |
| Maintenance health | 2 |
| Licensing clarity | 5 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
