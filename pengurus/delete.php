<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $pdo->prepare("DELETE FROM pengurus WHERE id_pengurus=?")->execute([$id]);
        setAlert('success', 'Berhasil dihapus.');
    } catch (PDOException $e) {
        setAlert('danger', 'Gagal menghapus: data mungkin masih digunakan di tabel lain.');
    }
}
redirect(BASE_URL . '/pengurus/');
?>