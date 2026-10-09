<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    setAlert('warning', 'Silakan login terlebih dahulu.');
    redirect(BASE_URL . '/auth/login.php');
}

// Session timeout 2 jam
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 7200) {
    session_unset();
    session_destroy();
    setAlert('warning', 'Sesi expired. Silakan login kembali.');
    redirect(BASE_URL . '/auth/login.php');
}

// Regenerate session ID setiap 30 menit (anti session fixation)
if (!isset($_SESSION['last_regenerate'])) {
    $_SESSION['last_regenerate'] = time();
} elseif (time() - $_SESSION['last_regenerate'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regenerate'] = time();
}
?>