<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

$id_user = $_SESSION['id_user'];
$judul_halaman = 'Riwayat Pengaduan';
$subjudul_halaman = 'Pengaduan yang telah selesai atau ditolak';
$halaman_aktif = 'riwayat';

$list = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    WHERE p.id_user = $id_user AND p.status IN ('selesai','ditolak')
    ORDER BY p.tgl_update DESC");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>
            <div class="card">
                <div class="card-header"><h2><i class="bi bi-clock-history"></i> Riwayat Pengaduan</h2></div>
                <div class="card-body" style="padding:0;">
                    <?php if (mysqli_num_rows($list) === 0): ?>
                        <div class="empty-state"><i class="bi bi-archive"></i>Belum ada riwayat pengaduan yang selesai diproses.</div>
                    <?php else: ?>
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Judul</th><th>Kategori</th><th>Tgl Selesai/Update</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($list)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= formatTanggal($row['tgl_update']) ?></td>
                                <td><?= badgeStatus($row['status']) ?></td>
                                <td><a href="detail.php?id=<?= $row['id_pengaduan'] ?>" class="btn btn-outline btn-sm">Detail</a></td>
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
