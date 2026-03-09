<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Teams';
$msg = $err = '';

$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $team_no   = (int)($_POST['team_no']   ?? 0);
        $team_name = trim($_POST['team_name']  ?? '');
        $points    = (int)($_POST['points']    ?? 0);
        if (!$team_no || !$team_name) { $err = 'Team number and name are required.'; }
        else {
            db_execute("INSERT INTO teams (team_no, team_name, points) VALUES (?,?,?)", [$team_no, $team_name, $points]);
            $msg = '✅ Team created.'; $action = '';
        }
    } elseif ($action === 'edit') {
        $id        = (int)($_POST['id']        ?? 0);
        $team_no   = (int)($_POST['team_no']   ?? 0);
        $team_name = trim($_POST['team_name']  ?? '');
        $points    = (int)($_POST['points']    ?? 0);
        if (!$team_no || !$team_name) { $err = 'Team number and name are required.'; }
        else {
            db_execute("UPDATE teams SET team_no=?, team_name=?, points=? WHERE id=?", [$team_no, $team_name, $points, $id]);
            $msg = '✅ Team updated.'; $action = '';
        }
    } elseif ($action === 'add_points') {
        // Quick +/- points
        $id     = (int)($_POST['id']     ?? 0);
        $delta  = (int)($_POST['delta']  ?? 0);
        db_execute("UPDATE teams SET points = MAX(0, points + ?) WHERE id=?", [$delta, $id]);
        $msg = ($delta >= 0 ? '✅ Added ' : '✅ Removed ') . abs($delta) . ' points.'; $action = '';
    } elseif ($action === 'delete') {
        db_execute("UPDATE members SET team_id = NULL WHERE team_id=?", [(int)($_POST['id'] ?? 0)]);
        db_execute("DELETE FROM teams WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = '✅ Team deleted.'; $action = '';
    }
}

$teams     = db_query("SELECT t.*, COUNT(m.id) as member_count FROM teams t LEFT JOIN members m ON m.team_id = t.id GROUP BY t.id ORDER BY t.points DESC, t.team_no");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM teams WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');

// Find top scorer for podium effect
$max_pts = !empty($teams) ? max(array_column($teams, 'points')) : 1;

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">People</p><h1>Teams &amp; Points</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">group_add</span>New Team</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- LEADERBOARD CARDS -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;margin-bottom:2rem;">
    <?php foreach ($teams as $i => $t):
        $pct = $max_pts > 0 ? round(($t['points'] / $max_pts) * 100) : 0;
        $is_top = $i === 0;
    ?>
    <div class="stat-card" style="<?= $is_top ? 'border-color:var(--accent);box-shadow:0 4px 20px rgba(245,158,11,0.15);' : '' ?>">
        <div class="stat-card-top">
            <div class="stat-card-icon" style="background:<?= $is_top ? 'var(--accent-light)' : 'var(--primary-light)' ?>;color:<?= $is_top ? 'var(--accent)' : 'var(--primary)' ?>;">
                <span class="msi"><?= $is_top ? 'emoji_events' : 'groups' ?></span>
            </div>
            <?php if ($is_top): ?><span class="badge badge-amber">🏆 Leader</span><?php endif; ?>
        </div>
        <div class="stat-card-num" style="color:<?= $is_top ? 'var(--accent)' : 'var(--text)' ?>"><?= $t['points'] ?></div>
        <div class="stat-card-label">pts · <?= $t['member_count'] ?> member<?= $t['member_count'] != 1 ? 's' : '' ?></div>
        <div style="margin-top:0.5rem;font-weight:700;font-size:0.875rem;">Team <?= $t['team_no'] ?> — <?= htmlspecialchars($t['team_name']) ?></div>
        <!-- Progress bar -->
        <div style="margin-top:0.75rem;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;">
            <div style="width:<?= $pct ?>%;height:100%;background:<?= $is_top ? 'var(--accent)' : 'var(--primary)' ?>;border-radius:100px;transition:width 0.5s;"></div>
        </div>
        <!-- Quick points buttons -->
        <div style="display:flex;gap:0.4rem;margin-top:0.75rem;flex-wrap:wrap;">
            <?php foreach ([5,10,25] as $pts): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="add_points">
                <input type="hidden" name="id"     value="<?= $t['id'] ?>">
                <input type="hidden" name="delta"  value="<?= $pts ?>">
                <button type="submit" class="btn btn-success btn-sm" style="padding:0.25rem 0.5rem;font-size:0.68rem;">+<?= $pts ?></button>
            </form>
            <?php endforeach; ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="add_points">
                <input type="hidden" name="id"     value="<?= $t['id'] ?>">
                <input type="hidden" name="delta"  value="-10">
                <button type="submit" class="btn btn-danger btn-sm" style="padding:0.25rem 0.5rem;font-size:0.68rem;">-10</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">groups</span><?= $edit_item ? 'Edit: '.htmlspecialchars($edit_item['team_name']) : 'New Team' ?></div>
        <a href="/admin/teams.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST" action="/admin/teams.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Team Number *</label>
                    <input type="number" name="team_no" required min="1" value="<?= htmlspecialchars($edit_item['team_no'] ?? '') ?>" placeholder="e.g. 1">
                </div>
                <div class="form-group-admin">
                    <label>Team Name *</label>
                    <input type="text" name="team_name" required value="<?= htmlspecialchars($edit_item['team_name'] ?? '') ?>" placeholder="e.g. Alpha Coders">
                </div>
                <div class="form-group-admin">
                    <label>Starting Points</label>
                    <input type="number" name="points" min="0" value="<?= htmlspecialchars($edit_item['points'] ?? '0') ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item?'save':'add' ?></span><?= $edit_item ? 'Save Changes' : 'Create Team' ?></button>
                <a href="/admin/teams.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- FULL TABLE -->
<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($teams) ?> Teams</span>
        <input type="text" class="table-search" placeholder="Search teams…" data-target="#teams-tbody">
    </div>
    <?php if (empty($teams)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">groups</span></div><p>No teams yet.</p></div>
    <?php else: ?>
    <table>
        <thead><tr><th>No.</th><th>Team Name</th><th>Points</th><th>Members</th><th>Set Points</th><th>Actions</th></tr></thead>
        <tbody id="teams-tbody">
        <?php foreach ($teams as $t): ?>
        <tr>
            <td class="td-mono"><?= $t['team_no'] ?></td>
            <td><div class="td-title"><?= htmlspecialchars($t['team_name']) ?></div></td>
            <td>
                <span style="font-family:var(--mono);font-size:1.1rem;font-weight:700;color:var(--primary);"><?= $t['points'] ?></span>
                <span style="color:var(--text-dim);font-size:0.75rem;"> pts</span>
            </td>
            <td><span class="badge badge-blue"><?= $t['member_count'] ?> member<?= $t['member_count'] != 1 ? 's' : '' ?></span></td>
            <td>
                <!-- Inline set-exact-points form -->
                <form method="POST" style="display:flex;gap:0.4rem;align-items:center;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id"      value="<?= $t['id'] ?>">
                    <input type="hidden" name="team_no"   value="<?= $t['team_no'] ?>">
                    <input type="hidden" name="team_name" value="<?= htmlspecialchars($t['team_name']) ?>">
                    <input type="number" name="points" value="<?= $t['points'] ?>" min="0"
                        style="width:80px;padding:0.3rem 0.5rem;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:var(--mono);font-size:0.82rem;background:var(--surface2);">
                    <button type="submit" class="btn btn-primary btn-sm"><span class="msi" style="font-size:14px">check</span></button>
                </form>
            </td>
            <td>
                <div class="td-actions">
                    <a href="?action=edit&id=<?= $t['id'] ?>" class="btn btn-warning btn-sm"><span class="msi" style="font-size:14px">edit</span>Edit</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($t['team_name']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id"     value="<?= $t['id'] ?>">
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
        <h3>Delete Team?</h3>
        <p>Delete "<span id="confirm-item-name"></span>"? Members will be unassigned but not deleted.</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
