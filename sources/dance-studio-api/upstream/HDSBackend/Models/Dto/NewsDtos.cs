using System.ComponentModel.DataAnnotations;

namespace HDSBackend.Models.Dto
{
    public class NewsCreateDto
    {
        [Required, MaxLength(255)] public string ImagePath { get; set; } = string.Empty;
        [Required, MaxLength(200)] public string Title { get; set; } = string.Empty;
        [Required] public string Body { get; set; } = string.Empty;
        [Required] public DateTime PublishDate { get; set; }
        [Required] public bool IsPublished { get; set; }
    }

    public class NewsUpdateDto
    {
        [MaxLength(200)] public string? Title { get; set; }
        public string? Body { get; set; }
        public DateTime? PublishDate { get; set; }
        public bool? IsPublished { get; set; }
    }

    public class NewsReadDto
    {
        public int NewsId { get; set; }
        public string ImagePath { get; set; } = string.Empty;
        public string Title { get; set; } = string.Empty;
        public string Body { get; set; } = string.Empty;
        public DateTime PublishDate { get; set; }
        public bool IsPublished { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
            
    public class NewsUploadDto
    {
        public IFormFile Image { get; set; }
        public string Title { get; set; }
        public string Body { get; set; }
        public DateTime PublishDate { get; set; }
        public bool IsPublished { get; set; }
    }

}
