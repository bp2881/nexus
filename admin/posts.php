<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Blog Posts';
$msg = $err = '';

$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']    ?? '');
    $content  = trim($_POST['content']  ?? '');
    $author   = trim($_POST['author']   ?? 'Admin');
    $category = trim($_POST['category'] ?? 'general');

    if ($action === 'delete') {
        db_execute("DELETE FROM blog_posts WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = '✅ Post deleted.'; $action = '';
    } elseif (!$title) {
        $err = 'Title is required.';
    } elseif ($action === 'create') {
        db_execute("INSERT INTO blog_posts (title,content,author,category) VALUES (?,?,?,?)", [$title,$content,$author,$category]);
        $msg = '✅ Post published.'; $action = '';
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        db_execute("UPDATE blog_posts SET title=?,content=?,author=?,category=? WHERE id=?", [$title,$content,$author,$category,$id]);
        $msg = '✅ Post updated.'; $action = '';
    }
}

$posts     = db_query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM blog_posts WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');
$categories= ['general','announcement','guide','project','event-recap','tutorial'];

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Content</p><h1>Blog Posts</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">add</span>New Post</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">article</span><?= $edit_item ? 'Edit Post' : 'Write New Post' ?></div>
        <a href="/admin/posts.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <form method="POST" action="/admin/posts.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2" style="margin-bottom:1.25rem;">
                <div class="form-group-admin">
                    <label>Title *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($edit_item['title'] ?? '') ?>" placeholder="Post title...">
                </div>
                <div class="form-group-admin">
                    <label>Author</label>
                    <input type="text" name="author" value="<?= htmlspecialchars($edit_item['author'] ?? 'Admin') ?>">
                </div>
                <div class="form-group-admin">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c ?>" <?= ($edit_item['category'] ?? 'general')===$c?'selected':'' ?>><?= ucfirst(str_replace('-',' ',$c)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group-admin">
                <label>Content</label>
                <textarea name="content" rows="10"><?= htmlspecialchars($edit_item['content'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item?'save':'publish' ?></span><?= $edit_item ? 'Save Changes' : 'Publish Post' ?></button>
                <a href="/admin/posts.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($posts) ?> Posts</span>
        <input type="text" class="table-search" placeholder="Search posts…" data-target="#posts-tbody">
    </div>
    <?php if (empty($posts)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">article</span></div><p>No posts yet.</p></div>
    <?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody id="posts-tbody">
        <?php foreach ($posts as $p): ?>
        <tr>
            <td class="td-mono"><?= $p['id'] ?></td>
            <td>
                <div class="td-title"><?= htmlspecialchars($p['title']) ?></div>
                <div class="td-sub"><?= htmlspecialchars(substr($p['content'],0,70)) ?>…</div>
            </td>
            <td class="td-mono"><?= htmlspecialchars($p['author']) ?></td>
            <td><span class="badge badge-info"><?= htmlspecialchars($p['category']) ?></span></td>
            <td class="td-mono"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
            <td>
                <div class="td-actions">
                    <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm"><span class="msi" style="font-size:14px">edit</span>Edit</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($p['title']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
        <h3>Delete Post?</h3>
        <p>Permanently delete "<span id="confirm-item-name"></span>"?</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
