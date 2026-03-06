#!/usr/bin/env python3
"""
export_events.py — Export upcoming events to a JSON file for display or API use.
Run periodically via cron: 0 * * * * python3 export_events.py
"""

import sqlite3, json, os
from datetime import datetime

DB_PATH = os.path.join(os.path.dirname(__file__), '..', 'db', 'techclub.db')
OUTPUT_PATH = os.path.join(os.path.dirname(__file__), '..', 'assets', 'events_cache.json')

def export_events():
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    cursor = conn.cursor()
    today = datetime.today().strftime('%Y-%m-%d')
    cursor.execute("SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC", (today,))
    rows = [dict(r) for r in cursor.fetchall()]
    conn.close()
    with open(OUTPUT_PATH, 'w') as f:
        json.dump(rows, f, indent=2)
    print(f"✅ Exported {len(rows)} events to {OUTPUT_PATH}")

if __name__ == '__main__':
    export_events()
