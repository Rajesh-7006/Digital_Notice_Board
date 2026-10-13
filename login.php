<?php
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['user_id'])) redirect('index.php');

// First run: create a default admin account if no users exist
if ((int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
    $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?,?,?)')
        ->execute(['Rajesh', 'admin@example.com', password_hash('admin123', PASSWORD_DEFAULT)]);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $st = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $st->execute([$email]);
    $user = $st->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        redirect('index.php');
    }
    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Digital Notice Board</title>
<?php include __DIR__ . '/includes/style.php'; ?>
</head>
<body>
<div class="login-wrap">
    <form class="login-card" method="post">
        <h1>📋 Notice Board</h1>
        <p class="muted">Sign in to manage notices</p>
        <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
        <?= csrf_field() ?>
        <div class="form-group"><label>Email</label><input type="email" name="email" required autofocus></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button class="btn" style="width:100%">Login</button>
        <p class="muted" style="margin-top:16px;font-size:13px"><a href="board.php">View public notice board →</a></p>
    </form>
</div>
</body>
</html>
