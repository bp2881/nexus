#!/usr/bin/env python3
"""setup_db.py — Initialize SQLite database for Nexus Club."""
import sqlite3, os
DB   = os.path.join(os.path.dirname(__file__), '..', 'db', 'nexus.db')
SQL  = os.path.join(os.path.dirname(__file__), '..', 'db', 'schema.sql')
conn = sqlite3.connect(DB)
with open(SQL) as f: conn.executescript(f.read())
conn.commit(); conn.close()
print(f"Database ready at {os.path.abspath(DB)}")
