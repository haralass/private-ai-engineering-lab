# Source Research Dossier: synthetic-relational-data

research_date: 2026-06-23
analyst: deep-analysis agent
import_type: reference-only (no upstream/ code — web research + SOURCE.yaml only)

---

## Repository identity

- **Display name**: one-stop-ride-hail
- **Functional name**: synthetic-relational-data
- **Creator**: tsembp (Panagiotis Tsembekis)
- **GitHub URL**: https://github.com/tsembp/one-stop-ride-hail
- **Source path**: `sources/synthetic-relational-data/`
- **License**: NOT-FOUND (no LICENSE file — reference-only, copy not permitted)
- **Import type**: reference-only
- **Creator context**: CS student (Panagiotis Tsembekis), same as SQL-Gym, WG-Course-Task-Notifier-Bot, EPL231-GroupAssignment, and Hitting-Set-Problem. The name "one-stop-ride-hail" suggests a database or application simulation project built around a ride-hailing business domain. This is likely a database course project (modeling a complex business domain as a relational schema and generating realistic data for it).
- **Pinned commit**: 14e0f42a9b32ea0b7f27543f7eeb6fe9fb92a9ff

---

## What it actually does

[Based on web research, SOURCE.yaml, and domain inference — no source code available]

one-stop-ride-hail is a synthetic data generation project for a ride-hailing domain (similar to Uber/Lyft/Bolt). It models the relational entities of a ride-hail platform (drivers, riders, trips, payments, ratings, vehicles) and generates consistent, realistic synthetic data across those entities. The term "one-stop" in the name suggests either a comprehensive all-in-one simulation or a reference to the business concept of a single-platform ride aggregator.

The SOURCE.yaml notes describe it as "Ride-hail synthetic data generation." The lab interprets it as a demonstration of relational data consistency patterns: a rider on trip 1 is the same person on trip 2, drivers have vehicles, trip timestamps respect logical ordering (pickup before dropoff, payment after trip end), ratings are bounded to valid trips.

This project is likely connected to the EPL231 group assignment (also tsembp) — database systems or data management coursework at the same university [inference]. EPL231 is a course code style typical of Cypriot universities (e.g., University of Cyprus).

---

## Architecture

[Inferred from domain knowledge and lab context]

A ride-hail synthetic data generator of this type typically consists of:

1. **Schema definition**: The relational schema covering the ride-hail domain. Expected entities: `drivers`, `riders`, `vehicles`, `trips`, `payments`, `ratings`, possibly `locations`, `promotions`.

2. **Entity generators**: Functions that produce realistic instances of each entity type using Faker (names, phone numbers, coordinates) and domain-specific logic (driver ratings between 3.5–5.0, fares proportional to distance, etc.).

3. **Relationship enforcement**: Foreign key consistency — every trip references a real driver and rider, every payment references a real trip, every rating references a real completed trip.

4. **Temporal ordering**: Trips have pickup_time before dropoff_time; payments have payment_time after trip end; driver signup_date before their first trip date.

5. **Output**: SQL INSERT statements for populating a database, or CSV/JSON for other uses.

This project may be part of a larger "one-stop" application that includes not just data generation but also a minimal application layer (Flask/FastAPI) for querying the data, similar to SQL-Gym's approach [hypothesis].

---

## Main modules and important files

[Inferred — no code available]

Expected structure for this type of project:
- `schema.sql` or `models.py` — relational schema definition
- `seeder.py` or `generate_data.py` — main data generation script
- Entity-specific generation functions (drivers, riders, trips, payments, ratings)
- Possibly a Flask/FastAPI app for demonstrating queries against the generated data
- Possibly a `queries.sql` file with analytical queries demonstrating the dataset

---

## Core technical patterns

**1. Relational consistency via ordered generation**: Generate parent entities first, then child entities referencing them. This ensures all foreign keys are valid. The pattern: `users → trips → payments → ratings` (each layer depends on the previous).

**2. Domain-specific value ranges**: Faker gives realistic names and emails, but domain constraints (fare amounts, GPS coordinates within a city bounding box, trip durations in realistic ranges) require custom logic on top of Faker.

**3. Seed-based reproducibility**: Using a fixed random seed produces the same dataset on every run, enabling reproducible test suites. This is a key feature for test data generation tools.

---

## Novel or interesting mechanisms

The "one-stop" framing might indicate that this project goes beyond data generation and implements a simple query interface or analytics layer on top of the generated data — making it a mini demonstration of a ride-hail data platform. If so, it demonstrates the full stack from synthetic data generation to analytical queries, which is more valuable as a reference than a pure data generator [hypothesis].

The ride-hail domain is particularly interesting for synthetic data because it involves geospatial data (coordinates), multi-party transactions (driver + rider + payment processor), and time-series patterns (trip frequency by hour of day, week of year). These are non-trivial constraints to enforce in a simple Faker-based generator.

---

## Data flow

[Inferred]
```
Schema definition (entities + relationships)
        ↓
Ordered entity generation (parent entities first)
        → Faker for names, emails, phones
        → Domain logic for fares, coordinates, durations
        → Temporal ordering for timestamps
        ↓
Relational consistency validation (FK integrity check)
        ↓
Output: SQL INSERT statements / CSV / JSON
        ↓
Target database (PostgreSQL / SQLite)
        ↓
Analytical queries / application layer
```

---

## Dependencies

[Inferred]
- `Faker` — names, emails, phone numbers, addresses
- `sqlalchemy` — ORM models and database connection
- `sqlite3` or `psycopg2` — database backend
- `pandas` — optional, for data manipulation and export
- `random`, `datetime` — standard library, for custom domain logic

---

## Security model

Not applicable for a local synthetic data generator. No sensitive data involved (all data is synthetic by design).

---

## Testing strategy

[Unknown — likely none for a student project]

---

## Genuinely reusable elements

[Conceptual — cannot verify without code]

1. **Relational consistency pattern**: The ordered generation approach (parents before children, FK integrity enforced) is the core reusable technique for any synthetic relational data generator.

2. **Ride-hail schema as a template**: The entities and relationships of a ride-hail domain are a good demonstration schema for a synthetic data platform — complex enough to show multi-table relationships, simple enough to understand.

3. **Domain constraint encapsulation**: The pattern of wrapping domain-specific value ranges (trip duration, fare amount, GPS bounding box) in generator functions is reusable and cleaner than inline `random.uniform` calls.

---

## What NOT to reuse

- Any hardcoded coordinates or business rules specific to a particular city or market
- No LICENSE — cannot copy code
- Student-project data volumes (typically small: hundreds of drivers, thousands of trips) are insufficient for validating a production data generator

---

## Production-readiness

Not production-ready. This is a demonstration/coursework tool. A production synthetic data platform would need:
- Schema inference from existing database schemas (not manual definition)
- Configurable volume (generate N entities)
- Multiple output formats (SQL INSERT, CSV, JSON, Parquet)
- Privacy-preserving synthetic data (preserving statistical distributions without exposing real data)
- Snapshot management (named seeds for reproducible test suites)

---

## Strengths / Weaknesses / Technical debt

**Strengths**:
- Ride-hail domain is a good synthetic data showcase: multi-entity, geospatial, temporal
- Likely demonstrates correct relational consistency approach
- tsembp has shown good engineering judgment in SQL-Gym (can inspect the vendored code); similar patterns likely apply here

**Weaknesses**:
- No LICENSE — cannot use commercially
- Unknown scope and completeness
- Likely academic scale (small data volumes)
- Single-domain (ride-hail only)

---

## Novel or differentiated elements

No technical novelty — Faker-based seeding with relational consistency is a well-known pattern. The value is domain specificity (ride-hail is a good reference schema) and the existence proof that a CS student built a working multi-table synthetic data generator for coursework. This validates that the core technical problem is tractable without specialized tools.

---

## Possible clean-room adaptations

The `product-concepts/synthetic-test-data-platform/` concept describes the generalization:

1. **Schema inference**: Read an existing database schema or migration files (SQLAlchemy migrations, Prisma schema, raw SQL DDL) and automatically detect entities, foreign keys, and constraints.

2. **Generator configuration**: For each column, infer an appropriate Faker provider or value distribution from the column name, type, and any check constraints.

3. **Relational ordering**: Topologically sort entities by their foreign key dependencies to determine generation order.

4. **Volume control**: Generate N root entities, with configurable children-per-parent ratios.

5. **Business logic constraints**: Allow users to specify custom constraints (e.g., `payment_time > trip_end_time`, `rating between 1 and 5`).

6. **Output formats**: SQL INSERT, CSV, JSON, Parquet.

7. **Snapshot management**: Name a generated dataset and reproduce it with a fixed seed.

All of this can be built clean-room. The ride-hail schema from this source serves as a reference domain for testing the tool.

---

## Business applications

Directly inspires `product-concepts/synthetic-test-data-platform/` and is adjacent to `business-research/category-a/synthetic-regulatory-document-ai.md`. The commercial application:

- **Developer teams**: Privacy-safe test data for development and staging environments (GDPR compliance driver)
- **QA teams**: Reproducible, schema-consistent test fixtures for automated test suites
- **Data teams**: Demo datasets for new customers, realistic data for training data pipelines
- **Compliance and audit**: Generate representative synthetic samples from real data for sharing with auditors without exposing PII

**Unique angle vs. Faker/Mockaroo**: Relational consistency across tables (the exact gap these tools don't fill well). A customer on order 1 is the same person on order 2. A driver's first trip is after their signup date.

---

## Competitor landscape

From web research (2026-06-23):

- **Faker** (https://faker.readthedocs.io/) — Open-source Python library. Column-level fake data generation. No relational consistency, no schema inference. The de facto default for developers who need any fake data quickly.
- **Mockaroo** (https://www.mockaroo.com/) — Browser-based GUI for defining schemas and generating mock data. Acquired by Tonic.ai (April 22, 2025). Lightweight, fast, good for single-table scenarios. Limited relational consistency across multiple tables. Free tier available; pricing page at mockaroo.com/pricing.
- **Tonic.ai** (https://www.tonic.ai/) — Enterprise synthetic data platform. Tonic Structural handles relational databases with referential integrity and statistical fidelity. Custom enterprise pricing (not public). Acquired Mockaroo (Fabricate product) in 2025. Has Tonic Datasets for AI training data.
- **MOSTLY AI** (https://mostly.ai/) — Enterprise tabular synthesizer. Privacy-guaranteed synthetic data with statistical fidelity. Used by banks and insurers. Free tier for individuals; enterprise pricing not public. Compute-based pricing model.
- **Gretel.ai** (acquired by Nvidia for $320M in March 2025; now at nvidia.com) — Was a leading synthetic data platform. Acquisition signals the strategic importance of synthetic data for AI training.
- **K2view** (https://www.k2view.com/) — Enterprise data fabric + synthetic data. Targets large enterprise data teams.

**Key insight from Mockaroo/Tonic acquisition**: Tonic acquired Mockaroo to cover the lightweight/prototyping end of the market while keeping Tonic Structural for enterprise use. This suggests a clear market segmentation: individual developers want simple tools (Mockaroo), enterprises want full referential integrity and statistical fidelity (Tonic Structural). A mid-market product with relational consistency but without enterprise pricing complexity could serve the gap.

---

## Related business ideas in this lab

- `product-concepts/synthetic-test-data-platform/README.md` — the direct product concept
- `business-research/category-a/synthetic-regulatory-document-ai.md` — compliance-specific vertical
- `business-research/BUSINESS_IDEAS_INDEX.md` — synthetic data appears in the overlap map

---

## Related sources in this lab

- `sources/database-query-training/` (tsembp/SQL-Gym) — same creator; the SQL-Gym fintech schema (users, transactions, refunds, subscriptions, generated with Faker) is a direct example of the pattern this source generalizes
- `sources/data-structure-search-engine/` (tsembp/EPL231) — likely the same course context; may use the ride-hail data as a query dataset

---

## Open questions

1. Is this project part of the EPL231 course assignment, or a separate personal project?
2. Does the project include an application layer (Flask/FastAPI) for querying the generated data, or is it purely a data generation script?
3. Does it generate geospatial data (GPS coordinates within a city bounding box) or just generic location fields?
4. Are there analytical queries included, or just schema + seeder?
5. What volume of data does it generate? (Hundreds of drivers? Thousands of trips?)

---

## Final research conclusion

one-stop-ride-hail demonstrates the core pattern for relational synthetic data generation (ordered entity creation with FK consistency) in a complex, realistic domain. While technically not novel, it is a good existence proof and reference for the `synthetic-test-data-platform` product concept. The commercial opportunity is real: Tonic's Mockaroo acquisition confirms that the market for synthetic relational data tools is active and consolidating. The differentiation opportunity is at the mid-market level: better relational consistency than Faker/Mockaroo, but more accessible and cheaper than Tonic Structural. The missing LICENSE means no code can be used from this source, but clean-room implementation of a schema-inference + ordered-generation + relational-consistency tool is entirely feasible.
