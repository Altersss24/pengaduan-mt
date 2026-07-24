<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../admin/kategori.php');
}

$nama = bersihkan($_POST['nama_kategori']);
$deskripsi = bersihkan($_POST['deskripsi'] ?? '');

$stmt = mysqli_prepare($koneksi, "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, 'ss', $nama, $deskripsi);

if (mysqli_stmt_execute($stmt)) {
    catatLog($_SESSION['id_user'], "Menambahkan kategori: $nama");
    redirect('../admin/kategori.php', 'Kategori berhasil ditambahkan.');
} else {
    redirect('../admin/kategori.php', 'Gagal menambahkan kategori.', 'danger');
}
