# sources/

Audited source records for upstream repositories. A source may be a vendored
snapshot, a local-research-only record, or a reference-only metadata record.
Do not assume every source directory contains committed upstream code.

## Vendored source structure

```
sources/<functional-name>/
├── upstream/       Clean snapshot at a pinned commit — never modify this
├── adapted/        Our changes, improvements, or integrations
├── README.md       What this source is, what we kept, what we removed
├── SOURCE.yaml     Full metadata: URL, SHA, license, review status
├── LICENSE         Copy of the upstream license file
├── ATTRIBUTION.md  Credit to original authors
├── AUDIT.md        Security audit, prompt-injection review, notes
└── FILE_MANIFEST.json  List of all files with SHA-256 hashes
```

## Local-research-only source structure

```
sources/<functional-name>/
├── README.md       What this source is and how to clone it locally
├── SOURCE.yaml     Full metadata: URL, SHA, license evidence, review status
├── ATTRIBUTION.md  Credit and provenance
└── USEFUL_PATHS.md Research index of selected useful files and patterns
```

Use `local-research-only` when source code was studied from a local clone but
should not be committed to this repository. Local clones belong under the
gitignored `external-sources/` directory:

```bash
git clone --depth 1 <github-url> external-sources/<functional-name>
git -C external-sources/<functional-name> fetch --depth 1 origin <commit-sha>
git -C external-sources/<functional-name> checkout <commit-sha>
```

## Rules

- Never modify anything inside `upstream/`
- All changes go into `adapted/`
- A source with `decision: candidate` or `decision: pending` is not approved for production reuse
- A source with `license_review_status: pending` has not been cleared for copying
- Do not commit full third-party repositories merely for archival or research purposes
- Missing or unclear license means no source copying and no vendored snapshot
- See `docs/SOURCE_POLICY.md` and `docs/LICENSING_POLICY.md` for full policy

## Adding a new source

```bash
python scripts/source-management/import_source.py \
  --url <github-url> \
  --commit <sha> \
  --name <functional-name>
```
