# para-visual-programming

Source: [mitmedialab/para](https://github.com/mitmedialab/para)  
Pinned commit: `5aa5a2801b78159ebcd1ff45bd55c2b43d1daa7a`  
Upstream default branch: `master`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

MIT Media Lab visual programming / parametric design environment.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/para-visual-programming/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/mitmedialab/para external-sources/para-visual-programming
git -C external-sources/para-visual-programming fetch --depth 1 origin 5aa5a2801b78159ebcd1ff45bd55c2b43d1daa7a
git -C external-sources/para-visual-programming checkout 5aa5a2801b78159ebcd1ff45bd55c2b43d1daa7a
```

## License Status

- License recorded: `MIT`
- Evidence: Root `LICENSE` and `package.json` at the pinned commit identify MIT licensing.
- Redistribution: permitted by MIT with copyright and permission notice retained.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
