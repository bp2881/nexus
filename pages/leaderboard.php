<?php
$page_title = 'Leaderboard';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$teams   = db_query_cached("SELECT t.*, COUNT(m.id) as member_count FROM teams t LEFT JOIN members m ON m.team_id=t.id GROUP BY t.id ORDER BY t.points DESC");
$max_pts = !empty($teams) ? max(array_column($teams, 'points')) : 1;

// Separate Top 3 for the podium
$top_3 = array_slice($teams, 0, 3);
$rest = array_slice($teams, 3);
?>

<main class="pt-32 pb-24 px-6 max-w-screen-xl mx-auto">
<!-- Header Section -->
<header class="text-center mb-16 relative">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tight mb-4 relative z-10">Hall of <span class="text-primary">Legends</span></h1>
<p class="text-xl text-on-surface-variant max-w-2xl mx-auto relative z-10">
                Recognizing the top contributors, hackers, and teams in the Nexus ecosystem this season.
            </p>
</header>

<?php if (count($top_3) > 1): ?>
<!-- Top 3 Podium Section -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end mb-24 max-w-4xl mx-auto">
<?php
// Define rank mappings (2, 1, 3 for a podium)
$podium_order = [];
if (isset($top_3[1])) $podium_order[] = [$top_3[1], 2, 'silver-gradient', 'rank-shadow-2', '#E0E0E0'];
if (isset($top_3[0])) $podium_order[] = [$top_3[0], 1, 'gold-gradient', 'rank-shadow-1', '#FFD700'];
if (isset($top_3[2])) $podium_order[] = [$top_3[2], 3, 'bronze-gradient', 'rank-shadow-3', '#CD7F32'];
?>

<?php foreach ($podium_order as $idx => list($t, $rank, $grad, $shadow, $color)): 
    // Just determining physical order classes based on index (0=left/Rank2, 1=center/Rank1, 2=right/Rank3)
    $order_cls = ($rank == 1) ? 'order-1 md:order-2 z-10 -mt-12 md:mt-0' : (($rank == 2) ? 'order-2 md:order-1' : 'order-3 md:order-3');
    $initial = strtoupper(substr($t['team_name'], 0, 1));
?>
<div class="<?= $order_cls ?> flex flex-col items-center group">
<?php if ($rank == 1): ?>
<span class="material-symbols-outlined text-5xl mb-2 <?= $grad ?> <?= $shadow ?>" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
<div class="relative w-40 h-40 mb-6">
<div class="absolute inset-0 bg-secondary/20 rounded-full blur-xl animate-pulse pointer-events-none"></div>
<?php else: ?>
<div class="relative w-32 h-32 mb-6">
<div class="absolute inset-0 bg-surface-container-highest rounded-full blur-md pointer-events-none"></div>
<?php endif; ?>

<div class="relative w-full h-full rounded-full border-4 flex items-center justify-center font-bold font-headline text-5xl bg-surface-container-high group-hover:scale-105 transition-transform duration-300" style="border-color: <?= $color ?>; color: <?= $color ?>;">
    <?= $initial ?>
</div>
<div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full flex items-center justify-center text-surface font-black shadow-lg" style="background: <?= $color ?>; <?= ($rank==1) ? 'w-10 h-10 -bottom-5 text-xl' : '' ?>">
    <?= $rank ?>
</div>
</div>
<h3 class="<?= ($rank==1) ? 'text-3xl' : 'text-2xl' ?> font-bold font-headline mb-1 text-center"><?= htmlspecialchars($t['team_name']) ?></h3>
<p class="text-sm font-bold text-on-surface-variant tracking-wider uppercase mb-2">Team ID: <?= htmlspecialchars($t['team_no']) ?></p>
<div class="<?= ($rank==1) ? 'text-4xl' : 'text-3xl' ?> font-black <?= $grad ?> <?= $shadow ?>"><?= htmlspecialchars($t['points']) ?> XP</div>
</div>
<?php endforeach; ?>
</section>
<?php endif; ?>

<!-- Leaderboard List Section -->
<?php if (count($teams) > 0): ?>
<section>
<!-- Filters & Sorting (Static for now) -->
<div class="flex flex-col sm:flex-row justify-between items-center bg-surface-container p-4 rounded-xl mb-6 border border-white/5">
<div class="flex gap-2 mb-4 sm:mb-0">
<button class="px-5 py-2 rounded-lg bg-primary/10 text-primary font-bold text-sm">Global Teams</button>
</div>
<div class="flex items-center gap-3">
<span class="text-sm font-bold text-outline">Search:</span>
<input type="text" id="teamSearchInput" class="bg-surface-container-low border border-white/10 text-on-surface text-sm rounded-lg focus:ring-primary focus:border-primary block p-2 w-48" placeholder="Team name...">
</div>
</div>

<!-- The List -->
<div class="bg-surface-container-low rounded-xl border border-white/5 overflow-hidden">
<!-- List Header -->
<div class="grid grid-cols-[80px_1fr_120px_120px] gap-4 p-4 border-b border-surface text-xs font-bold text-outline uppercase tracking-wider">
<div class="text-center">Rank</div>
<div>Team</div>
<div class="text-center hidden sm:block">Members</div>
<div class="text-right pr-4">Total XP</div>
</div>

<div id="leaderboardList">
<?php foreach ($teams as $i => $t): 
    $rank = $i + 1;
    if ($rank <= 3) continue; // skip podium if we want, or keep them? 
    // They are usually in the list as well or we just render $rest.
    // Let's render everything past the first three, OR if we didn't show podium, just render all.
    $initial = strtoupper(substr($t['team_name'], 0, 1));
?>
<!-- List Item -->
<div class="team-item grid grid-cols-[80px_1fr_auto_auto] sm:grid-cols-[80px_1fr_120px_120px] gap-4 p-4 items-center hover:bg-surface-container-high transition-colors border-b border-surface/50 group cursor-pointer" data-name="<?= htmlspecialchars(strtolower($t['team_name'])) ?>">
<div class="text-center font-bold text-xl text-on-surface-variant group-hover:text-on-surface transition-colors"><?= $rank ?></div>
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-lg border border-primary/30">
    <?= $initial ?>
</div>
<div>
<div class="font-bold font-headline text-lg group-hover:text-primary transition-colors"><?= htmlspecialchars($t['team_name']) ?></div>
<div class="text-xs text-outline font-medium">Team ID: <?= htmlspecialchars($t['team_no']) ?></div>
</div>
</div>
<div class="text-center font-bold text-on-surface-variant hidden sm:block flex items-center justify-center gap-1">
<span class="material-symbols-outlined text-[16px] align-middle">person</span> <?= $t['member_count'] ?>
</div>
<div class="text-right pr-4 font-black font-headline text-on-surface"><?= $t['points'] ?></div>
</div>
<?php endforeach; ?>
</div>

<?php if (empty($rest) && count($teams) <= 3): ?>
<div class="p-8 text-center text-on-surface-variant font-bold">End of ranking.</div>
<?php endif; ?>
</div>
</section>
<?php else: ?>
    <div class="text-center py-24 text-on-surface-variant text-xl">
        No teams have been ranked yet. Check back soon!
    </div>
<?php endif; ?>

</main>

<script>
// Simple live search
const searchInput = document.getElementById('teamSearchInput');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.team-item').forEach(el => {
            if (el.dataset.name.includes(query)) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
