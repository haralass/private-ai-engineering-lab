# dance-studio-api

Source: [CS-UCY-EPL343/epl343.winter25.team6](https://github.com/CS-UCY-EPL343/epl343.winter25.team6)
Pinned commit: `d06f49fd9ec6a250d1ab816ceae914aa8487a0bf`
License: unknown
Import mode: vendored-snapshot
Status: candidate (pending review)

## What this is

ASP.NET Core 8 REST API for a dance studio management platform (Harris Dance Studio).
Collaborative university project (EPL343). Manages students, classes, academic years,
enrollments, payments, media, and news.

## What was imported

Full repository:
- `HDSBackend/` — complete .NET 8 Web API project
  - Controllers: Auth, Student, Class, AcademicYear, AppUser, StudentClass, StudentAcademicYear, News, Media
  - Services: full service layer with interfaces
  - Models: EF Core entities with soft-delete and audit fields
  - Migrations: complete EF migration history

## Key reference patterns

- **Auto-link user to student by email** on register AND login — handles pre-loaded student records
- **JWT + ASP.NET Identity** with int PKs (not default string)
- **Service layer + DTO pattern** cleanly separated from controllers
- **Soft-delete everywhere** — `IsDeleted` flag, never hard deletes
- **Single active academic year** enforced via bulk `ExecuteUpdateAsync()` in transaction
- **10-installment payment tracking** via boolean flags on StudentAcademicYear
- **CSV export** with proper RFC-compliant quoting
- **File upload** with GUID suffix + alphanumeric-only base name (path injection prevention)
- **Startup seeding** — admin user and role created automatically on first run

## upstream/

Clean snapshot at the pinned commit. Do not modify.

## adapted/

Extracted patterns and integrations go here.
