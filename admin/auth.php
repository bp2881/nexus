<?php
// admin/auth.php
// Credentials are read from environment variables or .env if present.
// Persistent login via HMAC-signed browser cookie — no server-side file storage.

define('COOKIE_NAME',     'nexus_admin_token');
define('COOKIE_LIFETIME', 86400 * 365); // 1 year

function _load_env(): void {
    if (getenv('ADMIN_USER') !== false && getenv('ADMIN_PASS_HASH') !== false) {
        return;
    }
    $env = __DIR__ . '/../.env';
    if (!file_exists($env)) return;
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim(trim($v), '"\'');
    }
}
_load_env();

function _env(string $key, string $default = ''): string {
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    $v = getenv($key);
    if ($v !== false && $v !== '') return $v;
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}

function _admin_user(): string { return _env('ADMIN_USER', 'admin'); }
function _admin_hash(): string { return _env('ADMIN_PASS_HASH', ''); }

/**
 * Secret key used to sign the cookie.
 * Falls back to a hash of the password hash so it's always unique per install.
 */
function _cookie_secret(): string {
    $secret = _env('COOKIE_SECRET', '');
    if ($secret !== '') return $secret;
    // Derive from the password hash — unique per site, no extra config needed
    return hash('sha256', _admin_hash() . 'nexus_cookie_salt');
}

// ── Cookie helpers ───────────────────────────────────────────────

/**
 * Build a signed cookie value: base64(username|expiry|hmac).
 * The HMAC covers username + expiry so neither can be tampered with.
 */
function _build_cookie_value(string $user): string {
    $expiry  = time() + COOKIE_LIFETIME;
    $payload = $user . '|' . $expiry;
    $sig     = hash_hmac('sha256', $payload, _cookie_secret());
    return base64_encode($payload . '|' . $sig);
}

/**
 * Validate the signed cookie.
 * Returns the username on success, empty string on failure.
 */
function _verify_cookie_value(string $cookie): string {
    $decoded = base64_decode($cookie, true);
    if ($decoded === false) return '';

    $parts = explode('|', $decoded, 3);
    if (count($parts) !== 3) return '';

    [$user, $expiry, $sig] = $parts;

    // Check expiry
    if ((int)$expiry < time()) return '';

    // Constant-time HMAC comparison to prevent timing attacks
    $expected = hash_hmac('sha256', $user . '|' . $expiry, _cookie_secret());
    if (!hash_equals($expected, $sig)) return '';

    return $user;
}

/**
 * Set the persistent signed cookie on the browser.
 */
function _set_persistent_cookie(string $user): void {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie(COOKIE_NAME, _build_cookie_value($user), [
        'expires'  => time() + COOKIE_LIFETIME,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/**
 * Clear the persistent cookie from the browser.
 */
function _clear_persistent_cookie(): void {
    setcookie(COOKIE_NAME, '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// ── Public auth functions ────────────────────────────────────────

function require_login(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();

    // Already authenticated in this session
    if (!empty($_SESSION['admin_logged_in'])) return;

    // Try to restore from persistent cookie
    if (!empty($_COOKIE[COOKIE_NAME])) {
        $user = _verify_cookie_value($_COOKIE[COOKIE_NAME]);
        if ($user !== '') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $user;
            return;
        }
    }

    header('Location: /admin/login.php'); exit;
}

function admin_login(string $user, string $pass): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($user === _admin_user() && password_verify($pass, _admin_hash())) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $user;
        _set_persistent_cookie($user);  // Store signed token on browser
        return true;
    }
    return false;
}

function admin_logout(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    _clear_persistent_cookie();
    session_destroy();
    header('Location: /admin/login.php'); exit;
}

function is_logged_in(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!empty($_SESSION['admin_logged_in'])) return true;

    if (!empty($_COOKIE[COOKIE_NAME])) {
        $user = _verify_cookie_value($_COOKIE[COOKIE_NAME]);
        if ($user !== '') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $user;
            return true;
        }
    }

    return false;
}
?>