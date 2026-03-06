<?php
$page_title = 'Gallery';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
$gallery = db_query("SELECT * FROM gallery ORDER BY created_at DESC");
$placeholders = [['Hackathon 2024','emoji_events','#e8f0fe'],['Web Dev Workshop','laptop','#dcfce7'],['AI Talk','psychology','#ede9fe'],['Team Building','groups','#fef3c7'],['Open Source Drive','hub','#e0f2fe'],['Demo Day','present_to_all','#fee2e2']];
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">photo_library</span>Memories</div>
        <h1>Our Gallery</h1>
        <p>Snapshots from hackathons, workshops, and club meetups.</p>
    </div>
</div>

<section class="section">
    <div class="gallery-grid">
        <?php if (empty($gallery)):
            foreach ($placeholders as [$label, $icon, $bg]): ?>
            <div class="gallery-item" style="background:<?= $bg ?>;">
                <div class="gallery-placeholder">
                    <span class="msi" style="font-size:2.5rem;display:block;margin-bottom:0.5rem;color:var(--primary);opacity:0.5"><?= $icon ?></span>
                    <strong style="color:var(--text-mid)"><?= $label ?></strong>
                    <p style="margin-top:0.25rem;font-size:0.72rem;">Photo coming soon</p>
                </div>
            </div>
            <?php endforeach;
        else:
            foreach ($gallery as $item): ?>
            <div class="gallery-item">
                <?php if ($item['image_url']): ?>
                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                <div class="gallery-placeholder">
                    <span class="msi" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.3">image</span>
                    <strong><?= htmlspecialchars($item['title']) ?></strong>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach;
        endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
