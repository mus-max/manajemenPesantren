<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

// Log logout activity
if (isset($_SESSION['email'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO log_login (email, status_login) VALUES (?, 'Logout')");
        $stmt->execute([$_SESSION['email']]);
    } catch (Exception $e) {}
}

// Clear all session data
$_SESSION = [];

// Destroy session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// Redirect
header("Location: " . BASE_URL . "/auth/login.php");
exit;
?>