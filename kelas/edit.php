<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Edit Kelas'; $id=$_GET['id']??''; if(!$id) redirect(BASE_URL.'/kelas/');
$stmt=$pdo->prepare("SELECT * FROM kelas WHERE id_kelas=?");$stmt->execute([$id]);$k=$stmt->fetch();
if(!$k){setAlert('danger','Tidak ditemukan.');redirect(BASE_URL.'/kelas/');}
$ustadz=$pdo->query("SELECT id_ustadz,nama_ustadz FROM ustadz ORDER BY nama_ustadz")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$nama=trim($_POST['nama_kelas']??'');$t=trim($_POST['tingkat_pendidikan']??'');$s=trim($_POST['sub_tingkatan']??'');$w=$_POST['wali_ustadz_id']??null;
if(empty($nama)||empty($w)){$error='Field wajib harus diisi.';}
else{$pdo->prepare("UPDATE kelas SET nama_kelas=?,tingkat_pendidikan=?,sub_tingkatan=?,wali_ustadz_id=? WHERE id_kelas=?")->execute([$nama,$t?:null,$s?:null,$w,$id]);setAlert('success','Berhasil diperbarui.');redirect(BASE_URL.'/kelas/');}}
$p=$k;$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-0">Edit Kelas: <?=sanitize($k['id_kelas'])?></h4></div><a href="<?= BASE_URL ?>/kelas/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
    <div class="col-md-3"><label class="form-label fw-semibold">ID Kelas</label><input type="text" class="form-control" value="<?=sanitize($p['id_kelas'])?>" disabled></div>
    <div class="col-md-3"><label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label><input type="text" name="nama_kelas" class="form-control" required value="<?=sanitize($p['nama_kelas'])?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Tingkat</label><input type="text" name="tingkat_pendidikan" class="form-control" value="<?=sanitize($p['tingkat_pendidikan']??'')?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Sub Tingkatan</label><input type="text" name="sub_tingkatan" class="form-control" value="<?=sanitize($p['sub_tingkatan']??'')?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Wali Ustadz</label><select name="wali_ustadz_id" class="form-select" required><option value="">-- Pilih --</option><?php foreach($ustadz as $u):?><option value="<?=$u['id_ustadz']?>" <?=$p['wali_ustadz_id']==$u['id_ustadz']?'selected':''?>><?=sanitize($u['nama_ustadz'])?></option><?php endforeach;?></select></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Update</button><a href="<?= BASE_URL ?>/kelas/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>