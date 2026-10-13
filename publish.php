<?php
$active = 'publish';
require_once __DIR__ . '/config.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$notice = ['title' => '', 'content' => '', 'priority' => 'normal', 'expiry_date' => ''];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM notices WHERE id = ?');
    $st->execute([$id]);
    $notice = $st->fetch();
    if (!$notice) { flash('Notice not found.', 'error'); redirect('notices.php'); }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title    = trim($_POST['title'] ?? '');
    $content  = trim($_POST['content'] ?? '');
    $priority = in_array($_POST['priority'] ?? '', ['normal', 'important', 'urgent'], true) ? $_POST['priority'] : 'normal';
    $expiry   = trim($_POST['expiry_date'] ?? '');
    $status   = isset($_POST['save_draft']) ? 'draft' : 'published';

    if ($title === '' || mb_strlen($title) > 200) $errors[] = 'Title is required (max 200 characters).';
    if ($content === '') $errors[] = 'Content is required.';
    if ($expiry !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $expiry);
        if (!$d || $d->format('Y-m-d') !== $expiry) $errors[] = 'Invalid expiry date.';
    } else { $expiry = null; }

    if (!$errors) {
        if ($id) {
            $pdo->prepare('UPDATE notices SET title=?, content=?, priority=?, status=?, expiry_date=? WHERE id=?')
                ->execute([$title, $content, $priority, $status, $expiry, $id]);
        } else {
            $pdo->prepare('INSERT INTO notices (title, content, priority, status, expiry_date, author_id) VALUES (?,?,?,?,?,?)')
                ->execute([$title, $content, $priority, $status, $expiry, $_SESSION['user_id']]);
        }
        flash($status === 'draft' ? 'Saved as draft.' : 'Notice published.');
        redirect($status === 'draft' ? 'drafts.php' : 'notices.php');
    }
    $notice = ['title' => $title, 'content' => $content, 'priority' => $priority, 'expiry_date' => $expiry ?? ''];
}

$pageTitle = $id ? 'Edit Notice' : 'Publish Notice';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1><?= $id ? 'Edit Notice' : 'Publish New Notice' ?></h1></div>
<div class="card">
    <?php foreach ($errors as $er): ?><div class="alert error"><?= e($er) ?></div><?php endforeach; ?>
    <form method="post">
        <?= csrf_field() ?>
        <div class="form-group"><label>Title</label><input type="text" name="title" maxlength="200" value="<?= e($notice['title']) ?>" required></div>
        <div class="form-group"><label>Content</label><textarea name="content" required><?= e($notice['content']) ?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Priority</label>
                <select name="priority">
                    <?php foreach (['normal' => 'Normal', 'important' => 'Important', 'urgent' => 'Urgent'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $notice['priority'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="form-group"><label>Expiry Date (optional)</label><input type="date" name="expiry_date" value="<?= e($notice['expiry_date']) ?>"></div>
        </div>
        <div class="actions">
            <button class="btn green" name="publish">Publish</button>
            <button class="btn gray" name="save_draft">Save as Draft</button>
            <a class="btn gray" href="notices.php">Cancel</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
