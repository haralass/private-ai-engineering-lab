using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Text.Json.Serialization;

namespace HDSBackend.Models
{
    /// <summary>
    /// Represents a class/course with schedule, level, and capacity information.
    /// </summary>
    public class Class
    {
        [Key]
        public int ClassId { get; set; }

        [Required]
        [MaxLength(100)]
        public string ClassName { get; set; }

        [Required]
        [MaxLength(50)]
        public string Level { get; set; }

        // Split DayTime into DayOfWeek and TimeSlot for better querying
        [Required]
        [MaxLength(20)]
        public string DayOfWeek { get; set; } // e.g., "Monday"

        [Required]
        [MaxLength(50)]
        public string TimeSlot { get; set; } // e.g., "17:00 - 18:00"

        [Required]
        public int Capacity { get; set; }

        public int CurrentEnrollment { get; set; }

        [Required]
        public int AcademicYearId { get; set; }

        [JsonIgnore]
        public AcademicYear? AcademicYear { get; set; }

        [JsonIgnore]
        public virtual ICollection<StudentClass> StudentClasses { get; set; } = new List<StudentClass>();

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }
    }
}
