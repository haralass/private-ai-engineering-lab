# sindresorhus/Defaults

## Identity
- **Owner**: sindresorhus
- **Repository**: Defaults
- **URL**: https://github.com/sindresorhus/Defaults
- **Live URL**: N/A (Swift library)
- **Commit SHA**: 00a7465a0668a87fa159e779b9d80f1f9652357e
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push June 2026)
- **Transferred**: no

## Relationship classification
reusable-engine-or-library

Evidence: SwiftPM library for type-safe UserDefaults. Actively maintained, extensively tested.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: Swift
- **Frameworks**: Foundation, Combine, SwiftUI, Observation framework
- **Key dependencies**: none (pure Swift)
- **Build system**: Swift Package Manager
- **Package manager**: SwiftPM
- **Tests**: yes — extensive XCTest suite: bridge tests, stress tests, Codable, Set, Range, Color, NSColor, NSSecureCoding, iCloud, enum, array, collection, validation, custom bridge tests
- **CI**: yes
- **Architecture**: Static subscript on `Defaults` enum using `Key<Value>` typed keys. `@Default` property wrapper for SwiftUI integration. `@ObservableDefault` macro for `@Observable` classes. iCloud sync via NSUbiquitousKeyValueStore. Custom serialization bridge protocol (`DefaultsSerializable`).
- **State management**: UserDefaults + optional iCloud KV sync; Combine publishers for observation
- **Rendering model**: N/A (library); SwiftUI `@Default` property wrapper triggers view updates

## Useful content (exact files)

### Directly reusable code
- `Sources/Defaults/Defaults.swift` — Core: `Defaults[.key]` subscript, `@Default` property wrapper, type definitions
- `Sources/Defaults/Defaults+Bridge.swift` (or equivalent) — `DefaultsSerializable` protocol for custom type serialization
- `Sources/Defaults/Defaults+iCloud.swift` — iCloud key-value store sync layer
- `Sources/Defaults/Defaults+Observation.swift` — Combine + async stream observation of key changes
- `Sources/DefaultsMacrosDeclarations/` — Swift macro implementations for `@ObservableDefault`

### Adaptable patterns
- **Subscript-based typed API**: `Defaults[.myKey]` reads/writes. Key type is `Defaults.Key<Value>` defined as `extension Defaults.Keys { static let myKey = Key<Bool>("myKey", default: false) }`. This pattern provides compile-time type safety over string-keyed dictionaries. Adaptable to any typed configuration store.
- **Custom serialization bridge**: `DefaultsSerializable` protocol allows any type (including custom structs, enums, SwiftUI `Color`) to be stored in UserDefaults. Shows the correct approach to serialization-protocol design.
- **iCloud sync**: The iCloud layer observes `NSUbiquitousKeyValueStore.didChangeExternallyNotification` and merges remote changes into local UserDefaults. Pattern for lightweight cross-device sync without CloudKit.
- **Property wrapper + macro dual approach**: Offers both `@Default(.key) var setting` (property wrapper) and `@ObservableDefault(.key)` macro for `@Observable` classes. Shows how to support both paradigms.

### Architecture reference
- The `Defaults.Keys` pattern (static properties on a class, extended externally) is reusable as a type-safe configuration key system in any Swift project.
- Stress tests in `DefaultsStressTests.swift` show concurrent read/write patterns.

### Reference-only
- `Tests/DefaultsTests/` — Comprehensive test coverage shows expected behavior for every edge case (nil handling, optional types, RawRepresentable enums, Codable types, etc.)

## Evaluation
**Problem solved**: Type-safe, Swifty UserDefaults access with property wrappers, iCloud sync, Combine observation, and `@Observable` support.
**Original value**: High — the most comprehensive open-source UserDefaults library for Swift. The macro support and iCloud sync layer go significantly beyond what any alternative offers.
**Future project types**: Any macOS/iOS app storing user preferences. Essential for a macOS Curator companion app, menu bar tools, or any Swift app with settings.
**Do not copy**: Do not use the iCloud sync layer without understanding the merge strategy — simultaneous writes from multiple devices can produce conflicts that require conflict resolution logic.
**Risks**: Swift macro support requires Swift 5.9+/Xcode 15+. The macro is optional — the library works without it.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 5 |
| Architecture | 5 |
| Design and UX | 4 |
| Accessibility | 3 |
| Performance | 5 |
| Testing | 5 |
| Documentation | 5 |
| Maintenance health | 5 |
| Licensing clarity | 5 |
| Long-term lab value | 5 |

**Priority**: critical
**Action**: clone
