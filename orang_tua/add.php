<?php
require_once __DIR__ . '/../auth/auth_check.php';
setAlert('info', 'Data orang tua sekarang dikelola langsung dari halaman Santri. Silakan tambah data dari menu Santri.');
redirect(BASE_URL . '/santri/add.php');
