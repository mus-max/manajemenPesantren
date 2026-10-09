<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Tambah Jadwal';$kitab=$pdo->query("SELECT id_kitab,nama_kitab FROM kitab ORDER BY nama_kitab")->fetchAll();$ustadz=$pdo->query("SELECT id_ustadz,nama_ustadz FROM ustadz ORDER BY nama_ustadz")->fetchAll();$kelas=$pdo->query("SELECT id_kelas,nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();$hari=['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$h=$_POST['hari']??'';$jm=$_POST['jam_mulai']??'';$js=$_POST['jam_selesai']??'';$kid=$_POST['id_kitab']??null;$uid=$_POST['id_ustadz']??null;$kelid=$_POST['id_kelas']??null;
if(empty($h)||empty($jm)||empty($js)||empty($kid)||empty($uid)||empty($kelid)){$error='Semua field harus diisi.';}else{$pdo->prepare("INSERT INTO jadwal(hari,jam_mulai,jam_selesai,id_kitab,id_ustadz,id_kelas) VALUES(?,?,?,?,?,?)")->execute([$h,$jm,$js,$kid,$uid,$kelid]);setAlert('success','Berhasil.');redirect(BASE_URL.'/jadwal/');}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0">Tambah Jadwal</h4></div><a href="<?= BASE_URL ?>/jadwal/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
<div class="col-md-2"><label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label><select name="hari" class="form-select" required><option value="">-- Pilih --</option><?php foreach($hari as $h):?><option value="<?=$h?>" <?=($_POST['hari']??'')===$h?'selected':''?>><?=$h?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label class="form-label fw-semibold">Jam Mulai</label><input type="time" name="jam_mulai" class="form-control" required value="<?=sanitize($_POST['jam_mulai']??'')?>"></div>
<div class="col-md-2"><label class="form-label fw-semibold">Jam Selesai</label><input type="time" name="jam_selesai" class="form-control" required value="<?=sanitize($_POST['jam_selesai']??'')?>"></div>
<div class="col-md-2"><label class="form-label fw-semibold">Kelas</label><select name="id_kelas" class="form-select" required><option value="">-- Pilih --</option><?php foreach($kelas as $k):?><option value="<?=$k['id_kelas']?>"><?=sanitize($k['nama_kelas'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label class="form-label fw-semibold">Kitab</label><select name="id_kitab" class="form-select" required><option value="">-- Pilih --</option><?php foreach($kitab as $kb):?><option value="<?=$kb['id_kitab']?>"><?=sanitize($kb['nama_kitab'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label class="form-label fw-semibold">Ustadz</label><select name="id_ustadz" class="form-select" required><option value="">-- Pilih --</option><?php foreach($ustadz as $u):?><option value="<?=$u['id_ustadz']?>"><?=sanitize($u['nama_ustadz'])?></option><?php endforeach;?></select></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="<?= BASE_URL ?>/jadwal/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>