<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helper.php';

// Jika sudah login, arahkan ke dashboard sesuai role
if (isLoggedIn()) {
    $tujuan = match ($_SESSION['role']) {
        'admin' => '/pengaduan_masyarakat/admin/dashboard.php',
        'petugas' => '/pengaduan_masyarakat/petugas/dashboard.php',
        default => '/pengaduan_masyarakat/masyarakat/dashboard.php',
    };
    header('Location: ' . $tujuan);
    exit;
}

$totalPengaduan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM pengaduan"))['c'];
$totalSelesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM pengaduan WHERE status='selesai'"))['c'];
$totalWarga = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM user WHERE role='masyarakat'"))['c'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIGAP Warga — Aplikasi Pengaduan Masyarakat</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="landing-nav">
    <div class="brand"><div class="brand-mark">SW</div> SIGAP Warga</div>
    <div class="nav-links">
        <a href="#fitur">Fitur</a>
        <a href="#alur">Cara Kerja</a>
        <a href="#tentang">Tentang</a>
    </div>
    <div class="nav-cta">
        <a href="auth/login.php" class="btn btn-outline btn-sm">Masuk</a>
        <a href="auth/register.php" class="btn btn-primary btn-sm">Daftar</a>
    </div>
</nav>

<section class="hero">
    <div>
        <div class="eyebrow"><i class="bi bi-shield-check"></i> Layanan Pengaduan Resmi Warga</div>
        <h1>Sampaikan aduan, <span class="accent">pantau prosesnya</span> hingga tuntas.</h1>
        <p class="lead">SIGAP Warga menghubungkan masyarakat, admin, dan petugas lapangan dalam satu alur kerja yang transparan — dari laporan masuk hingga tindak lanjut selesai.</p>
        <div class="hero-cta">
            <a href="auth/register.php" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Buat Pengaduan</a>
            <a href="auth/login.php" class="btn btn-outline"><i class="bi bi-box-arrow-in-right"></i> Masuk ke Akun</a>
        </div>
    </div>
    <div class="hero-panel">
        <div class="hp-title">Statistik Layanan</div>
        <div class="hp-row"><span><i class="bi bi-inboxes"></i> &nbsp;Total Pengaduan</span><strong><?= $totalPengaduan ?></strong></div>
        <div class="hp-row"><span><i class="bi bi-check-circle"></i> &nbsp;Telah Diselesaikan</span><strong><?= $totalSelesai ?></strong></div>
        <div class="hp-row"><span><i class="bi bi-people"></i> &nbsp;Warga Terdaftar</span><strong><?= $totalWarga ?></strong></div>
        <div class="hp-row"><span><i class="bi bi-lock"></i> &nbsp;Keamanan Data</span><strong>Terenkripsi</strong></div>
    </div>
</section>

<section class="section" id="fitur">
    <div class="section-head">
        <div class="eyebrow"><i class="bi bi-stars"></i> Fitur Utama</div>
        <h2>Satu platform, tiga peran pengguna</h2>
        <p>Dirancang agar masyarakat mudah melapor, admin mudah memverifikasi, dan petugas mudah menindaklanjuti.</p>
    </div>
    <div class="feature-grid">
        <div class="feature-card"><div class="f-icon"><i class="bi bi-pencil-square"></i></div><h3>Buat &amp; Lacak Pengaduan</h3><p>Warga dapat membuat pengaduan lengkap dengan kategori, lokasi, dan bukti foto, lalu memantau statusnya real-time.</p></div>
        <div class="feature-card"><div class="f-icon"><i class="bi bi-check2-square"></i></div><h3>Verifikasi oleh Admin</h3><p>Setiap pengaduan diverifikasi lebih dulu sebelum diteruskan ke petugas terkait, menjaga kualitas laporan.</p></div>
        <div class="feature-card"><div class="f-icon"><i class="bi bi-tools"></i></div><h3>Tindak Lanjut Petugas</h3><p>Petugas mengelola daftar tugas, memberi tanggapan, mengunggah bukti penyelesaian, dan memperbarui status.</p></div>
        <div class="feature-card"><div class="f-icon"><i class="bi bi-bell"></i></div><h3>Notifikasi Status</h3><p>Warga menerima notifikasi setiap kali status pengaduan berubah — menunggu, diproses, selesai, atau ditolak.</p></div>
        <div class="feature-card"><div class="f-icon"><i class="bi bi-bar-chart-line"></i></div><h3>Grafik &amp; Laporan</h3><p>Admin dapat melihat statistik pengaduan per kategori dan status, serta mencetak laporan dalam format PDF.</p></div>
        <div class="feature-card"><div class="f-icon"><i class="bi bi-shield-lock"></i></div><h3>Keamanan Data</h3><p>Autentikasi berbasis sesi dan validasi login menjaga data pengguna dan pengaduan tetap aman.</p></div>
    </div>
</section>

<section class="section" id="alur" style="background:var(--white); border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
    <div class="section-head">
        <div class="eyebrow"><i class="bi bi-diagram-3"></i> Cara Kerja</div>
        <h2>Alur pengaduan dalam empat langkah</h2>
        <p>Transparan dari awal laporan dibuat hingga dinyatakan selesai ditangani.</p>
    </div>
    <div class="flow-steps">
        <div class="flow-step"><div class="step-num">01</div><h4>Warga Melapor</h4><p>Registrasi, login, lalu isi form pengaduan lengkap dengan bukti foto dan lokasi kejadian.</p></div>
        <div class="flow-step"><div class="step-num">02</div><h4>Verifikasi Admin</h4><p>Admin meninjau kelengkapan laporan, lalu menyetujui dan meneruskannya ke petugas terkait.</p></div>
        <div class="flow-step"><div class="step-num">03</div><h4>Ditindaklanjuti Petugas</h4><p>Petugas menindaklanjuti di lapangan, memberi tanggapan, dan mengunggah bukti penanganan.</p></div>
        <div class="flow-step"><div class="step-num">04</div><h4>Pengaduan Selesai</h4><p>Status diperbarui menjadi selesai, dan warga dapat melihat hasil akhir beserta riwayatnya.</p></div>
    </div>
</section>

<section class="cta-band" id="tentang">
    <h2>Siap menyampaikan aduan Anda?</h2>
    <p>Bergabunglah bersama warga lain untuk lingkungan yang lebih tertangani dan transparan.</p>
    <a href="auth/register.php" class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> Daftar Sekarang</a>
</section>

<footer class="landing-footer">
    © <?= date('Y') ?> SIGAP Warga — Aplikasi Pengaduan Masyarakat Berbasis Web. Dibangun dengan Native HTML, CSS &amp; PHP.
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>
