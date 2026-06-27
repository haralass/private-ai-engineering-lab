# Sindre Sorhus

**GitHub**: sindresorhus
**Website**: https://sindresorhus.com
**Analysis date**: 2026-06-27

## Profile overview
Sindre Sorhus is one of the most prolific open-source contributors alive, with over 1,000 public repositories spanning Node.js utilities, TypeScript type libraries, macOS Swift applications, and Electron tools. He is the author of `type-fest` (17,000+ stars), `ky` (17,000+ stars), `modern-normalize`, `Gifski`, and hundreds of foundational npm packages. He is also deeply embedded in the macOS/Swift ecosystem with production-quality SwiftPM libraries used by thousands of apps. His org memberships include xojs, avajs, chalk, yeoman, and refined-github.

## Repository inventory (selected — top value only)

### Swift/macOS libraries
| Repository | Stars | Status | Decision |
|-----------|-------|--------|----------|
| Gifski | 8,473 | Active | Reference |
| KeyboardShortcuts | 2,648 | Active | Clone |
| Defaults | 2,474 | Active | Clone |
| Settings | 1,546 | Active | Clone |
| DockProgress | 1,350 | Active | Clone |
| LaunchAtLogin-Modern | 573 | Active | Clone |
| Pasteboard-Viewer | 838 | Active | Reference |
| macos-wallpaper | 700 | Active | Reference |
| CircularProgress | 577 | Active | Reference |
| Blear | 563 | Active | Reference |
| touch-bar-simulator | 1,933 | Archived | Reject |
| LaunchAtLogin-Legacy | 1,617 | Archived | Reject |
| macos-trash | 438 | Active | Reference |

### TypeScript/JavaScript libraries
| Repository | Stars | Status | Decision |
|-----------|-------|--------|----------|
| type-fest | 17,244 | Active | Reference |
| ky | 16,954 | Active | Reference |
| modern-normalize | 7,350 | Active | Reference |
| slugify | 2,697 | Active | Reference |
| ow | 3,868 | Active | Reference |
| emittery | 2,068 | Active | Reference |
| on-change | 2,027 | Active | Reference |
| create-dmg | 5,308 | Active | Reference |
| capture-website | 2,011 | Active | Reference |
| nano-spawn | 586 | Active | Reference |
| ts-extras | 785 | Active | Reference |
| yoctocolors | 865 | Active | Reference |
| electron-util | 1,337 | Active | Reference |
| electron-better-ipc | 744 | Active | Reference |
| css-extras | 969 | Active | Reference |

## Original vs forks vs transferred
All repos above are original works. Sindre does not maintain notable public forks. The `dip/cmdk` repo (Paco Coursey) is mentioned in his orgs tangentially but is not his work. His orgs (xojs, avajs, chalk, gruntjs, yeoman) are separate collaborative organizations.

## Strongest repositories
- **KeyboardShortcuts**: Production SwiftPM library for user-customizable global hotkeys in macOS apps. Includes conflict detection, SwiftUI recorder view, Combine/async streams. Cloned.
- **Defaults**: Strongly-typed, iCloud-syncing UserDefaults wrapper for Swift. Uses `@Observable`, property wrapper macros, codable types. The most sophisticated UserDefaults replacement in the Swift ecosystem. Cloned.
- **Settings**: Drop-in settings window for macOS apps. Supports toolbar-item and segmented-control styles, handles window centering. Cloned.
- **DockProgress**: Show animated progress in the macOS Dock icon. Easing curves, bar/circular/pie/badge styles, SwiftUI compatible. Cloned.
- **LaunchAtLogin-Modern**: Single-file SwiftPM package for "launch at login" using `SMAppService`. Cloned.
- **Gifski**: Full macOS app for converting video to GIF. Open source, MIT. Best example of a complete, production-shipped Mac app in his portfolio. Reference only (too large to clone productively).
- **type-fest**: 17,000+ star TypeScript type utility collection. Canonical reference for complex TypeScript generics. Reference only.
- **ky**: 17,000+ star HTTP client based on Fetch API. Tiny, TypeScript-first, hooks system. Reference only.
- **modern-normalize**: Minimal CSS normalization (successor to normalize.css). 7,000+ stars, tiny. Reference.

## Key findings
Sindre's macOS Swift libraries are uniquely valuable for any native macOS development. Together, `KeyboardShortcuts` + `Defaults` + `Settings` + `DockProgress` + `LaunchAtLogin-Modern` constitute a complete foundation for building polished macOS menu bar apps. Each is production-quality: tests, CI, clean public APIs, well-documented, actively maintained. His JS/TS work is equally extraordinary in volume and quality but is better consumed as npm packages rather than cloned source — the value is in the APIs and patterns, not the implementations.

## Rejected repositories
- `touch-bar-simulator` — Archived, Touch Bar is removed from modern Macs
- `LaunchAtLogin-Legacy` — Archived, superseded by LaunchAtLogin-Modern (uses `SMLoginItemSetEnabled`, deprecated)
- All npm/JS repos: referenced but not cloned — better consumed from npm
- `awesome-chatgpt`, `awesome-whisper` — Awesome lists, no code
- `css-in-readme-like-wat` — Joke repo
- `Actions` — No license file found, cannot verify reuse rights
- `System-Color-Picker` — No license file found in repo metadata
- `Plash` — No license file found
