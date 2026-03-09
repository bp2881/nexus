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

CREATE TABLE IF NOT EXISTS gallery (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT NOT NULL,
    description TEXT,
    image_url   TEXT,
    event_name  TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
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
    team_no    INTEGER NOT NULL UNIQUE,
    team_name  TEXT NOT NULL,
    points     INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- NEW: Members table
CREATE TABLE IF NOT EXISTS members (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    name       TEXT NOT NULL,
    email      TEXT,
    role       TEXT DEFAULT 'member',
    team_id    INTEGER REFERENCES teams(id) ON DELETE SET NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ── Seed Data ──────────────────────────────────────────

INSERT OR IGNORE INTO events (title, description, event_date, event_time, location, category) VALUES
('Hackathon 2025', 'Annual 24-hour coding challenge!', '2025-04-15', '09:00 AM', 'CS Block, Room 301', 'hackathon'),
('Web Dev Workshop', 'Hands-on: React, Node.js and REST APIs', '2025-03-28', '05:00 PM', 'Lab 2, Tech Building', 'workshop'),
('AI/ML Talk', 'Guest lecture on LLMs and practical NLP', '2025-03-20', '04:00 PM', 'Seminar Hall A', 'talk'),
('Open Source Drive', 'Contribute to real open source projects', '2025-04-05', '03:00 PM', 'CS Block, Room 201', 'session');

INSERT OR IGNORE INTO projects (title, description, tech_stack, github_url, is_top, team_members) VALUES
('CampusConnect', 'Real-time event notification app for college students', 'React, Node.js, WebSockets, MongoDB', 'https://github.com/example/campusconnect', 1, 'Arjun, Priya, Ravi'),
('SmartAttend', 'Face recognition based attendance system', 'Python, OpenCV, Flask, SQLite', 'https://github.com/example/smartattend', 1, 'Sneha, Kiran'),
('CodeQuest', 'Gamified coding challenge platform for beginners', 'PHP, MySQL, Bootstrap', 'https://github.com/example/codequest', 1, 'Meera, Rohan, Ananya'),
('BudgetBot', 'Telegram bot to track college expenses using NLP', 'Python, Telegram API, spaCy', 'https://github.com/example/budgetbot', 0, 'Dev, Sai');

INSERT OR IGNORE INTO materials (title, description, external_url, category, difficulty) VALUES
('Python Crash Course', 'Beginner guide to Python', 'https://docs.python.org/3/tutorial/', 'python', 'beginner'),
('Git & GitHub Essentials', 'Learn version control from scratch', 'https://docs.github.com/en/get-started', 'tools', 'beginner'),
('Data Structures & Algorithms', 'DSA patterns for competitive programming', 'https://cp-algorithms.com/', 'dsa', 'intermediate'),
('React Official Docs', 'Deep dive into React hooks, state & effects', 'https://react.dev', 'web', 'intermediate'),
('Machine Learning Roadmap', 'From linear regression to deep learning', 'https://www.fast.ai/', 'ml', 'advanced');

INSERT OR IGNORE INTO blog_posts (title, content, author, category) VALUES
('Welcome to Nexus!', 'We are thrilled to launch our club website. This is a space for students passionate about coding, open source, and building things that matter.', 'Admin', 'announcement'),
('How We Built CampusConnect in 24 Hours', 'At our last hackathon, team CampusConnect tackled the problem of students missing events. They shipped a working MVP now used by 200+ students.', 'Arjun K', 'project'),
('Top 5 Coding Resources for Beginners', 'Starting your coding journey can feel overwhelming. Here are five resources our club recommends: freeCodeCamp, Python.org, The Odin Project, CS50, and our own materials!', 'Priya M', 'guide');

INSERT OR IGNORE INTO teams (team_no, team_name, points) VALUES
(1, 'Alpha Coders', 120),
(2, 'Beta Builders', 95),
(3, 'Gamma Hackers', 140),
(4, 'Delta Devs', 80);

INSERT OR IGNORE INTO members (name, email, role, team_id) VALUES
('Arjun Kumar',   'arjun@college.edu',   'lead',   3),
('Priya Sharma',  'priya@college.edu',   'member', 3),
('Ravi Patel',    'ravi@college.edu',    'member', 1),
('Sneha Reddy',   'sneha@college.edu',   'lead',   1),
('Kiran Rao',     'kiran@college.edu',   'member', 2),
('Meera Nair',    'meera@college.edu',   'member', 2),
('Rohan Singh',   'rohan@college.edu',   'lead',   4),
('Dev Anand',     'dev@college.edu',     'member', 4);
