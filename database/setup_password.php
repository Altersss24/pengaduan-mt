<?php
/**
 * Jalankan file ini SEKALI lewat browser setelah import database, misalnya:
 * http://localhost/pengaduan_masyarakat/database/setup_password.php
 *
 * File ini mengatur password akun contoh (admin, petugas1, siti) menjadi
 * "password123" dengan hash yang valid (password_hash), lalu sebaiknya
 * dihapus atau diberi izin akses agar tidak dijalankan ulang oleh orang lain.
 */
require_once __DIR__ . '/../config/koneksi.php';

$passwordBaru = 'password123';
$hash = password_hash($passwordBaru, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($koneksi, "UPDATE user SET password = ? WHERE username IN ('admin','petugas1','siti')");
mysqli_stmt_bind_param($stmt, 's', $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "<h2 style='font-family:sans-serif;color:#16376B;'>Berhasil!</h2>";
    echo "<p style='font-family:sans-serif;'>Password akun <b>admin</b>, <b>petugas1</b>, dan <b>siti</b> telah diatur menjadi: <code>$passwordBaru</code></p>";
    echo "<p style='font-family:sans-serif;'>Silakan hapus file ini setelah digunakan, lalu <a href='../auth/login.php'>masuk ke aplikasi</a>.</p>";
} else {
    echo "Gagal memperbarui password: " . mysqli_error($koneksi);
}
