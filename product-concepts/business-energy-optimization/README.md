# Business Energy Optimization

Status: `research`
Inspired by: `sources/business-energy-dispatch/` (sgavriil01/neura-btm-battery-dispatch)

## Summary

PV + battery dispatch optimization and ROI analysis for commercial buildings (offices, hotels, restaurants). Inputs: energy tariffs, PV production profile, load profile, battery specs. Output: optimal charge/discharge schedule, projected savings, payback period.

## Target users

- Hotel operators planning solar + storage installation
- Commercial building managers evaluating battery storage
- Energy consultants producing feasibility reports

## Core functionality

1. Load profile import (from smart meter or estimated from building type)
2. PV production simulation (location, panel specs, orientation)
3. Battery dispatch optimization (minimize grid import during peak tariff periods)
4. Tariff modeling (time-of-use, demand charges, export tariffs)
5. ROI calculation (CapEx, OpEx, savings, payback, IRR)
6. Scenario comparison (PV only vs PV+battery vs battery only)

## Inspired by

The sgavriil01/neura-btm-battery-dispatch project implements the core dispatch simulation. Our version adds the full business context: tariff modeling, ROI, and report generation.

See `reference-implementations/business-energy-dispatch-simulation/` for the reference build.

---

## Related sources

- `sources/business-energy-dispatch/` (sgavriil01/neura-btm-battery-dispatch, MIT) — upstream dispatch implementation
- `reference-implementations/business-energy-dispatch-simulation/` — in-lab reference build

## Research connections

- `research/people/stylianos-gavriil/IDEAS_DERIVED.md` — this concept is explicitly derived from reviewing sgavriil01/neura-btm-battery-dispatch

## Origin

Sourced from sgavriil01/neura-btm-battery-dispatch via sources import. The upstream
project implements battery dispatch optimization; this product concept extends it to
the full commercial building energy context (tariff modeling, ROI, scenario comparison).

## Current evidence level

`initial-research` — upstream source studied, reference implementation plan exists.
No user interviews with hotel operators or energy consultants conducted.

## Open assumptions

- Hotel operators / building managers make this decision (vs. specialist energy consultants)
- The ROI calculation and scenario comparison are the highest-value features
- Tariff data can be reliably sourced or input manually by target users

## Competitor landscape

Source: verified from product websites, 2026-06-23. Full analysis in `research/domain-synthesis/data-and-learning.md`.

| Competitor | Type | Target | Gap |
|---|---|---|---|
| Homer Energy (HOMER Pro) | PV+storage system design | Energy engineers / consultants | Complex; requires engineering expertise; not self-serve for building managers |
| PVsyst | Solar simulation | Engineers | Solar-only; no battery dispatch optimization; technical tool |
| EnergyHub | Grid-connected asset management | Utilities / enterprise | Utility-side; not accessible to individual building operators |
| AutoGrid (Enel X) | AI-based grid optimization | Enterprise utilities | Enterprise; requires hardware integration |
| Stem | Battery storage AI (Athena) | Commercial C&I | Hardware bundle; not a standalone software tool |
| Voltaware | Residential energy monitoring | Residential | Consumer product; no ROI/dispatch optimization |

**Key finding:** Existing tools are either too technical (Homer/PVsyst require engineering expertise), enterprise-only (EnergyHub, AutoGrid, Stem require hardware bundles and enterprise contracts), or residential (Voltaware). A self-serve web tool for commercial building managers evaluating PV+battery ROI — without requiring an engineering consultant — is not served by any identified competitor.

**Caveat:** Energy consulting is a domain requiring specific regulatory and tariff knowledge per country. Building this without local energy market expertise is a significant risk. The lab source (neura-btm-battery-dispatch) is a student project with no license — the technical foundation is weak.

evidence_level: initial-research (competitor landscape verified; lab source is ref-only student project)

## Next validation step

1. Talk to 2–3 hotel or commercial building managers: how do they currently evaluate PV+battery investments?
2. Identify whether they hire energy consultants for this, and whether consultants would use a tool
3. Check what existing software (Homer Energy, PVsyst, SolarEdge monitoring) covers vs. leaves open
