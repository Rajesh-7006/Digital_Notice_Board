<?php
$pageTitle = 'Drafts'; $active = 'drafts';
require __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $nid = (int)($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'publish') {
        $pdo->prepare("UPDATE notices SET status='published' WHERE id=? AND status='draft'")->execute([$nid]);
        flash('Draft published.');
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $pdo->prepare("DELETE FROM notices WHERE id=? AND status='draft'")->execute([$nid]);
        flash('Draft deleted.');
    }
    redirect('drafts.php');
}
$drafts = $pdo->query("SELECT * FROM notices WHERE status='draft' ORDER BY updated_at DESC")->fetchAll();
?>
<div class="page-head"><h1>Drafts</h1><a class="btn" href="publish.php">+ New Notice</a></div>
<div class="card"><div class="table-wrap"><table>
    <tr><th>Title</th><th>Priority</th><th>Last Updated</th><th>Actions</th></tr>
    <?php foreach ($drafts as $n): ?>
    <tr>
        <td><strong><?= e($n['title']) ?></strong></td>
        <td><?= priority_badge($n['priority']) ?></td>
        <td class="muted"><?= e(date('d M Y, h:i A', strtotime($n['updated_at']))) ?></td>
        <td class="actions">
            <a class="btn sm gray" href="publish.php?id=<?= (int)$n['id'] ?>">Edit</a>
            <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="publish"><input type="hidden" name="id" value="<?= (int)$n['id'] ?>"><button class="btn sm green">Publish</button></form>
            <form method="post" onsubmit="return confirm('Delete this draft?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$n['id'] ?>"><button class="btn sm red">Delete</button></form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$drafts): ?><tr><td colspan="4" class="empty">No drafts.</td></tr><?php endif; ?>
</table></div></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
