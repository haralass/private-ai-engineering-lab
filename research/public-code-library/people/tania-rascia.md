# Tania Rascia

**GitHub**: taniarascia
**Website**: https://www.taniarascia.com
**Analysis date**: 2026-06-27

## Profile overview
Tania Rascia is a self-taught software engineer turned educator and open-source contributor. She is best known for her deeply approachable technical writing and for building real, complete applications as teaching artifacts. Her public code is notable for clarity and intentionality — even tutorial-adjacent projects have production-quality architecture. She created the `new-moon` dark theme used across editors and the `primitive` CSS framework. She also writes MVC frameworks, game emulators, and full-stack note-taking apps.

## Repository inventory
| Repository | Type | Stars | Status | Decision |
|-----------|------|-------|--------|----------|
| takenote | Original | 7,124 | Active | Clone |
| taniarascia.com | Original | 2,127 | Active | Reference |
| webpack-boilerplate | Original | 2,365 | Active | Reference |
| new-moon | Original | 1,228 | Active | Reference |
| wp-functions | Original | 1,235 | Active | Reject |
| primitive | Original | 940 | Active | Reference |
| mvc | Original | 599 | Active | Reference |
| sandbox | Original | 560 | Reject (mixed content) |
| react-tutorial | Original | 751 | Active | Reject (tutorial) |
| chip8 | Original | 471 | Active | Reference |
| laconia | Original | 366 | Active | Reject |
| react-hooks | Original | 376 | Active | Reject (tutorial) |
| pdo | Original | 230 | Active | Reject |
| snek | Original | 275 | MIT | Reference |
| chat | Original | 213 | Unmaintained | Reject |
| accordion | Original | 203 | Active | Reject |
| startwordpress | Original | 210 | Active | Reject |
| sokoban | Original | 91 | Active | Reject |
| react-advanced-form | Original | 130 | Unmaintained | Reject |
| vue-tutorial | Original | 150 | Active | Reject (tutorial) |
| node-api-postgres | Original | 103 | Active | Reject (tutorial) |
| tictactoe | Original | 44 | MIT | Reject |
| new-moon-vscode | Original | 94 | Active | Reject |
| startjekyll | Original | 120 | Active | Reject |
| oblate (archived) | Original | 122 | Archived | Reject |
| untheme (archived) | Original | 88 | Archived | Reject |

## Original vs forks vs transferred
All listed repos are original. No significant forks found in her public profile. The `new-moon` theme exists in several format-specific repos (VSCode, Atom, Sublime, Chrome devtools, Brackets, Vim) — all original ports.

## Strongest repositories
- **takenote**: Full-stack React/TypeScript/Redux Toolkit note-taking app with Express backend, Redux-Saga for side effects, CodeMirror editor, GitHub Gist sync, drag-and-drop categories, unit and e2e tests (Cypress), Docker support. The most architecturally complete project in her portfolio. Cloned.
- **webpack-boilerplate**: Actively maintained webpack 5 boilerplate (2,365 stars). Good reference for modern build config without framework overhead.
- **taniarascia.com**: Gatsby-based personal site/blog, actively maintained (last push May 2026). Clean content-driven architecture.
- **primitive**: CSS framework / design toolkit in SCSS. Actively maintained. Reference for minimal, semantic CSS system design.
- **new-moon**: Her signature dark syntax theme. Used in VS Code, Atom, Sublime Text, Chrome DevTools. Pure CSS/Less — reference only.
- **mvc**: Vanilla JS MVC implementation in plain JavaScript (~600 stars). Clean pattern implementation with no dependencies.
- **chip8**: CHIP-8 emulator written in JavaScript — noteworthy for cross-platform target (web/CLI/native), interesting for understanding emulator architecture.
- **snek**: Terminal-based Snake game in JavaScript — minimal, clever, MIT.

## Key findings
Tania's most valuable contribution is demonstrating complete, production-quality architectures in public. `takenote` in particular showcases: Redux Toolkit slices with saga middleware, CodeMirror integration, GitHub Gist OAuth sync, split-pane layout, and markdown preview — all in TypeScript with real test coverage. Her work is pedagogically clean but not toy-scale. The `primitive` CSS framework is a useful alternative to utility-first CSS for projects that want semantic class names and a lightweight reset.

## Rejected repositories
- `wp-functions` — WordPress-specific, out of scope
- `sandbox` — Mixed content collection, no single useful artifact
- `react-tutorial`, `vue-tutorial`, `react-hooks`, `node-api-postgres` — Tutorial code, not applications
- `pdo` — PHP database tutorial
- `laconia` — PHP MVC framework, language out of scope
- `chat` — TypeScript/Socket.io chat app, unmaintained since 2021
- `accordion`, `startwordpress`, `startjekyll`, `startgrunt` — Learning scaffolding
- `sokoban`, `tictactoe` — Simple games, limited architectural value
- `react-advanced-form` — Schema-form experiment, unmaintained
- `new-moon-vscode`, `new-moon-atom-syntax`, `new-moon-sublime`, `new-moon-brackets` — Theme format ports, no code value
- `oblate`, `untheme` — Archived WordPress themes
