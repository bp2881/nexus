#!/usr/bin/env python3
"""
setup_db.py — Initialize and seed the Tech Club SQLite database.
Run this once: python3 setup_db.py
"""

import sqlite3
import os

DB_PATH = os.path.join(os.path.dirname(__file__), '..', 'db', 'techclub.db')
SCHEMA_PATH = os.path.join(os.path.dirname(__file__), '..', 'db', 'schema.sql')

def init_db():
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    with open(SCHEMA_PATH, 'r') as f:
        sql = f.read()
    cursor.executescript(sql)
    conn.commit()
    conn.close()
    print(f"✅ Database initialized at: {os.path.abspath(DB_PATH)}")

if __name__ == '__main__':
    init_db()
