# Backend API Capabilities

The Harris Dance Studio Management Platform backend provides a full suite of RESTful API endpoints for managing users, students, academic years, classes, enrollments, media, and news content.
All endpoints are built with ASP.NET Core Web API, secured using JWT authentication and role-based authorization.

---

# Core Features

## Authentication & Authorization
- Register new users (`/api/Auth/register`)
- Login using username or email and receive a JWT (`/api/Auth/login`)
- Change password and admin password resets
- Role-based access (Admin / User)

---

## AppUser Management
- Get all users or user by ID
- Update user profile
- Soft-delete users
- Admin can promote/demote users to/from Admin role
- Get all non-admin or all admin users

---

## Student Management
- Create, read, update, delete student profiles
- Auto-link students to AppUsers by matching email
- Check if logged-in user has a student profile
- Create or update logged-in user’s student profile
- Search students by name, email, or academic year

---

## Academic Year Management
- Create, view, update, delete academic years
- Auto-create academic years based on the current date
- Get summaries with aggregated statistics (total students & classes per academic year)
- Preview next academic year (no database write)

---

## Class Management
- Create, update, delete classes
- Get all classes or class by ID
- Get classes filtered by academic year
- Export class list as CSV

---

## Student - Class Enrollment (Many-to-Many)
- Enroll a student into a class
- Remove a student from a class
- List all classes a student is enrolled in
- List all students in a class
- Enroll the logged-in user into a class via their student profile

---

## Student - Academic Year Management (Many-to-Many)
- Get all students linked to a specific academic year
- Get all academic years linked to a specific student
- Export students of an academic year as CSV
- View and update payment status (8 checkmarks) for each student/year

---

## Media Management
Supports images and videos.

- Upload image/video files
- Store file metadata (path, size, uploader, type)
- Get media files by ID or list all
- Soft-delete media items
- Automatic saving under `wwwroot/uploads/Media/...`

---

## News Management
- Create news items with title, body, and image
- Upload image and news data in one step (multipart/form-data)
- Update or delete news posts
- Get single or all news posts
- Serve images directly via `wwwroot/uploads/News`

---

# Static File Handling

The backend serves uploaded files via: `wwwroot/uploads/folder/filename`

This allows the frontend to load images or media directly without additional API calls.

---

# Security Features
- JWT-based authentication
- Role enforcement for admin-only actions
- Server-side validation via ModelState
- Safe file handling (jpg, png, mp4, etc.)

---

# Tech Stack
- .NET 8.0
- ASP.NET Core 8 Web API
- Entity Framework Core
- SQL Server (Express)
- Swagger / OpenAPI
- ASP.NET Identity 


