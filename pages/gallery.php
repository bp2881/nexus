<?php
$page_title = 'Gallery';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$albums = db_query("SELECT * FROM gallery_albums ORDER BY created_at DESC");
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">photo_library</span>Memories</div>
        <h1>Our Gallery</h1>
        <p>Snapshots from hackathons, workshops, and club meetups.</p>
    </div>
</div>

<section class="section">
<?php if (empty($albums)): ?>
    <div style="text-align:center;padding:4rem 1rem;color:var(--text-dim);">
        <span class="msi" style="font-size:3rem;display:block;margin-bottom:.75rem;opacity:.25">photo_library</span>
        <p>Photos will appear here once the admin adds them.</p>
    </div>
<?php else: ?>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:1.5rem;">
    <?php foreach ($albums as $a):
        $has_drive = !empty($a['drive_folder_url']);
        $thumb_src = !empty($a['thumbnail']) ? '/assets/uploads/gallery/' . htmlspecialchars($a['thumbnail']) : '';
    ?>
    <div style="background:white;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;
                box-shadow:var(--shadow-sm);transition:box-shadow .2s,transform .2s; cursor:pointer;"
         onclick="window.location.href='gallery_event.php?id=<?= $a['id'] ?>'"
         onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-4px)'"
         onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform=''">

        <!-- Thumbnail -->
        <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#e0e7ff,#dbeafe);position:relative;overflow:hidden;">
            <?php if ($thumb_src): ?>
            <img src="<?= $thumb_src ?>"
                 alt="<?= htmlspecialchars($a['event_name']) ?>"
                 style="width:100%;height:100%;object-fit:cover;transition:transform .35s;"
                 onmouseover="<?= $has_drive ? "this.style.transform='scale(1.05)'" : '' ?>"
                 onmouseout="this.style.transform=''"
                 onerror="this.style.display='none'">
            <?php else: ?>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <span class="msi" style="font-size:3rem;color:var(--primary);opacity:.3">photo_library</span>
            </div>
            <?php endif; ?>

            <!-- Dark gradient overlay at bottom -->
            <div style="position:absolute;inset:0;background:linear-gradient(transparent 45%,rgba(10,15,30,.72));pointer-events:none;"></div>

            <!-- Event name on the image -->
            <div style="position:absolute;bottom:0;left:0;right:0;padding:.85rem 1rem;">
                <div style="color:white;font-weight:800;font-size:1.05rem;text-shadow:0 1px 4px rgba(0,0,0,.5);">
                    <?= htmlspecialchars($a['event_name']) ?>
                </div>
                <?php if ($a['description']): ?>
                <div style="color:rgba(255,255,255,.75);font-size:.75rem;margin-top:.15rem;text-shadow:0 1px 3px rgba(0,0,0,.5);">
                    <?= htmlspecialchars($a['description']) ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- "View photos" badge -->
            <div style="position:absolute;top:.65rem;right:.65rem;background:rgba(255,255,255,.9);backdrop-filter:blur(4px);
                        border-radius:100px;padding:.28rem .7rem;display:flex;align-items:center;gap:.3rem;
                        font-size:.72rem;font-weight:700;color:var(--primary);box-shadow:0 2px 8px rgba(0,0,0,.15);">
                <span class="msi" style="font-size:15px">arrow_forward</span>View Gallery
            </div>
        </div>

        <!-- Card footer -->
        <div style="padding:.8rem 1rem;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:.75rem;color:var(--text-dim);display:flex;align-items:center;gap:.3rem;">
                <span class="msi" style="font-size:15px">calendar_month</span>
                <?= date('d M Y', strtotime($a['created_at'])) ?>
            </div>
            <?php if ($has_drive): ?>
            <div style="font-size:.72rem;font-weight:700;color:var(--primary);display:flex;align-items:center;gap:.25rem;">
                <span class="msi" style="font-size:14px">folder_open</span>Drive Link
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
