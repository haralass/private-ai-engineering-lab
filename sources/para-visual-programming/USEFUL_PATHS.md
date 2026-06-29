# Useful Path Index - Para

## Source Record

- Upstream: [mitmedialab/para](https://github.com/mitmedialab/para)
- Pinned commit: `5aa5a2801b78159ebcd1ff45bd55c2b43d1daa7a`
- Default branch inspected: `master`
- Date inspected: 2026-06-27
- License status: `MIT`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `js/src/app.js` - application bootstrap pattern for a browser-based creative tool.
- `js/src/router.js` - simple route/view coordination in a multi-screen editor.
- `js/src/views/CanvasView.js` - canvas interaction boundary for a visual programming workspace.
- `js/src/views/ParametersView.js` - parameter editing panel pattern for generated/constraint-driven objects.
- `js/src/utils/GeometryGenerator.js` - geometry helper boundary for keeping math utilities separate from UI views.
- `server/app.js` - small server companion pattern for a mostly client-side creative tool.

## Risks And Limitations

- Older browser/JavaScript stack; useful for architecture and interaction patterns rather than direct integration.
- Future reuse should target exact files or clean-room reimplementation, not a full snapshot.
