---
lab_label: person3
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person3 — Open Questions

Evidence labels used in this file:
- `confirmed-from-code` — verified in vendored upstream/ or directly read files
- `confirmed-from-documentation` — verified from README, docs, or comments in the repo
- `strong-inference` — high-confidence conclusion from indirect evidence; not confirmed
- `hypothesis` — plausible but speculative; requires verification
- `still-open` — cannot be answered from available lab content

Only `confirmed-from-code` and `confirmed-from-documentation` are treated as CLOSED.

---

## Confirmed [CLOSED]

**Price history table:** `confirmed-from-code` — No history table exists in the analyzed
scraper schema. The `VariantRow` model maps to a single `raw_products` row using
`ON CONFLICT DO UPDATE`, meaning each scraper run overwrites the latest price per
product per store. A separate history table would need to be added before price-drop
alerting is possible.

---

## Inferences requiring confirmation

**mpn_root cross-retailer usage:** `strong-inference` — `mpn_root` appears designed as a
normalized candidate key for future cross-retailer matching. The scraper code generates
and stores it (stripping locale suffixes from Apple MPN codes). No downstream matching
pipeline, join query, comparison engine, or frontend was found in the analyzed code.
Active usage for cross-retailer matching remains unconfirmed.

**mini-search-engine — academic coursework:** `strong-inference` — The sample data file
(`pages.txt`) contains UCY CS department URLs. This is consistent with a UCY course
assignment but no explicit course reference or README was found in the analyzed files.
Not confirmed from direct documentation.

---

## Still open — requires user input or upstream access

### noshowly deployment status [`still-open`]

- Is noshowly deployed in production with real paying customers, or still at demo/testing stage?
- Is the `pg_cron` extension configured in the Supabase dashboard? The schema references
  it but does not define the schedule.
- Is the GitHub Actions keep-alive workflow actually running on the free tier?
- Was SMS delivery ever implemented, or always planned and never built?
  (`sms_template` column exists but is marked legacy.)
- Are `pro` and `business` plans being sold manually or planned for the future?
  (Referenced in code but not on the public pricing page.)

### timicy-scrapers [`still-open`]

- Are the scrapers run on a schedule, and if so, how? (GitHub Actions, cron, Render, Fly?)
- Is there a price comparison frontend under development, or are the scrapers
  a standalone data collection project?
- Is `mpn_root` actively used for cross-retailer matching? (currently `strong-inference`)

### mini-search-engine [`still-open`]

- Is this a UCY EPL course assignment? (currently `strong-inference`)
- Which course specifically?
