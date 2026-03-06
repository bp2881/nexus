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
$cat_tag= ['hackathon'=>'tag-amber','workshop'=>'tag-green','talk'=>'tag-blue','session'=>'tag-purple','competition'=>'tag-red','general'=>'tag-gray'];
?>

<div class="page-hero">
    <div class="page-hero-inner">
        <div class="eyebrow"><span class="msi" style="font-size:14px">calendar_month</span>What's On</div>
        <h1>Events &amp; Announcements</h1>
        <p>Workshops, hackathons, talks and collaborative sessions — stay in the loop.</p>
    </div>
</div>

<section class="section">
    <div class="chip-bar">
        <?php foreach ($cats as $cat): ?>
        <a href="?cat=<?= $cat ?>" class="chip <?= $filter===$cat?'active':'' ?>">
            <?= ucfirst($cat) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php
    $upcoming = array_filter($events, fn($e) => $e['event_date'] >= $today);
    $past     = array_filter($events, fn($e) => $e['event_date'] < $today);
    ?>

    <?php if (!empty($upcoming)): ?>
    <h3 style="font-size:0.8rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;display:flex;align-items:center;gap:0.4rem;">
        <span class="msi" style="font-size:16px;color:var(--success)">radio_button_checked</span> Upcoming
    </h3>
    <div class="event-list" style="margin-bottom:2.5rem;">
        <?php foreach ($upcoming as $ev):
            $d = new DateTime($ev['event_date']); ?>
        <div class="event-item">
            <div class="event-date-box">
                <div class="event-day"><?= $d->format('d') ?></div>
                <div class="event-month"><?= $d->format('M') ?></div>
            </div>
            <div class="event-info">
                <h3><?= htmlspecialchars($ev['title']) ?>
                    <span class="tag <?= $cat_tag[$ev['category']] ?? 'tag-gray' ?>" style="margin-left:0.5rem;font-size:0.65rem;"><?= htmlspecialchars($ev['category']) ?></span>
                </h3>
                <div class="event-meta">
                    <span class="msi" style="font-size:14px">location_on</span><?= htmlspecialchars($ev['location']) ?>
                    &nbsp;·&nbsp;
                    <span class="msi" style="font-size:14px">schedule</span><?= htmlspecialchars($ev['event_time']) ?>
                </div>
                <p style="font-size:0.82rem;color:var(--text-mid);margin-top:0.35rem;"><?= htmlspecialchars($ev['description']) ?></p>
            </div>
            <a href="#" class="btn btn-primary btn-sm">Register</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($past)): ?>
    <h3 style="font-size:0.8rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;display:flex;align-items:center;gap:0.4rem;">
        <span class="msi" style="font-size:16px;color:var(--text-dim)">history</span> Past Events
    </h3>
    <div class="event-list" style="opacity:0.65;">
        <?php foreach ($past as $ev):
            $d = new DateTime($ev['event_date']); ?>
        <div class="event-item">
            <div class="event-date-box" style="background:var(--surface2);border-color:var(--border);">
                <div class="event-day" style="color:var(--text-dim)"><?= $d->format('d') ?></div>
                <div class="event-month" style="color:var(--text-dim)"><?= $d->format('M') ?></div>
            </div>
            <div class="event-info">
                <h3><?= htmlspecialchars($ev['title']) ?></h3>
                <div class="event-meta"><span class="msi" style="font-size:14px">location_on</span><?= htmlspecialchars($ev['location']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($events)): ?>
    <div style="text-align:center;padding:3rem;color:var(--text-dim);">
        <span class="msi" style="font-size:3rem;display:block;margin-bottom:0.75rem;opacity:0.3">event_busy</span>
        No events found for this category.
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
