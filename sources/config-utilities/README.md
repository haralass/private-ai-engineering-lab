# config-utilities

Source: [MIT-SPARK/config_utilities](https://github.com/MIT-SPARK/config_utilities)  
Pinned commit: `629688a1f6c24ff38130aebd528838a569179dac`  
Upstream default branch: `main`  
Date inspected: 2026-06-27  
Import mode: `local-research-only`

## Purpose

Configuration utilities and ROS integration from MIT-SPARK.

## Repository Handling

No upstream source snapshot is committed here. Keep any local clone outside Git history under `external-sources/config-utilities/` or another gitignored local directory.

```bash
git clone --depth 1 https://github.com/MIT-SPARK/config_utilities external-sources/config-utilities
git -C external-sources/config-utilities fetch --depth 1 origin 629688a1f6c24ff38130aebd528838a569179dac
git -C external-sources/config-utilities checkout 629688a1f6c24ff38130aebd528838a569179dac
```

## License Status

- License recorded: `BSD-3-Clause`
- Evidence: Root `LICENSE` at the pinned commit contains BSD 3-Clause License text.
- Redistribution: permitted by BSD-3-Clause with copyright, conditions, and disclaimer retained.
- Lab decision: do not vendor this full repository; preserve only research metadata and useful paths in Git.

See `USEFUL_PATHS.md` for the research index.
