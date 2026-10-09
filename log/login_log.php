<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Log Login';
$logs=$pdo->query("SELECT * FROM log_login ORDER BY waktu_login DESC LIMIT 100")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>Log Login</h4></div><a href="<?= BASE_URL ?>/log/santri_log.php" class="btn btn-outline-primary btn-sm">Log Perubahan Santri <i class="bi bi-arrow-right"></i></a></div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom"><?=sanitize($alert['message'])?></div><?php endif;?>
<div class="table-container"><div class="table-responsive"><table class="table table-hover data-table"><thead class="table-primary"><tr><th>No</th><th>Email</th><th>Waktu</th><th>Status</th></tr></thead><tbody>
<?php $no=1;foreach($logs as $l):?><tr><td><?=$no++?></td><td><?=sanitize($l['email'])?></td><td><small><?=date('d M Y H:i:s',strtotime($l['waktu_login']))?></small></td><td><?=$l['status_login']==='Sukses'?'<span class="badge bg-success badge-status">Sukses</span>':'<span class="badge bg-danger badge-status">Gagal</span>'?></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>