# workspace-multiselect

Source: [mit-cml/workspace-multiselect](https://github.com/mit-cml/workspace-multiselect)  
Pinned commit: `2ae843226a40f530e7b658f03d570615ca66b952`  
Upstream default branch: `main`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

MIT App Inventor Blockly workspace multiselect plugin.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/workspace-multiselect/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/mit-cml/workspace-multiselect external-sources/workspace-multiselect
git -C external-sources/workspace-multiselect fetch --depth 1 origin 2ae843226a40f530e7b658f03d570615ca66b952
git -C external-sources/workspace-multiselect checkout 2ae843226a40f530e7b658f03d570615ca66b952
```

## License Status

- License recorded: `Apache-2.0`
- Evidence: No root LICENSE file was found at the pinned commit, but `package.json` declares `Apache-2.0` and source files include `SPDX-License-Identifier: Apache-2.0` headers.
- Redistribution: permitted for the package/source files carrying Apache-2.0 metadata; this lab still keeps it as local-research-only.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
