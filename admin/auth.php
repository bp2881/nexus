<?php
// admin/auth.php
// Credentials are read from environment variables or .env if present.

function _load_env(): void {
    $env = __DIR__ . '/../.env';
    if (!file_exists($env)) return;
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v);
    }
}
_load_env();

function _env(string $key, string $default = ''): string {
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
        return $_ENV[$key];
    }

    $v = getenv($key);
    if ($v !== false && $v !== '') {
        return $v;
    }

    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
        return $_SERVER[$key];
    }

    return $default;
}

function _admin_user(): string { return _env('ADMIN_USER', 'admin'); }
function _admin_hash(): string { return _env('ADMIN_PASS_HASH', ''); }

function require_login(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/login.php'); exit;
    }
}

function admin_login(string $user, string $pass): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($user === _admin_user() && password_verify($pass, _admin_hash())) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $user;
        return true;
    }
    return false;
}

function admin_logout(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_destroy();
    header('Location: /admin/login.php'); exit;
}

function is_logged_in(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_logged_in']);
}
?>