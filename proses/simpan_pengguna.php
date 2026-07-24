<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../admin/masyarakat.php');
}

$nama = bersihkan($_POST['nama']);
$username = bersihkan($_POST['username']);
$email = bersihkan($_POST['email']);
$password = $_POST['password'];
$role = in_array($_POST['role'], ['admin', 'petugas']) ? $_POST['role'] : 'petugas';

$cek = mysqli_prepare($koneksi, "SELECT id_user FROM user WHERE username=? OR email=?");
mysqli_stmt_bind_param($cek, 'ss', $username, $email);
mysqli_stmt_execute($cek);
if (mysqli_stmt_get_result($cek)->num_rows > 0) {
    redirect('../admin/masyarakat.php', 'Username atau email sudah digunakan.', 'danger');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($koneksi, "INSERT INTO user (nama, username, password, email, role) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sssss', $nama, $username, $hash, $email, $role);

if (mysqli_stmt_execute($stmt)) {
    catatLog($_SESSION['id_user'], "Menambahkan pengguna baru ($role): $nama");
    redirect('../admin/masyarakat.php', 'Pengguna berhasil ditambahkan.');
} else {
    redirect('../admin/masyarakat.php', 'Gagal menambahkan pengguna.', 'danger');
}
