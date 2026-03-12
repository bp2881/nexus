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
        
        <div style="margin-top:2rem; max-width:400px; margin-inline:auto; position:relative;">
            <span class="msi" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--text-dim);">search</span>
            <input type="text" id="memberSearch" placeholder="Search members..." style="width:100%; padding:0.8rem 1rem 0.8rem 2.8rem; border-radius:100px; border:1px solid var(--border); background:white; font-size:1rem; outline:none; box-shadow:var(--shadow-sm); color:var(--text);">
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     HOD (DUMMY)
════════════════════════════════════════════════════════════ -->
<section class="section" style="padding-bottom:1rem;">
    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.8rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text);display:flex;align-items:center;">
            Head of Department
            <div style="height:1px;background:var(--border);flex:1;margin-left:1rem;"></div>
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem; max-width:320px;">
        <div class="member-card dummy-card" data-name="hod dummy name" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);">
            <img src="https://ui-avatars.com/api/?name=HOD+Name&background=random&size=400" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.1rem;text-transform:uppercase;margin-bottom:0.1rem;">Dr. HOD Name</div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.05em;">Head of Department, CSE</div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     FACULTY COORDINATORS (DUMMY)
════════════════════════════════════════════════════════════ -->
<section class="section" style="padding-top:1rem;padding-bottom:1rem;">
    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.8rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text);display:flex;align-items:center;">
            Faculty Coordinators
            <div style="height:1px;background:var(--border);flex:1;margin-left:1rem;"></div>
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;">
        <?php for($i=1; $i<=3; $i++): ?>
        <div class="member-card dummy-card" data-name="faculty name <?= $i ?>" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);">
            <img src="https://ui-avatars.com/api/?name=Faculty+<?= $i ?>&background=random&size=400" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.1rem;text-transform:uppercase;margin-bottom:0.1rem;">Mr. Faculty Name <?= $i ?></div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.05em;">Assistant Professor</div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     STUDENT COORDINATORS (DUMMY)
════════════════════════════════════════════════════════════ -->
<section class="section" style="padding-top:1rem;padding-bottom:1rem;">
    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.8rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text);display:flex;align-items:center;">
            Student Leads & Coordinators
            <div style="height:1px;background:var(--border);flex:1;margin-left:1rem;"></div>
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;">
        <?php for($i=1; $i<=4; $i++): ?>
        <div class="member-card dummy-card" data-name="student name <?= $i ?>" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);">
            <img src="https://ui-avatars.com/api/?name=Student+<?= $i ?>&background=random&size=400" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.05rem;text-transform:uppercase;margin-bottom:0.1rem;">Student Name <?= $i ?></div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.05em;">Co-Lead</div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</section>

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
            <div class="member-card" data-name="<?= htmlspecialchars(strtolower($m['name'])) ?>" style="background:white;border:1px solid var(--border);border-radius:var(--radius);padding:.9rem 1rem;display:flex;align-items:center;gap:.75rem;box-shadow:var(--shadow-sm);transition:all .18s;"
                 onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform=''">
                <?= avatar($m, 40) ?>
                <div style="flex:1; overflow:hidden;">
                    <div style="font-weight:700;font-size:.85rem;line-height:1.2;margin-bottom:0.15rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($m['name']) ?></div>
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

<script>
document.getElementById('memberSearch').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('.member-card').forEach(card => {
        let name = card.dataset.name || '';
        card.style.display = name.includes(filter) ? '' : 'none';
    });
});
</script>
