# sindresorhus/macos-trash

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | sindresorhus |
| Repository | macos-trash |
| URL | https://github.com/sindresorhus/macos-trash |
| Live URL | N/A (Swift command-line tool + library) |
| Commit SHA | 9358749ddd1b06b3bfa0a5a2e5af3e3bfcfef35a |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active |
| Last Meaningful Push | 2026-02-25 |

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
| Primary Language | Swift |
| Framework | AppKit (Foundation for CLI) |
| Major Dependencies | none |
| Build System | Swift Package Manager |
| Test System | none visible |
| Repository Structure | `Sources/` (SwiftPM library + executable targets) |
| Architecture | Swift library + CLI tool. Uses `NSWorkspace.shared.recycle(_:completionHandler:)` on macOS to move files to Trash (preserves undo-ability). Falls back for older macOS versions. |

## Actual Valuable Content

### Core API: Trash Files Correctly on macOS

**`Sources/`** — The library exposes a `Trash` type (or top-level function) that calls `NSWorkspace.shared.recycle(_:completionHandler:)` — the only API that:
1. Moves files to the system Trash (not just deletes them)
2. Preserves the "Put Back" context menu entry
3. Plays the system trash sound
4. Respects user's "Empty Trash Immediately" preference

This is distinct from `FileManager.removeItem(atPath:)` which permanently deletes, and from `fm.trashItem(at:resultingItemURL:)` which does not play the sound or support "Put Back."

### Why This Matters

Most macOS apps that want to "delete" a file use `FileManager.removeItem` which bypasses the Trash. This is wrong for user-facing file operations. The correct approach is `NSWorkspace.recycle`. This library wraps that correctly and handles edge cases (async completion, error reporting).

The implementation is directly applicable to any macOS app (like Curator) that needs to offer a "Move to Trash" action instead of permanent deletion.

## Value Classification

| Item | Classification |
|------|---------------|
| `NSWorkspace.recycle` wrapper | directly reusable code |
| Correct macOS Trash semantics | architecture reference |
| CLI tool for scripting | developer-tooling reference |

## General Usefulness

**Problem it solves**: Correctly moving files to macOS Trash (with Finder-compatible semantics) from Swift code.

**Why the implementation is notable**: The difference between `FileManager.removeItem`, `FileManager.trashItem`, and `NSWorkspace.recycle` is a common macOS development pitfall. This library is the authoritative correct approach.

**Future project uses**:
- Any macOS Swift app that needs to move files to Trash
- Directly applicable to Curator's file management workflow (though implementation is not to be copied to Curator during this task)

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 5/5 | Idiomatic Swift, minimal and correct |
| Originality | 3/5 | Simple wrapper but solves a real pitfall |
| General Reusability | 5/5 | Drop-in SwiftPM dependency for macOS apps |
| Dependency Risk | 5/5 | Zero external dependencies |
| Licensing Clarity | 5/5 | Clear MIT |
| Long-term Usefulness | 5/5 | macOS Trash semantics are stable |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 5 |
| Originality | 3 |
| General Reusability | 5 |
| Educational Value | 4 |
| Design / UX Quality | N/A |
| Architecture Quality | 5 |
| Documentation Quality | 4 |
| Maintenance Health | 5 |
| Licensing Clarity | 5 |
| Long-term Lab Value | 5 |

**Final Priority**: high  
**Recommended Action**: clone (done — `external-sources/native-macos/macos-trash`)
