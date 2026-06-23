# ride-booking-php

Source: [giannisHadjizorzis/OSRH](https://github.com/giannisHadjizorzis/OSRH)
Pinned commit: `e4fe4835192974b22e42c5b4f45312906d93098c`
License: unknown
Import mode: vendored-snapshot
Status: candidate (pending review)

## What this is

Collaborative PHP ride-booking platform with multi-segment ride logic, Leaflet maps,
Nominatim geocoding, and SQL Server backend. Collaborative work.

## What was imported

Full repository including:
- `book_ride.js` — main frontend logic (793 lines)
- `book_ride.php` / `book_ride.css` — booking UI
- `my_frontend_files/` — upgraded UI versions (5-step wizard, analytics dashboard)
- `files_for_frontend_upgrade/` — intermediate upgrade versions
- All PHP backend files (login, register, dashboard, driver flow, tracking)

## Key reusable utilities (in upstream/book_ride.js)

- `haversine(lat1, lon1, lat2, lon2)` — straight-line distance fallback
- `normalizeText(text)` — Greek accent normalization for Nominatim search
- Two-strategy Nominatim search (Cyprus-bounded → broader) with progressive cache
- Rate-limited geocoding (1 req/sec enforcer)
- OSRM routing integration with Haversine fallback

## Security note

`upstream/dashboard.php` and several other files contain hardcoded SQL Server credentials
from the original repo. Do NOT expose `upstream/` publicly. Keep this source private.

## upstream/

Clean snapshot at the pinned commit. Do not modify.

## adapted/

Extracted utilities (haversine, Nominatim search, Greek NLP helpers) go here.
