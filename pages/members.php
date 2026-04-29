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

// Avatar helper
function avatar(array $m, int $size = 24): string {
    $initial = htmlspecialchars(strtoupper(substr($m['name'], 0, 1)));
    $colors  = ['#1a73e8','#16a34a','#7c3aed','#db2777','#f59e0b','#0891b2'];
    $bg      = $colors[crc32($m['name']) % count($colors)];
    if (!empty($m['photo_url'])) {
        return '<img src="'.htmlspecialchars($m['photo_url']).'" alt="'.htmlspecialchars($m['name']).'" class="w-full h-full object-cover rounded-full border-2 border-surface shadow-md" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\'">'
             .'<div class="hidden w-full h-full items-center justify-center rounded-full text-white font-extrabold" style="background:'.$bg.';">'.$initial.'</div>';
    }
    return '<div class="flex w-full h-full items-center justify-center rounded-full text-white font-extrabold border-2 border-surface shadow-md" style="background:'.$bg.';">'.$initial.'</div>';
}
?>

<main class="pt-32 pb-24 px-6 max-w-screen-xl mx-auto min-h-screen">
<!-- Hero Section -->
<header class="text-center mb-16 relative">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[600px] h-[300px] bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 mb-6 relative z-10">
    <span class="material-symbols-outlined text-xs text-primary">group</span>
    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Our People</span>
</span>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tight mb-4 relative z-10">Meet the <span class="text-primary">Team</span></h1>
<p class="text-xl text-on-surface-variant max-w-2xl mx-auto relative z-10 mb-8">
    The faculty and students who make Nexus run.
</p>

<div class="max-w-md mx-auto relative z-10">
    <button id="openMemberSearchBtn" class="w-full px-6 py-4 text-left rounded-full border border-white/10 bg-surface-container-low hover:bg-surface-container-high transition-colors shadow-lg flex items-center gap-3 group">
        <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors">search</span>
        <span class="flex-1 text-on-surface-variant group-hover:text-on-surface transition-colors">Search members...</span>
        <span class="px-2 py-1 rounded-md bg-surface-container-highest text-xs font-mono text-outline">Ctrl+K</span>
    </button>
</div>
</header>

<!-- HOD -->
<section class="mb-20">
    <div class="flex items-center justify-center mb-10">
        <div class="h-px bg-white/10 flex-1 max-w-[100px] mr-4"></div>
        <h2 class="text-2xl md:text-3xl font-black font-headline tracking-widest uppercase text-center text-on-surface">Head of Department</h2>
        <div class="h-px bg-white/10 flex-1 max-w-[100px] ml-4"></div>
    </div>
    <div class="flex justify-center flex-wrap gap-8">
        <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] w-full max-w-[280px] border border-white/5 shadow-2xl transition-transform hover:-translate-y-2">
            <img src="/assets/images/rajavikram.avif" alt="HOD" class="w-full h-full object-cover">
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/80 to-transparent p-6 pt-12">
                <div class="text-white font-black font-headline text-xl uppercase tracking-wider mb-1">Dr. G. RajaVikram</div>
                <div class="text-secondary font-bold text-xs uppercase tracking-widest">Head of Department, CSE</div>
            </div>
        </div>
    </div>
</section>

<!-- Faculty Coordinators -->
<section class="mb-20">
    <div class="flex items-center justify-center mb-10">
        <div class="h-px bg-white/10 flex-1 max-w-[100px] mr-4"></div>
        <h2 class="text-2xl md:text-3xl font-black font-headline tracking-widest uppercase text-center text-on-surface">Faculty Coordinators</h2>
        <div class="h-px bg-white/10 flex-1 max-w-[100px] ml-4"></div>
    </div>
    <div class="flex justify-center flex-wrap gap-8">
        <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] w-full max-w-[260px] border border-white/5 shadow-2xl transition-transform hover:-translate-y-2">
            <img src="/assets/images/balaji.avif" alt="Faculty" class="w-full h-full object-cover">
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/80 to-transparent p-6 pt-12">
                <div class="text-white font-black font-headline text-lg uppercase tracking-wider mb-1">Mr. Balaji Lanka</div>
                <div class="text-secondary font-bold text-xs uppercase tracking-widest">Assistant Professor</div>
            </div>
        </div>
    </div>
</section>

<!-- Student Leads -->
<section class="mb-24">
    <div class="flex items-center justify-center mb-10">
        <div class="h-px bg-white/10 flex-1 max-w-[100px] mr-4"></div>
        <h2 class="text-2xl md:text-3xl font-black font-headline tracking-widest uppercase text-center text-on-surface">Student Leads</h2>
        <div class="h-px bg-white/10 flex-1 max-w-[100px] ml-4"></div>
    </div>
    <div class="flex justify-center flex-wrap gap-6">
        <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] w-full max-w-[240px] border border-white/5 shadow-xl transition-transform hover:-translate-y-2">
            <img src="/assets/images/kowshik.avif" alt="Student Lead" class="w-full h-full object-cover transition-all duration-500">
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/80 to-transparent p-5 pt-12">
                <div class="text-white font-black font-headline text-lg uppercase tracking-wider mb-1">Ghanta Kowshik Kumar</div>
                <div class="text-primary font-bold text-xs uppercase tracking-widest">Co-Lead</div>
            </div>
        </div>
        <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] w-full max-w-[240px] border border-white/5 shadow-xl transition-transform hover:-translate-y-2">
            <img src="/assets/images/pranav.avif" alt="Student Lead" class="w-full h-full object-cover transition-all duration-500">
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/80 to-transparent p-5 pt-12">
                <div class="text-white font-black font-headline text-lg uppercase tracking-wider mb-1">Pranav Bairy</div>
                <div class="text-primary font-bold text-xs uppercase tracking-widest">Co-Lead</div>
            </div>
        </div>
        <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] w-full max-w-[240px] border border-white/5 shadow-xl transition-transform hover:-translate-y-2">
            <img src="/assets/images/devesh.avif" alt="Student Lead" class="w-full h-full object-cover transition-all duration-500">
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/80 to-transparent p-5 pt-12">
                <div class="text-white font-black font-headline text-lg uppercase tracking-wider mb-1">Devesh Rayudu</div>
                <div class="text-primary font-bold text-xs uppercase tracking-widest">Co-Lead</div>
            </div>
        </div>
    </div>
</section>

<!-- Club Members by Team -->
<?php if (!empty($club_members)): ?>
<section>
    <div class="text-center mb-12">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 mb-4">
            <span class="material-symbols-outlined text-xs text-primary">groups</span>
            <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Club Members</span>
        </span>
        <h2 class="text-3xl font-extrabold font-headline text-on-surface">Members By Team</h2>
    </div>

    <?php
    $by_team = [];
    foreach ($club_members as $m) {
        $key = $m['team_id'] ?? 0;
        $by_team[$key][] = $m;
    }
    $teams_data = db_query_cached("SELECT * FROM teams ORDER BY team_no");
    $teams_map  = array_column($teams_data, null, 'id');
    ?>

    <?php foreach ($by_team as $tid => $grp):
        $team = $teams_map[$tid] ?? null; ?>
    <div class="mb-12">
        <?php if ($team): ?>
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
            <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined">group_work</span>
            </div>
            <div>
                <h3 class="font-headline font-bold text-lg text-on-surface"><?= htmlspecialchars($team['team_name']) ?> <span class="text-outline text-sm font-normal ml-2">ID: <?= htmlspecialchars($team['team_no']) ?></span></h3>
                <p class="text-xs text-on-surface-variant"><?= count($grp) ?> Members</p>
            </div>
        </div>
        <?php else: ?>
        <h3 class="font-headline font-bold text-lg text-on-surface-variant mb-6 pb-2 border-b border-surface/50 border-dashed">Unassigned</h3>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php foreach ($grp as $m): ?>
            <div class="member-card bg-surface-container-low border border-white/5 rounded-xl p-4 flex items-center gap-4 hover:-translate-y-1 hover:border-white/10 hover:bg-surface-container-high transition-all" data-name="<?= htmlspecialchars(strtolower($m['name'])) ?>">
                <div class="w-12 h-12 flex-shrink-0">
                    <?= avatar($m, 48) ?>
                </div>
                <div class="overflow-hidden flex-1">
                    <h4 class="font-bold text-sm text-on-surface truncate mb-1"><?= htmlspecialchars($m['name']) ?></h4>
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $m['role'] === 'lead' ? 'bg-secondary/20 text-secondary-fixed-dim' : 'bg-surface-container-highest text-outline' ?>">
                        <?= htmlspecialchars($m['role']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>

<?php if (empty($all)): ?>
<section class="text-center py-24 text-on-surface-variant">
    <span class="material-symbols-outlined text-6xl opacity-20 mb-4 block">group</span>
    <p class="text-xl">Member profiles coming soon!</p>
</section>
<?php endif; ?>
</main>

<!-- Search Overlay -->
<div id="searchOverlay" class="fixed inset-0 bg-[#0d0e11]/90 backdrop-blur-md z-[100] hidden opacity-0 transition-opacity duration-200">
    <div class="max-w-2xl mx-auto mt-24 bg-surface-container rounded-2xl border border-white/10 shadow-2xl flex flex-col max-h-[70vh] overflow-hidden transform scale-95 transition-transform duration-200" id="searchModal">
        <div class="px-6 py-4 border-b border-white/5 flex items-center gap-4">
            <span class="material-symbols-outlined text-outline">search</span>
            <input type="text" id="overlayMemberSearch" placeholder="Search members by name..." class="flex-1 bg-transparent border-none text-on-surface text-lg outline-none focus:ring-0 p-0 placeholder-outline">
            <button id="closeMemberSearchBtn" class="bg-surface-container-high hover:bg-surface-container-highest text-outline px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">ESC</button>
        </div>
        <div id="searchResults" class="p-4 overflow-y-auto flex-1">
            <div id="searchEmptyState" class="text-center py-16 text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl opacity-20 mb-4 block">person_search</span>
                <p>Type a name to search our members.</p>
            </div>
            <!-- Results injected here -->
        </div>
    </div>
</div>

<script>
const searchOverlay = document.getElementById('searchOverlay');
const searchModal = document.getElementById('searchModal');
const openSearchBtn = document.getElementById('openMemberSearchBtn');
const closeSearchBtn = document.getElementById('closeMemberSearchBtn');
const overlayInput = document.getElementById('overlayMemberSearch');
const searchResults = document.getElementById('searchResults');
const searchEmptyState = document.getElementById('searchEmptyState');

const membersList = [
    <?php
    foreach ($all as $m) {
        $initial = htmlspecialchars(strtoupper(substr($m['name'], 0, 1)));
        $colors  = ['#1a73e8','#16a34a','#7c3aed','#db2777','#f59e0b','#0891b2'];
        $bg      = $colors[crc32($m['name']) % count($colors)];
        
        $roleInfo = ucfirst(str_replace('_', ' ', $m['role']));
        if ($m['role'] === 'hod') $roleInfo = 'Head of Department';

        $tName = '';
        if (!empty($m['team_id'])) {
            $tName = htmlspecialchars($m['team_name'] . ' (ID: ' . $m['team_no'] . ')');
        }
        
        $avatarHtml = '';
        if (!empty($m['photo_url'])) {
            $avatarHtml = '<img src="'.htmlspecialchars($m['photo_url']).'" class="w-10 h-10 rounded-full object-cover shadow-sm">';
        } else {
            $avatarHtml = '<div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-extrabold shadow-sm" style="background:'.$bg.';">'.$initial.'</div>';
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
    searchOverlay.classList.remove('hidden');
    // small delay for transition
    setTimeout(() => {
        searchOverlay.classList.remove('opacity-0');
        searchModal.classList.remove('scale-95');
    }, 10);
    overlayInput.value = '';
    renderResults('');
    overlayInput.focus();
    document.body.style.overflow = 'hidden';
}

function closeSearch() {
    searchOverlay.classList.add('opacity-0');
    searchModal.classList.add('scale-95');
    setTimeout(() => {
        searchOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
}

if(openSearchBtn) openSearchBtn.addEventListener('click', openSearch);
if(closeSearchBtn) closeSearchBtn.addEventListener('click', closeSearch);
searchOverlay.addEventListener('click', e => {
    if (e.target === searchOverlay) closeSearch();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !searchOverlay.classList.contains('hidden')) {
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
        searchResults.innerHTML = '<div class="text-center py-16 text-on-surface-variant"><p>No members found matching "'+filter+'".</p></div>';
        return;
    }
    
    let html = '<div class="flex flex-col gap-2">';
    hits.forEach(m => {
        html += `
            <div class="flex items-center gap-4 p-3 rounded-xl border border-white/5 bg-surface hover:bg-surface-container-high transition-colors cursor-pointer group">
                ${m.avatar}
                <div class="flex-1 overflow-hidden">
                    <div class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors">${m.name}</div>
                    <div class="text-xs text-outline mt-1 flex items-center gap-2">
                        <span class="bg-surface-container-highest px-1.5 py-0.5 rounded text-[9px] uppercase tracking-wider font-bold text-on-surface-variant">${m.role}</span>
                        ${m.team ? '<span class="text-outline">&bull;</span> <span class="truncate">' + m.team + '</span>' : ''}
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
