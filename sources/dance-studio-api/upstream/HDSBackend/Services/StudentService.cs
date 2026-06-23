using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace HDSBackend.Services
{
    /// <summary>
    /// Data access service for managing Student entities.
    /// </summary>
    public class StudentService : IStudentService
    {
        private readonly HDSDbContext _context;

        public StudentService(HDSDbContext context)
        {
            _context = context;
        }

        public async Task<Student?> GetStudentByIdAsync(int studentId)
        {
            return await _context.Students
                .AsNoTracking()
                .Include(s => s.User)
                .FirstOrDefaultAsync(s => s.StudentId == studentId && !s.IsDeleted);
        }

        public async Task<Student?> GetStudentByUserIdAsync(int userId)
        {
            return await _context.Students
                .AsNoTracking()
                .Include(s => s.User)
                .FirstOrDefaultAsync(s => s.UserId == userId && !s.IsDeleted);
        }

        public async Task<IEnumerable<Student>> GetAllStudentsAsync()
        {
            return await _context.Students
                .AsNoTracking()
                .Include(s => s.User)
                .Where(s => !s.IsDeleted)
                .ToListAsync();
        }

        public async Task<Student> CreateStudentAsync(Student student)
        {
            student.CreatedAt = DateTime.UtcNow;
            student.IsDeleted = false;

            _context.Students.Add(student);
            await _context.SaveChangesAsync();
            return student;
        }

        public async Task<Student?> UpdateStudentAsync(int studentId, Student updatedStudent)
        {
            var existingStudent = await _context.Students.FindAsync(studentId);
            if (existingStudent == null || existingStudent.IsDeleted)
            {
                return null;
            }

            existingStudent.FirstName = updatedStudent.FirstName ?? existingStudent.FirstName;
            existingStudent.LastName = updatedStudent.LastName ?? existingStudent.LastName;
            existingStudent.BirthDate = updatedStudent.BirthDate != default ? updatedStudent.BirthDate : existingStudent.BirthDate;
            existingStudent.EmergencyContact = updatedStudent.EmergencyContact ?? existingStudent.EmergencyContact;
            existingStudent.EmergencyPhone = updatedStudent.EmergencyPhone ?? existingStudent.EmergencyPhone;
            existingStudent.PersonalPhone = updatedStudent.PersonalPhone ?? existingStudent.PersonalPhone;
            existingStudent.Allergies = updatedStudent.Allergies ?? existingStudent.Allergies;
            existingStudent.Email = updatedStudent.Email ?? existingStudent.Email;
            existingStudent.UserId = updatedStudent.UserId;
            existingStudent.UpdatedAt = DateTime.UtcNow;

            await _context.SaveChangesAsync();
            return existingStudent;
        }

        public async Task<bool> DeleteStudentAsync(int studentId)
        {
            var existing = await _context.Students.FindAsync(studentId);
            if (existing == null || existing.IsDeleted)
            {
                return false;
            }

            var enrollments = await _context.StudentClasses
                .Where(sc => sc.StudentId == studentId && !sc.IsDeleted)
                .ToListAsync();
            if (enrollments.Count > 0)
            {
                var classIds = enrollments.Select(e => e.ClassId).Distinct().ToList();
                var classes = await _context.Classes.Where(c => classIds.Contains(c.ClassId)).ToListAsync();
                var classMap = classes.ToDictionary(c => c.ClassId, c => c);

                foreach (var sc in enrollments)
                {
                    if (classMap.TryGetValue(sc.ClassId, out var cls))
                    {
                        if (cls.CurrentEnrollment > 0)
                        {
                            cls.CurrentEnrollment -= 1;
                            cls.UpdatedAt = DateTime.UtcNow;
                        }
                    }
                    sc.IsDeleted = true;
                    sc.UpdatedAt = DateTime.UtcNow;
                }
            }

            var yearLinks = await _context.StudentAcademicYears
                .Where(l => l.StudentId == studentId && !l.IsDeleted)
                .ToListAsync();
            foreach (var link in yearLinks)
            {
                link.IsDeleted = true;
                link.UpdatedAt = DateTime.UtcNow;
            }

            existing.IsDeleted = true;
            existing.UpdatedAt = DateTime.UtcNow;

            await _context.SaveChangesAsync();
            return true;
        }

        public async Task<IEnumerable<StudentReadDto>> GetAllReadDtosAsync()
        {
            var students = await GetAllStudentsAsync();
            return students.Select(s => new StudentReadDto
            {
                StudentId = s.StudentId,
                FirstName = s.FirstName,
                LastName = s.LastName,
                BirthDate = s.BirthDate,
                EmergencyContact = s.EmergencyContact,
                EmergencyPhone = s.EmergencyPhone,
                PersonalPhone = s.PersonalPhone,
                Email = s.Email,
                UserId = s.UserId,
                Allergies = s.Allergies
            });
        }

        public async Task<StudentReadDto?> GetReadDtoByIdAsync(int studentId)
        {
            var s = await GetStudentByIdAsync(studentId);
            if (s == null) return null;
            return new StudentReadDto
            {
                StudentId = s.StudentId,
                FirstName = s.FirstName,
                LastName = s.LastName,
                BirthDate = s.BirthDate,
                EmergencyContact = s.EmergencyContact,
                EmergencyPhone = s.EmergencyPhone,
                PersonalPhone = s.PersonalPhone,
                Email = s.Email,
                UserId = s.UserId,
                Allergies = s.Allergies
            };
        }

        public async Task<StudentReadDto> CreateFromDtoAsync(StudentCreateDto dto)
        {
            var entity = new Student
            {
                FirstName = dto.FirstName,
                LastName = dto.LastName,
                BirthDate = dto.BirthDate,
                EmergencyContact = dto.EmergencyContact,
                EmergencyPhone = dto.EmergencyPhone,
                PersonalPhone = dto.PersonalPhone,
                Email = dto.Email!,
                UserId = dto.UserId
            };
            var created = await CreateStudentAsync(entity);
            return new StudentReadDto
            {
                StudentId = created.StudentId,
                FirstName = created.FirstName,
                LastName = created.LastName,
                BirthDate = created.BirthDate,
                EmergencyContact = created.EmergencyContact,
                EmergencyPhone = created.EmergencyPhone,
                PersonalPhone = created.PersonalPhone,
                Email = created.Email,
                UserId = created.UserId,
                Allergies = created.Allergies
            };
        }

        public async Task<StudentReadDto?> UpdateFromDtoAsync(int studentId, StudentUpdateDto dto)
        {
            var existing = await _context.Students.FirstOrDefaultAsync(s => s.StudentId == studentId && !s.IsDeleted);
            if (existing == null) return null;

            if (!string.IsNullOrWhiteSpace(dto.FirstName)) existing.FirstName = dto.FirstName;
            if (!string.IsNullOrWhiteSpace(dto.LastName)) existing.LastName = dto.LastName;
            if (dto.BirthDate.HasValue) existing.BirthDate = dto.BirthDate.Value;
            if (!string.IsNullOrWhiteSpace(dto.EmergencyContact)) existing.EmergencyContact = dto.EmergencyContact;
            if (!string.IsNullOrWhiteSpace(dto.EmergencyPhone)) existing.EmergencyPhone = dto.EmergencyPhone;
            if (!string.IsNullOrWhiteSpace(dto.PersonalPhone)) existing.PersonalPhone = dto.PersonalPhone;
            if (!string.IsNullOrWhiteSpace(dto.Email)) existing.Email = dto.Email;
            if (!string.IsNullOrWhiteSpace(dto.Allergies)) existing.Allergies = dto.Allergies;

            existing.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();

            return new StudentReadDto
            {
                StudentId = existing.StudentId,
                FirstName = existing.FirstName,
                LastName = existing.LastName,
                BirthDate = existing.BirthDate,
                EmergencyContact = existing.EmergencyContact,
                EmergencyPhone = existing.EmergencyPhone,
                PersonalPhone = existing.PersonalPhone,
                Email = existing.Email,
                UserId = existing.UserId,
                Allergies = existing.Allergies
            };
        }

        public async Task<IEnumerable<StudentReadDto>> SearchAsync(string query, int limit = 20, int? academicYearId = null)
        {
            var q = _context.Students.AsNoTracking().Where(s => !s.IsDeleted);
            if (academicYearId.HasValue)
            {
                q = q.Where(s => _context.StudentAcademicYears
                    .Any(link => link.StudentId == s.StudentId && link.AcademicYearId == academicYearId.Value && !link.IsDeleted));
            }
            if (string.IsNullOrWhiteSpace(query))
            {
                var all = await q.OrderBy(s => s.FirstName).ThenBy(s => s.LastName).ToListAsync();
                return all.Select(ToReadDto);
            }
            query = query.Trim();
            var lower = query.ToLower();
            var students = await q.Where(s => (
                    s.FirstName.ToLower().StartsWith(lower) ||
                    s.LastName.ToLower().StartsWith(lower) ||
                    s.Email.ToLower().StartsWith(lower) ||
                    (s.FirstName + " " + s.LastName).ToLower().Contains(lower)))
                .OrderBy(s => s.FirstName)
                .ThenBy(s => s.LastName)
                .Take(limit <= 0 ? 20 : limit)
                .ToListAsync();
            return students.Select(ToReadDto);
        }

        public async Task<StudentReadDto?> UpdateMyProfileAsync(int userId, StudentUpdateDto dto)
        {
            var existing = await _context.Students.FirstOrDefaultAsync(s => s.UserId == userId && !s.IsDeleted);
            if (existing == null) return null;

            if (!string.IsNullOrWhiteSpace(dto.FirstName)) existing.FirstName = dto.FirstName;
            if (!string.IsNullOrWhiteSpace(dto.LastName)) existing.LastName = dto.LastName;
            if (dto.BirthDate.HasValue) existing.BirthDate = dto.BirthDate.Value;
            if (!string.IsNullOrWhiteSpace(dto.EmergencyContact)) existing.EmergencyContact = dto.EmergencyContact;
            if (!string.IsNullOrWhiteSpace(dto.EmergencyPhone)) existing.EmergencyPhone = dto.EmergencyPhone;
            if (!string.IsNullOrWhiteSpace(dto.PersonalPhone)) existing.PersonalPhone = dto.PersonalPhone;
            if (!string.IsNullOrWhiteSpace(dto.Email)) existing.Email = dto.Email; // normally might enforce from AppUser
            if (!string.IsNullOrWhiteSpace(dto.Allergies)) existing.Allergies = dto.Allergies;

            existing.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();

            return new StudentReadDto
            {
                StudentId = existing.StudentId,
                FirstName = existing.FirstName,
                LastName = existing.LastName,
                BirthDate = existing.BirthDate,
                EmergencyContact = existing.EmergencyContact,
                EmergencyPhone = existing.EmergencyPhone,
                PersonalPhone = existing.PersonalPhone,
                Email = existing.Email,
                UserId = existing.UserId,
                Allergies = existing.Allergies
            };
        }

        private static StudentReadDto ToReadDto(Student s) => new StudentReadDto
        {
            StudentId = s.StudentId,
            FirstName = s.FirstName,
            LastName = s.LastName,
            BirthDate = s.BirthDate,
            EmergencyContact = s.EmergencyContact,
            EmergencyPhone = s.EmergencyPhone,
            PersonalPhone = s.PersonalPhone,
            Email = s.Email,
            UserId = s.UserId,
            Allergies = s.Allergies
        };
    }
}
