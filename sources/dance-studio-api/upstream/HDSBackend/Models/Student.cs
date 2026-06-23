using System.ComponentModel.DataAnnotations;
using System.Text.Json.Serialization;

namespace HDSBackend.Models
{
    /// <summary>
    /// Represents a student with personal and emergency contact information.
    /// </summary>
    public class Student
    {
        [Key]
        public int StudentId { get; set; }

        public int? UserId { get; set; }

        [JsonIgnore]
        public AppUser? User { get; set; }

        [Required]
        [EmailAddress]
        [MaxLength(256)]
        public string Email { get; set; } = string.Empty;

        // Personal info
        [Required]
        [MaxLength(50)]
        public string FirstName { get; set; }

        [Required]
        [MaxLength(50)]
        public string LastName { get; set; }

        [Required]
        public DateTime BirthDate { get; set; }

        // Safety & medical info
        [Required]
        [MaxLength(100)]
        public string EmergencyContact { get; set; }

        [Required]
        [MaxLength(20)]
        public string EmergencyPhone { get; set; }

        [MaxLength(20)]
        public string? PersonalPhone { get; set; }

        [MaxLength(500)]
        public string? Allergies { get; set; }

        // Enrollments (each tied to a specific AcademicYear)
        [JsonIgnore]
        public virtual ICollection<StudentClass>? StudentClasses { get; set; } = new List<StudentClass>();

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }
    }
}
