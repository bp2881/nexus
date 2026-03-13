<?php
$page_title = 'Home';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$upcoming_events = db_query_cached("SELECT * FROM events WHERE event_date >= date('now') ORDER BY event_date ASC LIMIT 3");
$top_projects    = db_query_cached("SELECT * FROM projects WHERE is_top=1 LIMIT 3");
$recent_posts    = db_query_cached("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 3");
$stats = [
    'events'    => db_query_cached("SELECT COUNT(*) c FROM events")[0]['c'],
    'projects'  => db_query_cached("SELECT COUNT(*) c FROM projects")[0]['c'],
    'materials' => db_query_cached("SELECT COUNT(*) c FROM materials")[0]['c'],
];
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-blob-1"></div>
    <div class="hero-blob-2"></div>
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-pill">
                <span class="msi" style="font-size:15px">bolt</span>
                Student Tech Club · Est. 2023
            </div>
            <h1>
                Build. <span class="hi">Learn.</span><br>
                Ship Together.
            </h1>
            <p class="hero-desc">
                Nexus is where students turn ideas into real projects — through hackathons, workshops, open source drives, and a community that genuinely loves to code.
            </p>
            <div class="hero-actions">
                <a href="/pages/contact.php" class="btn btn-primary">
                    <span class="msi" style="font-size:18px">group_add</span>
                    Join the Club
                </a>
                <a href="/pages/projects.php" class="btn btn-outline">
                    <span class="msi" style="font-size:18px">code</span>
                    View Projects
                </a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card">
                <div class="hero-card-icon hci-blue"><span class="msi">event</span></div>
                <div>
                    <div class="hero-card-num"><?= $stats['events'] ?>+</div>
                    <div class="hero-card-label">Events Hosted</div>
                </div>
            </div>
            <div class="hero-card">
                <div class="hero-card-icon hci-amber"><span class="msi">emoji_events</span></div>
                <div>
                    <div class="hero-card-num"><?= $stats['projects'] ?>+</div>
                    <div class="hero-card-label">Projects Built</div>
                </div>
            </div>
            <div class="hero-card">
                <div class="hero-card-icon hci-green"><span class="msi">menu_book</span></div>
                <div>
                    <div class="hero-card-num"><?= $stats['materials'] ?></div>
                    <div class="hero-card-label">Study Resources</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS STRIP -->
<div class="stats-strip">
    <div class="stats-strip-inner">
        <div class="stat-block">
            <div class="stat-icon"><span class="msi">group</span></div>
            <div class="stat-num">120+</div>
            <div class="stat-label">Active Members</div>
        </div>
        <div class="stat-block">
            <div class="stat-icon"><span class="msi">event</span></div>
            <div class="stat-num"><?= $stats['events'] ?>+</div>
            <div class="stat-label">Events Held</div>
        </div>
        <div class="stat-block">
            <div class="stat-icon"><span class="msi">rocket_launch</span></div>
            <div class="stat-num"><?= $stats['projects'] ?>+</div>
            <div class="stat-label">Projects Shipped</div>
        </div>
        <div class="stat-block">
            <div class="stat-icon"><span class="msi">school</span></div>
            <div class="stat-num"><?= $stats['materials'] ?></div>
            <div class="stat-label">Learning Resources</div>
        </div>
    </div>
</div>

<!-- UPCOMING EVENTS -->
<section class="section">
    <div class="section-header">
        <div class="eyebrow"><span class="msi" style="font-size:14px">calendar_month</span>What's Coming Up</div>
        <h2 class="section-title">Upcoming Events</h2>
        <p class="section-desc">Hackathons, workshops, talks and more — mark your calendar.</p>
    </div>
    <?php if (empty($upcoming_events)): ?>
    <p style="color:var(--text-dim);font-size:0.9rem;">No upcoming events. Check back soon!</p>
    <?php else: ?>
    <div class="event-list">
        <?php foreach ($upcoming_events as $ev):
            $d = new DateTime($ev['event_date']); ?>
        <div class="event-item">
            <div class="event-date-box">
                <div class="event-day"><?= $d->format('d') ?></div>
                <div class="event-month"><?= $d->format('M') ?></div>
            </div>
            <div class="event-info">
                <h3><?= htmlspecialchars($ev['title']) ?></h3>
                <div class="event-meta">
                    <span class="msi" style="font-size:14px">location_on</span><?= htmlspecialchars($ev['location']) ?>
                    <span>&nbsp;·&nbsp;</span>
                    <span class="msi" style="font-size:14px">schedule</span><?= htmlspecialchars($ev['event_time']) ?>
                    <span class="tag tag-blue" style="margin-left:0.25rem;"><?= htmlspecialchars($ev['category']) ?></span>
                </div>
            </div>
            <a href="/pages/events.php" class="btn btn-outline btn-sm">Details</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div style="margin-top:1.5rem;">
        <a href="/pages/events.php" class="btn btn-ghost">
            All Events <span class="msi" style="font-size:17px">arrow_forward</span>
        </a>
    </div>
</section>

<!-- TOP PROJECTS -->
<div class="section-alt">
    <div class="section-alt-inner">
        <div class="section-header">
            <div class="eyebrow"><span class="msi" style="font-size:14px">star</span>Built by Us</div>
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-desc">Real projects built by club members — from hackathons to semester-long builds.</p>
        </div>
        <div class="card-grid">
            <?php foreach ($top_projects as $p): ?>
            <div class="card">
                <div class="card-head">
                    <div class="card-icon-box"><span class="msi">terminal</span></div>
                    <span class="top-badge"><span class="msi" style="font-size:12px">star</span>Featured</span>
                </div>
                <h3><?= htmlspecialchars($p['title']) ?></h3>
                <p><?= htmlspecialchars($p['description']) ?></p>
                <div class="card-meta">
                    <?php foreach (array_slice(explode(',', $p['tech_stack']), 0, 3) as $t): ?>
                    <span class="tag tag-blue"><?= trim(htmlspecialchars($t)) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="card-foot">
                    <span class="card-foot-meta">
                        <span class="msi" style="font-size:14px">group</span>
                        <?= htmlspecialchars($p['team_members']) ?>
                    </span>
                    <?php if ($p['github_url']): ?>
                    <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="card-link">
                        GitHub <span class="msi" style="font-size:14px">open_in_new</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="margin-top:1.75rem;">
            <a href="/pages/projects.php" class="btn btn-primary">
                <span class="msi" style="font-size:17px">rocket_launch</span>
                All Projects
            </a>
        </div>
    </div>
</div>

<!-- BLOG POSTS -->
<section class="section">
    <div class="section-header">
        <div class="eyebrow"><span class="msi" style="font-size:14px">article</span>From the Team</div>
        <h2 class="section-title">Latest Updates</h2>
    </div>
    <div class="card-grid">
        <?php foreach ($recent_posts as $post): ?>
        <div class="post-card">
            <div class="post-meta">
                <span class="msi" style="font-size:14px">person</span><?= htmlspecialchars($post['author']) ?>
                <span>·</span>
                <?= date('d M Y', strtotime($post['created_at'])) ?>
                <span class="tag tag-blue"><?= htmlspecialchars($post['category']) ?></span>
            </div>
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p class="post-excerpt"><?= htmlspecialchars(substr($post['content'], 0, 130)) ?>…</p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA BANNER -->
<div style="background:var(--primary);padding:4rem 2.5rem;text-align:center;">
    <h2 style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:white;margin-bottom:0.75rem;">Ready to start building?</h2>
    <p style="color:rgba(255,255,255,0.75);font-size:1rem;max-width:480px;margin:0 auto 2rem;line-height:1.75;">Whether you're a beginner or pro, join Nexus and build things that matter.</p>
    <a href="/pages/contact.php" class="btn" style="background:white;color:var(--primary);font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,0.15);">
        <span class="msi" style="font-size:18px">group_add</span>
        Apply to Join
    </a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
