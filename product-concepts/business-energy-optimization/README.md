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
