# Useful Path Index - workspace-multiselect

## Source Record

- Upstream: [mit-cml/workspace-multiselect](https://github.com/mit-cml/workspace-multiselect)
- Pinned commit: `2ae843226a40f530e7b658f03d570615ca66b952`
- Default branch inspected: `main`
- Date inspected: 2026-06-27
- License status: `Apache-2.0` evidence from package metadata and source SPDX headers; no root `LICENSE`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `src/multiselect.js` - main Blockly plugin coordination pattern for multi-select state and workspace behavior.
- `src/multiselect_contextmenu.js` - context-menu extension pattern for selection commands.
- `src/multiselect_controls.js` - UI controls boundary for select/unselect affordances.
- `src/multiselect_draggable.js` - drag behavior extension pattern for grouped selected blocks.
- `test-e2e/selection/multiselect/block.spec.ts` - Playwright regression pattern for multi-block selection behavior.
- `playwright.config.ts` - compact e2e test configuration for browser-based workspace interactions.

## Risks And Limitations

- No root `LICENSE` file exists. Full-repository vendoring is not authorized.
- A future PR may approve only individually verified files that carry compatible SPDX headers and preserve attribution.
