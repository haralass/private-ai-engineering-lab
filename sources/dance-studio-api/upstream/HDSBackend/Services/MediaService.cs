using Microsoft.EntityFrameworkCore;
using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Services
{
    /// <summary>
    /// Data access service for managing Media entities.
    /// </summary>
    public class MediaService : IMediaService
    {
        private readonly HDSDbContext _context;

        public MediaService(HDSDbContext context) => _context = context;

        public async Task<IEnumerable<Media>> GetAllAsync()
        {
            return await _context.Media
                .AsNoTracking()
                .Where(m => !m.IsDeleted)
                .ToListAsync();
        }

        public async Task<Media?> GetByIdAsync(int id)
        {
            return await _context.Media
                .AsNoTracking()
                .FirstOrDefaultAsync(m => m.FileId == id && !m.IsDeleted);
        }

        public async Task<Media> CreateAsync(Media media)
        {
            if (media == null) throw new ArgumentNullException(nameof(media));

            media.CreatedAt = DateTime.UtcNow;
            media.IsDeleted = false;

            await _context.Media.AddAsync(media);
            await _context.SaveChangesAsync();

            return media;
        }

        public async Task<Media?> UpdateAsync(int id, Media media)
        {
            if (media == null) throw new ArgumentNullException(nameof(media));

            var existing = await _context.Media.FirstOrDefaultAsync(m => m.FileId == id && !m.IsDeleted);
            if (existing == null) return null;

            // update allowed fields (match names from Models\Media.cs)
            existing.FilePath = media.FilePath;
            existing.Name = media.Name;
            existing.FileType = media.FileType;
            existing.FileSize = media.FileSize;
            existing.UpdatedAt = DateTime.UtcNow;

            await _context.SaveChangesAsync();
            return existing;
        }

        public async Task<bool> SoftDeleteAsync(int id)
        {
            var existing = await _context.Media.FirstOrDefaultAsync(m => m.FileId == id && !m.IsDeleted);
            if (existing == null) return false;

            existing.IsDeleted = true;
            existing.UpdatedAt = DateTime.UtcNow;

            await _context.SaveChangesAsync();
            return true;
        }
    }
}
