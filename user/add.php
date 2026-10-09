<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Tambah User';
if(!isAdmin()){setAlert('danger','Akses ditolak.');redirect(BASE_URL.'/dashboard/');}
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$u=trim($_POST['username']??'');$e=trim($_POST['email']??'');$p=$_POST['password']??'';$r=$_POST['role']??'Operator';$s=isset($_POST['status'])?1:0;
if(empty($u)||empty($e)||empty($p)){$error='Semua field wajib diisi.';}
elseif(strlen($p)<6){$error='Password minimal 6 karakter.';}
else{$stmt=$pdo->prepare("SELECT COUNT(*) FROM user WHERE email=? OR username=?");$stmt->execute([$e,$u]);
if($stmt->fetchColumn()>0){$error='Email atau username sudah ada.';}
else{$hashed=password_hash($p,PASSWORD_DEFAULT);$pdo->prepare("INSERT INTO user(username,email,password,role,status) VALUES(?,?,?,?,?)")->execute([$u,$e,$hashed,$r,$s]);setAlert('success','Berhasil.');redirect(BASE_URL.'/user/');}}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0">Tambah User</h4></div><a href="<?= BASE_URL ?>/user/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
<div class="col-md-4"><label class="form-label fw-semibold">Username <span class="text-danger">*</span></label><input type="text" name="username" class="form-control" required value="<?=sanitize($_POST['username']??'')?>"></div>
<div class="col-md-4"><label class="form-label fw-semibold">Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" required value="<?=sanitize($_POST['email']??'')?>"></div>
<div class="col-md-4"><label class="form-label fw-semibold">Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Role</label><select name="role" class="form-select"><option value="Operator">Operator</option><option value="Admin">Admin</option></select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Status</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="status" value="1" checked><label class="form-check-label">Aktif</label></div></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-dark px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="<?= BASE_URL ?>/user/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>