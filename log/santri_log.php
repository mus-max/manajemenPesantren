<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Log Perubahan Santri';
$logs=$pdo->query("SELECT * FROM log_santri ORDER BY waktu_perubahan DESC LIMIT 100")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-warning"></i>Log Perubahan Santri</h4></div><a href="<?= BASE_URL ?>/log/login_log.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-left me-1"></i>Log Login</a></div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom"><?=sanitize($alert['message'])?></div><?php endif;?>
<div class="table-container"><div class="table-responsive"><table class="table table-hover data-table"><thead class="table-warning"><tr><th>No</th><th>Email</th><th>Waktu</th><th>Keterangan</th></tr></thead><tbody>
<?php $no=1;foreach($logs as $l):?><tr><td><?=$no++?></td><td><?=sanitize($l['email'])?></td><td><small><?=date('d M Y H:i:s',strtotime($l['waktu_perubahan']))?></small></td><td><?=sanitize($l['keterangan'])?></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>