using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

namespace HDSBackend.Models
{
    /// <summary>
    /// Links a student to an academic year (many-to-many via link table).
    /// </summary>
    public class StudentAcademicYear
    {
        [Key]
        public int StudentAcademicYearId { get; set; }

        [Required]
        public int StudentId { get; set; }
        [JsonIgnore]
        public Student? Student { get; set; }

        [Required]
        public int AcademicYearId { get; set; }
        [JsonIgnore]
        public AcademicYear? AcademicYear { get; set; }

        // Payment checkmarks (10 installments)
        public bool Paid1 { get; set; }
        public bool Paid2 { get; set; }
        public bool Paid3 { get; set; }
        public bool Paid4 { get; set; }
        public bool Paid5 { get; set; }
        public bool Paid6 { get; set; }
        public bool Paid7 { get; set; }
        public bool Paid8 { get; set; }
        public bool Paid9 { get; set; }
        public bool Paid10 { get; set; }

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }
    }
}
