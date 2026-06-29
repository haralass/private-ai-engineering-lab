# Useful Path Index - EfficientViT

## Source Record

- Upstream: [mit-han-lab/efficientvit](https://github.com/mit-han-lab/efficientvit)
- Pinned commit: `de7d7733cc0329f391b33f1f459271562ec27bd5`
- Default branch inspected: `master`
- Date inspected: 2026-06-27
- License status: `Apache-2.0`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files, model weights, or generated assets are copied into this repository.

- `efficientvit/models/efficientvit/backbone.py` - model-family organization pattern around reusable backbone definitions.
- `efficientvit/apps/trainer/base.py` - reusable trainer abstraction for research-code workflows.
- `efficientvit/apps/utils/export.py` - deployment/export helper boundary for model artifacts.
- `applications/efficientvit_sam/demo_efficientvit_sam_model.py` - small application entrypoint pattern for demoing a model variant.
- `applications/efficientvit_sam/configs/default.yaml` - configuration-driven application setup.
- `applications/efficientvit_gazesam/gazesam_demo.py` - multi-stage demo pipeline pattern that keeps processing steps explicit.

## Risks And Limitations

- Model weights and large generated artifacts must never be committed.
- Treat as research-code architecture reference unless a later PR approves a narrow, licensed file set.
