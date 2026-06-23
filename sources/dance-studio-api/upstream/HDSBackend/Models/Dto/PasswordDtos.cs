using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class ChangePasswordDto
    {
        [Required] public string CurrentPassword { get; set; } = string.Empty;
        [Required] public string NewPassword { get; set; } = string.Empty;
    }

    public class AdminResetPasswordDto
    {
        [Required] public int UserId { get; set; }
        [Required] public string NewPassword { get; set; } = string.Empty;
    }
}
