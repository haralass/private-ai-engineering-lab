using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class MediaCreateDto
    {
        [Required, MaxLength(255)] public string FilePath { get; set; } = string.Empty;
        [Required, MaxLength(100)] public string Name { get; set; } = string.Empty;
        [Required, MaxLength(50)] public string FileType { get; set; } = string.Empty;
        [Required] public long FileSize { get; set; }
    }

    public class MediaUpdateDto
    {
        [MaxLength(100)] public string? Name { get; set; }
        public bool? IsDeleted { get; set; }
    }

    public class MediaReadDto
    {
        public int FileId { get; set; }
        public string FilePath { get; set; } = string.Empty;
        public string Name { get; set; } = string.Empty;
        public string FileType { get; set; } = string.Empty;
        public long FileSize { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }

    public class MediaUploadDto
    {
        public IFormFile File { get; set; } // image or video file
        public string Name { get; set; }
    }

}
