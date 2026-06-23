using HDSBackend.Models.Dto;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace HDSBackend.Services.Interfaces
{
    public interface IStudentAcademicYearService
    {
        Task<IEnumerable<StudentReadDto>> GetStudentsByAcademicYearAsync(int academicYearId);
        Task<IEnumerable<AcademicYearReadDto>> GetAcademicYearsByStudentAsync(int studentId);
        Task<byte[]> ExportStudentsByAcademicYearCsvAsync(int academicYearId);
        Task<StudentYearPaymentStatusDto?> GetPaymentStatusAsync(int studentId, int academicYearId);
        Task<bool> UpdatePaymentStatusAsync(StudentYearPaymentStatusDto dto);
    }
}
