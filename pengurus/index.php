<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Data Pengurus';
$pengurus=$pdo->query("SELECT p.*, u.nama_ustadz FROM pengurus p JOIN ustadz u ON p.id_ustadz=u.id_ustadz ORDER BY p.jabatan")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Data Pengurus</h4></div>
    <a href="<?= BASE_URL ?>/pengurus/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom alert-dismissible fade show"><?=sanitize($alert['message'])?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;?>
<div class="table-container"><div class="table-responsive"><table class="table table-hover data-table"><thead class="table-primary"><tr><th>No</th><th>Nama Ustadz</th><th>Jabatan</th><th class="text-center">Aksi</th></tr></thead><tbody>
<?php $no=1;foreach($pengurus as $p):?><tr><td><?=$no++?></td><td><?=sanitize($p['nama_ustadz'])?></td><td><?=sanitize($p['jabatan'])?></td><td class="text-center"><a href="<?= BASE_URL ?>/pengurus/edit.php?id=<?=$p['id_pengurus']?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a> <a href="<?= BASE_URL ?>/pengurus/delete.php?id=<?=$p['id_pengurus']?>" class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></a></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
