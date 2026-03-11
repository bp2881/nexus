<?php
$page_title = 'Members';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$all = db_query("SELECT m.*, t.team_name, t.team_no FROM members m LEFT JOIN teams t ON m.team_id=t.id ORDER BY m.created_at ASC");

// Split by role
$hod          = array_filter($all, fn($m) => $m['role'] === 'hod');
$faculty      = array_filter($all, fn($m) => $m['role'] === 'faculty_coordinator');
$student_coords = array_filter($all, fn($m) => $m['role'] === 'student_coordinator');
$club_members = array_filter($all, fn($m) => !in_array($m['role'], ['hod','faculty_coordinator','student_coordinator']));

// Avatar helper — photo if set, else coloured initial
function avatar(array $m, int $size = 96): string {
    $initial = htmlspecialchars(strtoupper(mb_substr($m['name'], 0, 1)));
    $colors  = ['#1a73e8','#16a34a','#7c3aed','#db2777','#f59e0b','#0891b2'];
    $bg      = $colors[crc32($m['name']) % count($colors)];
    if (!empty($m['photo_url'])) {
        return '<img src="'.htmlspecialchars($m['photo_url']).'" alt="'.htmlspecialchars($m['name']).'"
                     style="width:'.$size.'px;height:'.$size.'px;border-radius:50%;object-fit:cover;border:3px solid white;box-shadow:0 2px 12px rgba(0,0,0,.12);"
                     onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\'">'
             .'<div style="width:'.$size.'px;height:'.$size.'px;border-radius:50%;background:'.$bg.';color:white;display:none;align-items:center;justify-content:center;font-size:'.round($size*.38).'px;font-weight:800;">'.$initial.'</div>';
    }
    return '<div style="width:'.$size.'px;height:'.$size.'px;border-radius:50%;background:'.$bg.';color:white;display:flex;align-items:center;justify-content:center;font-size:'.round($size*.38).'px;font-weight:800;border:3px solid white;box-shadow:0 2px 12px rgba(0,0,0,.12);">'.$initial.'</div>';
}
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">group</span>Our People</div>
        <h1>Meet the Team</h1>
        <p>The faculty and students who make Nexus run.</p>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     HOD
════════════════════════════════════════════════════════════ -->
<?php if (!empty($hod)): ?>
<section class="section" style="padding-bottom:0;">
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div class="eyebrow" style="justify-content:center;"><span class="msi" style="font-size:14px">school</span>Head of Department</div>
        <h2 style="font-size:1.75rem;font-weight:800;margin-top:.4rem;">HOD</h2>
    </div>
    <div style="display:flex;justify-content:center;gap:2rem;flex-wrap:wrap;">
        <?php foreach ($hod as $m): ?>
        <div style="text-align:center;max-width:240px;">
            <div style="display:flex;justify-content:center;margin-bottom:1rem;">
                <?= avatar($m, 120) ?>
            </div>
            <div style="font-size:1.2rem;font-weight:800;margin-bottom:.3rem;"><?= htmlspecialchars($m['name']) ?></div>
            <?php if (!empty($m['designation'])): ?>
            <div style="font-size:.82rem;color:var(--text-mid);margin-bottom:.4rem;"><?= htmlspecialchars($m['designation']) ?></div>
            <?php endif; ?>
            <span style="display:inline-block;padding:.25rem .75rem;border-radius:100px;background:var(--primary-light);color:var(--primary);font-size:.72rem;font-weight:700;">Head of Department</span>
            <?php if (!empty($m['email'])): ?>
            <div style="margin-top:.5rem;"><a href="mailto:<?= htmlspecialchars($m['email']) ?>" style="font-size:.78rem;color:var(--primary);text-decoration:none;">✉ <?= htmlspecialchars($m['email']) ?></a></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- divider -->
<div style="display:flex;align-items:center;gap:1rem;max-width:600px;margin:2.5rem auto;">
    <div style="flex:1;height:1px;background:var(--border);"></div>
    <span class="msi" style="color:var(--border);font-size:20px">keyboard_arrow_down</span>
    <div style="flex:1;height:1px;background:var(--border);"></div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════
     FACULTY COORDINATORS
════════════════════════════════════════════════════════════ -->
<?php if (!empty($faculty)): ?>
<section class="section" style="padding-top:0;padding-bottom:0;">
    <div style="text-align:center;margin-bottom:2rem;">
        <div class="eyebrow" style="justify-content:center;"><span class="msi" style="font-size:14px">person_check</span>Faculty</div>
        <h2 style="font-size:1.4rem;font-weight:800;margin-top:.4rem;">Faculty Coordinators</h2>
    </div>
    <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
        <?php foreach ($faculty as $m): ?>
        <div style="text-align:center;background:white;border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.5rem 1.75rem;max-width:200px;box-shadow:var(--shadow-sm);transition:box-shadow .18s,transform .18s;"
             onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-3px)'"
             onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform=''">
            <div style="display:flex;justify-content:center;margin-bottom:.85rem;">
                <?= avatar($m, 80) ?>
            </div>
            <div style="font-weight:800;font-size:.95rem;margin-bottom:.25rem;"><?= htmlspecialchars($m['name']) ?></div>
            <?php if (!empty($m['designation'])): ?>
            <div style="font-size:.75rem;color:var(--text-mid);margin-bottom:.4rem;"><?= htmlspecialchars($m['designation']) ?></div>
            <?php endif; ?>
            <span style="display:inline-block;padding:.2rem .6rem;border-radius:100px;background:#f0fdf4;color:#16a34a;font-size:.68rem;font-weight:700;">Faculty Coordinator</span>
            <?php if (!empty($m['email'])): ?>
            <div style="margin-top:.45rem;"><a href="mailto:<?= htmlspecialchars($m['email']) ?>" style="font-size:.72rem;color:var(--primary);">✉ <?= htmlspecialchars($m['email']) ?></a></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<div style="display:flex;align-items:center;gap:1rem;max-width:600px;margin:2.5rem auto;">
    <div style="flex:1;height:1px;background:var(--border);"></div>
    <span class="msi" style="color:var(--border);font-size:20px">keyboard_arrow_down</span>
    <div style="flex:1;height:1px;background:var(--border);"></div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════
     STUDENT COORDINATORS
════════════════════════════════════════════════════════════ -->
<?php if (!empty($student_coords)): ?>
<section class="section" style="padding-top:0;padding-bottom:0;">
    <div style="text-align:center;margin-bottom:2rem;">
        <div class="eyebrow" style="justify-content:center;"><span class="msi" style="font-size:14px">star</span>Student Leadership</div>
        <h2 style="font-size:1.4rem;font-weight:800;margin-top:.4rem;">Student Coordinators</h2>
    </div>
    <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
        <?php foreach ($student_coords as $m): ?>
        <div style="text-align:center;background:white;border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.5rem 1.75rem;max-width:200px;box-shadow:var(--shadow-sm);transition:box-shadow .18s,transform .18s;"
             onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-3px)'"
             onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform=''">
            <div style="display:flex;justify-content:center;margin-bottom:.85rem;">
                <?= avatar($m, 80) ?>
            </div>
            <div style="font-weight:800;font-size:.95rem;margin-bottom:.25rem;"><?= htmlspecialchars($m['name']) ?></div>
            <?php if (!empty($m['designation'])): ?>
            <div style="font-size:.75rem;color:var(--text-mid);margin-bottom:.4rem;"><?= htmlspecialchars($m['designation']) ?></div>
            <?php endif; ?>
            <span style="display:inline-block;padding:.2rem .6rem;border-radius:100px;background:#fdf4ff;color:#7c3aed;font-size:.68rem;font-weight:700;">Student Coordinator</span>
            <?php if (!empty($m['email'])): ?>
            <div style="margin-top:.45rem;"><a href="mailto:<?= htmlspecialchars($m['email']) ?>" style="font-size:.72rem;color:var(--primary);">✉ <?= htmlspecialchars($m['email']) ?></a></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<div style="display:flex;align-items:center;gap:1rem;max-width:600px;margin:2.5rem auto;">
    <div style="flex:1;height:1px;background:var(--border);"></div>
    <span class="msi" style="color:var(--border);font-size:20px">keyboard_arrow_down</span>
    <div style="flex:1;height:1px;background:var(--border);"></div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════
     CLUB MEMBERS (by team)
════════════════════════════════════════════════════════════ -->
<?php if (!empty($club_members)): ?>
<section class="section" style="padding-top:0;">
    <div style="text-align:center;margin-bottom:2rem;">
        <div class="eyebrow" style="justify-content:center;"><span class="msi" style="font-size:14px">groups</span>Club Members</div>
        <h2 style="font-size:1.4rem;font-weight:800;margin-top:.4rem;">Members</h2>
    </div>

    <?php
    // Group remaining members by team
    $by_team = [];
    foreach ($club_members as $m) {
        $key = $m['team_id'] ?? 0;
        $by_team[$key][] = $m;
    }
    $teams_data = db_query("SELECT * FROM teams ORDER BY team_no");
    $teams_map  = array_column($teams_data, null, 'id');

    $role_colors = ['lead'=>['#fffbeb','#f59e0b'],'member'=>['#eff6ff','#1a73e8'],'core'=>['#fdf4ff','#7c3aed']];
    ?>

    <?php foreach ($by_team as $tid => $grp):
        $team = $teams_map[$tid] ?? null; ?>
    <div style="margin-bottom:2.5rem;">
        <?php if ($team): ?>
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;padding-bottom:.6rem;border-bottom:2px solid var(--border);">
            <div style="width:34px;height:34px;border-radius:9px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;">
                <span class="msi" style="font-size:18px">groups</span>
            </div>
            <div>
                <span style="font-weight:800;font-size:.95rem;">Team <?= $team['team_no'] ?> — <?= htmlspecialchars($team['team_name']) ?></span>
                <span style="font-size:.72rem;color:var(--text-dim);margin-left:.5rem;"><?= count($grp) ?> members</span>
            </div>
        </div>
        <?php else: ?>
        <div style="font-weight:700;font-size:.85rem;color:var(--text-dim);margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px dashed var(--border);">Unassigned</div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(175px,1fr));gap:.75rem;">
            <?php foreach ($grp as $m):
                [$rbg,$rcol] = $role_colors[$m['role']] ?? ['#f1f5f9','#64748b']; ?>
            <div style="background:white;border:1px solid var(--border);border-radius:var(--radius);padding:.9rem 1rem;display:flex;align-items:center;gap:.75rem;box-shadow:var(--shadow-sm);transition:all .18s;"
                 onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform=''">
                <?= avatar($m, 40) ?>
                <div style="overflow:hidden;">
                    <div style="font-weight:700;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($m['name']) ?></div>
                    <span style="display:inline-block;margin-top:.2rem;padding:.15rem .5rem;border-radius:100px;background:<?= $rbg ?>;color:<?= $rcol ?>;font-size:.65rem;font-weight:700;"><?= ucfirst($m['role']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>

<?php if (empty($all)): ?>
<section class="section" style="text-align:center;padding:4rem 1rem;color:var(--text-dim);">
    <span class="msi" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:0.25">group</span>
    <p>Member profiles coming soon!</p>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
