<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle='Edit Pengurus';$id=$_GET['id']??null;if(!$id) redirect(BASE_URL.'/pengurus/');
$stmt=$pdo->prepare("SELECT * FROM pengurus WHERE id_pengurus=?");$stmt->execute([$id]);$p=$stmt->fetch();
if(!$p){setAlert('danger','Tidak ditemukan.');redirect(BASE_URL.'/pengurus/');}
$ustadz=$pdo->query("SELECT id_ustadz,nama_ustadz FROM ustadz ORDER BY nama_ustadz")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){verifyCSRF();$uid=$_POST['id_ustadz']??null;$j=trim($_POST['jabatan']??'');
if(empty($uid)||empty($j)){$error='Semua field harus diisi.';}else{$pdo->prepare("UPDATE pengurus SET id_ustadz=?,jabatan=? WHERE id_pengurus=?")->execute([$uid,$j,$id]);setAlert('success','Berhasil.');redirect(BASE_URL.'/pengurus/');}}
$alert=getAlert();require_once __DIR__ . '/../includes/header.php';require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-0">Edit Pengurus</h4></div><a href="<?= BASE_URL ?>/pengurus/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-danger alert-custom"><?=sanitize($error)?></div><?php endif;?>
<div class="form-card"><form method="POST"><?= csrfField() ?><div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Ustadz</label><select name="id_ustadz" class="form-select" required><?php foreach($ustadz as $u):?><option value="<?=$u['id_ustadz']?>" <?=$p['id_ustadz']==$u['id_ustadz']?'selected':''?>><?=sanitize($u['nama_ustadz'])?></option><?php endforeach;?></select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Jabatan</label><input type="text" name="jabatan" class="form-control" required value="<?=sanitize($p['jabatan'])?>"></div>
</div><div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-warning px-4"><i class="bi bi-check-lg me-1"></i>Update</button><a href="<?= BASE_URL ?>/pengurus/" class="btn btn-outline-secondary">Batal</a></div></form></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>