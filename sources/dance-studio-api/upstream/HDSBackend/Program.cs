using HDSBackend.Data;
using HDSBackend.Models;
using HDSBackend.Services;
using HDSBackend.Services.Interfaces;
using Microsoft.EntityFrameworkCore;
using Microsoft.AspNetCore.Identity;
using HDSBackend.Models.Dto;
using Microsoft.OpenApi.Models;
using System.Reflection;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.IdentityModel.Tokens;
using System.Text;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddDbContext<HDSDbContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("DefaultConnection")));

// Identity (without cookie UI)
builder.Services.AddIdentity<AppUser, IdentityRole<int>>(options =>
{
    options.Password.RequiredLength = 6;
    options.Password.RequireDigit = true;
    options.Password.RequireUppercase = true;
    options.User.RequireUniqueEmail = true; // require unique emails
})
.AddEntityFrameworkStores<HDSDbContext>()
.AddDefaultTokenProviders();

// JWT Authentication
var jwtSection = builder.Configuration.GetSection("Jwt"); //read jwt settings from appsettings
var signingKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(jwtSection["Key"]!)); // secret key to cryprographic key

// Configure JWT authentication
builder.Services.AddAuthentication(options =>
{
    options.DefaultAuthenticateScheme = JwtBearerDefaults.AuthenticationScheme;
    options.DefaultChallengeScheme = JwtBearerDefaults.AuthenticationScheme;
})
.AddJwtBearer(options =>    // configure JWT Bearer options, validation logic
{
    // Require HTTPS metadata outside development environments, .NET knows through launchSettings.json and environment
    options.RequireHttpsMetadata = !builder.Environment.IsDevelopment();
    options.SaveToken = true;
    options.TokenValidationParameters = new TokenValidationParameters
    {
        ValidateIssuer = true,
        ValidateAudience = true,
        ValidateIssuerSigningKey = true,
        ValidateLifetime = true,
        ValidIssuer = jwtSection["Issuer"],
        ValidAudience = jwtSection["Audience"],
        IssuerSigningKey = signingKey,
        ClockSkew = TimeSpan.FromMinutes(1)
    };
});

builder.Services.AddControllers()
    .AddJsonOptions(options =>
        options.JsonSerializerOptions.ReferenceHandler = System.Text.Json.Serialization.ReferenceHandler.IgnoreCycles);

builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll",
        policy => policy.AllowAnyOrigin()
                        .AllowAnyHeader()
                        .AllowAnyMethod());
});

builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen(options =>
{
    options.SwaggerDoc("v1", new OpenApiInfo
    {
        Title = "HDS Backend API",
        Version = "v1"
    });

    // Include XML comments for controllers and models
    var xmlFile = $"{Assembly.GetExecutingAssembly().GetName().Name}.xml";
    var xmlPath = Path.Combine(AppContext.BaseDirectory, xmlFile);
    if (File.Exists(xmlPath))
    {
        options.IncludeXmlComments(xmlPath, includeControllerXmlComments: true);
    }

    // Swagger JWT security definition
    options.AddSecurityDefinition("Bearer", new OpenApiSecurityScheme
    {
        Name = "Authorization",
        Type = SecuritySchemeType.Http,
        Scheme = "bearer",
        BearerFormat = "JWT",
        In = ParameterLocation.Header,
        Description = "JWT Authorization header using the Bearer scheme. Example: 'Bearer {token}'"
    });
    options.AddSecurityRequirement(new OpenApiSecurityRequirement
    {
        {
            new OpenApiSecurityScheme
            {
                Reference = new OpenApiReference
                {
                    Type = ReferenceType.SecurityScheme,
                    Id = "Bearer"
                }
            },
            new string[] {}
        }
    });
});

builder.Services.AddScoped<IStudentService, StudentService>();
builder.Services.AddScoped<IClassService, ClassService>();
builder.Services.AddScoped<INewsService, NewsService>();
builder.Services.AddScoped<IMediaService, MediaService>();
builder.Services.AddScoped<IAcademicYearService, AcademicYearService>();
builder.Services.AddScoped<IStudentClassService, StudentClassService>();
builder.Services.AddScoped<IStudentAcademicYearService, StudentAcademicYearService>();

var app = builder.Build();

app.UseStaticFiles(); // Enable serving static files from wwwroot

app.UseSwagger();
app.UseSwaggerUI();

app.UseHttpsRedirection();

app.UseCors("AllowAll");

app.UseAuthentication();
app.UseAuthorization();

app.MapControllers();

// Seed Identity roles and a default admin user (run once on startup)
using (var scope = app.Services.CreateScope())
{
    var roleManager = scope.ServiceProvider.GetRequiredService<RoleManager<IdentityRole<int>>>();
    var userManager = scope.ServiceProvider.GetRequiredService<UserManager<AppUser>>();
    var context = scope.ServiceProvider.GetRequiredService<HDSDbContext>();

    // Ensure DB is up-to-date
    await context.Database.MigrateAsync();

    // Ensure Admin role exists
    const string adminRole = "Admin";
    if (!await roleManager.RoleExistsAsync(adminRole))
    {
        await roleManager.CreateAsync(new IdentityRole<int>
        {
            Name = adminRole,
            NormalizedName = adminRole.ToUpperInvariant()
        });
    }

    // Create or get default admin user (configurable via appsettings or secrets later)
    var adminUserName = builder.Configuration["Admin:UserName"] ?? "admin";
    var adminEmail = builder.Configuration["Admin:Email"] ?? "admin@example.com";
    var adminPassword = builder.Configuration["Admin:Password"] ?? "Change_this_dev_only_123!";

    var adminUser = await userManager.FindByNameAsync(adminUserName);
    if (adminUser == null)
    {
        adminUser = new AppUser
        {
            UserName = adminUserName,
            Email = adminEmail,
            DisplayName = "Administrator",
            CreatedAt = DateTime.UtcNow,
            IsDeleted = false,
            EmailConfirmed = true
        };

        var createResult = await userManager.CreateAsync(adminUser, adminPassword);
        if (!createResult.Succeeded)
        {
            throw new InvalidOperationException(string.Join("; ", createResult.Errors.Select(e => e.Description)));
        }
    }

    // Ensure user is in Admin role
    if (!await userManager.IsInRoleAsync(adminUser, adminRole))
    {
        var addRoleResult = await userManager.AddToRoleAsync(adminUser, adminRole);
        if (!addRoleResult.Succeeded)
        {
            throw new InvalidOperationException(string.Join("; ", addRoleResult.Errors.Select(e => e.Description)));
        }
    }
}

app.Run();
