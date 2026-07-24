<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../masyarakat/profil.php');
}

$id_user = $_SESSION['id_user'];
$lama = $_POST['password_lama'];
$baru = $_POST['password_baru'];

$stmt = mysqli_prepare($koneksi, "SELECT password FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$row = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!password_verify($lama, $row['password'])) {
    redirect('../masyarakat/profil.php', 'Password lama tidak sesuai.', 'danger');
}
if (strlen($baru) < 6) {
    redirect('../masyarakat/profil.php', 'Password baru minimal 6 karakter.', 'danger');
}

$hash = password_hash($baru, PASSWORD_DEFAULT);
$upd = mysqli_prepare($koneksi, "UPDATE user SET password = ? WHERE id_user = ?");
mysqli_stmt_bind_param($upd, 'si', $hash, $id_user);
mysqli_stmt_execute($upd);

catatLog($id_user, 'Mengubah password akun');
redirect('../masyarakat/profil.php', 'Password berhasil diperbarui.');
