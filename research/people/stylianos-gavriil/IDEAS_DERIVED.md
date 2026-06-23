# Ideas Derived from Stylianos Gavriil's Repositories

research_date: 2026-06-22
source: connections documented in product-concepts/ and reference-implementations/

This file lists only ideas where the connection to a specific repository is
explicitly documented in this lab (in README.md, SOURCE.yaml notes, or product concept files).
No connections have been assumed or inferred beyond what is already written in the repo.

---

## Explicit connections (documented in product-concepts/)

### Business Energy Optimization

**Inspired by:** `sources/business-energy-dispatch/` (neura-btm-battery-dispatch)
**Existing concept:** `product-concepts/business-energy-optimization/`
**Connection source:** product-concepts/business-energy-optimization/README.md states:
  "Inspired by: sources/business-energy-dispatch/ (sgavriil01/neura-btm-battery-dispatch)"

The dispatch simulation logic in neura-btm-battery-dispatch showed the technical
feasibility of the core optimization algorithm. The product concept extends it with
tariff modeling, ROI analysis, and a B2B interface.

---

## Documented patterns without explicit product concepts

The following repositories are catalogued as reference-only and have corresponding
stubs in `reference-implementations/`, but no product concept has been explicitly
connected to them in this lab:

| Repository | Lab source | Reference implementation | Notes |
|---|---|---|---|
| forgequeue | `sources/durable-background-job-queue/` | `reference-implementations/durable-background-job-queue/` | MIT, vendored — infrastructure pattern |
| sms-platform | `sources/asynchronous-job-processing/` | `reference-implementations/asynchronous-job-processing/` | reference-only |
| whisper-faiss-example | `sources/semantic-audio-search/` | `reference-implementations/semantic-audio-search/` | reference-only |
| commitgen | `sources/privacy-safe-commit-assistant/` | `reference-implementations/privacy-safe-commit-assistant/` | reference-only |

---

## What needs user input

- Were there other product ideas that emerged directly from studying these repositories
  that should be documented here?
- Should semantic-audio-search be elevated to a product concept?
- Is the async job processing pattern from forgequeue intended for use in any
  specific product under development?
