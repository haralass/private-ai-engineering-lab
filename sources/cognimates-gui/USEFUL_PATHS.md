# Useful Path Index - Cognimates GUI

## Source Record

- Upstream: [mitmedialab/cognimates-gui](https://github.com/mitmedialab/cognimates-gui)
- Pinned commit: `7025907ad83168089e307b321a4d0f81bb740dcf`
- Default branch inspected: `develop`
- Date inspected: 2026-06-27
- License status: `BSD-3-Clause`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `src/containers/gui.jsx` - top-level GUI composition pattern for a large creative tool shell.
- `src/containers/blocks.jsx` - container boundary around a block-programming workspace; useful for separating VM/block state from presentation.
- `src/containers/target-pane.jsx` - pattern for coordinating sprites/stage-like side panels in a dense editor UI.
- `src/reducers/gui.js` - Redux slice organization for global GUI state in a multi-panel application.
- `src/lib/make-toolbox-xml.js` - generated toolbox/config pattern for block categories.
- `src/css/units.css` and `src/css/colors.css` - token-style CSS constants for a mature editor surface.

## Risks And Limitations

- Historical React/Redux stack; use as architecture reference, not as a modern dependency choice.
- Full-repository vendoring is not needed for current lab work.
