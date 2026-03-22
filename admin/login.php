<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
if (is_logged_in()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_login(trim($_POST['username'] ?? ''), trim($_POST['password'] ?? ''))) {
        header('Location: /admin/index.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Nexus</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="login-body">
    <div class="login-card">
        <div class="login-logo">
            <div class="login-logo-icon">NX</div>
            <div>
                <div class="login-logo-text">Nexus</div>
            </div>
            <span class="login-sub">Admin</span>
        </div>
        <h2>Welcome back</h2>
        <p>Sign in to manage your club content.</p>

        <?php if ($error): ?>
        <div class="alert alert-error"><span class="msi">error</span>
            <?= htmlspecialchars($error)?>
        </div>
        <?php
endif; ?>

        <form method="POST">
            <div class="form-group-admin" style="margin-bottom:1rem;">
                <label>Username</label>
                <input type="text" name="username" placeholder="admin" autocomplete="username" required>
            </div>
            <div class="form-group-admin" style="margin-bottom:1.5rem;">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">
                <span class="msi">login</span> Sign In
            </button>
        </form>

        <a href="/index.php" class="back-link"><span class="msi" style="font-size:15px">arrow_back</span> Back to
            site</a>
    </div>
</body>

</html>