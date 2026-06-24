# Business Ideas — Master Index

last_updated: 2026-06-24

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

Wave 1 (original) and Wave 2–3 (parallel agent research) ideas.
All 20 white-space files are indexed here. See `DEEP_RESEARCH_SYNTHESIS.md` Section 10b–10c for full scoring.

### Agent safety / AI operations

| White space | Status | Lab sources | Evidence level | Next action |
|---|---|---|---|---|
| [Agent Permission Firewall](startup-white-spaces/agent-permission-firewall.md) | research | `components/agent-safety-firewall/`, `sources/deterministic-agent-safety/` | initial-research | Customer discovery: 5 dev teams using agents |
| [Agent Liability Attribution Engine](startup-white-spaces/agent-liability-attribution-engine.md) | research | None | none | Interview enterprise legal/compliance teams |
| [Agent Specification Firewall](startup-white-spaces/agent-specification-firewall.md) | research | `sources/deterministic-agent-safety/` | initial-research | Define spec language; interview 3 platform teams |
| [AI Agent Cost Circuit Breaker](startup-white-spaces/ai-agent-cost-circuit-breaker.md) | research | None | none | Interview teams with high LLM API bills |
| [LLM Prompt Regression Monitor](startup-white-spaces/llm-prompt-regression-monitor.md) | research | None | none | Interview ML engineers deploying LLM features |
| [NHI Agent Identity Governance](startup-white-spaces/nhi-agent-identity-governance.md) | research | None | none | Interview platform security teams |
| [Vibe Security Gate](startup-white-spaces/vibe-security-gate.md) | research | `sources/deterministic-agent-safety/` | initial-research | Interview security engineers using AI coding tools |
| [AI Support Response Gate](startup-white-spaces/ai-support-response-gate.md) | research | None | none | Interview CS teams deploying LLM-powered support |
| [Hallucination Cost Accounting](startup-white-spaces/hallucination-cost-accounting.md) | research | None | none | Research incident postmortems; find 3 teams with LLM errors |

### Regulatory compliance / EU legislation

| White space | Status | Lab sources | Evidence level | Next action |
|---|---|---|---|---|
| [CRA Vulnerability Disclosure Automation](startup-white-spaces/cra-vulnerability-disclosure-automation.md) | research | None | none | Research CRA timelines; interview product security teams |
| [DORA Register of Information](startup-white-spaces/dora-register-of-information.md) | research | None | none | Interview EU financial entity compliance officers |
| [GPAI Documentation Form Automation](startup-white-spaces/gpai-documentation-form-automation.md) | research | None | none | Interview AI product teams in EU |
| [LC-as-a-Service](startup-white-spaces/lc-as-a-service.md) | research | None | none | Research LoA/LC issuing banks; interview trade finance desks |
| [MDR + AI Act Dual Conformity](startup-white-spaces/mdr-ai-act-dual-conformity.md) | research | None | none | Interview EU MedTech compliance officers |
| [Multi-Regulation Compliance Platform](startup-white-spaces/multi-regulation-compliance-platform.md) | research | None | none | Interview SME compliance managers |
| [PLD Liability Evidence Vault](startup-white-spaces/pld-liability-evidence-vault.md) | research | None | none | Interview EU product liability counsel |
| [Training Data Bill of Materials](startup-white-spaces/training-data-bill-of-materials.md) | research | None | none | Interview ML teams with EU deployment; research GPAI obligations |

### Security / privacy / infrastructure

| White space | Status | Lab sources | Evidence level | Next action |
|---|---|---|---|---|
| [Accessibility Source-Fix CI](startup-white-spaces/accessibility-source-fix-ci.md) | research | None | none | Interview 3 frontend devs about WCAG CI workflow |
| [Mobile Privacy Truth Engine](startup-white-spaces/mobile-privacy-truth-engine.md) | research | `sources/privacy-safe-commit-assistant/` (indirect) | none | Interview enterprise IT admins about app vetting |
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
