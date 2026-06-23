using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class StudentClassCreateDto
    {
        [Required] public int StudentId { get; set; }
        [Required] public int ClassId { get; set; }
        [Required] public DateTime EnrollmentDate { get; set; }
    }

    public class StudentClassReadDto
    {
        public int StudentClassId { get; set; }
        public int StudentId { get; set; }
        public int ClassId { get; set; }
        public DateTime EnrollmentDate { get; set; }
    }
}
