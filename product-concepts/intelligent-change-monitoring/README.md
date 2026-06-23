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

---

## Related sources

- `sources/change-monitoring-notifications/` (tsembp/WG-Course-Task-Notifier-Bot, MIT) — upstream polling + notification pattern
- `reference-implementations/monitored-source-notifications/` — in-lab reference build

## Research connections

None filed. See `business-research/BUSINESS_IDEAS_INDEX.md` for context.

## Origin

Sourced from tsembp/WG-Course-Task-Notifier-Bot (a university course/task notifier).
Product concept extends the core polling pattern to multi-source, multi-domain monitoring
with deduplication and importance classification.

## Current evidence level

`initial-research` — upstream source studied, reference implementation planned.
No user interviews conducted.

## Open assumptions

- Users across the listed domains (internships, grants, tenders) are underserved by
  existing notification tools
- Deduplication and importance classification are the meaningful differentiators
  over setting up simple RSS/email alerts
- A horizontal multi-domain product is better than a vertical single-domain one

## Competitor landscape

Source: verified from product websites, 2026-06-23. Full analysis in `research/domain-synthesis/data-and-learning.md`.

| Competitor | Domain | Approach | Gap |
|---|---|---|---|
| Visualping | Web page change detection | Screenshot diff | No content deduplication; no importance classification |
| Wachete | Web monitoring | CSS selector monitoring | More technical; no importance classification |
| Datadog / PagerDuty | Infrastructure/API monitoring | Event-based alerting | Not for content/regulatory changes; infrastructure-only |
| Google Alerts | Web content | Keyword-based email alerts | No deduplication; high noise; no importance classification |
| Track Changes (EU Publications Office) | EU regulatory updates | RSS feeds | EU only; no filtering or importance ranking |
| Domain-specific job boards | Internships/jobs | Native alerts (LinkedIn, etc.) | Fragmented; one source per board; no aggregation |

**Competitive observation:** Visualping/Wachete exist for web monitoring but have no AI-powered deduplication or importance classification. Google Alerts has high noise. The specific gap — multi-source monitoring with AI-powered importance classification and deduplication — is not filled by a single product. However, many users solve this with free tools (Google Alerts + RSS) and may not pay.

**Key risk:** Low willingness to pay for this category. The competitive advantage is real but may not be monetizable.

evidence_level: initial-research (competitor landscape verified; no customer interviews conducted)

## Next validation step

1. Pick one domain (internship listings or grant calls) and interview 3–5 users about
   how they currently track new opportunities
2. Identify whether there is an existing tool for that domain (e.g., dedicated job board alerts)
3. Determine if users would pay for this or if it must be free/freemium to get adoption
