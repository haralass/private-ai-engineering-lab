using HDSBackend.Models;

namespace HDSBackend.Services.Interfaces
{
    /// <summary>
    /// Contract for media-related operations.
    /// </summary>
    public interface IMediaService
    {
        Task<IEnumerable<Media>> GetAllAsync();
        Task<Media?> GetByIdAsync(int id);
        Task<Media> CreateAsync(Media media);
        Task<Media?> UpdateAsync(int id, Media media);
        Task<bool> SoftDeleteAsync(int id);
    }
}
