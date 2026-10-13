<?php
require_once __DIR__ . '/config.php';

$msg = ''; $msgType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $nid  = (int)($_POST['notice_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $text = trim($_POST['comment'] ?? '');
    $chk = $pdo->prepare("SELECT COUNT(*) FROM notices WHERE id=? AND status='published' AND (expiry_date IS NULL OR expiry_date >= ?)");
    $chk->execute([$nid, date('Y-m-d')]);
    if (!$chk->fetchColumn()) { $msg = 'This notice is not available.'; $msgType = 'error'; }
    elseif ($name === '' || $text === '' || mb_strlen($name) > 100 || mb_strlen($text) > 1000) { $msg = 'Please enter your name and a comment (max 1000 characters).'; $msgType = 'error'; }
    else {
        $pdo->prepare('INSERT INTO comments (notice_id, name, comment) VALUES (?,?,?)')->execute([$nid, $name, $text]);
        $msg = 'Thanks! Your comment will appear after approval.';
    }
}

$st = $pdo->prepare("SELECT * FROM notices WHERE status='published' AND (expiry_date IS NULL OR expiry_date >= ?)
                     ORDER BY FIELD(priority,'urgent','important','normal'), created_at DESC");
$st->execute([date('Y-m-d')]);
$notices = $st->fetchAll();
$cs = $pdo->prepare("SELECT * FROM comments WHERE notice_id=? AND status='approved' ORDER BY created_at");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notice Board</title>
<?php include __DIR__ . '/includes/style.php'; ?>
</head>
<body>
<header class="topbar">
    <a class="brand" href="board.php">📋 Digital Notice Board</a>
    <a class="btn sm" href="<?= empty($_SESSION['user_id']) ? 'login.php' : 'index.php' ?>"><?= empty($_SESSION['user_id']) ? 'Admin Login' : 'Dashboard' ?></a>
</header>
<div class="public-wrap">
    <h1 style="margin-bottom:18px">Latest Notices</h1>
    <?php if ($msg): ?><div class="alert <?= e($msgType) ?>"><?= e($msg) ?></div><?php endif; ?>
    <?php foreach ($notices as $n): $cs->execute([$n['id']]); $cm = $cs->fetchAll(); ?>
    <div class="card notice-card <?= e($n['priority']) ?>">
        <?= priority_badge($n['priority']) ?>
        <h2><?= e($n['title']) ?></h2>
        <p class="muted" style="font-size:13px">Posted <?= e(date('d M Y', strtotime($n['created_at']))) ?><?= $n['expiry_date'] ? ' · Valid till ' . e(date('d M Y', strtotime($n['expiry_date']))) : '' ?></p>
        <p style="margin-top:12px"><?= nl2br(e($n['content'])) ?></p>
        <hr style="border:none;border-top:1px solid var(--border);margin:18px 0">
        <strong>Comments (<?= count($cm) ?>)</strong>
        <?php foreach ($cm as $c): ?><div class="comment"><strong><?= e($c['name']) ?></strong><br><?= nl2br(e($c['comment'])) ?></div><?php endforeach; ?>
        <form method="post" style="margin-top:14px">
            <?= csrf_field() ?><input type="hidden" name="notice_id" value="<?= (int)$n['id'] ?>">
            <div class="form-row">
                <div class="form-group"><input type="text" name="name" placeholder="Your name" maxlength="100" required></div>
                <div class="form-group"><input type="text" name="comment" placeholder="Write a comment..." maxlength="1000" required></div>
            </div>
            <button class="btn sm">Post Comment</button>
        </form>
    </div>
    <?php endforeach; ?>
    <?php if (!$notices): ?><div class="card empty">No active notices right now.</div><?php endif; ?>
</div>
</body>
</html>
