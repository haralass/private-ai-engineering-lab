using HDSBackend.Models.Dto;

namespace HDSBackend.Services.Interfaces
{
    public interface IStudentClassService
    {
        Task<StudentClassReadDto?> EnrollAsync(StudentClassCreateDto model);
        Task<StudentClassReadDto?> GetByIdAsync(int id);
        Task<IEnumerable<StudentClassReadDto>> GetByStudentAsync(int studentId);
        Task<IEnumerable<StudentClassReadDto>> GetByClassAsync(int classId);
        Task<bool> UnenrollAsync(int id);
        Task<IEnumerable<StudentClassReadDto>> GetByUserIdAsync(int userId);
        Task<StudentClassReadDto?> EnrollForUserAsync(int userId, StudentClassCreateDto model);
    }
}
