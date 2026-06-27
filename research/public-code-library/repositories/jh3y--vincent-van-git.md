# jh3y/vincent-van-git

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | jh3y |
| Repository | vincent-van-git |
| URL | https://github.com/jh3y/vincent-van-git |
| Live URL | https://vincent-van-git.netlify.app |
| Commit SHA | 801e87a4e2235c5a39e8b1f14bde68cd7c374f81 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active (last push December 2025) |
| Last Meaningful Push | 2025-12-29 |

## Legal Status

| Field | Value |
|-------|-------|
| License | NONE |
| Attribution Required | unknown |
| Code Reuse | reference-only |
| Reference-Only | yes — no license file |

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | JavaScript |
| Framework | React (with Storybook MDX for component docs) |
| Major Dependencies | React, Storybook, Netlify functions |
| Build System | React (CRA-style) + Storybook |
| Test System | none |
| Repository Structure | `src/shared/` (components, assets), `src/shared/components/commit-grid/`, `functions/vincent/` (Netlify function) |
| Architecture | Visual Git commit graph painter — draws on GitHub's contribution graph grid to create pixel art. Netlify function generates the actual Git commit history needed to produce the desired visual pattern on GitHub's contribution heatmap. |

## Actual Valuable Content

### The Core Concept: Contribution Graph Art Generator

The app lets users "paint" a picture on a grid (matching GitHub's 52×7 contribution graph layout), then generates a script (via the Netlify function) that creates the Git commits needed to produce that pattern on their GitHub profile.

**`src/shared/components/commit-grid/`** — Grid component representing the GitHub contribution graph. Allows painting cells by clicking/dragging.

**`functions/vincent/vincent.js`** — Netlify function that:
1. Accepts the painted grid as input (which cells should be "lit")
2. Maps each cell to a specific week/day in the past
3. Generates a shell script with `git commit --date` commands to backdate commits and produce the desired heat pattern on GitHub

### Audio/Visual Interaction Patterns

**`src/shared/components/actions/`** — Action toolbar with audio feedback (brush stroke sounds).

**`src/shared/assets/audio/`** — `brush-stroke.mp3`, `click.mp3`, `sparkle.mp3`, `trumpet-fanfare.mp3` — spatial audio feedback for interactions.

The audio feedback integration is a subtle creative interaction pattern: haptic-like sound confirmation for each painted cell.

## Value Classification

| Item | Classification |
|------|---------------|
| Commit graph date mapping algorithm | adaptable implementation pattern |
| Draggable grid painter (commit-grid) | adaptable implementation pattern |
| Audio feedback on canvas interactions | UX reference |
| Git backdate commit generation | developer-tooling reference |
| No license | reference-only |

## General Usefulness

**Problem it solves**: Creates pixel art on GitHub's contribution graph by generating backdated commits.

**Why the implementation is notable**: The date mapping algorithm (week/day offset from today → ISO date string for `git commit --date`) is a clever concrete implementation. The interactive canvas painter with drag-select is a clean reference for grid-painting UIs. The audio feedback creates delight.

**Future project uses**:
- Grid painting UI pattern (applicable to any pixel-art or grid selection UI)
- Audio-augmented interaction pattern
- The backdated commit generation is a creative developer tool concept

**Should not retain**: The concept encourages misleading GitHub contribution graphs. The technical pattern is sound; the application is frivolous/potentially misleading.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 3/5 | Functional React with Storybook |
| Originality | 4/5 | Creative application of git internals |
| General Reusability | 3/5 | Grid painter + audio patterns are reusable concepts |
| Implementation Age | 3/5 | Updated through 2025 |
| Licensing Clarity | 1/5 | No license |
| Long-term Usefulness | 2/5 | Niche; the interaction patterns are more valuable than the app |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 3 |
| Originality | 4 |
| General Reusability | 2 |
| Educational Value | 3 |
| Design / UX Quality | 4 |
| Architecture Quality | 3 |
| Documentation Quality | 2 |
| Maintenance Health | 3 |
| Licensing Clarity | 1 |
| Long-term Lab Value | 2 |

**Final Priority**: low  
**Recommended Action**: metadata-only (no license; niche concept; grid-painter and audio patterns are the only reusable elements)
