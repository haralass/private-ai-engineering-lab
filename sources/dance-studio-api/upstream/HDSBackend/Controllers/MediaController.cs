using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using HDSBackend.Models;
using HDSBackend.Models.Dto;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Controllers
{
    /// <summary>
    /// API endpoints for managing media files and metadata.
    /// </summary>
    [ApiController]
    [Route("api/[controller]")]
    public class MediaController : ControllerBase
    {
        private readonly IMediaService _mediaService;

        public MediaController(IMediaService mediaService)
        {
            _mediaService = mediaService;
        }

        /// <summary>
        /// Returns all media items.
        /// </summary>
        [HttpGet]
        [AllowAnonymous]
        public async Task<ActionResult<IEnumerable<MediaReadDto>>> GetAll()
        {
            var items = await _mediaService.GetAllAsync();
            var dto = items.Select(m => new MediaReadDto
            {
                FileId = m.FileId,
                FilePath = m.FilePath,
                Name = m.Name,
                FileType = m.FileType,
                FileSize = m.FileSize,
                CreatedAt = m.CreatedAt,
                UpdatedAt = m.UpdatedAt
            });
            return Ok(dto);
        }

        /// <summary>
        /// Returns a media item by id. 
        /// </summary>
        /// <param name="id">Media id.</param>
        [HttpGet("{id:int}")]
        [AllowAnonymous]
        public async Task<ActionResult<MediaReadDto>> GetById(int id)
        {
            var m = await _mediaService.GetByIdAsync(id);
            if (m == null) return NotFound();
            var dto = new MediaReadDto
            {
                FileId = m.FileId,
                FilePath = m.FilePath,
                Name = m.Name,
                FileType = m.FileType,
                FileSize = m.FileSize,
                CreatedAt = m.CreatedAt,
                UpdatedAt = m.UpdatedAt
            };
            return Ok(dto);
        }

        /// <summary>
        /// Creates a new media item. DO NOT USE MOST LIKELY DEPRECATED BY MEDIA UPLOAD
        /// </summary>
        /// <param name="media">Media payload.</param>
        [HttpPost]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<MediaReadDto>> Create([FromBody] MediaCreateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var entity = new Media
            {
                FilePath = model.FilePath,
                Name = model.Name,
                FileType = model.FileType,
                FileSize = model.FileSize
            };
            var created = await _mediaService.CreateAsync(entity);
            var dto = new MediaReadDto
            {
                FileId = created.FileId,
                FilePath = created.FilePath,
                Name = created.Name,
                FileType = created.FileType,
                FileSize = created.FileSize,
                CreatedAt = created.CreatedAt,
                UpdatedAt = created.UpdatedAt
            };
            return CreatedAtAction(nameof(GetById), new { id = dto.FileId }, dto);
        }

        /// <summary>
        /// Updates an existing media item.
        /// </summary>
        /// <param name="id">Media id.</param>
        /// <param name="media">Updated fields.</param>
        [HttpPut("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<MediaReadDto>> Update(int id, [FromBody] MediaUpdateDto model)
        {
            if (!ModelState.IsValid)
                return ValidationProblem(ModelState);

            var existing = await _mediaService.GetByIdAsync(id);
            if (existing == null) return NotFound();

            if (!string.IsNullOrWhiteSpace(model.Name)) existing.Name = model.Name;
            if (model.IsDeleted.HasValue) existing.IsDeleted = model.IsDeleted.Value;

            var updated = await _mediaService.UpdateAsync(id, existing);
            var dto = new MediaReadDto
            {
                FileId = updated!.FileId,
                FilePath = updated.FilePath,
                Name = updated.Name,
                FileType = updated.FileType,
                FileSize = updated.FileSize,
                CreatedAt = updated.CreatedAt,
                UpdatedAt = updated.UpdatedAt
            };
            return Ok(dto);
        }

        /// <summary>
        /// Soft-deletes a media item.
        /// </summary>
        /// <param name="id">Media id.</param>
        [HttpDelete("{id:int}")]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Delete(int id)
        {
            var removed = await _mediaService.SoftDeleteAsync(id);
            if (!removed) return NotFound();
            return NoContent();
        }

        /// <summary>
        /// Uploads an image and creates a Media item in one step (multipart/form-data).
        /// </summary>
        [HttpPost("upload")]
        [Authorize(Roles = "Admin")]
        public async Task<ActionResult<MediaReadDto>> UploadMedia([FromForm] MediaUploadDto dto)
        {
            if (dto.File == null || dto.File.Length == 0)
                return BadRequest("No file uploaded.");

            // Allowed extensions
            var allowed = new[]
            {
                ".jpg", ".jpeg", ".png", ".gif", ".webp", // images
                ".mp4", ".mov", ".avi", ".wmv", ".webm", ".mkv" // videos
            }; 

            var ext = Path.GetExtension(dto.File.FileName).ToLowerInvariant();
            if (!allowed.Contains(ext))
                return BadRequest("Unsupported file type.");

            // Prepare folder
            var root = Path.Combine(Directory.GetCurrentDirectory(), "wwwroot", "uploads", "media");
            Directory.CreateDirectory(root);

            // Sanitized filename
            var baseName = Path.GetFileNameWithoutExtension(dto.File.FileName);
            var safeBase = new string(baseName.Where(char.IsLetterOrDigit).ToArray());
            if (string.IsNullOrWhiteSpace(safeBase)) safeBase = "media";

            var fileName = $"{safeBase}_{Guid.NewGuid():N}{ext}";
            var fullPath = Path.Combine(root, fileName);

            // Save file
            using (var fs = new FileStream(fullPath, FileMode.Create))
                await dto.File.CopyToAsync(fs);

            var relativePath = $"/uploads/media/{fileName}";

            // Determine file type for DB
            string type = ext switch
            {
                ".jpg" or ".jpeg" or ".png" or ".gif" or ".webp" => "image",
                ".mp4" or ".mov" or ".avi" or ".wmv" or ".webm" or ".mkv" => "video",
                _ => "other"
            };

            var entity = new Media
            {
                FilePath = relativePath,
                Name = dto.Name,
                FileSize = dto.File.Length,
                FileType = type
            };

            var created = await _mediaService.CreateAsync(entity);

            var read = new MediaReadDto
            {
                FileId = created.FileId,
                FilePath = created.FilePath,
                Name = created.Name,
                FileType = created.FileType,
                FileSize = created.FileSize,
                CreatedAt = created.CreatedAt,
                UpdatedAt = created.UpdatedAt
            };

            return CreatedAtAction(nameof(GetById), new { id = read.FileId }, read);
        }

    }
}
