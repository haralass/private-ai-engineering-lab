# Archived and Unmaintained Report
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

---

## Officially Archived

These repositories have been explicitly archived by their owners (GitHub "Archived" status — read-only, no more contributions accepted).

| Repository | Owner | Archived | Stars | Last Push | Lab Decision |
|-----------|-------|---------|-------|----------|--------------|
| pacocoursey/paco | Paco Coursey | yes | 316 | 2022-04-04 | reject — archived personal site, no license |

---

## Unmaintained or Stale (No Archive, But No Active Development)

These repositories are not officially archived but have not received meaningful updates in over 12 months (from 2026-06-27 perspective).

### High Staleness Risk (Last push 3+ years ago)

| Repository | Last Push | Language | Lab Decision | Notes |
|-----------|----------|---------|--------------|-------|
| tobiasahlin/animated-menu | 2016-05-03 | CSS/JS | low value | 10 years old; jQuery; concept only |
| LeaVerou/stretchy | 2023-12-15 | JavaScript | medium | Concept still valid; code is ES5 |
| raunofreiberg/inspx | 2023-02-01 | TypeScript | medium | Unmaintained; patterns are reference |
| pacocoursey/writer | 2021-10-28 | JavaScript | medium | No license; canvas editor concept only |
| tobiasahlin/moving-letters | 2020-10-01 | JavaScript | medium | Animejs-based; concept still valid |
| TobiasAhlin/SpinKit | 2020-08-01 | CSS | high | CSS animations are perennial; code still valid |
| raunofreiberg/interfaces | 2023-09-07 | JavaScript | high | UX principles are timeless even if repo is stale |
| antfu/vue-starport | 2023-09-14 | TypeScript | medium | FLIP concept is the value |
| tobiasahlin/bendable | 2023-09-22 | CSS | high | CSS custom properties are stable |
| LeaVerou/awesomplete | 2024-07-25 | JavaScript | high | Production-proven; no framework dependency ages well |

### Moderate Staleness Risk (Last push 1–2 years ago)

| Repository | Last Push | Lab Decision | Notes |
|-----------|----------|--------------|-------|
| maxboeck/mxb | 2025-01-26 | low | Eleventy site; slowing activity |
| sindresorhus/LaunchAtLogin-Modern | 2024-01-11 | high | macOS API is stable; library still works |
| bchiang7/spotify-profile | 2023-01-04 | medium | Spotify API changes may have broken it |
| bchiang7/octoprofile | 2023-01-05 | low | GitHub API-based demo; may be outdated |
| MaximeHeckel/linear-vaporwave-react-three-fiber | 2022-11-16 | low | R3F API changes; stale |
| jh3y/whirl | 2023-01-03 | medium | CSS animations age well |
| jh3y/meanderer | 2023-01-05 | medium | Vanilla JS; stable |
| maxboeck/whimsical | 2023-05-10 | low | Curated list; live site is more current |
| taniarascia/taniarascia.com | 2026-05-27 | low | Gatsby (declining framework) |
| taniarascia/takenote | 2024-06-29 | high | Core Redux/React patterns still valid |

---

## Marked Unmaintained by Author

### emilkowalski/vaul
- **Status**: The repository README states the project is unmaintained
- **Last Push**: 2025-10-03
- **Stars**: 8,427
- **Lab Decision**: reference-only — the snap-point and velocity fling algorithms are valuable but the library should not be adopted as a dependency
- **Note**: As of 2026-06-27, the README unmaintained notice should be verified in the cloned source, as status may have changed

---

## Deprecated Dependencies

### MaximeHeckel/design-system
- Uses **Stitches** CSS-in-JS, which was officially deprecated in 2023
- Stitches' maintainer moved to Panda CSS
- **Impact**: The component code in this design-system cannot be adopted without migrating away from Stitches
- **Lab Decision**: Study the patterns and token architecture; do not adopt the Stitches dependency

---

## Recommendations for Stale High-Value Repositories

| Repository | Recommended Action |
|-----------|-------------------|
| TobiasAhlin/SpinKit | Safe to use — pure CSS animations are forward-compatible |
| tobiasahlin/bendable | Safe to use — CSS custom properties are forward-compatible |
| LeaVerou/awesomplete | Safe to use for non-framework projects; test with current browsers |
| raunofreiberg/interfaces | Study as UX documentation; code is not the value |
| taniarascia/takenote | Study Redux + TypeScript patterns; do not adopt the full app |
| antfu/vue-starport | Study FLIP concept; Vue-specific code needs adapting |
| pacocoursey/writer | Study canvas editor architecture; no license so re-implement |

---

## What Was Rejected Due to Archive/Unmaintained Status

The following repositories from the approved list were rejected specifically because of archive or unmaintained status, combined with low residual value:

1. **pacocoursey/paco** — archived personal website, no license, no novel patterns
2. **tobiasahlin/animated-menu** — 10-year-old jQuery tutorial; concept is better found in modern references
