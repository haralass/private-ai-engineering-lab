# emilkowalski/vaul

## Identity
- **Owner**: emilkowalski
- **Repository**: vaul
- **URL**: https://github.com/emilkowalski/vaul
- **Live URL**: https://vaul.emilkowal.ski
- **Commit SHA**: 3e97aac6a38e4481bade71d7233ed6002e80f9b0
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: EXPLICITLY UNMAINTAINED — README states: "This repo is unmaintained. I might come back to it at some point, but not in the near future. This was and always will be a hobby project and I simply don't have the time or will to work on it right now."
- **Transferred**: no

## Relationship classification
full-open-source-product

Evidence: Production npm package. 8,427 stars. Ships as a React component. Playwright tests. But explicitly declared unmaintained by author.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: study only (due to unmaintained status and 155 open issues)

## Technical profile
- **Languages**: TypeScript (React)
- **Frameworks**: React
- **Key dependencies**: none listed as runtime peer deps beyond React; uses Radix Dialog primitives internally
- **Build system**: pnpm
- **Tests**: yes — Playwright tests
- **Architecture**: Bottom-sheet/drawer component built on top of a Dialog primitive. Handles drag-to-close gesture, spring animation on release, snap points (multiple heights the drawer can rest at), nested drawers, and keyboard/screen reader accessibility via Radix Dialog.
- **Rendering model**: React client component

## Useful content (exact files)

### Adaptable patterns
- **Snap point physics** (vaul src): The snap-to-nearest-point logic with spring animation on pointer release is worth studying for any drag-based UI component.
- **Nested drawer management**: Handling multiple layers of overlapping drawers/sheets, each with its own backdrop scale and gesture handling.

### Reference-only
- All source files — reference only due to unmaintained status

## Evaluation
**Problem solved**: Mobile-style bottom-sheet/drawer for React web applications. More capable than a simple modal: supports drag gestures, snap points, nested sheets, and spring physics.

**Original value**: The snap-point implementation with spring animation on release was novel when published. The API design (`<Drawer.Root>`, `<Drawer.Content>`) mirrors Radix Dialog which made adoption easy.

**Future project types**: Mobile-first web apps, command palettes, action sheets, settings panels.

**Do not copy**: Do not take this into production for new projects. 155 open issues, explicitly unmaintained. A maintained alternative or fork should be sought.

**Risks**: Author has explicitly stepped away. Open issues include accessibility bugs and animation edge cases. The 155 open issues with no maintainer response means bugs will not be fixed.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 4 |
| General usefulness | 4 |
| Architecture | 4 |
| Design and UX | 5 |
| Accessibility | 3 |
| Performance | 4 |
| Testing | 3 |
| Documentation | 3 |
| Maintenance health | 1 |
| Licensing clarity | 5 |
| Long-term lab value | 3 |

**Priority**: low
**Action**: reference-only
