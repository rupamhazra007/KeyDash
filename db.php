<?php
// db.php — PDO connect + session start (edit creds if needed)
$DB_HOST = '127.0.0.1';
$DB_NAME = 'keydash';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    exit("DB connection failed: " . htmlspecialchars($e->getMessage()));
}
if (session_status() === PHP_SESSION_NONE) session_start();

function current_user() { return $_SESSION['user'] ?? null; }
function require_login() {
    if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
}
function login_user($u) {
    $_SESSION['user'] = ['id'=>$u['id'], 'name'=>$u['name'], 'email'=>$u['email']];
}
function logout_user() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
