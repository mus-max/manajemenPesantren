<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='User Management';
if(!isAdmin()){setAlert('danger','Akses ditolak.');redirect(BASE_URL.'/dashboard/');}
$users=$pdo->query("SELECT * FROM user ORDER BY username ASC")->fetchAll();
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">User Management</h4></div>
    <a href="<?= BASE_URL ?>/user/add.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah User</a>
</div>
<?php if($alert):?><div class="alert alert-<?=$alert['type']?> alert-custom alert-dismissible fade show"><?=sanitize($alert['message'])?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;?>
<div class="table-container"><div class="table-responsive"><table class="table table-hover data-table"><thead class="table-primary"><tr><th>No</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th class="text-center">Aksi</th></tr></thead><tbody>
<?php $no=1;foreach($users as $u):?><tr><td><?=$no++?></td><td><?=sanitize($u['username'])?></td><td><?=sanitize($u['email'])?></td><td><?=$u['role']==='Admin'?'<span class="badge bg-dark">Admin</span>':'<span class="badge bg-secondary">Operator</span>'?></td><td><?=$u['status']==1?'<span class="badge bg-success badge-status">Aktif</span>':'<span class="badge bg-secondary badge-status">Nonaktif</span>'?></td><td class="text-center"><a href="<?= BASE_URL ?>/user/edit.php?id=<?=$u['id_user']?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a> <?php if(intval($u['id_user'])!==intval($_SESSION['user_id'])):?><a href="<?= BASE_URL ?>/user/delete.php?id=<?=$u['id_user']?>" class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></a><?php endif;?></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
