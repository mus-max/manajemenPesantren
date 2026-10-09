<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sanitize($data) {
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data) {
    return intval($data ?? 0);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'Admin';
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function setAlert($type, $message) {
    $_SESSION['alert'] = ['type' => $type, 'message' => $message];
}

function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

function formatDate($date) {
    if (!$date) return '-';
    $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $d = date('d', strtotime($date));
    $m = (int)date('m', strtotime($date));
    $y = date('Y', strtotime($date));
    return $d . ' ' . $months[$m] . ' ' . $y;
}

function formatTime($time) {
    if (!$time) return '-';
    return date('H:i', strtotime($time));
}

// CSRF Token
function generateCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRF() . '">';
}

function verifyCSRF() {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        setAlert('danger', 'Sesi tidak valid. Silakan coba lagi.');
        redirect($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/dashboard/');
    }
}

// Rate limiting untuk login
function checkRateLimit($email) {
    $key = 'rate_' . md5($email);
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'last' => 0];
    }
    $data = &$_SESSION[$key];
    // Reset setelah 15 menit
    if (time() - $data['last'] > 900) {
        $data['count'] = 0;
    }
    return $data['count'];
}

function recordFailedLogin($email) {
    $key = 'rate_' . md5($email);
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'last' => 0];
    }
    $_SESSION[$key]['count']++;
    $_SESSION[$key]['last'] = time();
}

function resetRateLimit($email) {
    unset($_SESSION['rate_' . md5($email)]);
}

// Regenerate session ID (anti session fixation)
function regenerateSession() {
    if (session_status() === PHP_SESSION_NONE) return;
    session_regenerate_id(true);
}

// Validasi input
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateLength($data, $min, $max) {
    $len = strlen(trim($data ?? ''));
    return $len >= $min && $len <= $max;
}

// Prevent SQL injection extra layer — strip anything suspicious
function cleanInput($data) {
    $data = trim($data ?? '');
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>