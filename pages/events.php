<?php
$page_title = 'Events';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$filter = $_GET['cat'] ?? 'all';
$events = $filter !== 'all'
    ? db_query("SELECT * FROM events WHERE category=? ORDER BY event_date ASC", [$filter])
    : db_query("SELECT * FROM events ORDER BY event_date ASC");
$today  = date('Y-m-d');

$cats   = ['all','hackathon','workshop','talk','session','competition'];

$upcoming = array_filter($events, fn($e) => $e['event_date'] >= $today);
$past     = array_filter($events, fn($e) => $e['event_date'] < $today);

// Extract featured event if we have any upcoming
$featured = null;
if (!empty($upcoming)) {
    $featured = array_shift($upcoming); 
}
?>

<main class="pt-32 pb-24 px-6 md:px-12 max-w-screen-2xl mx-auto">
<!-- Hero Section -->
<header class="mb-20">
<h1 class="font-headline text-6xl md:text-8xl font-extrabold tracking-tighter text-indigo-100 mb-6">
                The Nexus <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary-container to-tertiary">Gatherings.</span>
</h1>
<p class="text-on-surface-variant text-xl md:text-2xl max-w-2xl font-light leading-relaxed">
                Connect with minds shaping the digital frontier. From deep-dive sessions to high-stakes hackathons.
            </p>
</header>
<!-- Filter Bar -->
<div class="flex flex-wrap items-center gap-4 mb-12">
<?php foreach ($cats as $cat): ?>
<a href="?cat=<?= urlencode($cat) ?>" class="px-6 py-2 rounded-full font-label text-sm font-medium tracking-wider transition-colors <?= $filter === $cat ? 'bg-secondary-container text-on-secondary-container font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' ?>">
    <?= ucfirst($cat) ?>
</a>
<?php endforeach; ?>
</div>

<?php if ($featured): ?>
<!-- Featured Event Card -->
<section class="editorial-grid mb-32 grid grid-cols-12 gap-8">
<div class="col-span-12 lg:col-span-7 relative group">
<div class="absolute -inset-4 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-xl blur-2xl group-hover:opacity-100 opacity-0 transition-opacity duration-500"></div>
<div class="relative overflow-hidden rounded-lg aspect-[16/9] bg-surface-container-low">
<img class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwFzBIJu82hIUpl7BimJusHFcwo9BlTRzr-bc26kSDYtudvIkIkdjTx3tW-2LoDTg-S7wZZFXhPTOwOvTr9X9490QksBC2UHue_FSeSYy_dHkEOxo3HfBnYWXXqCoKQMNfeNh9UtYsOmqb_n6n_KwtfbigJl0SNzC_xTE54jUhdqcEBJWxpE-cHXzGe6zANFz0p7DFx1EeXq34uZfwl3nvxemPy0xoqLedJ5Pxb7THPPfEKoLU2VkotsuXMLZQVgs9hpOMuk9uBxID"/>
<div class="absolute top-6 left-6">
<span class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-error-container text-on-error-container font-label text-xs font-black tracking-widest uppercase">
<span class="w-2 h-2 rounded-full bg-error animate-pulse"></span>
                            Live Registration
                        </span>
</div>
</div>
</div>
<div class="col-span-12 lg:col-span-5 flex flex-col justify-center">
<div class="inline-block px-3 py-1 rounded-md bg-primary/10 text-primary font-label text-xs font-bold tracking-widest uppercase mb-4 w-max">Featured <?= htmlspecialchars(ucfirst($featured['category'])) ?></div>
<h2 class="font-headline text-4xl md:text-5xl font-bold text-indigo-100 mb-6 leading-tight"><?= htmlspecialchars($featured['title']) ?></h2>
<p class="text-on-surface-variant text-lg mb-8 leading-relaxed">
    <?= htmlspecialchars($featured['description']) ?>
</p>
<div class="flex flex-col gap-4 mb-10 text-on-surface-variant font-label text-sm">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">calendar_today</span>
<span><?= date('F d, Y', strtotime($featured['event_date'])) ?> &bull; <?= htmlspecialchars($featured['event_time']) ?></span>
</div>
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">location_on</span>
<span><?= htmlspecialchars($featured['location']) ?></span>
</div>
</div>
<button class="w-fit bg-gradient-to-br from-primary to-primary-container text-on-primary px-10 py-4 rounded-full font-label font-bold tracking-widest uppercase hover:shadow-[0_0_30px_rgba(192,193,255,0.4)] transition-all">
                    Register Now
                </button>
</div>
</section>
<?php endif; ?>

<!-- Upcoming Schedule -->
<section class="mb-32">
<div class="flex justify-between items-end mb-12">
<div>
<h2 class="font-headline text-3xl font-bold text-indigo-100 mb-2">Upcoming Schedule</h2>
<p class="text-on-surface-variant">Reserve your spot for our next community interactions.</p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<?php if (empty($upcoming)): ?>
    <div class="col-span-3 text-on-surface-variant">No other upcoming events for this category.</div>
<?php else: ?>
<?php foreach ($upcoming as $ev): 
    $d = new DateTime($ev['event_date']);
?>
<!-- Event Card -->
<div class="bg-surface-container-low p-8 rounded-lg group hover:bg-surface-container-high transition-all duration-300 flex flex-col h-full border border-white/5">
<div class="text-primary font-headline text-lg font-black mb-6 uppercase"><?= $d->format('M d') ?></div>
<div class="flex-grow">
<div class="font-label text-xs text-on-secondary-fixed-variant bg-secondary-fixed/20 px-2 py-1 rounded inline-block mb-4 uppercase tracking-tighter"><?= htmlspecialchars($ev['category']) ?></div>
<h3 class="font-headline text-2xl font-bold text-indigo-100 mb-4 group-hover:text-primary transition-colors"><?= htmlspecialchars($ev['title']) ?></h3>
<p class="text-on-surface-variant text-sm mb-6 line-clamp-2"><?= htmlspecialchars($ev['description']) ?></p>
</div>
<div class="pt-6 border-t border-outline-variant/10 flex items-center justify-between">
<div class="flex items-center gap-2 text-on-surface-variant text-xs font-label">
<span class="material-symbols-outlined text-[18px]">schedule</span>
                            <?= htmlspecialchars($ev['event_time']) ?>
                        </div>
<button class="bg-white/5 hover:bg-primary/20 text-on-surface px-5 py-2 rounded-full font-label text-xs font-bold tracking-widest uppercase transition-colors">RSVP Now</button>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</section>

<!-- Past Events -->
<?php if (!empty($past)): ?>
<section class="mb-32">
<div class="flex justify-between items-end mb-12">
<div>
<h2 class="font-headline text-3xl font-bold text-indigo-100 mb-2">Past Events</h2>
<p class="text-on-surface-variant">Explore the archives of previous Nexus gatherings.</p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 opacity-70">
<?php foreach ($past as $ev): 
    $d = new DateTime($ev['event_date']);
?>
<div class="bg-surface-container-lowest p-8 rounded-lg flex flex-col h-full border border-white/5 grayscale">
<div class="text-outline font-headline text-lg font-black mb-6 uppercase"><?= $d->format('M d, Y') ?></div>
<div class="flex-grow">
<h3 class="font-headline text-xl font-bold text-on-surface-variant mb-4"><?= htmlspecialchars($ev['title']) ?></h3>
</div>
<div class="pt-6 border-t border-outline-variant/10 flex items-center justify-between">
<div class="flex items-center gap-2 text-on-surface-variant text-xs font-label">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                            <?= htmlspecialchars($ev['location']) ?>
                        </div>
</div>
</div>
<?php endforeach; ?>
</div>
</section>
<?php endif; ?>

<!-- Archive Link Section -->
<section class="flex flex-col items-center text-center p-16 rounded-xl bg-gradient-to-b from-surface-container-low to-surface">
<h2 class="font-headline text-2xl font-bold text-indigo-100 mb-4">Looking for something specific?</h2>
<p class="text-on-surface-variant mb-8 max-w-lg">Explore our extensive archive of past talks, project presentations, and winner announcements.</p>
<button class="px-8 py-3 border border-outline-variant rounded-full text-on-surface font-label text-sm font-bold tracking-widest uppercase hover:bg-white/5 transition-all">
                Browse Past Events
            </button>
</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
