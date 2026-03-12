<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Materials';
$msg = $err = '';

// BUG FIX: POST action always takes priority
$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title']        ?? '');
    $desc       = trim($_POST['description']  ?? '');
    $ext_url    = trim($_POST['external_url'] ?? '');
    $file_url   = trim($_POST['file_url']     ?? '');
    $category   = trim($_POST['category']     ?? 'general');
    $difficulty = trim($_POST['difficulty']   ?? 'beginner');

    if ($action === 'delete') {
        db_execute("DELETE FROM materials WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = '✅ Material deleted.'; $action = '';
    } elseif (!$title) {
        $err = 'Title is required.';
    } elseif ($action === 'create') {
        db_execute("INSERT INTO materials (title,description,external_url,file_url,category,difficulty) VALUES (?,?,?,?,?,?)",
            [$title,$desc,$ext_url,$file_url,$category,$difficulty]);
        $msg = '✅ Material added successfully.'; $action = '';
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        db_execute("UPDATE materials SET title=?,description=?,external_url=?,file_url=?,category=?,difficulty=? WHERE id=?",
            [$title,$desc,$ext_url,$file_url,$category,$difficulty,$id]);
        $msg = '✅ Material updated.'; $action = '';
    }
}

$materials = db_query("SELECT * FROM materials ORDER BY category, difficulty");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM materials WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');

$categories  = ['python','web','dsa','ml','tools','general','database','devops','design'];
$difficulties= ['beginner','intermediate','advanced'];
$diff_badge  = ['beginner'=>'badge-green','intermediate'=>'badge-amber','advanced'=>'badge-red'];
$cat_badge   = ['python'=>'badge-blue','web'=>'badge-green','dsa'=>'badge-purple','ml'=>'badge-info','tools'=>'badge-amber','general'=>'badge-gray','database'=>'badge-purple','devops'=>'badge-blue','design'=>'badge-info'];

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Content</p><h1>Study Materials</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">add</span>Add Material</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">menu_book</span><?= $edit_item ? 'Edit: '.htmlspecialchars($edit_item['title']) : 'Add Resource' ?></div>
        <a href="/admin/materials.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST" action="/admin/materials.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Title *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($edit_item['title'] ?? '') ?>" placeholder="e.g. Python Crash Course">
                </div>
                <div class="form-group-admin">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c ?>" <?= ($edit_item['category'] ?? 'general')===$c?'selected':'' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-admin">
                    <label>Difficulty</label>
                    <select name="difficulty">
                        <?php foreach ($difficulties as $d): ?>
                        <option value="<?= $d ?>" <?= ($edit_item['difficulty'] ?? 'beginner')===$d?'selected':'' ?>><?= ucfirst($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-admin">
                    <label>External Link (URL)</label>
                    <input type="url" name="external_url" value="<?= htmlspecialchars($edit_item['external_url'] ?? '') ?>" placeholder="https://docs.python.org">
                </div>
                <div class="form-group-admin">
                    <label>File URL (uploaded PDF)</label>
                    <input type="text" name="file_url" value="<?= htmlspecialchars($edit_item['file_url'] ?? '') ?>" placeholder="/uploads/file.pdf">
                </div>
            </div>
            <div class="form-group-admin" style="margin-top:1.25rem;">
                <label>Short Description</label>
                <textarea name="description" rows="3" placeholder="One-line description of this resource"><?= htmlspecialchars($edit_item['description'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item?'save':'add' ?></span><?= $edit_item ? 'Save Changes' : 'Add Resource' ?></button>
                <a href="/admin/materials.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($materials) ?> Resources</span>
        <div style="display:flex;gap:0.5rem;">
            <input type="text" class="table-search" placeholder="Search materials…" data-target="#mat-tbody">
            <button class="btn btn-outline" onclick="exportTableToCSV('materials.csv')"><span class="msi">download</span>Export</button>
        </div>
    </div>
    <?php if (empty($materials)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">menu_book</span></div><p>No materials yet. <a href="?action=new" style="color:var(--primary)">Add one →</a></p></div>
    <?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Difficulty</th><th>Link</th><th>Actions</th></tr></thead>
        <tbody id="mat-tbody">
        <?php foreach ($materials as $m): ?>
        <tr>
            <td class="td-mono"><?= $m['id'] ?></td>
            <td>
                <div class="td-title"><?= htmlspecialchars($m['title']) ?></div>
                <?php if ($m['description']): ?><div class="td-sub"><?= htmlspecialchars(substr($m['description'],0,60)) ?>…</div><?php endif; ?>
            </td>
            <td><span class="badge <?= $cat_badge[$m['category']] ?? 'badge-gray' ?>"><?= htmlspecialchars($m['category']) ?></span></td>
            <td><span class="badge <?= $diff_badge[$m['difficulty']] ?? 'badge-gray' ?>"><?= ucfirst($m['difficulty']) ?></span></td>
            <td>
                <?php $url = $m['external_url'] ?: $m['file_url']; ?>
                <?php if ($url): ?>
                <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="btn btn-outline btn-sm"><span class="msi" style="font-size:14px">open_in_new</span>Open</a>
                <?php else: ?><span style="color:var(--text-dim);font-size:0.75rem;">—</span><?php endif; ?>
            </td>
            <td>
                <div class="td-actions">
                    <a href="?action=edit&id=<?= $m['id'] ?>" class="btn btn-warning btn-sm"><span class="msi" style="font-size:14px">edit</span>Edit</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($m['title']) ?>">
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
        <div class="confirm-icon"><span class="msi">delete_forever</span></div>
        <h3>Delete Material?</h3>
        <p>Permanently delete "<span id="confirm-item-name"></span>"?</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
