<?php
$pageTitle = 'Notices'; $active = 'notices';
require __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    csrf_check();
    $pdo->prepare('DELETE FROM notices WHERE id = ?')->execute([(int)$_POST['id']]);
    flash('Notice deleted.');
    redirect('notices.php');
}

$q = trim($_GET['q'] ?? '');
$f = $_GET['status'] ?? '';
$today = date('Y-m-d');
$where = []; $params = [];
if ($q !== '') { $where[] = '(title LIKE ? OR content LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($f === 'published') { $where[] = "status='published' AND (expiry_date IS NULL OR expiry_date >= ?)"; $params[] = $today; }
elseif ($f === 'expired') { $where[] = "status='published' AND expiry_date < ?"; $params[] = $today; }
elseif ($f === 'draft') { $where[] = "status='draft'"; }

$sql = 'SELECT * FROM notices' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC, id DESC';
$st = $pdo->prepare($sql); $st->execute($params);
$notices = $st->fetchAll();
?>
<div class="page-head"><h1>All Notices</h1><a class="btn" href="publish.php">+ New Notice</a></div>
<div class="card">
    <form class="filters" method="get">
        <input type="text" name="q" placeholder="Search notices..." value="<?= e($q) ?>">
        <select name="status">
            <option value="">All statuses</option>
            <?php foreach (['published' => 'Published', 'expired' => 'Expired', 'draft' => 'Draft'] as $k => $v): ?>
                <option value="<?= $k ?>" <?= $f === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn">Filter</button>
        <a class="btn gray" href="notices.php">Reset</a>
    </form>
    <div class="table-wrap"><table>
        <tr><th>Title</th><th>Priority</th><th>Status</th><th>Expires</th><th>Created</th><th>Actions</th></tr>
        <?php foreach ($notices as $n): ?>
        <tr>
            <td><strong><?= e($n['title']) ?></strong></td>
            <td><?= priority_badge($n['priority']) ?></td>
            <td><?= state_badge(notice_state($n)) ?></td>
            <td class="muted"><?= $n['expiry_date'] ? e(date('d M Y', strtotime($n['expiry_date']))) : '—' ?></td>
            <td class="muted"><?= e(date('d M Y', strtotime($n['created_at']))) ?></td>
            <td class="actions">
                <a class="btn sm gray" href="publish.php?id=<?= (int)$n['id'] ?>">Edit</a>
                <form method="post" onsubmit="return confirm('Delete this notice permanently?')">
                    <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                    <button class="btn sm red">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$notices): ?><tr><td colspan="6" class="empty">No notices found.</td></tr><?php endif; ?>
    </table></div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
