<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Tambah Kitab';$ustadz=$pdo->query("SELECT id_ustadz,nama_ustadz FROM ustadz ORDER BY nama_ustadz")->fetchAll();$kelas=$pdo->query("SELECT id_kelas,nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$nama=trim($_POST['nama_kitab']??'');$uid=$_POST['ustadz_id']??null;$kid=$_POST['kelas_id']??null;$kat=trim($_POST['kategori_kitab']??'');
if(empty($nama)||empty($uid)){$error='Field wajib harus diisi.';}else{$userId=intval($_SESSION['user_id']??0);if($userId<1){setAlert('danger','Session expired. Silakan login ulang.');redirect(BASE_URL.'/auth/logout.php');}$pdo->prepare("INSERT INTO kitab(nama_kitab,ustadz_id,kelas_id,kategori_kitab,id_user) VALUES(?,?,?,?,?)")->execute([$nama,$uid,$kid?:null,$kat?:null,$userId]);setAlert('success','Berhasil.');redirect(BASE_URL.'/kitab/');}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-0">Tambah Kitab</h4></div><a href="<?= BASE_URL ?>/kitab/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
<div class="col-md-4"><label class="form-label fw-semibold">Nama Kitab <span class="text-danger">*</span></label><input type="text" name="nama_kitab" class="form-control" required value="<?=sanitize($_POST['nama_kitab']??'')?>"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Kategori</label><input type="text" name="kategori_kitab" class="form-control" value="<?=sanitize($_POST['kategori_kitab']??'')?>"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Ustadz <span class="text-danger">*</span></label><select name="ustadz_id" class="form-select" required><option value="">-- Pilih --</option><?php foreach($ustadz as $u):?><option value="<?=$u['id_ustadz']?>"><?=sanitize($u['nama_ustadz'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label class="form-label fw-semibold">Kelas</label><select name="kelas_id" class="form-select"><option value="">-- Semua --</option><?php foreach($kelas as $k):?><option value="<?=$k['id_kelas']?>"><?=sanitize($k['nama_kelas'])?></option><?php endforeach;?></select></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="<?= BASE_URL ?>/kitab/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>