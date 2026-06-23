# Source Import Policy

## Before importing any source

1. Verify the actual `LICENSE` file in the upstream repository — do not trust README claims
2. Record the exact commit SHA to be imported
3. Check the repository's activity — is it maintained?
4. Identify which files are actually useful vs generated/build artifacts

## Import process

```
git clone --depth 1 --branch <tag-or-sha> <url>
cd <repo>
# Remove internal git history
rm -rf .git
# Remove dependencies and generated outputs
rm -rf node_modules venv .venv __pycache__ dist build .next coverage
# Run secret scan
python scripts/security/secret_scan.py <path>
# Create SOURCE.yaml, ATTRIBUTION.md, FILE_MANIFEST.json
# Commit clean snapshot
```

Or use the automated script:
```bash
python scripts/source-management/import_source.py \
  --url <github-url> \
  --commit <sha> \
  --name <functional-name>
```

## What to keep in `upstream/`

- Source code
- Tests
- Hooks
- Scripts
- Examples
- Configuration files
- Templates
- Documentation

## What to remove before import

```
.git/
node_modules/
venv/ .venv/ env/
__pycache__/ *.pyc
dist/ build/ .next/ out/
coverage/
*.log
.DS_Store
IDE configs
Temporary databases
Generated caches
Downloaded dependencies
```

## Import modes

| Mode | When to use |
|---|---|
| `vendored-snapshot` | Repository is small enough, license permits, code is directly useful |
| `selected-subsystem` | Only a specific directory or module is relevant |
| `clean-room-reimplementation` | License unclear, or we want to improve significantly |
| `reference-only` | Study only — no code copied |
| `submodule` | Repository is very large or has many binary assets |

## If no clear license

- Default to `reference-only`
- Set `copy_allowed: false`
- Do not copy code
- Document the reason in `SOURCE.yaml` notes field

## Student repository policy

Repositories from known colleagues are treated identically to external sources:
- Full audit required
- Source label set to `student-1` or `student-2` in SOURCE.yaml metadata
- Real names and GitHub URLs recorded for attribution
- Functional folder names used (not author names)
