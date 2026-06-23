# Source Research Dossier: change-monitoring-notifications

research_date: 2026-06-23
analyst: deep-analysis agent
import_type: reference-only (no upstream/ code — web research + SOURCE.yaml only)

---

## Repository identity

- **Display name**: WG-Course-Task-Notifier-Bot
- **Functional name**: change-monitoring-notifications
- **Creator**: tsembp (Panagiotis Tsembekis)
- **GitHub URL**: https://github.com/tsembp/WG-Course-Task-Notifier-Bot
- **Source path**: `sources/change-monitoring-notifications/`
- **License**: NOT-FOUND (no LICENSE file — reference-only, copy not permitted)
- **Import type**: reference-only
- **Creator context**: CS student, same as SQL-Gym and three other sources in this lab. The "WG" prefix likely stands for "Web Group" or refers to a specific university course or group project. This is most likely a university project that automates monitoring a course platform (e.g., a Moodle or custom task submission portal) for new assignments and deadlines, then notifies students via Telegram or similar.
- **Pinned commit**: 9abbac6f4f4126e44187da84889cf6b6571397b0

---

## What it actually does

[Based on web research and SOURCE.yaml — no source code available]

WG-Course-Task-Notifier-Bot is a polling-based change-detection and notification bot for a university course task system. The "WG" context and the word "Course" in the name strongly suggest this monitors a specific university portal (course management system, task submission platform, or grade portal) and sends notifications when new tasks, deadlines, or grade changes are detected. The delivery mechanism is almost certainly a Telegram bot (the dominant choice for student notification bots in CS programs) or email. The SOURCE.yaml notes describe it as: "Notification bot pattern."

The core loop is: poll a target URL/API at regular intervals → compare current state to last known state → if changed, parse the difference → send notification with the change summary.

---

## Architecture

[Inferred from domain knowledge and naming]

Student notification bots of this type follow a standard pattern:

**Polling loop**: A scheduler (schedule library, APScheduler, or a simple `while True: time.sleep(interval)`) triggers a fetch of the monitored page or API endpoint at a configured interval (e.g., every 15–60 minutes).

**State comparison**: The fetched content is hashed or compared against a previously stored state (in a JSON file, SQLite, or just in memory). A change triggers notification logic.

**Notification dispatch**: Most student bots of this era use `python-telegram-bot` to send messages to a Telegram group or individual user. Email via SMTP or webhook-to-Slack are alternatives.

**Target scraping**: If the target is a university web portal (not a clean API), BeautifulSoup is the typical HTML parser used to extract task names, deadlines, and statuses.

Likely structure:
```
main.py (or bot.py) — entry point, scheduler, bot setup
scraper.py (or fetcher.py) — HTTP fetch + HTML parsing
state.py (or storage.py) — persistence of last-known state
notifier.py — Telegram/email notification dispatch
config.py or .env — credentials, target URL, chat_id
```

---

## Main modules and important files

[Inferred — no code available]

- Main entry point with polling loop
- Scraper/fetcher targeting the university portal
- State storage (likely a simple JSON file or sqlite for last-seen content hash)
- Telegram bot integration (likely python-telegram-bot)
- Configuration (target URL, credentials, notification channel IDs)

---

## Core technical patterns

**1. Poll-diff-notify loop**: The fundamental pattern: fetch → compare → notify. This is the simplest implementation of a change monitoring system. No webhooks, no push subscriptions, just repeated fetching with state comparison.

**2. Content hashing for change detection**: The typical approach is to hash the fetched content (or a specific extracted section) and compare to the stored hash. A hash mismatch triggers notification. This is simple but catches all changes, including irrelevant ones (timestamps, advertisements, session tokens in HTML).

**3. Targeted extraction**: For a course portal, the bot likely parses specific HTML elements (task list table, deadline column, grade field) rather than hashing the entire page. This is more robust than full-page hashing.

---

## Novel or interesting mechanisms

The interesting thing about this project (from a product design perspective) is what it does NOT have: deduplication, importance classification, or multi-source support. These are exactly the limitations that the `product-concepts/intelligent-change-monitoring/` concept addresses. The bot sends a notification for every detected change on its single monitored source. This is fine for a course task system (every change is potentially important), but does not generalize to monitoring product pricing, regulatory documents, or grant calls where not every change is equally significant.

---

## Data flow

[Inferred]
```
Scheduler (interval-based timer)
        ↓
HTTP GET target URL (university portal page)
        ↓
HTML parser (BeautifulSoup) → extract relevant fields
        ↓
Compare with stored state (JSON/SQLite)
        ↓ (on change)
Format notification message
        ↓
Telegram bot send_message → user/group
        ↓
Update stored state
```

---

## Dependencies

[Inferred from domain standards for Python notification bots of this type]
- `requests` or `httpx` — HTTP fetching
- `beautifulsoup4` + `lxml` — HTML parsing
- `python-telegram-bot` — Telegram notification delivery
- `schedule` or `APScheduler` — polling interval management
- `python-dotenv` — credential management (.env file)
- Possibly `sqlite3` or plain JSON for state persistence

---

## Security model

Minimal for a personal student bot. Credentials (Telegram bot token, target portal credentials) are stored in a `.env` file. The bot runs on a personal machine or a small VPS. No multi-user isolation, no rate limiting, no input validation needed for a single-user monitoring bot.

For a production multi-tenant monitoring service, this model would need complete replacement: per-user credential isolation, secret management, rate limiting on scraping (respect robots.txt, implement delays), and secure credential storage.

---

## Testing strategy

[Unknown — likely none for a student bot project]

---

## Genuinely reusable elements

[Conceptual]

1. **Poll-diff-notify pattern**: The core three-step loop is the reusable primitive. It is simple, well-understood, and works for any source that doesn't offer webhooks.

2. **HTML extraction targeting**: The pattern of parsing specific HTML elements (rather than diffing raw HTML) is the right approach for structured page monitoring. BeautifulSoup selectors for a known target structure are robust and fast.

3. **State persistence with hashing**: Storing a hash of the last-seen content and comparing on each poll is a minimal, efficient state management approach for low-frequency monitoring.

---

## What NOT to reuse

- Single-source, single-user architecture: does not scale to a multi-tenant service
- No deduplication: will re-notify if the same change is reformatted (e.g., HTML whitespace change)
- No importance classification: treats all changes equally
- Likely hardcoded to a specific university portal URL/structure
- No LICENSE — cannot copy code

---

## Production-readiness

Not production-ready. A single-source student bot. To build a production monitoring service, every layer (scraping, state management, deduplication, classification, notification routing, rate limiting) needs to be redesigned.

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- Demonstrates a complete working implementation of the core pattern
- Student projects of this type are often surprisingly reliable for their narrow target
- Low complexity means low surface area for bugs

**Weaknesses**:
- Single source, hardcoded target
- No deduplication or importance filtering
- No LICENSE — cannot use commercially
- Reference-only in this lab

**Technical debt**: N/A (reference-only, student project)

---

## Novel or differentiated elements

Not technically novel. The poll-diff-notify pattern is decades old. The value is in using it as an existence proof for the product concept: students build these bots because the pain point (missing a course deadline due to lack of notification) is real. The product opportunity is generalizing and productizing this pattern for other high-stakes change monitoring scenarios (job listings, grant calls, regulatory updates).

---

## Possible clean-room adaptations

A clean-room implementation of the monitoring service concept would include:
1. A normalized Source abstraction (URL + extraction selector + credentials)
2. A change detection engine with content diffing (not just hash comparison — compute a semantic diff of the extracted content)
3. A deduplication layer (check if the new content is semantically similar to already-notified content)
4. An importance classifier (LLM-based or rule-based: is this a meaningful change?)
5. A notification batching layer (immediate/hourly/daily digest)
6. Multi-channel delivery (Telegram, email, Slack, webhook)
7. A web UI for configuring monitoring sources

---

## Business applications

Directly inspires `product-concepts/intelligent-change-monitoring/`. Specific high-value domains:

- **Job/internship listings**: Monitor company career pages and university job boards for new postings matching keywords. Deduplication is critical here (same job posted on multiple boards).
- **Grant and tender calls**: Monitor national research funding agencies, EU Horizon portals, and government procurement platforms for new calls. High stakes, low frequency — users will pay.
- **Regulatory and legal updates**: Monitor GDPR guidance, API policy pages, legal changes. Compliance teams pay well for this.
- **Pricing and product changes**: Monitor SaaS pricing pages, product announcement blogs. Useful for competitive intelligence.
- **Software security releases**: Monitor GitHub releases or CVE databases for new security advisories on specified packages.

---

## Competitor landscape

From web research (2026-06-23):

- **Visualping** (https://visualping.io/) — The market leader in web change monitoring. 2M+ users. Pricing: Free (5 pages, 150 checks/month), Personal from $10/month (1,000 checks, 10 pages), Business from $100/month (20,000 checks, 200 pages, team features). Key feature added recently: AI-powered importance classification ("IMPORTANT" flag). This is the most direct competitor.
- **changedetection.io** (https://github.com/dgtlmoon/changedetection.io) — Open-source, self-hosted. Also has a SaaS plan. The GitHub repo has 20k+ stars. Best for technical users who want control. No importance classification.
- **Browse.ai** (https://www.browse.ai/) — No-code web scraping + monitoring. Focuses on structured data extraction (tables, lists) rather than unstructured page change detection. Higher-level than Visualping.
- **Distill** (https://distill.io/) — Desktop app + browser extension for page monitoring. Free and paid plans. Lightweight, good for individual users.
- **Oxylabs Website Change Monitoring** (https://oxylabs.io/solutions/website-change-monitoring) — Enterprise-grade, API-first scraping infrastructure with change detection. Not SMB-oriented.
- **Thunderbit** (per web research) — AI-powered monitoring, automation, structured data exports. Listed as a leading tool for 2025.

**Visualping's AI importance flag** is significant: the market leader is moving toward the exact differentiator the `intelligent-change-monitoring` concept proposes. The window for that specific differentiator is closing. The remaining opportunity is vertical specificity (e.g., a monitoring tool built specifically for grant calls, with domain-specific extraction and classification) rather than horizontal importance filtering.

---

## Related business ideas in this lab

- `product-concepts/intelligent-change-monitoring/README.md` — the direct product concept
- `business-research/BUSINESS_IDEAS_INDEX.md` — evidence level: initial-research
- `sources/business-energy-dispatch/` — monitoring infrastructure could complement BEMS alerting

---

## Related sources in this lab

- Other tsembp sources: `sources/database-query-training/`, `sources/synthetic-relational-data/`, `sources/data-structure-search-engine/`, `sources/algorithm-benchmarking/`

---

## Open questions

1. What is the "WG" prefix? Is this a specific course, lab group, or student team identifier?
2. What university portal does this monitor? Is it a public portal (e.g., a specific university's Moodle instance) or a custom internal system?
3. Does the bot use a Telegram bot or another notification channel?
4. Is the project still in use (i.e., was it maintained after the course ended)?
5. Is the change detection at the level of full-page hash, section hash, or structured field extraction?

---

## Final research conclusion

WG-Course-Task-Notifier-Bot is a minimal student tool that demonstrates the poll-diff-notify pattern in a practical context. Its technical contribution is limited — the pattern is well-known — but its importance as a source is that it validates the user pain point: students build these bots from scratch because existing tools don't serve their specific monitoring needs. The product concept it inspires (`intelligent-change-monitoring`) is sound but faces a sophisticated competitor in Visualping, which has added AI importance classification. The remaining differentiation opportunity is vertical specificity (domain-aware extraction and classification) rather than the general importance-flagging feature. The missing LICENSE means no code can be used, but a clean-room implementation of the monitoring service is entirely feasible using requests, BeautifulSoup, and an LLM classifier.
