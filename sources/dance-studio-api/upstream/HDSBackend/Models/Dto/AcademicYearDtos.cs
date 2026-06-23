using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class AcademicYearCreateDto
    {
        [Required]
        [MaxLength(20)]
        public string Year { get; set; } = string.Empty;

        [Required]
        public DateTime StartDate { get; set; }

        [Required]
        public DateTime EndDate { get; set; }

        [Required]
        public bool IsActive { get; set; }
    }

    public class AcademicYearUpdateDto
    {
        [MaxLength(20)]
        public string? Year { get; set; }
        public DateTime? StartDate { get; set; }
        public DateTime? EndDate { get; set; }
        public bool? IsActive { get; set; }
    }

    public class AcademicYearReadDto
    {
        public int AcademicYearId { get; set; }
        public string Year { get; set; } = string.Empty;
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public bool IsActive { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
