using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;
using Microsoft.AspNetCore.Identity;
using HDSBackend.Models;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for managing students and their data.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class StudentController : ControllerBase
    {
        private readonly IStudentService _service;
        private readonly UserManager<AppUser> _userManager;

        public StudentController(IStudentService service, UserManager<AppUser> userManager)
        {
            _service = service;
            _userManager = userManager;
        }

        /// <summary>
        /// Returns all students (not deleted).
        /// </summary>
        /// <returns>List of students.</returns>
        [HttpGet]
        [Authorize(Roles = "Admin")] //changed this to admin, i trhink thats fair? going to have to check later
        public async Task<ActionResult<IEnumerable<StudentReadDto>>> GetAll()
        {
            var dto = await _service.GetAllReadDtosAsync();
            return Ok(dto);
        }

        /// <summary>
        /// Returns a student by id.
        /// </summary>
        /// <param name="id">Student id.</param>
        /// <returns>Student or 404.</returns>
        [HttpGet("{id:int}")]
        [AllowAnonymous]
        public async Task<ActionResult<StudentReadDto>> Get(int id)
        {
            var dto = await _service.GetReadDtoByIdAsync(id);
            return dto == null ? NotFound() : Ok(dto);
        }

        /// <summary>
        /// Creates a new student. If an AppUser with the same email exists, the student will be linked to that user.
        /// </summary>
        /// <param name="student">Student payload.</param>
        /// <returns>Created student.</returns>
        [HttpPost]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<StudentReadDto>> Create([FromBody] StudentCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            // Link to existing AppUser when email matches and not explicitly linked
            if (model.UserId == null && !string.IsNullOrWhiteSpace(model.Email))
            {
                var existingUser = await _userManager.FindByEmailAsync(model.Email);
                if (existingUser != null)
                {
                    model.UserId = existingUser.Id;
                }
            }

            var dto = await _service.CreateFromDtoAsync(model);
            return CreatedAtAction(nameof(Get), new { id = dto.StudentId }, dto);
        }

        /// <summary>
        /// Updates an existing student. If an AppUser with the provided email exists and the student is not linked, it will be linked.
        /// </summary>
        /// <param name="id">Student id.</param>
        /// <param name="student">Updated fields.</param>
        /// <returns>Updated student or 404.</returns>
        [HttpPut("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<StudentReadDto>> Update(int id, [FromBody] StudentUpdateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var dto = await _service.UpdateFromDtoAsync(id, model);
            return dto == null ? NotFound() : Ok(dto);
        }

        /// <summary>
        /// Soft-deletes a student.
        /// </summary>
        /// <param name="id">Student id.</param>
        /// <returns>No content or 404.</returns>
        [HttpDelete("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Delete(int id)
        {
            var deleted = await _service.DeleteStudentAsync(id);
            return deleted ? NoContent() : NotFound();
        }

        /// <summary>
        /// Returns the current authenticated user's student profile if linked.
        /// </summary>
        [HttpGet("me")]
        [Authorize]
        public async Task<ActionResult<StudentReadDto>> GetMyStudentProfile()
        {
            var user = await _userManager.GetUserAsync(User);
            if (user == null) return Unauthorized();

            var s = await _service.GetStudentByUserIdAsync(user.Id);
            if (s is null) return NotFound();

            var dto = await _service.GetReadDtoByIdAsync(s.StudentId);
            return dto == null ? NotFound() : Ok(dto);
        }

        /// <summary>
        /// Creates or updates the current user's student profile and links it to the logged-in user.
        /// The student Email is always set from the authenticated user's Email since it 100% exists on AppUser after sign up
        /// </summary>
        [HttpPost("me")]
        [Authorize]
        public async Task<ActionResult<StudentReadDto>> UpsertMyStudentProfile([FromBody] StudentCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var user = await _userManager.GetUserAsync(User);
            if (user == null) return Unauthorized();

            var existing = await _service.GetStudentByUserIdAsync(user.Id);
            var userEmail = user.Email;

            if (existing == null)
            {
                model.Email = userEmail; // enforce email from AppUser
                model.UserId = user.Id;
                var dto = await _service.CreateFromDtoAsync(model);
                return CreatedAtAction(nameof(Get), new { id = dto.StudentId }, dto);
            }
            else
            {
                var update = new StudentUpdateDto
                {
                    FirstName = model.FirstName,
                    LastName = model.LastName,
                    BirthDate = model.BirthDate,
                    EmergencyContact = model.EmergencyContact,
                    EmergencyPhone = model.EmergencyPhone,
                    PersonalPhone = model.PersonalPhone,
                    Email = userEmail
                };
                var dto = await _service.UpdateFromDtoAsync(existing.StudentId, update);
                return dto == null ? NotFound() : Ok(dto);
            }
        }

        /// <summary>
        /// Searches students by partial match on first name, last name, or email.
        /// If query is empty or missing, returns all students.
        /// Optionally restrict by academicYearId.
        /// </summary>
        /// <param name="q">Search query string (optional).</param>
        [HttpGet("search")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<StudentReadDto>>> Search([FromQuery] string? q = null, [FromQuery] int limit = 20, [FromQuery] int? academicYearId = null)
        {
            var results = await _service.SearchAsync(q ?? string.Empty, limit <= 0 ? 20 : Math.Min(limit, 100), academicYearId);
            return Ok(results);
        }

        /// <summary>
        /// Checks if the current authenticated user has a linked student profile.
        /// </summary>
        [HttpGet("me/exists")]
        [Authorize]
        public async Task<ActionResult<object>> DoesMyStudentProfileExist()
        {
            var user = await _userManager.GetUserAsync(User);
            if (user == null) return Unauthorized();
            var student = await _service.GetStudentByUserIdAsync(user.Id);
            return Ok(new { exists = student != null });
        }

        /// <summary>
        /// Updates the current authenticated user's student profile.
        /// </summary>
        [HttpPut("me")]
        [Authorize]
        public async Task<ActionResult<StudentReadDto>> UpdateMyStudentProfile([FromBody] StudentUpdateDto model)
        {
            if (!ModelState.IsValid) return ValidationProblem(ModelState);
            var user = await _userManager.GetUserAsync(User);
            if (user == null) return Unauthorized();
            var updated = await _service.UpdateMyProfileAsync(user.Id, model);
            return updated == null ? NotFound("No linked student profile.") : Ok(updated);
        }
    }
}