# match-sorter

## 1. Identity and Provenance

| Field | Value |
|---|---|
| owner | kentcdodds |
| repo | match-sorter |
| url | https://github.com/kentcdodds/match-sorter |
| live_url | https://www.npmjs.com/package/match-sorter |
| commit_sha | 3bfa8803d64a2c0fe4b532822e5abf8e956e37f8 |
| date_analyzed | 2026-06-27 |
| fork | No |
| upstream | N/A |
| status | Active |
| last_pushed | 2026-05-13 |

## 2. Legal Status

| Field | Value |
|---|---|
| license | MIT |
| attribution_required | No (binary distribution only) |
| asset_licensing | N/A |
| code_reuse_permitted | Yes — copy, modify, sublicense freely |
| reference_only | No |

## 3. Technical Profile

| Field | Value |
|---|---|
| language | TypeScript |
| framework | None |
| dependencies | `remove-accents` (runtime), `@babel/runtime` (polyfills) |
| build_system | kcd-scripts (Rollup under the hood), outputs CJS + ESM bundles |
| test_system | Jest via kcd-scripts |
| structure | Single source file: `src/index.ts` (~570 lines), `src/__tests__/index.ts` |
| architecture | Pure function library — no classes, no global state, no side effects. All logic is in stateless functions that compose into the main `matchSorter` export. |

## 4. Actual Valuable Content

### `src/index.ts`

**`rankings` constant (lines 59–68)**
The core ranking enum. Integer values 0–7 drive every sort decision:
`CASE_SENSITIVE_EQUAL=7, EQUAL=6, STARTS_WITH=5, WORD_STARTS_WITH=4, CONTAINS=3, ACRONYM=2, MATCHES=1, NO_MATCH=0`

**`matchSorter<ItemType>` (lines 82–88)**
Main export. Accepts `items: ReadonlyArray<ItemType>`, a `value: string`, and an optional `options` object. Returns a filtered, ranked copy of the array. Delegates to `getRankedItems` then strips metadata.

**`matchSorterWithRankInfo<ItemType>` (lines 97–103)**
Same pipeline as `matchSorter` but returns `RankedItem[]` — each element carries `rank`, `keyIndex`, `keyThreshold`, and `rankedValue`. Useful when the caller needs to highlight matches or debug ranking.

**`getRankedItems<ItemType>` (lines 105–132)**
Core pipeline: pulls `keys`, `threshold`, `baseSort`, and `sorter` from options; runs `reduceItemsToRanked` over every item; passes results to the `sorter` (defaults to `sortRankedValues`). The `reduceItemsToRanked` inner function calls `getHighestRanking` and filters out items below the threshold.

**`getHighestRanking` (lines 145–192)**
Iterates over all key-derived values for a single item. For each candidate value calls `getMatchRanking`, then clamps the result with `minRanking`/`maxRanking` per-key attributes. Returns the best `{rank, rankedValue, keyIndex, keyThreshold}` tuple found.

**`getMatchRanking` (lines 209–284)**
The workhorse. Given `testString` and `stringToRank` (both run through `prepareValueForComparison`), evaluates them in order of descending rank:
1. Byte-equal → `CASE_SENSITIVE_EQUAL`
2. Lower-case equal → `EQUAL`
3. Lower-case starts-with → `STARTS_WITH`
4. Found after a space character → `WORD_STARTS_WITH`
5. Substring found anywhere → `CONTAINS`
6. Acronym of `testString` contains `stringToRank` → `ACRONYM`
7. All characters found in order → `getClosenessRanking` (returns float in `[1, 2)`)
8. Otherwise → `NO_MATCH`

Uses a `function* indexesOf` generator (lines 194–200) to avoid calling `indexOf` multiple times needlessly.

**`getAcronym` (lines 300–316)**
Generates an acronym by scanning for characters that follow a space or hyphen delimiter. Single-pass, O(n). Example: `'The Tail-spin Test'` → `'TTsT'`.

**`getClosenessRanking` (lines 328–370)**
Fuzzy matching fallback. Walks `stringToRank` character-by-character through `testString`, measuring the *spread* (last-match-index minus first-match-index). Score = `MATCHES + (inOrderCount / totalChars) * (1 / spread)`. Returns a float rather than an integer, so tight matches bubble above loose ones within the same `MATCHES` tier.

**`sortRankedValues` (lines 378–398)**
Comparator used by the default sorter. Primary key: `rank` (higher wins). Secondary key: `keyIndex` (lower key index wins — earlier keys are treated as more important). Tie-breaker: `baseSort` (defaults to `localeCompare`).

**`prepareValueForComparison` (lines 406–417)**
Stringifies the value, optionally strips diacritics via `removeAccents`. Controlled by `keepDiacritics` option.

**`getItemValues` / `getNestedValues` (lines 425–504)**
Extracts values from items. Supports:
- String key paths (`'name'`)
- Dot-notation deep paths (`'address.city'`)
- Wildcard `*` segments for arrays (`'tags.*.label'`)
- Function keys `(item) => item.computed`

**`getAllValuesToRank` (lines 512–529)**
Collects all `{itemValue, attributes}` pairs across all keys for a single item. Feeds `getHighestRanking`.

**Exported types** (lines 547–563): `MatchSorterOptions`, `KeyAttributesOptions`, `KeyOption`, `KeyAttributes`, `RankingInfo`, `RankedItem`, `ValueGetterKey`.

## 5. Value Classification

| Item | Classification |
|---|---|
| `rankings` enum | Directly reusable code |
| `matchSorter` + `matchSorterWithRankInfo` | Directly reusable code |
| `getMatchRanking` ranking pipeline | Directly reusable code |
| `getAcronym` function | Directly reusable code |
| `getClosenessRanking` fuzzy score | Adaptable implementation pattern |
| Dot-path + wildcard key resolution (`getNestedValues`) | Adaptable implementation pattern |
| `sortRankedValues` multi-key comparator | Directly reusable code |
| `prepareValueForComparison` + diacritics handling | Directly reusable code |
| Overall tiered-ranking architecture | Architecture reference |

## 6. General Usefulness

**Problem solved:** Produces human-intuitive sorted results for autocomplete, search-as-you-type, and command palette interfaces. Prioritizes exact matches, then prefix matches, then word-start matches, then substring matches, then fuzzy (character-in-order) matches — matching the mental model of what users expect.

**Why notable:** Single-file, zero-framework TypeScript. The ranking logic is well-documented inline and the algorithm is simple enough to understand and adapt in under an hour. Used as the default filter/sort engine in TanStack Table (`@tanstack/react-table` column filtering), which alone puts it in production in tens of thousands of applications.

**Future project types:** Any UI with a search input: command palettes, file pickers, tag selectors, mention inputs, fuzzy nav. Drop `matchSorter` in directly, or adapt `getMatchRanking` standalone for a Swift/Python port.

**What NOT to retain:** The `kcd-scripts` build toolchain — that's a meta-package specific to Kent's OSS workflow, not a pattern to copy. The `remove-accents` dependency is simple enough to inline if bundle size matters.

## 7. Quality Assessment

| Dimension | Rating | Notes |
|---|---|---|
| Code quality | 5 | Extremely clean, every function has JSDoc, consistent naming |
| Architecture | 5 | Pure functions, no mutation except opt-in sorter, composable |
| Maintainability | 5 | Single file, all logic visible, no abstractions that obscure intent |
| Accessibility | N/A | Library, not UI |
| Performance | 4 | O(n*k) per search call, generator avoids repeat indexOf; no caching built in (consumer responsibility) |
| Testing | 5 | Comprehensive Jest suite in `__tests__/index.ts` covers edge cases: empty string, diacritics, deep paths, custom sorters |
| Documentation | 5 | Inline JSDoc on every exported function, README with examples |
| Dependency risk | 5 | Only runtime dep is `remove-accents` (tiny, stable) |
| Security | 5 | Pure data transformation, no network or DOM |
| Originality | 4 | Ranking concept is well-known; the tiered integer system with per-key clamping is Kent's clean design |
| Age | 5 | Actively maintained; last push 2026-05-13 |
| Long-term usefulness | 5 | Fundamental UX utility that will remain relevant indefinitely |

## 8. Scoring

| Criterion | Score |
|---|---|
| Technical quality | 5 |
| Originality | 4 |
| General reusability | 5 |
| Educational value | 5 |
| Design / UX quality | N/A |
| Architecture quality | 5 |
| Documentation quality | 5 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Final Priority:** critical

**Recommended Action:** clone — already cloned at `external-sources/developer-tools/match-sorter`. The entire `src/index.ts` is reference-grade and can be copied verbatim into any TypeScript project or ported to another language.
