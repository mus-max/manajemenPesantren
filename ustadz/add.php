<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Tambah Ustadz';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verifyCSRF();
    $nama=trim($_POST['nama_ustadz']??''); $alamat=trim($_POST['alamat']??''); $jk=$_POST['jenis_kelamin']??''; $hp=trim($_POST['no_hp']??'');
    if (empty($nama)||empty($alamat)||empty($jk)) { $error='Field wajib harus diisi.'; }
    else { $pdo->prepare("INSERT INTO ustadz(nama_ustadz,alamat,jenis_kelamin,no_hp) VALUES(?,?,?,?)")->execute([$nama,$alamat,$jk,$hp]); setAlert('success','Ustadz berhasil ditambahkan.'); redirect(BASE_URL.'/ustadz/'); }
}
$alert=getAlert(); require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-0">Tambah Ustadz</h4></div><a href="<?= BASE_URL ?>/ustadz/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
    <div class="col-md-6"><label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label><input type="text" name="nama_ustadz" class="form-control" required value="<?=sanitize($_POST['nama_ustadz']??'')?>"></div>
    <div class="col-md-3"><label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label><select name="jenis_kelamin" class="form-select" required><option value="">-- Pilih --</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></div>
    <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="no_hp" class="form-control" maxlength="15" value="<?=sanitize($_POST['no_hp']??'')?>"></div>
    <div class="col-md-12"><label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label><textarea name="alamat" class="form-control" rows="3" required><?=sanitize($_POST['alamat']??'')?></textarea></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="<?= BASE_URL ?>/ustadz/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>