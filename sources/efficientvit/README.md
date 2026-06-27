# efficientvit

Source: [mit-han-lab/efficientvit](https://github.com/mit-han-lab/efficientvit)  
Pinned commit: `de7d7733cc0329f391b33f1f459271562ec27bd5`  
Upstream default branch: `master`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

Efficient vision transformer research implementation from MIT HAN Lab.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/efficientvit/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/mit-han-lab/efficientvit external-sources/efficientvit
git -C external-sources/efficientvit fetch --depth 1 origin de7d7733cc0329f391b33f1f459271562ec27bd5
git -C external-sources/efficientvit checkout de7d7733cc0329f391b33f1f459271562ec27bd5
```

## License Status

- License recorded: `Apache-2.0`
- Evidence: Root `LICENSE` at the pinned commit contains Apache License 2.0 text.
- Redistribution: permitted by Apache-2.0 with preservation of notices and license text.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
