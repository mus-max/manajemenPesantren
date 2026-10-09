<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) redirect(BASE_URL . '/dashboard/');

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRF();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi.';
    } elseif (!validateEmail($email)) {
        $error = 'Format email tidak valid.';
    } else {
        $attempts = checkRateLimit($email);
        if ($attempts >= 5) {
            $error = 'Terlalu banyak percobaan gagal. Coba lagi dalam 15 menit.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] != 1) {
                    $error = 'Akun Anda tidak aktif.';
                } else {
                    regenerateSession();
                    $_SESSION['user_id'] = intval($user['id_user']);
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['login_time'] = time();
                    resetRateLimit($email);

                    $stmt = $pdo->prepare("INSERT INTO log_login (email, status_login) VALUES (?, 'Sukses')");
                    $stmt->execute([$email]);

                    setAlert('success', 'Selamat datang, ' . $user['username'] . '!');
                    redirect(BASE_URL . '/dashboard/');
                }
            } else {
                recordFailedLogin($email);
                $stmt = $pdo->prepare("INSERT INTO log_login (email, status_login) VALUES (?, 'Gagal')");
                $stmt->execute([$email]);
                $error = 'Email atau password salah. (' . (5 - checkRateLimit($email)) . ' percobaan tersisa)';
            }
        }
    }
}

$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Pesantren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="login-icon"><i class="bi bi-book-half"></i></div>
                            <h5 class="fw-semibold mb-0">Manajemen Pesantren</h5>
                            <small class="text-muted">Masuk ke panel administrasi</small>
                        </div>
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-custom py-2 mb-3"><?= sanitize($error) ?></div>
                        <?php endif; ?>
                        <?php if ($alert): ?>
                            <div class="alert alert-<?= $alert['type'] ?> alert-custom py-2 mb-3"><?= sanitize($alert['message']) ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <?= csrfField() ?>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= sanitize($email) ?>" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                Masuk
                            </button>
                        </form>
                        <p class="mt-3 mb-0 text-center"><small class="text-muted">Belum punya akun? <a href="register.php">Daftar</a></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
