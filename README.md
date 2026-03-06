
A multi-page PHP website for a college tech/coding club, backed by SQLite and enhanced with Python utility scripts.

---

## 📁 Project Structure

```
techclub/
├── index.php                  # Home page
├── pages/
│   ├── events.php             # Events & Announcements
│   ├── projects.php           # Projects & Achievements
│   ├── materials.php          # Study Materials
│   ├── gallery.php            # Photo Gallery
│   └── contact.php            # Join Us / Contact form
├── includes/
│   ├── header.php             # Nav + HTML head
│   ├── footer.php             # Footer + JS
│   └── db.php                 # SQLite PDO helper
├── assets/
│   ├── css/style.css          # All styles
│   └── js/main.js             # Scroll animations, nav
├── db/
│   ├── schema.sql             # DB schema + seed data
│   └── techclub.db            # SQLite database (auto-created)
└── python/
    ├── setup_db.py            # Initialize & seed the database
    ├── export_events.py       # Export upcoming events → JSON
    └── stats_report.py        # Print club activity report
```

---

## ⚡ Quick Setup

### 1. Initialize the database
```bash
cd techclub/python
python3 setup_db.py
```
This creates `db/techclub.db` and seeds it with sample data.

### 2. Serve with PHP built-in server
```bash
cd techclub
php -S localhost:8000
```
Open `http://localhost:8000` in your browser.

### 3. Or deploy with Apache/Nginx
- Place the folder in your web root (`/var/www/html/techclub`)
- Ensure `mod_rewrite` is enabled for Apache
- Make sure `db/` is writable: `chmod 755 db/`

---

## 🐍 Python Scripts

| Script | Purpose | When to run |
|--------|---------|-------------|
| `setup_db.py` | Create + seed the SQLite database | Once, at setup |
| `export_events.py` | Export upcoming events to JSON | Via cron hourly |
| `stats_report.py` | Print activity summary to terminal | Anytime |

**Cron example** (run export every hour):
```
0 * * * * /usr/bin/python3 /path/to/techclub/python/export_events.py
```

---

## 🗄 Database Tables

| Table | Stores |
|-------|--------|
| `events` | Event title, date, time, location, category |
| `projects` | Project info, tech stack, GitHub URL, top flag |
| `blog_posts` | Title, content, author, category |
| `materials` | Study links, difficulty, category |
| `gallery` | Photo title, URL, event name |
| `contact_requests` | Form submissions from Join Us page |

---

## ➕ Adding Data

Insert via SQL directly:
```sql
-- Add a new event
INSERT INTO events (title, description, event_date, event_time, location, category)
VALUES ('Django Workshop', 'Build your first web app with Django', '2025-05-10', '04:00 PM', 'Lab 3', 'workshop');

-- Add a new project
INSERT INTO projects (title, description, tech_stack, github_url, is_top, team_members)
VALUES ('MyProject', 'Description here', 'Python, Flask', 'https://github.com/...', 0, 'Alice, Bob');
```

---

## 🎨 Tech Stack

- **Frontend**: HTML5, CSS3, Vanilla JS
- **Backend**: PHP 8+ with PDO
- **Database**: SQLite (no MySQL needed!)
- **Scripts**: Python 3
- **Fonts**: Syne (headings) + Space Mono (code/labels)
- **Design**: Dark terminal aesthetic with neon green accents

---

## 🔧 PHP Requirements

- PHP 8.0+
- `pdo_sqlite` extension enabled (usually on by default)

Check with: `php -m | grep sqlite`
