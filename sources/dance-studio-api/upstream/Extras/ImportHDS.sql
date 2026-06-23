INSERT INTO [HDS].[dbo].[AspNetUsers] 
(Username, DisplayName, Email, PasswordHash, AccessFailedCount, IsDeleted, EmailConfirmed, PhoneNumberConfirmed, TwoFactorEnabled, LockoutEnabled,
CreatedAt, NormalizedUsername, NormalizedEmail, SecurityStamp, ConcurrencyStamp)
VALUES
('Joe', 'Joe', 'Joe@hds.com', 'Abc-123', 0, 0, 0, 0, 0, 0, GETDATE(), 'JOE', 'JOE@HDS.COM', 'Placeholder', 'Placeholder'),
('Alice', 'Alice Smith', 'Alice@hds.com', 'Abc-123', 0, 0, 0, 0, 0, 0, GETDATE(), 'ALICE', 'ALICE@HDS.COM', 'Placeholder', 'Placeholder'),
('Michael', 'Michael Chan', 'Michael@hds.com', 'Abc-123', 0, 0, 0, 0, 0, 0, GETDATE(), 'MICHAEL', 'MICHAEL@HDS.COM', 'Placeholder', 'Placeholder'),
('Rosa', 'Rosa Garcia', 'Rosa@hds.com', 'Abc-123', 0, 0, 0, 0, 0, 0, GETDATE(), 'ROSA', 'ROSA@HDS.COM', 'Placeholder', 'Placeholder'),
('Kiran', 'Kiran Patel', 'Kiran@hds.com', 'Abc-123', 0, 0, 0, 0, 0, 0, GETDATE(), 'KIRAN', 'KIRAN@HDS.COM', 'Placeholder', 'Placeholder');


INSERT INTO [HDS].[dbo].[AcademicYears] (Year, StartDate, EndDate, IsActive, CreatedAt, UpdatedAt, IsDeleted)
VALUES
('2021', '2021-01-01', '2021-12-30', 0, GETDATE(), NULL, 0),
('2022', '2022-01-01', '2022-12-30', 0, GETDATE(), NULL, 0),
('2023', '2023-01-01', '2023-12-30', 0, GETDATE(), NULL, 0),
('2024', '2024-01-01', '2024-12-30', 0, GETDATE(), NULL, 0),
('2025', '2025-01-01', '2025-12-30', 0, GETDATE(), NULL, 0);

INSERT INTO [HDS].[dbo].[Classes] 
(ClassName, Level, InstructorFirstName, InstructorLastName, DayTime, Capacity, CurrentEnrollment, AcademicYearId, CreatedAt, UpdatedAt, IsDeleted)
VALUES
('Ballet Basics', 'Beginner', 'Anna', 'Smith', 'Monday 16:00', 20, 15, 1, GETDATE(), GETDATE(), 0),
('Jazz Techniques', 'Intermediate', 'Michael', 'Chan', 'Wednesday 18:00', 25, 20, 2, GETDATE(), GETDATE(), 0),
('Hip-Hop Groove', 'Advanced', 'Rosa', 'Garcia', 'Friday 17:30', 30, 28, 3, GETDATE(), GETDATE(), 0),
('Salsa Moves', 'Beginner', 'Kiran', 'Patel', 'Tuesday 19:00', 20, 18, 4, GETDATE(), GETDATE(), 0),
('Contemporary Flow', 'Intermediate', 'Laura', 'Walker', 'Thursday 16:30', 25, 22, 5, GETDATE(), GETDATE(), 0);

INSERT INTO [HDS].[dbo].[Students] 
(UserID, Email, FirstName, LastName, BirthDate, EmergencyContact, EmergencyPhone, Allergies, IsArchived, ArchivedAt, CreatedAt, UpdatedAt, IsDeleted)
VALUES
(2, 'Alice@hds.com', 'Alice', 'Smith', '2012-08-20', 'Bob Smith', '555-5678', 'Peanuts', 0, NULL, GETDATE(), GETDATE(), 0),
(3, 'Michael@hds.com', 'Michael', 'Chan', '2009-11-30', 'Linda Chan', '555-4321', 'Gluten', 0, NULL, GETDATE(), GETDATE(), 0),
(4, 'Rosa@hds.com', 'Rosa', 'Garcia', '2011-06-14', 'Carlos Garcia', '555-2468', NULL, 0, NULL, GETDATE(), GETDATE(), 0),
(NULL, 'Tom.Brown@hds.com', 'Tom', 'Brown', '2011-03-05', 'Mary Brown', '555-8765', NULL, 0, NULL, GETDATE(), GETDATE(), 0),
(NULL, 'Sara.Roberts@hds.com', 'Sara', 'Roberts', '2013-07-15', 'Kevin Roberts', '555-9876', NULL, 0, NULL, GETDATE(), GETDATE(), 0);

INSERT INTO [HDS].[dbo].[StudentClasses] 
(StudentID, ClassID, EnrollmentDate, EnrollmentStatus, CreatedAt, UpdatedAt, IsDeleted)
VALUES
(1, 1, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0),
(2, 2, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0),
(3, 3, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0),
(4, 4, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0),
(5, 5, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0),
(1, 2, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0), 
(2, 4, GETDATE(), 'Enrolled', GETDATE(), GETDATE(), 0);  



INSERT INTO [HDS].[dbo].[Media] (FilePath, Name, Description, UploadedByUserId, FileType, FileSize, CreatedAt, UpdatedAt, IsDeleted)
VALUES
('uploads/videos/ballet_intro.mp4', 'Ballet Intro', 'Introductory ballet class video.', 1, 'video/mp4', 10485760, GETDATE(), GETDATE(), 0),
('uploads/images/jazz_class1.jpg', 'Jazz Class 1', 'Students performing jazz warm-up exercises.', 1, 'image/jpeg', 512000, GETDATE(), GETDATE(), 0),
('uploads/videos/hiphop_routine.mp4', 'Hip-Hop Routine', 'Hip-hop dance routine from the advanced class.', 1, 'video/mp4', 15728640, GETDATE(), GETDATE(), 0),
('uploads/images/salsa_event.jpg', 'Salsa Event', 'Photo from the summer salsa showcase.', 1, 'image/jpeg', 307200, GETDATE(), GETDATE(), 0),
('uploads/videos/contemporary_recital.mp4', 'Contemporary Recital', 'Full video of the contemporary dance recital.', 1, 'video/mp4', 20971520, GETDATE(), GETDATE(), 0);

INSERT INTO [HDS].[dbo].[News] (FilePath, Name, Description, AnnouncedByUserId, PublishDate, IsPublished, CreatedAt, UpdatedAt, IsDeleted)
VALUES
('uploads/news/summer_recital.jpg', 'Summer Recital Announced', 'The summer recital schedule has been released. Register your students now!', 1, GETDATE(), 1, GETDATE(), GETDATE(), 0),
('uploads/news/ballet_workshop.pdf', 'Ballet Workshop', 'Join our intensive ballet workshop this weekend. Open to all levels.', 1, GETDATE(), 1, GETDATE(), GETDATE(), 0),
('uploads/news/hiphop_competition.jpg', 'Hip-Hop Competition', 'Local hip-hop dance competition announced for next month.', 1, GETDATE(), 1, GETDATE(), GETDATE(), 0),
('uploads/news/salsa_party.jpg', 'Salsa Night Party', 'Salsa night event for students and families. Don’t miss it!', 1, GETDATE(), 1, GETDATE(), GETDATE(), 0),
('uploads/news/contemporary_showcase.mp4', 'Contemporary Showcase', 'Video announcement of the upcoming contemporary dance showcase.', 1, GETDATE(), 1, GETDATE(), GETDATE(), 0);



SELECT * FROM [HDS].[dbo].[AspNetUsers]
SELECT * FROM [HDS].[dbo].[AcademicYears]
SELECT * FROM [HDS].[dbo].[Classes]
SELECT * FROM [HDS].[dbo].[Students]
SELECT * FROM [HDS].[dbo].[StudentClasses]
SELECT * FROM [HDS].[dbo].[Media]
SELECT * FROM [HDS].[dbo].[News]