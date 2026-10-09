
<div class="d-flex">
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-header px-3 py-2">
                <small class="text-uppercase fw-semibold" style="color:#fff;font-size:0.7rem;letter-spacing:0.05em">Menu</small>
            </div>
            <ul class="nav flex-column py-1">
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], 'dashboard') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/dashboard/">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/ustadz/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/ustadz/">
                        <i class="bi bi-person-badge"></i>Ustadz
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/pengurus/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/pengurus/">
                        <i class="bi bi-clipboard-check"></i>Pengurus
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/kelas/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/kelas/">
                        <i class="bi bi-door-open"></i>Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/santri/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/santri/">
                        <i class="bi bi-people"></i>Santri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/kitab/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/kitab/">
                        <i class="bi bi-journal-text"></i>Kitab
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/jadwal/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/jadwal/">
                        <i class="bi bi-calendar3"></i>Jadwal
                    </a>
                </li>
                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/user/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/user/">
                        <i class="bi bi-person-gear"></i>User
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/log/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/log/login_log.php">
                        <i class="bi bi-clock-history"></i>Log
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/laporan/') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/laporan/">
                        <i class="bi bi-filetype-pdf"></i>Laporan
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <main class="main-content flex-grow-1">
        <div class="container-fluid py-3 px-4">
