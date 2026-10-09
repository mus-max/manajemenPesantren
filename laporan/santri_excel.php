<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$santri=$pdo->query("SELECT s.nis,s.nama_santri,s.jenis_kelamin,s.tempat_lahir,s.tanggal_lahir,s.alamat,s.no_hp,s.email,s.wali_santri,k.nama_kelas,s.tanggal_masuk FROM santri s LEFT JOIN kelas k ON s.kelas_id=k.id_kelas WHERE s.status=1 ORDER BY k.nama_kelas,s.nama_santri")->fetchAll();
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="laporan_santri_'.date('Y-m-d').'.xls"');
header('Pragma: no-cache');header('Expires: 0');
?>
<table border="1">
<tr><th colspan="10" style="background:#1a7a3a;color:white;text-align:center;font-size:14px;padding:8px">LAPORAN DATA SANTRI - MANAJEMEN PESANTREN</th></tr>
<tr style="background:#1a7a3a;color:white"><th>No</th><th>NIS</th><th>Nama</th><th>L/P</th><th>Tempat Lahir</th><th>Tgl Lahir</th><th>Alamat</th><th>No. HP</th><th>Email</th><th>Kelas</th></tr>
<?php $no=1;foreach($santri as $s):?><tr><td><?=$no++?></td><td><?=$s['nis']??'-'?></td><td><?=$s['nama_santri']?></td><td><?=$s['jenis_kelamin']==='Laki-laki'?'L':'P'?></td><td><?=$s['tempat_lahir']?></td><td><?=$s['tanggal_lahir']?></td><td><?=$s['alamat']?></td><td><?=$s['no_hp']?></td><td><?=$s['email']??'-'?></td><td><?=$s['nama_kelas']??'-'?></td></tr><?php endforeach;?>
</table><p>Total: <?=count($santri)?> santri aktif</p>
<?php exit;?>