# antfu/eslint-config

## Identity
- **Owner**: antfu
- **Repository**: eslint-config
- **URL**: https://github.com/antfu/eslint-config
- **Live URL**: N/A (npm package: @antfu/eslint-config)
- **Commit SHA**: 5ada54f
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active
- **Transferred**: no

## Relationship classification
`reusable-engine-or-library`

Evidence: Published as `@antfu/eslint-config` on npm. Used by thousands of projects including vitesse, nuxt, and many Vue ecosystem projects. The repository IS the library.

## Licensing
- **Code license**: MIT (LICENSE confirmed)
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript
- **Frameworks**: ESLint Flat Config
- **Key dependencies**: eslint, @eslint/js, @typescript-eslint/eslint-plugin, eslint-plugin-vue, eslint-plugin-react, eslint-plugin-import-x, eslint-plugin-unicorn, eslint-plugin-jsdoc, eslint-plugin-perfectionist, eslint-plugin-antfu
- **Build system**: tsdown (TypeScript bundler)
- **Package manager**: pnpm (monorepo)
- **Tests**: Vitest
- **CI**: GitHub Actions
- **Architecture**: A factory function (`antfu()`) that composes ESLint Flat Config configurations from composable presets. Each preset (`typescript`, `react`, `vue`, `svelte`, `angular`, `astro`, `json`, `toml`, `yaml`, `markdown`) is a separate module in `src/configs/`. Users opt into each preset by calling the factory with options.
- **Rendering model**: CLI tool + npm library

## Useful content (exact files)

### Directly reusable code

- `src/factory.ts` — The composable config factory. Takes options, assembles the appropriate preset modules, and returns a flat ESLint config array. The architecture is the most important part — shows how to build a composable ESLint Flat Config.
- `src/configs/` — Individual config presets: `typescript.ts`, `react.ts`, `vue.ts`, `javascript.ts`, `imports.ts`, `unicorn.ts`, `perfectionist.ts`, `markdown.ts`, `jsonc.ts`, `toml.ts`, `yaml.ts`. Each file is self-contained and can be studied independently.
- `src/globs.ts` — File glob patterns for each language/framework. A clean reference for which file extensions to lint for each technology.
- `src/plugins.ts` — Plugin imports with lazy loading (wrapped in getters to avoid loading unused plugins). This is the correct performance pattern for a composable ESLint config.
- `src/types.ts` — TypeScript types for the config options. Shows how to type an ESLint Flat Config factory.
- `bin/` — CLI for running the config with `@antfu/eslint-config` as a standalone linter.

### Adaptable patterns

- **Composable config factory pattern** (`src/factory.ts`): The `antfu()` function is called with an options object, enabling/disabling each preset. This factory pattern is directly adaptable for building project-specific or org-wide ESLint configurations.
- **Lazy plugin loading** (`src/plugins.ts`): Plugins are loaded via `Object.defineProperty` getters so they are only imported when actually needed. Prevents slow startup when many plugins are configured but not all used.
- **Auto-formatting with ESLint** (`src/configs/stylistic.ts`): Configuration for `@stylistic/eslint-plugin` to replace Prettier. Shows the correct approach to using ESLint for both linting AND formatting in one pass.

### Architecture reference

This config is the reference implementation for ESLint Flat Config composition. The flat config API (introduced in ESLint 9) replaced `.eslintrc`. Most existing resources still use the old format. This repository is the definitive public reference for the new format with complex multi-framework support.

### Reference-only

- `fixtures/` — Test fixtures for each language/framework combination. Useful to understand what the config actually produces.
- `scripts/` — Maintenance scripts for updating plugin versions.

## Evaluation

**Problem solved**: ESLint configuration complexity for multi-framework TypeScript projects. The flat config API is significantly harder to compose than the old `.eslintrc` inheritance model — this factory pattern makes it manageable.

**Original value**: The `antfu()` factory function is the most-copied ESLint Flat Config pattern in the Vue/TypeScript ecosystem. The lazy loading of plugins is a non-obvious performance optimization that most configs miss.

**Future project types**: Any JavaScript/TypeScript project needing a production ESLint setup. Particularly valuable for mixed-framework repos (e.g., a repo with Vue components AND React components AND MDX files).

**Do not copy**: The specific rule choices are Anthony Fu's preferences, not universal best practices. Use the architecture as a reference, not the rule configuration verbatim.

**Risks**: ESLint Flat Config is still evolving. Some plugin APIs may change. The number of plugin dependencies means occasional breaking changes during updates.

## Scores (1–5)

| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 5 |
| Architecture | 5 |
| Design and UX | N/A |
| Accessibility | N/A |
| Performance | 4 |
| Testing | 4 |
| Documentation | 5 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: high
**Action**: clone
