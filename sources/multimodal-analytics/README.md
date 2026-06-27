# multimodal-analytics

Source: [mitdbg/multimodal-analytics](https://github.com/mitdbg/multimodal-analytics)  
Pinned commit: `de8fd18e20bbbcd13786dc20841cb3ea859b5335`  
Upstream default branch: `main`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

MIT DB Group reproducibility/research code for multimodal analytics.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/multimodal-analytics/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/mitdbg/multimodal-analytics external-sources/multimodal-analytics
git -C external-sources/multimodal-analytics fetch --depth 1 origin de8fd18e20bbbcd13786dc20841cb3ea859b5335
git -C external-sources/multimodal-analytics checkout de8fd18e20bbbcd13786dc20841cb3ea859b5335
```

## License Status

- License recorded: `MIT`
- Evidence: Root `LICENSE` at the pinned commit contains MIT License text.
- Redistribution: permitted by MIT with copyright and permission notice retained.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
