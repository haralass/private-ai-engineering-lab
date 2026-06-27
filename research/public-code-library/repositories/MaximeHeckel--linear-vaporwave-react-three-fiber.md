# MaximeHeckel/linear-vaporwave-react-three-fiber

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | MaximeHeckel |
| Repository | linear-vaporwave-react-three-fiber |
| URL | https://github.com/MaximeHeckel/linear-vaporwave-react-three-fiber |
| Live URL | N/A (demo from a blog post) |
| Commit SHA | 1bf94daa0999a68b4f27a2a63ae5e1c49ded8e37 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | stale (last push November 2022) |
| Last Meaningful Push | 2022-11-16 |

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
| Primary Language | JavaScript |
| Framework | Next.js + React Three Fiber |
| Major Dependencies | @react-three/fiber, @react-three/drei, three.js |
| Build System | Next.js |
| Test System | none |
| Repository Structure | `pages/` (Next.js), single scene file |
| Architecture | Single-page R3F scene replicating Linear's vaporwave aesthetic — wireframe terrain grid, displacement maps, metalness texture. |

## Actual Valuable Content

### The Scene Implementation

**`pages/index.js`** (the main file) — Implements a WebGL scene with:
- Displacement map terrain: a `PlaneGeometry` mesh with vertex shader displacement driven by a PNG texture.
- Metalness/roughness: uses a custom texture for the metalness map on the terrain.
- Grid texture: creates the vaporwave wireframe grid effect using a custom texture.
- Post-processing: bloom glow effect on glowing elements.
- Fog: volumetric fog depth cue.

**`public/displacement-7.png`**, **`public/grid-6.png`**, **`public/metalness-2.png`** — The texture assets used in the scene.

## Value Classification

| Item | Classification |
|------|---------------|
| Displacement map terrain in R3F | adaptable implementation pattern |
| Vaporwave texture pipeline (displacement + metalness + grid) | visual inspiration only |
| Post-processing bloom in R3F | adaptable implementation pattern |
| Texture assets | visual inspiration only |

## General Usefulness

**Problem it solves**: Demonstrates how to create a vaporwave/retro-futuristic 3D terrain effect using React Three Fiber with texture maps and post-processing.

**Why the implementation is notable**: A clean, minimal demonstration of R3F displacement mapping and PBR textures. Useful as a starting point for any WebGL terrain or environment scene.

**Future project uses**: Terrain rendering, ambient WebGL backgrounds, R3F texture pipeline references.

**Caveats**: This is an old (2022) demo tied to a blog post. The R3F APIs have evolved; some patterns may need updating. The visual output is the primary value, not the implementation quality.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 3/5 | Simple demo code, not production-quality |
| Originality | 3/5 | Replicates an existing aesthetic (vaporwave) |
| Implementation Age | 2/5 | 2022 — R3F has changed significantly |
| Dependency Risk | 3/5 | Three.js API changes affect shader/geometry code |
| Long-term Usefulness | 2/5 | Visual reference; patterns need updating |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 3 |
| Originality | 3 |
| General Reusability | 2 |
| Educational Value | 3 |
| Design / UX Quality | 4 |
| Architecture Quality | 2 |
| Documentation Quality | 2 |
| Maintenance Health | 1 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 2 |

**Final Priority**: low  
**Recommended Action**: metadata-only (visual inspiration; implementation is stale)
