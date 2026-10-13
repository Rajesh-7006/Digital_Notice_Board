<?php
require_once __DIR__ . '/../config.php';
require_login();
$pageTitle = $pageTitle ?? 'Dashboard';
$active    = $active ?? 'dashboard';
$pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM comments WHERE status='pending'")->fetchColumn();
$nav = [
    'dashboard' => ['index.php',    'Dashboard', '🏠'],
    'notices'   => ['notices.php',  'Notices',   '📢'],
    'publish'   => ['publish.php',  'Publish',   '✍️'],
    'drafts'    => ['drafts.php',   'Drafts',    '📝'],
    'comments'  => ['comments.php', 'Comments',  '💬'],
    'settings'  => ['settings.php', 'Settings',  '⚙️'],
];
$userName = $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | Digital Notice Board</title>
<?php include __DIR__ . '/style.php'; ?>
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php">📋 Digital Notice Board</a>
    <div class="top-right">
        <a class="muted" href="board.php" target="_blank">View Public Board ↗</a>
        <a class="bell" href="comments.php" title="Pending comments">🔔<?php if ($pendingCount): ?><span class="dot"><?= $pendingCount ?></span><?php endif; ?></a>
        <div class="user"><span class="avatar"><?= e(strtoupper(substr($userName, 0, 1))) ?></span><?= e($userName) ?></div>
    </div>
</header>
<aside class="sidebar">
    <?php foreach ($nav as $key => [$file, $label, $icon]): ?>
        <a href="<?= $file ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= $icon ?> <?= $label ?></a>
    <?php endforeach; ?>
    <div class="spacer"></div>
    <a href="logout.php" class="logout">🚪 Logout</a>
</aside>
<main class="content">
<?php if (!empty($_SESSION['flash'])): [$fm, $ft] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert <?= e($ft) ?>"><?= e($fm) ?></div>
<?php endif; ?>
