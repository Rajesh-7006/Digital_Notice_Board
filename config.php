<?php
// ---------- Database settings (XAMPP defaults) ----------
const DB_HOST = 'localhost';
const DB_NAME = 'notice_board';
const DB_USER = 'root';
const DB_PASS = '';

date_default_timezone_set('Asia/Kolkata');
session_start();

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $ex) {
    exit('Database connection failed. Start MySQL in XAMPP and import db.sql.');
}

// ---------- Helpers ----------
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function redirect(string $url): void { header("Location: $url"); exit; }

function require_login(): void {
    if (empty($_SESSION['user_id'])) redirect('login.php');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid request token. Go back and try again.');
    }
}

function flash(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = [$msg, $type];
}

// draft | published | expired
function notice_state(array $n): string {
    if ($n['status'] === 'draft') return 'draft';
    if (!empty($n['expiry_date']) && $n['expiry_date'] < date('Y-m-d')) return 'expired';
    return 'published';
}
function priority_badge(string $p): string {
    return '<span class="badge ' . e($p) . '">' . strtoupper(e($p)) . '</span>';
}
function state_badge(string $s): string {
    return '<span class="badge st-' . e($s) . '">' . ucfirst(e($s)) . '</span>';
}
