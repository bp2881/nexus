<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Dashboard';

$counts = [
    'events'    => db_query("SELECT COUNT(*) c FROM events")[0]['c'],
    'projects'  => db_query("SELECT COUNT(*) c FROM projects")[0]['c'],
    'materials' => db_query("SELECT COUNT(*) c FROM materials")[0]['c'],
    'posts'     => db_query("SELECT COUNT(*) c FROM blog_posts")[0]['c'],
    'contacts'  => db_query("SELECT COUNT(*) c FROM contact_requests")[0]['c'],
];
$recent_events   = db_query("SELECT * FROM events ORDER BY created_at DESC LIMIT 5");
$recent_projects = db_query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5");
$recent_contacts = db_query("SELECT * FROM contact_requests ORDER BY created_at DESC LIMIT 5");
require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div>
        <p class="page-eyebrow">Overview</p>
        <h1>Dashboard</h1>
    </div>
    <span style="font-size:0.78rem;color:var(--text-dim);font-family:var(--mono);"><?= date('D, d M Y · H:i') ?></span>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:var(--primary-light);color:var(--primary);"><span class="msi">event</span></div>
        </div>
        <div class="stat-card-num"><?= $counts['events'] ?></div>
        <div class="stat-card-label">Events</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:var(--purple-light);color:var(--purple);"><span class="msi">code</span></div>
        </div>
        <div class="stat-card-num"><?= $counts['projects'] ?></div>
        <div class="stat-card-label">Projects</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:var(--warning-light);color:var(--warning);"><span class="msi">menu_book</span></div>
        </div>
        <div class="stat-card-num"><?= $counts['materials'] ?></div>
        <div class="stat-card-label">Materials</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:var(--info-light);color:var(--info);"><span class="msi">article</span></div>
        </div>
        <div class="stat-card-num"><?= $counts['posts'] ?></div>
        <div class="stat-card-label">Blog Posts</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:var(--danger-light);color:var(--danger);"><span class="msi">mail</span></div>
        </div>
        <div class="stat-card-num"><?= $counts['contacts'] ?></div>
        <div class="stat-card-label">Contacts</div>
    </div>
</div>

<div class="dash-grid">
    <div class="box">
        <div class="box-header">
            <span class="box-title"><span class="msi" style="font-size:16px;color:var(--primary)">event</span> Recent Events</span>
            <a href="/admin/events.php" class="btn btn-outline btn-sm">Manage →</a>
        </div>
        <?php if (empty($recent_events)): ?>
        <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">event_busy</span></div><p>No events yet.</p></div>
        <?php else: foreach ($recent_events as $e): ?>
        <div class="recent-row">
            <div>
                <div class="recent-title"><?= htmlspecialchars($e['title']) ?></div>
                <div class="recent-meta"><?= $e['event_date'] ?> · <?= htmlspecialchars($e['location']) ?></div>
            </div>
            <span class="badge badge-blue"><?= htmlspecialchars($e['category']) ?></span>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="box">
        <div class="box-header">
            <span class="box-title"><span class="msi" style="font-size:16px;color:var(--purple)">code</span> Recent Projects</span>
            <a href="/admin/projects.php" class="btn btn-outline btn-sm">Manage →</a>
        </div>
        <?php if (empty($recent_projects)): ?>
        <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">code_off</span></div><p>No projects yet.</p></div>
        <?php else: foreach ($recent_projects as $p): ?>
        <div class="recent-row">
            <div>
                <div class="recent-title"><?= htmlspecialchars($p['title']) ?></div>
                <div class="recent-meta"><?= htmlspecialchars(substr($p['tech_stack'],0,50)) ?></div>
            </div>
            <?php if ($p['is_top']): ?><span class="badge badge-amber">⭐ Top</span><?php endif; ?>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="box">
        <div class="box-header">
            <span class="box-title"><span class="msi" style="font-size:16px;color:var(--danger)">mail</span> Latest Contacts</span>
            <a href="/admin/contacts.php" class="btn btn-outline btn-sm">View All →</a>
        </div>
        <?php if (empty($recent_contacts)): ?>
        <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">mark_email_unread</span></div><p>No messages yet.</p></div>
        <?php else: foreach ($recent_contacts as $c): ?>
        <div class="recent-row">
            <div>
                <div class="recent-title"><?= htmlspecialchars($c['name']) ?></div>
                <div class="recent-meta"><?= htmlspecialchars($c['email']) ?> · <?= date('d M', strtotime($c['created_at'])) ?></div>
            </div>
            <span class="badge badge-info"><?= htmlspecialchars($c['request_type']) ?></span>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="box">
        <div class="box-header"><span class="box-title"><span class="msi" style="font-size:16px;color:var(--success)">bolt</span> Quick Actions</span></div>
        <div class="quick-actions">
            <a href="/admin/events.php?action=new"    class="quick-action-btn"><span class="msi">add_circle</span> Add New Event</a>
            <a href="/admin/projects.php?action=new"  class="quick-action-btn"><span class="msi">add_circle</span> Add New Project</a>
            <a href="/admin/materials.php?action=new" class="quick-action-btn"><span class="msi">add_circle</span> Add New Material</a>
            <a href="/admin/posts.php?action=new"     class="quick-action-btn"><span class="msi">add_circle</span> Write Blog Post</a>
            <a href="/index.php" target="_blank"       class="quick-action-btn"><span class="msi">open_in_new</span> View Public Site</a>
        </div>
    </div>
</div>

<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon"><span class="msi">delete_forever</span></div>
        <h3>Delete Item?</h3>
        <p>You're about to permanently delete "<span id="confirm-item-name"></span>". This action cannot be undone.</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:16px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
