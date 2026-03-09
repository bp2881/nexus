<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Events';
$msg = $err = '';

// BUG FIX: POST action always takes priority over GET
$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']       ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $date     = trim($_POST['event_date']  ?? '');
    $time     = trim($_POST['event_time']  ?? '');
    $location = trim($_POST['location']    ?? '');
    $category = trim($_POST['category']    ?? 'general');

    if (!$title || !$date) {
        $err = 'Title and date are required.';
    } elseif ($action === 'create') {
        db_execute("INSERT INTO events (title,description,event_date,event_time,location,category) VALUES (?,?,?,?,?,?)",
            [$title,$desc,$date,$time,$location,$category]);
        $msg = 'Event created successfully.'; $action = '';
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        db_execute("UPDATE events SET title=?,description=?,event_date=?,event_time=?,location=?,category=? WHERE id=?",
            [$title,$desc,$date,$time,$location,$category,$id]);
        $msg = 'Event updated.'; $action = '';
    } elseif ($action === 'delete') {
        db_execute("DELETE FROM events WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = 'Event deleted.'; $action = '';
    }
}

$events    = db_query("SELECT * FROM events ORDER BY event_date DESC");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM events WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');
$cats      = ['general','hackathon','workshop','talk','session','competition'];
$cat_badge = ['hackathon'=>'badge-amber','workshop'=>'badge-green','talk'=>'badge-blue','session'=>'badge-purple','competition'=>'badge-red','general'=>'badge-gray'];

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Content</p><h1>Events</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">add</span>Add Event</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title">
            <span class="msi">event</span>
            <?= $edit_item ? 'Edit: '.htmlspecialchars($edit_item['title']) : 'New Event' ?>
        </div>
        <a href="/admin/events.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST" action="/admin/events.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Event Title *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($edit_item['title'] ?? '') ?>" placeholder="e.g. Web Dev Workshop">
                </div>
                <div class="form-group-admin">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach ($cats as $c): ?>
                        <option value="<?= $c ?>" <?= ($edit_item['category'] ?? 'general')===$c?'selected':'' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-admin">
                    <label>Date *</label>
                    <input type="date" name="event_date" required value="<?= htmlspecialchars($edit_item['event_date'] ?? '') ?>">
                </div>
                <div class="form-group-admin">
                    <label>Time</label>
                    <input type="text" name="event_time" value="<?= htmlspecialchars($edit_item['event_time'] ?? '') ?>" placeholder="e.g. 04:00 PM">
                </div>
                <div class="form-group-admin">
                    <label>Location</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($edit_item['location'] ?? '') ?>" placeholder="CS Block, Room 201">
                </div>
            </div>
            <div class="form-group-admin" style="margin-top:1.25rem;">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($edit_item['description'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item?'save':'add' ?></span><?= $edit_item ? 'Save Changes' : 'Create Event' ?></button>
                <a href="/admin/events.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($events) ?> Events</span>
        <input type="text" class="table-search" placeholder="Search events…" data-target="#events-tbody">
    </div>
    <?php if (empty($events)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">event_busy</span></div><p>No events yet. <a href="?action=new" style="color:var(--primary)">Add the first one →</a></p></div>
    <?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Date</th><th>Location</th><th>Category</th><th>Actions</th></tr></thead>
        <tbody id="events-tbody">
        <?php foreach ($events as $e): ?>
        <tr>
            <td class="td-mono"><?= $e['id'] ?></td>
            <td>
                <div class="td-title"><?= htmlspecialchars($e['title']) ?></div>
                <?php if ($e['event_time']): ?><div class="td-sub"><?= htmlspecialchars($e['event_time']) ?></div><?php endif; ?>
            </td>
            <td class="td-mono"><?= $e['event_date'] ?></td>
            <td class="td-mono"><?= htmlspecialchars($e['location'] ?: '—') ?></td>
            <td><span class="badge <?= $cat_badge[$e['category']] ?? 'badge-gray' ?>"><?= htmlspecialchars($e['category']) ?></span></td>
            <td>
                <div class="td-actions">
                    <a href="?action=edit&id=<?= $e['id'] ?>" class="btn btn-warning btn-sm"><span class="msi" style="font-size:14px">edit</span>Edit</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($e['title']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id"     value="<?= $e['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><span class="msi" style="font-size:14px">delete</span>Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon"><span class="msi">delete_forever</span></div>
        <h3>Delete Event?</h3>
        <p>Permanently delete "<span id="confirm-item-name"></span>"? This cannot be undone.</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
