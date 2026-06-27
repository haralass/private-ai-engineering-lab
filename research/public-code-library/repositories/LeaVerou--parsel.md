# LeaVerou/parsel

## Identity
- **Owner**: LeaVerou
- **Repository**: parsel
- **URL**: https://github.com/LeaVerou/parsel
- **Live URL**: https://projects.verou.me/parsel/
- **Commit SHA**: 6a4081b40082b141ab686f8de03f128af143c5de
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push June 2026, 4 days ago)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Published to npm. Single-file TypeScript implementation with separate compiled releases. Has a test suite (`test.json`). Used as a dependency by other tools including Chrome DevTools (historically). Actively maintained.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript
- **Frameworks**: none (vanilla TS, compiles to ESM)
- **Key dependencies**: none
- **Build system**: Rollup
- **Tests**: yes — `test.json` with JSON-format test cases, `test.html` for browser execution
- **Architecture**: Single-file (`parsel.ts`). Tokenization via regex-based scanner (`tokenizeBy`) that splits a selector string using ordered grammar patterns. Parenthetical arguments are pre-processed using `gobbleParens()` and replaced with placeholder characters (`¶`) to avoid ambiguity with nested pseudo-classes. After tokenization, a nested tree is built for recursive pseudo-classes (`:not()`, `:is()`, `:has()`, `:where()`, `:nth-child()` with `of` clause). Exports specificity calculation and selector walking utilities.
- **Rendering model**: isomorphic (no DOM dependency — pure string parsing)

## Useful content (exact files)

### Directly reusable code
- `parsel.ts` — The entire library in one file. Key exports: `tokenize()`, `parse()`, `specificity()`, `specificityToNumber()`, `walk()`, `serialize()`
- `releases/v1.2.3.md` — Changelog for current release

### Adaptable patterns
- **Placeholder-character nested parsing** (`parsel.ts` lines 50–68, `gobbleParens`): Replace nested parenthetical content with a placeholder before tokenizing, then restore it. Applicable to any recursive grammar that needs to avoid ambiguity in sequential parsing.
- **Ordered regex grammar** (`TOKENS` object): Using an ordered dictionary of named regex patterns where order determines parsing precedence. Clean pattern for building simple tokenizers.

### Architecture reference
- The overall approach: tokenize-then-tree-build in two separate passes. The tree structure (`AST`) makes selector introspection straightforward — useful reference for anyone building selector-aware tooling.

### Reference-only
- `index.html` — Live demo/playground deployed to projects.verou.me/parsel/

## Evaluation
**Problem solved**: Parsing CSS selectors into an AST for analysis, transformation, or specificity calculation. No other well-maintained TypeScript CSS selector parser exists at this quality level.

**Original value**: Handles full CSS4 selector syntax including recursive pseudo-classes with arguments (`:nth-child(2n+1 of .item)`), namespace-qualified types, and universal selectors with namespaces. The specificity implementation is spec-compliant.

**Future project types**: CSS linting tools, selector-based component isolation, specificity analysis, CSS transformation pipelines, design system token mapping to selectors, CSS test coverage tools.

**Do not copy**: The TOKENS regex constants are carefully ordered — changing order breaks the parser. Do not reorder without understanding the precedence rules.

**Risks**: Parser is string-based only; it cannot evaluate whether a selector matches elements. That is by design but is a common point of confusion.

## Scores (1–5)
| Dimension | Score |
|---|---|
| Technical quality | 5 |
| Originality | 4 |
| General usefulness | 4 |
| Architecture | 5 |
| Design and UX | 4 |
| Accessibility | N/A |
| Performance | 4 |
| Testing | 4 |
| Documentation | 4 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: high
**Action**: clone
