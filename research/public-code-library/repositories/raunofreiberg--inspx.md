# raunofreiberg/inspx

## Identity
- **Owner**: raunofreiberg
- **Repository**: inspx
- **URL**: https://github.com/raunofreiberg/inspx
- **Live URL**: https://inspx.vercel.app
- **Commit SHA**: 061f3831900f5a89ca10567823915e7b5aacace4
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: unmaintained (last push February 2023)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Published as an npm package (`inspx`). Single React component (`<Inspect>`). MIT licensed. Designed to wrap a React app as a dev-only overlay.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript (React)
- **Frameworks**: React
- **Key dependencies**: @radix-ui/popper (for marker placement), csstype
- **Build system**: tsdx
- **Tests**: no
- **Architecture**: Single file (`src/index.tsx`, 326 lines). Wraps children in a `<span>` with `onMouseOver` handler. On hover, calls `document.elementsFromPoint()` to get all elements at the pointer. On Alt key press (`keydown` handler), calls `inspectMargin()`, `inspectSize()`, and `inspectPadding()`. Each inspect function: reads `getComputedStyle()`, creates custom HTML elements (`<inspx>`, `<margin>`, `<padding>`) and appends them to `document.body`, positions them using `@radix-ui/popper.getPlacementData()`. On Alt key up, `uninspect()` removes all custom elements from the DOM. Default-disabled in production (`process.env.NODE_ENV !== 'development'`).
- **Rendering model**: React DOM + direct DOM manipulation for overlay elements

## Useful content (exact files)

### Directly reusable code
- `src/index.tsx` — Complete implementation. 326 lines, single file.
- `src/styles.css` — CSS for `<inspx>`, `<margin>`, `<padding>` custom element overlays

### Adaptable patterns
- **`document.elementsFromPoint()` for hit testing** (`src/index.tsx` line 63): Getting all elements at a pointer position in Z order — useful for any overlay/inspector tool.
- **Custom element names as overlay markers** (`createMarkerNode` function): Using non-standard element names (`<inspx>`, `<margin>`, `<padding>`) as overlay containers — they don't inherit any default browser styles, making them predictable overlay containers.
- **Alt key as modifier for dev tools** (`onKeyDown` listening for `e.key === 'Alt'`): Pattern for activating dev-only overlay modes without conflicting with standard shortcuts.
- **`@radix-ui/popper.getPlacementData()` for overlay positioning**: Handles viewport collision, returns `popperStyles` for positioning via `transform: translate3d()`. Adaptable for any floating element that needs smart placement.

### Architecture reference
- The `disabled={process.env.NODE_ENV !== 'development'}` default ensures zero overhead in production — pattern worth reusing for any dev-only component.

### Reference-only
- `www/` — Marketing site source

## Evaluation
**Problem solved**: Visual layout inspection during development — see margin, padding, and size of DOM elements by hovering while holding Alt. Useful for catching off-by-one pixel spacing bugs without opening DevTools.

**Original value**: The combination of `elementsFromPoint`, custom element overlays, and the `@radix-ui/popper` placement API is clean and non-obvious. The Alt-key modifier approach doesn't interfere with normal usage.

**Future project types**: Component development environments, Storybook-like tools, design system inspection, internal admin tools where layout precision matters.

**Do not copy**: The dependency on `@radix-ui/popper` (internal Radix API, not a public package) may change without notice. The `getPlacementData` import is from Radix's internal popper package — verify this still exists in current Radix versions before using.

**Risks**: `@radix-ui/popper` is not a public API. The package has not been updated since February 2023 and Radix has gone through multiple API revisions since. Test this dependency before use. No tests in the package itself.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 4 |
| General usefulness | 3 |
| Architecture | 4 |
| Design and UX | 4 |
| Accessibility | 2 |
| Performance | 4 |
| Testing | 1 |
| Documentation | 3 |
| Maintenance health | 1 |
| Licensing clarity | 5 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
