<?php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <title><?= sanitize($pageTitle ?? 'Manajemen Pesantren') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark navbar-expand-lg fixed-top" style="background:#264653">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/dashboard/">
            <i class="bi bi-book-half"></i>
            <span>SIMASTREN</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1 py-1" href="#" role="button" data-bs-toggle="dropdown" style="font-size:0.85rem">
                        <i class="bi bi-person-circle"></i>
                        <?= sanitize($_SESSION['username'] ?? 'User') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.85rem;min-width:160px">
                        <li><a class="dropdown-item py-1" href="<?= BASE_URL ?>/user/edit.php?id=<?= intval($_SESSION['user_id'] ?? 0) ?>"><i class="bi bi-gear me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-1 text-danger" href="<?= BASE_URL ?>/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
