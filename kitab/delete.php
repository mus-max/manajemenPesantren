<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$id=$_GET['id']??null;if($id){try{$pdo->prepare("DELETE FROM kitab WHERE id_kitab=?")->execute([$id]);setAlert('success','Berhasil.');}catch(PDOException $e){setAlert('danger','Gagal: masih ada jadwal terkait.');}}
redirect(BASE_URL.'/kitab/');
?>