<?php
// admin/auth.php — Session-based authentication for admin panel

define('ADMIN_USER', 'admin');
// Password: "nexus2025" — change this! Generate with: php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"
define('ADMIN_PASS_HASH', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); // "password" for demo

function require_login(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function admin_login(string $user, string $pass): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASS_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $user;
        return true;
    }
    return false;
}

function admin_logout(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_destroy();
    header('Location: /admin/login.php');
    exit;
}

function is_logged_in(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_logged_in']);
}
