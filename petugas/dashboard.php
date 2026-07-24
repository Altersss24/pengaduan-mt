<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('petugas');

$id_user = $_SESSION['id_user'];
$judul_halaman = 'Dashboard Petugas';
$subjudul_halaman = 'Ringkasan pengaduan yang menjadi tanggung jawab Anda';
$halaman_aktif = 'dashboard';

$stat = ['diproses' => 0, 'selesai' => 0];
$q = mysqli_query($koneksi, "SELECT status, COUNT(*) jml FROM pengaduan WHERE id_petugas = $id_user GROUP BY status");
while ($r = mysqli_fetch_assoc($q)) { $stat[$r['status']] = (int)$r['jml']; }
$total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM pengaduan WHERE id_petugas = $id_user"))['c'];

$tugas = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori, u.nama AS pelapor FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    JOIN user u ON u.id_user = p.id_user
    WHERE p.id_petugas = $id_user AND p.status = 'diproses'
    ORDER BY p.tgl_pengaduan ASC LIMIT 6");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>

            <div class="stat-grid">
                <div class="stat-card"><div class="stat-label">Total Ditugaskan</div><div class="stat-value"><?= $total ?></div><i class="bi bi-clipboard-data stat-icon"></i></div>
                <div class="stat-card c-diproses"><div class="stat-label">Sedang Diproses</div><div class="stat-value"><?= $stat['diproses'] ?></div><i class="bi bi-arrow-repeat stat-icon"></i></div>
                <div class="stat-card c-selesai"><div class="stat-label">Selesai Ditangani</div><div class="stat-value"><?= $stat['selesai'] ?></div><i class="bi bi-check-circle stat-icon"></i></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-tools"></i> Perlu Ditindaklanjuti</h2>
                    <a href="pengaduan.php" class="btn btn-outline btn-sm">Lihat Semua</a>
                </div>
                <div class="card-body" style="padding:0;">
                    <?php if (mysqli_num_rows($tugas) === 0): ?>
                        <div class="empty-state"><i class="bi bi-check2-circle"></i>Tidak ada pengaduan yang perlu ditindaklanjuti saat ini.</div>
                    <?php else: ?>
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Tanggal</th><th></th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($tugas)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['pelapor']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= formatTanggal($row['tgl_pengaduan']) ?></td>
                                <td><a href="tindak_lanjut.php?id=<?= $row['id_pengaduan'] ?>" class="btn btn-primary btn-sm">Tindak Lanjut</a></td>
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
