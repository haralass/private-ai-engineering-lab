# Native macOS Catalogue
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

Swift and macOS-specific libraries, utilities, patterns, and applications from the lab's research. All from Sindre Sorhus unless noted.

---

## Reusable SwiftPM Libraries (Drop-In)

### sindresorhus/Settings
**Clone**: `external-sources/native-macos/Settings`  
**License**: MIT  
**Status**: Active (November 2025)  
**Stars**: 1,546  
**What**: Drop-in multi-pane settings window for macOS apps. Accepts an array of `SettingsPane`-conforming view controllers and renders a standard macOS Settings window.  
**When to use**: Any macOS app needing a preferences/settings window.  
**Key files**:
- `Sources/Settings/SettingsTabViewController.swift` — tab management
- `Sources/Settings/SettingsPane.swift` — pane protocol
- `Sources/Settings/Container.swift` — SwiftUI pane embedding

### sindresorhus/KeyboardShortcuts
**Clone**: `external-sources/native-macos/KeyboardShortcuts`  
**License**: MIT  
**Status**: Active (June 2026)  
**Stars**: 2,648  
**What**: User-definable global keyboard shortcuts for macOS apps. User sets shortcuts in settings UI, stored in UserDefaults, work system-wide.  
**When to use**: Any macOS app that needs a global keyboard shortcut (e.g., activate app, trigger action while app is in background).  
**Integration**: SwiftUI `KeyboardShortcuts.Recorder` view for settings UI. `KeyboardShortcuts.onKeyUp(for:)` for triggering actions.

### sindresorhus/Defaults
**Clone**: `external-sources/native-macos/Defaults`  
**License**: MIT  
**Status**: Active (June 2026)  
**Stars**: 2,474  
**What**: Type-safe UserDefaults wrapper using Swift property wrappers and Swift Macros.  
**When to use**: Any macOS app storing persistent user preferences. Replaces raw `UserDefaults.standard.set/get`.  
**Key feature**: Supports Codable types, optional types, arrays, dictionaries. Works with iCloud sync. Thread-safe.

### sindresorhus/DockProgress
**Clone**: `external-sources/native-macos/DockProgress`  
**License**: MIT  
**Status**: Active (January 2026)  
**Stars**: 1,350  
**What**: Progress bar overlaid on the app's Dock icon. Visible while the app is in background.  
**When to use**: File conversion, download, export operations — lets users monitor progress without switching to the app.  
**API**: `DockProgress.progress = 0.5` or `DockProgress.resetProgress()`.

### sindresorhus/LaunchAtLogin-Modern
**Clone**: `external-sources/native-macos/LaunchAtLogin-Modern`  
**License**: MIT  
**Status**: Active (January 2024)  
**Stars**: 573  
**What**: "Launch at Login" toggle for macOS apps. Uses the modern `ServiceManagement` framework (not the legacy Login Items approach).  
**When to use**: Any utility/background app (menu bar app, agent) that the user may want to auto-launch.  
**API**: `LaunchAtLogin.isEnabled = true/false`. SwiftUI `LaunchAtLogin.Toggle` view.

### sindresorhus/macos-trash
**Clone**: `external-sources/native-macos/macos-trash`  
**License**: MIT  
**Status**: Active (February 2026)  
**Stars**: 438  
**What**: Move files to macOS Trash correctly via `NSWorkspace.shared.recycle()`. Preserves "Put Back" in Finder. Plays trash sound.  
**When to use**: Any macOS app offering a "Delete" action — should send to Trash, not permanently delete.  
**Critical**: `FileManager.removeItem` permanently deletes. `FileManager.trashItem` does not play sound or support "Put Back". This library uses the correct API.

---

## Reference Applications (Architecture Study)

### sindresorhus/Gifski
**Clone**: `external-sources/native-macos/Gifski`  
**License**: MIT  
**Status**: Active (June 2026)  
**Stars**: 8,473  
**What**: macOS video → GIF converter app with full SwiftUI interface.  
**Why study**: Production-quality SwiftUI macOS app with:
- `@Observable` centralized state (AppState.swift)
- Multi-screen conversion flow (Start → Edit → Convert → Done)
- AVFoundation video trimming
- C FFI integration (gifski Rust library)
- App Intents / Shortcuts support
- Drop zone + validation
- Aspect-ratio-locked dimension editing
- Pre-computation estimates (EstimatedFileSize.swift)

---

## Patterns for macOS Development

### UserDefaults — Correct Approach
Use `sindresorhus/Defaults`. Raw `UserDefaults` calls are error-prone (typos in keys, wrong types, no default-value management). `@Default(.myKey)` is type-safe and Observable-compatible.

### Settings Window — Correct Approach
Use `sindresorhus/Settings`. Building a settings window from scratch is complex (NSToolbar, pane sizing, animation). This library is the canonical community solution.

### Global Keyboard Shortcuts
Use `sindresorhus/KeyboardShortcuts`. The built-in `NSEvent.addGlobalMonitorForEvents` pattern is verbose and doesn't support user customization. This library adds user-configurable shortcuts with a SwiftUI recorder view.

### Trash (Not Delete)
Use `sindresorhus/macos-trash` or call `NSWorkspace.shared.recycle(_:completionHandler:)` directly. Never call `FileManager.removeItem` for user-visible file deletion. Permanent deletion without warning is a serious macOS UX violation.

### Launch at Login
Use `sindresorhus/LaunchAtLogin-Modern`. The older Login Items API (`LSSharedFileList`) is deprecated. The `ServiceManagement` framework API is the correct modern approach.

### Dock Progress
Use `sindresorhus/DockProgress`. Long-running operations should show progress in the Dock tile via `NSApp.dockTile`.

---

## macOS UI Guidelines Observed in These Repos

| Pattern | Source |
|---------|--------|
| Settings window auto-resizes to pane content | sindresorhus/Settings |
| Settings window centers on screen when resizing | sindresorhus/Settings |
| Preferences key names follow `com.companyname.appname.setting` convention | sindresorhus/Defaults |
| File drop zones validate immediately, not on accept | sindresorhus/Gifski |
| Destructive operations offer Trash, not direct delete | sindresorhus/macos-trash |
| App Intents expose the app's core operation to Shortcuts | sindresorhus/Gifski |
| @Observable replaces @ObservableObject in macOS 14+ | sindresorhus/Gifski |
| Progress visible in Dock for long operations | sindresorhus/DockProgress |

---

## Dependency Matrix for macOS Projects

| Need | Library | License | Status |
|------|---------|---------|--------|
| Settings window | sindresorhus/Settings | MIT | active |
| User preferences | sindresorhus/Defaults | MIT | active |
| Global keyboard shortcuts | sindresorhus/KeyboardShortcuts | MIT | active |
| Dock progress | sindresorhus/DockProgress | MIT | active |
| Launch at login | sindresorhus/LaunchAtLogin-Modern | MIT | active |
| Trash (not delete) | sindresorhus/macos-trash | MIT | active |
| Full app reference | sindresorhus/Gifski | MIT | active |

All six utility libraries are MIT-licensed and actively maintained by the same author. They form a complementary suite.
