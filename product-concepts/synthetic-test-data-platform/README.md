# Synthetic Test Data Platform

Status: `research`
Inspired by: `sources/synthetic-relational-data/` (tsembp/one-stop-ride-hail)

## Summary

Generates consistent, privacy-safe, schema-aware relational datasets for testing, development, and demo purposes. Entities are internally consistent (a customer on order 1 is the same person on order 2).

## Problem

Real data cannot be used in dev/test for privacy reasons. Naive random data breaks because:
- Customer IDs on orders don't match any customer record
- Dates are inconsistent (order before customer creation)
- Business logic invariants are violated (total != sum of line items)

## Core capabilities

1. Schema inference from existing database or migration files
2. Consistent entity generation (seeded, reproducible)
3. Relationship-aware generation (foreign keys always valid)
4. Business logic constraints (configurable)
5. Volume control (generate N customers, M orders per customer)
6. Format output: SQL INSERT, CSV, JSON, Parquet
7. Snapshot management (named seeds for reproducible test suites)

## Inspired by

The tsembp/one-stop-ride-hail project includes synthetic data generation for a ride-hail scenario. We generalize the pattern to any schema.

See `reference-implementations/synthetic-relational-data-generation/` for the reference build.
