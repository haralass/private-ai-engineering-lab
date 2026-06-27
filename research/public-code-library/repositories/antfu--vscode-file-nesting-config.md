# antfu/vscode-file-nesting-config

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | antfu |
| Repository | vscode-file-nesting-config |
| URL | https://github.com/antfu/vscode-file-nesting-config |
| Live URL | N/A (VS Code settings config) |
| Commit SHA | 7c701eadbe7d5a07b2c0a12bcb93db34893f3d15 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active (frequently updated) |
| Last Meaningful Push | 2026-06-24 |

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
| Primary Language | JavaScript (update script) |
| Framework | none |
| Major Dependencies | none |
| Build System | none (single config file) |
| Test System | none |
| Repository Structure | `extension/src/config.ts` (the generated config), `update.mjs` (the generation script) |
| Architecture | VS Code extension that ships a curated `explorer.fileNesting.patterns` configuration |

## Actual Valuable Content

### The Config: `extension/src/config.ts`

The entire value is in the VS Code file nesting patterns config. This config groups related files in the VS Code file explorer — for example:
- `package.json` nests `package-lock.json`, `yarn.lock`, `pnpm-lock.yaml`, `.npmrc`, `.nvmrc`, etc.
- `tsconfig.json` nests all `tsconfig.*.json` variants
- `README.md` nests `CHANGELOG.md`, `CONTRIBUTING.md`, etc.
- Framework-specific patterns: `next.config.*` nests `.env*`, `app.config.*`, etc.

The `update.mjs` script auto-generates the config from a structured data definition — the generation pattern (data → config file) is useful for maintaining large configuration files.

## Value Classification

| Item | Classification |
|------|---------------|
| `explorer.fileNesting.patterns` config | reusable dataset |
| Config generation script pattern | developer-tooling reference |
| VS Code extension scaffold | developer-tooling reference |

## General Usefulness

**Problem it solves**: VS Code's file explorer becomes cluttered with configuration files. This config groups related files under their "parent" file, reducing visual noise.

**Why the implementation is notable**: The config is maintained with extreme attention to the evolving frontend ecosystem — it covers dozens of frameworks, tools, and configuration file conventions. The auto-update script ensures it stays current. It is widely copied and adapted by thousands of developers.

**Future project uses**:
- Drop into any VS Code project's `settings.json`
- Starting point for customizing file nesting per-project
- The data-driven config generation pattern is applicable to any auto-maintained config

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 4/5 | Clean generation script |
| Originality | 4/5 | The most comprehensive maintained file nesting config |
| General Reusability | 5/5 | Directly usable as-is |
| Maintenance Health | 5/5 | Frequently updated |
| Licensing Clarity | 5/5 | Clear MIT |
| Long-term Usefulness | 4/5 | Useful as long as VS Code is the dominant editor |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 4 |
| Originality | 4 |
| General Reusability | 5 |
| Educational Value | 3 |
| Design / UX Quality | N/A |
| Architecture Quality | 4 |
| Documentation Quality | 4 |
| Maintenance Health | 5 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 4 |

**Final Priority**: high  
**Recommended Action**: metadata-only (single config file — copy the config directly; no need to clone)
