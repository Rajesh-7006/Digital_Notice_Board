<?php
$pageTitle = 'Comments'; $active = 'comments';
require __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $cid = (int)($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'approve') {
        $pdo->prepare("UPDATE comments SET status='approved' WHERE id=?")->execute([$cid]);
        flash('Comment approved.');
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $pdo->prepare('DELETE FROM comments WHERE id=?')->execute([$cid]);
        flash('Comment deleted.');
    }
    redirect('comments.php');
}
$comments = $pdo->query("SELECT c.*, n.title FROM comments c JOIN notices n ON n.id = c.notice_id
                         ORDER BY c.status='pending' DESC, c.created_at DESC")->fetchAll();
?>
<div class="page-head"><h1>Comments</h1></div>
<div class="card"><div class="table-wrap"><table>
    <tr><th>Notice</th><th>Name</th><th>Comment</th><th>Status</th><th>Date</th><th>Actions</th></tr>
    <?php foreach ($comments as $c): ?>
    <tr>
        <td><?= e($c['title']) ?></td>
        <td><strong><?= e($c['name']) ?></strong></td>
        <td style="max-width:320px"><?= nl2br(e($c['comment'])) ?></td>
        <td><?= state_badge($c['status']) ?></td>
        <td class="muted"><?= e(date('d M Y', strtotime($c['created_at']))) ?></td>
        <td class="actions">
            <?php if ($c['status'] === 'pending'): ?>
            <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>"><button class="btn sm green">Approve</button></form>
            <?php endif; ?>
            <form method="post" onsubmit="return confirm('Delete this comment?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>"><button class="btn sm red">Delete</button></form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$comments): ?><tr><td colspan="6" class="empty">No comments yet.</td></tr><?php endif; ?>
</table></div></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
