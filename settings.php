<?php
$pageTitle = 'Settings'; $active = 'settings';
require __DIR__ . '/includes/header.php';

$uid = (int)$_SESSION['user_id'];
$st = $pdo->prepare('SELECT * FROM users WHERE id = ?'); $st->execute([$uid]);
$user = $st->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'profile') {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') { flash('Name cannot be empty.', 'error'); }
        else {
            $pdo->prepare('UPDATE users SET name=? WHERE id=?')->execute([$name, $uid]);
            $_SESSION['user_name'] = $name;
            flash('Profile updated.');
        }
    } elseif (($_POST['action'] ?? '') === 'password') {
        $cur = $_POST['current'] ?? ''; $new = $_POST['new'] ?? ''; $conf = $_POST['confirm'] ?? '';
        if (!password_verify($cur, $user['password'])) flash('Current password is incorrect.', 'error');
        elseif (strlen($new) < 6) flash('New password must be at least 6 characters.', 'error');
        elseif ($new !== $conf) flash('Passwords do not match.', 'error');
        else {
            $pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash($new, PASSWORD_DEFAULT), $uid]);
            flash('Password changed.');
        }
    }
    redirect('settings.php');
}
?>
<div class="page-head"><h1>Settings</h1></div>
<div class="card">
    <h2>Profile</h2>
    <form method="post" style="max-width:480px">
        <?= csrf_field() ?><input type="hidden" name="action" value="profile">
        <div class="form-group"><label>Name</label><input type="text" name="name" value="<?= e($user['name']) ?>" required></div>
        <div class="form-group"><label>Email</label><input type="email" value="<?= e($user['email']) ?>" disabled></div>
        <button class="btn">Save Profile</button>
    </form>
</div>
<div class="card">
    <h2>Change Password</h2>
    <form method="post" style="max-width:480px">
        <?= csrf_field() ?><input type="hidden" name="action" value="password">
        <div class="form-group"><label>Current Password</label><input type="password" name="current" required></div>
        <div class="form-group"><label>New Password</label><input type="password" name="new" required></div>
        <div class="form-group"><label>Confirm New Password</label><input type="password" name="confirm" required></div>
        <button class="btn">Update Password</button>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
