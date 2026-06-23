using HDSBackend.Models;
using HDSBackend.Models.Dto;

namespace HDSBackend.Services.Interfaces
{
    /// <summary>
    /// Contract for student-related operations.
    /// </summary>
    public interface IStudentService
    {
        Task<Student?> GetStudentByIdAsync(int studentId); //get student by studentId
        Task<Student?> GetStudentByUserIdAsync(int userId); // get student profile linked to an AppUser
        Task<IEnumerable<Student>> GetAllStudentsAsync(); //get all students
        Task<Student> CreateStudentAsync(Student student); //create a new student
        Task<Student?> UpdateStudentAsync(int studentId, Student updatedStudent); //update student by studentId
        Task<bool> DeleteStudentAsync(int studentId); //delete student by studentId
        Task<StudentReadDto?> UpdateMyProfileAsync(int userId, StudentUpdateDto dto); // Update profile for logged-in user

        // Slim controller helpers
        Task<IEnumerable<StudentReadDto>> GetAllReadDtosAsync();
        Task<StudentReadDto?> GetReadDtoByIdAsync(int studentId);
        Task<StudentReadDto> CreateFromDtoAsync(StudentCreateDto dto);
        Task<StudentReadDto?> UpdateFromDtoAsync(int studentId, StudentUpdateDto dto);
        Task<IEnumerable<StudentReadDto>> SearchAsync(string query, int limit = 20, int? academicYearId = null); // Search
        
    }
}
