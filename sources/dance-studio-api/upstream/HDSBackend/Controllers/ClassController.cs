using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using HDSBackend.Models;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for managing classes (courses) offered by the school.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class ClassController : ControllerBase
    {
        private readonly IClassService _service;
        private readonly IAcademicYearService _yearService;

        public ClassController(IClassService service, IAcademicYearService yearService)
        {
            _service = service;
            _yearService = yearService;
        }

        /// <summary>
        /// Returns all classes.
        /// </summary>
        [HttpGet]
        [AllowAnonymous]
        public async Task<ActionResult<IEnumerable<ClassReadDto>>> GetAll()
        {
            var classes = await _service.GetAllClassesAsync();
            var dto = classes.Select(c => new ClassReadDto
            {
                ClassId = c.ClassId,
                ClassName = c.ClassName,
                Level = c.Level,
                DayOfWeek = c.DayOfWeek,
                TimeSlot = c.TimeSlot,
                Capacity = c.Capacity,
                CurrentEnrollment = c.CurrentEnrollment,
                AcademicYearId = c.AcademicYearId
            });
            return Ok(dto);
        }

        /// <summary>
        /// Returns a class by id.
        /// </summary>
        /// <param name="id">Class id.</param>
        [HttpGet("{id:int}")]
        [AllowAnonymous]
        public async Task<ActionResult<ClassReadDto>> Get(int id)
        {
            var c = await _service.GetClassByIdAsync(id);
            if (c is null) return NotFound();
            var dto = new ClassReadDto
            {
                ClassId = c.ClassId,
                ClassName = c.ClassName,
                Level = c.Level,
                DayOfWeek = c.DayOfWeek,
                TimeSlot = c.TimeSlot,
                Capacity = c.Capacity,
                CurrentEnrollment = c.CurrentEnrollment,
                AcademicYearId = c.AcademicYearId
            };
            return Ok(dto);
        }

        /// <summary>
        /// Returns classes filtered by academic year id.
        /// </summary>
        /// <param name="academicYearId">Academic year id.</param>
        [HttpGet("by-year/{academicYearId:int}")]
        [AllowAnonymous]
        public async Task<ActionResult<IEnumerable<ClassReadDto>>> GetByAcademicYear(int academicYearId)
        {
            var classes = await _service.GetClassesByAcademicYearAsync(academicYearId);
            var dto = classes.Select(c => new ClassReadDto
            {
                ClassId = c.ClassId,
                ClassName = c.ClassName,
                Level = c.Level,
                DayOfWeek = c.DayOfWeek,
                TimeSlot = c.TimeSlot,
                Capacity = c.Capacity,
                CurrentEnrollment = c.CurrentEnrollment,
                AcademicYearId = c.AcademicYearId
            });
            return Ok(dto);
        }

        /// <summary>
        /// Creates a new class.
        /// </summary>
        /// <param name="model">Class payload.</param>
        [HttpPost]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<ClassReadDto>> Create([FromBody] ClassCreateDto model)
        {
            if (!ModelState.IsValid) return ValidationProblem(ModelState);

            var active = await _yearService.GetActiveAsync();
            if (active == null) return BadRequest("No active academic year; create one first.");

            var entity = new Class
            {
                ClassName = model.ClassName,
                Level = model.Level,
                DayOfWeek = model.DayOfWeek,
                TimeSlot = model.TimeSlot,
                Capacity = model.Capacity,
                AcademicYearId = active.AcademicYearId
            };
            var created = await _service.CreateClassAsync(entity);
            var dto = new ClassReadDto
            {
                ClassId = created.ClassId,
                ClassName = created.ClassName,
                Level = created.Level,
                DayOfWeek = created.DayOfWeek,
                TimeSlot = created.TimeSlot,
                Capacity = created.Capacity,
                CurrentEnrollment = created.CurrentEnrollment,
                AcademicYearId = created.AcademicYearId
            };
            return CreatedAtAction(nameof(Get), new { id = dto.ClassId }, dto);
        }

        /// <summary>
        /// Updates an existing class.
        /// </summary>
        /// <param name="id">Class id.</param>
        /// <param name="model">Updated fields.</param>
        [HttpPut("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<ClassReadDto>> Update(int id, [FromBody] ClassUpdateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var existing = await _service.GetClassByIdAsync(id);
            if (existing is null) return NotFound();

            if (!string.IsNullOrWhiteSpace(model.ClassName)) existing.ClassName = model.ClassName;
            if (!string.IsNullOrWhiteSpace(model.Level)) existing.Level = model.Level;
            if (!string.IsNullOrWhiteSpace(model.DayOfWeek)) existing.DayOfWeek = model.DayOfWeek;
            if (!string.IsNullOrWhiteSpace(model.TimeSlot)) existing.TimeSlot = model.TimeSlot;
            if (model.Capacity.HasValue) existing.Capacity = model.Capacity.Value;
            if (model.AcademicYearId.HasValue) existing.AcademicYearId = model.AcademicYearId.Value;

            var updated = await _service.UpdateClassAsync(id, existing);
            var dto = new ClassReadDto
            {
                ClassId = updated!.ClassId,
                ClassName = updated.ClassName,
                Level = updated.Level,
                DayOfWeek = updated.DayOfWeek,
                TimeSlot = updated.TimeSlot,
                Capacity = updated.Capacity,
                CurrentEnrollment = updated.CurrentEnrollment,
                AcademicYearId = updated.AcademicYearId
            };
            return Ok(dto);
        }

        /// <summary>
        /// Soft-deletes a class.
        /// </summary>
        /// <param name="id">Class id.</param>
        [HttpDelete("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Delete(int id)
        {
            var deleted = await _service.DeleteClassAsync(id);
            return deleted ? NoContent() : NotFound();
        }

        /// <summary>
        /// Exports classes for the specified academic year as CSV.
        /// </summary>
        /// <param name="academicYearId">Academic year id.</param>
        [HttpGet("export/{academicYearId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> ExportClasses(int academicYearId)
        {
            var bytes = await _service.ExportClassesByAcademicYearCsvAsync(academicYearId);
            return File(bytes, "text/csv", $"classes_year_{academicYearId}.csv");
        }
    }
}
