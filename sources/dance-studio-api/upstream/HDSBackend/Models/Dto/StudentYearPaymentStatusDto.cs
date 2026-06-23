using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class StudentYearPaymentStatusDto
    {
        [Required]
        public int StudentId { get; set; }
        [Required]
        public int AcademicYearId { get; set; }

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
    }
}
