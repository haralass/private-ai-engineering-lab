using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;
using Microsoft.AspNetCore.Identity;

namespace HDSBackend.Models
{
    /// <summary>
    /// Application user entity extending ASP.NET Core Identity with int keys and extra profile fields.
    /// </summary>
    public class AppUser : IdentityUser<int>
    {
        // Optional display name in addition to Identity's UserName.. uuuhhh havent used this, will maybe remove
        [MaxLength(50)]
        public string? DisplayName { get; set; }

        // Keep your existing audit/soft-delete fields
        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public bool IsDeleted { get; set; }

        // 1:1 navigation to Student (ignore in JSON to prevent circular references / over-posting)
        [JsonIgnore]
        public Student? Student { get; set; }

        // Transient property to accept plain-text password during registration/update.
        // Identity stores the hashed password in PasswordHash; this property is NOT mapped to the DB.
        [NotMapped]
        [MaxLength(255)]
        public string? Password { get; set; }
    }
}