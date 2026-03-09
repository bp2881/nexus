<?php $current = basename($_SERVER['PHP_SELF'], '.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? htmlspecialchars($page_title).' — Admin' : 'Admin — Nexus' ?></title>
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon"><img src="/assets/images/nexus.png" alt="NX"></div>
        <div>
            <div class="sidebar-logo-title">Nexus</div>
            <div class="sidebar-logo-sub">Admin Panel</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-group">
            <div class="nav-group-label">Overview</div>
            <a href="/admin/index.php"     class="<?= $current==='index'     ?'active':'' ?>"><span class="msi">dashboard</span>Dashboard</a>
        </div>
        <div class="nav-group">
            <div class="nav-group-label">People</div>
            <a href="/admin/members.php"   class="<?= $current==='members'   ?'active':'' ?>"><span class="msi">group</span>Members</a>
            <a href="/admin/teams.php"     class="<?= $current==='teams'     ?'active':'' ?>"><span class="msi">emoji_events</span>Teams &amp; Points</a>
        </div>
        <div class="nav-group">
            <div class="nav-group-label">Content</div>
            <a href="/admin/events.php"    class="<?= $current==='events'    ?'active':'' ?>"><span class="msi">event</span>Events</a>
            <a href="/admin/projects.php"  class="<?= $current==='projects'  ?'active':'' ?>"><span class="msi">code</span>Projects</a>
            <a href="/admin/materials.php" class="<?= $current==='materials' ?'active':'' ?>"><span class="msi">menu_book</span>Materials</a>
            <a href="/admin/posts.php"     class="<?= $current==='posts'     ?'active':'' ?>"><span class="msi">article</span>Blog Posts</a>
            <a href="/admin/gallery.php"   class="<?= $current==='gallery'   ?'active':'' ?>"><span class="msi">photo_library</span>Gallery</a>
            <a href="/admin/contacts.php"  class="<?= $current==='contacts'  ?'active':'' ?>"><span class="msi">mail</span>Contacts</a>
        </div>
        <div class="nav-group">
            <div class="nav-group-label">Site</div>
            <a href="/index.php" target="_blank"><span class="msi">open_in_new</span>View Site</a>
            <a href="/admin/logout.php" class="nav-danger"><span class="msi">logout</span>Logout</a>
        </div>
    </nav>
    <div class="sidebar-footer">
        <span class="msi" style="font-size:16px">account_circle</span>
        <span>Logged in as <strong><?= htmlspecialchars($_SESSION['admin_user'] ?? 'admin') ?></strong></span>
    </div>
</aside>

<main class="admin-main">
