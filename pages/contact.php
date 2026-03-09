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

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">group_add</span>Get Involved</div>
        <h1>Join Nexus</h1>
        <p>Whether you're a complete beginner or a seasoned engineer — there's a place for you here.</p>
    </div>
</div>

<section class="section">
    <div class="contact-grid">
        <!-- FORM -->
        <div>
            <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:1.5rem;">Send us a Message</h2>

            <?php if ($success): ?>
            <div class="alert alert-success">
                <span class="msi">check_circle</span>
                Message sent! We'll reach out to you soon.
            </div>
            <?php elseif ($error): ?>
            <div class="alert alert-error">
                <span class="msi">error</span>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/pages/contact.php">
                <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="name" placeholder="e.g. Ravi Kumar" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@college.edu" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>I want to…</label>
                    <select name="type">
                        <option value="join"     <?= $prefill_type==='join'     ?'selected':'' ?>>Join the Club</option>
                        <option value="project"  <?= $prefill_type==='project'  ?'selected':'' ?>>Collaborate on a Project</option>
                        <option value="resource" <?= $prefill_type==='resource' ?'selected':'' ?>>Suggest a Resource</option>
                        <option value="general"  <?= $prefill_type==='general'  ?'selected':'' ?>>General Enquiry</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" placeholder="Tell us about yourself and what you'd like to build…"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <span class="msi" style="font-size:18px">send</span>
                    Send Message
                </button>
            </form>
        </div>

        <!-- INFO -->
        <div>
            <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:1.5rem;">Why Join Nexus?</h2>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php $perks = [
                    ['build','Build Real Projects','Work on projects that matter — hackathons, freelance, open source.','#e8f0fe','var(--primary)'],
                    ['menu_book','Learn Faster Together','Peer-led workshops and curated resources to accelerate growth.','#dcfce7','var(--success)'],
                    ['groups','Network & Collaborate','Meet talented peers across CS, ECE, and other departments.','#ede9fe','#7c3aed'],
                    ['emoji_events','Win Competitions','Train and team up for national-level hackathons and contests.','#fef3c7','var(--accent)'],
                    ['work','Career Prep','Resume reviews, mock interviews, and industry guest sessions.','#e0f2fe','var(--info)'],
                ]; foreach ($perks as [$icon, $title, $desc, $bg, $color]): ?>
                <div style="display:flex;align-items:flex-start;gap:1rem;padding:1rem;background:white;border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);">
                    <div style="width:42px;height:42px;border-radius:12px;background:<?= $bg ?>;color:<?= $color ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span class="msi"><?= $icon ?></span>
                    </div>
                    <div>
                        <strong style="font-size:0.9rem;"><?= $title ?></strong>
                        <p style="font-size:0.82rem;color:var(--text-mid);margin-top:0.2rem;line-height:1.6;"><?= $desc ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:1.5rem;padding:1.25rem;background:var(--primary-light);border:1px solid rgba(26,115,232,0.2);border-radius:var(--radius);">
                <p style="font-size:0.75rem;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
                    <span class="msi" style="font-size:16px">location_on</span> Find Us
                </p>
                <p style="font-size:0.875rem;color:var(--text-mid);line-height:2;">
                    📍 CS Department, Room 101<br>
                    📧 nexus@college.edu<br>
                    💬 discord.gg/nexus
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
