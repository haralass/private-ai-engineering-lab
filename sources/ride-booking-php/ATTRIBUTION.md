# Attribution — ride-booking-php

## License status

No LICENSE file was found in this repository at the pinned commit.
License status: **unknown / pending review**.

**Consequence:** This source is committed to the lab as a vendored-snapshot candidate
but must not be redistributed until the license status is resolved. The `decision`
field in SOURCE.yaml is set to `candidate`.

## Source

- **Functional name:** ride-booking-php
- **Source label:** student-collab
- **Upstream repository:** giannisHadjizorzis/OSRH
- **Pinned commit SHA:** `e4fe4835192974b22e42c5b4f45312906d93098c`
- **Retrieved:** 2026-06-23
- **Retrieval method:** read-only clone; no star, fork, watch, or follow

## Security review

Secret scan: FLAGGED (see AUDIT.md).

Hardcoded demo admin credential `password123` found in `login.php`. This is a
demo/test placeholder, not a real production credential. The upstream/ snapshot
must NOT be exposed publicly.

## Lab use restrictions

- No code from this source may be copied until a license is confirmed.
- `execution_allowed: false` — requires SQL Server; hardcoded demo credentials present.
- Do not expose `upstream/` in any public fork, mirror, or deployment.
