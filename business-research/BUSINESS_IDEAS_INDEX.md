# Business Ideas — Master Index

last_updated: 2026-06-23

This file is the master index for all business ideas tracked in this lab.
It cross-references product concepts, category-A ideas, and startup white spaces
with their current status, evidence level, and next action.

Do not add an idea here without a corresponding file in one of:
- `business-research/category-a/`
- `business-research/startup-white-spaces/`
- `product-concepts/`

---

## Product Concepts (research-stage, prototype-backed or source-backed)

| Concept | Status | Lab sources | Evidence level | Next action |
|---|---|---|---|---|
| [Adaptive SQL Learning Platform](../product-concepts/adaptive-sql-learning-platform/README.md) | research | `sources/database-query-training/` | initial-research | Define security model, interview potential users |
| [Agent Permission Firewall](../product-concepts/agent-permission-firewall/README.md) | research | `components/agent-safety-firewall/`, `sources/deterministic-agent-safety/`, `sources/structured-agent-development/` | initial-research | Customer discovery: dev teams using agents |
| [Business Energy Optimization](../product-concepts/business-energy-optimization/README.md) | research | `sources/business-energy-dispatch/` | initial-research | Identify target customer segment; build demo |
| [Functional Generative Design](../product-concepts/functional-generative-design/README.md) | research | None (barkod.studio — proprietary, no code) | assumption | Identify first asset type; build POC |
| [Intelligent Change Monitoring](../product-concepts/intelligent-change-monitoring/README.md) | research | `sources/change-monitoring-notifications/` | initial-research | Define target domain; interview potential users |
| [Skill Benchmarking Platform](../product-concepts/skill-benchmarking-platform/README.md) | research | `components/skill-evaluation-runner/` | initial-research | Internal dogfood; share with other AI tool users |
| [Synthetic Test Data Platform](../product-concepts/synthetic-test-data-platform/README.md) | research | `sources/synthetic-relational-data/` | initial-research | Generalize from ride-hail schema; test with another domain |
| [Trusted Skill Marketplace](../product-concepts/trusted-skill-marketplace/README.md) | research | `skill-library/` (internal) | assumption | Research whether this is a community problem or enterprise problem |

---

## Category A Ideas (CS+Economics founder, software/AI/B2B)

| Idea | Status | Lab sources | Evidence level | Next action |
|---|---|---|---|---|
| [EvidenceOps — AI Act / NIS2 / VSME](category-a/evidenceops-ai-act-nis2-vsme.md) | raw | None | none | Research: which regulation affects the most SMEs soonest? |
| [VSME ESG Data Room](category-a/vsme-esg-data-room.md) | raw | None | none | Verify VSME standard status; find SMEs with ESG questionnaires |
| [Synthetic Regulatory Document AI](category-a/synthetic-regulatory-document-ai.md) | raw | `sources/synthetic-relational-data/` | none | Talk to compliance software vendors about test data |

---

## Startup White Spaces

| White space | Status | Lab sources | Evidence level | Next action |
|---|---|---|---|---|
| [Agent Permission Firewall](startup-white-spaces/agent-permission-firewall.md) | research | `components/agent-safety-firewall/`, `sources/deterministic-agent-safety/` | initial-research | Customer discovery: 5 dev teams using agents |
| [Mobile Privacy Truth Engine](startup-white-spaces/mobile-privacy-truth-engine.md) | research | `sources/privacy-safe-commit-assistant/` (indirect) | none | Interview enterprise IT admins about app vetting |
| [Accessibility Source-Fix CI](startup-white-spaces/accessibility-source-fix-ci.md) | research | None | none | Interview 3 frontend devs about WCAG CI workflow |
| [PQC Discovery Copilot](startup-white-spaces/pqc-discovery-copilot.md) | research | None | none | Research NIST/NSA guidance; check SAST vendor coverage |

---

## Overlap map

Some ideas address the same underlying problem from different angles:

| Group | Related ideas |
|---|---|
| Compliance tooling | EvidenceOps, VSME ESG Data Room, Synthetic Regulatory Document AI |
| Agent safety | Agent Permission Firewall (product concept), Agent Permission Firewall (white space) |
| Synthetic data | Synthetic Test Data Platform, Synthetic Regulatory Document AI |
| Skill ecosystem | Skill Benchmarking Platform, Trusted Skill Marketplace |

---

## Founder fit summary

All ideas above fit a founder who:
- Has a CS and Economics background
- Can build software products without hardware, factory, or large capital
- Has experience using AI coding tools directly
- Is based in Europe (EU regulatory context is relevant for compliance ideas)

No idea in this index has been validated with real users or paying customers.
Evidence levels reflect research-stage confidence only.

---

## Ideas not yet filed

If you have an idea that isn't in this index, add a file to one of the directories
above and add a row here. Do not add rows to this index without a corresponding file.
