<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
if (isLoggedIn()) {
    redirect('/pengaduan_masyarakat/index.php');
}
$judul_halaman = 'Masuk';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — SIGAP Warga</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="/pengaduan_masyarakat/assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-visual">
        <div class="brand"><div class="brand-mark">SW</div> SIGAP Warga</div>
        <div>
            <h2>Satu pintu untuk setiap suara warga tersampaikan.</h2>
            <p>Sampaikan pengaduan, pantau prosesnya secara transparan, dan dapatkan tanggapan langsung dari petugas terkait — semua dalam satu platform.</p>
        </div>
        <div class="quote">© <?= date('Y') ?> SIGAP Warga · Aplikasi Pengaduan Masyarakat</div>
    </div>
    <div class="auth-form-side">
        <div class="auth-form">
            <h1>Selamat Datang Kembali</h1>
            <p class="sub">Masuk untuk melanjutkan ke akun Anda.</p>

            <?php tampilkanFlash(); ?>

            <form action="proses_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
            </form>

            <div class="switch-link">
                Belum punya akun? <a href="register.php">Daftar di sini</a><br>
                <a href="../index.php"><i class="bi bi-arrow-left"></i> Kembali ke beranda</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
