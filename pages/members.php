<?php
$page_title = 'Members';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$all = db_query_cached("SELECT m.*, t.team_name, t.team_no FROM members m LEFT JOIN teams t ON m.team_id=t.id ORDER BY m.created_at ASC");

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
        
        <div style="margin-top:2rem; max-width:400px; margin-inline:auto; text-align:center;">
            <button id="openMemberSearchBtn" style="width:100%; padding:0.8rem 1rem 0.8rem 1.5rem; text-align:left; border-radius:100px; border:1px solid var(--border); background:white; font-size:1rem; outline:none; box-shadow:var(--shadow-sm); color:var(--text-dim); cursor:pointer; display:flex; align-items:center; gap:0.5rem; transition:all 0.2s;">
                <span class="msi">search</span>
                <span style="flex:1;">Search members...</span>
                <span style="background:var(--surface2); padding:0.2rem 0.5rem; border-radius:6px; font-size:0.75rem; font-family:var(--mono); color:var(--text);">Ctrl+K</span>
            </button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     HOD (DUMMY)
════════════════════════════════════════════════════════════ -->
<section class="section" style="padding-bottom:1rem;">
    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.8rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text);display:flex;align-items:center;justify-content:center;text-align:center;">
            <div style="height:1px;background:var(--border);flex:1;margin-right:1rem;"></div>
            Head of Department
            <div style="height:1px;background:var(--border);flex:1;margin-left:1rem;"></div>
        </h2>
    </div>
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem;">
        <div class="member-card dummy-card" data-name="hod dummy name" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);width:100%;max-width:280px;">
            <img src="/assets/images/rajavikram.avif" style="width:100%;height:100%;object-fit:cover;">
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
        <h2 style="font-size:1.8rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text);display:flex;align-items:center;justify-content:center;text-align:center;">
            <div style="height:1px;background:var(--border);flex:1;margin-right:1rem;"></div>
            Faculty Coordinators
            <div style="height:1px;background:var(--border);flex:1;margin-left:1rem;"></div>
        </h2>
    </div>
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem;">
        <div class="member-card dummy-card" data-name="faculty name <?= $i ?>" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);width:100%;max-width:260px;">
            <img src="/assets/images/balaji.avif" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.1rem;text-transform:uppercase;margin-bottom:0.1rem;">Mr. Faculty Name <?= $i ?></div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.05em;">Assistant Professor</div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     STUDENT COORDINATORS (DUMMY)
════════════════════════════════════════════════════════════ -->
<section class="section" style="padding-top:1rem;padding-bottom:1rem;">
    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.8rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text);display:flex;align-items:center;justify-content:center;text-align:center;">
            <div style="height:1px;background:var(--border);flex:1;margin-right:1rem;"></div>
            Student Leads & Coordinators
            <div style="height:1px;background:var(--border);flex:1;margin-left:1rem;"></div>
        </h2>
    </div>
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem;">
        <div class="member-card dummy-card" data-name="student name" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);width:100%;max-width:240px;">
            <img src="/assets/images/kowshik.avif" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.05rem;text-transform:uppercase;margin-bottom:0.1rem;">Ghanta Kowshik Kumar</div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.05em;">Co-Lead</div>
            </div>
        </div>
        <div class="member-card dummy-card" data-name="student name" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);width:100%;max-width:240px;">
            <img src="/assets/images/pranav.avif" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.05rem;text-transform:uppercase;margin-bottom:0.1rem;">Pranav Bairy</div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.05em;">Co-Lead</div>
            </div>
        </div>
        <div class="member-card dummy-card" data-name="student name" style="position:relative;border-radius:1rem;overflow:hidden;aspect-ratio:3/4;box-shadow:var(--shadow);width:100%;max-width:240px;">
            <img src="/assets/images/devesh.avif" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%);padding:2rem 1.25rem 1.25rem;">
                <div style="color:white;font-weight:900;font-style:italic;font-size:1.05rem;text-transform:uppercase;margin-bottom:0.1rem;">Devesh Rayudu</div>
                <div style="color:#fbbf24;font-weight:800;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.05em;">Co-Lead</div>
            </div>
        </div>
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
    $teams_data = db_query_cached("SELECT * FROM teams ORDER BY team_no");
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
                <span style="font-weight:800;font-size:.95rem;"><?= htmlspecialchars($team['team_name']) ?> (ID: <?= htmlspecialchars($team['team_no']) ?>)</span>
                <span style="font-size:.72rem;color:var(--text-dim);margin-left:.5rem;"><?= count($grp) ?> members</span>
            </div>
        </div>
        <?php else: ?>
        <div style="font-weight:700;font-size:.85rem;color:var(--text-dim);margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px dashed var(--border);">Unassigned</div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(175px,1fr));gap:.75rem;">
            <?php foreach ($grp as $m):
                [$rbg,$rcol] = $role_colors[$m['role']] ?? ['#f1f5f9','#64748b']; ?>
            <div class="member-card club-member-card" data-name="<?= htmlspecialchars(strtolower($m['name'])) ?>" style="background:white;border:1px solid var(--border);border-radius:var(--radius);padding:.9rem 1rem;display:flex;align-items:center;gap:.75rem;box-shadow:var(--shadow-sm);transition:all .18s;"
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

<!-- Search Overlay -->
<div id="searchOverlay" style="position:fixed; inset:0; background:rgba(15,23,42,0.85); backdrop-filter:blur(8px); z-index:9999; display:none; opacity:0; transition:opacity 0.2s;">
    <div style="max-width:600px; margin:4rem auto 2rem; background:white; border-radius:var(--radius-lg); box-shadow:var(--shadow-xl); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 8rem);">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:1rem;">
            <span class="msi" style="color:var(--text-mid);">search</span>
            <input type="text" id="overlayMemberSearch" placeholder="Search members by name..." style="flex:1; border:none; outline:none; font-size:1.1rem; color:var(--text); font-family:var(--font); background:transparent;">
            <button id="closeMemberSearchBtn" style="background:var(--surface2); border:none; border-radius:6px; padding:0.3rem 0.6rem; font-size:0.75rem; font-weight:700; color:var(--text-mid); cursor:pointer;">ESC</button>
        </div>
        <div id="searchResults" style="padding:1rem; overflow-y:auto; flex:1; max-height:450px;">
            <div id="searchEmptyState" style="text-align:center; padding:3rem 1rem; color:var(--text-dim);">
                <span class="msi" style="font-size:2.5rem; opacity:0.3; margin-bottom:0.8rem; display:block;">person_search</span>
                <p>Type a name to search our members.</p>
            </div>
            <!-- Search results will inject here -->
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
const searchOverlay = document.getElementById('searchOverlay');
const openSearchBtn = document.getElementById('openMemberSearchBtn');
const closeSearchBtn = document.getElementById('closeMemberSearchBtn');
const overlayInput = document.getElementById('overlayMemberSearch');
const searchResults = document.getElementById('searchResults');
const searchEmptyState = document.getElementById('searchEmptyState');

const membersList = [
    <?php
    foreach ($all as $m) {
        $initial = htmlspecialchars(strtoupper(mb_substr($m['name'], 0, 1)));
        $colors  = ['#1a73e8','#16a34a','#7c3aed','#db2777','#f59e0b','#0891b2'];
        $bg      = $colors[crc32($m['name']) % count($colors)];
        
        $roleInfo = ucfirst($m['role']);
        if ($m['role'] === 'hod') $roleInfo = 'Head of Department';
        else if ($m['role'] === 'faculty_coordinator') $roleInfo = 'Faculty Coordinator';
        else if ($m['role'] === 'student_coordinator') $roleInfo = 'Student Coordinator';

        $tName = '';
        if (!empty($m['team_id'])) {
            $tName = htmlspecialchars($m['team_name'] . ' (ID: ' . $m['team_no'] . ')');
        }
        
        $avatarHtml = '';
        if (!empty($m['photo_url'])) {
            $avatarHtml = '<img src="'.htmlspecialchars($m['photo_url']).'" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">';
        } else {
            $avatarHtml = '<div style="width:40px;height:40px;border-radius:50%;background:'.$bg.';color:white;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;">'.$initial.'</div>';
        }

        echo json_encode([
            'name' => $m['name'],
            'role' => $roleInfo,
            'team' => $tName,
            'avatar' => $avatarHtml
        ]) . ",";
    }
    ?>
];

function openSearch() {
    searchOverlay.style.display = 'block';
    void searchOverlay.offsetWidth;
    searchOverlay.style.opacity = '1';
    overlayInput.value = '';
    renderResults('');
    overlayInput.focus();
    document.body.style.overflow = 'hidden';
}

function closeSearch() {
    searchOverlay.style.opacity = '0';
    setTimeout(() => {
        searchOverlay.style.display = 'none';
        document.body.style.overflow = '';
    }, 200);
}

openSearchBtn.addEventListener('click', openSearch);
closeSearchBtn.addEventListener('click', closeSearch);
searchOverlay.addEventListener('click', e => {
    if (e.target === searchOverlay) closeSearch();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && searchOverlay.style.display === 'block') {
        closeSearch();
    }
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        openSearch();
    }
});

function renderResults(filter) {
    if (!filter) {
        searchResults.innerHTML = '';
        searchResults.appendChild(searchEmptyState);
        searchEmptyState.style.display = 'block';
        return;
    }
    
    filter = filter.toLowerCase();
    const hits = membersList.filter(m => m.name.toLowerCase().includes(filter) || m.team.toLowerCase().includes(filter));
    
    if (hits.length === 0) {
        searchResults.innerHTML = '<div style="text-align:center; padding:3rem 1rem; color:var(--text-dim);"><p>No members found matching "'+filter+'".</p></div>';
        return;
    }
    
    let html = '<div style="display:flex; flex-direction:column; gap:0.5rem;">';
    hits.forEach(m => {
        html += `
            <div style="display:flex; align-items:center; gap:1rem; padding:0.75rem 1rem; border-radius:var(--radius-sm); border:1px solid var(--border); transition:background 0.15s;" onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='white'">
                ${m.avatar}
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:0.95rem; color:var(--text);">${m.name}</div>
                    <div style="font-size:0.75rem; color:var(--text-mid); margin-top:0.2rem; display:flex; align-items:center; gap:0.4rem;">
                        <span style="background:var(--primary-light); color:var(--primary); padding:0.15rem 0.5rem; border-radius:100px; font-weight:700; font-size:0.65rem;">${m.role}</span>
                        ${m.team ? '<span style="color:var(--text-dim);">&bull;</span> <span>' + m.team + '</span>' : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    searchResults.innerHTML = html;
}

overlayInput.addEventListener('input', e => {
    renderResults(e.target.value);
});
</script>
