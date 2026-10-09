<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM santri WHERE id_santri = ?");
        $stmt->execute([$id]);
        setAlert('success', 'Data santri berhasil dihapus.');
    } catch (PDOException $e) {
        setAlert('danger', 'Gagal menghapus: data mungkin masih digunakan di tabel lain.');
    }
}
redirect(BASE_URL . '/santri/');
?>