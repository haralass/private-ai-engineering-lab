using Microsoft.EntityFrameworkCore;
using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Services.Interfaces;

namespace HDSBackend.Services
{
    /// <summary>
    /// Data access service for managing News entities.
    /// </summary>
    public class NewsService : INewsService
    {
        private readonly HDSDbContext _context;

        public NewsService(HDSDbContext context) => _context = context;

        public async Task<IEnumerable<News>> GetAllAsync()
        {
            return await _context.News
                .AsNoTracking()
                .Where(n => !n.IsDeleted)
                .OrderByDescending(n => n.CreatedAt)
                .ToListAsync();
        }

        public async Task<News?> GetByIdAsync(int id)
        {
            return await _context.News
                .AsNoTracking()
                .FirstOrDefaultAsync(n => n.NewsId == id && !n.IsDeleted);
        }

        public async Task<News> CreateAsync(News news)
        {
            if (news == null) throw new ArgumentNullException(nameof(news));

            news.CreatedAt = DateTime.UtcNow;
            news.IsDeleted = false;

            await _context.News.AddAsync(news);
            await _context.SaveChangesAsync();
            return news;
        }

        public async Task<News?> UpdateAsync(int id, News news)
        {
            if (news == null) throw new ArgumentNullException(nameof(news));

            var existing = await _context.News.FirstOrDefaultAsync(n => n.NewsId == id && !n.IsDeleted);
            if (existing == null) return null;

            existing.ImagePath = !string.IsNullOrWhiteSpace(news.ImagePath) ? news.ImagePath : existing.ImagePath;
            existing.Title = !string.IsNullOrWhiteSpace(news.Title) ? news.Title : existing.Title;
            existing.Body = !string.IsNullOrWhiteSpace(news.Body) ? news.Body : existing.Body;
            existing.PublishDate = news.PublishDate != default ? news.PublishDate : existing.PublishDate;
            existing.IsPublished = news.IsPublished;
            existing.UpdatedAt = DateTime.UtcNow;

            await _context.SaveChangesAsync();
            return existing;
        }

        public async Task<bool> SoftDeleteAsync(int id)
        {
            var existing = await _context.News.FirstOrDefaultAsync(n => n.NewsId == id && !n.IsDeleted);
            if (existing == null) return false;

            existing.IsDeleted = true;
            existing.UpdatedAt = DateTime.UtcNow;
            await _context.SaveChangesAsync();
            return true;
        }
    }
}
