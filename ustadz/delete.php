<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$id=$_GET['id']??null;
if($id){try{$pdo->prepare("DELETE FROM ustadz WHERE id_ustadz=?")->execute([$id]);setAlert('success','Berhasil dihapus.');}catch(PDOException $e){setAlert('danger','Gagal: masih ada data terkait.');}}
redirect(BASE_URL.'/ustadz/');
?>