<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$username = bersihkan($_POST['username']);
$password = $_POST['password'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($password, $user['password'])) {
    redirect('login.php', 'Username atau password salah.', 'danger');
}

// Set session
$_SESSION['id_user']  = $user['id_user'];
$_SESSION['nama']     = $user['nama'];
$_SESSION['username'] = $user['username'];
$_SESSION['role']     = $user['role'];
$_SESSION['foto']     = $user['foto'];

catatLog($user['id_user'], 'Login ke sistem');

switch ($user['role']) {
    case 'admin':
        redirect('/pengaduan_masyarakat/admin/dashboard.php', 'Selamat datang kembali, ' . $user['nama'] . '.');
        break;
    case 'petugas':
        redirect('/pengaduan_masyarakat/petugas/dashboard.php', 'Selamat datang kembali, ' . $user['nama'] . '.');
        break;
    default:
        redirect('/pengaduan_masyarakat/masyarakat/dashboard.php', 'Selamat datang kembali, ' . $user['nama'] . '.');
}
