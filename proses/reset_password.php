<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$id = (int)($_GET['id'] ?? 0);
$hash = password_hash('password123', PASSWORD_DEFAULT);

$stmt = mysqli_prepare($koneksi, "UPDATE user SET password = ? WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'si', $hash, $id);
mysqli_stmt_execute($stmt);

catatLog($_SESSION['id_user'], "Mereset password pengguna #$id");
redirect('../admin/masyarakat.php', 'Password pengguna telah direset menjadi: password123');
