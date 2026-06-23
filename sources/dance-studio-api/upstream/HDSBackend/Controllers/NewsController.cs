using HDSBackend.Models;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for creating and reading news items.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class NewsController : ControllerBase
    {
        private readonly INewsService _newsService;

        public NewsController(INewsService newsService)
        {
            _newsService = newsService;
        }

        /// <summary>
        /// Returns all news items.
        /// </summary>
        [HttpGet]
        [AllowAnonymous]
        public async Task<ActionResult<IEnumerable<NewsReadDto>>> GetAll()
        {
            var items = await _newsService.GetAllAsync();
            var dto = items.Select(n => new NewsReadDto
            {
                NewsId = n.NewsId,
                ImagePath = n.ImagePath,
                Title = n.Title,
                Body = n.Body,
                PublishDate = n.PublishDate,
                IsPublished = n.IsPublished,
                CreatedAt = n.CreatedAt,
                UpdatedAt = n.UpdatedAt
            });
            return Ok(dto);
        }

        /// <summary>
        /// Returns a news item by id.
        /// </summary>
        /// <param name="id">News id.</param>
        [HttpGet("{id:int}")]
        [AllowAnonymous]
        public async Task<ActionResult<NewsReadDto>> GetById(int id)
        {
            var n = await _newsService.GetByIdAsync(id);
            if (n == null) return NotFound();
            var dto = new NewsReadDto
            {
                NewsId = n.NewsId,
                ImagePath = n.ImagePath,
                Title = n.Title,
                Body = n.Body,
                PublishDate = n.PublishDate,
                IsPublished = n.IsPublished,
                CreatedAt = n.CreatedAt,
                UpdatedAt = n.UpdatedAt
            };
            return Ok(dto);
        }

        /// <summary>
        /// Creates a news item. DO NOT USE MOST LIKELY DEPRECATED BY NEWS UPLOAD
        /// </summary>
        /// <param name="news">News payload.</param>
        [HttpPost]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<NewsReadDto>> Create([FromBody] NewsCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var entity = new News
            {
                ImagePath = model.ImagePath,
                Title = model.Title,
                Body = model.Body,
                PublishDate = model.PublishDate,
                IsPublished = model.IsPublished
            };
            var created = await _newsService.CreateAsync(entity);
            var dto = new NewsReadDto
            {
                NewsId = created.NewsId,
                ImagePath = created.ImagePath,
                Title = created.Title,
                Body = created.Body,
                PublishDate = created.PublishDate,
                IsPublished = created.IsPublished,
                CreatedAt = created.CreatedAt,
                UpdatedAt = created.UpdatedAt
            };
            return CreatedAtAction(nameof(GetById), new { id = dto.NewsId }, dto);
        }

        /// <summary>
        /// Updates an existing news item.
        /// </summary>
        /// <param name="id">News id.</param>
        /// <param name="news">Updated fields.</param>
        [HttpPut("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<NewsReadDto>> Update(int id, [FromBody] NewsUpdateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var existing = await _newsService.GetByIdAsync(id);
            if (existing == null) return NotFound();

            if (!string.IsNullOrWhiteSpace(model.Title)) existing.Title = model.Title;
            if (model.Body != null) existing.Body = model.Body;
            if (model.PublishDate.HasValue) existing.PublishDate = model.PublishDate.Value;
            if (model.IsPublished.HasValue) existing.IsPublished = model.IsPublished.Value;

            var updated = await _newsService.UpdateAsync(id, existing);
            var dto = new NewsReadDto
            {
                NewsId = updated!.NewsId,
                ImagePath = updated.ImagePath,
                Title = updated.Title,
                Body = updated.Body,
                PublishDate = updated.PublishDate,
                IsPublished = updated.IsPublished,
                CreatedAt = updated.CreatedAt,
                UpdatedAt = updated.UpdatedAt
            };
            return Ok(dto);
        }

        /// <summary>
        /// Soft-deletes a news item.
        /// </summary>
        /// <param name="id">News id.</param>
        [HttpDelete("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Delete(int id)
        {
            var removed = await _newsService.SoftDeleteAsync(id);
            if (!removed) return NotFound();
            return NoContent();
        }

        /// <summary>
        /// Uploads an image and creates a news item in one step (multipart/form-data).
        /// </summary>
        [HttpPost("upload-image")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<NewsReadDto>> UploadImage([FromForm] NewsUploadDto dto)
        {
            if (dto.Image == null || dto.Image.Length == 0)
                return BadRequest("No file uploaded."); // file uploaded?

            var allowed = new[] { ".jpg", ".jpeg", ".png", ".gif", ".webp" };
            var ext = Path.GetExtension(dto.Image.FileName).ToLowerInvariant();
            if (!allowed.Contains(ext))
                return BadRequest("Unsupported file type."); // file type allowed?

            var root = Path.Combine(Directory.GetCurrentDirectory(), "wwwroot", "uploads", "news");
            Directory.CreateDirectory(root); // create dir if not exists

            var baseName = Path.GetFileNameWithoutExtension(dto.Image.FileName);
            var safeBase = new string(baseName.Where(char.IsLetterOrDigit).ToArray());
            if (string.IsNullOrWhiteSpace(safeBase)) safeBase = "img";

            var fileName = $"{safeBase}_{Guid.NewGuid():N}{ext}";
            var fullPath = Path.Combine(root, fileName);

            using (var fs = new FileStream(fullPath, FileMode.Create))
                await dto.Image.CopyToAsync(fs);

            var relativePath = $"/uploads/news/{fileName}"; //path for the image

            var entity = new News
            {
                ImagePath = relativePath,
                Title = dto.Title,
                Body = dto.Body,
                PublishDate = dto.PublishDate,
                IsPublished = dto.IsPublished
            };

            var created = await _newsService.CreateAsync(entity);

            var readDto = new NewsReadDto
            {
                NewsId = created.NewsId,
                ImagePath = created.ImagePath,
                Title = created.Title,
                Body = created.Body,
                PublishDate = created.PublishDate,
                IsPublished = created.IsPublished,
                CreatedAt = created.CreatedAt,
                UpdatedAt = created.UpdatedAt
            };

            return CreatedAtAction(nameof(GetById), new { id = readDto.NewsId }, readDto);
        }

    }
}
