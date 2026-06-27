# cognimates-gui

Source: [mitmedialab/cognimates-gui](https://github.com/mitmedialab/cognimates-gui)  
Pinned commit: `7025907ad83168089e307b321a4d0f81bb740dcf`  
Upstream default branch: `develop`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

Scratch-derived educational programming GUI from MIT Media Lab.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/cognimates-gui/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/mitmedialab/cognimates-gui external-sources/cognimates-gui
git -C external-sources/cognimates-gui fetch --depth 1 origin 7025907ad83168089e307b321a4d0f81bb740dcf
git -C external-sources/cognimates-gui checkout 7025907ad83168089e307b321a4d0f81bb740dcf
```

## License Status

- License recorded: `BSD-3-Clause`
- Evidence: Root `LICENSE` and `package.json` at the pinned commit identify BSD-style/BSD-3-Clause licensing.
- Redistribution: permitted by BSD-3-Clause with copyright, conditions, and disclaimer retained.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
