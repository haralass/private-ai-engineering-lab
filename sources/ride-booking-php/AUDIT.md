# Audit — ride-booking-php

**Audited:** 2026-06-23
**Secret scan result:** FLAGGED
**Security review status:** flagged

## Findings

### Hardcoded credential (demo only)

`login.php` line 19: `$valid_password = 'password123';`

This is a hardcoded admin demo/test password. It is not a real production credential,
but the upstream/ snapshot must NOT be exposed publicly in any form (no public fork,
mirror, or derived artifact with this code).

The patterns worth studying (Haversine distance, Nominatim search with Greek accent
normalization, OSRM routing with fallback, multi-segment ride logic) are in separate
files and are clean.

## License

No LICENSE file present. License status: unknown / pending review.
Vendoring is provisional (`decision: candidate`).
