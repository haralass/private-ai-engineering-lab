using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class StudentCreateDto
    {
        [Required, MaxLength(50)] public string FirstName { get; set; } = string.Empty;
        [Required, MaxLength(50)] public string LastName { get; set; } = string.Empty;
        [Required] public DateTime BirthDate { get; set; }
        [Required, MaxLength(100)] public string EmergencyContact { get; set; } = string.Empty;
        [Required, MaxLength(20)] public string EmergencyPhone { get; set; } = string.Empty;
        [Required, EmailAddress, MaxLength(256)] public string Email { get; set; } = string.Empty;
        public int? UserId { get; set; }
        [MaxLength(20)] public string? PersonalPhone { get; set; }
    }

    public class StudentUpdateDto
    {
        [MaxLength(50)] public string? FirstName { get; set; }
        [MaxLength(50)] public string? LastName { get; set; }
        public DateTime? BirthDate { get; set; }
        [MaxLength(100)] public string? EmergencyContact { get; set; }
        [MaxLength(20)] public string? EmergencyPhone { get; set; }
        [MaxLength(20)] public string? PersonalPhone { get; set; }
        [EmailAddress, MaxLength(256)] public string? Email { get; set; }
        [MaxLength(500)] public string? Allergies { get; set; }
    }

    public class StudentReadDto
    {
        public int StudentId { get; set; }
        public string FirstName { get; set; } = string.Empty;
        public string LastName { get; set; } = string.Empty;
        public DateTime BirthDate { get; set; }
        public string EmergencyContact { get; set; } = string.Empty;
        public string EmergencyPhone { get; set; } = string.Empty;
        public string? PersonalPhone { get; set; }
        public string Email { get; set; } = string.Empty;
        public int? UserId { get; set; }
        public string? Allergies { get; set; }
    }
}
