---
lab_label: person3
research_date: 2026-06-24
---

# person3 — Ideas Derived

Ideas below are grounded in actual code analysis, not README descriptions.

---

## 1. Appointment SaaS for non-salon verticals

**Problem:** noshowly is designed for salons/barbershops. The core scheduling infrastructure
(staff availability, service catalog, public booking page, Stripe billing, email reminders
with YES/NO confirmation) is vertical-agnostic.

**Source:** `sources/people/person3/github/noshowly/upstream/` (MIT)

**Pattern used:** `findEligibleBarbers()`, `staff_availability`, `barber_services`,
`booking_pages` (per-slug public booking), white-label email templates.

**What to change:** rename "barbers" to "staff", generalize services, remove SMS legacy
columns, add multi-location support, add group booking.

**Target verticals:** physiotherapy clinics, tutoring centers, car workshops, dog grooming,
medical specialists.

**Potential user:** SME owner in any appointment-based business.

**Business model:** per-seat SaaS or revenue share on bookings. $19/month baseline from noshowly.

**Technical difficulty:** Low — architecture already supports it. Primarily UI/labeling work.

**Assessment:** Real opportunity. The Supabase RLS + slug-based public booking is the hard
part — already done. Vertical expansion is a product/marketing decision, not an
engineering challenge.

---

## 2. Cyprus electronics price comparison product

**Problem:** No public price comparison tool exists for Cyprus electronics retailers.
timicy-scrapers collects structured data from 6 stores with MPN normalization — the
infrastructure for a price comparison product.

**Source:** timicy-scrapers (local-research-only, no license)

**Pattern used:** `VariantRow` normalized model, `mpn_root` cross-retailer matching,
`identifier_source` confidence tiers, Supabase `raw_products` upsert.

**What exists:** scrapers + normalized data model + Supabase persistence. Missing: frontend,
price history tracking, alerting, search index.

**What's missing:**
- Price history table (current model only keeps latest price per product per store)
- Search/filter UI
- Price drop alerts
- Affiliate links for monetization

**Potential user:** Cyprus consumers buying electronics.

**Business model:** affiliate commissions (3-8% per referred sale), or retailer-paid
premium listings.

**Technical difficulty:** Medium — data pipeline exists. Frontend and alerting are
the remaining pieces.

**Assessment:** Genuine opportunity specific to Cyprus. The MPN root extraction across
Apple product lines (locale-stripped) shows the data quality needed for reliable matching.
The `identifier_source` confidence tier means matches can be ranked by reliability.

---

## 3. Email confirmation link as first-party data collection

**Problem:** noshowly's YES/NO email confirmation is used for appointment confirmation.
The same single-use UUID token pattern could be used for preference collection, NPS,
re-engagement, or any one-click action from email without requiring login.

**Source:** `sources/people/person3/github/noshowly/upstream/app/api/confirm/[token]/route.ts` (MIT)

**Pattern used:** `reminders.token` (UUID), `/api/confirm/[token]?response=yes|no`
public endpoint, HTML response (not JSON).

**What exists:** The complete server-side pattern for single-use token link handling.

**Reuse:** Any transactional email product needing one-click actions without login.

---

## 4. White-label SaaS infrastructure template

**Problem:** Building a new SaaS requires boilerplate: auth, billing, multi-tenancy,
email, cron. noshowly is a complete, working example of all five.

**Source:** `sources/people/person3/github/noshowly/upstream/` (MIT)

**What this is:** Not a new product — a reusable starting point. With MIT license,
it can be directly adapted. Key reusable pieces:
- Supabase multi-tenant RLS pattern (TECHNICAL_PATTERNS.md, Pattern 1)
- Stripe checkout + webhook handler
- White-label email templates
- Hourly cron with rate limiting and quota enforcement
- Public slug-based booking page

**Assessment:** The highest-value use is as infrastructure for lab product concepts
that need appointment scheduling (e.g., the adaptive SQL learning platform's tutor session
booking, or any product that needs customer scheduling).
