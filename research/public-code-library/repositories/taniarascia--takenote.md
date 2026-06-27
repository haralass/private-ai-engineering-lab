# taniarascia/takenote

## Identity
- **Owner**: taniarascia
- **Repository**: takenote
- **URL**: https://github.com/taniarascia/takenote
- **Live URL**: https://takenote.dev (may be archived)
- **Commit SHA**: e0eddbb9a21ae4cf4c4c7c183f29cfd666e08331
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (last push June 2024)
- **Transferred**: no

## Relationship classification
full-open-source-product

Evidence: Complete web application with frontend, backend, authentication, sync, Docker config, and Kubernetes manifest. Not a tutorial scaffold — a real product with 7,000+ stars.

## Licensing
- **Code license**: MIT
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript (client + server), SCSS
- **Frameworks**: React 16, Redux Toolkit, Redux-Saga, Express
- **Key dependencies**: @reduxjs/toolkit, redux-saga, codemirror (v5), react-codemirror2, react-beautiful-dnd, react-markdown, react-split-pane, mousetrap, jszip, helmet, axios
- **Build system**: webpack 5 (separate dev/prod configs)
- **Package manager**: npm
- **Tests**: yes — Jest unit tests (slices, components, utils) + Cypress e2e tests (note, category, settings integration)
- **CI**: yes — Travis CI (.travis.yml)
- **Architecture**: Three-panel notes app (sidebar: categories, note list, note editor). Redux state slice per domain (auth, category, note, settings, sync). Sagas for async side effects (GitHub Gist API sync, auth). Express backend for GitHub OAuth token exchange and optional cloud sync. Offline-first with localStorage, optional GitHub Gist cloud sync.
- **State management**: Redux Toolkit slices + Redux-Saga
- **Rendering model**: React SPA (webpack-bundled), served by Express

## Useful content (exact files)

### Directly reusable code
- `src/client/slices/note.ts` — Note slice: CRUD operations, selection, search, sort, trash, favorites
- `src/client/slices/category.ts` — Category slice with drag-and-drop state
- `src/client/slices/settings.ts` — Settings slice with font size, markdown preview mode, sidebar width
- `src/client/slices/sync.ts` — GitHub Gist sync state machine
- `src/client/sagas/index.ts` — Root saga orchestrating async operations
- `src/client/selectors/index.ts` — Reselect selectors for derived note and category state
- `src/client/utils/notesSortStrategies.ts` — Pluggable sort strategies (date, title, alphabetical)
- `src/client/utils/helpers.ts` — Note utility functions (new note creation, markdown export)
- `src/client/containers/NoteEditor.tsx` — CodeMirror integration container with markdown toggle
- `src/client/containers/KeyboardShortcuts.tsx` — Mousetrap-based keyboard shortcut registration
- `src/server/middleware/checkAuth.ts` — JWT auth middleware for Express
- `src/server/handlers/sync.ts` — GitHub Gist API sync handler

### Adaptable patterns
- **Redux slice + saga separation**: Each domain (note, category, settings, sync, auth) has its own slice file. Sagas are imported separately and combined in root saga. Clean separation of sync and async concerns.
- **CodeMirror v5 integration**: `NoteEditor.tsx` shows the pattern for controlling a CodeMirror instance via React state without losing cursor position.
- **Split-pane resizable layout**: Uses `react-split-pane` with persisted widths in settings slice. Pattern for any two- or three-panel layout.
- **GitHub Gist as cloud storage**: `src/server/handlers/sync.ts` demonstrates reading/writing structured JSON to GitHub Gist as a persistence backend — no database required.
- **Mousetrap keyboard shortcut layer**: `KeyboardShortcuts.tsx` shows centralized shortcut registration using `mousetrap` + `mousetrap-global-bind`. All shortcuts in one container, mounted once.
- **Category drag-and-drop**: Uses `react-beautiful-dnd` for sidebar category reordering, state persisted in Redux.

### Architecture reference
- Offline-first architecture: all data in localStorage, GitHub Gist sync is optional and additive.
- Component vs. Container distinction: `src/client/components/` holds presentational components; `src/client/containers/` holds Redux-connected logic containers.
- Note sort strategy pattern: `notesSortStrategies.ts` defines an array of sort functions keyed by name — easily extended with new strategies.

### Reference-only
- `config/webpack.common.js`, `config/webpack.dev.js`, `config/webpack.prod.js` — Webpack 5 config split pattern
- `config/jest.config.js` — Jest setup for TypeScript + React Testing Library
- `tests/e2e/` — Cypress integration tests as reference for e2e testing patterns
- `kubernetes.yml` — Kubernetes deployment manifest (historical reference)
- `Dockerfile` — Multi-stage Docker build for Node + React

## Evaluation
**Problem solved**: Full-stack note-taking app with markdown, categorization, full-text search, and optional GitHub Gist cloud sync. Works offline with no backend required.
**Original value**: High — one of the most complete TypeScript+Redux open-source apps available as a reference. Real test coverage, real build system, real auth, real sync.
**Future project types**: Any SPA with complex local state + optional sync. Excellent reference for Curator-adjacent features: note/file management UIs, sidebar category systems, split-pane editors, offline-first architectures.
**Do not copy**: The CodeMirror v5 integration is outdated (v6 is current). The `react-split-pane` package is unmaintained. GitHub Gist sync is clever but limited to personal use.
**Risks**: Dependencies are somewhat dated (React 16, CodeMirror v5, webpack 5 config). Travis CI is deprecated. The sync system requires a GitHub OAuth app registration.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 4 |
| Originality | 4 |
| General usefulness | 5 |
| Architecture | 4 |
| Design and UX | 3 |
| Accessibility | 3 |
| Performance | 3 |
| Testing | 4 |
| Documentation | 4 |
| Maintenance health | 3 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: high
**Action**: clone
