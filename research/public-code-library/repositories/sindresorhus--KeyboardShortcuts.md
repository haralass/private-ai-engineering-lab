# sindresorhus/KeyboardShortcuts

## Identity
- **Owner**: sindresorhus
- **Repository**: KeyboardShortcuts
- **URL**: https://github.com/sindresorhus/KeyboardShortcuts
- **Live URL**: N/A (Swift library)
- **Commit SHA**: 49c3fc04ea827f816df67843bfcc57286b47ff06
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push June 2026)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: SwiftPM library distributed as a package, not an application. Used as a dependency in hundreds of macOS apps.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: Swift
- **Frameworks**: AppKit, SwiftUI, Combine, Carbon framework (for hotkey registration)
- **Key dependencies**: Carbon.framework (system-level global hotkey interception)
- **Build system**: Swift Package Manager
- **Package manager**: SwiftPM
- **Tests**: yes — XCTest (RecorderLayoutTests, KeyboardShortcutsTests)
- **CI**: yes (GitHub Actions assumed)
- **Architecture**: Static enum `KeyboardShortcuts` as namespace. Shortcut `Name` as type-safe identifier (defined via extension). `RecorderCocoa` (NSView) and `Recorder` (SwiftUI view) for user input. Global hotkey registration via Carbon `InstallEventHandler`. Persistence via UserDefaults. Conflict detection policy.
- **State management**: UserDefaults for persistence; Combine publishers and async streams for observation
- **Rendering model**: Hybrid — AppKit `NSView` for recorder, SwiftUI view wrapping it

## Useful content (exact files)

### Directly reusable code
- `Sources/KeyboardShortcuts/KeyboardShortcuts.swift` — Core namespace: `onKeyDown`, `onKeyUp`, async stream handlers, `isEnabled`, `isPaused`, conflict policy, `storedNames`
- `Sources/KeyboardShortcuts/Name.swift` — Type-safe shortcut name definition via `extension KeyboardShortcuts.Name { static let myAction = Self("myAction") }`
- `Sources/KeyboardShortcuts/Shortcut.swift` — Shortcut value type (key + modifiers), Codable, Equatable
- `Sources/KeyboardShortcuts/HotKey.swift` — Carbon hotkey registration and management
- `Sources/KeyboardShortcuts/Recorder.swift` — SwiftUI `RecordShortcut` view for inline keyboard shortcut recording
- `Sources/KeyboardShortcuts/RecorderCocoa.swift` — AppKit NSView implementation of shortcut recorder
- `Sources/KeyboardShortcuts/ConflictPolicy.swift` — Conflict detection and resolution policy
- `Sources/KeyboardShortcuts/ViewModifiers.swift` — SwiftUI `onKeyboardShortcut` modifier

### Adaptable patterns
- **Type-safe named shortcut pattern**: Shortcuts are registered by name (`KeyboardShortcuts.Name`) defined as `static let` extensions. This creates a compile-time-checked enum-like namespace for all shortcuts in an app. Applicable to any resource-naming pattern.
- **Async stream observation**: `KeyboardShortcuts.events(for: .myAction)` returns an `AsyncStream<KeyboardShortcuts.EventType>` — clean modern Swift pattern for event observation without delegates or Combine.
- **Carbon hotkey registration wrapper**: `HotKey.swift` wraps the Carbon `InstallEventHandler` API in a modern Swift class with proper cleanup. Reusable for any global hotkey registration need.
- **Conflict policy API**: `ConflictPolicy` lets the app define behavior when a requested shortcut is already in use (allow, disallow with message). Good pattern for user-facing validation.

### Architecture reference
- Static enum as namespace pattern (not a class, not a struct) — avoids instantiation while keeping a clean API surface.
- Dual-target recorder (AppKit + SwiftUI wrapping) — shows the correct way to expose AppKit components to SwiftUI.

### Reference-only
- `Example/` — Complete example macOS app showing recorder UI and shortcut triggers

## Evaluation
**Problem solved**: User-customizable global keyboard shortcuts in macOS apps. Users can record their own shortcuts, which persist across launches and respect system conflicts.
**Original value**: High — this is the standard solution. The Carbon framework wrapper and type-safe name system are well-designed. No other library does this as cleanly in Swift.
**Future project types**: Any macOS menu bar app, utility app, or productivity tool that should support configurable hotkeys. Directly applicable to any Curator macOS companion app.
**Do not copy**: Do not use `HotKey.swift` without understanding the Carbon framework's threading requirements. The `InstallEventHandler` runs on the main thread.
**Risks**: Carbon framework is technically deprecated by Apple but continues to function. This dependency is unavoidable for global hotkeys in macOS.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 5 |
| Architecture | 5 |
| Design and UX | 4 |
| Accessibility | 4 |
| Performance | 5 |
| Testing | 4 |
| Documentation | 5 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: critical
**Action**: clone
