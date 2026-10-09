<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Laporan';
$countSantri=$pdo->query("SELECT COUNT(*) FROM santri")->fetchColumn();
$countAktif=$pdo->query("SELECT COUNT(*) FROM santri WHERE status=1")->fetchColumn();
$countLaki=$pdo->query("SELECT COUNT(*) FROM santri WHERE jenis_kelamin='Laki-laki'")->fetchColumn();
$countPerempuan=$pdo->query("SELECT COUNT(*) FROM santri WHERE jenis_kelamin='Perempuan'")->fetchColumn();
$perKelas=$pdo->query("SELECT k.nama_kelas, COUNT(s.id_santri) as jumlah FROM kelas k LEFT JOIN santri s ON k.id_kelas=s.kelas_id AND s.status=1 GROUP BY k.id_kelas ORDER BY jumlah DESC")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-bar-graph me-2 text-success"></i>Laporan</h4></div></div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom"><?=sanitize($alert['message'])?></div><?php endif;?>
<div class="row g-3 mb-4">
<div class="col-md-3"><div class="card stat-card shadow-sm text-center p-3"><h3 class="fw-bold text-primary"><?=$countSantri?></h3><small class="text-muted">Total Santri</small></div></div>
<div class="col-md-3"><div class="card stat-card shadow-sm text-center p-3"><h3 class="fw-bold text-success"><?=$countAktif?></h3><small class="text-muted">Santri Aktif</small></div></div>
<div class="col-md-3"><div class="card stat-card shadow-sm text-center p-3"><h3 class="fw-bold" style="color:#15803d"><?=$countLaki?></h3><small class="text-muted">Laki-laki</small></div></div>
<div class="col-md-3"><div class="card stat-card shadow-sm text-center p-3"><h3 class="fw-bold text-danger"><?=$countPerempuan?></h3><small class="text-muted">Perempuan</small></div></div>
</div>
<div class="row g-3">
<div class="col-lg-6"><div class="card shadow-sm" style="border-radius:12px"><div class="card-header bg-white border-bottom"><h6 class="fw-bold mb-0">Santri per Kelas</h6></div><div class="card-body p-0"><table class="table table-hover mb-0"><thead class="table-light"><tr><th>Kelas</th><th class="text-end">Jumlah</th></tr></thead><tbody><?php foreach($perKelas as $pk):?><tr><td><?=sanitize($pk['nama_kelas'])?></td><td class="text-end"><span class="badge bg-primary"><?=$pk['jumlah']?></span></td></tr><?php endforeach;?></tbody></table></div></div></div>
<div class="col-lg-6"><div class="card shadow-sm" style="border-radius:12px"><div class="card-header bg-white border-bottom"><h6 class="fw-bold mb-0">Cetak Laporan</h6></div><div class="card-body"><div class="d-grid gap-2">
<a href="<?= BASE_URL ?>/laporan/santri_pdf.php" class="btn btn-outline-danger btn-lg"><i class="bi bi-file-pdf me-2"></i>Cetak Laporan Santri (PDF)</a>
<a href="<?= BASE_URL ?>/laporan/santri_excel.php" class="btn btn-outline-success btn-lg"><i class="bi bi-file-excel me-2"></i>Export Laporan Santri (Excel)</a>
<a href="<?= BASE_URL ?>/laporan/print.php" class="btn btn-outline-primary btn-lg"><i class="bi bi-printer me-2"></i>Cetak Seluruh Data</a>
</div></div></div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>