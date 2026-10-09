<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
if(!isAdmin()){setAlert('danger','Akses ditolak.');redirect(BASE_URL.'/dashboard/');}
$id=$_GET['id']??null;
if($id && intval($id)!==intval($_SESSION['user_id'])){
    try{
        $pdo->prepare("DELETE FROM user WHERE id_user=?")->execute([$id]);
        setAlert('success','Berhasil dihapus.');
    }catch(PDOException $e){
        setAlert('danger','Gagal menghapus: data mungkin masih digunakan di tabel lain.');
    }
}
redirect(BASE_URL.'/user/');
?>