<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM orang_tua WHERE id_orang_tua = ?");
        $stmt->execute([$id]);
        setAlert('success', 'Data orang tua berhasil dihapus.');
    } catch (PDOException $e) {
        setAlert('danger', 'Gagal menghapus: masih ada santri terkait.');
    }
}
redirect(BASE_URL . '/orang_tua/');
?>