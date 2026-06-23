using Microsoft.EntityFrameworkCore;
using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Services.Interfaces;
using HDSBackend.Models.Dto;

namespace HDSBackend.Services
{
    public class AcademicYearService : IAcademicYearService
    {
        private readonly HDSDbContext _context;
        public AcademicYearService(HDSDbContext context) => _context = context;

        public async Task<IEnumerable<AcademicYear>> GetAllAsync()
        {
            return await _context.AcademicYears
                .AsNoTracking()
                .Where(y => !y.IsDeleted)
                .OrderByDescending(y => y.StartDate)
                .ToListAsync();
        }

        public async Task<AcademicYear?> GetByIdAsync(int id)
        {
            return await _context.AcademicYears
                .AsNoTracking()
                .FirstOrDefaultAsync(y => y.AcademicYearId == id && !y.IsDeleted);
        }

        public async Task<AcademicYear?> GetActiveAsync()
        {
            return await _context.AcademicYears
                .AsNoTracking()
                .FirstOrDefaultAsync(y => y.IsActive && !y.IsDeleted);
        }

        public async Task<AcademicYear> CreateAsync(AcademicYear academicYear)
        {
            if (academicYear == null) throw new ArgumentNullException(nameof(academicYear));

            using var tx = await _context.Database.BeginTransactionAsync();

            if (academicYear.IsActive)
            {
                await _context.AcademicYears
                    .Where(y => y.IsActive && !y.IsDeleted)
                    .ExecuteUpdateAsync(setters => setters.SetProperty(y => y.IsActive, false));
            }

            academicYear.CreatedAt = DateTime.UtcNow;
            academicYear.IsDeleted = false;

            await _context.AcademicYears.AddAsync(academicYear);
            await _context.SaveChangesAsync();
            await tx.CommitAsync();
            return academicYear;
        }

        public async Task<AcademicYear?> UpdateAsync(int id, AcademicYear academicYear)
        {
            if (academicYear == null) throw new ArgumentNullException(nameof(academicYear));

            using var tx = await _context.Database.BeginTransactionAsync();

            var existing = await _context.AcademicYears.FirstOrDefaultAsync(y => y.AcademicYearId == id && !y.IsDeleted);
            if (existing == null) return null;

            existing.Year = !string.IsNullOrWhiteSpace(academicYear.Year) ? academicYear.Year : existing.Year;
            existing.StartDate = academicYear.StartDate != default ? academicYear.StartDate : existing.StartDate;
            existing.EndDate = academicYear.EndDate != default ? academicYear.EndDate : existing.EndDate;

            var setActive = academicYear.IsActive;
            if (setActive && !existing.IsActive)
            {
                await _context.AcademicYears
                    .Where(y => y.IsActive && y.AcademicYearId != existing.AcademicYearId && !y.IsDeleted)
                    .ExecuteUpdateAsync(setters => setters.SetProperty(y => y.IsActive, false));
                existing.IsActive = true;
            }
            else if (!setActive && existing.IsActive)
            {
                existing.IsActive = false;
            }

            existing.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();
            await tx.CommitAsync();
            return existing;
        }

        public async Task<bool> SoftDeleteAsync(int id)
        {
            var existing = await _context.AcademicYears.FirstOrDefaultAsync(y => y.AcademicYearId == id && !y.IsDeleted);
            if (existing == null) return false;

            existing.IsDeleted = true;
            existing.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();
            return true;
        }

        public async Task<IEnumerable<AcademicYearSummaryDto>> GetSummariesAsync()
        {
            var years = await _context.AcademicYears
                .AsNoTracking()
                .Where(y => !y.IsDeleted)
                .Select(y => new { y.AcademicYearId, y.Year })
                .ToListAsync();

            var totalClasses = await _context.Classes
                .AsNoTracking()
                .Where(c => !c.IsDeleted)
                .GroupBy(c => c.AcademicYearId)
                .Select(g => new { AcademicYearId = g.Key, Count = g.Count() })
                .ToListAsync();

            var totalStudents = await _context.StudentAcademicYears
                .AsNoTracking()
                .Where(sy => !sy.IsDeleted)
                .GroupBy(sy => sy.AcademicYearId)
                .Select(g => new { AcademicYearId = g.Key, Count = g.Select(x => x.StudentId).Distinct().Count() })
                .ToListAsync();

            var classesMap = totalClasses.ToDictionary(x => x.AcademicYearId, x => x.Count);
            var studentsMap = totalStudents.ToDictionary(x => x.AcademicYearId, x => x.Count);

            return years
                .Select(y => new AcademicYearSummaryDto
                {
                    AcademicYearId = y.AcademicYearId,
                    Year = y.Year,
                    TotalClasses = classesMap.TryGetValue(y.AcademicYearId, out var c) ? c : 0,
                    TotalStudents = studentsMap.TryGetValue(y.AcademicYearId, out var s) ? s : 0
                })
                .OrderByDescending(x => x.AcademicYearId)
                .ToList();
        }

        public async Task<AcademicYear> CreateAutoAsync(bool makeActive = true)
        {
            var now = DateTime.UtcNow.Date;

            // Academic year starts Sep 1 and ends Jun 30 of the next calendar year.
            // If we are on/after Sep 1, the start year is current year; otherwise previous year.
            int startYear = (now.Month > 9 || (now.Month == 9 && now.Day >= 1)) ? now.Year : now.Year - 1;
            var start = new DateTime(startYear, 9, 1, 0, 0, 0, DateTimeKind.Utc);
            var end = new DateTime(startYear + 1, 6, 30, 23, 59, 59, DateTimeKind.Utc);

            var yearName = $"{start.Year}-{startYear + 1}"; // e.g., 2024-2025

            var entity = new AcademicYear
            {
                Year = yearName,
                StartDate = start,
                EndDate = end,
                IsActive = makeActive
            };
            return await CreateAsync(entity);
        }
    }
}
