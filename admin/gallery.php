<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Gallery';
$msg = $err = '';

$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title']       ?? '');
    $desc       = trim($_POST['description'] ?? '');
    $image_url  = trim($_POST['image_url']   ?? '');
    $event_name = trim($_POST['event_name']  ?? '');

    if (!$title) {
        $err = 'Photo title is required.';
    } elseif ($action === 'create') {
        db_execute("INSERT INTO gallery (title, description, image_url, event_name) VALUES (?,?,?,?)",
            [$title, $desc, $image_url, $event_name]);
        $msg = '✅ Photo added to gallery.'; $action = '';
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        db_execute("UPDATE gallery SET title=?, description=?, image_url=?, event_name=? WHERE id=?",
            [$title, $desc, $image_url, $event_name, $id]);
        $msg = '✅ Photo updated.'; $action = '';
    } elseif ($action === 'delete') {
        db_execute("DELETE FROM gallery WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = '✅ Photo removed.'; $action = '';
    }
}

$photos    = db_query("SELECT * FROM gallery ORDER BY created_at DESC");
$edit_item = ($action === 'edit' && isset($_GET['id'])) ? (db_query("SELECT * FROM gallery WHERE id=?", [(int)$_GET['id']])[0] ?? null) : null;
$show_form = ($action === 'new' || $action === 'edit');

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Content</p><h1>Gallery</h1></div>
    <a href="?action=new" class="btn btn-primary"><span class="msi">add_photo_alternate</span>Add Photo</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title"><span class="msi">photo_library</span><?= $edit_item ? 'Edit Photo' : 'Add New Photo' ?></div>
        <a href="/admin/gallery.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <div class="alert alert-info" style="margin-bottom:1.25rem;">
            <span class="msi">info</span>
            Paste a direct image URL (e.g. from Google Drive, Imgur, or your hosting). For Google Drive: share the image → get shareable link → convert to direct link using <strong>drive.google.com/uc?id=FILE_ID</strong>.
        </div>
        <form method="POST" action="/admin/gallery.php">
            <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'create' ?>">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>
            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Photo Title *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($edit_item['title'] ?? '') ?>" placeholder="e.g. Hackathon 2025 Team Photo">
                </div>
                <div class="form-group-admin">
                    <label>Event / Album Name</label>
                    <input type="text" name="event_name" value="<?= htmlspecialchars($edit_item['event_name'] ?? '') ?>" placeholder="e.g. Annual Hackathon 2025">
                </div>
            </div>
            <div class="form-group-admin" style="margin-top:1.1rem;">
                <label>Image URL *</label>
                <input type="url" name="image_url" required value="<?= htmlspecialchars($edit_item['image_url'] ?? '') ?>" placeholder="https://i.imgur.com/yourimage.jpg" id="imgUrlInput">
            </div>

            <!-- Live preview -->
            <div id="img-preview-wrap" style="margin-top:0.75rem;display:<?= !empty($edit_item['image_url']) ? 'block' : 'none' ?>;">
                <div style="font-size:0.72rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.4rem;">Preview</div>
                <img id="img-preview" src="<?= htmlspecialchars($edit_item['image_url'] ?? '') ?>"
                     style="max-height:200px;max-width:100%;border-radius:var(--radius);border:1px solid var(--border);object-fit:cover;"
                     onerror="this.style.display='none'">
            </div>

            <div class="form-group-admin" style="margin-top:1.1rem;">
                <label>Caption / Description</label>
                <textarea name="description" rows="2" placeholder="Short caption..."><?= htmlspecialchars($edit_item['description'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><span class="msi"><?= $edit_item?'save':'add_photo_alternate' ?></span><?= $edit_item ? 'Save Changes' : 'Add to Gallery' ?></button>
                <a href="/admin/gallery.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// Live preview as user types URL
document.getElementById('imgUrlInput')?.addEventListener('input', function() {
    const wrap = document.getElementById('img-preview-wrap');
    const img  = document.getElementById('img-preview');
    if (this.value) {
        wrap.style.display = 'block';
        img.style.display  = 'block';
        img.src = this.value;
        img.onerror = () => img.style.display = 'none';
    } else {
        wrap.style.display = 'none';
    }
});
</script>
<?php endif; ?>

<!-- GALLERY GRID PREVIEW -->
<?php if (!empty($photos)): ?>
<div style="margin-bottom:1.5rem;">
    <div style="font-size:0.75rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;display:flex;align-items:center;gap:0.4rem;">
        <span class="msi" style="font-size:16px">grid_view</span> Photo Grid (<?= count($photos) ?> photos)
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem;">
        <?php foreach ($photos as $p): ?>
        <div style="position:relative;border-radius:var(--radius);overflow:hidden;background:var(--surface2);border:1px solid var(--border);aspect-ratio:4/3;group;">
            <?php if ($p['image_url']): ?>
            <img src="<?= htmlspecialchars($p['image_url']) ?>"
                 alt="<?= htmlspecialchars($p['title']) ?>"
                 style="width:100%;height:100%;object-fit:cover;"
                 onerror="this.parentElement.style.background='var(--danger-light)';this.style.display='none';">
            <?php else: ?>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.3rem;color:var(--text-dim);">
                <span class="msi" style="font-size:2rem;opacity:0.3">image</span>
                <span style="font-size:0.7rem;">No image</span>
            </div>
            <?php endif; ?>
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.75));padding:0.6rem 0.5rem 0.5rem;color:white;">
                <div style="font-size:0.72rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($p['title']) ?></div>
                <?php if ($p['event_name']): ?>
                <div style="font-size:0.62rem;opacity:0.75;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($p['event_name']) ?></div>
                <?php endif; ?>
            </div>
            <!-- Hover actions -->
            <div style="position:absolute;top:0.4rem;right:0.4rem;display:flex;gap:0.3rem;">
                <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm" style="padding:0.25rem 0.4rem;" title="Edit"><span class="msi" style="font-size:13px">edit</span></a>
                <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($p['title']) ?>" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id"     value="<?= $p['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" style="padding:0.25rem 0.4rem;" title="Delete"><span class="msi" style="font-size:13px">delete</span></button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- TABLE VIEW -->
<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title"><?= count($photos) ?> Photos</span>
        <input type="text" class="table-search" placeholder="Search photos…" data-target="#gallery-tbody">
    </div>
    <?php if (empty($photos)): ?>
    <div class="empty-state">
        <div class="empty-icon"><span class="msi" style="font-size:2.5rem">photo_library</span></div>
        <p>No photos yet. <a href="?action=new" style="color:var(--primary)">Add the first one →</a></p>
    </div>
    <?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Preview</th><th>Title</th><th>Event / Album</th><th>Caption</th><th>Added</th><th>Actions</th></tr></thead>
        <tbody id="gallery-tbody">
        <?php foreach ($photos as $p): ?>
        <tr>
            <td class="td-mono"><?= $p['id'] ?></td>
            <td>
                <?php if ($p['image_url']): ?>
                <img src="<?= htmlspecialchars($p['image_url']) ?>" style="width:60px;height:44px;object-fit:cover;border-radius:6px;border:1px solid var(--border);" onerror="this.style.display='none'">
                <?php else: ?>
                <div style="width:60px;height:44px;border-radius:6px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;"><span class="msi" style="font-size:16px;color:var(--text-dim)">image</span></div>
                <?php endif; ?>
            </td>
            <td><div class="td-title"><?= htmlspecialchars($p['title']) ?></div></td>
            <td class="td-mono"><?= htmlspecialchars($p['event_name'] ?: '—') ?></td>
            <td style="max-width:180px;font-size:0.8rem;color:var(--text-mid);"><?= htmlspecialchars(substr($p['description'] ?: '—', 0, 60)) ?></td>
            <td class="td-mono"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
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
        <h3>Remove Photo?</h3>
        <p>Remove "<span id="confirm-item-name"></span>" from the gallery?</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Remove</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
