# Source Governance Policy

This lab studies external repositories for private engineering work. Codex may use the research material broadly for personal lab prototypes, experiments, and implementation guidance. The governance labels exist so the lab always knows what is safe for direct reuse, what is private-only, and what needs review or rewriting before any public/commercial release.

## Default Workflow

Use **local-research-only** for external code-level research:

1. Keep the upstream repository in a local gitignored research area.
2. Pin the upstream URL, branch, and commit SHA in `SOURCE.yaml`.
3. Record license evidence from the root license file, package metadata, source SPDX headers, or custom terms.
4. Commit only metadata, attribution, README notes, and lightweight `USEFUL_PATHS.md` pointers.
5. Do not commit upstream source files unless a later PR explicitly approves a minimal vendoring scope.

This default keeps Codex and future research sessions able to use the findings without turning the lab into an archive of third-party repositories.

## Private Lab Use

For this private lab, `reference-only` and `local-research-only` sources are allowed as working material for Codex:

- Codex may study them, compare approaches, borrow structure conceptually, and produce private prototypes.
- Codex may use them to guide a clean implementation in this lab.
- If code is copied or closely followed for a private experiment, keep the provenance visible in notes or commit context.
- Before anything becomes public, commercial, distributed, or client-facing, review the source labels and rewrite or replace anything that is not clearly reusable.

The labels are therefore release-safety labels, not creativity blockers.

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

- If there is no clear license, use `reference-only` or `local-research-only` with `copy_allowed: false` for release/distribution purposes.
- If the license value is `NONE`, `NOASSERTION`, `UNLICENSED`, `RESTRICTED`, `UNKNOWN`, `NOT-FOUND`, Codrops, FSL, or AGPL, do not mark it for unrestricted reuse.
- Restricted or non-commercial licenses are private-lab/reference material unless the specific intended public/commercial use is verified.
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

- Set `copy_allowed: false` for public release or distribution.
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
