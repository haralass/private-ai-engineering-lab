# raunofreiberg/ui-playbook

## Identity
- **Owner**: raunofreiberg
- **Repository**: ui-playbook
- **URL**: https://github.com/raunofreiberg/ui-playbook
- **Live URL**: https://uiplaybook.dev
- **Commit SHA**: 9d1bfc997d3a03931985f2bf1782421ab51910bf
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: unmaintained (last push January 2023)
- **Transferred**: no

## Relationship classification
official-reference-implementation

Evidence: A documented reference of what UI components should do, with interactive examples. Positioned as "the documented collection of UI components." Not a component library for direct import — the value is the documentation and demos.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: custom fonts in `/public/static/fonts/` (GT Walsheim — commercial font, likely licensed per deployment not for redistribution)
- **Reuse verdict**: study only (font licensing; content is reference material)

## Technical profile
- **Languages**: TypeScript (Next.js pages router)
- **Frameworks**: Next.js (pages router), Stitches (CSS-in-JS)
- **Key dependencies**: Stitches, Radix UI primitives
- **Build system**: Next.js
- **Tests**: no
- **Architecture**: Content-driven Next.js site. Each component page in `src/pages/` documents one UI component. The `src/components/` directory contains the interactive demo implementations. Styling via Stitches (now deprecated/unmaintained).
- **Rendering model**: Next.js pages router (SSG/SSR)

## Useful content (exact files)

### Adaptable patterns
- Component documentation structure: each entry covers "what it should do," "why it matters," and a live interactive demo — a template worth following for design system documentation.

### Reference-only
- All component demos in `src/components/` — read as design reference for: Checkbox, Radio, Toggle, Switch, Slider, Select, Tooltip, Dialog, Popover, Command Palette, etc.
- The written documentation for each component covers expected behavior, keyboard interactions, and accessibility requirements — useful checklist when building these components.

## Evaluation
**Problem solved**: Documents the expected behavior and accessibility requirements for common UI components. Fills the gap between "here is a component" and "here is how it should behave and why."

**Original value**: The opinionated behavioral specification (keyboard navigation, ARIA states, focus management) is the primary value. This predates many similar resources and influenced how the Radix ecosystem documented its components.

**Future project types**: Reference when building or auditing any UI component library. Use the behavioral specs as acceptance criteria.

**Do not copy**: GT Walsheim font in `/public/static/fonts/` is a commercial font. Do not copy/redistribute. Stitches (the CSS-in-JS library used) is unmaintained — do not use as a technical reference for CSS-in-JS approach.

**Risks**: Built on Stitches (unmaintained) and Next.js pages router (being superseded by App Router). The demo code is outdated in terms of tooling choices, though the behavioral documentation remains valid.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 3 |
| Originality | 4 |
| General usefulness | 4 |
| Architecture | 3 |
| Design and UX | 5 |
| Accessibility | 5 |
| Performance | 3 |
| Testing | 1 |
| Documentation | 5 |
| Maintenance health | 1 |
| Licensing clarity | 3 |
| Long-term lab value | 4 |

**Priority**: medium
**Action**: reference-only
