using System;
using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models
{
    /// <summary>
    /// Represents a news item published by the school.
    /// </summary>
    public class News
    {
        [Key]
        public int NewsId { get; set; }

        [Required]
        [MaxLength(255)]
        public string ImagePath { get; set; }

        [Required]
        [MaxLength(200)]
        public string Title { get; set; }

        [Required]
        public string Body { get; set; }

        public DateTime PublishDate { get; set; }
        public bool IsPublished { get; set; }

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }
    }
}
