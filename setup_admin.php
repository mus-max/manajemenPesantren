<?php
require_once __DIR__ . '/config/database.php';
$stmt=$pdo->prepare("SELECT COUNT(*) FROM user WHERE email=?");$stmt->execute(['admin@pesantren.com']);
if($stmt->fetchColumn()>0){echo "<h2>Akun admin sudah ada!</h2><p>Email: admin@pesantren.com</p><p>Password: admin123</p><p><a href='".BASE_URL."/auth/login.php'>Login</a></p>";}
else{$hashed=password_hash('admin123',PASSWORD_DEFAULT);$pdo->prepare("INSERT INTO user(username,email,password,role,status) VALUES(?,?,?,?,?)")->execute(['admin','admin@pesantren.com',$hashed,'Admin',1]);echo "<h2>Akun admin berhasil dibuat!</h2><table class='table' style='max-width:400px'><tr><td>Username</td><td><strong>admin</strong></td></tr><tr><td>Email</td><td><strong>admin@pesantren.com</strong></td></tr><tr><td>Password</td><td><strong>admin123</strong></td></tr></table><p><a href='".BASE_URL."/auth/login.php' class='btn' style='background:#1a7a3a;color:white'>Login</a></p><p class='text-danger'>Hapus setup_admin.php setelah berhasil!</p>";}
?>