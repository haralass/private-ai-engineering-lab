# Useful Path Index - VisCompare

## Source Record

- Upstream: [mit-han-lab/VisCompare](https://github.com/mit-han-lab/VisCompare)
- Pinned commit: `39b819d73fabf84353f0a60d900a8a56ca4b6f8b`
- Default branch inspected: `main`
- Date inspected: 2026-06-27
- License status: `Apache-2.0`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `main.py` - Gradio entrypoint pattern for wiring a comparison UI around model-output examples.
- `pages/compare.py` - useful reference for side-by-side visual comparison flow and user-facing review state.
- `configs/example.yaml` - compact example of making demo behavior configurable instead of hardcoding every comparison option.
- `captions/example.yaml` - separates display captions from image assets, a useful pattern for repeatable qualitative eval pages.
- `buttons.py` and `dialogs.py` - small UI helper modules that keep the page implementation from becoming a single large script.

## Risks And Limitations

- Upstream includes image/demo media; do not commit a full snapshot without a documented exception.
- Treat as an implementation-pattern reference unless a later PR approves a specific minimal vendoring scope.
