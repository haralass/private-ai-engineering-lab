---
lab_label: person3
research_date: 2026-06-24
sources_with_full_code: [appointment-scheduling-saas]
sources_local_research_only: [cyprus-price-scrapers, static-business-website, java-search-engine]
sources_reference_only: [latin-square-solver]
---

# person3 — Technical Patterns

All patterns below are derived from actual code analysis except where marked (README-inferred).

---

## Pattern 1 — Supabase multi-tenant SaaS with RLS

**Source:** `sources/people/person3/github/noshowly/upstream/` (vendored, MIT)

The RLS pattern used:
1. All business data tables have `salon_id` as the tenant discriminator
2. `salons` has `user_id → auth.users`
3. RLS policies on all tables check `auth.uid() IN (SELECT user_id FROM salons WHERE id = salon_id)`
   — a two-hop join that scopes every read/write to the authenticated user's salon
4. Service-role key bypasses RLS for server-side operations (cron, webhooks, registration)
5. Middleware re-validates JWT on every request via `getUser()` (not session cache)

**Reuse:** Drop-in pattern for any Supabase-backed multi-tenant SaaS. The two-hop RLS
subselect is the standard correct approach when tables reference a junction `salons` table.

---

## Pattern 2 — White-label email reminder system

**Source:** `sources/people/person3/github/noshowly/upstream/lib/reminder-templates.ts` (MIT)

`getEmailHTML()` accepts per-salon template fields (greeting, body, closing, footer, subject)
and produces inline-CSS HTML email. Key design:
- `escapeHtml()` applied to all user-supplied variables (no XSS via email)
- Template variables: `{client_name}`, `{business_name}`, `{service}`, `{time}`, `{date}`
- YES/NO confirmation buttons link to `/api/confirm/[token]?response=yes|no`
- Single-use UUID token stored in `reminders.token`
- Platform name never appears in output (white-label)

`sendEmail()` in `lib/resend.ts` never throws — returns `{success, error}` envelope.
Safe to call in fire-and-forget context.

**Reuse:** The `escapeHtml()` + template variable pattern is reusable for any
transactional email product. The single-use token pattern applies to any
one-click action via email (unsubscribe, confirm, cancel).

---

## Pattern 3 — Duration-aware double-booking detection

**Source:** `sources/people/person3/github/noshowly/upstream/lib/appointment-helpers.ts` (MIT)

`appointmentsOverlap(a, b)`: two appointments overlap if neither ends before the other starts.
Each appointment's end time is `datetime + duration_minutes`. This is the standard interval
overlap formula: `a.start < b.end && b.start < a.end`.

`findEligibleBarbers(salon, datetime, serviceId, durationMinutes, excludeAppointmentId)`:
1. Filter candidate barbers: barber.active AND barber has barber_services entry for serviceId
2. Check staff_availability for the day of week and time slot
3. Check existing appointments for overlap (using appointmentsOverlap)
4. Returns eligible barbers with their overridden service duration

**Reuse:** This function is the core scheduling engine for any appointment SaaS.
Extracted cleanly — takes salon context + appointment parameters + DB state, returns
available staff.

---

## Pattern 4 — Hourly cron reminder dispatch with rate limiting

**Source:** `sources/people/person3/github/noshowly/upstream/app/api/cron/send-reminders/route.ts` (MIT)

Guards before sending:
1. Appointment in 23–25h window (not just "tomorrow")
2. Status = scheduled (not cancelled/confirmed)
3. No existing sent reminder (dedup by appointment_id + status='sent')
4. Plan allows email reminders
5. Monthly quota not exceeded (email_reminders_used_this_month < limit)
6. Per-salon hourly rate limit (20/hour)
7. Client has email address

`crypto.randomUUID()` for token generation (built-in, no uuid library needed).

**Reuse:** The guard chain is a complete reminder system checklist. Any scheduled email
product can adopt this guard order directly.

---

## Pattern 5 — Adaptive httpx scraping with 429 throttling

**Source:** timicy-scrapers (local-research-only, no license — clean-room reimplementation only)

`AdaptiveFetcher` class:
- Semaphore bounds concurrent requests (`INITIAL_CONCURRENCY`, reduced on 429)
- On 429: `self.semaphore = asyncio.Semaphore(max(1, current // 2))`
- Exponential backoff: `await asyncio.sleep(2 ** attempt)` before retry
- `MAX_RETRIES=3` with specific exception list (Timeout, Connect, RemoteProtocol, Read, Write, Close)
- Failed IDs persisted to disk for offline retry script

Alongside a shared normalized Pydantic model (`VariantRow`) across all scrapers:
single-output format despite 6 different HTML/API input formats.

**Reuse:** `AdaptiveFetcher` pattern (clean-room implementation) is directly applicable
to any async scraping or rate-limited API polling system.

---

## Pattern 6 — Java data structures for search engines (academic, clean-room only)

**Source:** mini-search-engine (local-research-only, no license)

Pattern: FNV-1a hash → Robin Hood open-addressing → AVL tree per keyword → min-heap
for top-K. The combination forms a complete inverted-index search engine.

**Reuse:** Clean-room reimplementation of the Robin Hood + AVL combination is applicable
for any in-memory keyword index. The FNV-1a hash implementation is particularly useful
for its low collision rate and cache friendliness.

---

## Reuse summary

| Pattern | Usability | License | Condition |
|---|---|---|---|
| Supabase multi-tenant RLS | Direct use | MIT | vendored |
| White-label email templates | Direct copy | MIT | vendored |
| Duration-aware booking detection | Direct copy | MIT | vendored |
| Cron reminder guard chain | Direct copy | MIT | vendored |
| AdaptiveFetcher scraping | Clean-room reimplementation | — | no license |
| Robin Hood + AVL search engine | Clean-room reimplementation | — | no license |
