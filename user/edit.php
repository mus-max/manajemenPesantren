<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Edit User';
$uid=$_GET['id']??null;
if(!isAdmin() && $uid != $_SESSION['user_id']){setAlert('danger','Akses ditolak.');redirect(BASE_URL.'/dashboard/');}
$stmt=$pdo->prepare("SELECT * FROM user WHERE id_user=?");$stmt->execute([$uid]);$u=$stmt->fetch();
if(!$u){setAlert('danger','Tidak ditemukan.');redirect(BASE_URL.'/user/');}
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$un=trim($_POST['username']??'');$em=trim($_POST['email']??'');$pw=$_POST['password']??'';$r=isAdmin()?($_POST['role']??$u['role']):$u['role'];$s=isAdmin()?(isset($_POST['status'])?1:0):$u['status'];
if(empty($un)||empty($em)){$error='Username dan email wajib diisi.';}
else{$hashed=!empty($pw)?password_hash($pw,PASSWORD_DEFAULT):$u['password'];$pdo->prepare("UPDATE user SET username=?,email=?,password=?,role=?,status=? WHERE id_user=?")->execute([$un,$em,$hashed,$r,$s,$uid]);
if($uid==$_SESSION['user_id']){$_SESSION['username']=$un;$_SESSION['email']=$em;$_SESSION['role']=$r;}
setAlert('success','Berhasil.');redirect(BASE_URL.'/user/');}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0">Edit User: <?=sanitize($u['username'])?></h4></div><a href="<?= BASE_URL ?>/user/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
<div class="col-md-4"><label class="form-label fw-semibold">Username</label><input type="text" name="username" class="form-control" required value="<?=sanitize($u['username'])?>"></div>
<div class="col-md-4"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" required value="<?=sanitize($u['email'])?>"></div>
<div class="col-md-4"><label class="form-label fw-semibold">Password Baru</label><input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Role</label><select name="role" class="form-select"><option value="Operator" <?=$u['role']==='Operator'?'selected':''?>>Operator</option><option value="Admin" <?=$u['role']==='Admin'?'selected':''?>>Admin</option></select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Status</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="status" value="1" <?=$u['status']==1?'checked':''?>><label class="form-check-label">Aktif</label></div></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-warning px-4"><i class="bi bi-check-lg me-1"></i>Update</button><a href="<?= BASE_URL ?>/user/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>