using HDSBackend.Models;
using HDSBackend.Models.Dto;

namespace HDSBackend.Services.Interfaces
{
    /// <summary>
    /// Contract for academic year-related operations.
    /// </summary>
    public interface IAcademicYearService
    {
        Task<IEnumerable<AcademicYear>> GetAllAsync();
        Task<AcademicYear?> GetByIdAsync(int id);
        Task<AcademicYear?> GetActiveAsync(); // fetch active year
        Task<AcademicYear> CreateAsync(AcademicYear academicYear);
        Task<AcademicYear?> UpdateAsync(int id, AcademicYear academicYear);
        Task<bool> SoftDeleteAsync(int id);
        Task<IEnumerable<AcademicYearSummaryDto>> GetSummariesAsync();

        /// <summary>
        /// Auto-creates an academic year for the fixed cycle: September 1 → June 30 (next year).
        /// </summary>
        Task<AcademicYear> CreateAutoAsync(bool makeActive = true);
    }
}
