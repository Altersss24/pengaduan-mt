<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$judul_halaman = 'Dashboard Admin';
$subjudul_halaman = 'Ringkasan sistem pengaduan masyarakat';
$halaman_aktif = 'dashboard';

$stat = ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0];
$q = mysqli_query($koneksi, "SELECT status, COUNT(*) jml FROM pengaduan GROUP BY status");
while ($r = mysqli_fetch_assoc($q)) { $stat[$r['status']] = (int)$r['jml']; }
$total = array_sum($stat);
$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM user WHERE role='masyarakat'"))['c'];

$perluVerifikasi = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori, u.nama AS pelapor FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    JOIN user u ON u.id_user = p.id_user
    WHERE p.status = 'menunggu' ORDER BY p.tgl_pengaduan ASC LIMIT 6");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>

            <div class="stat-grid">
                <div class="stat-card"><div class="stat-label">Total Pengaduan</div><div class="stat-value"><?= $total ?></div><i class="bi bi-inboxes stat-icon"></i></div>
                <div class="stat-card c-menunggu"><div class="stat-label">Menunggu Verifikasi</div><div class="stat-value"><?= $stat['menunggu'] ?></div><i class="bi bi-hourglass-split stat-icon"></i></div>
                <div class="stat-card c-diproses"><div class="stat-label">Sedang Diproses</div><div class="stat-value"><?= $stat['diproses'] ?></div><i class="bi bi-arrow-repeat stat-icon"></i></div>
                <div class="stat-card c-selesai"><div class="stat-label">Selesai</div><div class="stat-value"><?= $stat['selesai'] ?></div><i class="bi bi-check-circle stat-icon"></i></div>
                <div class="stat-card"><div class="stat-label">Total Warga Terdaftar</div><div class="stat-value"><?= $total_user ?></div><i class="bi bi-people stat-icon"></i></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-exclamation-diamond"></i> Perlu Verifikasi Segera</h2>
                    <a href="pengaduan.php?status=menunggu" class="btn btn-outline btn-sm">Lihat Semua</a>
                </div>
                <div class="card-body" style="padding:0;">
                    <?php if (mysqli_num_rows($perluVerifikasi) === 0): ?>
                        <div class="empty-state"><i class="bi bi-check2-circle"></i>Tidak ada pengaduan yang menunggu verifikasi.</div>
                    <?php else: ?>
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Tanggal</th><th></th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($perluVerifikasi)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['pelapor']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= formatTanggal($row['tgl_pengaduan']) ?></td>
                                <td><a href="verifikasi.php?id=<?= $row['id_pengaduan'] ?>" class="btn btn-primary btn-sm">Verifikasi</a></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
