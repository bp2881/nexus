<?php
$page_title = 'Materials';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$materials = db_query("SELECT * FROM materials ORDER BY category, difficulty");
$grouped   = [];
foreach ($materials as $m) $grouped[$m['category']][] = $m;

$cat_icons  = ['python'=>'code','web'=>'language','dsa'=>'account_tree','ml'=>'psychology','tools'=>'build','general'=>'folder','database'=>'storage','devops'=>'cloud','design'=>'palette'];
$diff_icons = ['beginner'=>'<span class="diff-badge diff-beginner"><span class="msi" style="font-size:12px">circle</span>Beginner</span>',
               'intermediate'=>'<span class="diff-badge diff-intermediate"><span class="msi" style="font-size:12px">change_history</span>Intermediate</span>',
               'advanced'=>'<span class="diff-badge diff-advanced"><span class="msi" style="font-size:12px">star</span>Advanced</span>'];
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">menu_book</span>Learn &amp; Grow</div>
        <h1>Study Materials</h1>
        <p>Curated resources handpicked by club seniors to accelerate your learning journey.</p>
    </div>
</div>

<section class="section">
    <?php if (empty($grouped)): ?>
    <div style="text-align:center;padding:3rem;color:var(--text-dim);">
        <span class="msi" style="font-size:3rem;display:block;margin-bottom:0.75rem;opacity:0.3">menu_book</span>
        No materials yet. Check back soon!
    </div>
    <?php endif; ?>

    <?php foreach ($grouped as $cat => $items): ?>
    <div style="margin-bottom:3rem;">
        <div class="section-header" style="margin-bottom:1.25rem;">
            <div class="eyebrow">
                <span class="msi" style="font-size:14px"><?= $cat_icons[$cat] ?? 'folder' ?></span>
                <?= strtoupper($cat) ?>
            </div>
            <h2 class="section-title" style="font-size:1.4rem;"><?= ucfirst($cat) ?> Resources</h2>
        </div>
        <div class="materials-list">
            <?php foreach ($items as $m):
                $url = $m['external_url'] ?: ($m['file_url'] ?: '#'); ?>
            <a class="material-row" href="<?= htmlspecialchars($url) ?>" <?= $m['external_url'] ? 'target="_blank"' : '' ?>>
                <div class="mat-icon">
                    <span class="msi"><?= $cat_icons[$cat] ?? 'article' ?></span>
                </div>
                <div style="flex:1;">
                    <div class="mat-title"><?= htmlspecialchars($m['title']) ?></div>
                    <div class="mat-meta">
                        <?= htmlspecialchars($m['description']) ?>
                        <span>·</span>
                        <?= $diff_icons[$m['difficulty']] ?? '' ?>
                    </div>
                </div>
                <span class="msi" style="color:var(--primary);font-size:20px">open_in_new</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="card" style="border-color:rgba(26,115,232,0.2);background:var(--primary-light);margin-top:1rem;">
        <div class="card-head">
            <div class="card-icon-box"><span class="msi">lightbulb</span></div>
        </div>
        <h3 style="color:var(--primary)">Know a great resource?</h3>
        <p>Suggest a book, course, or tool that helped you — we might add it here for everyone.</p>
        <div style="margin-top:1rem;">
            <a href="/pages/contact.php?type=resource" class="btn btn-primary btn-sm">
                <span class="msi" style="font-size:16px">add</span> Suggest a Resource
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
