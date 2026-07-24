<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$id = (int)($_GET['id'] ?? 0);

$cekPakai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM pengaduan WHERE id_kategori = $id"));
if ($cekPakai['c'] > 0) {
    redirect('../admin/kategori.php', 'Kategori tidak dapat dihapus karena masih digunakan oleh pengaduan.', 'danger');
}

$stmt = mysqli_prepare($koneksi, "DELETE FROM kategori WHERE id_kategori = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

catatLog($_SESSION['id_user'], "Menghapus kategori #$id");
redirect('../admin/kategori.php', 'Kategori berhasil dihapus.');
