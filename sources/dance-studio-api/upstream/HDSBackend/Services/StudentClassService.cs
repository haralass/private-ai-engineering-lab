using Microsoft.EntityFrameworkCore;
using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Services
{
    public class StudentClassService : IStudentClassService
    {
        private readonly HDSDbContext _context;
        public StudentClassService(HDSDbContext context)
        {
            _context = context;
        }

        public async Task<StudentClassReadDto?> EnrollAsync(StudentClassCreateDto model)
        {
            var studentExists = await _context.Students.AnyAsync(s => s.StudentId == model.StudentId && !s.IsDeleted);
            if (!studentExists) return null;

            var cls = await _context.Classes.FirstOrDefaultAsync(c => c.ClassId == model.ClassId && !c.IsDeleted);
            if (cls == null) return null;

            var existing = await _context.StudentClasses
                .FirstOrDefaultAsync(sc => sc.StudentId == model.StudentId && sc.ClassId == model.ClassId && !sc.IsDeleted);
            if (existing != null) return null;

            if (cls.CurrentEnrollment >= cls.Capacity) return null;

            // ensure StudentAcademicYear link exists for this class's academic year
            var link = await _context.StudentAcademicYears.FirstOrDefaultAsync(
                x => x.StudentId == model.StudentId && x.AcademicYearId == cls.AcademicYearId && !x.IsDeleted);
            if (link == null)
            {
                link = new StudentAcademicYear
                {
                    StudentId = model.StudentId,
                    AcademicYearId = cls.AcademicYearId,
                    CreatedAt = DateTime.UtcNow,
                    UpdatedAt = DateTime.UtcNow, // added for StudentAcademicYear stuff
                    IsDeleted = false
                };
                await _context.StudentAcademicYears.AddAsync(link);
            }

            var entity = new StudentClass
            {
                StudentId = model.StudentId,
                ClassId = model.ClassId,
                EnrollmentDate = model.EnrollmentDate,
                CreatedAt = DateTime.UtcNow,
                IsDeleted = false
            };

            cls.CurrentEnrollment += 1;
            cls.UpdatedAt = DateTime.UtcNow;

            await _context.StudentClasses.AddAsync(entity);
            await _context.SaveChangesAsync();

            return new StudentClassReadDto
            {
                StudentClassId = entity.StudentClassId,
                StudentId = entity.StudentId,
                ClassId = entity.ClassId,
                EnrollmentDate = entity.EnrollmentDate
            };
        }

        public async Task<StudentClassReadDto?> GetByIdAsync(int id)
        {
            var sc = await _context.StudentClasses.AsNoTracking().FirstOrDefaultAsync(x => x.StudentClassId == id && !x.IsDeleted);
            if (sc == null) return null;
            return new StudentClassReadDto
            {
                StudentClassId = sc.StudentClassId,
                StudentId = sc.StudentId,
                ClassId = sc.ClassId,
                EnrollmentDate = sc.EnrollmentDate
            };
        }

        public async Task<IEnumerable<StudentClassReadDto>> GetByStudentAsync(int studentId)
        {
            var list = await _context.StudentClasses.AsNoTracking()
                .Where(x => x.StudentId == studentId && !x.IsDeleted)
                .ToListAsync();
            return list.Select(sc => new StudentClassReadDto
            {
                StudentClassId = sc.StudentClassId,
                StudentId = sc.StudentId,
                ClassId = sc.ClassId,
                EnrollmentDate = sc.EnrollmentDate
            });
        }

        public async Task<IEnumerable<StudentClassReadDto>> GetByClassAsync(int classId)
        {
            var list = await _context.StudentClasses.AsNoTracking()
                .Where(x => x.ClassId == classId && !x.IsDeleted)
                .ToListAsync();
            return list.Select(sc => new StudentClassReadDto
            {
                StudentClassId = sc.StudentClassId,
                StudentId = sc.StudentId,
                ClassId = sc.ClassId,
                EnrollmentDate = sc.EnrollmentDate
            });
        }

        public async Task<bool> UnenrollAsync(int id)
        {
            var sc = await _context.StudentClasses.FirstOrDefaultAsync(x => x.StudentClassId == id && !x.IsDeleted);
            if (sc == null) return false;

            var cls = await _context.Classes.FirstAsync(c => c.ClassId == sc.ClassId);
            if (cls.CurrentEnrollment > 0)
            {
                cls.CurrentEnrollment -= 1;
                cls.UpdatedAt = DateTime.UtcNow;
            }

            sc.IsDeleted = true;
            sc.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();
            return true;
        }

        public async Task<IEnumerable<StudentClassReadDto>> GetByUserIdAsync(int userId)
        {
            var studentId = await _context.Students
                .Where(s => s.UserId == userId && !s.IsDeleted)
                .Select(s => (int?)s.StudentId)
                .FirstOrDefaultAsync();
            if (!studentId.HasValue) return Enumerable.Empty<StudentClassReadDto>();
            return await GetByStudentAsync(studentId.Value);
        }

        public async Task<StudentClassReadDto?> EnrollForUserAsync(int userId, StudentClassCreateDto model)
        {
            var studentId = await _context.Students
                .Where(s => s.UserId == userId && !s.IsDeleted)
                .Select(s => (int?)s.StudentId)
                .FirstOrDefaultAsync();
            if (!studentId.HasValue) return null;
            var create = new StudentClassCreateDto
            {
                StudentId = studentId.Value,
                ClassId = model.ClassId,
                EnrollmentDate = model.EnrollmentDate
            };
            return await EnrollAsync(create);
        }
    }
}
