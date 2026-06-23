using HDSBackend.Models;
using System.Threading.Tasks;
using System.Collections.Generic;

namespace HDSBackend.Services.Interfaces
{
    /// <summary>
    /// Contract for class-related operations.
    /// </summary>
    public interface IClassService
    {
        Task<Class?> GetClassByIdAsync(int classId);
        Task<IEnumerable<Class>> GetAllClassesAsync();
        Task<IEnumerable<Class>> GetClassesByAcademicYearAsync(int academicYearId);
        Task<Class> CreateClassAsync(Class newClass);
        Task<Class?> UpdateClassAsync(int classId, Class updatedClass);
        Task<bool> DeleteClassAsync(int classId);
        Task<byte[]> ExportClassesByAcademicYearCsvAsync(int academicYearId);
    }
}
