-- Nexus Club — SQLite Schema
-- Run via: python3 python/setup_db.py

CREATE TABLE IF NOT EXISTS events (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT NOT NULL,
    description TEXT,
    event_date  TEXT NOT NULL,
    event_time  TEXT,
    location    TEXT,
    category    TEXT DEFAULT 'general',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    title        TEXT NOT NULL,
    description  TEXT,
    tech_stack   TEXT,
    github_url   TEXT,
    demo_url     TEXT,
    image_url    TEXT,
    is_top       INTEGER DEFAULT 0,
    team_members TEXT,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blog_posts (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    title      TEXT NOT NULL,
    content    TEXT,
    author     TEXT,
    category   TEXT DEFAULT 'general',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS materials (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    title        TEXT NOT NULL,
    description  TEXT,
    file_url     TEXT,
    external_url TEXT,
    category     TEXT DEFAULT 'general',
    difficulty   TEXT DEFAULT 'beginner',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Gallery albums: one card per event on the public page.
-- Thumbnail is a locally uploaded image file (stored in assets/uploads/gallery/).
-- drive_folder_url is a shared Google Drive folder — clicking the album opens it.
CREATE TABLE IF NOT EXISTS gallery_albums (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    event_name       TEXT NOT NULL,
    description      TEXT,
    thumbnail        TEXT,   -- filename only, e.g. "hackathon2025.jpg" stored in assets/uploads/gallery/
    drive_folder_url TEXT,   -- Google Drive folder share link
    created_at       DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS gallery_highlights (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    album_id   INTEGER REFERENCES gallery_albums(id) ON DELETE CASCADE,
    photo_url  TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contact_requests (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    name         TEXT NOT NULL,
    email        TEXT NOT NULL,
    message      TEXT,
    request_type TEXT DEFAULT 'general',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- NEW: Teams table
CREATE TABLE IF NOT EXISTS teams (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    team_no    TEXT NOT NULL UNIQUE,
    team_name  TEXT NOT NULL,
    points     INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- NEW: Members table
CREATE TABLE IF NOT EXISTS members (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT NOT NULL,
    email       TEXT,
    role        TEXT DEFAULT 'member',  -- hod | faculty_coordinator | student_coordinator | lead | member
    designation TEXT,                   -- display title, e.g. "Associate Professor, CSE"
    photo_url   TEXT,                   -- direct image URL
    team_id     INTEGER REFERENCES teams(id) ON DELETE SET NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Migration note: if upgrading an existing DB run:
--   ALTER TABLE members ADD COLUMN designation TEXT;
--   ALTER TABLE members ADD COLUMN photo_url TEXT;

-- ── Seed Data ──────────────────────────────────────────

-- SEEDING TEAMS TABLE
-- SEEDING TEAMS TABLE
INSERT INTO teams (team_no, team_name) VALUES
('NX26101', 'Team NX26101'), ('NX26102', 'Team NX26102'), ('NX26103', 'Team NX26103'), ('NX26104', 'Team NX26104'), ('NX26105', 'Team NX26105'),
('NX26106', 'Team NX26106'), ('NX26107', 'Team NX26107'), ('NX26108', 'Team NX26108'), ('NX26109', 'Team NX26109'), ('NX26110', 'Team NX26110'),
('NX26111', 'Team NX26111'), ('NX26112', 'Team NX26112'), ('NX26113', 'Team NX26113'), ('NX26114', 'Team NX26114'), ('NX26115', 'Team NX26115'),
('NX26116', 'Team NX26116'), ('NX26117', 'Team NX26117'), ('NX26118', 'Team NX26118'), ('NX26119', 'Team NX26119'), ('NX26120', 'Team NX26120'),
('NX26121', 'Team NX26121'), ('NX26122', 'Team NX26122'), ('NX26123', 'Team NX26123'), ('NX26124', 'Team NX26124'), ('NX26125', 'Team NX26125'),
('NX26126', 'Team NX26126'), ('NX26127', 'Team NX26127'), ('NX26128', 'Team NX26128'), ('NX26129', 'Team NX26129'), ('NX26130', 'Team NX26130'),
('NX26131', 'Team NX26131'), ('NX26132', 'Team NX26132'), ('NX26133', 'Team NX26133'), ('NX26134', 'Team NX26134'), ('NX26135', 'Team NX26135'),
('NX26136', 'Team NX26136'), ('NX26137', 'Team NX26137'), ('NX26138', 'Team NX26138'), ('NX26139', 'Team NX26139'), ('NX26140', 'Team NX26140'),
('NX26141', 'Team NX26141');

-- SEEDING MEMBERS TABLE (Leads & Members)
-- Role 'lead' is assigned to the Team Head column with provided emails.
-- Role 'member' is assigned to the Team Members column.

INSERT INTO members (name, email, role, team_id) VALUES
-- Team 1
('Pooja', 'kandalapooja8@gmail.com', 'lead', 1),
('Manasa', NULL, 'member', 1),
-- Team 2
('Sahithi', 'sahithigoudaithagoni@gmail.com', 'lead', 2),
('Vani', NULL, 'member', 2),
('Yashasri', NULL, 'member', 2),
-- Team 3
('Neha', 'mahankalineha3@gmail.com', 'lead', 3),
('Geetha', NULL, 'member', 3),
-- Team 4
('Devendranadh', 'rdevendrayadav45@gmail.com', 'lead', 4),
('Mokshith', NULL, 'member', 4),
('Hemanth', NULL, 'member', 4),
-- Team 5
('Varkala Archana', 'archanavarkala2005@gmail.com', 'lead', 5),
('Sowmya', NULL, 'member', 5),
('Pooja', NULL, 'member', 5),
('Siri', NULL, 'member', 5),
-- Team 6
('Mithil Manchukonda', 'mithilmanchukonda@gmail.com', 'lead', 6),
('Shiva Manoj', NULL, 'member', 6),
('Satwik', NULL, 'member', 6),
-- Team 7
('A. Sahithi', 'sahithigoudaithagoni@gmail.com', 'lead', 7),
('D. Yashasri', NULL, 'member', 7),
('G. Vani', NULL, 'member', 7),
-- Team 8
('B. Arunkumar', 'bandiarunkumar0@gmail.com', 'lead', 8),
('Raghupati Shashank', NULL, 'member', 8),
('Mohammad Haneef', NULL, 'member', 8),
-- Team 9
('Yashwanth reddy', 'billapatiyashwanthreddy@gmail.com', 'lead', 9),
('Vamshi Krishna', NULL, 'member', 9),
('vaibhav', NULL, 'member', 9),
-- Team 10
('Ramya', 'ramyakandagatla5@gmail.com', 'lead', 10),
('K. Rithika', NULL, 'member', 10),
('D. Anushka', NULL, 'member', 10),
('B. Vaishnavi', NULL, 'member', 10),
-- Team 11
('Hasini', 'katamhasini29@gmail.com', 'lead', 11),
('Sunaina', NULL, 'member', 11),
('Poojitha', NULL, 'member', 11),
('Siri', NULL, 'member', 11),
-- Team 12
('Miryala Akshitha', 'miryalaakshitha06@gmail.com', 'lead', 12),
('Thanniru Sravana Lakshmi', NULL, 'member', 12),
('Surakanti Sowmya', NULL, 'member', 12),
('M. Harshitha', NULL, 'member', 12),
-- Team 13
('sandeep', 'pinintisandeepreddy123@gmail.com', 'lead', 13),
('mani varan', NULL, 'member', 13),
('ram charan', NULL, 'member', 13),
('S. Shashikiran', NULL, 'member', 13),
-- Team 14
('P. Harika', 'pittalaharika25@gmail.com', 'lead', 14),
('Md Saniya', NULL, 'member', 14),
('M keerthana', NULL, 'member', 14),
('P vasavi', NULL, 'member', 14),
-- Team 15
('Ghayas', 'ghayas089@gmail.com', 'lead', 15),
('Raviraj', NULL, 'member', 15),
('Anirudh', NULL, 'member', 15),
-- Team 16
('Sridhar', 'tannerusridhar18@gmail.com', 'lead', 16),
('PADAKANTI VARSHA', NULL, 'member', 16),
-- Team 17
('Bhuvana', 'valletibhuvana1414@gmail.com', 'lead', 17),
('Manaswini', NULL, 'member', 17),
('Jyothika', NULL, 'member', 17),
('Puneshwari', NULL, 'member', 17),
-- Team 18
('A. Udayasri', 'kghantasala2126@gmail.com', 'lead', 18),
('M. Chathurya', NULL, 'member', 18),
('D. Jasmitha', NULL, 'member', 18),
('G. Rama Kousalya', NULL, 'member', 18),
-- Team 19
('Praneeth Reddy', 'praneeth.reddypbr@gmail.com', 'lead', 19),
('T. Srikanth', NULL, 'member', 19),
('J. Naeem', NULL, 'member', 19),
-- Team 20
('M Tejdeep', 'munjampallytejdeep@gmail.com', 'lead', 20),
('Pavan Kalyan', NULL, 'member', 20),
('Dharani', NULL, 'member', 20),
('Bhargavi', NULL, 'member', 20),
-- Team 21
('Sandhya', 'rasagnakola8588@gmail.com', 'lead', 21),
('Akshaya', NULL, 'member', 21),
-- Team 22
('Yashwanth', 'yashwanthreddykoppula@gmail.com', 'lead', 22),
('K. Ajay Sai', NULL, 'member', 22),
('K. Mahathi', NULL, 'member', 22),
('K. Srija', NULL, 'member', 22),
-- Team 23
('Tanvi', 'ntanvisri3759@gmail.com', 'lead', 23),
('Rishitha', NULL, 'member', 23),
('Charitha', NULL, 'member', 23),
('Yuvana', NULL, 'member', 23),
-- Team 24
('OgguShivani', 'oggushivani3@gmail.com', 'lead', 24),
('Joshua', NULL, 'member', 24),
('Ram', NULL, 'member', 24),
('Pravalika', NULL, 'member', 24),
-- Team 25
('Uday Kiran', 'udaykirantanniru02@gmail.com', 'lead', 25),
('Advaith Chalam', NULL, 'member', 25),
('Sai Sathvik', NULL, 'member', 25),
('Sangala Vamshidhar', NULL, 'member', 25),
-- Team 26
('Mandava Ramya', 'ramyamandava15@gmail.com', 'lead', 26),
('Bhavya Anantha sai', NULL, 'member', 26),
('Sruthi Swaraj', NULL, 'member', 26),
('Myle Tejaswini', NULL, 'member', 26),
-- Team 27
('Pala Aishwarya', 'palaaishwarya070@gmail.com', 'lead', 27),
('Chinthala Navya', NULL, 'member', 27),
('Mattaparthi Varsha', NULL, 'member', 27),
('Gundepuri Srivani', NULL, 'member', 27),
-- Team 28
('Appala Shivani', 'shivaniappala999@gmail.com', 'lead', 28),
('Anthati Ruthika', NULL, 'member', 28),
('Samghna', NULL, 'member', 28),
-- Team 29
('Gumma Sanjana', 'sanjanagumma642@gmail.com', 'lead', 29),
('Sri Hasini', NULL, 'member', 29),
('Yadagani Sharanya', NULL, 'member', 29),
('Deva Naik', NULL, 'member', 29),
-- Team 30
('Sudeeksha', 'sudheekshasamineni2006@gmail.com', 'lead', 30),
('Pola Varshitha', NULL, 'member', 30),
('Aasritha Puli', NULL, 'member', 30),
('Nitish', NULL, 'member', 30),
-- Team 31
('N. Mrudula', 'nalabothumrudula@gmail.com', 'lead', 31),
('Cheguri sahasra', NULL, 'member', 31),
('Aineni Hansika', NULL, 'member', 31),
('Vishwa Sree', NULL, 'member', 31),
-- Team 32
('Noorjahan', 'shaherbanonoorjahan@gmail.com', 'lead', 32),
('Chandana Reddy', NULL, 'member', 32),
-- Team 33
('Rachana', 'rachanadarapaneni@gmail.com', 'lead', 33),
('Sindhuja', NULL, 'member', 33),
('Manaswini', NULL, 'member', 33),
('Nikhitha', NULL, 'member', 33),
-- Team 34
('Karthiksai', 'karnatikarthiksai@gmail.com', 'lead', 34),
('Karthik', NULL, 'member', 34),
('lokesh', NULL, 'member', 34),
('Dileep', NULL, 'member', 34),
-- Team 35
('G. Rishitha', 'kshirisha507@gmail.com', 'lead', 35),
('G. Soumya Reddy', NULL, 'member', 35),
('K. Shirisha', NULL, 'member', 35),
-- Team 36
('Kote Vishal', 'kotevishal677@gmail.com', 'lead', 36),
('Kovidh', NULL, 'member', 36),
('sreesha', NULL, 'member', 36),
-- Team 37
('Divya kusuru', 'divyakusuru@gmail.com', 'lead', 37),
('Ithagoni Jyothsna sri', NULL, 'member', 37),
('B. Nandini', NULL, 'member', 37),
-- Team 38
('Pramila', 'mariyasadaf107@gmail.com', 'lead', 38),
('Mariya Sadaf', NULL, 'member', 38),
('pranavi', NULL, 'member', 38),
-- Team 39
('Pranav Sri Datta', 'oruganti.pranav.datta@gmail.com', 'lead', 39),
('B. Ved Srish', NULL, 'member', 39),
('N. Yashwanth Reddy', NULL, 'member', 39),
('N. Charan Tej', NULL, 'member', 39),
-- Team 40
('R. Poojitha', 'poojitharapolu09@gmail.com', 'lead', 40),
('K. Rakshitha', NULL, 'member', 40),
('L. Ashwini', NULL, 'member', 40),
-- Team 41
('V. Pooja', 'varkalapooja19@gmail.com', 'lead', 41),
('Sana Afreen', NULL, 'member', 41),
('V. Swathi', NULL, 'member', 41);