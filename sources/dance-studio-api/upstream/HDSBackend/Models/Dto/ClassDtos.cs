using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class ClassCreateDto
    {
        [Required, MaxLength(100)] public string ClassName { get; set; } = string.Empty;
        [Required, MaxLength(50)] public string Level { get; set; } = string.Empty;
        [Required, MaxLength(20)] public string DayOfWeek { get; set; } = string.Empty;
        [Required, MaxLength(50)] public string TimeSlot { get; set; } = string.Empty;
        [Required] public int Capacity { get; set; }
    }

    public class ClassUpdateDto
    {
        [MaxLength(100)] public string? ClassName { get; set; }
        [MaxLength(50)] public string? Level { get; set; }
        [MaxLength(20)] public string? DayOfWeek { get; set; }
        [MaxLength(50)] public string? TimeSlot { get; set; }
        public int? Capacity { get; set; }
        public int? AcademicYearId { get; set; }
    }

    public class ClassReadDto
    {
        public int ClassId { get; set; }
        public string ClassName { get; set; } = string.Empty;
        public string Level { get; set; } = string.Empty;
        public string DayOfWeek { get; set; } = string.Empty;
        public string TimeSlot { get; set; } = string.Empty;
        public int Capacity { get; set; }
        public int CurrentEnrollment { get; set; }
        public int AcademicYearId { get; set; }
    }
}
