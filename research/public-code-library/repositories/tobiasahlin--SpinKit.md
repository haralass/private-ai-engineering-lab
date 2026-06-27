# tobiasahlin/SpinKit

## Identity
- **Owner**: tobiasahlin
- **Repository**: SpinKit
- **URL**: https://github.com/tobiasahlin/SpinKit
- **Live URL**: https://tobiasahlin.com/spinkit/
- **Commit SHA**: 742a71277c49b69053b5beb9fad80d720840a2ab
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: unmaintained (last push August 2020, but code is stable CSS)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Available as an npm package. Single CSS file with no JavaScript. The animations are CSS-only and require no build step. 19,347 stars — the most starred pure-CSS repository by any of the 5 surveyed developers.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: CSS
- **Frameworks**: none
- **Key dependencies**: none (zero)
- **Build system**: none required (precompiled CSS distributed)
- **Tests**: no
- **Architecture**: Single CSS file (`spinkit.css`, 437 lines). 10 loading spinner patterns: Plane, Chase, Bounce, Wave, Pulse, Flow, Swing, Circle, Circle-Fade, Grid, Fold, Wander. Each uses CSS `animation` with `@keyframes`, CSS custom properties for theming (`--sk-size: 40px`, `--sk-color: #333`). No JavaScript. HTML structures are minimal (1–6 divs per spinner). A prebuilt minified version (`spinkit.min.css`) is included.
- **Rendering model**: pure CSS (no DOM dependency, no JavaScript)

## Useful content (exact files)

### Directly reusable code
- `spinkit.css` — Full library (437 lines). Directly copy-paste any of the 10 spinner implementations.
- `spinkit.min.css` — Minified version for production use
- `examples.html` — All spinners demonstrated in a single HTML file

### Adaptable patterns
- **CSS custom property theming** (`:root` block in spinkit.css): `--sk-size` and `--sk-color` make all spinners theme-aware with two variables. Pattern for creating CSS animation utilities that are easily customizable.
- **`nth-child` staggered animation delays**: Each spinner that has multiple dots/elements uses `nth-child` selectors with negative animation delays to create stagger without JavaScript. Well-implemented reference for this technique.
- **Isolation via wrapper div**: Each spinner wraps all its elements in a single container div, keeping CSS specificity flat. Pattern for distributing CSS components that don't conflict with external styles.

### Architecture reference
- The `:root { --sk-size; --sk-color; }` theming pattern is a clean reference for how pure CSS libraries should expose customization points.

### Reference-only
- Nothing — everything is directly reusable CSS

## Evaluation
**Problem solved**: CSS-only loading indicators that require no JavaScript, no build step, and minimal HTML markup.

**Original value**: When published in 2013, CSS animations were new. SpinKit demonstrated that complex loading animations could be achieved without GIFs or JavaScript. The patterns are now standard technique but this was the teaching tool for a generation of developers. The CSS remains correct and usable today.

**Future project types**: Any project needing loading states — buttons, page transitions, data fetching indicators. The CSS custom property approach makes it trivial to theme per-use.

**Do not copy**: The spinners are designed for small sizes (default 40px). Scaling to very large sizes may require adjusting border-radius and perspective values. Not suitable as full-page loading overlays without wrapper CSS.

**Risks**: Unmaintained since 2020. No known bugs in the CSS itself. Browser compatibility for all used techniques is now universal. The only risk is if a future browser change breaks `animation-delay: -1.1s` (negative delays) — this has been stable for 12+ years.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 3 |
| General usefulness | 5 |
| Architecture | 4 |
| Design and UX | 4 |
| Accessibility | 2 |
| Performance | 5 |
| Testing | 1 |
| Documentation | 4 |
| Maintenance health | 2 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: high
**Action**: clone
