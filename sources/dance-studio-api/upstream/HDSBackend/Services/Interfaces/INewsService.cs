using HDSBackend.Models;

namespace HDSBackend.Services.Interfaces
{
    /// <summary>
    /// Contract for news-related operations.
    /// </summary>
    public interface INewsService
    {
        Task<IEnumerable<News>> GetAllAsync();
        Task<News?> GetByIdAsync(int id);
        Task<News> CreateAsync(News news);
        Task<News?> UpdateAsync(int id, News news);
        Task<bool> SoftDeleteAsync(int id);
    }
}
