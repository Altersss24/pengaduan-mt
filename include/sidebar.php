<?php
// Membutuhkan: session sudah start, $_SESSION['nama'], $_SESSION['role']
// $halaman_aktif diset di masing-masing halaman untuk menandai menu aktif
$role = $_SESSION['role'] ?? 'masyarakat';
$base = '/pengaduan_masyarakat';
$inisial = strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1));
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-mark">SW</div>
        <div class="brand-text">SIGAP Warga
            <small>Pengaduan Masyarakat</small>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="avatar"><?= $inisial ?></div>
        <div>
            <div class="u-name"><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></div>
            <div class="u-role"><?= htmlspecialchars($role) ?></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($role === 'masyarakat'): ?>
            <div class="nav-label">Menu Utama</div>
            <a href="<?= $base ?>/masyarakat/dashboard.php" class="<?= $halaman_aktif === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
            <a href="<?= $base ?>/masyarakat/tambah_pengaduan.php" class="<?= $halaman_aktif === 'tambah' ? 'active' : '' ?>"><i class="bi bi-pencil-square"></i> Buat Pengaduan</a>
            <a href="<?= $base ?>/masyarakat/pengaduan.php" class="<?= $halaman_aktif === 'pengaduan' ? 'active' : '' ?>"><i class="bi bi-card-checklist"></i> Status Pengaduan</a>
            <a href="<?= $base ?>/masyarakat/riwayat.php" class="<?= $halaman_aktif === 'riwayat' ? 'active' : '' ?>"><i class="bi bi-clock-history"></i> Riwayat</a>
            <div class="nav-label">Akun</div>
            <a href="<?= $base ?>/masyarakat/profil.php" class="<?= $halaman_aktif === 'profil' ? 'active' : '' ?>"><i class="bi bi-person-circle"></i> Profil Saya</a>

        <?php elseif ($role === 'admin'): ?>
            <div class="nav-label">Menu Utama</div>
            <a href="<?= $base ?>/admin/dashboard.php" class="<?= $halaman_aktif === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
            <a href="<?= $base ?>/admin/pengaduan.php" class="<?= $halaman_aktif === 'pengaduan' ? 'active' : '' ?>"><i class="bi bi-card-checklist"></i> Kelola Pengaduan</a>
            <div class="nav-label">Master Data</div>
            <a href="<?= $base ?>/admin/kategori.php" class="<?= $halaman_aktif === 'kategori' ? 'active' : '' ?>"><i class="bi bi-folder2-open"></i> Kelola Kategori</a>
            <a href="<?= $base ?>/admin/masyarakat.php" class="<?= $halaman_aktif === 'pengguna' ? 'active' : '' ?>"><i class="bi bi-people-fill"></i> Kelola Pengguna</a>
            <div class="nav-label">Laporan</div>
            <a href="<?= $base ?>/admin/laporan.php" class="<?= $halaman_aktif === 'laporan' ? 'active' : '' ?>"><i class="bi bi-bar-chart-line-fill"></i> Grafik &amp; Laporan</a>

        <?php elseif ($role === 'petugas'): ?>
            <div class="nav-label">Menu Utama</div>
            <a href="<?= $base ?>/petugas/dashboard.php" class="<?= $halaman_aktif === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
            <a href="<?= $base ?>/petugas/pengaduan.php" class="<?= $halaman_aktif === 'pengaduan' ? 'active' : '' ?>"><i class="bi bi-card-checklist"></i> Daftar Pengaduan</a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= $base ?>/auth/logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</aside>
