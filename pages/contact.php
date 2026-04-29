<?php
$page_title = 'Join Us';
require_once __DIR__ . '/../includes/db.php';

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');
    $type    = trim($_POST['type']    ?? 'join');

    if (!$name || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please provide a valid name and email address.';
    } elseif (!empty($_POST['website'])) {
        // honeypot
        $success = true;
    } else {
        db_execute("INSERT INTO contact_requests (name, email, message, request_type) VALUES (?,?,?,?)",
            [$name, $email, $message, $type]);
        $success = true;
    }
}

require_once __DIR__ . '/../includes/header.php';
$prefill_type = $_GET['type'] ?? 'join';
?>

<main class="pt-32 pb-24 px-6 max-w-screen-xl mx-auto min-h-screen">
<!-- Hero Section -->
<header class="text-center mb-16 relative">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[600px] h-[300px] bg-primary-container/10 rounded-full blur-[100px] pointer-events-none"></div>
<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/20 mb-6 relative z-10">
    <span class="material-symbols-outlined text-xs text-primary">group_add</span>
    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Get Involved</span>
</span>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tight mb-4 relative z-10">Join <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary-container">Nexus</span></h1>
<p class="text-xl text-on-surface-variant max-w-2xl mx-auto relative z-10 mb-8 font-light">
    Whether you're a complete beginner or a seasoned engineer — there's a place for you here.
</p>
</header>

<section class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 bg-surface-container-low border border-white/5 rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- FORM -->
    <div class="lg:col-span-7 relative z-10 pr-0 lg:pr-8 border-b-2 lg:border-b-0 lg:border-r-2 border-white/5 pb-10 lg:pb-0">
        <h2 class="text-3xl font-black font-headline text-on-surface mb-8">Send us a Message</h2>

        <?php if ($success): ?>
        <div class="mb-8 p-4 rounded-xl bg-secondary-container/20 border border-secondary/30 flex items-center gap-4 text-secondary-fixed-dim">
            <span class="material-symbols-outlined text-2xl">check_circle</span>
            <div class="font-bold">Message sent! We'll reach out to you soon.</div>
        </div>
        <?php elseif ($error): ?>
        <div class="mb-8 p-4 rounded-xl bg-error-container/20 border border-error/30 flex items-center gap-4 text-error">
            <span class="material-symbols-outlined text-2xl">error</span>
            <div class="font-bold"><?= htmlspecialchars($error) ?></div>
        </div>
        <?php endif; ?>

        <form method="POST" action="/pages/contact.php" class="space-y-6">
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold tracking-widest uppercase text-on-surface-variant font-label">Your Name</label>
                    <input type="text" name="name" placeholder="e.g. Ravi Kumar" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
                           class="w-full bg-surface-container border border-white/10 rounded-xl px-5 py-4 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-outline/50">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold tracking-widest uppercase text-on-surface-variant font-label">Email Address</label>
                    <input type="email" name="email" placeholder="you@college.edu" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                           class="w-full bg-surface-container border border-white/10 rounded-xl px-5 py-4 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-outline/50">
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-bold tracking-widest uppercase text-on-surface-variant font-label">I want to…</label>
                <select name="type" class="w-full bg-surface-container border border-white/10 rounded-xl px-5 py-4 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none form-select">
                    <option value="join"     <?= $prefill_type==='join'     ?'selected':'' ?>>Join the Club</option>
                    <option value="project"  <?= $prefill_type==='project'  ?'selected':'' ?>>Collaborate on a Project</option>
                    <option value="resource" <?= $prefill_type==='resource' ?'selected':'' ?>>Suggest a Resource</option>
                    <option value="general"  <?= $prefill_type==='general'  ?'selected':'' ?>>General Enquiry</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-bold tracking-widest uppercase text-on-surface-variant font-label">Message</label>
                <textarea name="message" placeholder="Tell us about yourself and what you'd like to build…" rows="5"
                          class="w-full bg-surface-container border border-white/10 rounded-xl px-5 py-4 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-outline/50 resize-y"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
            </div>
            
            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-primary to-primary-container hover:shadow-lg hover:shadow-primary/30 text-on-primary font-bold px-10 py-5 rounded-xl uppercase tracking-widest flex items-center justify-center gap-3 transition-all active:scale-95 duration-200">
                <span class="material-symbols-outlined text-[20px]">send</span>
                Send Message
            </button>
        </form>
    </div>

    <!-- INFO -->
    <div class="lg:col-span-5 relative z-10 pl-0 lg:pl-8 flex flex-col justify-center">
        <h2 class="text-3xl font-black font-headline text-on-surface mb-8">Why Join Nexus?</h2>
        <div class="space-y-6">
            <?php $perks = [
                ['build','Build Real Projects','Work on projects that matter — hackathons, freelance, open source.','bg-primary/20','text-primary'],
                ['menu_book','Learn Faster Together','Peer-led workshops and curated resources to accelerate growth.','bg-secondary-container/20','text-secondary-fixed-dim'],
                ['groups','Network & Collaborate','Meet talented peers across CS, ECE, and other departments.','bg-tertiary/20','text-tertiary-fixed-dim'],
                ['emoji_events','Win Competitions','Train and team up for national-level hackathons and contests.','bg-error-container/20','text-error'],
                ['work','Career Prep','Resume reviews, mock interviews, and industry guest sessions.','bg-surface-container-highest','text-on-surface'],
            ]; foreach ($perks as [$icon, $title, $desc, $bg, $color]): ?>
            <div class="flex items-start gap-5 p-4 rounded-2xl bg-surface hover:bg-surface-container-high transition-colors border border-white/5">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 <?= $bg ?> <?= $color ?> shadow-[inset_0_1px_rgba(255,255,255,0.1)]">
                    <span class="material-symbols-outlined text-2xl"><?= $icon ?></span>
                </div>
                <div>
                    <strong class="block text-lg font-bold font-headline mb-1 text-on-surface"><?= $title ?></strong>
                    <p class="text-sm text-on-surface-variant leading-relaxed"><?= $desc ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
