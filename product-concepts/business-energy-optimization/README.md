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

## Next validation step

1. Talk to 2–3 hotel or commercial building managers: how do they currently evaluate PV+battery investments?
2. Identify whether they hire energy consultants for this, and whether consultants would use a tool
3. Check what existing software (Homer Energy, PVsyst, SolarEdge monitoring) covers vs. leaves open
