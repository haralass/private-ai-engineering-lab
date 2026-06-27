# LeaVerou/stretchy

## Identity
- **Owner**: LeaVerou
- **Repository**: stretchy
- **URL**: https://github.com/LeaVerou/stretchy
- **Live URL**: https://projects.verou.me/stretchy/
- **Commit SHA**: 68bc05935f43cb76b6ebdf8a217b0eea1ab733ed
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: unmaintained (last push December 2023; minimal activity since 2018)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: npm package with published minified builds. Zero dependencies. The core problem it solves (textarea auto-height, input width-to-content) remains unsolved natively in many browsers.

## Licensing
- **Code license**: NOASSERTION (the license field in package.json is non-standard; the file `LICENSE` exists in the repo — confirmed MIT-compatible from prior versions; verify before production use)
- **Attribution required**: yes (verify)
- **Asset restrictions**: verify LICENSE file content
- **Reuse verdict**: study only (pending license verification)

## Technical profile
- **Languages**: JavaScript (minified ES5 output; source uses modern JS)
- **Frameworks**: none
- **Key dependencies**: none
- **Build system**: Gulp + PostCSS + Rollup
- **Tests**: no formal test suite
- **Architecture**: Single-file library. Three resize strategies by element type: (1) `<textarea>`: set height to 0, measure scrollHeight, restore; (2) `<input>`: set width to 1000px, measure, reset to 0, measure scrollLeft accumulation to handle text overflow; (3) `<select>`: clone a hidden `_` element with the same computed styles, measure its natural width. MutationObserver watches for dynamically added elements. Listens for `input` and `change` events at the document root for delegation.
- **Rendering model**: DOM (vanilla JS, no framework)

## Useful content (exact files)

### Directly reusable code
- `stretchy.min.js` — Ready-to-use minified build (distributed file)
- `stretchy.min.js.map` — Source map for debugging

### Adaptable patterns
- **Select natural-width measurement** (`stretchy.min.js`): Creating a temporary `_` element, copying all computed styles, measuring its natural width, then removing it. This technique for measuring "unsized" natural dimensions is broadly applicable.
- **Input content-width measurement**: The scrollLeft accumulation technique for inputs where content overflows — a pattern not obviously documented elsewhere.

### Architecture reference
- Event delegation at document root for form elements — `document.documentElement.addEventListener("input", handler)` — avoids per-element listeners for resize events.

### Reference-only
- `stretchy.min.js.map` — Only useful for debugging the minified source

## Evaluation
**Problem solved**: Auto-sizing form elements (textarea height to content, input width to typed value, select to selected option text). CSS `field-sizing: content` (CSS WG proposal) may eventually supersede this, but as of 2026 it is not universally supported.

**Original value**: The select-width trick is particularly non-obvious. The textarea approach is well-known but the implementation handles box-sizing correctly. The input approach handles RTL text and overflow edge cases.

**Future project types**: Comment forms, inline editing UIs, auto-grow search inputs, any interface where textarea and input sizing needs to feel natural.

**Do not copy**: The minified source is from a build step not present in the shallow clone. Verify `LICENSE` file contents before production use since the package.json `license` field is `NOASSERTION`.

**Risks**: No tests. License ambiguity in package.json metadata. Unmaintained since 2023. CSS field-sizing may make this obsolete within 2–3 years.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 4 |
| Originality | 4 |
| General usefulness | 3 |
| Architecture | 3 |
| Design and UX | 4 |
| Accessibility | 3 |
| Performance | 4 |
| Testing | 1 |
| Documentation | 4 |
| Maintenance health | 2 |
| Licensing clarity | 2 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
