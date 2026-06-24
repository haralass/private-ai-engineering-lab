# Attribution — ride-booking-php

## License status

No LICENSE file was found in this repository at the pinned commit.
License status: **unknown / pending review**.

**Import mode:** `local-research-only` — code was studied but not committed to this lab.
No code from this source may be copied into any component, reference-implementation,
or redistributed artifact until a license is confirmed.

## Source

- **Functional name:** ride-booking-php
- **Source label:** student-collab
- **Upstream repository:** giannisHadjizorzis/OSRH
- **Pinned commit SHA:** `e4fe4835192974b22e42c5b4f45312906d93098c`
- **Retrieved:** 2026-06-23
- **Retrieval method:** read-only clone; no star, fork, watch, or follow
- **upstream/ status:** Removed 2026-06-24 (never committed to main branch)

## Security review

Secret scan: FLAGGED (see AUDIT.md for full assessment).

Two hardcoded demo credentials were found in the upstream snapshot:
1. `$valid_password = 'password123'` in `login.php` — a demo admin UI credential
   shown visibly in the login page HTML (not a real production credential).
2. `new PDO("mysql:host=localhost;dbname=test", "root", "root")` in
   `my_frontend_files/upload_documents.php` — a local development MySQL default
   (localhost, dbname `test`, password `root`).

**Assessment:** Both are demo/development-only credentials. No rotation or revocation
is required. The upstream snapshot was removed before this branch was merged to main,
so main's git history does not contain these credentials.

## Patterns studied

PHP collaborative ride-booking platform (OSRH):
- Haversine distance calculation for route matching
- Nominatim smart address search with Greek accent normalization
- OSRM routing API integration with fallback handling
- Multi-segment ride logic (book → confirm → execute each segment)
- CSRF token pattern in PHP session management

## Lab use restrictions

- No code from this source may be copied until a license is confirmed.
- Study of patterns is permitted; clean-room reimplementation is allowed.
- `copy_allowed: false` in SOURCE.yaml and license-matrix.yaml.
