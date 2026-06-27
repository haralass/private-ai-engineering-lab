# sindresorhus/LaunchAtLogin-Modern

## Identity
- **Owner**: sindresorhus
- **Repository**: LaunchAtLogin-Modern
- **URL**: https://github.com/sindresorhus/LaunchAtLogin-Modern
- **Live URL**: N/A (Swift library)
- **Commit SHA**: a04ec1c363be3627734f6dad757d82f5d4fa8fcc
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push January 2024)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: Single-file SwiftPM package providing "launch at login" for macOS apps using the modern `SMAppService` API.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: Swift
- **Frameworks**: ServiceManagement (SMAppService), SwiftUI
- **Key dependencies**: none
- **Build system**: Swift Package Manager
- **Package manager**: SwiftPM
- **Tests**: no
- **CI**: no
- **Architecture**: Single file, single public enum `LaunchAtLogin`. Wraps `SMAppService.mainApp` with a `isEnabled` get/set property. Includes a SwiftUI `Toggle` component (`LaunchAtLogin.Toggle`).
- **State management**: N/A (stateless wrapper around system API)
- **Rendering model**: SwiftUI Toggle binding

## Useful content (exact files)

### Directly reusable code
- `Sources/LaunchAtLogin/LaunchAtLogin.swift` — Complete implementation. Single file (~100 lines). Contains: `LaunchAtLogin.isEnabled` (Bool getter/setter using SMAppService), `LaunchAtLogin.Toggle` (SwiftUI view).

### Adaptable patterns
- **SMAppService wrapper pattern**: The `SMAppService.mainApp.register()` / `.unregister()` pattern wrapped behind a simple Bool property. This is the correct modern replacement for `SMLoginItemSetEnabled` (deprecated). Any macOS app targeting macOS 13+ should use this.
- **Single-file library design**: The entire library is one Swift file. Good example of appropriate scope — when a feature genuinely needs only one file, don't add unnecessary abstraction.
- **SwiftUI Toggle wrapper**: `LaunchAtLogin.Toggle` is a one-liner wrapping a SwiftUI `Toggle` to a system capability. Pattern for wrapping system APIs in SwiftUI-idiomatic components.

### Architecture reference
- Demonstrates the correct entitlement requirement: macOS apps using `SMAppService` require `com.apple.security.app-sandbox` or the login items entitlement. The README documents this clearly.

### Reference-only
- README — Documents setup requirements including necessary entitlements and Info.plist changes

## Evaluation
**Problem solved**: "Launch at login" functionality for macOS apps using the modern `SMAppService` API (macOS 13+).
**Original value**: Medium — this wraps a single system API. The value is in knowing the correct API to use, the correct entitlements, and having a tested reference. The legacy `SMLoginItemSetEnabled` approach is deprecated and incorrect for sandboxed apps.
**Future project types**: Any macOS menu bar app or utility app that should start on login (Curator companion app, indexer daemon, etc.).
**Do not copy**: Requires macOS 13+. If targeting macOS 12 or earlier, use `LaunchAtLogin-Legacy` (archived) or handle `SMLoginItemSetEnabled` separately.
**Risks**: Minimal. Simple wrapper. No external dependencies.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 3 |
| General usefulness | 5 |
| Architecture | 4 |
| Design and UX | 3 |
| Accessibility | 3 |
| Performance | 5 |
| Testing | 1 |
| Documentation | 4 |
| Maintenance health | 4 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: high
**Action**: clone
