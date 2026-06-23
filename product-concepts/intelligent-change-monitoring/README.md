# Intelligent Change Monitoring

Status: `research`
Inspired by: `sources/change-monitoring-notifications/` (tsembp/WG-Course-Task-Notifier-Bot)

## Summary

A monitoring platform that watches configurable sources for meaningful changes, deduplicates, classifies importance, and notifies — without spamming.

## Target domains

- Internship and job listings (university portals, company sites)
- Research grant calls
- Competitive tender publications
- University announcements (grades, deadlines, schedule changes)
- Software release notes (security-relevant releases only)
- Regulatory and legal updates (GDPR guidance, API policy changes)
- Product pricing changes

## Core pipeline

```
monitor → detect change → deduplicate → classify importance → batch → notify
```

## Key differentiators

- **Deduplication**: changes re-worded but identical in content are not re-sent
- **Importance classification**: a price change of 2% is not the same as a service being discontinued
- **Batching**: configurable digest window (immediate | hourly | daily)
- **Source verification**: can this source be scraped reliably? Does it have an API?

## Inspired by

The tsembp/WG-Course-Task-Notifier-Bot showed the core polling + notification pattern. Our version adds deduplication, importance classification, and multi-source support.

See `reference-implementations/monitored-source-notifications/` for the reference build.
