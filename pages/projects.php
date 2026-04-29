<?php
$page_title = 'Projects';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$projects = db_query("SELECT * FROM projects ORDER BY is_top DESC, created_at DESC");
?>

<main class="pt-32 pb-20 px-6 max-w-screen-2xl mx-auto">
<!-- Hero Section -->
<header class="relative mb-24 flex flex-col items-start">
<div class="absolute -top-20 -left-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none"></div>
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 mb-6">
<span class="w-2 h-2 rounded-full bg-secondary shadow-[0_0_8px_rgba(255,223,158,0.8)]"></span>
<span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Nexus Labs / <?= date('Y') ?> Collection</span>
</div>
<h1 class="text-6xl md:text-8xl font-headline font-extrabold tracking-tighter mb-8 max-w-4xl leading-[0.9]">
                The Forge of <span class="gradient-text">Creation.</span>
</h1>
<p class="text-xl text-on-surface-variant max-w-2xl leading-relaxed font-light">
                Witness the convergence of code, design, and hardware. Our projects are the living manifestations of our shared curiosity and technical rigor.
            </p>
</header>
<!-- Featured / CTA Card -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-24">
<div class="lg:col-span-8 group relative overflow-hidden rounded-lg bg-surface-container-low p-12 flex flex-col justify-end min-h-[450px]">
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110 opacity-40 mix-blend-overlay" data-alt="Abstract blue digital circuits networking background" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD8eb3niHJymG4HX2w7AFy_Je4RYIxqSuygVOybPzFQt-h8TaAVGsUvC7cSLfBaMg0Z_TLFUk81s2XRDwpHk_NFfcE9l8ve2zz6TB14ko07stKyhzI9JuRYnzKGdNH5XS0SNykbnsh4u5gxADqfx-mdOhLmne6jTktInyFsCKh6m6p0F9mIMSW17mYIx7N2ilBPDp3Es-kS4nFw-OzGQDbO-5rDnqAnipZedQzIlZOwmfLs9-rnY3PPWBXIGOKMlDZW6pV1L9OavLnh')"></div>
<div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/40 to-transparent"></div>
<div class="relative z-10">
<h2 class="text-4xl font-headline font-bold mb-4">Hack the Flow</h2>
<p class="text-on-surface-variant max-w-lg mb-8">Participate in our most ambitious infrastructure project yet. We are building the next-gen telemetry system for campus-wide IoT devices.</p>
<button class="bg-white text-surface px-8 py-4 rounded-full font-label font-bold text-sm tracking-widest hover:bg-primary transition-colors active:scale-95 duration-200">GET INVOLVED</button>
</div>
</div>
<div class="lg:col-span-4 bg-secondary-container p-10 rounded-lg flex flex-col justify-between relative overflow-hidden">
<div class="absolute top-0 right-0 w-32 h-32 bg-on-secondary-container/10 rounded-full -mr-16 -mt-16 blur-3xl pointer-events-none"></div>
<div class="relative z-10">
<span class="material-symbols-outlined text-on-secondary-container text-4xl mb-6" data-weight="fill">lightbulb</span>
<h3 class="text-2xl font-headline font-bold text-on-secondary-container mb-4">Have an Idea?<br/>Pitch It.</h3>
<p class="text-on-secondary-container/80 text-sm leading-relaxed mb-6">
                        We provide the compute power, the hardware lab, and the talent. You provide the vision.
                    </p>
</div>
<div class="space-y-4 relative z-10">
<div class="flex items-center gap-3 text-on-secondary-container/70 text-xs font-bold uppercase tracking-wider">
<span class="material-symbols-outlined text-sm">check_circle</span>
                        Abstract Submission
                    </div>
<div class="flex items-center gap-3 text-on-secondary-container/70 text-xs font-bold uppercase tracking-wider">
<span class="material-symbols-outlined text-sm">check_circle</span>
                        Peer Review Phase
                    </div>
<div class="flex items-center gap-3 text-on-secondary-container/70 text-xs font-bold uppercase tracking-wider">
<span class="material-symbols-outlined text-sm">check_circle</span>
                        Resource Allocation
                    </div>
</div>
</div>
</section>
<!-- Project Filter/Grid Header -->
<div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
<div>
<h2 class="text-3xl font-headline font-bold mb-2">Current Repository</h2>
<div class="h-1 w-20 bg-primary"></div>
</div>
</div>
<!-- Project Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<?php foreach ($projects as $p): 
    $tech_stack = array_map('trim', explode(',', $p['tech_stack']));
?>
<div class="bg-surface-container-low rounded-lg p-8 flex flex-col group border border-outline-variant/5 hover:border-outline-variant/20 transition-all duration-300">
<div class="flex justify-between items-start mb-6">
<?php if ($p['is_top']): ?>
<div class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest rounded-full flex items-center gap-2">
<span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                        Featured
                    </div>
<?php else: ?>
<div class="px-3 py-1 bg-surface-container-highest text-on-surface-variant text-[10px] font-bold uppercase tracking-widest rounded-full flex items-center gap-2">
<span class="material-symbols-outlined text-[12px]">code</span>
                        Project
                    </div>
<?php endif; ?>
<?php if ($p['is_top']): ?>
<span class="material-symbols-outlined text-primary group-hover:text-primary transition-colors cursor-pointer" title="Featured">star</span>
<?php endif; ?>
</div>
<h3 class="text-2xl font-headline font-bold mb-3 group-hover:text-primary transition-colors"><?= htmlspecialchars($p['title']) ?></h3>
<p class="text-on-surface-variant text-sm leading-relaxed mb-8 flex-grow">
    <?= htmlspecialchars($p['description']) ?>
</p>
<!-- Tech Stack Tags -->
<div class="flex flex-wrap gap-2 mb-8">
<?php foreach (array_slice($tech_stack, 0, 4) as $t): ?>
<span class="bg-surface-container-highest text-on-surface px-2 py-1 rounded text-xs"><?= htmlspecialchars($t) ?></span>
<?php endforeach; ?>
</div>

<div class="flex items-center justify-between pt-6 border-t border-outline-variant/10">
<div class="flex items-center gap-2 text-on-surface-variant text-sm font-label">
<span class="material-symbols-outlined text-lg">group</span>
    <?= htmlspecialchars($p['team_members']) ?>
</div>
<div class="flex gap-2">
<?php if ($p['github_url']): ?>
<a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant transition-colors" title="GitHub">
<span class="material-symbols-outlined text-lg">code</span>
</a>
<?php endif; ?>
<?php if ($p['demo_url']): ?>
<a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" class="bg-surface-container-high px-4 py-2 rounded-lg text-xs font-bold tracking-widest uppercase hover:text-primary transition-colors">Demo</a>
<?php endif; ?>
</div>
</div>
</div>
<?php endforeach; ?>

<?php if (empty($projects)): ?>
<div class="col-span-full text-center py-12 bg-surface-container-lowest rounded-lg border border-white/5">
    <p class="text-on-surface-variant">No projects available at the moment.</p>
</div>
<?php endif; ?>
</div>

<!-- Submission Guidelines Section -->
<section class="mt-32 pt-24 border-t border-outline-variant/10">
<div class="flex flex-col lg:flex-row gap-20">
<div class="lg:w-1/3">
<h2 class="text-4xl font-headline font-extrabold mb-6 tracking-tight">The Pitch <span class="text-primary">Protocol.</span></h2>
<p class="text-on-surface-variant leading-relaxed mb-8">
                        Nexus is a meritocracy of ideas. Every project starts as a pitch and evolves through rigorous collective feedback.
                    </p>
<button class="flex items-center gap-4 group">
<div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined">arrow_forward</span>
</div>
<span class="text-sm font-bold tracking-widest uppercase text-on-surface">Start your pitch</span>
</button>
</div>
<div class="lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-10">
<div class="space-y-4">
<div class="text-primary font-headline text-3xl font-black opacity-20">01</div>
<h4 class="text-xl font-bold font-headline">Technical Scope</h4>
<p class="text-sm text-on-surface-variant leading-relaxed">Projects must solve a non-trivial technical problem. We prioritize cross-disciplinary approaches that blend hardware and software.</p>
</div>
<div class="space-y-4">
<div class="text-primary font-headline text-3xl font-black opacity-20">02</div>
<h4 class="text-xl font-bold font-headline">Resource Needs</h4>
<p class="text-sm text-on-surface-variant leading-relaxed">Clearly define what you need: specialized ICs, AWS credits, lab time, or specific skillsets (e.g., UI/UX Designers, ML Engineers).</p>
</div>
<div class="space-y-4">
<div class="text-primary font-headline text-3xl font-black opacity-20">03</div>
<h4 class="text-xl font-bold font-headline">The Commons</h4>
<p class="text-sm text-on-surface-variant leading-relaxed">All funded projects must be open-source under MIT or GPL licenses. Knowledge is shared, not siloed.</p>
</div>
<div class="space-y-4">
<div class="text-primary font-headline text-3xl font-black opacity-20">04</div>
<h4 class="text-xl font-bold font-headline">Timeline</h4>
<p class="text-sm text-on-surface-variant leading-relaxed">Identify key milestones. Nexus works in 8-week 'Sprints' with public demos at the end of each cycle.</p>
</div>
</div>
</div>
</section>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
