using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using HDSBackend.Models;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for managing academic years.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class AcademicYearController : ControllerBase
    {
        private readonly IAcademicYearService _service;

        public AcademicYearController(IAcademicYearService service) => _service = service;

        /// <summary>
        /// Retrieves all academic years.
        /// </summary>
        /// <returns>A list of academic years.</returns>
        [HttpGet]
        [AllowAnonymous]
        public async Task<ActionResult<IEnumerable<AcademicYearReadDto>>> GetAll()
        {
            var items = await _service.GetAllAsync();
            var dto = items.Select(y => new AcademicYearReadDto
            {
                AcademicYearId = y.AcademicYearId,
                Year = y.Year,
                StartDate = y.StartDate,
                EndDate = y.EndDate,
                IsActive = y.IsActive,
                CreatedAt = y.CreatedAt,
                UpdatedAt = y.UpdatedAt
            });
            return Ok(dto);
        }

        /// <summary>
        /// Retrieves academic year summaries (aggregated counts).
        /// </summary>
        /// <remarks>
        /// Requires admin role.
        /// </remarks>
        /// <returns>A list of academic year summaries.</returns>
        [HttpGet("summaries")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<AcademicYearSummaryDto>>> GetSummaries()
        {
            var summaries = await _service.GetSummariesAsync();
            return Ok(summaries);
        }

        /// <summary>
        /// Retrieves an academic year by its ID.
        /// </summary>
        /// <param name="id">The ID of the academic year.</param>
        /// <returns>The academic year with the specified ID.</returns>
        [HttpGet("{id:int}")]
        [AllowAnonymous]
        public async Task<ActionResult<AcademicYearReadDto>> GetById(int id)
        {
            var item = await _service.GetByIdAsync(id);
            if (item == null) return NotFound();
            var dto = new AcademicYearReadDto
            {
                AcademicYearId = item.AcademicYearId,
                Year = item.Year,
                StartDate = item.StartDate,
                EndDate = item.EndDate,
                IsActive = item.IsActive,
                CreatedAt = item.CreatedAt,
                UpdatedAt = item.UpdatedAt
            };
            return Ok(dto);
        }

        /// <summary>
        /// Creates a new academic year. DEPRECATED: Use the auto-create endpoint instead.
        /// </summary>
        /// <returns>The created academic year.</returns>
        [HttpPost]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<AcademicYearReadDto>> Create([FromBody] AcademicYearCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var entity = new AcademicYear
            {
                Year = model.Year,
                StartDate = model.StartDate,
                EndDate = model.EndDate,
                IsActive = model.IsActive
            };
            var created = await _service.CreateAsync(entity);
            var dto = new AcademicYearReadDto
            {
                AcademicYearId = created.AcademicYearId,
                Year = created.Year,
                StartDate = created.StartDate,
                EndDate = created.EndDate,
                IsActive = created.IsActive,
                CreatedAt = created.CreatedAt,
                UpdatedAt = created.UpdatedAt
            };
            return CreatedAtAction(nameof(GetById), new { id = dto.AcademicYearId }, dto);
        }

        /// <summary>
        /// Updates an existing academic year.
        /// </summary>
        /// <param name="id">The ID of the academic year to update.</param>
        /// <param name="academicYear">The updated academic year data.</param>
        /// <returns>The updated academic year.</returns>
        [HttpPut("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<AcademicYearReadDto>> Update(int id, [FromBody] AcademicYearUpdateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var existing = await _service.GetByIdAsync(id);
            if (existing == null) return NotFound();

            if (!string.IsNullOrWhiteSpace(model.Year)) existing.Year = model.Year;
            if (model.StartDate.HasValue) existing.StartDate = model.StartDate.Value;
            if (model.EndDate.HasValue) existing.EndDate = model.EndDate.Value;
            if (model.IsActive.HasValue) existing.IsActive = model.IsActive.Value;

            var updated = await _service.UpdateAsync(id, existing);
            var dto = new AcademicYearReadDto
            {
                AcademicYearId = updated!.AcademicYearId,
                Year = updated.Year,
                StartDate = updated.StartDate,
                EndDate = updated.EndDate,
                IsActive = updated.IsActive,
                CreatedAt = updated.CreatedAt,
                UpdatedAt = updated.UpdatedAt
            };
            return Ok(dto);
        }

        /// <summary>
        /// Deletes an academic year.
        /// </summary>
        /// <param name="id">The ID of the academic year to delete.</param>
        /// <returns>No content if the deletion was successful.</returns>
        [HttpDelete("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Delete(int id)
        {
            var removed = await _service.SoftDeleteAsync(id);
            if (!removed) return NotFound();
            return NoContent();
        }

        /// <summary>
        /// Auto-creates an academic year based on current date. Defaults to Sep 1 start.
        /// </summary>
        [HttpPost("auto")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<AcademicYearReadDto>> CreateAuto([FromQuery] bool makeActive = true)
        {
            var created = await _service.CreateAutoAsync(makeActive);
            var dto = new AcademicYearReadDto
            {
                AcademicYearId = created.AcademicYearId,
                Year = created.Year,
                StartDate = created.StartDate,
                EndDate = created.EndDate,
                IsActive = created.IsActive,
                CreatedAt = created.CreatedAt,
                UpdatedAt = created.UpdatedAt
            };
            return CreatedAtAction(nameof(GetById), new { id = dto.AcademicYearId }, dto);
        }

        /// <summary>
        /// Preview the computed year name and dates for an automated academic year (no DB write).
        /// just testing probably wont use it. maybe show it to the admin before creeation
        /// </summary>
        [HttpGet("auto/preview")]
        [Authorize(Roles = "Admin")]
        public ActionResult<object> PreviewAuto()
        {
            var now = DateTime.UtcNow.Date;
            int startYear = (now.Month > 9 || (now.Month == 9 && now.Day >= 1)) ? now.Year : now.Year - 1;
            var start = new DateTime(startYear, 9, 1, 0, 0, 0, DateTimeKind.Utc);
            var end = new DateTime(startYear + 1, 6, 30, 23, 59, 59, DateTimeKind.Utc);
            var yearName = $"{start.Year}-{startYear + 1}";
            return Ok(new { year = yearName, startDate = start, endDate = end });
        }
    }
}
