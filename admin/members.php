<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Members';
$msg = $err = '';

$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $role    = trim($_POST['role']    ?? 'member');
    $team_id = (int)($_POST['team_id'] ?? 0) ?: null;

    if (!$name) {
        $err = 'Member name is required.';
    } elseif ($action === 'create') {
        db_execute("INSERT INTO members (name, email, role, team_id) VALUES (?,?,?,?)",
            [$name, $email, $role, $team_id]);
        $msg = '✅ Member added.'; $action = '';
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        db_execute("UPDATE members SET name=?, email=?, role=?, team_id=? WHERE id=?",
            [$name, $email, $role, $team_id, $id]);
        $msg = '✅ Member updated.'; $action = '';
    } elseif ($action === 'delete') {
        db_execute("DELETE FROM members WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = '✅ Member removed.'; $action = '';
    }
}

$members   = db_query("SELECT m.*, t.team_name, t.team_no FROM members m LEFT JOIN teams t ON m.team_id = t.id ORDER BY t.team_no, m.name");
$teams     = db_query("SELECT * FROM teams ORDER BY team_no");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM members WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');

$role_badge = ['lead'=>'badge-amber','member'=>'badge-blue','core'=>'badge-purple','advisor'=>'badge-green'];

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">People</p><h1>Members</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">person_add</span>Add Member</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">person</span><?= $edit_item ? 'Edit: '.htmlspecialchars($edit_item['name']) : 'Add New Member' ?></div>
        <a href="/admin/members.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST" action="/admin/members.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Full Name *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($edit_item['name'] ?? '') ?>" placeholder="e.g. Arjun Kumar">
                </div>
                <div class="form-group-admin">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($edit_item['email'] ?? '') ?>" placeholder="student@college.edu">
                </div>
                <div class="form-group-admin">
                    <label>Role</label>
                    <select name="role">
                        <?php foreach (['member','lead','core','advisor'] as $r): ?>
                        <option value="<?= $r ?>" <?= ($edit_item['role'] ?? 'member') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-admin">
                    <label>Assign to Team</label>
                    <select name="team_id">
                        <option value="">— No Team —</option>
                        <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($edit_item['team_id'] ?? null) == $t['id'] ? 'selected' : '' ?>>
                            Team <?= $t['team_no'] ?> — <?= htmlspecialchars($t['team_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item ? 'save' : 'person_add' ?></span><?= $edit_item ? 'Save Changes' : 'Add Member' ?></button>
                <a href="/admin/members.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Member count per team summary -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.75rem;">
    <?php foreach ($teams as $t):
        $cnt = count(array_filter($members, fn($m) => $m['team_id'] == $t['id'])); ?>
    <div class="stat-card" style="--stat-color:var(--primary);">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:var(--primary-light);color:var(--primary);"><span class="msi">groups</span></div>
            <span class="badge badge-amber"><?= $t['points'] ?> pts</span>
        </div>
        <div class="stat-card-num"><?= $cnt ?></div>
        <div class="stat-card-label">Team <?= $t['team_no'] ?> · <?= htmlspecialchars($t['team_name']) ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($members) ?> Members</span>
        <input type="text" class="table-search" placeholder="Search members…" data-target="#members-tbody">
    </div>
    <?php if (empty($members)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">group</span></div><p>No members yet.</p></div>
    <?php else: ?>
    <table>
        <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Team</th><th>Actions</th></tr>
        </thead>
        <tbody id="members-tbody">
        <?php foreach ($members as $m): ?>
        <tr>
            <td class="td-mono"><?= $m['id'] ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:0.6rem;">
                    <div style="width:32px;height:32px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;flex-shrink:0;">
                        <?= strtoupper(substr($m['name'], 0, 1)) ?>
                    </div>
                    <div class="td-title"><?= htmlspecialchars($m['name']) ?></div>
                </div>
            </td>
            <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>" style="color:var(--primary);font-size:0.82rem;"><?= htmlspecialchars($m['email'] ?: '—') ?></a></td>
            <td><span class="badge <?= $role_badge[$m['role']] ?? 'badge-gray' ?>"><?= ucfirst($m['role']) ?></span></td>
            <td>
                <?php if ($m['team_name']): ?>
                <span class="badge badge-blue">Team <?= $m['team_no'] ?> · <?= htmlspecialchars($m['team_name']) ?></span>
                <?php else: ?>
                <span style="color:var(--text-dim);font-size:0.8rem;">Unassigned</span>
                <?php endif; ?>
            </td>
            <td>
                <div class="td-actions">
                    <a href="?action=edit&id=<?= $m['id'] ?>" class="btn btn-warning btn-sm"><span class="msi" style="font-size:14px">edit</span>Edit</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($m['name']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id"     value="<?= $m['id'] ?>">
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
        <div class="confirm-icon"><span class="msi">person_remove</span></div>
        <h3>Remove Member?</h3>
        <p>Remove "<span id="confirm-item-name"></span>" from the club?</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Remove</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
