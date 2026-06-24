---
lab_label: person3
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person3 — Open Questions

---

## Answered from code analysis

### Price history — CLOSED

**Q: Is there a price history table beyond `raw_products`?**
→ **No.** The current model is upsert-only: each scraper run overwrites the latest
price per product per store. `VariantRow` maps to a single `raw_products` row with
`ON CONFLICT DO UPDATE`. No history table exists in the analyzed schema. A separate
history table would need to be added before price-drop alerting is possible.
(See `research/people/person3/IDEAS_DERIVED.md` — Cyprus price comparison idea
explicitly lists this as missing infrastructure.)

**Q: Is `mpn_root` used for cross-retailer matching, or collected for future use?**
→ **Used now.** `mpn_root` is the canonical cross-retailer join key in the
`VariantRow` model. It strips locale suffixes from Apple MPN codes (e.g., `MQKQ3`
from `MQKQ3B/A`, `MQKQ3LL/A`). The `identifier_source` confidence tier ranks
reliability of the match. The matching infrastructure is live — what's missing
is a frontend to surface it.

### mini-search-engine context — CLOSED

**Q: Is mini-search-engine an academic assignment or an independent project?**
→ **Academic.** The sample data file (`pages.txt`) uses UCY CS department URLs,
consistent with a UCY course assignment. The same pattern appears in person2's
EPL231-GroupAssignment. Both are University of Cyprus CS course work.

---

## Still open — requires user input or upstream access

### noshowly deployment status

- Is noshowly deployed in production with real paying customers, or still demo/testing?
- Is the `pg_cron` extension configured in the Supabase dashboard? The schema references
  it but does not define the schedule.
- Is the GitHub Actions keep-alive workflow actually running on the free tier?
- Was SMS delivery ever implemented, or always planned and never built?
  (`sms_template` column marked legacy.)
- Are `pro` and `business` plans being sold manually or planned for the future?
  (Referenced in code but not on the public pricing page.)

### timicy-scrapers

- Are the scrapers run on a schedule, and if so, how? (GitHub Actions, cron, Render, Fly?)
- Is there a price comparison frontend under development, or are the scrapers
  a standalone data collection project?
