---
lab_label: person3
research_date: 2026-06-24
---

# person3 — Repositories Reviewed

---

## 1. appointment-scheduling-saas (noshowly)

**Import mode:** vendored-snapshot (MIT)
**Source path:** `sources/people/person3/github/noshowly/upstream/`
**Pinned commit:** e5296a3cbb383af1cfe481f5d2b030c81d028d0d
**Stack:** Next.js 16.2.1, TypeScript 5, React 19.2.4, Supabase (PostgreSQL + Auth + RLS),
Resend 6.9.4, Stripe 21.0.1, Framer Motion 12.38.0, Sentry 10.46.0, shadcn/ui, date-fns,
tailwindcss 4, Vercel (deploy)

### Architecture

**App Router structure:**
- Pages: `/`, `/login`, `/register`, `/pricing`, `/privacy`, `/terms`,
  `/auth/reset-password`, `/dashboard/*`, `/book/[slug]` (public)
- 26 API routes under `app/api/`

**Database (Supabase/PostgreSQL) — 10 tables:**
- `users` — id (→ auth.users), email, stripe_customer_id, plan (trial/basic/pro/business/
  starter/professional/cancelled), trial_ends_at, email_reminders_used_this_month,
  reminders_reset_at
- `salons` — id, user_id (→ users), name, phone, timezone, opening_time, closing_time,
  email_footer/subject/greeting/body/closing (custom email templates), currency, sms_template
  (legacy, unused), email_confirmation_enabled, sms_confirmation_enabled
- `barbers` — id, salon_id, name, photo_url, bio, active
- `clients` — id, salon_id, name, phone, email, notes
- `appointments` — id, salon_id, client_id, barber_id, datetime (TIMESTAMPTZ), service_type,
  duration_minutes (default 30), notes, status (scheduled/confirmed/cancelled)
- `reminders` — id, appointment_id, type (email), send_at, sent_at, status (pending/sent/
  failed/confirmed/cancelled), token (UUID, single-use for YES/NO confirmation)
- `services` — id, salon_id, name, duration_minutes, price, active
- `barber_services` — salon_id, barber_id, service_id, price_override, duration_minutes_override
- `booking_pages` — salon_id (UNIQUE), slug (UNIQUE), is_active, description, allow_no_
  preference_staff, allow_no_preference_service, custom_title, custom_intro, require_phone/email
- `staff_availability` — barber_id, day_of_week (0=Sun), is_available, time_slots (JSONB array
  of `{start, end}` HH:MM strings)

Indexes: `idx_appointments_salon_datetime`, `idx_appointments_status`,
`idx_reminders_status_send_at WHERE status='pending'` (partial), `idx_barbers_salon_id`,
`idx_clients_salon_id`.

**Authentication:**
Supabase Auth for sign-up/sign-in. On success, client calls `POST /api/auth/register`
(service-role key) to insert `users` + `salons` rows. Middleware uses `getUser()` for
JWT re-validation on every request (not cached sessions). All tables have RLS with
`auth.uid()` via `salon_id → user_id` chain. Public endpoints (`/api/confirm/[token]`,
`/api/book/[slug]/*`) correctly bypass auth but use single-use UUID tokens.

**Email system:**
`lib/resend.ts`: `sendEmail(to, subject, html)` — never throws, returns `{success, id}`
or `{success, error}`. `lib/reminder-templates.ts`: `getEmailHTML(...)` builds inline-CSS
email with HTML injection prevention via `escapeHtml()`. Template variables: `{client_name}`,
`{business_name}`, `{service}`, `{time}`, `{date}`. YES (green) / NO (red) CTA buttons
link to `/api/confirm/[token]?response=yes|no`. Salon branding only — platform is invisible.

**Stripe:**
Plans: `basic` ($19/month, publicly available), `pro` and `business` (internal).
Legacy plan names (`starter`, `professional`) exist for DB compatibility.
`POST /api/stripe/checkout`: creates/reuses Stripe Customer, creates Checkout session in
subscription mode. `POST /api/webhooks/stripe`: verifies HMAC signature, handles
`customer.subscription.created/updated/deleted` and `invoice.payment_failed/succeeded`.
Writes `plan` column on `users` table. In-memory idempotency cache (per-Vercel-instance).

**Cron / reminder dispatch:**
`POST /api/cron/send-reminders` (protected by `X-Cron-Secret` header).
Window: `EMAIL_REMINDER_WINDOW = {minHours: 23, maxHours: 25}` — appointments 23–25h out.
Guards: status=scheduled, future datetime, no existing sent reminder (dedup).
Plan check: `planAllowsEmail(plan)` + monthly limit. Per-salon rate limit: 20/hour.
Flow: insert `pending` reminder with `crypto.randomUUID()` token → `sendEmail()` →
mark `sent` → increment `email_reminders_used_this_month`.

**Key `lib/appointment-helpers.ts` patterns:**
`findEligibleBarbers()`: 4-layer filter (candidate set → service assignment → availability
→ conflicts). Used by dashboard POST, dashboard PUT, and public booking POST.
`appointmentsOverlap()`: duration-aware overlap detection for double-booking.
`localToUTC()`: Intl-based timezone offset calculation for public booking.

**Key types (`types/index.ts`):**
`AppointmentStatus = 'scheduled' | 'confirmed' | 'cancelled'`
`ReminderStatus = 'pending' | 'sent' | 'failed' | 'confirmed' | 'cancelled'`
`UserPlan = 'trial' | 'basic' | 'pro' | 'business' | 'starter' | 'professional' | 'cancelled'`
Full `Database` generic type for end-to-end Supabase type safety.

**Environment variables:**
`NEXT_PUBLIC_SUPABASE_URL`, `NEXT_PUBLIC_SUPABASE_ANON_KEY`, `SUPABASE_SERVICE_ROLE_KEY`,
`NEXT_PUBLIC_APP_URL`, `RESEND_API_KEY`, `RESEND_FROM_ADDRESS` (optional), `STRIPE_SECRET_KEY`,
`STRIPE_WEBHOOK_SECRET`, `STRIPE_BASIC_PRICE_ID`, `STRIPE_PRO_PRICE_ID`, `CRON_SECRET`.

**Interesting detail:** `.github/workflows/keep-alive.yml` pings Supabase REST API every
5 days via GitHub Actions to prevent the free-tier project from auto-pausing.

### What is done well
- RLS correctly enforced at database level with auth.uid() sub-selects
- Clean separation of anon key (client/middleware) vs service-role key (cron/webhooks)
- `sendEmail()` never throws — reliable side-effect isolation
- HTML injection prevented throughout via `escapeHtml()`
- Stripe webhook signature verified
- Duration-aware double-booking detection
- `findEligibleBarbers()` shared across 3 API routes (no duplication)
- White-label email design (salon branding only, platform invisible)
- Single-use confirmation tokens

### What is weak
- No automated tests
- In-memory Stripe idempotency cache (per-Vercel-instance — acknowledged)
- `next.config.ts` completely empty (no CSP, HSTS, image domains)
- `RESEND_FROM_ADDRESS` must be set before live emails work for non-account-holders
- Per-admin `adminSupabase` client created inside try block on each request (not singleton)
- `pg_cron` extension referenced but actual schedule not in schema (manual setup)

### Production readiness
Near-production MVP. Auth, billing, email, RLS, and double-booking all correctly implemented.
Blockers before production: tests, security headers, singleton Stripe idempotency.

---

## 2. cyprus-price-scrapers (timicy-scrapers)

**Import mode:** local-research-only (no LICENSE)
**Coverage:** Full code analysis — 7 Python files studied locally, not committed to lab
**Stack:** Python, httpx (async/sync), Pydantic, BeautifulSoup (selected scrapers),
lxml (XML/sitemap parsing), Supabase Python client (optional)

### Architecture

6 store scrapers + 1 retry script:
| File | Store | Method | Approx. products |
|---|---|---|---|
| `istorm_scraper.py` | iStorm Cyprus | Shopify `/products.json` JSON API pagination | unknown |
| `kotsovolos_scraper.py` | Kotsovolos Cyprus (storeId=10161) | AEM nav JSON → IBM WebSphere Commerce `/api/ext/getProductsByCategory` | unknown |
| `stephanis_scraper.py` | Stephanis | Sitemap XML → async HTML → Schema.org JSON-LD | ~26k products |
| `electroline_scraper.py` | Electroline | WordPress sitemap → async HTML → Yoast JSON-LD `@graph` | ~9.5k products |
| `public_scraper.py` | Public.cy | Sitemap XML → `/public/v2/sku/{id}` JSON API | ~88k (filtered to electronics) |
| `bionic_scraper.py` | Bionic Electronics | Sitemap → async HTML → embedded React JSON in `<script>` | ~5.2k products |
| `public_retry.py` | Public.cy retry | Reads `data/public_errors.json`, retries with CONCURRENCY=1 | n/a |

**Normalized output model (Pydantic `VariantRow`):**
`store`, `store_product_id`, `title`, `vendor`, `product_type`, `sku`, `price` (Decimal),
`available` (bool), `image_url`, `product_url`, `mpn`, `ean`, `mpn_root`,
`identifier_source` ('sku' | 'api' | 'title_regex' | 'none'), `scraped_at`.

**MPN extraction strategies:**
- iStorm: SKU field = MPN directly
- Kotsovolos: `SupplierPartNumbers` attribute + EAN from `BarCode`
- Others: parenthetical regex `\(([A-Z0-9][A-Z0-9\-/]{4,})\)` on title
- Apple part-number root: `APPLE_PN_RE = re.compile(r"^([A-Z0-9]{5,6})[A-Z]{1,3}/[A-Z]$")`
  strips locale suffix (`MTUX3ZD/A` → root `MTUX3`)

**Adaptive rate limiting (`AdaptiveFetcher`):**
Semaphore-bounded async pool. On HTTP 429: halves concurrency (floor: `MIN_CONCURRENCY=1`).
Exponential backoff on 429/5xx: `wait = 2 ** attempt`. `MAX_RETRIES=3`.
Handles: `httpx.TimeoutException`, `ConnectError`, `RemoteProtocolError`, `ReadError/WriteError/CloseError`.
Failed IDs saved to `data/public_errors.json` for `public_retry.py`.

**Output:** `data/{store}.json`. If `SUPABASE_URL` + `SUPABASE_SERVICE_KEY` set, upserts
to `raw_products` table in batches of 500 (`on_conflict="store,store_product_id"`).

**Public.cy category filter:** `TIMICY_CATEGORIES` set (12 electronics categories) reduces
~88k products to relevant subset. Excludes books, music, toys.

### What is done well
- Single normalized `VariantRow` model across 6 different scraping methods
- `identifier_source` field documents data quality per record
- `mpn_root` enables cross-retailer matching without locale suffix noise
- `AdaptiveFetcher` correctly reduces concurrency under 429 pressure
- Browser-like User-Agent headers on all requests
- Deferred Supabase import (scraper runs without SDK if not needed)

### What is weak
- No orchestrator or cron wiring — each scraper is a standalone CLI
- No unified runner or scheduling system
- Scrapers are not generalized into a framework (6 separate implementations)
- Public.cy sitemap → individual API calls is fragile (sitemap format may change)

### Implication
The `mpn_root` field and `identifier_source` confidence tier point to a downstream
price-comparison/matching layer under development. This is not just scrapers — it's
infrastructure for a Cyprus electronics price comparison product.

---

## 3. static-business-website (articya-website)

**Import mode:** local-research-only (no LICENSE)
**Coverage:** Code studied locally — 5 HTML files, 6 CSS files, inline JS

ArtiCYa is a Cyprus youth non-profit connecting young people with Erasmus+ exchange programs.
The website is a static site (no framework, no build step). 5 pages with shared CSS base.
Features: image slider (3 slides, 6s setInterval), scroll-reveal via IntersectionObserver,
mobile hamburger menu. Social links: facebook.com/p/Articya-61560558245829/ and Instagram.

**Useful patterns:** none beyond basic static site structure.

---

## 4. java-search-engine (mini-search-engine)

**Import mode:** local-research-only (no LICENSE)
**Coverage:** Full code analysis — 8 Java files studied locally

### Architecture

Data structures (all hand-implemented):
- `RobinHoodHashing.java`: open-addressing hash table with Robin Hood displacement, FNV-1a
  hash, rehash at 90% load factor. Used for: URL→WebPage map, keyword→KeywordEntry map.
- `AVLTree.java`: self-balancing BST keyed on WebPage.inDegree. Used per keyword to maintain
  pages sorted by inDegree for relevance ranking.
- `MinHeapWebPage.java`: min-heap for top-K search by hit count.
- `singlyLinkedList.java`: custom singly linked list of int values (URL hashes, keyword hashes).
- `WebPage.java`: url, hits, inDegree, keys (singlyLinkedList of keyword hashes), links
  (singlyLinkedList of outgoing URL hashes).

**Search pipeline:** Input file (`pages.txt`) with URL + keyword list + outgoing links.
Operations: keyword search via AVL tree (ranked by inDegree), top-K by hits via min-heap,
BFS reachability, all-pairs shortest paths (BFS from each node), link suggestions via
Jaccard similarity on keyword hash sets.

**Weakness:** BFS link resolution is O(V) per link (full hash table scan to find URL by hash).
Correct algorithm would store URL in the linked list node.

---

## 5. latin-square-solver (latin-square-solver)

**Import mode:** reference-only
**Coverage:** README description only

C command-line Latin Square solver using iterative backtracking with an explicit linked-list
stack (no recursion). Supports boards up to N=9. Fixed cells encoded as negative values in
input file.
