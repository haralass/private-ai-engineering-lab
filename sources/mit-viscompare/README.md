# mit-viscompare

Source: [mit-han-lab/VisCompare](https://github.com/mit-han-lab/VisCompare)  
Pinned commit: `39b819d73fabf84353f0a60d900a8a56ca4b6f8b`  
Upstream default branch: `main`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

Visualization comparison research/application from MIT HAN Lab.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/mit-viscompare/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/mit-han-lab/VisCompare external-sources/mit-viscompare
git -C external-sources/mit-viscompare fetch --depth 1 origin 39b819d73fabf84353f0a60d900a8a56ca4b6f8b
git -C external-sources/mit-viscompare checkout 39b819d73fabf84353f0a60d900a8a56ca4b6f8b
```

## License Status

- License recorded: `Apache-2.0`
- Evidence: Root `LICENSE.txt` at the pinned commit contains the Apache License 2.0 text.
- Redistribution: permitted by Apache-2.0 with preservation of notices and license text.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
