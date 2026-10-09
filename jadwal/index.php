<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Data Jadwal';
$jadwal=$pdo->query("SELECT j.*, u.nama_ustadz, k.nama_kitab, kl.nama_kelas FROM jadwal j JOIN ustadz u ON j.id_ustadz=u.id_ustadz JOIN kitab k ON j.id_kitab=k.id_kitab JOIN kelas kl ON j.id_kelas=kl.id_kelas ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'),j.jam_mulai")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Data Jadwal</h4></div>
    <a href="<?= BASE_URL ?>/jadwal/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom alert-dismissible fade show"><?=sanitize($alert['message'])?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;?>
<div class="table-container"><div class="table-responsive"><table class="table table-hover data-table"><thead class="table-primary"><tr><th>No</th><th>Hari</th><th>Jam</th><th>Kelas</th><th>Kitab</th><th>Ustadz</th><th class="text-center">Aksi</th></tr></thead><tbody>
<?php $no=1;foreach($jadwal as $j):?><tr><td><?=$no++?></td><td><?=sanitize($j['hari'])?></td><td><small><?=formatTime($j['jam_mulai'])?> - <?=formatTime($j['jam_selesai'])?></small></td><td><?=sanitize($j['nama_kelas'])?></td><td><?=sanitize($j['nama_kitab'])?></td><td><?=sanitize($j['nama_ustadz'])?></td><td class="text-center"><a href="<?= BASE_URL ?>/jadwal/edit.php?id=<?=$j['id_jadwal']?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a> <a href="<?= BASE_URL ?>/jadwal/delete.php?id=<?=$j['id_jadwal']?>" class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></a></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
