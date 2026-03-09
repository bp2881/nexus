#!/usr/bin/env python3
"""
stats_report.py — Print a summary of club activity from MySQL.
Usage: python3 stats_report.py

Install dependency first:
    pip install pymysql
"""

import pymysql
import os
from datetime import datetime

DB_HOST = 'sqlXXX.infinityfree.com'
DB_NAME = 'ifXXXXXXXX_techclub'
DB_USER = 'ifXXXXXXXX'
DB_PASS = 'your_db_password'

def report():
    conn = pymysql.connect(host=DB_HOST, user=DB_USER, password=DB_PASS, database=DB_NAME, charset='utf8mb4')
    c = conn.cursor()

    print("\n" + "="*50)
    print("    CODECRAFT CLUB — ACTIVITY REPORT")
    print(f"    Generated: {datetime.now().strftime('%d %b %Y, %H:%M')}")
    print("="*50)

    for label, table in [('Events','events'),('Projects','projects'),('Materials','materials'),('Blog Posts','blog_posts'),('Contacts','contact_requests')]:
        c.execute(f"SELECT COUNT(*) FROM {table}")
        print(f"\n  {label:20}: {c.fetchone()[0]}")

    print("\n--- Upcoming Events ---")
    c.execute("SELECT title, event_date, location FROM events WHERE event_date >= date('now') ORDER BY event_date LIMIT 5")
    for row in c.fetchall():
        print(f"  • {row[0]} | {row[1]} | {row[2]}")

    print("\n--- Top Projects ---")
    c.execute("SELECT title, tech_stack FROM projects WHERE is_top=1")
    for row in c.fetchall():
        print(f"  ⭐ {row[0]} ({row[1]})")

    print("\n" + "="*50 + "\n")
    conn.close()

if __name__ == '__main__':
    report()
