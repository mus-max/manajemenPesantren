<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Data Santri';

$santri = $pdo->query("
    SELECT s.id_santri, s.nis, s.nama_santri, s.jenis_kelamin, s.no_hp, s.status,
           s.tanggal_lahir, s.email, s.kelas_id,
           k.nama_kelas
    FROM santri s
    LEFT JOIN kelas k ON s.kelas_id = k.id_kelas
    ORDER BY s.nama_santri ASC
")->fetchAll();

$alert = getAlert();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Data Santri</h4></div>
    <a href="<?= BASE_URL ?>/santri/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>

<?php if ($alert): ?>
    <div class="alert alert-<?= $alert['type'] ?> alert-custom alert-dismissible fade show">
        <?= sanitize($alert['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover data-table">
            <thead class="table-primary">
                <tr><th>No</th><th>NIS</th><th>Nama Santri</th><th>L/P</th><th>Kelas</th><th>No. HP</th><th>Status</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($santri as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><code><?= sanitize($s['nis'] ?? '-') ?></code></td>
                    <td><?= sanitize($s['nama_santri']) ?><br><small class="text-muted"><?= formatDate($s['tanggal_lahir']) ?></small></td>
                    <td><?= $s['jenis_kelamin'] === 'Laki-laki' ? '<span class="badge bg-dark">L</span>' : '<span class="badge bg-secondary">P</span>' ?></td>
                    <td><?= sanitize($s['nama_kelas'] ?? '-') ?></td>
                    <td><?= sanitize($s['no_hp']) ?></td>
                    <td><?= $s['status'] == 1 ? '<span class="badge bg-success badge-status">Aktif</span>' : '<span class="badge bg-secondary badge-status">Nonaktif</span>' ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/santri/detail.php?id=<?= $s['id_santri'] ?>" class="btn btn-sm btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="<?= BASE_URL ?>/santri/edit.php?id=<?= $s['id_santri'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <a href="<?= BASE_URL ?>/santri/delete.php?id=<?= $s['id_santri'] ?>" class="btn btn-sm btn-outline-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
