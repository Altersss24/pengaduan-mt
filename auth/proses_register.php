<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('register.php');
}

$nama     = bersihkan($_POST['nama']);
$username = bersihkan($_POST['username']);
$email    = bersihkan($_POST['email']);
$no_hp    = bersihkan($_POST['no_hp']);
$alamat   = bersihkan($_POST['alamat']);
$password = $_POST['password'];

if (strlen($password) < 6) {
    redirect('register.php', 'Password minimal 6 karakter.', 'danger');
}

// Cek duplikasi username/email
$cek = mysqli_prepare($koneksi, "SELECT id_user FROM user WHERE username = ? OR email = ?");
mysqli_stmt_bind_param($cek, 'ss', $username, $email);
mysqli_stmt_execute($cek);
if (mysqli_stmt_get_result($cek)->num_rows > 0) {
    redirect('register.php', 'Username atau email sudah terdaftar.', 'danger');
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($koneksi, "INSERT INTO user (nama, username, password, email, no_hp, alamat, role) VALUES (?, ?, ?, ?, ?, ?, 'masyarakat')");
mysqli_stmt_bind_param($stmt, 'ssssss', $nama, $username, $hash, $email, $no_hp, $alamat);

if (mysqli_stmt_execute($stmt)) {
    redirect('login.php', 'Registrasi berhasil! Silakan masuk dengan akun Anda.');
} else {
    redirect('register.php', 'Registrasi gagal, silakan coba lagi.', 'danger');
}
