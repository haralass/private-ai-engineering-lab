# antfu/node-modules-inspector

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | antfu |
| Repository | node-modules-inspector |
| URL | https://github.com/antfu/node-modules-inspector |
| Live URL | https://node-modules-inspector.antfu.me |
| Commit SHA | 8be7f9c1b6c74b83a5b95b91e13d29c23c87a0c8 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active |
| Last Meaningful Push | 2026-05-30 |

## Legal Status

| Field | Value |
|-------|-------|
| License | MIT |
| Attribution Required | yes |
| Code Reuse | clearly permitted |
| Reference-Only | no |

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | TypeScript |
| Framework | Nuxt 3 (for the web UI), Unbuild (for the node-modules-tools package) |
| Major Dependencies | Nuxt 3, @nuxt/ui, @nuxt/eslint, Playwright (E2E), unbuild, rollup |
| Build System | pnpm monorepo + unbuild + Nuxt |
| Test System | Playwright (E2E), Vitest (unit for node-modules-tools) |
| Repository Structure | `packages/node-modules-inspector/` (Nuxt app + CLI), `packages/node-modules-tools/` (core analysis library), `test/e2e/` (Playwright tests) |
| Architecture | Monorepo. `node-modules-tools` is a headless analysis library. `node-modules-inspector` is a CLI that spawns a local Nuxt web server serving an interactive dependency graph browser. |

## Actual Valuable Content

### Core Analysis Library: `packages/node-modules-tools/src/`

**`packages/node-modules-tools/src/list.ts`**
- Lists all packages in `node_modules/` by reading `package.json` files.
- Recursively traverses the node_modules tree including nested/hoisted packages.
- Returns structured package data with name, version, path, dependencies.

**`packages/node-modules-tools/src/analyze-esm.ts`**
- Analyzes ESM exports of packages — determines if a package is CommonJS or ESM, detects dual CJS/ESM packages.
- Uses dynamic import and Node.js `resolve` to inspect actual exports.

**`packages/node-modules-tools/src/size.ts`**
- Calculates disk size of each package in node_modules.
- Handles both flat and nested installs.

**`packages/node-modules-tools/src/resolve.ts`**
- Resolves package relationships: which packages depend on which, builds dependency graph.

**`packages/node-modules-tools/src/types/`**
- TypeScript types for the package graph, package metadata, analysis results.

**`packages/node-modules-tools/src/json-parse-stream.ts`**
- JSON streaming parser for large package-lock.json files — avoids loading entire lockfile into memory.

### Web UI (Nuxt App)

**`packages/node-modules-inspector/src/`**
- The Nuxt 3 web UI renders an interactive dependency graph. Key patterns:
  - Real-time filtering by package name, license, ESM status.
  - Tree visualization of dependency relationships.
  - License summary and risk detection.

### E2E Tests

**`test/e2e/a11y.spec.ts`**
- Playwright accessibility spec. Good reference for testing large-data UIs with Playwright.

## Value Classification

| Item | Classification |
|------|---------------|
| `node-modules-tools` package listing + graph | directly reusable code |
| ESM detection algorithm (`analyze-esm.ts`) | directly reusable code |
| Package size calculation | directly reusable code |
| JSON streaming parser for lockfiles | adaptable implementation pattern |
| Dependency graph resolution | directly reusable code |
| Nuxt interactive web UI | architecture reference |
| License detection pattern | adaptable implementation pattern |

## General Usefulness

**Problem it solves**: Visualizes and analyzes the contents of `node_modules/` — useful for auditing dependency health, identifying duplicates, detecting non-ESM packages, and understanding the full dependency tree.

**Why the implementation is notable**: The `node-modules-tools` library is headless and independently usable. The ESM detection and size calculation algorithms are non-trivial. The streaming JSON parser for lockfiles is a practical performance optimization.

**Future project uses**:
- Build tooling that needs to inspect a project's dependencies
- Dependency audit pipelines
- License compliance checking tools
- Any CLI tool that needs to read and analyze node_modules

**Smaller isolated part**: The `node-modules-tools` package is the high-value unit. The Nuxt UI is a showcase.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 5/5 | Excellent TypeScript, well-structured monorepo |
| Architecture | 5/5 | Clean separation of headless tools from UI |
| Maintainability | 5/5 | Actively maintained, modern Node.js APIs |
| Testing Quality | 4/5 | Playwright E2E + Vitest unit tests |
| Documentation | 4/5 | README + in-code types |
| Dependency Risk | 3/5 | Nuxt dependency for UI; core tools have minimal deps |
| Originality | 4/5 | ESM detection + streaming lockfile parse are distinctive |
| Long-term Usefulness | 5/5 | Node.js ecosystem tooling is perennially needed |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 5 |
| Originality | 4 |
| General Reusability | 5 |
| Educational Value | 4 |
| Design / UX Quality | 4 |
| Architecture Quality | 5 |
| Documentation Quality | 4 |
| Maintenance Health | 5 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 5 |

**Final Priority**: critical  
**Recommended Action**: clone (done — `external-sources/node-modules-inspector`)
