<?php
$page_title = 'Materials';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$materials = db_query("SELECT * FROM materials ORDER BY category, difficulty");
$grouped   = [];
foreach ($materials as $m) {
    if (!$m['category']) $m['category'] = 'general';
    $grouped[$m['category']][] = $m;
}

$cat_icons  = [
    'python'=>'logo_dev',
    'web'=>'language',
    'dsa'=>'account_tree',
    'ml'=>'psychology',
    'tools'=>'build',
    'general'=>'folder',
    'database'=>'storage',
    'devops'=>'cloud',
    'design'=>'palette'
];

$diff_ui = [
    'beginner' => ['bg'=>'bg-secondary-container/20', 'text'=>'text-secondary-fixed-dim', 'icon'=>'circle'],
    'intermediate' => ['bg'=>'bg-primary-container/20', 'text'=>'text-primary', 'icon'=>'change_history'],
    'advanced' => ['bg'=>'bg-error-container/20', 'text'=>'text-error', 'icon'=>'star']
];
?>

<main class="pt-32 pb-24 px-6 max-w-screen-xl mx-auto min-h-screen">
<!-- Hero Section -->
<header class="text-center mb-20 relative">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-secondary-container/10 rounded-full blur-[100px] pointer-events-none"></div>
<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 mb-6 relative z-10">
    <span class="material-symbols-outlined text-xs text-secondary-fixed-dim">menu_book</span>
    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Learn &amp; Grow</span>
</span>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tight mb-4 relative z-10">Study <span class="text-secondary-fixed-dim">Materials</span></h1>
<p class="text-xl text-on-surface-variant max-w-2xl mx-auto relative z-10 mb-8">
    Curated resources handpicked by club seniors to accelerate your learning journey.
</p>
</header>

<section>
    <?php if (empty($grouped)): ?>
    <div class="text-center py-24 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl opacity-20 mb-4 block">menu_book</span>
        <p class="text-xl">No materials yet. Check back soon!</p>
    </div>
    <?php endif; ?>

    <?php foreach ($grouped as $cat => $items): ?>
    <div class="mb-16">
        <div class="flex items-center gap-4 mb-8 pb-4 border-b border-white/5">
            <div class="w-12 h-12 rounded-xl bg-surface-container-high border border-white/10 flex items-center justify-center text-on-surface group-hover:bg-primary/20 transition-colors">
                <span class="material-symbols-outlined text-2xl"><?= $cat_icons[strtolower($cat)] ?? 'folder' ?></span>
            </div>
            <div>
                <h2 class="text-2xl font-black font-headline text-on-surface uppercase tracking-wider"><?= htmlspecialchars(ucfirst($cat)) ?> Resources</h2>
                <p class="text-xs text-on-surface-variant font-label mt-1"><?= count($items) ?> items available</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($items as $m):
                $url = $m['external_url'] ?: ($m['file_url'] ?: '#'); 
                $d_diff = strtolower($m['difficulty']) ?: 'beginner';
                $ui = $diff_ui[$d_diff] ?? $diff_ui['beginner'];
            ?>
            <a href="<?= htmlspecialchars($url) ?>" <?= $m['external_url'] ? 'target="_blank"' : '' ?> class="group flex flex-col p-6 rounded-2xl bg-surface-container-low border border-white/5 hover:border-white/20 hover:bg-surface-container-high transition-all hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-surface-container-highest rounded-bl-full -mr-8 -mt-8 opacity-50 group-hover:bg-primary/10 transition-colors pointer-events-none"></div>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-outline group-hover:text-on-surface transition-colors"><?= $cat_icons[strtolower($cat)] ?? 'article' ?></span>
                    <h3 class="font-bold text-lg text-on-surface flex-1 truncate font-headline group-hover:text-primary transition-colors"><?= htmlspecialchars($m['title']) ?></h3>
                </div>
                
                <p class="text-sm text-on-surface-variant flex-1 mb-6 line-clamp-2 leading-relaxed">
                    <?= htmlspecialchars($m['description']) ?>
                </p>
                
                <div class="flex items-center justify-between mt-auto">
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full <?= $ui['bg'] ?> <?= $ui['text'] ?>">
                        <span class="material-symbols-outlined text-[12px]"><?= $ui['icon'] ?></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider"><?= ucfirst($d_diff) ?></span>
                    </div>
                    <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors">open_in_new</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Suggestion CTA -->
    <div class="mt-20 p-10 rounded-2xl bg-surface-container-low border border-primary/20 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8 group">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl">lightbulb</span>
            </div>
            <div>
                <h3 class="text-2xl font-black font-headline text-on-surface mb-2">Know a great resource?</h3>
                <p class="text-on-surface-variant">Suggest a book, course, or tool that helped you — we might add it here for everyone.</p>
            </div>
        </div>
        <a href="/pages/contact.php?type=resource" class="relative z-10 flex items-center gap-2 whitespace-nowrap px-8 py-4 rounded-full bg-primary hover:bg-primary-fixed transition-colors text-on-primary font-bold font-label uppercase tracking-widest text-sm shadow-lg hover:shadow-primary/30 active:scale-95 duration-200">
            <span class="material-symbols-outlined text-[18px]">add</span> Suggest Resource
        </a>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
