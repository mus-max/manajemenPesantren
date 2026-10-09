<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Dashboard';

$countSantri = $pdo->query("SELECT COUNT(*) FROM santri")->fetchColumn();
$countUstadz = $pdo->query("SELECT COUNT(*) FROM ustadz")->fetchColumn();
$countKelas = $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();
$countKitab = $pdo->query("SELECT COUNT(*) FROM kitab")->fetchColumn();
$countPengurus = $pdo->query("SELECT COUNT(*) FROM pengurus")->fetchColumn();

$hariIndo = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
$hariIni = $hariIndo[date('l')] ?? date('l');
$jadwalStmt = $pdo->prepare("SELECT j.*, u.nama_ustadz, k.nama_kitab, kl.nama_kelas FROM jadwal j JOIN ustadz u ON j.id_ustadz = u.id_ustadz JOIN kitab k ON j.id_kitab = k.id_kitab JOIN kelas kl ON j.id_kelas = kl.id_kelas WHERE j.hari = ? ORDER BY j.jam_mulai");
$jadwalStmt->execute([$hariIni]);
$jadwal = $jadwalStmt->fetchAll();

$logLogin = $pdo->query("SELECT * FROM log_login ORDER BY waktu_login DESC LIMIT 8")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Dashboard</h4>
        <small class="text-muted">Selamat datang, <?= sanitize($_SESSION['username'] ?? '') ?></small>
    </div>
    <small class="text-muted"><?= date('d/m/Y') ?></small>
</div>

<?php $alert = getAlert(); if ($alert): ?>
    <div class="alert alert-<?= $alert['type'] ?> alert-custom alert-dismissible fade show">
        <?= sanitize($alert['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-2 mb-3">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#ecfdf5;color:#2d6a4f"><i class="bi bi-people"></i></div>
            <div><h4 class="mb-0"><?= $countSantri ?></h4><small>Santri</small></div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-person-badge"></i></div>
            <div><h4 class="mb-0"><?= $countUstadz ?></h4><small>Ustadz</small></div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fefce8;color:#a16207"><i class="bi bi-door-open"></i></div>
            <div><h4 class="mb-0"><?= $countKelas ?></h4><small>Kelas</small></div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eff6ff;color:#2563eb"><i class="bi bi-journal-text"></i></div>
            <div><h4 class="mb-0"><?= $countKitab ?></h4><small>Kitab</small></div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fdf2f8;color:#db2777"><i class="bi bi-clipboard-check"></i></div>
            <div><h4 class="mb-0"><?= $countPengurus ?></h4><small>Pengurus</small></div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Statistik</h6></div>
            <div class="card-body"><canvas id="chartStatistik" height="200"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Jadwal Hari Ini</h6>
                <span class="badge" style="background:var(--pondok-green)"><?= $hariIni ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($jadwal)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar3 fs-3 d-block mb-1" style="opacity:0.4"></i>
                        <small>Tidak ada jadwal hari ini</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th>Jam</th><th>Kelas</th><th>Kitab</th><th>Ustadz</th></tr></thead>
                            <tbody>
                                <?php foreach ($jadwal as $j): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark"><?= formatTime($j['jam_mulai']) ?> - <?= formatTime($j['jam_selesai']) ?></span></td>
                                    <td><?= sanitize($j['nama_kelas']) ?></td>
                                    <td><?= sanitize($j['nama_kitab']) ?></td>
                                    <td><?= sanitize($j['nama_ustadz']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Log Login</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Email</th><th>Waktu</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($logLogin as $log): ?>
                            <tr>
                                <td><?= sanitize($log['email']) ?></td>
                                <td><small class="text-muted"><?= date('d/m H:i', strtotime($log['waktu_login'])) ?></small></td>
                                <td><?= $log['status_login'] === 'Sukses' ? '<span class="badge bg-success badge-status">OK</span>' : '<span class="badge bg-danger badge-status">Gagal</span>' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Distribusi Kelas</h6></div>
            <div class="card-body"><canvas id="chartKelas" height="200"></canvas></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctxStat = document.getElementById('chartStatistik').getContext('2d');
    new Chart(ctxStat, {
        type: 'bar',
        data: {
            labels: ['Santri','Ustadz','Kelas','Kitab','Pengurus'],
            datasets: [{ label: 'Jumlah', data: [<?= $countSantri ?>,<?= $countUstadz ?>,<?= $countKelas ?>,<?= $countKitab ?>,<?= $countPengurus ?>],
                backgroundColor: ['#2d6a4f','#40916c','#52b788','#95d5b2','#b7e4c7'], borderRadius: 4, borderSkipped: false }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } } }
    });

    var ctxKelas = document.getElementById('chartKelas').getContext('2d');
    var kelasLabels = [], kelasData = [];
    <?php
    $stmt = $pdo->query("SELECT k.nama_kelas, COUNT(s.id_santri) as jml FROM kelas k LEFT JOIN santri s ON k.id_kelas = s.kelas_id GROUP BY k.id_kelas ORDER BY jml DESC");
    foreach ($stmt->fetchAll() as $ks) {
        echo "kelasLabels.push('" . addslashes($ks['nama_kelas']) . "');\n";
        echo "kelasData.push(" . $ks['jml'] . ");\n";
    }
    ?>
    new Chart(ctxKelas, {
        type: 'doughnut',
        data: { labels: kelasLabels, datasets: [{ data: kelasData, backgroundColor: ['#264653','#2a9d8f','#e9c46a','#f4a261','#e76f51','#2d6a4f','#52b788','#95d5b2'] }] },
        options: { responsive: true, plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 8, font: { size: 11 } } } } }
    });
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
