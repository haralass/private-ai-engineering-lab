# sindresorhus/DockProgress

## Identity
- **Owner**: sindresorhus
- **Repository**: DockProgress
- **URL**: https://github.com/sindresorhus/DockProgress
- **Live URL**: N/A (Swift library)
- **Commit SHA**: 43ca0137201a479fbd891b129155f4a84c1e1b13
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push January 2026)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: SwiftPM library for macOS Dock icon progress visualization.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: Swift
- **Frameworks**: AppKit, SwiftUI, Foundation
- **Key dependencies**: none
- **Build system**: Swift Package Manager
- **Package manager**: SwiftPM
- **Tests**: yes — `Tests/DockProgressTests/SwiftUITests.swift`
- **CI**: yes
- **Architecture**: Static `@MainActor enum DockProgress`. Internal `DisplayLinkObserver` (CVDisplayLink wrapper) for smooth animation. Custom `NSImage` compositing to overdraw the Dock tile. Multiple visual styles: bar, circular progress, pie, badge. Easing functions for smooth progress interpolation.
- **State management**: Static stored properties on the enum (single shared instance)
- **Rendering model**: Custom NSImage drawn to the app's Dock tile via `NSApp.dockTile`

## Useful content (exact files)

### Directly reusable code
- `Sources/DockProgress/DockProgress.swift` — Core implementation: `progress` setter, `progressInstance` for multiple concurrent progress indicators, DisplayLink animation, `updateDockIcon()` drawing
- `Sources/DockProgress/Utilities.swift` — `Easing` struct with `linearInterpolation` and `easeInOut`, `DisplayLinkObserver` (CVDisplayLink abstraction), custom drawing helpers

### Adaptable patterns
- **CVDisplayLink abstraction**: `DisplayLinkObserver` wraps `CVDisplayLink` (a C API for display-synchronized callbacks) in a clean Swift class. The callback fires at 60fps and provides the elapsed time since last frame. This pattern is reusable for any animation loop synchronized to the display refresh rate.
- **Easing interpolation**: `Easing.easeInOut(progress:)` and `Easing.linearInterpolation(start:end:progress:)` are clean, dependency-free easing functions. Extract for any animation use case.
- **NSApp Dock tile drawing**: `NSApp.dockTile.contentView` + `NSApp.dockTile.display()` is the correct pattern for custom Dock icon drawing. DockProgress shows how to composite custom graphics over the existing app icon.
- **Smooth progress display**: The `displayedProgress` vs `progress` separation (animated displayed, instant underlying) is a good pattern for any progress visualization that should animate smoothly between values rather than jumping.

### Architecture reference
- Static enum as a lightweight singleton: no instantiation needed, thread-safe via `@MainActor`. Good for global state that should not be instantiated multiple times.

### Reference-only
- `Example/` — SwiftUI app showing all four progress styles with a slider

## Evaluation
**Problem solved**: Show animated progress in the macOS Dock icon during long operations (downloads, exports, processing).
**Original value**: Medium-high — solving this correctly requires CVDisplayLink, Dock tile compositing, and easing. The library handles all of it. The easing utilities are independently useful.
**Future project types**: Any macOS app with long-running background operations (file processing, model inference, export). A Curator macOS sidecar doing indexing could surface progress in the Dock.
**Do not copy**: The Dock tile drawing code is specifically for macOS. Do not adapt it expecting iOS compatibility. CVDisplayLink is macOS/iOS-only.
**Risks**: CVDisplayLink may be deprecated in favor of `CADisplayLink` on macOS 14+ (a note in the source mentions this as a TODO).

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 4 |
| General usefulness | 4 |
| Architecture | 4 |
| Design and UX | 5 |
| Accessibility | 3 |
| Performance | 5 |
| Testing | 3 |
| Documentation | 4 |
| Maintenance health | 4 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: high
**Action**: clone
