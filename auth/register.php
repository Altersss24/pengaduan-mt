<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
if (isLoggedIn()) {
    redirect('/pengaduan_masyarakat/index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Akun — SIGAP Warga</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="/pengaduan_masyarakat/assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-visual">
        <div class="brand"><div class="brand-mark">SW</div> SIGAP Warga</div>
        <div>
            <h2>Daftar sekali, sampaikan pengaduan kapan saja.</h2>
            <p>Buat akun masyarakat untuk mulai melaporkan masalah di lingkungan Anda dan memantau tindak lanjutnya secara real-time.</p>
        </div>
        <div class="quote">© <?= date('Y') ?> SIGAP Warga · Aplikasi Pengaduan Masyarakat</div>
    </div>
    <div class="auth-form-side">
        <div class="auth-form">
            <h1>Buat Akun Baru</h1>
            <p class="sub">Khusus untuk warga/masyarakat pelapor.</p>

            <?php tampilkanFlash(); ?>

            <form action="proses_register.php" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama sesuai KTP" required autofocus>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username unik" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="no_hp">Nomor HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Alamat domisili" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-person-plus-fill"></i> Daftar Sekarang</button>
            </form>

            <div class="switch-link">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
