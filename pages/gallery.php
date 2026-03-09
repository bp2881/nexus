<?php
$page_title = 'Gallery';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$photos = db_query("SELECT * FROM gallery ORDER BY created_at DESC");

// Group by event_name for album view
$albums = [];
foreach ($photos as $p) {
    $album = $p['event_name'] ?: 'General';
    $albums[$album][] = $p;
}
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">photo_library</span>Memories</div>
        <h1>Our Gallery</h1>
        <p>Snapshots from hackathons, workshops, and club meetups.</p>
    </div>
</div>

<section class="section">
    <?php if (empty($photos)): ?>
    <!-- No photos yet — show placeholder grid -->
    <div style="text-align:center;padding:2rem 0 1rem;">
        <span class="msi" style="font-size:3rem;display:block;margin-bottom:0.75rem;color:var(--text-dim);opacity:0.3">photo_library</span>
        <p style="color:var(--text-dim);font-size:0.9rem;">Photos will appear here once the admin adds them.</p>
    </div>
    <?php else: ?>

    <!-- ALBUM TABS -->
    <?php if (count($albums) > 1): ?>
    <div class="chip-bar" id="album-filter">
        <button class="chip active" data-album="all">All Photos (<?= count($photos) ?>)</button>
        <?php foreach ($albums as $name => $items): ?>
        <button class="chip" data-album="<?= htmlspecialchars($name) ?>">
            <span class="msi" style="font-size:13px">folder</span>
            <?= htmlspecialchars($name) ?> (<?= count($items) ?>)
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- PHOTO GRID -->
    <div class="gallery-grid" id="photo-grid">
        <?php foreach ($photos as $p): ?>
        <div class="gallery-item photo-card" data-album="<?= htmlspecialchars($p['event_name'] ?: 'General') ?>"
             style="cursor:pointer;" onclick="openLightbox('<?= htmlspecialchars(addslashes($p['image_url'])) ?>','<?= htmlspecialchars(addslashes($p['title'])) ?>','<?= htmlspecialchars(addslashes($p['description'] ?? '')) ?>')">
            <?php if ($p['image_url']): ?>
            <img src="<?= htmlspecialchars($p['image_url']) ?>"
                 alt="<?= htmlspecialchars($p['title']) ?>"
                 style="width:100%;height:100%;object-fit:cover;transition:transform 0.25s;"
                 onmouseover="this.style.transform='scale(1.04)'"
                 onmouseout="this.style.transform=''"
                 onerror="this.parentElement.innerHTML='<div class=\'gallery-placeholder\'><span class=\'msi\' style=\'font-size:2rem;display:block;opacity:0.3\'>broken_image</span><?= htmlspecialchars($p['title']) ?></div>'">
            <?php else: ?>
            <div class="gallery-placeholder">
                <span class="msi" style="font-size:2rem;display:block;margin-bottom:0.4rem;opacity:0.3">image</span>
                <strong style="font-size:0.8rem;color:var(--text-mid)"><?= htmlspecialchars($p['title']) ?></strong>
            </div>
            <?php endif; ?>
            <!-- Caption overlay -->
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(15,23,42,0.8));padding:1rem 0.75rem 0.6rem;color:white;opacity:0;transition:opacity 0.2s;"
                 class="caption-overlay">
                <div style="font-size:0.8rem;font-weight:600;"><?= htmlspecialchars($p['title']) ?></div>
                <?php if ($p['event_name']): ?><div style="font-size:0.68rem;opacity:0.75;"><?= htmlspecialchars($p['event_name']) ?></div><?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- LIGHTBOX -->
<div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.92);z-index:999;align-items:center;justify-content:center;padding:1.5rem;backdrop-filter:blur(8px);"
     onclick="if(event.target===this)closeLightbox()">
    <button onclick="closeLightbox()" style="position:absolute;top:1rem;right:1.25rem;background:rgba(255,255,255,0.1);border:none;color:white;font-size:1.5rem;width:40px;height:40px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;">
        <span class="msi">close</span>
    </button>
    <div style="max-width:860px;width:100%;text-align:center;">
        <img id="lb-img" src="" alt="" style="max-height:72vh;max-width:100%;border-radius:var(--radius-lg);box-shadow:0 24px 64px rgba(0,0,0,0.5);object-fit:contain;">
        <div style="margin-top:0.85rem;">
            <div id="lb-title" style="font-size:1rem;font-weight:700;color:white;"></div>
            <div id="lb-desc"  style="font-size:0.82rem;color:rgba(255,255,255,0.6);margin-top:0.25rem;"></div>
        </div>
    </div>
</div>

<style>
.gallery-item { position:relative; overflow:hidden; }
.gallery-item:hover .caption-overlay { opacity:1 !important; }
</style>

<script>
// Lightbox
function openLightbox(src, title, desc) {
    if (!src) return;
    document.getElementById('lb-img').src = src;
    document.getElementById('lb-title').textContent = title;
    document.getElementById('lb-desc').textContent  = desc;
    const lb = document.getElementById('lightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// Album filter
document.querySelectorAll('#album-filter .chip').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#album-filter .chip').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const album = this.dataset.album;
        document.querySelectorAll('.photo-card').forEach(card => {
            card.style.display = (album === 'all' || card.dataset.album === album) ? '' : 'none';
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
