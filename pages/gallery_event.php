<?php
$page_title = 'Event Gallery';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo "<div class='section' style='text-align:center'><h2>Event not found.</h2></div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$album = db_query("SELECT * FROM gallery_albums WHERE id=?", [$id])[0] ?? null;
if (!$album) {
    echo "<div class='section' style='text-align:center'><h2>Event not found.</h2></div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$highlights = db_query("SELECT * FROM gallery_highlights WHERE album_id=? ORDER BY created_at ASC", [$id]);
$has_drive = !empty($album['drive_folder_url']);
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><a href="gallery.php" style="color:inherit;text-decoration:none;"><span class="msi" style="font-size:14px;vertical-align:middle;">arrow_back</span> Back to Gallery</a></div>
        <h1><?= htmlspecialchars($album['event_name']) ?></h1>
        <?php if ($album['description']): ?>
        <p><?= htmlspecialchars($album['description']) ?></p>
        <?php endif; ?>
        <?php if ($has_drive): ?>
        <div style="margin-top:2rem;">
            <a href="<?= htmlspecialchars($album['drive_folder_url']) ?>" target="_blank" class="btn btn-primary" style="display:inline-flex;padding:0.6rem 1.4rem;border-radius:100px;font-weight:700;font-size:1rem;"><span class="msi">folder_open</span> View Album in Google Drive</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div style="text-align:center;margin-bottom:2rem;">
        <h2 style="font-size:1.75rem;font-weight:800;">Highlights</h2>
    </div>

    <?php if (empty($highlights)): ?>
    <div style="text-align:center;color:var(--text-dim);padding:3rem 1rem;">
        <span class="msi" style="font-size:3rem;display:block;opacity:0.25;margin-bottom:0.75rem;">image_not_supported</span>
        <p>No highlight photos uploaded yet.</p>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;">
        <?php foreach ($highlights as $hl): ?>
        <div style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);aspect-ratio:1;">
            <img src="/assets/uploads/gallery/<?= htmlspecialchars($hl['photo_url']) ?>" style="width:100%;height:100%;object-fit:cover;transition:transform .3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform=''">
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
