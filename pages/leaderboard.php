<?php
$page_title = 'Leaderboard';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$teams   = db_query_cached("SELECT t.*, COUNT(m.id) as member_count FROM teams t LEFT JOIN members m ON m.team_id=t.id GROUP BY t.id ORDER BY t.points DESC");
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
        
        <div style="margin-top:2rem; max-width:400px; margin-inline:auto; text-align:center;">
            <button id="openTeamSearchBtn" style="width:100%; padding:0.8rem 1rem 0.8rem 1.5rem; text-align:left; border-radius:100px; border:1px solid var(--border); background:white; font-size:1rem; outline:none; box-shadow:var(--shadow-sm); color:var(--text-dim); cursor:pointer; display:flex; align-items:center; gap:0.5rem; transition:all 0.2s;">
                <span class="msi">search</span>
                <span style="flex:1;">Search teams...</span>
                <span style="background:var(--surface2); padding:0.2rem 0.5rem; border-radius:6px; font-size:0.75rem; font-family:var(--mono); color:var(--text);">Ctrl+K</span>
            </button>
        </div>
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
            <div style="font-size:0.7rem;color:var(--text-dim);margin-bottom:0.5rem;"><?= htmlspecialchars($t['team_no']) ?></div>
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
                <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($t['team_name']) ?> <span style="font-size:0.72rem;color:var(--text-dim);font-weight:400;">· ID: <?= htmlspecialchars($t['team_no']) ?></span></div>
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

<!-- Search Overlay -->
<div id="searchOverlay" style="position:fixed; inset:0; background:rgba(15,23,42,0.85); backdrop-filter:blur(8px); z-index:9999; display:none; opacity:0; transition:opacity 0.2s;">
    <div style="max-width:600px; margin:4rem auto 2rem; background:white; border-radius:var(--radius-lg); box-shadow:var(--shadow-xl); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 8rem);">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:1rem;">
            <span class="msi" style="color:var(--text-mid);">search</span>
            <input type="text" id="overlayTeamSearch" placeholder="Search teams by name or ID..." style="flex:1; border:none; outline:none; font-size:1.1rem; color:var(--text); font-family:var(--font); background:transparent;">
            <button id="closeTeamSearchBtn" style="background:var(--surface2); border:none; border-radius:6px; padding:0.3rem 0.6rem; font-size:0.75rem; font-weight:700; color:var(--text-mid); cursor:pointer;">ESC</button>
        </div>
        <div id="searchResults" style="padding:1rem; overflow-y:auto; flex:1; max-height:450px;">
            <div id="searchEmptyState" style="text-align:center; padding:3rem 1rem; color:var(--text-dim);">
                <span class="msi" style="font-size:2.5rem; opacity:0.3; margin-bottom:0.8rem; display:block;">groups</span>
                <p>Type a team name or ID to search.</p>
            </div>
            <!-- Search results will inject here -->
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
const searchOverlay = document.getElementById('searchOverlay');
const openSearchBtn = document.getElementById('openTeamSearchBtn');
const closeSearchBtn = document.getElementById('closeTeamSearchBtn');
const overlayInput = document.getElementById('overlayTeamSearch');
const searchResults = document.getElementById('searchResults');
const searchEmptyState = document.getElementById('searchEmptyState');

const teamsList = [
    <?php
    $palette = [
        ['#f59e0b','#fffbeb'],
        ['#1a73e8','#eff6ff'],
        ['#16a34a','#f0fdf4'],
        ['#7c3aed','#f5f3ff'],
    ];
    foreach ($teams as $i => $t) {
        [$col,$bg] = $palette[$i % 4];
        $initial = strtoupper(mb_substr($t['team_name'],0,1));
        $avatarHtml = '<div style="width:40px;height:40px;border-radius:50%;background:'.$bg.';color:'.$col.';border:2px solid '.$col.';display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:900;">'.$initial.'</div>';
        
        $rank = $i < 3 ? ['🥇','🥈','🥉'][$i] : '#'.($i+1);
        
        echo json_encode([
            'name' => $t['team_name'],
            'id' => $t['team_no'],
            'points' => $t['points'],
            'members' => (int)$t['member_count'],
            'rank' => $rank,
            'avatar' => $avatarHtml,
            'col' => $col
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

if (openSearchBtn) openSearchBtn.addEventListener('click', openSearch);
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
    const hits = teamsList.filter(t => t.name.toLowerCase().includes(filter) || String(t.id).toLowerCase().includes(filter));
    
    if (hits.length === 0) {
        searchResults.innerHTML = '<div style="text-align:center; padding:3rem 1rem; color:var(--text-dim);"><p>No teams found matching "'+filter+'".</p></div>';
        return;
    }
    
    let html = '<div style="display:flex; flex-direction:column; gap:0.5rem;">';
    hits.forEach(t => {
        html += `
            <div style="display:flex; align-items:center; gap:1.25rem; padding:0.75rem 1rem; border-radius:var(--radius-sm); border:1px solid var(--border); transition:background 0.15s;" onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='white'">
                <div style="font-size:1.1rem; font-weight:800; color:${t.col}; width:30px; text-align:center;">${t.rank}</div>
                ${t.avatar}
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:0.95rem; color:var(--text);">${t.name} <span style="font-size:0.7rem; font-weight:400; color:var(--text-dim);">· ID: ${t.id}</span></div>
                    <div style="font-size:0.75rem; color:var(--text-mid); margin-top:0.2rem; display:flex; align-items:center; gap:0.3rem;">
                        <span class="msi" style="font-size:14px;">person</span> ${t.members} members
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:1.1rem; font-weight:900; color:${t.col};">${t.points}</div>
                    <div style="font-size:0.65rem; font-weight:700; color:var(--text-dim); text-transform:uppercase;">pts</div>
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
