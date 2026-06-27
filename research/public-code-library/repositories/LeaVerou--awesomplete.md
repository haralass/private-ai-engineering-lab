# LeaVerou/awesomplete

## Identity
- **Owner**: LeaVerou
- **Repository**: awesomplete
- **URL**: https://github.com/LeaVerou/awesomplete
- **Live URL**: https://projects.verou.me/awesomplete/
- **Commit SHA**: 7bafd6bbe4d09d994ebdd628dd7568ed339eb4f8
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push July 2024)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: npm package, zero dependencies, self-contained JS+CSS files, wide adoption (6,979 stars), referenced in web accessibility resources.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: JavaScript (ES5-compatible)
- **Frameworks**: none
- **Key dependencies**: none (zero dependencies)
- **Build system**: Gulp (for minification)
- **Tests**: yes — karma-based test suite in `/test/`
- **Architecture**: Class-based. `Awesomplete` wraps an `<input>` element. Uses `MutationObserver` for watching data changes on the list source element. Popup implemented as an `<ul>` with ARIA roles (`listbox`, `option`). Supports custom suggestion objects (value + label split), custom filtering function, custom sorting, and item rendering. Event-driven (`awesomplete-open`, `awesomplete-close`, `awesomplete-select`, `awesomplete-selectcomplete`).
- **Rendering model**: DOM manipulation (vanilla JS, no virtual DOM)

## Useful content (exact files)

### Directly reusable code
- `awesomplete.js` — Complete library (~450 lines). All logic in one file.
- `awesomplete.base.css` — Minimal structural CSS (positioning, visibility)
- `awesomplete.css` — Full default theme CSS
- `awesomplete.theme.css` — Alternative theme

### Adaptable patterns
- **MutationObserver for data sync**: Watches a `<datalist>` or `<ul>` element for changes and updates suggestions automatically. Pattern applicable to any component that needs to react to external DOM data sources.
- **ARIA `listbox` + `option` pattern**: Clean accessible popup implementation with keyboard navigation (`aria-activedescendant`, `aria-expanded`, `role="listbox"`). Well-tested reference for accessible autocomplete ARIA patterns.
- **Fuzzy filter function** (`awesomplete.js`, `FILTER_CONTAINS` and `FILTER_STARTSWITH`): Two-line substring/prefix filter implementations worth referencing for custom filter logic.

### Architecture reference
- `_.all` WeakMap for instance tracking by element — similar pattern to style-observer's approach
- Event-based plugin system via custom DOM events

### Reference-only
- `test/` — karma + mocha test suite; useful as test structure reference for DOM-heavy components

## Evaluation
**Problem solved**: Zero-dependency accessible autocomplete for `<input>` elements. Augments the native `<datalist>` element with flexible filtering and custom rendering.

**Original value**: In 2015 it was novel; in 2026 it is mature and stable. Value is now primarily in its proven accessibility implementation and zero-dependency approach. For modern React projects, a Combobox primitive (e.g., Radix) would be preferred — but for vanilla JS or progressive enhancement contexts, awesomplete remains directly usable.

**Future project types**: Admin tools, progressive enhancement layers for existing HTML forms, any context where React overhead is unwanted.

**Do not copy**: The ES5-style class pattern (using `_` utility functions rather than ES6 class syntax) is dated. If adapting, update to modern syntax.

**Risks**: Old-style build system (Gulp); the minification step is non-standard. The base functionality is stable but the project is unlikely to adopt modern CSS (container queries, popover API) without a community PR.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 3 |
| General usefulness | 4 |
| Architecture | 3 |
| Design and UX | 4 |
| Accessibility | 5 |
| Performance | 5 |
| Testing | 4 |
| Documentation | 5 |
| Maintenance health | 3 |
| Licensing clarity | 5 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
