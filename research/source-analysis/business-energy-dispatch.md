# Source Research Dossier: business-energy-dispatch

research_date: 2026-06-23
analyst: deep-analysis agent
import_type: reference-only (no upstream/ code — web research + SOURCE.yaml only)

---

## Repository identity

- **Display name**: neura-btm-battery-dispatch
- **Functional name**: business-energy-dispatch
- **Creator**: sgavriil01 (Stylianos Gavriil)
- **GitHub URL**: https://github.com/sgavriil01/neura-btm-battery-dispatch
- **Source path**: `sources/business-energy-dispatch/`
- **License**: NOT-FOUND (no LICENSE file — reference-only, copy not permitted)
- **Import type**: reference-only (no code copied into lab)
- **Creator context**: sgavriil01 is Stylianos Gavriil, catalogued as student-1 in this lab. According to `research/people/stylianos-gavriil/OVERVIEW.md`, five of his repositories are catalogued here. His work spans async job processing, battery dispatch simulation, semantic audio search, and commit message generation. The "neura" prefix in the repository name suggests it may be associated with a startup or project named Neura, rather than pure coursework — this is a possible indication of more serious intent than the tsembp repositories [inference].
- **Pinned commit**: d0e3735dd26855ceb41509022c71a44696ef42aa

---

## What it actually does

[Based on web research and SOURCE.yaml — no source code available]

neura-btm-battery-dispatch is a battery-dispatch simulation and optimization project for behind-the-meter (BTM) energy storage systems combined with photovoltaic (PV) generation. "BTM" specifically means the battery is sited at a commercial or industrial facility and operates to reduce that facility's grid import costs, rather than trading on a wholesale electricity market. The project likely models the core optimization problem: given a PV generation forecast, a building load profile, battery state-of-charge (SoC) constraints, and time-of-use (ToU) electricity tariffs, determine the optimal charge/discharge schedule to minimize energy cost or maximize self-consumption.

The "neura" prefix in the name suggests this may have been developed as part of a startup concept or applied project, not pure academic coursework [inference]. The SOURCE.yaml notes describe it as "Battery dispatch simulation."

---

## Architecture

[Inferred from domain knowledge, SOURCE.yaml notes, and web research — no code available]

Battery dispatch optimization systems of this type typically follow one of two architectural patterns:

**Pattern A — Linear Programming (LP) solver-based**: Define the optimization problem formally (minimize grid cost subject to SoC constraints, charge/discharge rate limits, PV availability, and load). Solve with a library like PuLP, scipy.optimize, or CVXPY. This gives a globally optimal dispatch schedule for a planning horizon (typically 24–48 hours ahead).

**Pattern B — Rule-based heuristic**: Simpler but suboptimal. Charge when PV generation exceeds load; discharge during peak tariff periods. Easier to implement and understand but leaves savings on the table.

Given the repository name includes "neura" (suggesting neural/AI orientation) and the btm specificity, it may incorporate a forecasting step (predict next-day load and PV output) before optimization [hypothesis]. The most common Python stack for this is: numpy/pandas for time-series data, scipy or CVXPY for the optimization, matplotlib for visualization.

Battery dispatch systems of this complexity are typically structured as:
1. Data ingestion (PV generation profile, building load profile, tariff schedule)
2. Forecasting layer (optional — predict next interval's load and PV)
3. Optimization model (objective function + constraints)
4. Dispatch controller (executes the schedule, updates SoC)
5. Results/reporting (cost savings, SoC trajectory, self-consumption rate)

---

## Main modules and important files

[Inferred — no code available]

A typical project of this type would contain:
- A main simulation entry point (e.g., `dispatch.py` or `simulation.py`)
- Battery model class (SoC dynamics, charge/discharge efficiency, capacity limits)
- PV generation data (historical or synthetic profiles)
- Tariff data (time-of-use rates, demand charges)
- Optimization module (LP formulation or heuristic)
- Results visualization (matplotlib charts of SoC, grid import, cost)

The "neura-btm" naming suggests there may also be a neural network component, possibly for load forecasting or policy learning (e.g., reinforcement learning for dispatch control) [hypothesis].

---

## Core technical patterns

[Inferred]

- **Behind-the-meter optimization**: Focus on reducing a single building's electricity bill, not grid-level trading. This is a simpler problem than front-of-meter dispatch because the objective (minimize bill) is clear and the constraints (SoC, charge rate, grid connection capacity) are well-defined.
- **Time-series dispatch problem**: The core data structure is a time-indexed series of PV generation, load, and SoC values. The optimization operates over a 24–96 hour horizon.
- **Battery SoC dynamics model**: SoC at time t+1 = SoC(t) + charge(t)*efficiency - discharge(t)/efficiency. This is the fundamental physical constraint of any battery dispatch system.

---

## Novel or interesting mechanisms

The "neura" prefix is intriguing. If this project implements a neural network for dispatch policy (e.g., a deep RL agent that learns when to charge/discharge based on price signals and SoC), that would be more sophisticated than standard LP-based dispatch and potentially more adaptable to real-time conditions where exact forecasts are unavailable [hypothesis]. This would be a genuinely interesting technical contribution if it outperforms LP-based dispatch on noisy real-world data.

---

## Data flow

[Inferred]
```
PV generation profile (historical/synthetic)
Building load profile (historical/synthetic)
Electricity tariff schedule (ToU rates)
        ↓
Battery dispatch optimizer
        ↓
Optimal charge/discharge schedule
Cost savings calculation
SoC trajectory
        ↓
Visualization / report
```

---

## Dependencies

[Inferred from domain standards]
- Python
- numpy, pandas — time-series data manipulation
- scipy.optimize or CVXPY or PuLP — LP/QP optimization (if LP-based)
- OR PyTorch/TensorFlow/stable-baselines3 — if RL-based
- matplotlib or plotly — visualization
- Possibly pvlib — PV generation simulation

---

## Security model

Not applicable for a simulation/optimization tool used locally. If deployed as a web service, energy data (load profiles, billing data) would be sensitive and require encryption at rest and in transit.

---

## Testing strategy

[Unknown — no code available]. Academic/personal projects of this type rarely have formal test suites. Validation is typically done by comparing dispatch costs against a baseline (no battery) and checking that SoC constraints are never violated.

---

## Genuinely reusable elements

[Conceptual — cannot verify without code]

1. **Battery SoC dynamics model**: The mathematical model of battery state-of-charge evolution is standard and reusable regardless of the optimization method.
2. **Tariff modeling pattern**: Representing time-of-use electricity tariffs as a time-indexed array and using it as a cost signal in an optimization is a reusable pattern.
3. **Dispatch-vs-baseline comparison**: The methodology of comparing an optimized dispatch schedule against a no-battery baseline to calculate savings is the core ROI calculation pattern.

---

## What NOT to reuse

- Any data hardcoded for a specific electricity market (e.g., Cyprus tariffs) would need to be replaced for other geographies.
- If the project uses simplified battery models (no degradation, no temperature effects), these would be inadequate for real commercial deployment where battery lifetime is a major economic factor.
- No LICENSE file — cannot copy code.

---

## Production-readiness

**Not assessable without code**. As a simulation tool, it is not designed for production deployment. For a production BTM optimization system, you would additionally need: real-time grid price integration, BMS (battery management system) API integration, cloud deployment, monitoring, and battery degradation modeling.

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- Behind-the-meter scope is commercially relevant: this is the exact problem commercial building operators face
- Python is the right stack for energy optimization (scipy/CVXPY ecosystem is mature)
- If it includes neural/RL components, potentially more adaptable than pure LP solvers

**Weaknesses**:
- No LICENSE — cannot copy or use commercially
- Reference-only in this lab — limited ability to study the actual implementation
- Unknown quality and completeness

**Technical debt**: Unknown.

---

## Novel or differentiated elements

The BTM focus combined with "neura" naming is the potentially differentiated element. If this project demonstrates that a learned dispatch policy outperforms LP-based optimization on realistic building load data with noisy PV forecasts, that would be novel. Most commercial BTM optimization tools use LP/MPC; a learned approach could generalize better across diverse building types.

---

## Possible clean-room adaptations

The product concept (`product-concepts/business-energy-optimization/`) extends this to a commercial tool with:
1. Load profile import (from smart meter CSV or estimation from building type)
2. PV production simulation via pvlib
3. LP-based dispatch optimization with CVXPY
4. Multi-tariff modeling (ToU + demand charges + export rates)
5. ROI calculation (CapEx, OpEx, savings, payback period, IRR)
6. Scenario comparison (PV only vs. PV+battery vs. battery only)
7. PDF/Excel report generation

All of these can be built clean-room without copying from the source repository, which has no license.

---

## Business applications

Directly inspires `product-concepts/business-energy-optimization/`. The commercial application is a SaaS tool for:
- Hotel operators and commercial building managers evaluating PV+battery investments
- Energy consultants producing feasibility reports for SME clients
- Solar installers/EPCs generating proposals automatically

**Market context**: The BEMS (Building Energy Management System) market is growing rapidly. SolarEdge ONE for C&I (released 2024–2025) targets the same segment from the hardware side. HOMER Energy (now UL Solutions) targets engineers for system design. The gap is an accessible, non-engineer-facing ROI and scenario tool for business decision-makers.

---

## Competitor landscape

From web research (2026-06-23):

- **HOMER Energy / HOMER Grid** (https://www.homerenergy.com/products/grid/index.html) — Engineering-grade hybrid system design tool. Subscription pricing. Targets engineers and academics. Too complex for building managers. Can integrate with PVsyst for solar modeling.
- **SolarEdge ONE for C&I** (https://investors.solaredge.com/news-releases/news-release-details/re-north-america-2024-solaredge-presents-new-software-capabilities) — Hardware-tied (SolarEdge inverters required). Cloud-based, AI-optimized dispatch. Released 2024–2025 in North America. Technically sophisticated but locked to SolarEdge ecosystem.
- **PVsyst** (https://www.pvsyst.com/) — Industry-standard solar PV simulation for bankable yield analysis. Does not do battery dispatch optimization natively; integrates with HOMER Grid.
- **Energy Toolbase** (https://www.energytoolbase.com/) — Commercial energy storage proposal and analysis tool. Targets C&I solar+storage. More directly competitive. Pricing not public. (Review: https://www.surgepv.com/reviews/energy-toolbase)
- **Eaton Building Energy Management Software** (https://www.eaton.com/bg/en-gb/catalog/energy-storage/building-energy-management-software.html) — BEMS from a major hardware vendor. Enterprise pricing. Not SME-oriented.
- **CAMOPO Energy** (cited in web research) — SaaS platform for hybrid power plants using digital twins and ML. Targeting utility/portfolio scale rather than individual buildings.

**Gap identified**: The existing tools are either engineer-grade (HOMER, PVsyst), hardware-locked (SolarEdge ONE), or enterprise-priced (Eaton). There is no accessible, hardware-agnostic, business-decision-maker-facing tool for SMEs to evaluate PV+battery ROI scenarios without hiring an energy consultant.

---

## Related business ideas in this lab

- `product-concepts/business-energy-optimization/README.md` — the direct product concept
- `research/people/stylianos-gavriil/IDEAS_DERIVED.md` — explicitly connects this source to the energy optimization concept
- `business-research/BUSINESS_IDEAS_INDEX.md` — evidence level: initial-research

---

## Related sources in this lab

- Other sgavriil01 sources (forgequeue, semantic-audio-search) — same creator but different domains
- `sources/change-monitoring-notifications/` — a monitoring + notification infrastructure could complement a BEMS system (alert when dispatch falls behind schedule or PV underperforms forecast) [inference]

---

## Open questions

1. Does the "neura" prefix refer to a startup, a research group, or just a naming choice?
2. Is the dispatch approach LP-based, RL-based, or heuristic?
3. Does the project include real tariff data (e.g., Cyprus electricity tariffs) or synthetic data?
4. Is the project actively maintained or abandoned?
5. Would sgavriil01 be open to licensing this work if contacted?

---

## Final research conclusion

neura-btm-battery-dispatch is a potentially valuable reference for behind-the-meter battery dispatch optimization, but it is reference-only due to the missing LICENSE. The domain it addresses — commercial building PV+battery optimization — is a genuine market gap between expensive engineer-grade tools and accessible business-decision-maker tools. The product concept it inspires is well-scoped. Without access to the code, it is unclear whether the technical approach is LP-based, RL-based, or heuristic, and whether the "neura" naming reflects genuine ML components or just a project name. The clean-room adaptation path (CVXPY for LP, pvlib for PV simulation, pandas for time-series) does not require copying from this source and is the recommended approach given the license situation.
