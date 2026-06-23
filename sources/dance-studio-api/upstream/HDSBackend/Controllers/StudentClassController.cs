using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;
using Microsoft.AspNetCore.Identity;
using HDSBackend.Models;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for managing student enrollments in classes (many-to-many Student &lt;-&gt; Class).
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class StudentClassController : ControllerBase
    {
        private readonly IStudentClassService _service;
        private readonly UserManager<AppUser> _userManager;
        private readonly IStudentService _studentService;

        public StudentClassController(IStudentClassService service, UserManager<AppUser> userManager, IStudentService studentService)
        {
            _service = service;
            _userManager = userManager;
            _studentService = studentService;
        }

        /// <summary>
        /// Enrolls a student into a class if capacity allows and no active enrollment exists.
        /// </summary>
        [HttpPost]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<StudentClassReadDto>> Enroll([FromBody] StudentClassCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var result = await _service.EnrollAsync(model);
            if (result == null)
                return Conflict("Enrollment failed (duplicate, capacity, or missing entities).");

            return CreatedAtAction(nameof(GetById), new { id = result.StudentClassId }, result);
        }

        /// <summary>
        /// Retrieves a specific enrollment by its identifier.
        /// </summary>
        [HttpGet("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<StudentClassReadDto>> GetById(int id)
        {
            var result = await _service.GetByIdAsync(id);
            return result == null ? NotFound() : Ok(result);
        }

        /// <summary>
        /// Lists all enrollments for a given student.
        /// </summary>
        [HttpGet("by-student/{studentId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<StudentClassReadDto>>> GetByStudent(int studentId)
        {
            var result = await _service.GetByStudentAsync(studentId);
            return Ok(result);
        }

        /// <summary>
        /// Lists all enrollments for a given class.
        /// </summary>
        [HttpGet("by-class/{classId:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<StudentClassReadDto>>> GetByClass(int classId)
        {
            var result = await _service.GetByClassAsync(classId);
            return Ok(result);
        }

        /// <summary>
        /// Unenrolls a student from a class (soft delete) and decrements the class's current enrollment.
        /// </summary>
        [HttpDelete("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Unenroll(int id)
        {
            var ok = await _service.UnenrollAsync(id);
            return ok ? NoContent() : NotFound();
        }

        /// <summary>
        /// Lists all enrollments for the current authenticated user (by linked student profile).
        /// </summary>
        [HttpGet("me")]
        [Authorize]
        public async Task<ActionResult<IEnumerable<StudentClassReadDto>>> GetMyEnrollments()
        {
            var user = await _userManager.GetUserAsync(User);
            if (user == null) return Unauthorized();

            var student = await _studentService.GetStudentByUserIdAsync(user.Id);
            if (student == null) return NotFound("No linked student profile.");

            var result = await _service.GetByStudentAsync(student.StudentId);
            return Ok(result);
        }

        /// <summary>
        /// Enroll the current authenticated user into a class, using their linked student profile.
        /// </summary>
        /// <param name="model">Enrollment data (ClassId, EnrollmentDate). StudentId is ignored.</param>
        [HttpPost("me")]
        [Authorize]
        public async Task<ActionResult<StudentClassReadDto>> EnrollMe([FromBody] StudentClassCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var user = await _userManager.GetUserAsync(User);
            if (user == null) return Unauthorized();

            var student = await _studentService.GetStudentByUserIdAsync(user.Id);
            if (student == null) return NotFound("No linked student profile.");

            var create = new StudentClassCreateDto
            {
                StudentId = student.StudentId,
                ClassId = model.ClassId,
                EnrollmentDate = model.EnrollmentDate
            };

            var result = await _service.EnrollAsync(create);
            if (result == null)
                return Conflict("Enrollment failed (duplicate, capacity, or missing entities).");

            return CreatedAtAction(nameof(GetById), new { id = result.StudentClassId }, result);
        }
    }
}
