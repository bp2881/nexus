<?php
// includes/db.php — SQLite connection

define('DB_PATH', __DIR__ . '/../db/nexus.db');

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return $pdo;
}

function db_query(string $sql, array $params = []): array {
    $stmt = get_db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function db_query_cached(string $sql, array $params = [], int $ttl = 300): array {
    $cache_dir = __DIR__ . '/../cache';
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0777, true);
    }
    
    $cache_key = md5($sql . json_encode($params));
    $cache_file = $cache_dir . '/' . $cache_key . '.json';
    
    $time_now = time();
    $cache_valid = false;
    
    // Check if cache file exists and is within TTL
    if (file_exists($cache_file)) {
        $file_mtime = filemtime($cache_file);
        if (($time_now - $file_mtime) <= $ttl) {
            $cache_valid = true;
        }
    }
    
    if ($cache_valid) {
        $data = file_get_contents($cache_file);
        if ($data !== false) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
    }
    
    try {
        // Cache miss or expired, fetch fresh data
        $stmt = get_db()->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        // Save fresh data to cache
        @file_put_contents($cache_file, json_encode($results));
        
        return $results;
    } catch (PDOException $e) {
        // DB is totally unresponsive, fallback to stale cache if it exists
        if (file_exists($cache_file)) {
            $data = file_get_contents($cache_file);
            if ($data !== false) {
                $decoded = json_decode($data, true);
                if (is_array($decoded)) {
                    return $decoded; // Return stale data gracefully
                }
            }
        }
        // If DB is down and no cache exists, throw the error
        throw $e;
    }
}

function db_execute(string $sql, array $params = []): bool {
    $stmt = get_db()->prepare($sql);
    return $stmt->execute($params);
}
