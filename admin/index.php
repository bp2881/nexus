<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Dashboard';

$counts = [
    'members'   => db_query("SELECT COUNT(*) c FROM members")[0]['c'],
    'teams'     => db_query("SELECT COUNT(*) c FROM teams")[0]['c'],
    'events'    => db_query("SELECT COUNT(*) c FROM events")[0]['c'],
    'projects'  => db_query("SELECT COUNT(*) c FROM projects")[0]['c'],
    'materials' => db_query("SELECT COUNT(*) c FROM materials")[0]['c'],
    'gallery'   => db_query("SELECT COUNT(*) c FROM gallery")[0]['c'],
    'contacts'  => db_query("SELECT COUNT(*) c FROM contact_requests")[0]['c'],
];

$top_teams      = db_query("SELECT * FROM teams ORDER BY points DESC LIMIT 4");
$recent_members = db_query("SELECT m.*, t.team_name, t.team_no FROM members m LEFT JOIN teams t ON m.team_id=t.id ORDER BY m.created_at DESC LIMIT 5");
$recent_contacts= db_query("SELECT * FROM contact_requests ORDER BY created_at DESC LIMIT 5");
$upcoming       = db_query("SELECT * FROM events WHERE event_date >= date('now') ORDER BY event_date ASC LIMIT 4");

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div>
        <p class="page-eyebrow">Overview</p>
        <h1>Dashboard</h1>
    </div>
    <span style="font-size:0.78rem;color:var(--text-dim);font-family:var(--mono);"><?= date('D, d M Y · H:i') ?></span>
</div>

<!-- STATS ROW -->
<div class="stats-row" style="grid-template-columns:repeat(auto-fill,minmax(130px,1fr));">
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--primary-light);color:var(--primary);"><span class="msi">group</span></div></div>
        <div class="stat-card-num"><?= $counts['members'] ?></div>
        <div class="stat-card-label">Members</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--accent-light);color:var(--accent);"><span class="msi">emoji_events</span></div></div>
        <div class="stat-card-num"><?= $counts['teams'] ?></div>
        <div class="stat-card-label">Teams</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--success-light);color:var(--success);"><span class="msi">event</span></div></div>
        <div class="stat-card-num"><?= $counts['events'] ?></div>
        <div class="stat-card-label">Events</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--purple-light);color:var(--purple);"><span class="msi">code</span></div></div>
        <div class="stat-card-num"><?= $counts['projects'] ?></div>
        <div class="stat-card-label">Projects</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--warning-light);color:var(--warning);"><span class="msi">menu_book</span></div></div>
        <div class="stat-card-num"><?= $counts['materials'] ?></div>
        <div class="stat-card-label">Materials</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--info-light);color:var(--info);"><span class="msi">photo_library</span></div></div>
        <div class="stat-card-num"><?= $counts['gallery'] ?></div>
        <div class="stat-card-label">Photos</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon" style="background:var(--danger-light);color:var(--danger);"><span class="msi">mail</span></div></div>
        <div class="stat-card-num"><?= $counts['contacts'] ?></div>
        <div class="stat-card-label">Contacts</div>
    </div>
</div>

<!-- TEAM LEADERBOARD -->
<?php if (!empty($top_teams)):
    $max_pts = max(array_column($top_teams,'points')) ?: 1; ?>
<div class="box" style="margin-bottom:1.5rem;">
    <div class="box-header">
        <span class="box-title"><span class="msi" style="font-size:16px;color:var(--accent)">emoji_events</span> Team Leaderboard</span>
        <a href="/admin/teams.php" class="btn btn-outline btn-sm">Manage →</a>
    </div>
    <div style="padding:1.25rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
        <?php foreach ($top_teams as $i => $t):
            $pct = round(($t['points']/$max_pts)*100);
            $is_top = $i===0; ?>
        <div style="padding:1rem;background:<?= $is_top?'var(--accent-light)':'var(--surface2)' ?>;border-radius:var(--radius);border:1px solid <?= $is_top?'rgba(245,158,11,0.3)':'var(--border)' ?>;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                <span style="font-size:0.75rem;font-weight:700;color:var(--text-mid);">Team <?= $t['team_no'] ?></span>
                <?php if($is_top): ?><span class="badge badge-amber">🏆 #1</span><?php else: ?><span class="badge badge-gray">#<?= $i+1 ?></span><?php endif; ?>
            </div>
            <div style="font-weight:800;font-size:0.95rem;margin-bottom:0.25rem;"><?= htmlspecialchars($t['team_name']) ?></div>
            <div style="font-size:1.4rem;font-weight:800;color:<?= $is_top?'var(--accent)':'var(--primary)' ?>;line-height:1;"><?= $t['points'] ?> <span style="font-size:0.7rem;font-weight:600;color:var(--text-dim);">pts</span></div>
            <div style="margin-top:0.6rem;background:rgba(0,0,0,0.08);border-radius:100px;height:4px;overflow:hidden;">
                <div style="width:<?= $pct ?>%;height:100%;background:<?= $is_top?'var(--accent)':'var(--primary)' ?>;border-radius:100px;"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="dash-grid">
    <!-- RECENT MEMBERS -->
    <div class="box">
        <div class="box-header">
            <span class="box-title"><span class="msi" style="font-size:16px;color:var(--primary)">group</span> Recent Members</span>
            <a href="/admin/members.php" class="btn btn-outline btn-sm">Manage →</a>
        </div>
        <?php if (empty($recent_members)): ?>
        <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">group</span></div><p>No members yet.</p></div>
        <?php else: foreach ($recent_members as $m): ?>
        <div class="recent-row">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <div style="width:30px;height:30px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;flex-shrink:0;"><?= strtoupper(substr($m['name'],0,1)) ?></div>
                <div>
                    <div class="recent-title"><?= htmlspecialchars($m['name']) ?></div>
                    <div class="recent-meta"><?= $m['team_name'] ? 'Team '.$m['team_no'].' · '.htmlspecialchars($m['team_name']) : 'Unassigned' ?></div>
                </div>
            </div>
            <span class="badge badge-blue"><?= ucfirst($m['role']) ?></span>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <!-- UPCOMING EVENTS -->
    <div class="box">
        <div class="box-header">
            <span class="box-title"><span class="msi" style="font-size:16px;color:var(--success)">event</span> Upcoming Events</span>
            <a href="/admin/events.php" class="btn btn-outline btn-sm">Manage →</a>
        </div>
        <?php if (empty($upcoming)): ?>
        <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">event_busy</span></div><p>No upcoming events.</p></div>
        <?php else: foreach ($upcoming as $e): ?>
        <div class="recent-row">
            <div>
                <div class="recent-title"><?= htmlspecialchars($e['title']) ?></div>
                <div class="recent-meta"><?= $e['event_date'] ?> · <?= htmlspecialchars($e['location']) ?></div>
            </div>
            <span class="badge badge-green"><?= htmlspecialchars($e['category']) ?></span>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <!-- LATEST CONTACTS -->
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

    <!-- QUICK ACTIONS -->
    <div class="box">
        <div class="box-header"><span class="box-title"><span class="msi" style="font-size:16px;color:var(--success)">bolt</span> Quick Actions</span></div>
        <div class="quick-actions">
            <a href="/admin/members.php?action=new"   class="quick-action-btn"><span class="msi">person_add</span> Add Member</a>
            <a href="/admin/teams.php?action=new"     class="quick-action-btn"><span class="msi">group_add</span> Create Team</a>
            <a href="/admin/events.php?action=new"    class="quick-action-btn"><span class="msi">event</span> Add Event</a>
            <a href="/admin/gallery.php?action=new"   class="quick-action-btn"><span class="msi">add_photo_alternate</span> Upload Photo</a>
            <a href="/admin/projects.php?action=new"  class="quick-action-btn"><span class="msi">code</span> Add Project</a>
            <a href="/index.php" target="_blank"       class="quick-action-btn"><span class="msi">open_in_new</span> View Site</a>
        </div>
    </div>
</div>

<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon"><span class="msi">delete_forever</span></div>
        <h3>Delete Item?</h3>
        <p>Permanently delete "<span id="confirm-item-name"></span>"? This cannot be undone.</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:16px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
