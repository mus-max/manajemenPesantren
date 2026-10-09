<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$santri=$pdo->query("SELECT s.*, k.nama_kelas FROM santri s LEFT JOIN kelas k ON s.kelas_id=k.id_kelas WHERE s.status=1 ORDER BY k.nama_kelas,s.nama_santri")->fetchAll();
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Laporan Santri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>@media print{.no-print{display:none!important;}body{font-size:11px;}}body{padding:20px;}h2{border-bottom:3px solid #000;padding-bottom:5px;}table{font-size:11px;}</style>
</head><body>
<div class="no-print mb-3 text-center"><button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i>Cetak</button> <a href="<?= BASE_URL ?>/laporan/" class="btn btn-secondary btn-sm">Kembali</a></div>
<h2 class="text-center fw-bold">LAPORAN DATA SANTRI</h2>
<p class="text-center text-muted">Manajemen Pesantren - <?= date('d F Y') ?></p>
<table class="table table-bordered table-striped mt-3"><thead class="table-dark"><tr><th>No</th><th>NIS</th><th>Nama</th><th>L/P</th><th>Tgl Lahir</th><th>Kelas</th><th>No. HP</th><th>Status</th></tr></thead><tbody>
<?php $no=1;foreach($santri as $s):?><tr><td><?=$no++?></td><td><?=sanitize($s['nis']??'-')?></td><td><?=sanitize($s['nama_santri'])?></td><td><?=$s['jenis_kelamin']==='Laki-laki'?'L':'P'?></td><td><?=formatDate($s['tanggal_lahir'])?></td><td><?=sanitize($s['nama_kelas']??'-')?></td><td><?=sanitize($s['no_hp'])?></td><td>Aktif</td></tr><?php endforeach;?>
</tbody></table>
<p class="text-end mt-4">Dicetak: <?= date('d F Y, H:i') ?></p>
</body></html>