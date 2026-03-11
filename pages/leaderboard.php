<?php
$page_title = 'Leaderboard';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$teams   = db_query("SELECT t.*, COUNT(m.id) as member_count FROM teams t LEFT JOIN members m ON m.team_id=t.id GROUP BY t.id ORDER BY t.points DESC");
$max_pts = !empty($teams) ? max(array_column($teams, 'points')) : 1;
$palette = [
    ['#f59e0b','#fffbeb','rgba(245,158,11,0.18)'],
    ['#1a73e8','#eff6ff','rgba(26,115,232,0.12)'],
    ['#16a34a','#f0fdf4','rgba(22,163,74,0.12)'],
    ['#7c3aed','#f5f3ff','rgba(124,58,237,0.12)'],
];
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">emoji_events</span>Competition</div>
        <h1>Team Leaderboard</h1>
        <p>Points earned through events, competitions, and contributions. Updated in real-time by admin.</p>
    </div>
</div>

<section class="section">
<?php if (empty($teams)): ?>
    <div style="text-align:center;padding:4rem 1rem;color:var(--text-dim);">
        <span class="msi" style="font-size:3.5rem;display:block;margin-bottom:1rem;opacity:0.25">emoji_events</span>
        <p>No teams yet. Check back soon!</p>
    </div>
<?php else: ?>

    <!-- PODIUM -->
    <?php if (count($teams) >= 2):
        $podium_order = [];
        if (isset($teams[1])) $podium_order[] = [$teams[1], 2, '155px'];
        if (isset($teams[0])) $podium_order[] = [$teams[0], 1, '200px'];
        if (isset($teams[2])) $podium_order[] = [$teams[2], 3, '120px'];
        $medals = [1=>'🥇',2=>'🥈',3=>'🥉'];
    ?>
    <div style="display:flex;align-items:flex-end;justify-content:center;gap:1.25rem;margin-bottom:3.5rem;flex-wrap:wrap;">
        <?php foreach ($podium_order as [$t, $place, $h]):
            [$col,$bg,$shadow] = $palette[($place-1) % 4]; ?>
        <div style="text-align:center;flex:1;max-width:200px;min-width:130px;">
            <div style="width:60px;height:60px;border-radius:50%;background:<?= $bg ?>;border:3px solid <?= $col ?>;margin:0 auto 0.5rem;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:900;color:<?= $col ?>;">
                <?= strtoupper(substr($t['team_name'],0,1)) ?>
            </div>
            <div style="font-size:1.4rem;margin-bottom:0.2rem;"><?= $medals[$place] ?></div>
            <div style="font-weight:800;font-size:0.88rem;margin-bottom:0.15rem;"><?= htmlspecialchars($t['team_name']) ?></div>
            <div style="font-size:0.7rem;color:var(--text-dim);margin-bottom:0.5rem;">Team <?= $t['team_no'] ?></div>
            <div style="height:<?= $h ?>;background:<?= $bg ?>;border:2px solid <?= $col ?>22;border-radius:10px 10px 0 0;display:flex;align-items:center;justify-content:center;flex-direction:column;box-shadow:0 8px 28px <?= $shadow ?>;">
                <div style="font-size:2rem;font-weight:900;color:<?= $col ?>;line-height:1;"><?= $t['points'] ?></div>
                <div style="font-size:0.65rem;color:var(--text-dim);font-weight:700;letter-spacing:.08em;text-transform:uppercase;">pts</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- RANKINGS TABLE -->
    <div style="background:white;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);max-width:760px;margin:0 auto;">
        <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);font-weight:700;font-size:0.82rem;color:var(--text-dim);text-transform:uppercase;letter-spacing:.06em;">Full Rankings</div>
        <?php foreach ($teams as $i => $t):
            $pct = $max_pts > 0 ? round(($t['points']/$max_pts)*100) : 0;
            [$col,$bg,$shadow] = $palette[$i % 4];
            $ranks = ['🥇','🥈','🥉'];
        ?>
        <div style="display:grid;grid-template-columns:52px 1fr auto auto;gap:1rem;align-items:center;padding:1rem 1.5rem;border-bottom:1px solid var(--border);<?= $i===0?'background:'.$bg.';':'' ?>">
            <div style="font-size:<?= $i<3?'1.5rem':'0.95rem' ?>;font-weight:800;text-align:center;color:<?= $i<3?$col:'var(--text-dim)' ?>;">
                <?= $i < 3 ? $ranks[$i] : '#'.($i+1) ?>
            </div>
            <div>
                <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($t['team_name']) ?> <span style="font-size:0.72rem;color:var(--text-dim);font-weight:400;">· Team <?= $t['team_no'] ?></span></div>
                <div style="margin-top:0.35rem;height:5px;background:var(--surface2);border-radius:100px;overflow:hidden;width:180px;max-width:100%;">
                    <div style="width:<?= $pct ?>%;height:100%;background:<?= $col ?>;border-radius:100px;"></div>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--text-mid);white-space:nowrap;display:flex;align-items:center;gap:.3rem;">
                <span class="msi" style="font-size:15px">person</span><?= $t['member_count'] ?>
            </div>
            <div style="text-align:right;white-space:nowrap;">
                <span style="font-size:1.5rem;font-weight:900;color:<?= $col ?>;"><?= $t['points'] ?></span>
                <span style="font-size:0.7rem;color:var(--text-dim);font-weight:600;"> pts</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
