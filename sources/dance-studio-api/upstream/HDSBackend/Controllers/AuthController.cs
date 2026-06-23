using HDSBackend.Models;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.IdentityModel.Tokens;
using System.ComponentModel.DataAnnotations;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using HDSBackend.Data;
using Microsoft.EntityFrameworkCore;
using Microsoft.AspNetCore.Authorization;
using HDSBackend.Models.Dto;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// Authentication endpoints for registering and logging in users.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly UserManager<AppUser> _userManager;
        private readonly SignInManager<AppUser> _signInManager;
        private readonly IConfiguration _config;
        private readonly HDSDbContext _context;

        public AuthController(UserManager<AppUser> userManager, SignInManager<AppUser> signInManager, IConfiguration config, HDSDbContext context)
        {
            _userManager = userManager;
            _signInManager = signInManager;
            _config = config;
            _context = context;
        }

        public record RegisterRequest(
            [param: Required]
            string Username,
            [param: Required, EmailAddress]
            string Email,
            [param: Required]
            string Password,
            string? DisplayName);

        public record AuthResponse(int UserId, string Username, string? Email, string? DisplayName, string Token, DateTime ExpiresAtUtc);
        public record LoginRequest(
            [param: Required]
            string UsernameOrEmail,
            [param: Required]
            string Password);

        /// <summary>
        /// Registers a new user. Email is required and must be valid (x@y.z).
        /// </summary>
        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] RegisterRequest request)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var existingByName = await _userManager.FindByNameAsync(request.Username);
            if (existingByName != null)
                return Conflict("Username already exists.");

            var existingByEmail = await _userManager.FindByEmailAsync(request.Email);
            if (existingByEmail != null)
                return Conflict("Email already in use.");

            var user = new AppUser
            {
                UserName = request.Username,
                Email = request.Email,
                DisplayName = request.DisplayName,
                CreatedAt = DateTime.UtcNow,
                IsDeleted = false
            };

            var result = await _userManager.CreateAsync(user, request.Password);
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            // auto-link student by email if exactly one unlinked student matches
            if (!string.IsNullOrWhiteSpace(request.Email))
            {
                var normalizedEmail = request.Email.Trim().ToLowerInvariant();
                var candidates = await _context.Students
                    .Where(s => s.Email != null && s.Email.ToLower() == normalizedEmail && s.UserId == null)
                    .ToListAsync();
                if (candidates.Count == 1)
                {
                    candidates[0].UserId = user.Id;
                    candidates[0].UpdatedAt = DateTime.UtcNow;
                    await _context.SaveChangesAsync();
                }
            }

            return Ok(new { message = "Registered successfully." });
        }

        public record LoginResponse(int UserId, string Username, string? Email, string? DisplayName, string Token, DateTime ExpiresAtUtc);

        /// <summary>
        /// Logs in an existing user with username or email and returns a JWT token.
        /// Also attempts to auto-link a matching student by email if not already linked.
        /// </summary>
        [HttpPost("login")]
        public async Task<IActionResult> Login(LoginRequest request)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            AppUser? user = null;
            var input = request.UsernameOrEmail?.Trim() ?? string.Empty;
            var isEmail = new EmailAddressAttribute().IsValid(input);
            if (isEmail)
            {
                user = await _userManager.FindByEmailAsync(input);
            }
            else
            {
                user = await _userManager.FindByNameAsync(input);
            }

            if (user == null || user.IsDeleted)
                return Unauthorized("Invalid credentials.");

            var valid = await _userManager.CheckPasswordAsync(user, request.Password);
            if (!valid)
                return Unauthorized("Invalid credentials.");

            // Attempt to auto-link student on first successful login
            if (!string.IsNullOrWhiteSpace(user.Email))
            {
                var normalizedEmail = user.Email.Trim().ToLowerInvariant();
                var candidates = await _context.Students
                    .Where(s => s.Email != null && s.Email.ToLower() == normalizedEmail && s.UserId == null)
                    .ToListAsync();
                if (candidates.Count == 1)
                {
                    candidates[0].UserId = user.Id;
                    candidates[0].UpdatedAt = DateTime.UtcNow;
                    await _context.SaveChangesAsync();
                }
            }

            // Build JWT with role claims
            var roles = await _userManager.GetRolesAsync(user);
            var jwtSection = _config.GetSection("Jwt");
            var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(jwtSection["Key"]!));
            var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);

            var claims = new List<Claim>
            {
                new(JwtRegisteredClaimNames.Sub, user.Id.ToString()),
                new(ClaimTypes.Name, user.UserName!)
            };
            claims.AddRange(roles.Select(r => new Claim(ClaimTypes.Role, r)));

            var expires = DateTime.UtcNow.AddMinutes(int.TryParse(jwtSection["ExpireMinutes"], out var m) ? m : 60);
            var token = new JwtSecurityToken(
                issuer: jwtSection["Issuer"],
                audience: jwtSection["Audience"],
                claims: claims,
                expires: expires,
                signingCredentials: creds
            );

            var tokenString = new JwtSecurityTokenHandler().WriteToken(token);
            var resp = new AuthResponse(user.Id, user.UserName!, user.Email, user.DisplayName, tokenString, expires);
            return Ok(resp);
        }

        /// <summary>
        /// Changes the authenticated user's password.
        /// </summary>
        [Authorize]
        [HttpPost("change-password")]
        public async Task<IActionResult> ChangePassword([FromBody] ChangePasswordDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var user = await _userManager.GetUserAsync(User);
            if (user == null || user.IsDeleted)
                return Unauthorized();

            var result = await _userManager.ChangePasswordAsync(user, model.CurrentPassword, model.NewPassword);
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            await _userManager.UpdateSecurityStampAsync(user);
            return Ok(new { message = "Password changed." });
        }

        /// <summary>
        /// Admin-only endpoint to reset a user's password without the current password.
        /// </summary>
        [Authorize(Roles = "Admin")]
        [HttpPost("admin/reset-password")]
        public async Task<IActionResult> AdminResetPassword([FromBody] AdminResetPasswordDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var user = await _userManager.FindByIdAsync(model.UserId.ToString());
            if (user == null || user.IsDeleted)
                return NotFound("User not found.");

            var token = await _userManager.GeneratePasswordResetTokenAsync(user);
            var result = await _userManager.ResetPasswordAsync(user, token, model.NewPassword);
            if (!result.Succeeded)
                return BadRequest(string.Join("; ", result.Errors.Select(e => e.Description)));

            await _userManager.UpdateSecurityStampAsync(user);
            return Ok(new { message = "Password reset." });
        }
    }
}
