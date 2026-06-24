---
lab_label: person3
research_date: 2026-06-24
---

# person3 — Open Questions

These questions cannot be answered from the available public code alone.

---

## About noshowly

1. Is noshowly deployed in production with real paying customers, or is it still at the
   demo/testing stage?

2. Is the `pg_cron` extension configured in the Supabase dashboard? The schema file
   references it but does not define the schedule.

3. Is the GitHub Actions keep-alive workflow (`every 5 days → ping Supabase REST API`)
   actually running on the free tier, or has the project been upgraded?

4. The `sms_template` column in `salons` is marked as legacy. Was SMS delivery ever
   implemented, or was it always planned and never built?

5. `pro` and `business` plans are referenced in the code but not available on the
   public pricing page. Are they being sold manually or planned for the future?

---

## About timicy-scrapers

6. Is there a price history table in Supabase beyond `raw_products`? The current model
   only keeps the latest price per product (upsert with conflict). Is historical pricing
   being tracked elsewhere?

7. Are the scrapers run on a schedule, and if so, how (GitHub Actions, cron, Render, Fly)?

8. Is there a price comparison frontend under development, or are the scrapers a standalone
   data collection project?

9. Is the `mpn_root` field used downstream for cross-retailer price matching, or is it
   collected for potential future use?

---

## About mini-search-engine

10. Is this an academic assignment (EPL course work) or an independent project?
    The sample data (`pages.txt`) appears to use UCY CS department URLs.
