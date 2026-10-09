<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Data Kitab';
$kitab=$pdo->query("SELECT k.*, u.nama_ustadz, kl.nama_kelas FROM kitab k LEFT JOIN ustadz u ON k.ustadz_id=u.id_ustadz LEFT JOIN kelas kl ON k.kelas_id=kl.id_kelas ORDER BY k.nama_kitab")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Data Kitab</h4></div>
    <a href="<?= BASE_URL ?>/kitab/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom alert-dismissible fade show"><?=sanitize($alert['message'])?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;?>
<div class="table-container"><div class="table-responsive"><table class="table table-hover data-table"><thead class="table-primary"><tr><th>No</th><th>Nama Kitab</th><th>Kategori</th><th>Ustadz</th><th>Kelas</th><th class="text-center">Aksi</th></tr></thead><tbody>
<?php $no=1;foreach($kitab as $k):?><tr><td><?=$no++?></td><td><?=sanitize($k['nama_kitab'])?></td><td><?=sanitize($k['kategori_kitab']??'-')?></td><td><?=sanitize($k['nama_ustadz']??'-')?></td><td><?=sanitize($k['nama_kelas']??'-')?></td><td class="text-center"><a href="<?= BASE_URL ?>/kitab/edit.php?id=<?=$k['id_kitab']?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a> <a href="<?= BASE_URL ?>/kitab/delete.php?id=<?=$k['id_kitab']?>" class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></a></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
