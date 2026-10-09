<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Tambah Kelas'; $ustadz=$pdo->query("SELECT id_ustadz,nama_ustadz FROM ustadz ORDER BY nama_ustadz")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$id=trim($_POST['id_kelas']??'');$nama=trim($_POST['nama_kelas']??'');$t=trim($_POST['tingkat_pendidikan']??'');$s=trim($_POST['sub_tingkatan']??'');$w=$_POST['wali_ustadz_id']??null;
if(empty($id)||empty($nama)||empty($w)){$error='Field wajib harus diisi.';}
else{try{$userId=intval($_SESSION['user_id']??0);if($userId<1){setAlert('danger','Session expired. Silakan login ulang.');redirect(BASE_URL.'/auth/logout.php');}$pdo->prepare("INSERT INTO kelas(id_kelas,nama_kelas,tingkat_pendidikan,sub_tingkatan,wali_ustadz_id,id_user) VALUES(?,?,?,?,?,?)")->execute([$id,$nama,$t?:null,$s?:null,$w,$userId]);setAlert('success','Berhasil ditambahkan.');redirect(BASE_URL.'/kelas/');}catch(PDOException $e){$error='ID atau Nama Kelas sudah ada.';}}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-0">Tambah Kelas</h4></div><a href="<?= BASE_URL ?>/kelas/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
    <div class="col-md-3"><label class="form-label fw-semibold">ID Kelas <span class="text-danger">*</span></label><input type="text" name="id_kelas" class="form-control" placeholder="Contoh: VII-A" required value="<?=sanitize($_POST['id_kelas']??'')?>"></div>
    <div class="col-md-3"><label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label><input type="text" name="nama_kelas" class="form-control" required value="<?=sanitize($_POST['nama_kelas']??'')?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Tingkat</label><input type="text" name="tingkat_pendidikan" class="form-control" value="<?=sanitize($_POST['tingkat_pendidikan']??'')?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Sub Tingkatan</label><input type="text" name="sub_tingkatan" class="form-control" value="<?=sanitize($_POST['sub_tingkatan']??'')?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Wali Ustadz <span class="text-danger">*</span></label><select name="wali_ustadz_id" class="form-select" required><option value="">-- Pilih --</option><?php foreach($ustadz as $u):?><option value="<?=$u['id_ustadz']?>"><?=sanitize($u['nama_ustadz'])?></option><?php endforeach;?></select></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="<?= BASE_URL ?>/kelas/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>