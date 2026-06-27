# sindresorhus/Gifski

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | sindresorhus |
| Repository | Gifski |
| URL | https://github.com/sindresorhus/Gifski |
| Live URL | https://sindresorhus.com/gifski |
| Commit SHA | b765f6cdb886ee9c8d6ed278c2bc3e72a24cbfa0 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active |
| Last Meaningful Push | 2026-06-10 |

## Legal Status

| Field | Value |
|-------|-------|
| License | MIT |
| Attribution Required | yes (Sindre Sorhus + Kornel Lesiński) |
| Code Reuse | clearly permitted |
| Reference-Only | no |

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | Swift |
| Framework | SwiftUI + AppKit (Xcode project, macOS 14+) |
| Major Dependencies | gifski (Rust CLI via Swift wrapper), AVFoundation, AppKit |
| Build System | Xcode |
| Test System | XCTest |
| Repository Structure | `Gifski/` (main app target: Swift source files, Xcode assets), `Tests/` |
| Architecture | SwiftUI/AppKit macOS app. Multi-screen flow: StartScreen → EditScreen (trim, resize, quality) → ConversionScreen (progress) → CompletedScreen. Uses AVFoundation for video reading and the gifski Rust library (via C FFI wrapper) for GIF encoding. |

## Actual Valuable Content

### Architecture: Multi-Screen Conversion Flow

The app implements a clear state-machine-driven screen flow that is a strong reference pattern for any file-conversion macOS app:

**`App.swift`** — Entry point with `@main` attribute. Window setup using SwiftUI `WindowGroup`.

**`AppState.swift`** — Central shared state using `@Observable` (Swift 5.9 Observation framework). Holds the current conversion parameters (video URL, start/end trim, quality, resolution, FPS). Single source of truth.

**`StartScreen.swift`** — Drop zone and file picker. Validates dropped files via `VideoValidator`.

**`EditScreen.swift`** — Trim handles, quality slider, resize controls. Binds directly to `AppState`.

**`ConversionScreen.swift`** — Progress display during async conversion.

**`CompletedScreen.swift`** — Result preview and save/share actions.

### Valuable Patterns

**`ResizableDimensions.swift`**
- Maintains aspect-ratio-locked width/height editing: when the user types a width, height is automatically calculated. Handles both pixel and percentage inputs. The `ResizableDimensions` struct with `mutating func apply(width:)` / `apply(height:)` is a clean pattern for constrained dimension editing.

**`EstimatedFileSize.swift`**
- Estimates output file size from conversion parameters (resolution, FPS, quality, duration) before the conversion runs. Good reference for pre-computation estimates on file processing.

**`VideoValidator.swift`**
- Validates dropped/picked files: checks MIME type, video codec compatibility, minimum dimensions. Pattern for robust drop-zone validation in macOS.

**`GifskiWrapper.swift`**
- Swift wrapper around the gifski C library. Demonstrates the Swift/C FFI bridging pattern with unsafe pointers, bridging header, and callbacks.

**`ExportModifiedVideo.swift`**
- Trims a video using AVFoundation `AVAssetExportSession` before encoding. Reference for video trimming in SwiftUI macOS app.

**`Intents.swift`**
- Shortcuts/App Intents integration for macOS. Demonstrates exposing conversion as a Shortcuts action.

**`Utilities.swift`**
- Collection of extension methods on standard types. `@discardableResult` patterns, async/await wrappers for AVFoundation callbacks.

**`Components/`** — Custom SwiftUI/AppKit components for the UI (trim slider, quality knob, etc.).

## Value Classification

| Item | Classification |
|------|---------------|
| Multi-screen file conversion app pattern | architecture reference |
| ResizableDimensions aspect-ratio lock | directly reusable code |
| VideoValidator drop-zone validation | adaptable implementation pattern |
| C FFI bridging (GifskiWrapper) | architecture reference |
| AVFoundation video trimming | adaptable implementation pattern |
| App Intents / Shortcuts integration | adaptable implementation pattern |
| EstimatedFileSize pre-computation | adaptable implementation pattern |
| AppState with @Observable | architecture reference |

## General Usefulness

**Problem it solves**: Converts videos to high-quality GIFs with a polished macOS UI.

**Why the implementation is notable**: This is production-quality macOS SwiftUI code from 2024–2026. It uses modern Swift APIs (`@Observable`, async/await, App Intents), has a clear state machine for multi-step file processing, and demonstrates C FFI integration cleanly. The `ResizableDimensions` and `VideoValidator` patterns are directly applicable to any macOS file-processing app.

**Future project uses**:
- File conversion app architecture
- Drop zone + validation pattern for macOS
- Aspect-ratio-locked dimension editing
- C library integration in Swift
- App Intents for macOS Shortcuts

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 5/5 | Excellent Swift; idiomatic modern patterns |
| Architecture | 5/5 | Clean state machine, clear screen separation |
| Maintainability | 5/5 | Actively maintained; uses latest Swift APIs |
| Testing Quality | 3/5 | Basic XCTest; limited coverage |
| Documentation | 3/5 | Code is self-documenting; README minimal |
| Dependency Risk | 4/5 | Depends on gifski Rust binary; otherwise no deps |
| Security | 4/5 | Sandboxed; entitlements file present |
| Originality | 4/5 | Excellent reference for file-conversion app pattern |
| Long-term Usefulness | 5/5 | macOS SwiftUI patterns are current and lasting |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 5 |
| Originality | 4 |
| General Reusability | 4 |
| Educational Value | 5 |
| Design / UX Quality | 5 |
| Architecture Quality | 5 |
| Documentation Quality | 3 |
| Maintenance Health | 5 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 5 |

**Final Priority**: critical  
**Recommended Action**: clone (done — `external-sources/native-macos/Gifski`)
