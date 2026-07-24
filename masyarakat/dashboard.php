<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

$id_user = $_SESSION['id_user'];
$judul_halaman = 'Dashboard';
$subjudul_halaman = 'Ringkasan aktivitas pengaduan Anda';
$halaman_aktif = 'dashboard';

// Statistik
$stat = ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0];
$q = mysqli_query($koneksi, "SELECT status, COUNT(*) jml FROM pengaduan WHERE id_user = $id_user GROUP BY status");
while ($r = mysqli_fetch_assoc($q)) { $stat[$r['status']] = (int)$r['jml']; }
$total = array_sum($stat);

// Pengaduan terbaru
$terbaru = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    WHERE p.id_user = $id_user
    ORDER BY p.tgl_pengaduan DESC LIMIT 5");

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
                <div class="stat-card c-menunggu"><div class="stat-label">Menunggu</div><div class="stat-value"><?= $stat['menunggu'] ?></div><i class="bi bi-hourglass-split stat-icon"></i></div>
                <div class="stat-card c-diproses"><div class="stat-label">Diproses</div><div class="stat-value"><?= $stat['diproses'] ?></div><i class="bi bi-arrow-repeat stat-icon"></i></div>
                <div class="stat-card c-selesai"><div class="stat-label">Selesai</div><div class="stat-value"><?= $stat['selesai'] ?></div><i class="bi bi-check-circle stat-icon"></i></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-clock-history"></i> Pengaduan Terbaru</h2>
                    <a href="tambah_pengaduan.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Buat Pengaduan</a>
                </div>
                <div class="card-body" style="padding:0;">
                    <?php if (mysqli_num_rows($terbaru) === 0): ?>
                        <div class="empty-state"><i class="bi bi-inbox"></i>Anda belum membuat pengaduan apa pun.</div>
                    <?php else: ?>
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($terbaru)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= formatTanggal($row['tgl_pengaduan']) ?></td>
                                <td><?= badgeStatus($row['status']) ?></td>
                                <td><a href="detail.php?id=<?= $row['id_pengaduan'] ?>" class="btn btn-outline btn-sm">Lihat</a></td>
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
