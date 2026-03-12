<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Projects';
$msg = $err = '';

$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title']        ?? '');
    $desc   = trim($_POST['description']  ?? '');
    $tech   = trim($_POST['tech_stack']   ?? '');
    $github = trim($_POST['github_url']   ?? '');
    $demo   = trim($_POST['demo_url']     ?? '');
    $team   = trim($_POST['team_members'] ?? '');
    $is_top = isset($_POST['is_top']) ? 1 : 0;

    if ($action === 'delete') {
        db_execute("DELETE FROM projects WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = '✅ Project deleted.'; $action = '';
    } elseif (!$title) {
        $err = 'Project title is required.';
    } elseif ($action === 'create') {
        db_execute("INSERT INTO projects (title,description,tech_stack,github_url,demo_url,team_members,is_top) VALUES (?,?,?,?,?,?,?)",
            [$title,$desc,$tech,$github,$demo,$team,$is_top]);
        $msg = '✅ Project added.'; $action = '';
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        db_execute("UPDATE projects SET title=?,description=?,tech_stack=?,github_url=?,demo_url=?,team_members=?,is_top=? WHERE id=?",
            [$title,$desc,$tech,$github,$demo,$team,$is_top,$id]);
        $msg = '✅ Project updated.'; $action = '';
    }
}

$projects  = db_query("SELECT * FROM projects ORDER BY is_top DESC, created_at DESC");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM projects WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Content</p><h1>Projects</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">add</span>Add Project</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">code</span><?= $edit_item ? 'Edit: '.htmlspecialchars($edit_item['title']) : 'New Project' ?></div>
        <a href="/admin/projects.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST" action="/admin/projects.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Project Title *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($edit_item['title'] ?? '') ?>" placeholder="e.g. CampusConnect">
                </div>
                <div class="form-group-admin">
                    <label>Tech Stack</label>
                    <input type="text" name="tech_stack" value="<?= htmlspecialchars($edit_item['tech_stack'] ?? '') ?>" placeholder="React, Node.js, MongoDB">
                </div>
                <div class="form-group-admin">
                    <label>GitHub URL</label>
                    <input type="url" name="github_url" value="<?= htmlspecialchars($edit_item['github_url'] ?? '') ?>" placeholder="https://github.com/...">
                </div>
                <div class="form-group-admin">
                    <label>Live Demo URL</label>
                    <input type="url" name="demo_url" value="<?= htmlspecialchars($edit_item['demo_url'] ?? '') ?>" placeholder="https://...">
                </div>
                <div class="form-group-admin">
                    <label>Team Members</label>
                    <input type="text" name="team_members" value="<?= htmlspecialchars($edit_item['team_members'] ?? '') ?>" placeholder="Alice, Bob, Charlie">
                </div>
                <div class="form-group-admin" style="justify-content:flex-end;">
                    <label class="form-check" style="margin-top:auto;padding-bottom:0.5rem;">
                        <input type="checkbox" name="is_top" <?= ($edit_item['is_top'] ?? 0)?'checked':'' ?>>
                        Mark as ⭐ Featured Project
                    </label>
                </div>
            </div>
            <div class="form-group-admin" style="margin-top:1.25rem;">
                <label>Description</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($edit_item['description'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item?'save':'add' ?></span><?= $edit_item ? 'Save Changes' : 'Add Project' ?></button>
                <a href="/admin/projects.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($projects) ?> Projects</span>
        <div style="display:flex;gap:0.5rem;">
            <input type="text" class="table-search" placeholder="Search projects…" data-target="#proj-tbody">
            <button class="btn btn-outline" onclick="exportTableToCSV('projects.csv')"><span class="msi">download</span>Export</button>
        </div>
    </div>
    <?php if (empty($projects)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">code_off</span></div><p>No projects yet.</p></div>
    <?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Tech Stack</th><th>Team</th><th>Featured</th><th>Actions</th></tr></thead>
        <tbody id="proj-tbody">
        <?php foreach ($projects as $p): ?>
        <tr>
            <td class="td-mono"><?= $p['id'] ?></td>
            <td>
                <div class="td-title"><?= htmlspecialchars($p['title']) ?></div>
                <div class="td-sub"><?= htmlspecialchars(substr($p['description'],0,60)) ?>…</div>
            </td>
            <td>
                <?php foreach (array_slice(explode(',', $p['tech_stack']),0,3) as $t): ?>
                <span class="badge badge-purple" style="font-size:0.6rem;margin-right:2px;"><?= trim(htmlspecialchars($t)) ?></span>
                <?php endforeach; ?>
            </td>
            <td class="td-mono"><?= htmlspecialchars($p['team_members'] ?: '—') ?></td>
            <td><?= $p['is_top'] ? '<span class="badge badge-amber">⭐ Yes</span>' : '<span class="badge badge-gray">No</span>' ?></td>
            <td>
                <div class="td-actions">
                    <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm"><span class="msi" style="font-size:14px">edit</span>Edit</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($p['title']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id"     value="<?= $p['id'] ?>">
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
        <h3>Delete Project?</h3>
        <p>Permanently delete "<span id="confirm-item-name"></span>"?</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
