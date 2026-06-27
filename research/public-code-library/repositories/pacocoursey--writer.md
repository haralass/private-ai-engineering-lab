# pacocoursey/writer

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | pacocoursey |
| Repository | writer |
| URL | https://github.com/pacocoursey/writer |
| Live URL | https://writer.paco.me |
| Commit SHA | d0b3379b1319f14f1db19b1d90be553680a1f7f5 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | stale (last push October 2021) |
| Last Meaningful Push | 2021-10-28 |

## Legal Status

| Field | Value |
|-------|-------|
| License | NONE |
| Attribution Required | unknown |
| Code Reuse | reference-only |
| Reference-Only | yes — no license file |

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | JavaScript |
| Framework | Vite (no React/Vue — vanilla JS) |
| Major Dependencies | none |
| Build System | Vite |
| Test System | none |
| Repository Structure | `src/` (6 focused modules: buffer, cursor, drawing, measure, position, selection, state, textarea, utils), `style.css`, `index.html` |
| Architecture | Custom canvas-based text editor built entirely from scratch in vanilla JavaScript. No DOM contenteditable. Renders text directly to a `<canvas>` element. |

## Actual Valuable Content

### The Architecture: Canvas Text Editor

This is a from-scratch text rendering and editing engine. Every part that browsers normally handle for text editing is reimplemented:

**`src/buffer.js`** — Text rope/buffer: stores text content, handles insertions and deletions at arbitrary positions.

**`src/cursor.js`** — Cursor position management, selection state, movement operations (arrow keys, home/end, word jump).

**`src/drawing.js`** — All rendering: clears the canvas, measures text, draws characters, draws cursor caret, draws selections.

**`src/measure.js`** — Text measurement using canvas `measureText`. Calculates character widths for cursor positioning.

**`src/position.js`** — Converts between logical (line, column) and visual (x, y) coordinates. Handles line wrapping.

**`src/selection.js`** — Selection model: start/end anchors, drag selection, double-click word selection.

**`src/state.js`** — State machine: composes all modules, dispatches events, coordinates updates.

**`src/textarea.js`** — The key trick: uses a hidden off-screen `<textarea>` to capture keyboard input (the browser handles IME, copy/paste, accessibility natively), then routes events to the canvas renderer.

**`samples/`** — Large text files for performance testing (Alice in Wonderland in various sizes).

## Value Classification

| Item | Classification |
|------|---------------|
| Canvas text rendering engine | architecture reference |
| Hidden textarea + canvas input trick | adaptable implementation pattern |
| Text buffer implementation | adaptable implementation pattern |
| Text measurement via canvas API | adaptable implementation pattern |
| Line/column ↔ pixel coordinate mapping | adaptable implementation pattern |
| No license | reference-only |

## General Usefulness

**Problem it solves**: Building a text editor that bypasses DOM contenteditable's limitations (inconsistent behavior, accessibility issues, styling constraints) by rendering directly to canvas.

**Why the implementation is notable**: The hidden `<textarea>` trick for capturing keyboard input while rendering to canvas is the canonical approach used by professional canvas editors (Monaco Editor, CodeMirror's canvas mode, VS Code's terminal). Paco's implementation is a clean, minimal demonstration of this pattern in a few hundred lines.

**Future project uses**:
- Reference for any custom text editor or terminal emulator using canvas
- The textarea+canvas input pattern is directly applicable to any canvas UI
- Text measurement and coordinate mapping algorithms

**Caveats**: No license. Code is stale (2021). Canvas editors have since been largely superseded by modern CodeMirror 6 / ProseMirror for most use cases. However, the underlying pattern (canvas rendering + hidden textarea) is timeless.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 4/5 | Clean vanilla JS, well-structured modules |
| Originality | 5/5 | Hand-rolled canvas editor is uncommon and instructive |
| Architecture | 4/5 | Clear module separation |
| Implementation Age | 3/5 | 2021 — stale but patterns are timeless |
| Licensing Clarity | 1/5 | No license |
| Testing Quality | 1/5 | No tests |
| Long-term Usefulness | 4/5 | The input+canvas pattern is perennial |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 4 |
| Originality | 5 |
| General Reusability | 3 |
| Educational Value | 5 |
| Design / UX Quality | 3 |
| Architecture Quality | 4 |
| Documentation Quality | 2 |
| Maintenance Health | 1 |
| Licensing Clarity | 1 |
| Long-term Lab Value | 4 |

**Final Priority**: medium  
**Recommended Action**: reference-only (no license; stale; educational value only)
