using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Services.Interfaces;
using Microsoft.EntityFrameworkCore;
using System.Text; // for CSV building

namespace HDSBackend.Services
{
    /// <summary>
    /// Data access service for managing Class entities.
    /// </summary>
    public class ClassService : IClassService
    {
        private readonly HDSDbContext _context;
        public ClassService(HDSDbContext context)
        {
            _context = context;
        }

        public async Task<Class?> GetClassByIdAsync(int classId)
        {
            return await _context.Classes
                .AsNoTracking()
                .FirstOrDefaultAsync(c => c.ClassId == classId && !c.IsDeleted);
        }

        public async Task<IEnumerable<Class>> GetAllClassesAsync()
        {
            return await _context.Classes
                .AsNoTracking()
                .Where(c => !c.IsDeleted)
                .ToListAsync();
        }

        public async Task<IEnumerable<Class>> GetClassesByAcademicYearAsync(int academicYearId)
        {
            return await _context.Classes
                .AsNoTracking()
                .Where(c => c.AcademicYearId == academicYearId && !c.IsDeleted)
                .ToListAsync();
        }

        public async Task<Class> CreateClassAsync(Class newClass)
        {
            newClass.CreatedAt = DateTime.UtcNow;
            newClass.IsDeleted = false;

            _context.Classes.Add(newClass);
            await _context.SaveChangesAsync();
            return newClass;
        }

        public async Task<Class?> UpdateClassAsync(int classId, Class updatedClass)
        {
            var existingClass = await _context.Classes.FindAsync(classId);
            if (existingClass == null || existingClass.IsDeleted)
            {
                return null;
            }

            // Update properties
            existingClass.ClassName = updatedClass.ClassName ?? existingClass.ClassName;
            existingClass.AcademicYearId = updatedClass.AcademicYearId;
            existingClass.Level = updatedClass.Level ?? existingClass.Level;
            existingClass.DayOfWeek = updatedClass.DayOfWeek ?? existingClass.DayOfWeek;
            existingClass.TimeSlot = updatedClass.TimeSlot ?? existingClass.TimeSlot;
            existingClass.Capacity = updatedClass.Capacity != 0 ? updatedClass.Capacity : existingClass.Capacity;
            existingClass.CurrentEnrollment = updatedClass.CurrentEnrollment;
            existingClass.UpdatedAt = DateTime.UtcNow;

            await _context.SaveChangesAsync();
            return existingClass;
        }

        public async Task<bool> DeleteClassAsync(int classId)
        {
            var existing = await _context.Classes.FindAsync(classId);
            if (existing == null || existing.IsDeleted)
            {
                return false;
            }

            // Soft-delete related enrollments and reset enrollment count
            var enrollments = await _context.StudentClasses
                .Where(sc => sc.ClassId == classId && !sc.IsDeleted)
                .ToListAsync();
            foreach (var sc in enrollments)
            {
                sc.IsDeleted = true;
                sc.UpdatedAt = DateTime.UtcNow;
            }

            existing.CurrentEnrollment = 0;
            existing.IsDeleted = true;
            existing.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();
            return true;
        }

        public async Task<byte[]> ExportClassesByAcademicYearCsvAsync(int academicYearId)
        {
            var classes = await _context.Classes
                .AsNoTracking()
                .Where(c => c.AcademicYearId == academicYearId && !c.IsDeleted)
                .ToListAsync();

            var yearName = await _context.AcademicYears
                .AsNoTracking()
                .Where(y => y.AcademicYearId == academicYearId)
                .Select(y => y.Year)
                .FirstOrDefaultAsync() ?? string.Empty;

            var sb = new StringBuilder();
            // Header without ids, include Year name
            sb.AppendLine("Year,ClassName,Level,DayOfWeek,TimeSlot,Capacity,CurrentEnrollment");
            foreach (var c in classes)
            {
                sb.AppendLine(string.Join(',',
                    Csv(yearName),
                    Csv(c.ClassName),
                    Csv(c.Level),
                    Csv(c.DayOfWeek),
                    Csv(c.TimeSlot),
                    c.Capacity,
                    c.CurrentEnrollment));
            }
            return Encoding.UTF8.GetBytes(sb.ToString());

            static string Csv(string v) => string.IsNullOrEmpty(v) ? "" : (v.Contains(',') || v.Contains('"') || v.Contains('\n') ? $"\"{v.Replace("\"", "\"\"")}\"" : v);
        }
    }
}
