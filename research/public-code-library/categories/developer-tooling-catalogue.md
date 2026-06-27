# Developer Tooling Catalogue
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

Tools, configurations, and patterns that improve the development environment itself.

---

## Code Editor Configuration

### VS Code File Nesting Config
**Source**: antfu/vscode-file-nesting-config — `extension/src/config.ts`  
**License**: MIT  
**What**: `explorer.fileNesting.patterns` configuration that groups related files in VS Code's file explorer.  
**Coverage**: package.json, tsconfig.json, .env files, Next.js configs, Nuxt configs, Vite configs, Svelte configs, Astro configs, Remix configs, and 50+ other file groupings.  
**Usage**: Copy into project `.vscode/settings.json` or global VS Code settings.  
**Maintenance**: Actively maintained and updated as new tooling emerges.

---

## Node.js / npm Tooling

### Node Modules Inspector
**Source**: antfu/node-modules-inspector — `packages/node-modules-tools/`  
**License**: MIT  
**What**: Headless library for inspecting `node_modules`:
- `list.ts` — enumerates all packages with metadata
- `analyze-esm.ts` — detects ESM/CJS/dual status per package
- `size.ts` — calculates disk size per package
- `resolve.ts` — builds dependency graph
- `json-parse-stream.ts` — streams large lockfile JSON
**Usage**: Import `node-modules-tools` as a library in any Node.js build tool or CLI.  
**Notable**: The streaming JSON parser for lockfiles is a practical performance optimization for large monorepos.

### ESLint Config (Anthony Fu's Preset)
**Source**: antfu/eslint-config  
**License**: MIT  
**Status**: Active (6214 stars)  
**What**: A single, opinionated ESLint flat-config preset covering TypeScript, Vue, React, JSON, YAML, Markdown, and more.  
**Usage**: `import antfu from '@antfu/eslint-config'` — replaces dozens of individual plugin installations.  
**Note**: An additional candidate; analysis file at `repositories/antfu--eslint-config.md`.

---

## Build and Deployment Scripts

### Pre-Deployment Health Check
**Source**: kentcdodds/kentcdodds.com — `other/pre-deployment-health-check.ts`  
**License**: NOASSERTION (reference only)  
**Pattern**: Hits key application endpoints, validates HTTP responses. Runs as a deployment step before traffic is switched to new version.  
**Adapt for**: Any server deployment pipeline (Fly.io, Kubernetes, ECS).

### Changed Files Detection for CI
**Source**: kentcdodds/kentcdodds.com — `other/get-changed-files.js`  
**License**: NOASSERTION (reference only)  
**Pattern**: Git diff between HEAD and base branch to get list of changed files. Allows CI to skip irrelevant steps.  
**Adapt for**: Monorepos, content-heavy repos where only some paths trigger certain CI jobs.

### Data-Driven Config Generation Script
**Source**: antfu/vscode-file-nesting-config — `update.mjs`  
**License**: MIT  
**Pattern**: Reads a structured data definition, generates a configuration file. Separation of "what to include" (data) from "how to format it" (generator script).  
**Adapt for**: Any frequently-updated configuration file that benefits from structured data maintenance.

### esbuild Library Bundler
**Source**: MaximeHeckel/design-system — `esbuild.build.js`  
**License**: NONE (reference only — re-implement)  
**Pattern**: Simple esbuild script producing both CJS and ESM outputs for a React component library. Entry point is `src/index.ts`. Externals: react, react-dom.  
**Adapt for**: Any React component library that wants fast builds without Rollup complexity.

---

## Git Tooling

### Commit Grid Art Generator (Concept)
**Source**: jh3y/vincent-van-git — `functions/vincent/vincent.js`  
**License**: NONE (reference only)  
**What**: Generates shell script with `git commit --date=<ISO-date>` commands to produce a pixel-art pattern on GitHub's contribution heatmap.  
**Note**: The backdating technique is not recommended for production repos (misleading). Reference for: (1) how `git commit --date` works; (2) the date-to-grid-cell mapping algorithm.

---

## Testing References

### Playwright E2E for Animated UI Components
**Source**: emilkowalski/sonner — `test/tests/`  
**What**: Playwright tests for a component with complex animations and user interactions (hover to pause, swipe to dismiss, promise states).  
**Patterns**:
- Testing that timers pause on hover
- Testing swipe gesture sequences
- Waiting for animation completion before asserting
- Testing promise-based state transitions

### Playwright Accessibility Tests
**Source**: antfu/node-modules-inspector — `test/e2e/a11y.spec.ts`  
**What**: Playwright spec that runs axe-core accessibility checks on the main UI.  
**Usage**: Reference for adding automated a11y testing to any web app.

---

## Development Environment References

### Vite + Vue 3 + UnoCSS + Auto-Import Setup
**Source**: antfu/antfu.me — `vite.config.ts`  
**License**: MIT  
**What**: Complete Vite configuration with: file-system routing, Markdown-as-pages, auto-imported composables, auto-resolved components, UnoCSS atomic styling.  
**Usage**: Minimal starting point for a Vue 3 personal site or documentation.

### Vitest + Playwright Monorepo Test Setup
**Source**: antfu/node-modules-inspector — `playwright.config.ts`, `vitest.config.ts`  
**What**: Parallel unit (Vitest) and E2E (Playwright) test setup in a pnpm monorepo.

### TypeScript Strict Config Pattern
**Source**: Multiple sindresorhus repositories  
**What**: Sindre Sorhus's Swift packages and TypeScript libraries consistently use strict configuration. `tsconfig.json` with `strict: true`, `noUncheckedIndexedAccess: true`, and `exactOptionalPropertyTypes: true`. Pattern for maximum TypeScript safety.

---

## Interface Design Principles Documentation

### Rauno Freiberg's Interfaces Project
**Source**: raunofreiberg/interfaces — `src/`  
**License**: NONE (reference only)  
**What**: A documented set of interface design principles: specific behaviors that well-built interfaces should exhibit.  
**Value**: Acts as a design specification document. Can be adapted as a product's own interface quality checklist.  
**Key principle examples**:
- Buttons shouldn't trigger on mouse-down, only mouse-up
- Navigation items should indicate the current state
- Delete confirmations should describe what will be deleted, not just say "Are you sure?"
- Copy actions should briefly indicate success

---

## CSS Tooling Patterns

### Stretchy (Auto-Sizing Form Elements)
**Source**: LeaVerou/stretchy — `stretchy.js`  
**License**: MIT  
**What**: MutationObserver + resize event-based form element auto-sizing. Works on `<textarea>`, `<input>`, `<select>`. No framework dependency.  
**Note**: Old implementation (ES5), but the pattern is correct.

### Style Observer
**Source**: LeaVerou/style-observer — `src/`  
**License**: MIT  
**What**: MutationObserver wrapper that fires callbacks when CSS computed styles change. Useful for observing computed style changes that don't come with their own events (custom property values, computed color, etc.).  
**Usage**: When you need to react to CSS value changes without a separate state management layer.
