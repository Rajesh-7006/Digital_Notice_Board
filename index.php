<?php
$pageTitle = 'Dashboard'; $active = 'dashboard';
require __DIR__ . '/includes/header.php';

$today = date('Y-m-d');
$st = $pdo->prepare("SELECT COUNT(*) total,
    SUM(status='published' AND (expiry_date IS NULL OR expiry_date >= ?)) published,
    SUM(status='published' AND expiry_date < ?) expired,
    SUM(status='draft') drafts FROM notices");
$st->execute([$today, $today]);
$s = $st->fetch();

$recent = $pdo->query('SELECT * FROM notices ORDER BY created_at DESC, id DESC LIMIT 5')->fetchAll();
$h = (int)date('G');
$greet = $h < 12 ? 'Good Morning' : ($h < 17 ? 'Good Afternoon' : 'Good Evening');
?>
<div class="page-head">
    <div><h1><?= $greet ?>, <?= e($userName) ?> 👋</h1><p class="muted">Here is what's happening on your notice board.</p></div>
    <a class="btn" href="publish.php">+ New Notice</a>
</div>

<div class="stats">
    <div class="stat"><div class="num"><?= (int)$s['total'] ?></div><div class="lbl">Total</div></div>
    <div class="stat green"><div class="num"><?= (int)$s['published'] ?></div><div class="lbl">Published</div></div>
    <div class="stat red"><div class="num"><?= (int)$s['expired'] ?></div><div class="lbl">Expired</div></div>
    <div class="stat amber"><div class="num"><?= (int)$s['drafts'] ?></div><div class="lbl">Drafts</div></div>
</div>

<div class="card">
    <h2>Recent Notices</h2>
    <div class="table-wrap"><table>
        <tr><th>Title</th><th>Priority</th><th>Status</th><th>Created</th><th></th></tr>
        <?php foreach ($recent as $n): ?>
        <tr>
            <td><strong><?= e($n['title']) ?></strong></td>
            <td><?= priority_badge($n['priority']) ?></td>
            <td><?= state_badge(notice_state($n)) ?></td>
            <td class="muted"><?= e(date('d M Y', strtotime($n['created_at']))) ?></td>
            <td><a class="btn sm gray" href="publish.php?id=<?= (int)$n['id'] ?>">Edit</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$recent): ?><tr><td colspan="5" class="empty">No notices yet.</td></tr><?php endif; ?>
    </table></div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
