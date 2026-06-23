namespace HDSBackend.Models.Dto
{
    public class AcademicYearSummaryDto
    {
        public int AcademicYearId { get; set; }
        public string Year { get; set; } = string.Empty;
        public int TotalClasses { get; set; }
        public int TotalStudents { get; set; }
    }
}
