# New Candidates Discovered
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

Repositories discovered during profile exploration that were not in the original approved list but show potential lab value. These have been added to the candidate pool — they require further analysis before cloning or adopting.

---

## Priority Candidates (Recommend Analysis)

### raunofreiberg/ui-playbook
- **URL**: https://github.com/raunofreiberg/ui-playbook
- **Owner**: Rauno Freiberg (one of 13 people)
- **Stars**: ~1,000+
- **Language**: JavaScript/React
- **What**: Documentation of UI component implementation best practices with interactive examples
- **Why valuable**: Companion to the `interfaces` project. While `interfaces` documents behavioral rules, `ui-playbook` shows implementation approaches for common UI components (modals, tooltips, selects, etc.) with accessibility baked in.
- **Status**: stale (last push 2022-2023)
- **License**: NONE found at time of discovery
- **Action needed**: Verify license. If found, analyze the component examples and documented patterns.
- **Analysis file**: `repositories/raunofreiberg--ui-playbook.md`

### sindresorhus/file-type
- **URL**: https://github.com/sindresorhus/file-type
- **Owner**: Sindre Sorhus (one of 13 people)
- **Stars**: 4,303
- **Language**: JavaScript/TypeScript
- **What**: Detects the file type of a buffer/stream by reading its magic bytes (not file extension).
- **Why valuable**: File type detection by content, not extension, is critical for any file processing pipeline. Used by 1M+ npm projects. Zero native dependencies.
- **Status**: Active
- **License**: MIT
- **Action needed**: Clone and analyze. Strong candidate for any file ingestion/validation system.

### sindresorhus/p-queue
- **URL**: https://github.com/sindresorhus/p-queue
- **Owner**: Sindre Sorhus (one of 13 people)
- **Stars**: 4,226
- **Language**: TypeScript
- **What**: Promise queue with concurrency control. Run at most N async operations simultaneously.
- **Why valuable**: Any file processing pipeline, API rate limiting, or background job system needs concurrency control. This is the canonical TypeScript implementation.
- **Status**: Active
- **License**: MIT
- **Action needed**: Clone and analyze. Directly useful for batch processing tasks.

### sindresorhus/ky
- **URL**: https://github.com/sindresorhus/ky
- **Owner**: Sindre Sorhus (one of 13 people)
- **Stars**: 16,954
- **Language**: TypeScript
- **What**: Tiny, elegant HTTP client based on the Fetch API. Replaces `axios` with a smaller, modern alternative.
- **Why valuable**: Most projects use Axios for HTTP requests. `ky` is ~5KB, uses Fetch natively (works in browsers and Deno/Bun), supports retry, timeout, hooks, and JSON by default.
- **Status**: Active
- **License**: MIT
- **Action needed**: Metadata-only (well-documented on npm; no need to clone for reference).

### sindresorhus/System-Color-Picker
- **URL**: https://github.com/sindresorhus/System-Color-Picker
- **Owner**: Sindre Sorhus (one of 13 people)
- **Stars**: 1,405
- **Language**: Swift
- **What**: macOS color picker app with additional features (hex input, color history, larger UI).
- **Why valuable**: Reference for: NSColorPanel usage, color conversion utilities (NSColor → hex/RGB/HSL), macOS menu bar app pattern, SwiftUI color picker integration.
- **Status**: Active
- **License**: MIT
- **Action needed**: Clone and analyze the color conversion utilities and menu bar pattern.

### sindresorhus/Pasteboard-Viewer
- **URL**: https://github.com/sindresorhus/Pasteboard-Viewer
- **Owner**: Sindre Sorhus (one of 13 people)
- **Stars**: 838
- **Language**: Swift
- **What**: macOS app to inspect the system pasteboards (clipboard contents in all formats).
- **Why valuable**: Reference for reading NSPasteboard in all its formats (types, data, string). Useful for any macOS app that handles paste operations or clipboard integration.
- **Status**: Active
- **License**: MIT
- **Action needed**: Inspect NSPasteboard reading patterns.

### antfu/eslint-config
- **URL**: https://github.com/antfu/eslint-config
- **Owner**: Anthony Fu (one of 13 people)
- **Stars**: 6,214
- **Language**: TypeScript
- **What**: Single opinionated ESLint flat-config preset covering TypeScript, Vue, React, JSON, YAML, Markdown.
- **Why valuable**: Replaces a long list of individual plugin configurations with one import. Used by many major Vue/TypeScript projects. ESLint flat config format is the future.
- **Status**: Active
- **License**: MIT
- **Action needed**: Metadata-only (installable from npm; no need to clone).
- **Analysis file**: `repositories/antfu--eslint-config.md`

### antfu/drauu
- **URL**: https://github.com/antfu/drauu
- **Owner**: Anthony Fu (one of 13 people)
- **Stars**: 1,507
- **Language**: TypeScript
- **What**: Headless SVG-based drawing board for the browser. Draw on any SVG element.
- **Why valuable**: Canvas/SVG drawing primitives (freehand, lines, rectangles, circles). Framework-agnostic. Undo/redo support.
- **Status**: Active
- **License**: MIT
- **Action needed**: Clone and analyze freehand drawing implementation.

### bchiang7/v4
- **URL**: https://github.com/bchiang7/v4
- **Owner**: Brittany Chiang (one of 13 people)
- **Stars**: 8,262
- **Language**: JavaScript/Gatsby
- **What**: Fourth iteration of Brittany Chiang's personal portfolio site. Frequently cited as a high-quality portfolio reference.
- **Why valuable**: Clean portfolio layout; well-designed experience section; strong visual hierarchy. However, uses Gatsby (legacy).
- **Status**: stale (2021)
- **License**: NONE
- **Action needed**: Visual reference only. Do not clone.
- **Analysis file**: `repositories/bchiang7--v4.md`

### LeaVerou/parsel
- **URL**: https://github.com/LeaVerou/parsel
- **Owner**: Lea Verou (one of 13 people)
- **Stars**: 600+
- **Language**: JavaScript
- **What**: A tiny, zero-dependency CSS selector parser and specificity calculator.
- **Why valuable**: Useful for any tool that needs to analyze, transform, or calculate specificity of CSS selectors. Officially referenced by the CSS Working Group.
- **Status**: stale (2023)
- **License**: MIT
- **Action needed**: Metadata-only (installable from npm).
- **Analysis file**: `repositories/LeaVerou--parsel.md`

### antfu-collective/vitesse
- **URL**: https://github.com/antfu-collective/vitesse
- **Owner**: antfu-collective (Anthony Fu's org)
- **Stars**: 9,000+
- **Language**: TypeScript/Vue 3
- **What**: Opinionated Vite + Vue 3 starter template with everything wired up.
- **Why valuable**: Complete project scaffold: Vue 3, TypeScript, Vite, UnoCSS, Pinia, VueRouter, auto-imports, i18n, Vitest, Cypress. A good starting point for any Vue 3 project.
- **Status**: Active
- **License**: MIT
- **Action needed**: Study the starter template configuration as an architecture reference for Vue 3 projects.
- **Analysis file**: `repositories/antfu-collective--vitesse.md`

---

## Lower Priority Candidates (Document, Do Not Clone)

| Repository | Owner | Why Interesting | Action |
|-----------|-------|----------------|--------|
| sindresorhus/github-markdown-css | Sindre Sorhus | Minimal CSS to replicate GitHub Markdown styling. 8,893 stars. | Metadata-only |
| sindresorhus/macos-wallpaper | Sindre Sorhus | macOS wallpaper control from Swift. Reference for NSWorkspace wallpaper APIs. | Metadata-only |
| sindresorhus/create-dmg | Sindre Sorhus | CLI to create polished macOS .dmg. 5,308 stars. | Metadata-only |
| sindresorhus/update-notifier | Sindre Sorhus | Update notifications for CLI apps. Canonical pattern. | Metadata-only |
| taniarascia/new-moon | Tania Rascia | Popular dark theme (1,228 stars). | Visual reference only |
| taniarascia/primitive | Tania Rascia | Front-end design toolkit (940 stars). | Metadata-only |
| antfu/qrcode-toolkit | Anthony Fu | QR code generation with AI-assisted design. | Metadata-only |
| antfu/live-draw | Anthony Fu | Draw on screen in real-time (848 stars). | Metadata-only |
| raunofreiberg/vesper | Rauno Freiberg | VS Code dark theme (762 stars). | Visual reference only |
| jh3y/driveway | Jhey Tompkins | Pure CSS masonry layouts (641 stars). | CSS reference |
| kentcdodds/mdx-bundler | Kent C. Dodds | MDX/TSX bundler for React (1,900 stars). | Metadata-only |

---

## Candidates Rejected After Review

| Repository | Owner | Why Rejected |
|-----------|-------|-------------|
| sindresorhus/touch-bar-simulator | Sindre Sorhus | Archived; Touch Bar removed from MacBook Pro |
| sindresorhus/LaunchAtLogin-Legacy | Sindre Sorhus | Archived; superseded by LaunchAtLogin-Modern |
| antfu/shikiji | Anthony Fu | Archived; experimental predecessor to current Shiki |
| antfu/vitesse-nuxt | Anthony Fu | Template variant; vitesse (above) is more general |
| antfu/reactivue | Anthony Fu | Stale (2021); experimental Vue composition in React |
| antfu/shiki-stream | Anthony Fu | Archived; integrated into shiki itself |
| kentcdodds/stop-runaway-react-effects | Kent C. Dodds | Debug tool; stale (2021) |

---

## Total Discovered

- **Priority candidates (recommend analysis)**: 10
- **Lower priority candidates**: 11
- **Rejected after brief review**: 7
- **Total new candidates identified**: 28 (beyond the original 40 approved repos)
