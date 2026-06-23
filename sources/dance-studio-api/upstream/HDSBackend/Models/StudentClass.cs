using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

namespace HDSBackend.Models
{
    /// <summary>
    /// Join entity representing a student's enrollment in a class.
    /// </summary>
    public class StudentClass
    {
        [Key]
        public int StudentClassId { get; set; }

        public int StudentId { get; set; }
        [JsonIgnore]
        public Student Student { get; set; }

        public int ClassId { get; set; }
        [JsonIgnore]
        public Class Class { get; set; }

        public DateTime EnrollmentDate { get; set; }

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }
    }
}
