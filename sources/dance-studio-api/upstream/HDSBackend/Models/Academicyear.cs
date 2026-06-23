using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models
{
    /// <summary>
    /// Represents an academic year with start/end dates and active status.
    /// </summary>
    public class AcademicYear
    {
        [Key]
        public int AcademicYearId { get; set; }

        [Required]
        [MaxLength(20)]
        public string Year { get; set; } = null!; /*these stupid ass warnings CS8618 can be resolved by essentially 
                                                   * affirming it that it will NOT be null at the end of the day. theres too many for me to care*/
                                                 
        [Required]
        public DateTime StartDate { get; set; }

        [Required]
        public DateTime EndDate { get; set; }

        [Required]
        public bool IsActive { get; set; }

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }

        // Keep classes navigation
        public virtual ICollection<Class> Classes { get; set; } = new List<Class>();
    }
}
