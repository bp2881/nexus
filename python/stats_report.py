#!/usr/bin/env python3
"""
stats_report.py — Generate a summary report of club activity from the database.
Usage: python3 stats_report.py
"""

import sqlite3, os
from datetime import datetime

DB_PATH = os.path.join(os.path.dirname(__file__), '..', 'db', 'techclub.db')

def generate_report():
    conn = sqlite3.connect(DB_PATH)
    c = conn.cursor()

    print("\n" + "="*50)
    print("    CODECRAFT CLUB — ACTIVITY REPORT")
    print(f"    Generated: {datetime.now().strftime('%d %b %Y, %H:%M')}")
    print("="*50)

    c.execute("SELECT COUNT(*) FROM events")
    print(f"\n📅 Total Events        : {c.fetchone()[0]}")

    c.execute("SELECT COUNT(*) FROM projects")
    print(f"🛠  Total Projects      : {c.fetchone()[0]}")

    c.execute("SELECT COUNT(*) FROM projects WHERE is_top=1")
    print(f"⭐ Top Projects        : {c.fetchone()[0]}")

    c.execute("SELECT COUNT(*) FROM blog_posts")
    print(f"📝 Blog Posts          : {c.fetchone()[0]}")

    c.execute("SELECT COUNT(*) FROM materials")
    print(f"📚 Study Materials     : {c.fetchone()[0]}")

    c.execute("SELECT COUNT(*) FROM contact_requests")
    print(f"📬 Contact Requests    : {c.fetchone()[0]}")

    print("\n--- Upcoming Events ---")
    today = datetime.today().strftime('%Y-%m-%d')
    c.execute("SELECT title, event_date, location FROM events WHERE event_date >= ? ORDER BY event_date LIMIT 5", (today,))
    for row in c.fetchall():
        print(f"  • {row[0]} | {row[1]} | {row[2]}")

    print("\n--- Top Projects ---")
    c.execute("SELECT title, tech_stack FROM projects WHERE is_top=1")
    for row in c.fetchall():
        print(f"  ⭐ {row[0]} ({row[1]})")

    print("\n" + "="*50 + "\n")
    conn.close()

if __name__ == '__main__':
    generate_report()
