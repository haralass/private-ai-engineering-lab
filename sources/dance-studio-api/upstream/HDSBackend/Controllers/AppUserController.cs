using HDSBackend.Models;
using HDSBackend.Models.Dto;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for managing application users.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    [Authorize]
    public class AppUserController : ControllerBase
    {
        private readonly UserManager<AppUser> _userManager;
        private readonly IStudentService _studentService;

        public AppUserController(UserManager<AppUser> userManager, IStudentService studentService)
        {
            _userManager = userManager;
            _studentService = studentService;
        }

        /// <summary>
        /// Returns all non-deleted users.
        /// </summary>
        [HttpGet]
        public async Task<ActionResult<IEnumerable<AppUserReadDto>>> GetAll()
        {
            var users = await _userManager.Users
                .AsNoTracking()
                .Where(u => !u.IsDeleted)
                .Select(u => new AppUserReadDto
                {
                    Id = u.Id,
                    Username = u.UserName!,
                    Email = u.Email,
                    DisplayName = u.DisplayName,
                    IsDeleted = u.IsDeleted
                })
                .ToListAsync();

            return Ok(users);
        }

        /// <summary>
        /// Returns a user by id.
        /// </summary>
        [HttpGet("{id:int}")]
        public async Task<IActionResult> GetById(int id)
        {
            var u = await _userManager.FindByIdAsync(id.ToString());
            if (u == null) return NotFound();
            return Ok(new AppUserReadDto
            {
                Id = u.Id,
                Username = u.UserName!,
                Email = u.Email,
                DisplayName = u.DisplayName,
                IsDeleted = u.IsDeleted
            });
        }

        /// <summary>
        /// Updates an existing user.
        /// </summary>
        [HttpPut("{id:int}")]
        public async Task<IActionResult> Update(int id, [FromBody] AppUserUpdateDto request)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var u = await _userManager.FindByIdAsync(id.ToString());
            if (u == null) return NotFound();

            if (request.Email != null) u.Email = request.Email;
            if (request.DisplayName != null) u.DisplayName = request.DisplayName;
            u.UpdatedAt = DateTime.UtcNow;

            var result = await _userManager.UpdateAsync(u);
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            return Ok(new AppUserReadDto
            {
                Id = u.Id,
                Username = u.UserName!,
                Email = u.Email,
                DisplayName = u.DisplayName,
                IsDeleted = u.IsDeleted
            });
        }

        /// <summary>
        /// Soft-deletes a user (and their linked student profile if any).
        /// </summary>
        [HttpDelete("{id:int}")]
        public async Task<IActionResult> SoftDelete(int id)
        {
            var u = await _userManager.FindByIdAsync(id.ToString());
            if (u == null) return NotFound();

            u.IsDeleted = true;
            u.UpdatedAt = DateTime.UtcNow;

            var result = await _userManager.UpdateAsync(u);
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            var student = await _studentService.GetStudentByUserIdAsync(u.Id);
            if (student != null)
            {
                await _studentService.DeleteStudentAsync(student.StudentId);
            }

            return NoContent();
        }

        /// <summary>
        /// Allows the authenticated user to soft-delete their own account (also deletes linked student profile). Requires current password confirmation.
        /// </summary>
        [HttpDelete("me")]
        public async Task<IActionResult> DeleteMyAccount([FromBody] DeleteAccountRequestDto request)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var u = await _userManager.GetUserAsync(User);
            if (u == null) return Unauthorized();

            var hasPassword = await _userManager.HasPasswordAsync(u);
            if (hasPassword)
            {
                var valid = await _userManager.CheckPasswordAsync(u, request.CurrentPassword);
                if (!valid) return BadRequest("Invalid password.");
            }

            u.IsDeleted = true;
            u.UpdatedAt = DateTime.UtcNow;

            var result = await _userManager.UpdateAsync(u);
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            var student = await _studentService.GetStudentByUserIdAsync(u.Id);
            if (student != null)
            {
                await _studentService.DeleteStudentAsync(student.StudentId);
            }

            await _userManager.UpdateSecurityStampAsync(u);
            return NoContent();
        }

        /// <summary>
        /// Assigns the Admin role to the specified user by username.
        /// </summary>
        [HttpPost("promote/{username}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> PromoteToAdmin(string username)
        {
            if (string.IsNullOrWhiteSpace(username)) return BadRequest("Username is required.");

            var u = await _userManager.FindByNameAsync(username);
            if (u == null) return NotFound($"User '{username}' not found.");

            if (await _userManager.IsInRoleAsync(u, "Admin"))
                return Ok(new { message = "User already an Admin." });

            var result = await _userManager.AddToRoleAsync(u, "Admin");
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            return Ok(new AppUserReadDto
            {
                Id = u.Id,
                Username = u.UserName!,
                Email = u.Email,
                DisplayName = u.DisplayName,
                IsDeleted = u.IsDeleted
            });
        }

        /// <summary>
        /// Removes the Admin role from the specified user.
        /// </summary>
        [HttpPost("demote/{username}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> DemoteFromAdmin(string username)
        {
            if (string.IsNullOrWhiteSpace(username)) return BadRequest("Username is required.");

            var u = await _userManager.FindByNameAsync(username);
            if (u == null) return NotFound($"User '{username}' not found.");

            if (!await _userManager.IsInRoleAsync(u, "Admin"))
                return Ok(new { message = "User is not an Admin." });

            var result = await _userManager.RemoveFromRoleAsync(u, "Admin");
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            return Ok(new { message = "User demoted from Admin.", user = new AppUserReadDto { Id = u.Id, Username = u.UserName!, Email = u.Email, DisplayName = u.DisplayName, IsDeleted = u.IsDeleted } });
        }

        /// <summary>
        /// Returns all non-deleted users excluding those with the Admin role.
        /// </summary>
        [HttpGet("non-admins")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> GetNonAdminUsers()
        {
            var admins = await _userManager.GetUsersInRoleAsync("Admin");
            var adminIds = admins.Select(a => a.Id).ToHashSet();

            var users = await _userManager.Users
                .AsNoTracking()
                .Where(u => !u.IsDeleted && !adminIds.Contains(u.Id))
                .Select(u => new AppUserReadDto
                {
                    Id = u.Id,
                    Username = u.UserName!,
                    Email = u.Email,
                    DisplayName = u.DisplayName,
                    IsDeleted = u.IsDeleted
                })
                .ToListAsync();

            return Ok(users);
        }

        /// <summary>
        /// Returns all users with the Admin role.
        /// </summary>
        [HttpGet("admins")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<IEnumerable<AppUserReadDto>>> GetAdmins()
        {
            var admins = await _userManager.GetUsersInRoleAsync("Admin");

            var result = admins
                .Where(u => !u.IsDeleted)
                .OrderBy(u => u.UserName)
                .Select(u => new AppUserReadDto
                {
                    Id = u.Id,
                    Username = u.UserName!,
                    Email = u.Email,
                    DisplayName = u.DisplayName,
                    IsDeleted = u.IsDeleted
                })
                .ToList();

            return Ok(result);
        }
    }
}
