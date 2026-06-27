# Maxime Heckel

**GitHub**: MaximeHeckel
**Website**: https://blog.maximeheckel.com
**Analysis date**: 2026-06-27

## Profile overview
Maxime Heckel is a senior frontend engineer known for advanced WebGL/GLSL shader work, Three.js/React Three Fiber experiments, and his highly polished personal blog. His public output is focused on interactive 3D graphics, design systems, and technically excellent writing about shader programming, particle systems, and GPU compute (GPGPU). He is also prolific in the Vercel/Next.js ecosystem.

## Repository inventory
| Repository | Type | Stars | Status | Decision |
|-----------|------|-------|--------|----------|
| blog.maximeheckel.com | Original | 723 | Active | Clone |
| design-system | Original | 395 | Active | Clone |
| linear-vaporwave-three.js | Original | 55 | Maintained | Reference |
| linear-vaporwave-react-three-fiber | Original | 29 | Maintained | Reference |
| HarborJS | Original | 111 | Old/unmaintained | Reject |
| healthpulse | Original | 23 | Old | Reject |
| media-tagger | Original | 2 | Active (private-ish) | Reject |
| untitled-goose-running-game | Original | 2 | Experiment | Reject |
| sandpack | Original | 0 | Fork/mirror | Reject |
| react-livephoto | Original | 11 | Old | Reject |
| health-dashboard | Original | 21 | Old | Reject |
| carbonara | Original | 14 | Unmaintained | Reject |
| sunshine-weather-app | Original | 26 | SwiftUI experiment | Reject |
| github-action-merge-fast-forward | Original | 16 | Utility | Reject |
| gatsby-theme-maximeheckel | Original | 31 | Archived | Reject |
| r3f-cannon-orbital-mechanics-test | Original | 0 | Experiment | Reject |
| All forks (basement-laboratory, drei, react-postprocessing, etc.) | Fork | 0-5 | Various | Reject |

## Original vs forks vs transferred
- **Original**: 14 meaningful originals (blog, design-system, HarborJS, linear-vaporwave-three.js, linear-vaporwave-react-three-fiber, carbonara, media-tagger, etc.)
- **Forks**: ~15 repos are forks of upstream projects (drei, react-postprocessing, leerob.io, etc.) — all rejected
- **Transferred**: None detected
- **Archived**: gatsby-theme-maximeheckel, og-image-maximeheckel.com, redux-hooks-context, cypress-axe-example, docker-graphql, react-lazy-preloading

## Strongest repositories
1. **blog.maximeheckel.com** — The live source of his blog. Contains production-quality GLSL shaders, GPGPU particle system, React Three Fiber scenes, post-processing effects, MDX interactive widget infrastructure, Sandpack integration, semantic search with Orama, and an AI search feature. Genuinely exceptional implementation. Decision: **clone**
2. **design-system** — A personal component library built with Stitches (CSS-in-JS) and Base UI. Well-structured, MIT licensed, and actively maintained. Includes tokens, themes, accessibility hooks, and a glass material component. Decision: **clone**
3. **linear-vaporwave-three.js** and **linear-vaporwave-react-three-fiber** — Two versions of a vaporwave WebGL scene recreating the Linear release page. Interesting as paired implementations (vanilla Three.js vs R3F). MIT licensed. Decision: **reference**

## Key findings
The highest unique value in Maxime's public work is his GPGPU particle simulation system and GLSL shader widgets in the blog. The `core/features/IndexSection/gpgpu/` directory contains four GLSL files implementing a full GPU particle simulation (fragment + vertex + simulation fragment + simulation vertex shaders) with post-processing. The `core/components/MDX/Widgets/` directory has 25+ interactive in-article demos covering raymarching, atmospheric scattering, volumetric lighting, caustics, Moebius transforms, halftone, painterly shaders, and more. This is not tutorial material — it is production-quality educational tooling with real GPU compute.

## Rejected repositories
- **HarborJS** — Docker web UI from 2018, entirely irrelevant today
- **healthpulse / health-dashboard / node-selftracker-*** — 2014-2017 personal health data projects
- **media-tagger** — 2 stars, private-use media tool, no license
- **carbonara** — Unmaintained serverless Carbon screenshot tool
- **gatsby-theme-maximeheckel** — Archived, superseded by the Next.js blog
- **All Docker-era forks** (dockersh, docker.io, swarmkit, etc.) — Work-era forks from Docker employment 2013-2018
- **sunshine-weather-app** — "First SwiftUI project", explicitly noted as not shipped
- **react-hook-context-emotion-dark-mode** — Old pattern demo from 2019
- **All archived repos** — Explicitly deprecated
- **Forks of drei, react-postprocessing, leerob.io** — Upstream contributions, not original
