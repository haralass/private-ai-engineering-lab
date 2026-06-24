# Audit — appointment-scheduling-saas (noshowly)

audit_date: 2026-06-24
auditor: lab-automated
status: complete

## License check

- LICENSE file present: YES
- License type: MIT
- Vendoring allowed: YES
- Copy allowed: YES (with attribution)

## Secret scan

Scan date: 2026-06-24
Method: regex pattern matching for sk_live, sk_test, rk_live, eyJ (JWT), AKIA, ghp_,
postgres://:@, hardcoded password patterns.

Results:
- package-lock.json: `eyJ` pattern matched — confirmed to be SHA-512 npm integrity hashes
  (standard npm field, not credentials)
- No hardcoded API keys, tokens, passwords, or secrets found in any source file
- .env.example confirms all sensitive values are env-var references only

Status: CLEAN

## Code review notes

- RLS policies correctly scope all data to authenticated user via salon_id → user_id chain
- Service-role key (SUPABASE_SERVICE_ROLE_KEY) used only in server-side routes
- Anon key (NEXT_PUBLIC_SUPABASE_ANON_KEY) used in client/middleware
- HTML injection prevented via escapeHtml() in reminder-templates.ts
- Stripe webhook signature verified via stripe.webhooks.constructEvent()
- Auth confirmation endpoint (/api/confirm/[token]) uses single-use UUIDs
- Note: SQL injection risk exists in /tables/{table_name}/sample (N/A — this is person2's repo; noshowly does not have this endpoint)
- Note: No automated test coverage

## Execution policy

execution_allowed: false
Reason: Requires live Supabase, Stripe, and Resend credentials. Not approved for
execution in lab environment.
