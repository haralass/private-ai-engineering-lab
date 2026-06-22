# Licensing Policy

## Approved licenses for vendored snapshots

The following licenses permit copying and adaptation with attribution:

| License | Vendored snapshot | Adaptation | Commercial use |
|---|---|---|---|
| MIT | ✅ | ✅ | ✅ |
| Apache 2.0 | ✅ | ✅ | ✅ |
| BSD 2-Clause | ✅ | ✅ | ✅ |
| BSD 3-Clause | ✅ | ✅ | ✅ |
| ISC | ✅ | ✅ | ✅ |
| CC BY 4.0 | ✅ (docs) | ✅ | ✅ |
| CC BY-SA 4.0 | ✅ (docs) | ✅ share-alike | ✅ |
| MPL 2.0 | file-level only | file-level | ✅ |
| GPL v2/v3 | ⚠️ study only | ⚠️ copyleft | ⚠️ |
| AGPL | ⚠️ study only | ❌ | ⚠️ |
| Proprietary / no license | ❌ | ❌ | ❌ |
| Unknown | ❌ (reference-only) | ❌ | ❌ |

## Requirements for all vendored sources

- `LICENSE` file must be present in `sources/<name>/`
- `ATTRIBUTION.md` must credit original authors
- `SOURCE.yaml` must have `license_review_status: approved` before the source is used in production
- Copyright notices in source files must be preserved

## Clean-room reimplementations

A clean-room reimplementation is written from scratch after studying the original. It does not copy code. It is documented as `import_mode: clean-room-reimplementation` in `SOURCE.yaml`. No license constraint from the original applies.

## Model weights

Model weights are never committed. A manifest is created with:
- Model name and version
- Download location (official)
- License
- Parameter count and context
- Hardware requirements
- Serving framework compatibility
