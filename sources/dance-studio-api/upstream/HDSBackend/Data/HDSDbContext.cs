using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Identity.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore;
using HDSBackend.Models;

namespace HDSBackend.Data
{
    /// <summary>
    /// Entity Framework Core database context for the HDS application, including Identity tables and domain entities.
    /// </summary>
    public class HDSDbContext : IdentityDbContext<AppUser, IdentityRole<int>, int>
    {
        public HDSDbContext(DbContextOptions<HDSDbContext> options)
            : base(options)
        {
        }

        // === DbSets (Tables) ===
        public DbSet<AcademicYear> AcademicYears { get; set; }
        public DbSet<Class> Classes { get; set; }
        public DbSet<Student> Students { get; set; }
        public DbSet<StudentClass> StudentClasses { get; set; }
        public DbSet<StudentAcademicYear> StudentAcademicYears { get; set; }

        public DbSet<Media> Media { get; set; }
        public DbSet<News> News { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder); // Identity tables

            // Unique (filtered) index on Student.Email when provided
            modelBuilder.Entity<Student>()
                .HasIndex(s => s.Email)
                .IsUnique()
                .HasFilter("[Email] IS NOT NULL");

            // === StudentClass (Many-to-Many Student <-> Class) ===
            modelBuilder.Entity<StudentClass>()
                .HasOne(sc => sc.Student)
                .WithMany(s => s.StudentClasses)
                .HasForeignKey(sc => sc.StudentId)
                .OnDelete(DeleteBehavior.Restrict);

            modelBuilder.Entity<StudentClass>()
                .HasOne(sc => sc.Class)
                .WithMany(c => c.StudentClasses)
                .HasForeignKey(sc => sc.ClassId)
                .OnDelete(DeleteBehavior.Restrict);

            // === Class -> AcademicYear ===
            modelBuilder.Entity<Class>()
                .HasOne(c => c.AcademicYear)
                .WithMany(y => y.Classes)
                .HasForeignKey(c => c.AcademicYearId)
                .OnDelete(DeleteBehavior.Restrict);

            // === Student -> AppUser (1:1) ===
            modelBuilder.Entity<Student>()
                .HasOne(s => s.User)
                .WithOne(u => u.Student)
                .HasForeignKey<Student>(s => s.UserId)
                .IsRequired(false)
                .OnDelete(DeleteBehavior.SetNull);

            // === StudentAcademicYear (Many-to-Many Student <-> AcademicYear) ===
            modelBuilder.Entity<StudentAcademicYear>()
                .HasIndex(x => new { x.StudentId, x.AcademicYearId })
                .IsUnique();

            modelBuilder.Entity<StudentAcademicYear>()
                .HasOne(x => x.Student)
                .WithMany() // could add collection on Student if needed
                .HasForeignKey(x => x.StudentId)
                .OnDelete(DeleteBehavior.Restrict);

            modelBuilder.Entity<StudentAcademicYear>()
                .HasOne(x => x.AcademicYear)
                .WithMany() // could add collection on AcademicYear if needed
                .HasForeignKey(x => x.AcademicYearId)
                .OnDelete(DeleteBehavior.Restrict);
        }
    }
}
