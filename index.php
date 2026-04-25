<?php
$page_title = 'Home';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$upcoming_events = db_query_cached("SELECT * FROM events WHERE event_date >= date('now') ORDER BY event_date ASC LIMIT 3");
$top_projects    = db_query_cached("SELECT * FROM projects WHERE is_top=1 LIMIT 3");
$recent_posts    = db_query_cached("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 4");
$stats = [
    'events'    => db_query_cached("SELECT COUNT(*) c FROM events")[0]['c'],
    'projects'  => db_query_cached("SELECT COUNT(*) c FROM projects")[0]['c'],
    'materials' => db_query_cached("SELECT COUNT(*) c FROM materials")[0]['c'],
    'members'   => db_query_cached("SELECT COUNT(*) c FROM members")[0]['c'],
];
?>

<!-- Hero Section -->
<section class="relative px-4 md:px-8 py-16 md:py-40 max-w-screen-2xl mx-auto overflow-hidden">
<div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
<div class="absolute top-1/2 -left-24 w-64 h-64 bg-secondary-container/10 rounded-full blur-[100px]"></div>
<div class="relative z-10 grid grid-cols-12 gap-8">
<div class="col-span-12 lg:col-span-7">
<span class="inline-block bg-surface-container-highest text-primary font-label text-xs tracking-[0.2em] uppercase px-4 py-2 rounded-full mb-8">BUILD THE FUTURE</span>
<h1 class="font-headline text-4xl sm:text-6xl md:text-8xl font-extrabold tracking-tighter mb-8 leading-[1.1] md:leading-[0.9] text-on-surface">
                        Build. Learn.<br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary-container to-secondary">Ship Together.</span>
</h1>
<p class="text-lg md:text-2xl text-on-surface-variant max-w-xl mb-12 font-body leading-relaxed">
                        Nexus is where students turn ideas into real projects — through hackathons, workshops, open source drives, and a community that genuinely loves to code.
                    </p>
<div class="flex flex-col sm:flex-row gap-4">
<a href="/pages/contact.php" class="text-center bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-bold text-base sm:text-lg px-6 sm:px-10 py-3 sm:py-4 rounded-xl active:scale-95 duration-200 shadow-xl shadow-indigo-500/20">
                            Join the Club
                        </a>
<a href="/pages/projects.php" class="text-center bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-headline font-bold text-base sm:text-lg px-6 sm:px-10 py-3 sm:py-4 rounded-xl transition-all active:scale-95 duration-200">
                            View Projects
                        </a>
</div>
</div>
<div class="col-span-12 lg:col-span-5 mt-12 lg:mt-0 relative flex justify-center items-center">
<div class="relative w-full aspect-square bg-surface-container-low rounded-lg p-4 rotate-3 transform-gpu">
<img class="w-full h-full object-cover rounded-lg grayscale hover:grayscale-0 transition-all duration-700" data-alt="Modern dark code editor with neon syntax highlighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWuWjZTkvwsOaLU4DIr_MC3BLlX19ZX8geeAwgVd2Y2uxkW7Ld5hWp-2DEmLjsrH6Ukuv00RpOqpGgcprOAF99ihV-8d_a3oEBFBrWnSNUPeEm71Ik30th0WIKevqgISBAMeuYUFlMhH90CIjhwXTmi8qVsxVoxAlrWrwa5UiMaTf8ZT0RfyO77-Nt3hmW1jaZNpDdhKNSCaeW2e1gkm9RfvjvpDAiiQbo0ipMw5qdxyZ1yta7XhICSxkdhKSmuqL6bqEVpaOGT8ls"/>
<div class="absolute -bottom-8 -left-8 bg-surface-container-highest p-6 rounded-lg shadow-2xl -rotate-6">
<div class="flex items-center space-x-4">
<div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">terminal</span>
</div>
<div>
<div class="text-xs text-on-surface-variant font-label uppercase tracking-widest">Active Sprint</div>
<div class="text-lg font-bold font-headline">Nexus OS v2.4</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Stats Section -->
<section class="bg-surface-container-low py-12 md:py-16">
<div class="max-w-screen-2xl mx-auto px-4 md:px-8">
<div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
<div class="text-center">
<div class="text-4xl md:text-5xl font-black font-headline text-primary mb-2"><?= $stats['members'] ?>+</div>
<div class="text-sm font-label uppercase tracking-widest text-on-surface-variant">Active Members</div>
</div>
<div class="text-center">
<div class="text-4xl md:text-5xl font-black font-headline text-primary mb-2"><?= $stats['events'] ?></div>
<div class="text-sm font-label uppercase tracking-widest text-on-surface-variant">Events Held</div>
</div>
<div class="text-center">
<div class="text-4xl md:text-5xl font-black font-headline text-primary mb-2"><?= $stats['projects'] ?>+</div>
<div class="text-sm font-label uppercase tracking-widest text-on-surface-variant">Projects Shipped</div>
</div>
<div class="text-center">
<div class="text-4xl md:text-5xl font-black font-headline text-primary mb-2"><?= $stats['materials'] ?></div>
<div class="text-sm font-label uppercase tracking-widest text-on-surface-variant">Learning Resources</div>
</div>
</div>
</div>
</section>

<!-- What's Coming Up Section -->
<section class="py-16 md:py-24 max-w-screen-2xl mx-auto px-4 md:px-8">
<div class="flex justify-between items-end mb-16">
<div>
<h2 class="text-3xl md:text-5xl font-extrabold font-headline mb-4">What's Coming Up</h2>
<p class="text-on-surface-variant">Mark your calendars for the next wave of innovation.</p>
</div>
<a href="/pages/events.php" class="hidden md:flex items-center text-primary font-bold font-headline hover:gap-2 transition-all">
                    View full calendar <span class="material-symbols-outlined ml-2">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<?php if (empty($upcoming_events)): ?>
    <p class="text-on-surface-variant col-span-3 text-center py-8">No upcoming events. Check back soon!</p>
<?php else: ?>
    <?php foreach ($upcoming_events as $ev): 
        $d = new DateTime($ev['event_date']);
    ?>
    <div class="group bg-surface-container-lowest p-8 rounded-lg hover:bg-surface-container-high transition-all duration-300">
    <div class="text-secondary font-black font-headline text-4xl mb-4"><?= $d->format('d') ?></div>
    <div class="text-xs font-label uppercase tracking-widest text-on-surface-variant mb-6"><?= $d->format('F Y') ?></div>
    <h3 class="text-2xl font-bold font-headline mb-4 group-hover:text-primary transition-colors"><?= htmlspecialchars($ev['title']) ?></h3>
    <p class="text-on-surface-variant mb-8 line-clamp-2"><?= htmlspecialchars($ev['description']) ?></p>
    <div class="flex items-center space-x-2 text-sm text-outline">
    <span class="material-symbols-outlined text-sm">location_on</span>
    <span><?= htmlspecialchars($ev['location']) ?> (<?= htmlspecialchars($ev['event_time']) ?>)</span>
    </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<div class="mt-8 text-center md:hidden">
    <a href="/pages/events.php" class="text-primary font-bold font-headline hover:underline">View full calendar</a>
</div>
</section>

<!-- Featured Projects Section -->
<section class="py-16 md:py-24 bg-surface-container-low overflow-hidden">
<div class="max-w-screen-2xl mx-auto px-4 md:px-8">
<div class="mb-20">
<h2 class="text-3xl md:text-5xl font-extrabold font-headline mb-4">Featured Projects</h2>
<p class="text-on-surface-variant">Selected works from our most dedicated builders.</p>
</div>
<div class="flex flex-nowrap space-x-12 pb-12 overflow-x-auto scrollbar-hide">
<?php foreach ($top_projects as $i => $p): 
    // Alternate images
    $images = [
        "https://lh3.googleusercontent.com/aida-public/AB6AXuCRIUpFCfZevLU5D_7LHgfG3SDMfj__8z9BVKVj0-e8CIouTWzjXHpftqi6VWpPop2QE8YE79KJVdLfrz3dxEZKBojKR3HzykLPReK2h40pm9ZWNxrU4I-0a9yhfeNHXCXi8LHQzlh-8l5MNe6dUT8beuc01BjqKEB7ZjvSZqxa68YvGEdCLMkuKQ17eiin95RJCO0DqjBgjWL8MSzBYdMxf9pMXUcRl80o9jCZk5oCx1pT7CVTrHYgbeJJ9C2zSNKw_hfQ0dIsgV38",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuCaKkoI5MtslDV1JTxlzwJgb_cRBY0ehQFd-xzoqvuHMben88B7p_xy_lqz7wTm8t1Y4Wjt6e3pfdND8Irzw9dQrnqWm5Fuk2LcsXKhjUgbRuq3pMM4UJ6PWOuTwUIjAw6q46-5r-rRUe-Mog0f9KjAmlqg-YnrQXPKtOr_bQ79x5oD-TpCgFvW45uP_W7N81tEdYzt6KFwINh28V9BrLDsOBJY80cntRunSdOnUnpfIG4BlCgPgb_gs4xcVYRrfR6tyAkuKrBVwd7M",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuDVpPk8if07ImceSDUeqtovefpowgqZFgUW-vxpiDCr2l6T40aR-GMDAHybli5JW-P9BXJoDAkLhGJpEdY-z7pg7XOJflgmqul6LtQttM0VuDEyz-VNMhz_kyKJQ_7ZP6RVOq7FmBtclqRszo4N2wDa1MHmC4Hibs-gJyNL-8lmuInuJSLrRywBdlusP3_CWEfPriVxrDGZ6CTbh3Jl4kfTBxqkXtDyrh278c6StAdl-9DgqJ2rBaBrT7Fk1r3ShYuB9iYHv574l_IN"
    ];
    $img = $images[($i) % count($images)];
    $tech_stack = array_slice(explode(',', $p['tech_stack']), 0, 1);
    $primary_tag = count($tech_stack) > 0 ? trim($tech_stack[0]) : "Project";
?>
<div class="flex-none w-[85vw] md:w-[600px] <?= $i % 2 === 1 ? 'md:mt-20' : '' ?>">
<div class="relative group">
<div class="aspect-[16/10] overflow-hidden rounded-lg">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="<?= $img ?>"/>
</div>
<div class="absolute -bottom-10 left-8 right-8 bg-surface p-10 rounded-lg shadow-2xl border border-white/5">
<div class="flex items-center space-x-2 mb-4">
<?php if ($p['is_top']): ?>
<span class="px-3 py-1 bg-secondary/20 text-secondary text-[10px] font-bold uppercase tracking-widest rounded-full">Featured</span>
<?php endif; ?>
<span class="text-on-surface-variant text-sm font-label"><?= htmlspecialchars($primary_tag) ?></span>
</div>
<h3 class="text-3xl font-black font-headline mb-2 leading-none"><?= htmlspecialchars($p['title']) ?></h3>
<p class="text-on-surface-variant mb-6"><?= htmlspecialchars($p['description']) ?></p>
<div class="flex space-x-4">
<?php if ($p['demo_url']): ?>
<a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" class="material-symbols-outlined text-primary hover:text-primary-fixed transition-colors">link</a>
<?php endif; ?>
<?php if ($p['github_url']): ?>
<a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="material-symbols-outlined text-outline hover:text-on-surface transition-colors">code</a>
<?php endif; ?>
</div>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</section>

<!-- Latest Updates Section -->
<section class="py-16 md:py-24 max-w-screen-2xl mx-auto px-4 md:px-8">
<h2 class="text-3xl md:text-5xl font-extrabold font-headline mb-12 md:mb-16 text-center">Latest Updates</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<?php 
$icons = ["rocket_launch", "forum", "verified", "campaign"];
$colors = [
    "bg-primary/10 text-primary group-hover:bg-primary group-hover:text-on-primary", 
    "bg-secondary-container/10 text-secondary-fixed-dim group-hover:bg-secondary-container group-hover:text-on-secondary-container",
    "bg-tertiary-container/10 text-tertiary group-hover:bg-tertiary-container group-hover:text-on-tertiary-container",
    "bg-error-container/10 text-error group-hover:bg-error-container group-hover:text-on-error-container"
];

foreach ($recent_posts as $i => $post): 
    $icon = $icons[$i % count($icons)];
    $color_cls = $colors[$i % count($colors)];
?>
<div class="bg-surface-container-low p-6 rounded-lg group hover:-translate-y-1 transition-transform">
<div class="w-10 h-10 rounded flex items-center justify-center mb-6 transition-all <?= $color_cls ?>">
<span class="material-symbols-outlined"><?= $icon ?></span>
</div>
<div class="text-xs font-label text-outline mb-2 uppercase tracking-widest"><?= htmlspecialchars($post['category']) ?> • <?= date('d M Y', strtotime($post['created_at'])) ?></div>
<h4 class="font-bold mb-3 font-headline"><?= htmlspecialchars($post['title']) ?></h4>
<p class="text-sm text-on-surface-variant leading-relaxed"><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
</div>
<?php endforeach; ?>
</div>
</section>

<!-- Ready to Build Footer CTA -->
<section class="py-20 md:py-32 relative overflow-hidden">
<div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 to-surface pointer-events-none"></div>
<div class="max-w-4xl mx-auto px-4 md:px-8 relative z-10 text-center">
<h2 class="text-4xl md:text-7xl font-black font-headline mb-6 md:mb-8 tracking-tighter leading-tight">Ready to start building?</h2>
<p class="text-lg md:text-2xl text-on-surface-variant mb-10 md:mb-12 max-w-2xl mx-auto leading-relaxed">
                    Join a collective of motivated students pushing the boundaries of what's possible in campus tech.
                </p>
<a href="/pages/contact.php" class="inline-block bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-bold text-lg md:text-xl px-8 py-4 md:px-12 md:py-5 rounded-full active:scale-95 duration-200 shadow-2xl shadow-indigo-500/40">
                    Join Nexus Today
                </a>
</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
