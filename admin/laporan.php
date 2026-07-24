<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$judul_halaman = 'Grafik & Laporan';
$subjudul_halaman = 'Statistik pengaduan berdasarkan kategori dan status';
$halaman_aktif = 'laporan';

$perKategori = mysqli_query($koneksi, "
    SELECT k.nama_kategori, COUNT(p.id_pengaduan) jml
    FROM kategori k LEFT JOIN pengaduan p ON p.id_kategori = k.id_kategori
    GROUP BY k.id_kategori ORDER BY jml DESC");

$stat = ['menunggu'=>0,'diproses'=>0,'selesai'=>0,'ditolak'=>0];
$q = mysqli_query($koneksi, "SELECT status, COUNT(*) jml FROM pengaduan GROUP BY status");
while ($r = mysqli_fetch_assoc($q)) { $stat[$r['status']] = (int)$r['jml']; }
$totalAll = max(array_sum($stat), 1);

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-bar-chart-line"></i> Distribusi Status Pengaduan</h2>
                    <a href="../laporan/pdf.php" target="_blank" class="btn btn-outline btn-sm"><i class="bi bi-filetype-pdf"></i> Cetak Laporan PDF</a>
                </div>
                <div class="card-body">
                    <?php foreach ($stat as $label => $jml): $persen = round($jml / $totalAll * 100); ?>
                    <div style="margin-bottom:1rem;">
                        <div style="display:flex; justify-content:space-between; font-size:.85rem; margin-bottom:.3rem;">
                            <span style="text-transform:capitalize; font-weight:600;"><?= $label ?></span>
                            <span><?= $jml ?> (<?= $persen ?>%)</span>
                        </div>
                        <div style="background:var(--blue-soft); border-radius:999px; height:10px; overflow:hidden;">
                            <div style="width:<?= $persen ?>%; height:100%; background:var(--blue-accent); border-radius:999px;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-pie-chart"></i> Pengaduan per Kategori</h2></div>
                <div class="card-body" style="padding:0;">
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Kategori</th><th>Jumlah Pengaduan</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($perKategori)): ?>
                            <tr><td><?= htmlspecialchars($row['nama_kategori']) ?></td><td><?= $row['jml'] ?></td></tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
