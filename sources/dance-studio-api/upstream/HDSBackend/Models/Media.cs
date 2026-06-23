using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models
{
    /// <summary>
    /// Represents a media file stored by the application.
    /// </summary>
    public class Media
    {
        [Key]
        public int FileId { get; set; }

        [Required]
        [MaxLength(255)]
        public string FilePath { get; set; }

        [Required]
        [MaxLength(100)]
        public string Name { get; set; }

        [Required]
        [MaxLength(50)]
        public string FileType { get; set; }

        public long FileSize { get; set; }

        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }
    }
}
