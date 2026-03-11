<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Members';
$msg = $err = '';

$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

// --- DATABASE OPERATIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'delete') {
        db_execute("DELETE FROM members WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = 'Member removed.'; $action = '';
    } else {
        $name    = trim($_POST['name']        ?? '');
        $email   = trim($_POST['email']       ?? '');
        $role    = trim($_POST['role']        ?? 'member');
        $desig   = trim($_POST['designation'] ?? '');
        $photo   = trim($_POST['photo_url']   ?? '');
        $team_id = (int)($_POST['team_id']    ?? 0) ?: null;

        if (!$name) {
            $err = 'Member name is required.';
        } elseif ($action === 'create') {
            db_execute("INSERT INTO members (name, email, role, designation, photo_url, team_id) VALUES (?,?,?,?,?,?)",
                [$name, $email, $role, $desig, $photo, $team_id]);
            $msg = 'Member added.'; $action = '';
        } elseif ($action === 'edit') {
            $id = (int)($_POST['id'] ?? 0);
            db_execute("UPDATE members SET name=?, email=?, role=?, designation=?, photo_url=?, team_id=? WHERE id=?",
                [$name, $email, $role, $desig, $photo, $team_id, $id]);
            $msg = 'Member updated.'; $action = '';
        }
    }
}

// --- DATA FETCHING ---
// Sorting by Team first, then Lead role, then Name
$members   = db_query("SELECT m.*, t.team_name, t.team_no FROM members m LEFT JOIN teams t ON m.team_id = t.id ORDER BY t.team_no ASC, m.role DESC, m.name ASC");
$teams     = db_query("SELECT * FROM teams ORDER BY team_no ASC");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM members WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');

$role_badge = [
    'hod'                 => 'badge-purple',
    'faculty_coordinator' => 'badge-purple',
    'student_coordinator' => 'badge-amber',
    'lead'                => 'badge-amber',
    'member'              => 'badge-blue'
];

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">People</p><h1>Members</h1></div>
    <div style="display:flex; gap:0.75rem;">
        <div class="search-container" style="position:relative;">
            <span class="msi" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-dim); font-size:18px;">search</span>
            <input type="text" id="memberSearch" class="form-input" placeholder="Search by name..." style="padding-left:40px; width:260px; height:42px;">
        </div>
        <a href="?action=new" class="btn btn-primary"><span class="msi">person_add</span>Add Member</a>
    </div>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">person</span><?= $edit_item ? 'Edit Member' : 'Add New Member' ?></div>
        <a href="members.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin"><label>Full Name *</label><input type="text" name="name" required value="<?= htmlspecialchars($edit_item['name'] ?? '') ?>"></div>
                <div class="form-group-admin"><label>Email Address</label><input type="email" name="email" value="<?= htmlspecialchars($edit_item['email'] ?? '') ?>"></div>
                <div class="form-group-admin">
                    <label>Role</label>
                    <select name="role">
                        <?php foreach(['member'=>'Member','lead'=>'Team Lead','student_coordinator'=>'Student Coordinator','faculty_coordinator'=>'Faculty Coordinator','hod'=>'HOD'] as $k => $v): ?>
                            <option value="<?= $k ?>" <?= ($edit_item['role'] ?? 'member') == $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-admin"><label>Designation</label><input type="text" name="designation" value="<?= htmlspecialchars($edit_item['designation'] ?? '') ?>"></div>
                <div class="form-group-admin">
                    <label>Assign to Team</label>
                    <select name="team_id">
                        <option value="">— Unassigned —</option>
                        <?php foreach ($teams as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($edit_item['team_id'] ?? null) == $t['id'] ? 'selected' : '' ?>>Team <?= $t['team_no'] ?>: <?= htmlspecialchars($t['team_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $edit_item ? 'Save Changes' : 'Add Member' ?></button>
                <a href="members.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Member & Team</th>
                <th>Role</th>
                <th>Contact</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody id="members-tbody">
        <?php foreach ($members as $m): ?>
        <tr class="member-row" data-name="<?= strtolower($m['name']) ?>">
            <td>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="width:36px; height:36px; border-radius:8px; background:var(--primary-light); color:var(--primary); display:grid; place-items:center; font-weight:700;">
                        <?= strtoupper(substr($m['name'], 0, 1)) ?>
                    </div>
                    <div>
                        <div class="td-title" style="font-weight:600;"><?= htmlspecialchars($m['name']) ?></div>
                        <div style="font-size:0.75rem; color:var(--primary); font-weight:500;">
                            <?php if ($m['team_no']): ?>
                                Team <?= $m['team_no'] ?> — <?= htmlspecialchars($m['team_name']) ?>
                            <?php else: ?>
                                <span style="color:var(--text-dim);">No Team Assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge <?= $role_badge[$m['role']] ?? 'badge-gray' ?>">
                    <?= $m['role'] === 'lead' ? 'Lead' : ucfirst(str_replace('_', ' ', $m['role'])) ?>
                </span>
                <?php if($m['designation']): ?>
                    <div style="font-size:0.7rem; color:var(--text-dim); margin-top:2px;"><?= htmlspecialchars($m['designation']) ?></div>
                <?php endif; ?>
            </td>
            <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>" style="font-size:0.85rem;"><?= htmlspecialchars($m['email'] ?: '—') ?></a></td>
            <td style="text-align:right;">
                <div class="td-actions" style="justify-content: flex-end;">
                    <a href="?action=edit&id=<?= $m['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Remove <?= htmlspecialchars($m['name']) ?>?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Simple real-time search filtering
document.getElementById('memberSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('.member-row');
    
    rows.forEach(row => {
        let name = row.getAttribute('data-name');
        row.style.display = name.includes(filter) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>