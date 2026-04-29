<?php
$page_title = 'Gallery';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$albums = db_query("SELECT * FROM gallery_albums ORDER BY created_at DESC");
?>

<main class="pt-32 pb-24 px-6 max-w-screen-xl mx-auto min-h-screen">
<!-- Hero Section -->
<header class="text-center mb-16 relative">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[600px] h-[300px] bg-tertiary-container/10 rounded-full blur-[100px] pointer-events-none"></div>
<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 mb-6 relative z-10">
    <span class="material-symbols-outlined text-xs text-tertiary">photo_library</span>
    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Memories</span>
</span>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tight mb-4 relative z-10">Our <span class="text-tertiary">Gallery</span></h1>
<p class="text-xl text-on-surface-variant max-w-2xl mx-auto relative z-10 mb-8">
    Snapshots from hackathons, workshops, and club meetups.
</p>
</header>

<section>
<?php if (empty($albums)): ?>
    <div class="text-center py-24 text-on-surface-variant bg-surface-container-low rounded-2xl border border-white/5">
        <span class="material-symbols-outlined text-6xl opacity-20 mb-4 block">photo_library</span>
        <p class="text-xl">Photos will appear here soon.</p>
    </div>
<?php else: ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($albums as $a):
        $has_drive = !empty($a['drive_folder_url']);
        $thumb_src = !empty($a['thumbnail']) ? '/assets/uploads/gallery/' . htmlspecialchars($a['thumbnail']) : '';
    ?>
    <div class="group bg-surface-container-low border border-white/5 rounded-2xl overflow-hidden hover:border-white/20 hover:-translate-y-1 transition-all duration-300 flex flex-col cursor-pointer shadow-lg"
         onclick="window.location.href='gallery_event.php?id=<?= $a['id'] ?>'">

        <!-- Thumbnail -->
        <div class="aspect-[4/3] relative overflow-hidden bg-surface-container-highest">
            <?php if ($thumb_src): ?>
            <img src="<?= $thumb_src ?>"
                 alt="<?= htmlspecialchars($a['event_name']) ?>"
                 class="w-full h-full object-cover transition-transform duration-700 <?= $has_drive ? 'group-hover:scale-105' : '' ?>"
                 onerror="this.style.display='none'">
            <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <span class="material-symbols-outlined text-5xl text-primary opacity-30">photo_library</span>
            </div>
            <?php endif; ?>

            <!-- Gradient overlay -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent pointer-events-none"></div>

            <!-- Event name on the image -->
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <div class="text-white font-bold font-headline text-lg leading-tight mb-1">
                    <?= htmlspecialchars($a['event_name']) ?>
                </div>
                <?php if ($a['description']): ?>
                <div class="text-white/70 text-xs line-clamp-2">
                    <?= htmlspecialchars($a['description']) ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- "View photos" badge -->
            <div class="absolute top-3 right-3 bg-white/10 backdrop-blur-md rounded-full px-3 py-1.5 flex items-center gap-1.5 text-xs font-bold text-white shadow-lg border border-white/10 opacity-0 group-hover:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-[14px]">visibility</span> View
            </div>
        </div>

        <!-- Card footer -->
        <div class="p-4 flex items-center justify-between mt-auto">
            <div class="text-xs text-on-surface-variant font-label flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                <span><?= date('d M Y', strtotime($a['created_at'])) ?></span>
            </div>
            <?php if ($has_drive): ?>
            <div class="text-xs font-bold text-primary flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">folder_open</span> Drive
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>
</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
