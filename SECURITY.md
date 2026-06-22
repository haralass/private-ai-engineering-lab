# Security Policy

## What this repository stores

This repository contains:
- Audited source code snapshots from public repositories
- Adapted components derived from those snapshots
- Experiment code, scripts, and prototypes
- Product concept documentation

## What this repository never stores

- Real API keys, tokens, passwords, or certificates
- Private keys or secrets of any kind
- Production `.env` files
- Actual user data
- Model weights or large binaries

## Reporting a security issue

If you discover a secret accidentally committed or a security vulnerability in any component, open a private issue or contact the repository owner directly. Do not open a public issue.

## Secret scanning

All source imports run through `scripts/security/secret_scan.py` before commit. CI also runs secret scanning on every push. A detected probable secret will cause the CI check to fail and block merge.

## Automatic action restrictions

Components in this repository are designed to be **report-only** in their first version. No component may automatically:
- Edit, commit, push, or merge without human confirmation
- Deploy to any environment
- Publish packages
- Modify system configuration

## License compliance

Every imported source has a `SOURCE.yaml` with `license_review_status`. Sources with `license_review_status: pending` are not approved for production reuse. Only sources with `license_review_status: approved` and `decision: approved` may be incorporated into downstream work.
