using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class AppUserReadDto
    {
        public int Id { get; set; }
        public string Username { get; set; } = string.Empty;
        public string? Email { get; set; }
        public string? DisplayName { get; set; }
        public bool IsDeleted { get; set; }
    }

    public class AppUserUpdateDto
    {
        [EmailAddress]
        public string? Email { get; set; }
        [MaxLength(50)]
        public string? DisplayName { get; set; }
    }

    public class DeleteAccountRequestDto
    {
        [Required]
        public string CurrentPassword { get; set; } = string.Empty;
    }
}
