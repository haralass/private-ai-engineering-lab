# sources/

Audited, pinned snapshots of upstream repositories and our adapted versions.

## Structure of each source

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

## Rules

- Never modify anything inside `upstream/`
- All changes go into `adapted/`
- A source with `decision: candidate` or `decision: pending` is not approved for production reuse
- A source with `license_review_status: pending` has not been cleared for copying
- See `docs/SOURCE_POLICY.md` and `docs/LICENSING_POLICY.md` for full policy

## Adding a new source

```bash
python scripts/source-management/import_source.py \
  --url <github-url> \
  --commit <sha> \
  --name <functional-name>
```
