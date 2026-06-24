# Attribution — noshowly (appointment-scheduling-saas)

## License

MIT License

Copyright (c) 2024 [person3]

The full license text is in `upstream/LICENSE`. The copyright notice and license text
must be reproduced in any derivative work or distribution. No modifications have been
made to the upstream LICENSE file or any upstream copyright notices.

## Source

- **Functional name:** appointment-scheduling-saas
- **Source label:** person3
- **Upstream repository:** person3/noshowly (identity anonymized in lab)
- **Pinned commit SHA:** `e5296a3cbb383af1cfe481f5d2b030c81d028d0d`
- **Retrieved:** 2026-06-24
- **Retrieval method:** `git archive <sha> | tar -x` (read-only; no star, fork, watch, or follow)

## Snapshot integrity

- The `upstream/` directory contains 101 files at the pinned commit.
- No files were modified during retrieval.
- Paths removed: `.git/` only.
- Secret scan result: CLEAN — `upstream/package-lock.json` contains integrity hashes
  only; no API keys, tokens, passwords, or other secrets were found. Full scan log in
  `AUDIT.md`.
- No upstream LICENSE file, copyright notice, or author attribution has been modified.

## Lab modifications

The `adapted/` directory is reserved for lab modifications derived from the upstream
snapshot. It is currently empty. All future modifications must:

1. Go into `adapted/`, not `upstream/`
2. Be documented in `adapted/CHANGES.md`
3. Preserve the MIT copyright notice above in any redistributed form

Do not modify files under `upstream/`. If a file needs to be changed for lab use,
copy it to `adapted/` and modify the copy.

## Usage restrictions

- **Execution:** This source requires live Supabase, Stripe, and Resend credentials.
  `execution_allowed: false` in SOURCE.yaml. Do not run locally without a proper
  environment configuration separate from any production or staging environment.
- **Redistribution:** Permitted under MIT, with license and copyright notice preserved.
- **Code reuse:** Permitted under MIT. Any reuse in lab components must cite this source.
