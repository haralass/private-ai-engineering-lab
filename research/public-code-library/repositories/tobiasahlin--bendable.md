# tobiasahlin/bendable

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | tobiasahlin |
| Repository | bendable |
| URL | https://github.com/tobiasahlin/bendable |
| Live URL | none (CSS library, no demo hosted) |
| Commit SHA | a1c2c6c40ba7c1f1e9a8dbd62b04e7ac3c5c9d18 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active (last push September 2023) |
| Last Meaningful Push | 2023-09-22 |

## Legal Status

| Field | Value |
|-------|-------|
| License | MIT |
| Attribution Required | yes |
| Code Reuse | clearly permitted |
| Reference-Only | no |

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | CSS |
| Framework | none (pure CSS) |
| Major Dependencies | none |
| Build System | none (single file) |
| Test System | none |
| Repository Structure | single file: `bendable.css`, `playground.html`, `README.md` |
| Architecture | Single CSS file providing smooth, physics-informed easing functions as CSS custom properties |

## Actual Valuable Content

### The Single File: `bendable.css`

This is the entire repository's value: a set of named CSS easing custom properties providing alternatives to the standard `linear`, `ease`, `ease-in`, `ease-out`, `ease-in-out` functions.

Named easings follow `--ease-{category}-{n}` convention:
- Sine easings (gentle S-curves)
- Quad, Cubic, Quart, Quint (polynomial)
- Expo, Circ (exponential, circular)
- Back (overshoot — the key differentiator from CSS standard)
- Elastic (spring-like bounce)
- Bounce

The CSS custom properties use `cubic-bezier()` or `linear()` functions (CSS `linear()` is a newer spec allowing multi-stop easing).

The `playground.html` shows all easings side by side, animated on hover, with labeled comparison — useful for selecting the right easing visually.

## Value Classification

| Item | Classification |
|------|---------------|
| `bendable.css` easing library | directly reusable code |
| Easing naming convention | adaptable implementation pattern |
| `playground.html` easing comparison | UX reference |

## General Usefulness

**Problem it solves**: CSS `cubic-bezier()` functions for common animation patterns are difficult to remember and hard to compare. This library gives them semantic names and makes them copyable as CSS custom properties.

**Why the implementation is notable**: It uses the newer CSS `linear()` function for complex easings (elastic, bounce) that were previously impossible in pure CSS without JS. This makes complex, physically-informed animations achievable in CSS alone.

**Future project uses**:
- Drop `bendable.css` into any project that uses CSS transitions/animations
- Named easing custom properties are immediately usable in any CSS-in-JS or PostCSS setup
- Playground is a useful reference for picking the right easing for a given animation feel

**Important caveat**: CSS `linear()` easing is supported in Chrome 113+, Firefox 112+, Safari 17.2+. Older browser support requires JS polyfill or fallback. As of 2026, this is broadly supported.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 5/5 | Single well-named CSS file, clean convention |
| Originality | 4/5 | Semantic naming + CSS linear() is a distinctive combination |
| General Reusability | 5/5 | Drop-in single file, zero dependencies |
| Educational Value | 4/5 | Good reference for easing functions |
| Dependency Risk | 5/5 | Zero dependencies |
| Licensing Clarity | 5/5 | Clear MIT |
| Long-term Usefulness | 5/5 | CSS easing patterns are perennial |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 5 |
| Originality | 4 |
| General Reusability | 5 |
| Educational Value | 4 |
| Design / UX Quality | 4 |
| Architecture Quality | 5 |
| Documentation Quality | 3 |
| Maintenance Health | 3 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 5 |

**Final Priority**: high  
**Recommended Action**: reference-only (single file — copy `bendable.css` directly when needed; too small to clone)
