using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using HDSBackend.Services.Interfaces;
using HDSBackend.Models.Dto;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for querying student, academic year links.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class StudentAcademicYearController : ControllerBase
    {
        private readonly IStudentAcademicYearService _service;
        public StudentAcademicYearController(IStudentAcademicYearService service)
        {
            _service = service;
        }

        /// <summary>
        /// Returns all students linked to a specific academic year.
        /// </summary>
        /// <param name="academicYearId">Academic year id.</param>
        [HttpGet("students/{academicYearId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<StudentReadDto>>> GetStudentsByYear(int academicYearId)
        {
            var result = await _service.GetStudentsByAcademicYearAsync(academicYearId);
            return Ok(result);
        }

        /// <summary>
        /// Returns all academic years a given student is linked to.
        /// </summary>
        /// <param name="studentId">Student id.</param>
        [HttpGet("years/{studentId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<AcademicYearReadDto>>> GetYearsByStudent(int studentId)
        {
            var result = await _service.GetAcademicYearsByStudentAsync(studentId);
            return Ok(result);
        }

        /// <summary>
        /// Exports students for the specified academic year as CSV.
        /// </summary>
        /// <param name="academicYearId">Academic year id.</param>
        [HttpGet("export/students/{academicYearId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> ExportStudentsByYear(int academicYearId)
        {
            var bytes = await _service.ExportStudentsByAcademicYearCsvAsync(academicYearId);
            return File(bytes, "text/csv", $"students_year_{academicYearId}.csv");
        }

        /// <summary>
        /// Gets payment status (10 installments) for a student in an academic year.
        /// </summary>
        [HttpGet("payments/{studentId:int}/{academicYearId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<StudentYearPaymentStatusDto>> GetPaymentStatus(int studentId, int academicYearId)
        {
            var dto = await _service.GetPaymentStatusAsync(studentId, academicYearId);
            if (dto == null) return NotFound();
            return Ok(dto);
        }

        /// <summary>
        /// Updates payment status (10 installments) for a student in an academic year.
        /// </summary>
        [HttpPut("payments")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> UpdatePaymentStatus([FromBody] StudentYearPaymentStatusDto model)
        {
            if (!ModelState.IsValid) return ValidationProblem(ModelState);
            var ok = await _service.UpdatePaymentStatusAsync(model);
            return ok ? NoContent() : NotFound();
        }
    }
}
