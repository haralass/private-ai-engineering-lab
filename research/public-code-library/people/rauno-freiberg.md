# Rauno Freiberg

**GitHub**: raunofreiberg
**Website**: https://rauno.me
**Analysis date**: 2026-06-27

## Profile overview
Rauno Freiberg is a product engineer known for meticulous attention to micro-interaction quality. His public repos are small but high-signal — each targets a narrow problem in UI feel or developer experience. Formerly at Modulz (Radix), now focused on product work at Vercel. His output is more curated than prolific.

## Repository inventory
| Repository | Stars | Language | Status | Decision |
|---|---|---|---|---|
| interfaces | 1,928 | JavaScript | Stale (2023) | RETAIN |
| inspx | 1,474 | TypeScript | Unmaintained (2023) | RETAIN |
| ui-playbook | 1,420 | TypeScript | Unmaintained (2023) | RETAIN |
| vesper | 762 | — | Active | REJECT (VS Code theme) |
| axe-mode | 198 | TypeScript | Unmaintained | REFERENCE |
| raunofreiberg.github.io | 29 | HTML | Stale | REJECT (old portfolio) |
| programming-resources | 21 | — | Stale | REJECT (link list) |
| primitives | 6 | — | Fork (Radix upstream) | REJECT (not original work) |
| All other repos | — | — | Forks / old tutorials | REJECT |

NOTE: `raunofreiberg/primitives` is a fork of the Radix Primitives org repository. It is NOT Rauno's original work and must not be analyzed as such.

## Strongest repositories

**interfaces** — A curated collection of interaction design patterns implemented in Next.js, accompanied by detailed written analysis of what makes each interaction "feel right." The value is in both the running code and the written documentation. Covers cursor effects, fluid number inputs, spring animations, haptic-feel button interactions. No license (read-only for lab use).

**inspx** — A React component (`<Inspect>`) that, when enabled, shows pixel-level spacing overlays (margin, padding, dimensions) on hover while holding Alt. Single-file implementation (326 lines TypeScript). Uses Radix Popper primitives for accurate overlay placement, BoundingClientRect for measurements. MIT licensed, directly reusable as a dev-only debugging tool.

**ui-playbook** — Documented reference of UI component best practices: what each component should do, why, and how. Not a component library — it is opinionated design documentation with interactive examples. Useful as a reference rubric for component design decisions. MIT licensed.

## Key findings
Rauno's work is distinguished by its focus on felt quality rather than raw functionality. `inspx` is the only repo that is directly importable as a dev dependency — but its last update was 2023 and the underlying `@radix-ui/popper` API it uses may have changed. `interfaces` is the most valuable as a research and inspiration source for high-quality micro-interactions. Both `inspx` and `ui-playbook` are effectively unmaintained but frozen in a useful state.

## Rejected repositories
- **vesper** — VS Code color theme, not code
- **axe-mode** — Accessibility testing overlay, interesting but unmaintained and narrow use case
- **primitives** (fork) — This is the Radix Primitives upstream repo, not Rauno's original work
- **raunofreiberg.github.io** — Old static portfolio
- All forks from 2015–2021 — learning projects, clones, org forks
