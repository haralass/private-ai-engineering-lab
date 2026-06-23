using HDSBackend.Data;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;
using Microsoft.EntityFrameworkCore;
using System.Text; // for CSV

namespace HDSBackend.Services
{
    public class StudentAcademicYearService : IStudentAcademicYearService
    {
        private readonly HDSDbContext _context;
        public StudentAcademicYearService(HDSDbContext context)
        {
            _context = context;
        }

        public async Task<IEnumerable<StudentReadDto>> GetStudentsByAcademicYearAsync(int academicYearId)
        {
            var students = await _context.StudentAcademicYears
                .AsNoTracking()
                .Where(link => link.AcademicYearId == academicYearId && !link.IsDeleted)
                .Select(link => link.Student!)
                .Where(s => s != null && !s.IsDeleted)
                .Select(s => new StudentReadDto
                {
                    StudentId = s!.StudentId,
                    FirstName = s.FirstName,
                    LastName = s.LastName,
                    BirthDate = s.BirthDate,
                    EmergencyContact = s.EmergencyContact,
                    EmergencyPhone = s.EmergencyPhone,
                    Email = s.Email,
                    UserId = s.UserId,
                    Allergies = s.Allergies
                })
                .ToListAsync();

            return students;
        }

        public async Task<IEnumerable<AcademicYearReadDto>> GetAcademicYearsByStudentAsync(int studentId)
        {
            var years = await _context.StudentAcademicYears
                .AsNoTracking()
                .Where(link => link.StudentId == studentId && !link.IsDeleted)
                .Select(link => link.AcademicYear!)
                .Where(y => y != null && !y.IsDeleted)
                .Select(y => new AcademicYearReadDto
                {
                    AcademicYearId = y!.AcademicYearId,
                    Year = y.Year,
                    StartDate = y.StartDate,
                    EndDate = y.EndDate,
                    IsActive = y.IsActive,
                    CreatedAt = y.CreatedAt,
                    UpdatedAt = y.UpdatedAt
                })
                .ToListAsync();

            return years;
        }

        public async Task<byte[]> ExportStudentsByAcademicYearCsvAsync(int academicYearId)
        {
            var students = await GetStudentsByAcademicYearAsync(academicYearId);
            var sb = new StringBuilder();
            // Removed StudentId, UserId, AcademicYearId from CSV
            sb.AppendLine("FirstName,LastName,BirthDate,Email,EmergencyContact,EmergencyPhone,Allergies");
            foreach (var s in students)
            {
                sb.AppendLine(string.Join(',',
                    Csv(s.FirstName),
                    Csv(s.LastName),
                    s.BirthDate.ToString("yyyy-MM-dd"),
                    Csv(s.Email ?? string.Empty),
                    Csv(s.EmergencyContact),
                    Csv(s.EmergencyPhone),
                    Csv(s.Allergies ?? string.Empty)));
            }
            return Encoding.UTF8.GetBytes(sb.ToString());

            static string Csv(string v) => string.IsNullOrEmpty(v) ? "" : (v.Contains(',') || v.Contains('"') || v.Contains('\n') ? $"\"{v.Replace("\"", "\"\"")}\"" : v);
        }

        public async Task<StudentYearPaymentStatusDto?> GetPaymentStatusAsync(int studentId, int academicYearId)
        {
            var link = await _context.StudentAcademicYears
                .AsNoTracking()
                .FirstOrDefaultAsync(l => l.StudentId == studentId && l.AcademicYearId == academicYearId && !l.IsDeleted);
            if (link == null) return null;
            return new StudentYearPaymentStatusDto
            {
                StudentId = link.StudentId,
                AcademicYearId = link.AcademicYearId,
                Paid1 = link.Paid1,
                Paid2 = link.Paid2,
                Paid3 = link.Paid3,
                Paid4 = link.Paid4,
                Paid5 = link.Paid5,
                Paid6 = link.Paid6,
                Paid7 = link.Paid7,
                Paid8 = link.Paid8,
                Paid9 = link.Paid9,
                Paid10 = link.Paid10
            };
        }

        public async Task<bool> UpdatePaymentStatusAsync(StudentYearPaymentStatusDto dto)
        {
            var link = await _context.StudentAcademicYears
                .FirstOrDefaultAsync(l => l.StudentId == dto.StudentId && l.AcademicYearId == dto.AcademicYearId && !l.IsDeleted);
            if (link == null) return false;

            link.Paid1 = dto.Paid1;
            link.Paid2 = dto.Paid2;
            link.Paid3 = dto.Paid3;
            link.Paid4 = dto.Paid4;
            link.Paid5 = dto.Paid5;
            link.Paid6 = dto.Paid6;
            link.Paid7 = dto.Paid7;
            link.Paid8 = dto.Paid8;
            link.Paid9 = dto.Paid9;
            link.Paid10 = dto.Paid10;
            link.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();
            return true;
        }
    }
}
