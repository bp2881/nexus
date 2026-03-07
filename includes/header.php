<?php $current_page = basename($_SERVER['PHP_SELF'], '.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — Nexus' : 'Nexus Club' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="/index.php" class="nav-logo">
        <div class="nav-logo-icon"><img src="/assets/images/nexus.png" alt="NC"></div>
        <span class="nav-logo-text">Nexus<span>Club</span></span>
    </a>
    <button class="nav-toggle" id="navToggle"><span class="msi">menu</span></button>
    <ul class="nav-links" id="navLinks">
        <li><a href="/index.php"           class="<?= $current_page==='index'     ?'active':'' ?>"><span class="msi" style="font-size:17px">home</span>Home</a></li>
        <li><a href="/pages/events.php"    class="<?= $current_page==='events'    ?'active':'' ?>"><span class="msi" style="font-size:17px">event</span>Events</a></li>
        <li><a href="/pages/projects.php"  class="<?= $current_page==='projects'  ?'active':'' ?>"><span class="msi" style="font-size:17px">code</span>Projects</a></li>
        <li><a href="/pages/materials.php" class="<?= $current_page==='materials' ?'active':'' ?>"><span class="msi" style="font-size:17px">menu_book</span>Materials</a></li>
        <li><a href="/pages/gallery.php"   class="<?= $current_page==='gallery'   ?'active':'' ?>"><span class="msi" style="font-size:17px">photo_library</span>Gallery</a></li>
        <li><a href="/pages/contact.php" class="nav-cta"><span class="msi" style="font-size:17px">group_add</span>Join Us</a></li>
    </ul>
</nav>
