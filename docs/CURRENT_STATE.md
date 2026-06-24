# Current State

Last updated: 2026-06-24

---

## Repository status

Phase 1 complete (merged to main). Phase 2 complete: person3 and person4 sources
ingested and researched. All student-1/student-2 labels replaced with person1/person2.

Total sources: **41** (20 vendored · 5 local-research-only · 16 reference-only).

---

## People labels

| Lab label | Repos | Vendored (code in lab) | Local-research-only | Reference-only |
|---|---|---|---|---|
| person1 | 5 | 1 (forgequeue/MIT) | 0 | 4 |
| person2 | 6 | 2 (SQL-Gym/MIT, AI-Study-Mate/MIT) | 0 | 4 |
| person3 | 5 | 1 (noshowly/MIT) | 3 | 1 |
| person4 | 3 | 0 | 2 | 1 |

---

## Source import status — person1 (5 repos)

| Functional name | Import mode | License | Code in lab? |
|---|---|---|---|
| durable-background-job-queue | vendored-snapshot | MIT | YES — `sources/durable-background-job-queue/upstream/` |
| asynchronous-job-processing | reference-only | NOT-FOUND | NO — metadata + README only |
| business-energy-dispatch | reference-only | NOT-FOUND | NO — metadata + README only |
| semantic-audio-search | reference-only | NOT-FOUND | NO — metadata + README only |
| privacy-safe-commit-assistant | reference-only | NOT-FOUND | NO — metadata + README only |

## Source import status — person2 (6 repos)

| Functional name | Import mode | License | Code in lab? |
|---|---|---|---|
| database-query-training | vendored-snapshot | MIT | YES — `sources/database-query-training/upstream/` |
| modular-rag-learning | vendored-snapshot | MIT | YES — `sources/modular-rag-learning/upstream/` |
| change-monitoring-notifications | reference-only | NOT-FOUND | NO — metadata + README only |
| synthetic-relational-data | reference-only | NOT-FOUND | NO — metadata + README only |
| algorithm-benchmarking | reference-only | NOT-FOUND | NO — metadata + README only |
| data-structure-search-engine | reference-only | NOT-FOUND | NO — metadata + README only |

## Source import status — person3 (5 repos)

| Functional name | Import mode | License | Code in lab? |
|---|---|---|---|
| appointment-scheduling-saas | vendored-snapshot | MIT | YES — `sources/people/person3/github/noshowly/upstream/` |
| cyprus-price-scrapers | local-research-only | NOT-FOUND | NO — studied locally, dossier in research/people/person3/ |
| static-business-website | local-research-only | NOT-FOUND | NO — studied locally |
| java-search-engine | local-research-only | NOT-FOUND | NO — studied locally |
| latin-square-solver | reference-only | NOT-FOUND | NO — metadata only |

## Source import status — person4 (3 repos)

| Functional name | Import mode | License | Code in lab? |
|---|---|---|---|
| llm-structured-object-generator | local-research-only | NOT-FOUND | NO — full code analysis done, dossier in research/people/person4/ |
| unity-3d-platformer | local-research-only | NOT-FOUND | NO — C# scripts analyzed locally |
| person4-github-profile | reference-only | NOT-FOUND | NO — profile README only |

## Source import status — external (19 repos)

All external sources: complete. See `source-catalog/import-status.yaml` for details.
Vendored (MIT/Apache): deterministic-agent-safety, structured-agent-development,
full-product-engineering-agent-stack, persistent-agent-memory, design-quality-and-review,
design-taste, ui-ux-reference, writing-quality, product-marketing-context,
terminal-coding-agent, model-layer-streaming, design-agent-reviews, interaction-motion-toast.
Reference-only: anthropic-skills, vercel-skills, interaction-and-motion-design,
kimi-model-family, glm-model-family, code-review-assistant.

## Source import status — student-collab (3 repos, license pending)

| Functional name | Import mode | License | Status |
|---|---|---|---|
| dance-studio-api | vendored-snapshot | unknown | candidate — no code reuse until license confirmed |
| ride-booking-php | vendored-snapshot | unknown | candidate — upstream/ not to be exposed; demo credential in login.php |
| twitter-sentiment-classifier | vendored-snapshot | unknown | candidate — no code reuse until license confirmed |

---

## Research people index

| Lab label | Research directory | Research depth |
|---|---|---|
| person1 | `research/people/person1/` | README-based (4/5 repos reference-only) |
| person2 | `research/people/person2/` | Code-level for 2/6 (vendored) |
| person3 | `research/people/person3/` | Code-level for 4/5 repos |
| person4 | `research/people/person4/` | Code-level for 2/3 repos |

---

## Component status

| Component | Status |
|---|---|
| agent-safety-firewall | prototype — tests written, not production-ready |
| All others | research / stub |
