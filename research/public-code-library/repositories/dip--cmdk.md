# dip/cmdk

## Identity
- **Owner**: dip (original author: Paco Coursey / pacocoursey)
- **Repository**: cmdk
- **URL**: https://github.com/dip/cmdk
- **Live URL**: https://cmdk.paco.me
- **Commit SHA**: dd2250ed608443e8f32bafc5fa2d1d07a3746aa3
- **Analysis date**: 2026-06-27
- **Original / Fork**: Original (transferred from pacocoursey to dip org)
- **Status**: active
- **Transferred**: yes — originally at pacocoursey/cmdk

## Relationship classification
reusable-engine-or-library

Evidence: This is a published npm package (cmdk, v1.1.1) used as a dependency in thousands of applications including shadcn/ui. It is not a site or demo — it is a production React component library.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript
- **Frameworks**: React 18/19
- **Key dependencies**: @radix-ui/react-compose-refs, @radix-ui/react-dialog, @radix-ui/react-id, @radix-ui/react-primitive
- **Build system**: tsup
- **Package manager**: pnpm (monorepo workspace)
- **Tests**: yes — Playwright e2e tests (test/ directory): basic, dialog, group, keybind, item, numeric, props
- **CI**: yes — GitHub Actions (assumed from .github structure)
- **Architecture**: Compound component pattern. DOM-authoritative item selection. Context tree for item registration. Fuzzy scoring via custom `command-score.ts` algorithm.
- **State management**: React context (CommandContext, GroupContext, ListContext)
- **Rendering model**: Client-side React. Items always render in the React tree; hidden via CSS display:none when filtered. DOM order determines selection order.

## Useful content (exact files)

### Directly reusable code
- `cmdk/src/index.tsx` — Complete command menu implementation (1,091 lines). Includes all sub-components: Command, Command.Input, Command.List, Command.Item, Command.Group, Command.Separator, Command.Empty, Command.Loading, Command.Dialog
- `cmdk/src/command-score.ts` — Custom fuzzy scoring algorithm for matching search input to item text/keywords

### Adaptable patterns
- Compound component + context registration pattern: items register themselves via `useEffect` into a parent `CommandContext`. No prop drilling, no render props. Applicable to any hierarchical component system needing to know about its descendants.
- DOM-order-based selection tracking: instead of maintaining an index, selection is tracked by item value (stable string), and DOM traversal determines prev/next. Avoids index drift in concurrent React.
- Fuzzy filtering without external dependency: `command-score.ts` is self-contained and can be extracted for any search/filter use case.
- `disableAnimation()` utility in next-themes (shared pattern): temporarily inject CSS `transition: none !important` on `<html>` during theme switch — same principle is used to prevent flash on keyboard navigation in cmdk.

### Architecture reference
- `ARCHITECTURE.md` documents why compound components were chosen over array-based rendering, why `React.Children` was rejected, and how the DOM-order selection model avoids Strict Mode/concurrent mode bugs. Essential reading for understanding advanced React component design.
- Group tracking pattern: each Group registers itself with the root via context and tracks its own items. The root aggregates all groups to determine visibility.

### Reference-only
- `test/pages/` — Demo pages for each test scenario (group, keybinds, huge list, item-advanced, numeric sorting)

## Evaluation
**Problem solved**: Accessible, composable, filterable command menu / palette component that works with arbitrary React component trees.
**Original value**: The compound component approach to a combobox is genuinely hard and novel. Most command palette libs use data arrays. cmdk's approach enables full render customization including icons, keyboard shortcuts, conditional items, lazy-loaded groups.
**Future project types**: Any app that needs command palettes, global search, quick-switchers, or menu navigation. Directly relevant to Curator's navigation and file/note search interfaces.
**Do not copy**: The ARIA role assumptions are baked deep — don't extract just the filtering logic without the accessibility layer. The selection model depends on DOM ordering, so virtualized lists require special handling.
**Risks**: The `dip` org is a company org; the repo was transferred from Paco's personal account. License is still MIT. Long-term stewardship is less transparent than before.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 5 |
| Architecture | 5 |
| Design and UX | 4 |
| Accessibility | 5 |
| Performance | 4 |
| Testing | 4 |
| Documentation | 5 |
| Maintenance health | 4 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: critical
**Action**: clone
