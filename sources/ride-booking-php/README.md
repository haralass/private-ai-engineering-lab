# ride-booking-php

Source: [giannisHadjizorzis/OSRH](https://github.com/giannisHadjizorzis/OSRH)
Pinned commit: `e4fe4835192974b22e42c5b4f45312906d93098c`
License: unknown
Import mode: local-research-only
Status: candidate (license pending; demo credentials documented)

## What this is

Collaborative PHP ride-booking platform with multi-segment ride logic, Leaflet maps,
Nominatim geocoding, and SQL Server backend. Collaborative work.

## What was studied locally

No upstream source snapshot is committed here. The local clone was used to study:
- `book_ride.js` — main frontend ride-booking logic
- `book_ride.php` / `book_ride.css` — booking UI
- `my_frontend_files/` — upgraded UI versions
- `files_for_frontend_upgrade/` — intermediate upgrade versions
- PHP backend files for login, register, dashboard, driver flow, and tracking

## Key reusable utilities (in upstream/book_ride.js)

- `haversine(lat1, lon1, lat2, lon2)` — straight-line distance fallback
- `normalizeText(text)` — Greek accent normalization for Nominatim search
- Two-strategy Nominatim search (Cyprus-bounded → broader) with progressive cache
- Rate-limited geocoding (1 req/sec enforcer)
- OSRM routing integration with Haversine fallback

## Security note

The studied upstream contained hardcoded demo credentials (`password123`,
`localhost/root/root`). They were assessed as non-production demo values and
the upstream source tree is not committed to this repository.

## Repository handling

Keep any local clone under the gitignored `external-sources/` directory. Do not commit upstream code while the license remains unknown.
