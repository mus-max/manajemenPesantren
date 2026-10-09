<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT id_santri FROM santri WHERE id_orang_tua = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        redirect(BASE_URL . '/santri/edit.php?id=' . $row['id_santri']);
    }
}
setAlert('info', 'Data orang tua sekarang dikelola langsung dari halaman Santri.');
redirect(BASE_URL . '/santri/');
