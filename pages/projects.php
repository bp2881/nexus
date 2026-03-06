<?php
$page_title = 'Projects';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
$projects = db_query("SELECT * FROM projects ORDER BY is_top DESC, created_at DESC");
$top  = array_filter($projects, fn($p) => $p['is_top']);
$rest = array_filter($projects, fn($p) => !$p['is_top']);
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">rocket_launch</span>Built by Us</div>
        <h1>Projects &amp; Achievements</h1>
        <p>Real projects by real students — from weekend hackathons to polished products.</p>
    </div>
</div>

<section class="section">
    <?php if (!empty($top)): ?>
    <div class="section-header">
        <div class="eyebrow"><span class="msi" style="font-size:14px">star</span>Featured</div>
        <h2 class="section-title">Top Projects</h2>
    </div>
    <div class="card-grid" style="margin-bottom:3rem;">
        <?php foreach ($top as $p): ?>
        <div class="card">
            <div class="card-head">
                <div class="card-icon-box" style="background:var(--accent-light);color:var(--accent);"><span class="msi">terminal</span></div>
                <span class="top-badge"><span class="msi" style="font-size:12px">star</span>Featured</span>
            </div>
            <h3><?= htmlspecialchars($p['title']) ?></h3>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <div class="card-meta">
                <?php foreach (array_slice(explode(',', $p['tech_stack']), 0, 4) as $t): ?>
                <span class="tag tag-blue"><?= trim(htmlspecialchars($t)) ?></span>
                <?php endforeach; ?>
            </div>
            <div class="card-foot">
                <span class="card-foot-meta"><span class="msi" style="font-size:14px">group</span><?= htmlspecialchars($p['team_members']) ?></span>
                <div style="display:flex;gap:0.5rem;">
                    <?php if ($p['github_url']): ?>
                    <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="card-link">GitHub <span class="msi" style="font-size:13px">open_in_new</span></a>
                    <?php endif; ?>
                    <?php if ($p['demo_url']): ?>
                    <a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" class="card-link" style="margin-left:0.75rem;">Demo <span class="msi" style="font-size:13px">open_in_new</span></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($rest)): ?>
    <div class="section-header">
        <div class="eyebrow"><span class="msi" style="font-size:14px">code</span>All Work</div>
        <h2 class="section-title">Other Projects</h2>
    </div>
    <div class="card-grid">
        <?php foreach ($rest as $p): ?>
        <div class="card">
            <div class="card-head">
                <div class="card-icon-box"><span class="msi">code</span></div>
            </div>
            <h3><?= htmlspecialchars($p['title']) ?></h3>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <div class="card-meta">
                <?php foreach (array_slice(explode(',', $p['tech_stack']), 0, 3) as $t): ?>
                <span class="tag"><?= trim(htmlspecialchars($t)) ?></span>
                <?php endforeach; ?>
            </div>
            <div class="card-foot">
                <span class="card-foot-meta"><span class="msi" style="font-size:14px">group</span><?= htmlspecialchars($p['team_members']) ?></span>
                <?php if ($p['github_url']): ?>
                <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="card-link">GitHub <span class="msi" style="font-size:13px">open_in_new</span></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
