<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id === (int)$_SESSION['id_user']) {
    redirect('../admin/masyarakat.php', 'Anda tidak dapat menghapus akun sendiri.', 'danger');
}

$stmt = mysqli_prepare($koneksi, "DELETE FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

catatLog($_SESSION['id_user'], "Menghapus pengguna #$id");
redirect('../admin/masyarakat.php', 'Pengguna berhasil dihapus.');
