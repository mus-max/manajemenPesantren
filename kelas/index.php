<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Data Kelas';
$kelas=$pdo->query("SELECT k.*, u.nama_ustadz, COUNT(s.id_santri) as jumlah_santri FROM kelas k LEFT JOIN ustadz u ON k.wali_ustadz_id=u.id_ustadz LEFT JOIN santri s ON k.id_kelas=s.kelas_id AND s.status=1 GROUP BY k.id_kelas ORDER BY k.nama_kelas")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Data Kelas</h4></div>
    <a href="<?= BASE_URL ?>/kelas/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom alert-dismissible fade show"><?=sanitize($alert['message'])?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;?>
<div class="table-container"><div class="table-responsive">
    <table class="table table-hover data-table"><thead class="table-primary">
        <tr><th>No</th><th>ID</th><th>Nama Kelas</th><th>Tingkat</th><th>Wali Ustadz</th><th>Santri</th><th class="text-center">Aksi</th></tr>
    </thead><tbody>
        <?php $no=1;foreach($kelas as $k):?><tr>
            <td><?=$no++?></td><td><code><?=sanitize($k['id_kelas'])?></code></td><td><?=sanitize($k['nama_kelas'])?></td>
            <td><?=sanitize($k['tingkat_pendidikan']??'-')?></td><td><?=sanitize($k['nama_ustadz']??'-')?></td>
            <td><?=$k['jumlah_santri']?></td>
            <td class="text-center"><a href="<?= BASE_URL ?>/kelas/edit.php?id=<?=urlencode($k['id_kelas'])?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a> <a href="<?= BASE_URL ?>/kelas/delete.php?id=<?=urlencode($k['id_kelas'])?>" class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></a></td>
        </tr><?php endforeach;?>
    </tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
