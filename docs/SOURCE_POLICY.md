# Source Governance Policy

This lab studies external repositories, but external code is not automatically reusable and should not be committed merely because it is useful.

## Default Workflow

Use **local-research-only** for external code-level research:

1. Keep the upstream repository in a local gitignored research area.
2. Pin the upstream URL, branch, and commit SHA in `SOURCE.yaml`.
3. Record license evidence from the root license file, package metadata, source SPDX headers, or custom terms.
4. Commit only metadata, attribution, README notes, and lightweight `USEFUL_PATHS.md` pointers.
5. Do not commit upstream source files unless a later PR explicitly approves a minimal vendoring scope.

This default keeps Codex and future research sessions able to use the findings without turning the lab into an archive of third-party repositories.

## Import Modes

| Mode | When to use |
|---|---|
| `local-research-only` | Default for external research when code was inspected locally but no upstream files should be committed |
| `reference-only` | License is unclear, absent, restricted, non-commercial, AGPL/FSL/Codrops/custom, or the repo is useful only as metadata |
| `vendored-snapshot` | Rare: license permits redistribution and a full pinned snapshot is explicitly approved |
| `selected-subsystem` | Rare: license permits redistribution and only a small, useful file set is explicitly approved |
| `clean-room-reimplementation` | Pattern is studied, but implementation is written independently without copying code |
| `submodule` | Rare: explicit approval for a large dependency-style reference |

## License Rules

- If there is no clear license, use `reference-only` or `local-research-only` with `copy_allowed: false`.
- If the license value is `NONE`, `NOASSERTION`, `UNLICENSED`, `RESTRICTED`, `UNKNOWN`, `NOT-FOUND`, Codrops, FSL, or AGPL, do not mark it for unrestricted reuse.
- Restricted or non-commercial licenses are study/reference only unless the specific intended use is verified.
- AGPL repositories are study/reference only for proprietary or non-AGPL work unless the whole downstream use is compatible.
- Package metadata and source-file SPDX headers can support file-level conclusions, but they do not equal a root repository license.

## Vendoring Rules

Vendoring is allowed only when all of these are true:

- The exact files or subsystem are named.
- License evidence permits the intended copying and redistribution.
- Attribution is preserved.
- A secret scan has been run on the vendored scope.
- `FILE_MANIFEST.json`, `AUDIT.md`, and `SOURCE.yaml` are updated.
- The PR explains why metadata-only or local-research-only is insufficient.

Do not vendor a full repository merely for archival purposes. Prefer a focused dossier with useful paths, risks, and reimplementation notes.

## If No Clear License

- Set `copy_allowed: false`.
- Set `vendoring_allowed: false` unless a later review identifies individually licensed files and approves that limited scope.
- Keep the source `reference-only` or `local-research-only`.
- Document the reason in `SOURCE.yaml`, `ATTRIBUTION.md`, and relevant catalog entries.

## Local Research Dossiers

`USEFUL_PATHS.md` files should stay lightweight and specific:

- Prefer exact files or narrow subdirectories over broad root-level directory lists.
- Explain what implementation pattern each path contains.
- Include risks and limitations.
- Do not copy upstream code into the dossier.

## Validation

Run these after source, catalog, or public-code-library changes:

```bash
python scripts/validation/validate_source_manifests.py
python scripts/validation/validate_catalog_consistency.py
python scripts/validation/validate_public_code_library.py
```

CI must run all three validators directly.
