#!/usr/bin/env python3
"""
export_events.py — Export upcoming events to JSON.
Usage: python3 export_events.py  (or via cron)

Install dependency first:
    pip install pymysql
"""

import pymysql, pymysql.cursors, json, os
from datetime import datetime

DB_HOST = 'sqlXXX.infinityfree.com'
DB_NAME = 'ifXXXXXXXX_techclub'
DB_USER = 'ifXXXXXXXX'
DB_PASS = 'your_db_password'

OUTPUT = os.path.join(os.path.dirname(__file__), '..', 'assets', 'events_cache.json')

def export():
    conn = pymysql.connect(host=DB_HOST, user=DB_USER, password=DB_PASS, database=DB_NAME,
                           charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
    with conn:
        with conn.cursor() as c:
            c.execute("SELECT * FROM events WHERE event_date >= date('now') ORDER BY event_date ASC")
            rows = c.fetchall()
            # Convert date objects to strings for JSON
            for r in rows:
                for k, v in r.items():
                    if hasattr(v, 'isoformat'):
                        r[k] = v.isoformat()
    with open(OUTPUT, 'w') as f:
        json.dump(rows, f, indent=2)
    print(f"✅ Exported {len(rows)} events → {OUTPUT}")

if __name__ == '__main__':
    export()
