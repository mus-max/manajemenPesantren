<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$santri=$pdo->query("SELECT s.*, k.nama_kelas FROM santri s LEFT JOIN kelas k ON s.kelas_id=k.id_kelas WHERE s.status=1 ORDER BY k.nama_kelas,s.nama_santri")->fetchAll();
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Laporan Santri PDF</title>
<style>body{font-family:Arial,sans-serif;padding:30px;font-size:12px;}h2{text-align:center;border-bottom:2px solid #333;padding-bottom:8px;}table{width:100%;border-collapse:collapse;margin-top:15px;}th,td{border:1px solid #333;padding:6px 8px;text-align:left;font-size:11px;}th{background:#1a7a3a;color:white;}tr:nth-child(even){background:#f2f2f2;}.btn-print{display:block;text-align:center;margin-bottom:20px;}.btn-print a{padding:8px 20px;background:#1a7a3a;color:white;text-decoration:none;border-radius:5px;font-size:14px;}@media print{.btn-print{display:none;}}</style>
</head><body>
<div class="btn-print"><a href="javascript:window.print()">Cetak / Save as PDF</a> <a href="<?= BASE_URL ?>/laporan/" style="background:#6c757d;margin-left:10px">Kembali</a></div>
<h2>LAPORAN DATA SANTRI AKTIF</h2><h4>Manajemen Pesantren</h4>
<table><thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>L/P</th><th>Tempat, Tgl Lahir</th><th>Kelas</th><th>Alamat</th><th>No. HP</th></tr></thead><tbody>
<?php $no=1;foreach($santri as $s):?><tr><td><?=$no++?></td><td><?=sanitize($s['nis']??'-')?></td><td><?=sanitize($s['nama_santri'])?></td><td><?=$s['jenis_kelamin']==='Laki-laki'?'L':'P'?></td><td><?=sanitize($s['tempat_lahir'])?>, <?=formatDate($s['tanggal_lahir'])?></td><td><?=sanitize($s['nama_kelas']??'-')?></td><td><?=sanitize($s['alamat'])?></td><td><?=sanitize($s['no_hp'])?></td></tr><?php endforeach;?>
</tbody></table>
<p style="text-align:right;margin-top:20px;color:#999">Dicetak: <?=date('d/m/Y H:i')?> | Total: <?=count($santri)?> santri</p>
</body></html>