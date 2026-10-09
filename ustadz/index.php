<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Data Ustadz';
$ustadz = $pdo->query("SELECT * FROM ustadz ORDER BY nama_ustadz ASC")->fetchAll();
$alert = getAlert();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Data Ustadz</h4></div>
    <a href="<?= BASE_URL ?>/ustadz/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<?php if ($alert): ?><div class="alert alert-<?= $alert['type'] ?> alert-custom alert-dismissible fade show"><?= sanitize($alert['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="table-container"><div class="table-responsive">
    <table class="table table-hover data-table"><thead class="table-primary">
        <tr><th>No</th><th>Nama</th><th>L/P</th><th>Alamat</th><th>No. HP</th><th class="text-center">Aksi</th></tr>
    </thead><tbody>
        <?php $no=1; foreach($ustadz as $u): ?><tr>
            <td><?= $no++ ?></td><td><?= sanitize($u['nama_ustadz']) ?></td>
            <td><?= $u['jenis_kelamin']==='Laki-laki' ? '<span class="badge bg-dark">L</span>' : '<span class="badge bg-secondary">P</span>' ?></td>
            <td><?= sanitize($u['alamat']) ?></td><td><?= sanitize($u['no_hp']) ?></td>
            <td class="text-center">
                <a href="<?= BASE_URL ?>/ustadz/edit.php?id=<?= $u['id_ustadz'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="<?= BASE_URL ?>/ustadz/delete.php?id=<?= $u['id_ustadz'] ?>" class="btn btn-sm btn-outline-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></a>
            </td>
        </tr><?php endforeach; ?>
    </tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
