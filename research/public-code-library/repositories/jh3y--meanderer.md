# jh3y/meanderer

## Identity
- **Owner**: jh3y
- **Repository**: meanderer
- **URL**: https://github.com/jh3y/meanderer
- **Live URL**: (no live demo maintained)
- **Commit SHA**: cbb0f9a475dc730cadf26b50114253342e548879
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: stale (last push January 2023)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Published as npm package. Single class, ES module. MIT licensed. Solves a concrete, recurring problem with SVG motion paths.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: JavaScript (ES class, ES modules)
- **Frameworks**: none
- **Key dependencies**: d3-shape, d3-scale (for path scaling math)
- **Build system**: Rollup (multiple output targets: dev/prod/demo/deploy)
- **Tests**: no
- **Architecture**: Single `Meanderer` class (111 lines, `src/index.js`). Constructor accepts `{ path, height, width, threshold }`. Algorithm: (1) Convert SVG path string to data points using a temporary SVG element + `SVGPathElement.getTotalLength()` / `getPointAtLength(p)` loop; (2) Store maximums and calculate width/height ratios; (3) On `generatePath(containerWidth, containerHeight)`: create d3 linear scales mapping original path bounds to container bounds, with aspect-ratio-aware offset calculation to center paths that don't match container ratio; (4) Re-render scaled points as a new SVG path string using `d3-shape.line()`.
- **Rendering model**: DOM (uses document.createElement for path sampling), but outputs a string — can be used in any environment that supports the DOM API

## Useful content (exact files)

### Directly reusable code
- `src/index.js` — Complete library (111 lines). The `Meanderer` class and its `generatePath()` method.
- `playground/meanderer.js` — Bundled demo version (same code, for browser)

### Adaptable patterns
- **SVG path sampling via DOM** (`src/index.js` lines 45–62): Creating a temporary SVG element, inserting a path, and sampling it with `getPointAtLength()` at integer pixel intervals. This is the standard technique for converting SVG paths to data arrays — useful whenever SVG path math is needed.
- **Aspect-ratio-aware rescaling with centering offset** (`src/index.js` lines 68–108): When a path's aspect ratio doesn't match the container, compute a centering offset based on the ratio difference rather than stretching. Clean implementation of this non-obvious geometry.
- **d3-scale for domain-to-range mapping**: `scaleLinear().domain([0, maxWidth]).range([offset, containerWidth * ratio - offset])` — clean, readable pattern for linear coordinate transforms.

### Architecture reference
- The path → data → rescaled path pipeline: sample into data once (expensive, DOM operation), then `generatePath()` can be called repeatedly on resize (cheap, pure math). Good pattern for expensive-to-compute + cheap-to-update architectures.

### Reference-only
- `playground/script.js` — Demo interaction code

## Evaluation
**Problem solved**: SVG `offsetPath` animations are not responsive — the path is defined in absolute coordinates. Meanderer rescales any SVG path to fit any container dimension while preserving the path's proportions.

**Original value**: The combination of DOM-based path sampling + d3-scale rescaling + aspect-ratio-aware centering is non-obvious. The API is clean. This solves a problem that most developers either ignore (paths break on mobile) or solve badly (hard-coded viewBox manipulation).

**Future project types**: Any project using SVG-based CSS motion paths (`offset-path`): animated icons, particle paths, custom scroll indicators, animated UI decorations that follow curved paths.

**Do not copy**: The path sampling step (`getTotalLength()` + `getPointAtLength()` loop) is synchronous and potentially slow for very long or complex paths. For high-performance uses, consider caching the sampled data aggressively or doing the sampling off-thread.

**Risks**: Depends on d3-shape and d3-scale — 30KB added to bundle even though only two functions are used. In a modern bundler with tree-shaking this should reduce significantly. The path sampling step requires a DOM environment (browser or JSDOM). No tests.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 4 |
| General usefulness | 3 |
| Architecture | 4 |
| Design and UX | 3 |
| Accessibility | N/A |
| Performance | 3 |
| Testing | 1 |
| Documentation | 3 |
| Maintenance health | 2 |
| Licensing clarity | 5 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
