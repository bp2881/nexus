<?php
$page_title = 'Event Gallery';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo "<main class='pt-32 pb-24 px-6 text-center text-on-surface-variant min-h-screen'><h2 class='text-2xl'>Event not found.</h2></main>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$album = db_query("SELECT * FROM gallery_albums WHERE id=?", [$id])[0] ?? null;
if (!$album) {
    echo "<main class='pt-32 pb-24 px-6 text-center text-on-surface-variant min-h-screen'><h2 class='text-2xl'>Event not found.</h2></main>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$highlights = db_query("SELECT * FROM gallery_highlights WHERE album_id=? ORDER BY created_at ASC", [$id]);
$has_drive = !empty($album['drive_folder_url']);
?>

<main class="pt-32 pb-24 px-6 max-w-screen-2xl mx-auto min-h-screen">
<!-- Hero Section -->
<header class="text-center mb-16 relative">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[600px] h-[300px] bg-tertiary-container/10 rounded-full blur-[100px] pointer-events-none"></div>

<a href="gallery.php" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 hover:bg-surface-container-highest transition-colors mb-6 relative z-10 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
    <span class="material-symbols-outlined text-[14px]">arrow_back</span> Back to Gallery
</a>

<h1 class="text-4xl md:text-6xl font-extrabold font-headline tracking-tight mb-4 relative z-10 text-on-surface">
    <?= htmlspecialchars($album['event_name']) ?>
</h1>
<?php if ($album['description']): ?>
<p class="text-lg text-on-surface-variant max-w-2xl mx-auto relative z-10 mb-8">
    <?= htmlspecialchars($album['description']) ?>
</p>
<?php endif; ?>

<?php if ($has_drive): ?>
<div class="relative z-10 mt-8">
    <a href="<?= htmlspecialchars($album['drive_folder_url']) ?>" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-br from-tertiary to-tertiary-container text-on-tertiary font-bold font-label uppercase tracking-widest text-sm px-8 py-3 rounded-full hover:shadow-[0_0_20px_rgba(173,199,255,0.4)] transition-shadow">
        <span class="material-symbols-outlined text-[18px]">folder_open</span> View Album in Drive
    </a>
</div>
<?php endif; ?>
</header>

<section>
    <div class="flex items-center justify-center mb-12">
        <div class="h-px bg-white/10 flex-1 max-w-[100px] mr-4"></div>
        <h2 class="text-2xl font-black font-headline tracking-widest uppercase text-center text-on-surface">Highlights</h2>
        <div class="h-px bg-white/10 flex-1 max-w-[100px] ml-4"></div>
    </div>

    <?php if (empty($highlights)): ?>
    <div class="text-center text-on-surface-variant py-24 bg-surface-container-low rounded-2xl border border-white/5">
        <span class="material-symbols-outlined text-6xl opacity-20 block mb-4">image_not_supported</span>
        <p class="text-lg">No highlight photos uploaded yet.</p>
    </div>
    <?php else: ?>
    <!-- Masonry-like or standard grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        <?php foreach ($highlights as $hl): ?>
        <div class="group relative rounded-xl overflow-hidden bg-surface-container-highest aspect-square border border-white/5 shadow-md cursor-pointer" onclick="openImageModal('/assets/uploads/gallery/<?= htmlspecialchars($hl['photo_url']) ?>')">
            <img src="/assets/uploads/gallery/<?= htmlspecialchars($hl['photo_url']) ?>" 
                 alt="Highlight" 
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                <span class="material-symbols-outlined text-white text-4xl drop-shadow-lg">zoom_in</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
</main>

<!-- Image Zoom Modal -->
<div id="imageModalOverlay" class="fixed inset-0 bg-black/95 z-[200] hidden items-center justify-center opacity-0 transition-opacity duration-300">
    <button onclick="closeImageModal()" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
        <span class="material-symbols-outlined text-3xl">close</span>
    </button>
    <img id="modalImage" src="" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl scale-95 transition-transform duration-300">
</div>

<script>
const imageModalOverlay = document.getElementById('imageModalOverlay');
const modalImage = document.getElementById('modalImage');

function openImageModal(src) {
    modalImage.src = src;
    imageModalOverlay.classList.remove('hidden');
    imageModalOverlay.classList.add('flex');
    setTimeout(() => {
        imageModalOverlay.classList.remove('opacity-0');
        modalImage.classList.remove('scale-95');
    }, 10);
}

function closeImageModal() {
    imageModalOverlay.classList.add('opacity-0');
    modalImage.classList.add('scale-95');
    setTimeout(() => {
        imageModalOverlay.classList.remove('flex');
        imageModalOverlay.classList.add('hidden');
        modalImage.src = '';
    }, 300);
}

imageModalOverlay.addEventListener('click', e => {
    if (e.target === imageModalOverlay) closeImageModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !imageModalOverlay.classList.contains('hidden')) {
        closeImageModal();
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
