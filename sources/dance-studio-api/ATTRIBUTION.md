# Attribution — dance-studio-api

## License status

No LICENSE file was found in this repository at the pinned commit.
License status: **unknown / pending review**.

**Import mode:** `local-research-only` — code was studied but not committed to this lab.
No code from this source may be copied into any component, reference-implementation,
or redistributed artifact until a license is confirmed.

## Source

- **Functional name:** dance-studio-api
- **Source label:** student-collab
- **Upstream repository:** CS-UCY-EPL343/epl343.winter25.team6
- **Pinned commit SHA:** `d06f49fd9ec6a250d1ab816ceae914aa8487a0bf`
- **Retrieved:** 2026-06-23
- **Retrieval method:** read-only clone; no star, fork, watch, or follow
- **upstream/ status:** Removed 2026-06-24 (never committed to main branch)

## Security review

Secret scan: CLEAN. JWT configuration key is absent from committed config files
(correct practice). No real credentials found.

## Patterns studied

ASP.NET Core 8 REST API for dance studio management:
- Service layer + DTOs pattern
- Soft-delete on entities
- JWT + Identity auth with auto-link user/student by email on login
- 10-installment payment tracking per academic year
- CSV export via controller
- EF Core with SQL Server migrations

## Lab use restrictions

- No code from this source may be copied until a license is confirmed.
- Study of patterns is permitted; clean-room reimplementation is allowed.
- `copy_allowed: false` in SOURCE.yaml and license-matrix.yaml.
