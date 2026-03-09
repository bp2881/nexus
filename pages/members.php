<?php
$page_title = 'Members';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$teams   = db_query("SELECT t.*, COUNT(m.id) as member_count FROM teams t LEFT JOIN members m ON m.team_id=t.id GROUP BY t.id ORDER BY t.points DESC");
$members = db_query("SELECT m.*, t.team_name, t.team_no, t.points as team_points FROM members m LEFT JOIN teams t ON m.team_id=t.id ORDER BY t.team_no, m.name");
$max_pts = !empty($teams) ? max(array_column($teams,'points')) : 1;

// Group members by team
$by_team = [];
foreach ($members as $m) {
    $key = $m['team_id'] ?? 'unassigned';
    $by_team[$key][] = $m;
}
$role_tag = ['lead'=>'tag-amber','core'=>'tag-purple','member'=>'tag-blue','advisor'=>'tag-green'];
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">group</span>Our People</div>
        <h1>Members &amp; Teams</h1>
        <p>Meet the people behind Nexus — across teams, roles, and projects.</p>
    </div>
</div>

<!-- TEAM LEADERBOARD -->
<?php if (!empty($teams)): ?>
<div class="section-alt">
    <div class="section-alt-inner">
        <div class="section-header">
            <div class="eyebrow"><span class="msi" style="font-size:14px">emoji_events</span>Standings</div>
            <h2 class="section-title">Team Leaderboard</h2>
            <p class="section-desc">Points earned through events, competitions, and contributions.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem;">
            <?php foreach ($teams as $i => $t):
                $pct    = $max_pts > 0 ? round(($t['points']/$max_pts)*100) : 0;
                $is_top = $i === 0;
                $colors = [
                    ['var(--accent-light)','var(--accent)'],
                    ['var(--primary-light)','var(--primary)'],
                    ['var(--success-light)','var(--success)'],
                    ['var(--purple-light)','var(--purple)'],
                ];
                [$bg, $col] = $colors[$i % count($colors)];
            ?>
            <div class="card <?= $is_top ? '' : '' ?>" style="<?= $is_top ? 'border-color:var(--accent);box-shadow:0 8px 32px rgba(245,158,11,0.15);' : '' ?>">
                <div class="card-head">
                    <div class="card-icon-box" style="background:<?= $bg ?>;color:<?= $col ?>;">
                        <span class="msi"><?= $is_top ? 'emoji_events' : 'groups' ?></span>
                    </div>
                    <?php if ($is_top): ?>
                    <span class="top-badge"><span class="msi" style="font-size:12px">emoji_events</span>Leader</span>
                    <?php else: ?>
                    <span style="font-size:0.72rem;font-weight:700;color:var(--text-dim);">#<?= $i+1 ?></span>
                    <?php endif; ?>
                </div>
                <div style="font-size:0.72rem;font-weight:600;color:var(--text-dim);margin-bottom:0.2rem;">TEAM <?= $t['team_no'] ?></div>
                <h3 style="font-size:1.1rem;margin-bottom:0.75rem;"><?= htmlspecialchars($t['team_name']) ?></h3>

                <!-- Points big display -->
                <div style="font-size:2.4rem;font-weight:800;color:<?= $col ?>;line-height:1;"><?= $t['points'] ?></div>
                <div style="font-size:0.72rem;color:var(--text-dim);font-weight:600;margin-bottom:0.75rem;">POINTS</div>

                <!-- Progress bar -->
                <div style="background:var(--surface2);border-radius:100px;height:6px;overflow:hidden;margin-bottom:0.6rem;">
                    <div style="width:<?= $pct ?>%;height:100%;background:<?= $col ?>;border-radius:100px;transition:width 0.8s ease;"></div>
                </div>

                <div style="font-size:0.78rem;color:var(--text-mid);display:flex;align-items:center;gap:0.3rem;">
                    <span class="msi" style="font-size:15px">person</span>
                    <?= $t['member_count'] ?> member<?= $t['member_count'] != 1 ? 's' : '' ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MEMBERS BY TEAM -->
<section class="section">
    <div class="section-header">
        <div class="eyebrow"><span class="msi" style="font-size:14px">badge</span>The Team</div>
        <h2 class="section-title">All Members</h2>
    </div>

    <?php foreach ($teams as $t):
        $team_members = $by_team[$t['id']] ?? [];
        if (empty($team_members)) continue; ?>
    <div style="margin-bottom:3rem;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--border);">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;">
                <span class="msi">groups</span>
            </div>
            <div>
                <h3 style="font-size:1rem;font-weight:800;">Team <?= $t['team_no'] ?> — <?= htmlspecialchars($t['team_name']) ?></h3>
                <span style="font-size:0.75rem;color:var(--text-dim);"><?= count($team_members) ?> members · <?= $t['points'] ?> pts</span>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.75rem;">
            <?php foreach ($team_members as $m): ?>
            <div style="background:white;border:1px solid var(--border);border-radius:var(--radius);padding:1rem 1.1rem;display:flex;align-items:center;gap:0.85rem;box-shadow:var(--shadow-sm);transition:all 0.18s;"
                 onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform=''">
                <div style="width:42px;height:42px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;text-transform:uppercase;">
                    <?= substr($m['name'], 0, 1) ?>
                </div>
                <div>
                    <div style="font-weight:700;font-size:0.9rem;"><?= htmlspecialchars($m['name']) ?></div>
                    <span class="tag <?= $role_tag[$m['role']] ?? 'tag' ?>" style="margin-top:0.25rem;display:inline-block;"><?= ucfirst($m['role']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Unassigned members -->
    <?php if (!empty($by_team['unassigned'])): ?>
    <div>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--border);">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--surface2);color:var(--text-dim);display:flex;align-items:center;justify-content:center;">
                <span class="msi">person</span>
            </div>
            <h3 style="font-size:1rem;font-weight:800;color:var(--text-mid);">Unassigned Members</h3>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.75rem;">
            <?php foreach ($by_team['unassigned'] as $m): ?>
            <div style="background:white;border:1px solid var(--border);border-radius:var(--radius);padding:1rem 1.1rem;display:flex;align-items:center;gap:0.85rem;box-shadow:var(--shadow-sm);opacity:0.75;">
                <div style="width:42px;height:42px;border-radius:50%;background:var(--surface2);color:var(--text-dim);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;">
                    <?= substr($m['name'], 0, 1) ?>
                </div>
                <div>
                    <div style="font-weight:700;font-size:0.9rem;"><?= htmlspecialchars($m['name']) ?></div>
                    <span class="tag" style="margin-top:0.25rem;display:inline-block;"><?= ucfirst($m['role']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($members)): ?>
    <div style="text-align:center;padding:3rem;color:var(--text-dim);">
        <span class="msi" style="font-size:3rem;display:block;margin-bottom:0.75rem;opacity:0.3">group</span>
        <p>Member profiles coming soon!</p>
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
