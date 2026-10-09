<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) redirect(BASE_URL . '/dashboard/');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRF();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi.';
    } elseif (!validateEmail($email)) {
        $error = 'Format email tidak valid.';
    } elseif (!validateLength($username, 3, 50)) {
        $error = 'Username harus 3-50 karakter.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password harus mengandung huruf besar dan angka.';
    } elseif ($password !== $password_confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Email atau username sudah terdaftar.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO user (username, email, password, role, status) VALUES (?, ?, ?, 'Operator', 1)");
            $stmt->execute([$username, $email, $hashed]);
            $success = 'Registrasi berhasil! Silakan login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Manajemen Pesantren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="card-body text-center">
                        <div class="login-icon"><i class="bi bi-person-plus"></i></div>
                        <h4 class="fw-bold mb-1">Daftar Akun Baru</h4>
                        <p class="text-muted mb-4">Buat akun untuk mengakses sistem</p>
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-custom"><?= sanitize($error) ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-custom"><?= sanitize($success) ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <?= csrfField() ?>
                            <div class="mb-3 text-start">
                                <label class="form-label fw-semibold">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?= sanitize($_POST['username'] ?? '') ?>" required minlength="3" maxlength="50">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="<?= sanitize($_POST['email'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter, ada huruf besar & angka" required minlength="8">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password" required>
                            </div>
                            <button type="submit" class="btn w-100 py-2 fw-semibold" style="background:linear-gradient(135deg,#1a7a3a,#15803d);color:white;border:none">
                                <i class="bi bi-person-plus me-1"></i> Daftar
                            </button>
                        </form>
                        <p class="mt-3 text-muted small">Sudah punya akun? <a href="login.php" class="text-decoration-none">Masuk di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
