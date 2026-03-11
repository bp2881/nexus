<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Gallery';
$msg = $err = '';

define('UPLOAD_DIR',  __DIR__ . '/../assets/uploads/gallery/');
define('UPLOAD_PATH', '/assets/uploads/gallery/');

if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

// ── Upload helper ────────────────────────────────────────────────────────
function handle_thumbnail_upload(string $field = 'thumbnail'): ?string {
    if (empty($_FILES[$field]['tmp_name'])) return null;
    $file  = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) return null;

    $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allow = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $allow)) return null;

    if ($file['size'] > 8 * 1024 * 1024) return null; // 8 MB max

    $name  = uniqid('thumb_', true) . '.' . $ext;
    move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name);
    return $name;
}

$action = $_SERVER['REQUEST_METHOD'] === 'POST'
        ? ($_POST['action'] ?? '')
        : ($_GET['action']  ?? '');

// ── POST handlers ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($action === 'delete_album') {
        // Delete thumbnail file from disk
        $row = db_query("SELECT thumbnail FROM gallery_albums WHERE id=?", [(int)($_POST['id'] ?? 0)])[0] ?? null;
        if ($row && $row['thumbnail'] && file_exists(UPLOAD_DIR . $row['thumbnail']))
            unlink(UPLOAD_DIR . $row['thumbnail']);
        db_execute("DELETE FROM gallery_albums WHERE id=?", [(int)($_POST['id'] ?? 0)]);
        $msg = 'Album deleted.'; $action = '';

    } elseif ($action === 'save_album') {
        $name   = trim($_POST['event_name']       ?? '');
        $desc   = trim($_POST['description']       ?? '');
        $folder = trim($_POST['drive_folder_url']  ?? '');
        $id     = (int)($_POST['id'] ?? 0);

        if (!$name) {
            $err = 'Event name is required.';
        } else {
            $new_thumb = handle_thumbnail_upload('thumbnail');

            if ($id) {
                // Keep old thumbnail unless a new one was uploaded
                $old = db_query("SELECT thumbnail FROM gallery_albums WHERE id=?", [$id])[0] ?? [];
                $thumb = $new_thumb ?? ($old['thumbnail'] ?? null);
                // Delete old file if replaced
                if ($new_thumb && !empty($old['thumbnail']) && file_exists(UPLOAD_DIR . $old['thumbnail']))
                    unlink(UPLOAD_DIR . $old['thumbnail']);
                db_execute(
                    "UPDATE gallery_albums SET event_name=?, description=?, thumbnail=?, drive_folder_url=? WHERE id=?",
                    [$name, $desc, $thumb, $folder, $id]);
                $msg = 'Album updated.';
            } else {
                db_execute(
                    "INSERT INTO gallery_albums (event_name, description, thumbnail, drive_folder_url) VALUES (?,?,?,?)",
                    [$name, $desc, $new_thumb, $folder]);
                $msg = 'Album created.';
            }
            $action = '';
        }
    }
}

$albums    = db_query("SELECT * FROM gallery_albums ORDER BY created_at DESC");
$edit_item = ($action === 'edit_album' && isset($_GET['id']))
           ? (db_query("SELECT * FROM gallery_albums WHERE id=?", [(int)$_GET['id']])[0] ?? null)
           : null;
$show_form = ($action === 'new_album' || $action === 'edit_album');

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Content</p><h1>Gallery</h1></div>
    <a href="?action=new_album" class="btn btn-primary"><span class="msi">create_new_folder</span>New Album</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><span class="msi">error</span><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- ── ALBUM FORM ──────────────────────────────────────────────────────── -->
<?php if ($show_form): ?>
<div class="form-panel">
    <div class="form-panel-header">
        <div class="form-panel-title">
            <span class="msi">photo_library</span>
            <?= $edit_item ? 'Edit Album — '.htmlspecialchars($edit_item['event_name']) : 'New Album' ?>
        </div>
        <a href="/admin/gallery.php" class="form-panel-close">✕</a>
    </div>
    <div class="form-panel-body">
        <!-- multipart/form-data required for file upload -->
        <form method="POST" action="/admin/gallery.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_album">
            <?php if ($edit_item): ?><input type="hidden" name="id" value="<?= $edit_item['id'] ?>"><?php endif; ?>

            <div class="form-grid form-grid-2">
                <div class="form-group-admin">
                    <label>Event / Album Name *</label>
                    <input type="text" name="event_name" required
                           value="<?= htmlspecialchars($edit_item['event_name'] ?? '') ?>"
                           placeholder="e.g. Hackathon 2025">
                </div>
                <div class="form-group-admin">
                    <label>Description</label>
                    <input type="text" name="description"
                           value="<?= htmlspecialchars($edit_item['description'] ?? '') ?>"
                           placeholder="One-line summary of the event">
                </div>
            </div>

            <!-- Thumbnail upload -->
            <div class="form-group-admin" style="margin-top:1.1rem;">
                <label>Cover / Thumbnail Image <?= $edit_item && $edit_item['thumbnail'] ? '<span style="font-weight:400;color:var(--text-dim)">(upload new to replace)</span>' : '*' ?></label>
                <small style="display:block;color:var(--text-dim);margin-bottom:.45rem;">
                    Select an image from your device (JPG, PNG, WebP). Max 8 MB. This is shown as the album card on the public gallery page.
                </small>
                <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp,image/gif"
                       id="thumbFile" <?= ($edit_item && $edit_item['thumbnail']) ? '' : 'required' ?>
                       style="display:block;padding:.4rem 0;">
                <!-- Preview of selected file -->
                <div id="new-preview-wrap" style="display:none;margin-top:.6rem;">
                    <div style="font-size:.72rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">New thumbnail preview</div>
                    <img id="new-preview" style="max-height:160px;border-radius:var(--radius);border:1px solid var(--border);object-fit:cover;">
                </div>
                <!-- Current thumbnail (edit mode) -->
                <?php if ($edit_item && $edit_item['thumbnail']): ?>
                <div style="margin-top:.6rem;">
                    <div style="font-size:.72rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Current thumbnail</div>
                    <img src="<?= UPLOAD_PATH . htmlspecialchars($edit_item['thumbnail']) ?>"
                         style="max-height:140px;border-radius:var(--radius);border:1px solid var(--border);object-fit:cover;"
                         onerror="this.style.display='none'">
                </div>
                <?php endif; ?>
            </div>

            <!-- Drive folder link -->
            <div class="form-group-admin" style="margin-top:1.1rem;">
                <label>Google Drive Folder Link</label>
                <small style="display:block;color:var(--text-dim);margin-bottom:.45rem;">
                    In Google Drive: right-click the folder → <strong>Share</strong> → <strong>Copy link</strong>. Paste it here.
                    Users will be redirected to this folder when they click the album.
                </small>
                <input type="url" name="drive_folder_url"
                       value="<?= htmlspecialchars($edit_item['drive_folder_url'] ?? '') ?>"
                       placeholder="https://drive.google.com/drive/folders/1AbCdEfGhIjKlMn…">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="msi"><?= $edit_item ? 'save' : 'create_new_folder' ?></span>
                    <?= $edit_item ? 'Save Changes' : 'Create Album' ?>
                </button>
                <a href="/admin/gallery.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('thumbFile').addEventListener('change', function () {
    const wrap = document.getElementById('new-preview-wrap');
    const img  = document.getElementById('new-preview');
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
        reader.readAsDataURL(this.files[0]);
    } else {
        wrap.style.display = 'none';
    }
});
</script>
<?php endif; ?>

<!-- ── ALBUM LIST ─────────────────────────────────────────────────────── -->
<?php if (empty($albums)): ?>
<div class="empty-state">
    <div class="empty-icon"><span class="msi" style="font-size:2.5rem">photo_library</span></div>
    <p>No albums yet. <a href="?action=new_album" style="color:var(--primary)">Create the first one →</a></p>
</div>

<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
    <?php foreach ($albums as $a): ?>
    <div style="background:white;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">

        <!-- Thumbnail -->
        <div style="aspect-ratio:16/9;background:var(--surface2);position:relative;overflow:hidden;">
            <?php if ($a['thumbnail']): ?>
            <img src="<?= UPLOAD_PATH . htmlspecialchars($a['thumbnail']) ?>"
                 style="width:100%;height:100%;object-fit:cover;"
                 onerror="this.style.display='none'">
            <?php else: ?>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;color:var(--text-dim);gap:.4rem;">
                <span class="msi" style="font-size:2.5rem;opacity:.3">image</span>
                <span style="font-size:.72rem;">No thumbnail</span>
            </div>
            <?php endif; ?>
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.65));padding:.75rem .85rem .6rem;">
                <div style="color:white;font-weight:800;font-size:.95rem;"><?= htmlspecialchars($a['event_name']) ?></div>
            </div>
        </div>

        <!-- Info + actions -->
        <div style="padding:.85rem 1rem;">
            <?php if ($a['description']): ?>
            <div style="font-size:.78rem;color:var(--text-mid);margin-bottom:.5rem;"><?= htmlspecialchars($a['description']) ?></div>
            <?php endif; ?>

            <!-- Drive link status -->
            <?php if ($a['drive_folder_url']): ?>
            <a href="<?= htmlspecialchars($a['drive_folder_url']) ?>" target="_blank"
               style="display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;color:var(--primary);text-decoration:none;margin-bottom:.65rem;background:var(--primary-light);padding:.25rem .6rem;border-radius:100px;">
                <span class="msi" style="font-size:14px">folder_open</span>View Drive Folder
            </a>
            <?php else: ?>
            <div style="display:inline-flex;align-items:center;gap:.3rem;font-size:.72rem;color:var(--warning);margin-bottom:.65rem;background:var(--warning-light,#fffbeb);padding:.2rem .55rem;border-radius:100px;">
                <span class="msi" style="font-size:13px">warning</span>No Drive link set
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:.5rem;">
                <a href="?action=edit_album&id=<?= $a['id'] ?>" class="btn btn-warning btn-sm" style="flex:1;justify-content:center;">
                    <span class="msi" style="font-size:14px">edit</span>Edit
                </a>
                <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($a['event_name']) ?>" style="display:inline;">
                    <input type="hidden" name="action" value="delete_album">
                    <input type="hidden" name="id"     value="<?= $a['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <span class="msi" style="font-size:14px">delete</span>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon"><span class="msi">delete_forever</span></div>
        <h3>Delete Album?</h3>
        <p>Delete "<span id="confirm-item-name"></span>"? The thumbnail file will also be removed.</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()">
                <span class="msi" style="font-size:15px">delete</span>Delete
            </button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
