# Anthony Fu

**GitHub**: antfu
**Website**: https://antfu.me
**Analysis date**: 2026-06-27

## Profile overview
Anthony Fu is one of the most prolific open source developers in the Vue/Vite ecosystem. He is a core team member of Vue, Vite, Nuxt, VueUse, UnoCSS, Vitest, and Slidev, and independently maintains dozens of widely-used TypeScript tools. His work spans ESLint tooling, VS Code extensions, build system utilities, and creative coding experiments. He averages multiple published packages per week. His GitHub shows 200+ original repos across two pages.

## Repository inventory (selected — 200+ total)

### Tier 1: Critical ecosystem tools
| Repository | Stars | Status | Decision |
|-----------|-------|--------|----------|
| eslint-config | 6214 | Active | Clone |
| node-modules-inspector | 2897 | Active | Clone |
| vitesse (antfu-collective) | 9428 | Active | Clone |
| vscode-file-nesting-config | 3650 | Active | Reference |
| utils | 876 | Active | Reference |
| starter-ts | 919 | Active | Reference |

### Tier 2: Valuable tools
| Repository | Stars | Status | Decision |
|-----------|-------|--------|----------|
| drauu | 1507 | Active | Reference |
| vue-starport | 1953 | Active | Reference |
| changelogithub | 932 | Active | Reference |
| case-police | 1428 | Active | Reference |
| broz | 1122 | Active | Reference |
| eslint-plugin-command | 370 | Active | Reference |
| eslint-typegen | 277 | Active | Reference |
| magic-string-stack | 204 | Active | Reference |
| diff-match-patch-es | 193 | Active | Reference |
| pnpm-patch-i | 300 | Active | Reference |
| fast-npm-meta | 209 | Active | Reference |
| tsnapi | 188 | Active | Reference |
| nanovis | 85 | Active | Reference |
| nuxt-mcp-dev | 910 | Active | Reference |
| skills | 5410 | Active | Reference |
| ghfs | 266 | Active | Reference |
| regex-doctor | 167 | Active | Reference |
| vite-dev-rpc | 75 | Active | Reference |

### Tier 3: VS Code extensions
| Repository | Stars | Status | Decision |
|-----------|-------|--------|----------|
| vscode-iconify | 575 | Active | Reference |
| vscode-smart-clicks | 689 | Active | Reference |
| vscode-theme-vitesse | 694 | Active | Reference |
| vscode-browse-lite | 665 | Active | Reference |
| starter-vscode | 539 | Active | Reference |
| vscode-goto-alias | 277 | Active | Reference |

### Rejected / Archived
| Repository | Decision |
|-----------|----------|
| shikiji | Archived (merged into shiki) |
| shiki-stream | Archived |
| are-we-esm | Archived |
| vite-plugin-glob | Archived (merged into Vite core) |
| purge-icons | Archived |
| eslint-flat-config-viewer | Archived (integrated into eslint-config) |
| reactivue | Interesting but niche experiment |
| 1990-script | Joke/fun, archived |
| antfu.me | Personal website, Markdown-heavy |
| handle | Chinese Wordle game, content |
| qrcode-toolkit | Niche QR tool |
| 100 | Design experiments, art |

## Original vs forks vs transferred
- **Original repos**: ~170 across two pages
- **Forks**: ~30+ forks of upstream projects he contributes to (vueuse, undocs, typescript-eslint, etc.)
- **Transferred**: `vitesse` was transferred to `antfu-collective/vitesse` (9428 stars) — still his project, different org
- **Archived**: ~20 repos explicitly archived

## Strongest repositories

1. **eslint-config** — The gold standard for ESLint Flat Config configuration. A composable factory system supporting TypeScript, React, Vue, Svelte, Angular, Astro, JSON, TOML, YAML, Markdown, and more. Used by hundreds of projects. The `factory.ts` and `src/configs/` directory architecture is the definitive reference for ESLint Flat Config composition patterns. Decision: **clone**

2. **node-modules-inspector** — An interactive web UI for inspecting your project's `node_modules`. Built as a Nuxt app with a WebContainer-powered backend, MCP server integration, devframe architecture, and a tree-visualization layer using nanovis. The most sophisticated open-source node_modules analysis tool available. Decision: **clone**

3. **vitesse** (antfu-collective/vitesse) — The definitive opinionated Vue 3 + Vite starter template. Integrates auto-imports, file-based routing, UnoCSS, Pinia, i18n, PWA, Cypress, SSG, and 15+ Vite plugins in a coherent, production-tested configuration. Has spawned dozens of variants. Decision: **clone**

4. **vscode-file-nesting-config** — A regularly-updated, community-maintained VS Code config that groups related files (e.g., `tsconfig.json` nesting all its variants). 3650 stars. Reference-only — it's a config file, not code architecture. Decision: **reference**

5. **utils** — Anthony's personal JavaScript/TypeScript utility collection. Small functions for type checking, array manipulation, promise utilities, and more. Useful as a reference for clean utility API design. Decision: **reference**

## Key findings
Anthony Fu's unique contribution to the ecosystem is **architectural**: his eslint-config demonstrates how to compose ESLint Flat Config at scale; his vitesse shows how to compose 15+ Vite plugins into a coherent DX; his node-modules-inspector shows how to build a Nuxt/WebContainer hybrid tool. He also pioneered the `unplugin` pattern (framework-agnostic plugins), the `@antfu/eslint-config` composable factory pattern, and the `vscode-ext-gen` type-safe extension metadata generation pattern. His skills repo (5410 stars) is an actively-curated collection of Claude Code agent skills — currently his most-starred repo as of 2026.

## Rejected repositories
- **antfu.me** — Personal website, primarily Markdown blog content
- **All archived repos** — Explicitly deprecated or merged upstream
- **Game/art repos** (handle, 100, vue-minesweeper) — Personal creative work without reuse value
- **All forks** — Upstream contributions, not original architecture
- **Older/unmaintained tools** (reactivue, v-dollar, vue-global-api) — Superseded by Vue 3 patterns
- **qrcode-toolkit / sd-webui-qrcode-toolkit** — Niche domain
- **live-draw** — C#/Windows desktop app, out of scope
