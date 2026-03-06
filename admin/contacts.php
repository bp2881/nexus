<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Contacts';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    db_execute("DELETE FROM contact_requests WHERE id=?", [(int)($_POST['id'] ?? 0)]);
    $msg = '✅ Request deleted.';
}

$contacts   = db_query("SELECT * FROM contact_requests ORDER BY created_at DESC");
$type_badge = ['join'=>'badge-blue','project'=>'badge-purple','resource'=>'badge-amber','general'=>'badge-gray'];

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <div><p class="page-eyebrow">Inbox</p><h1>Contact Requests</h1></div>
    <span style="font-size:0.82rem;color:var(--text-dim);"><?= count($contacts) ?> total</span>
</div>

<?php if ($msg): ?><div class="alert alert-success"><span class="msi">check_circle</span><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title">All Submissions</span>
        <input type="text" class="table-search" placeholder="Search…" data-target="#contacts-tbody">
    </div>
    <?php if (empty($contacts)): ?>
    <div class="empty-state"><div class="empty-icon"><span class="msi" style="font-size:2.5rem">mark_email_unread</span></div><p>No contact requests yet.</p></div>
    <?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Type</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody id="contacts-tbody">
        <?php foreach ($contacts as $c): ?>
        <tr>
            <td class="td-mono"><?= $c['id'] ?></td>
            <td><div class="td-title"><?= htmlspecialchars($c['name']) ?></div></td>
            <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>" style="color:var(--primary);font-size:0.82rem;"><?= htmlspecialchars($c['email']) ?></a></td>
            <td><span class="badge <?= $type_badge[$c['request_type']] ?? 'badge-gray' ?>"><?= htmlspecialchars($c['request_type']) ?></span></td>
            <td style="max-width:280px;font-size:0.82rem;color:var(--text-mid);"><?= htmlspecialchars(substr($c['message'] ?: '—', 0, 90)) ?><?= strlen($c['message']) > 90 ? '…' : '' ?></td>
            <td class="td-mono"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
            <td>
                <div class="td-actions">
                    <a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="btn btn-outline btn-sm"><span class="msi" style="font-size:14px">reply</span>Reply</a>
                    <form method="POST" class="delete-form" data-title="<?= htmlspecialchars($c['name']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id"     value="<?= $c['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><span class="msi" style="font-size:14px">delete</span></button>
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
        <h3>Delete Request?</h3>
        <p>Delete request from "<span id="confirm-item-name"></span>"?</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="cancelDelete()">Cancel</button>
            <button class="btn btn-danger"  onclick="proceedDelete()"><span class="msi" style="font-size:15px">delete</span>Delete</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
