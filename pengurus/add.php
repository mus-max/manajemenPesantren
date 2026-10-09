<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Tambah Pengurus';$ustadz=$pdo->query("SELECT id_ustadz,nama_ustadz FROM ustadz ORDER BY nama_ustadz")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$uid=$_POST['id_ustadz']??null;$j=trim($_POST['jabatan']??'');
if(empty($uid)||empty($j)){$error='Semua field harus diisi.';}else{$userId=intval($_SESSION['user_id']??0);if($userId<1){setAlert('danger','Session expired. Silakan login ulang.');redirect(BASE_URL.'/auth/logout.php');}$pdo->prepare("INSERT INTO pengurus(id_ustadz,jabatan,id_user) VALUES(?,?,?)")->execute([$uid,$j,$userId]);setAlert('success','Berhasil.');redirect(BASE_URL.'/pengurus/');}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0">Tambah Pengurus</h4></div><a href="<?= BASE_URL ?>/pengurus/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Ustadz <span class="text-danger">*</span></label><select name="id_ustadz" class="form-select" required><option value="">-- Pilih --</option><?php foreach($ustadz as $u):?><option value="<?=$u['id_ustadz']?>"><?=sanitize($u['nama_ustadz'])?></option><?php endforeach;?></select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label><input type="text" name="jabatan" class="form-control" required placeholder="Contoh: Kepala Pesantren" value="<?=sanitize($_POST['jabatan']??'')?>"></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-danger px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="<?= BASE_URL ?>/pengurus/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>