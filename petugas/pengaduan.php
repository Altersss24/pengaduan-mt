<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('petugas');

$id_user = $_SESSION['id_user'];
$judul_halaman = 'Daftar Pengaduan';
$subjudul_halaman = 'Pengaduan yang ditugaskan kepada Anda';
$halaman_aktif = 'pengaduan';

$status_filter = $_GET['status'] ?? '';
$where = "WHERE p.id_petugas = $id_user";
if ($status_filter !== '') { $where .= " AND p.status = '" . mysqli_real_escape_string($koneksi, $status_filter) . "'"; }

$list = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori, u.nama AS pelapor FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    JOIN user u ON u.id_user = p.id_user
    $where ORDER BY p.tgl_pengaduan DESC");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>
            <div class="card">
                <div class="card-header"><h2><i class="bi bi-card-checklist"></i> Pengaduan Ditugaskan</h2></div>
                <div class="card-body">
                    <form method="GET" style="margin-bottom:1.3rem;">
                        <select name="status" class="form-control" style="max-width:200px;" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="diproses" <?= $status_filter==='diproses'?'selected':'' ?>>Diproses</option>
                            <option value="selesai" <?= $status_filter==='selesai'?'selected':'' ?>>Selesai</option>
                        </select>
                    </form>

                    <?php if (mysqli_num_rows($list) === 0): ?>
                        <div class="empty-state"><i class="bi bi-inbox"></i>Belum ada pengaduan yang ditugaskan.</div>
                    <?php else: ?>
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($list)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['pelapor']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= formatTanggal($row['tgl_pengaduan']) ?></td>
                                <td><?= badgeStatus($row['status']) ?></td>
                                <td><a href="tindak_lanjut.php?id=<?= $row['id_pengaduan'] ?>" class="btn btn-outline btn-sm">Kelola</a></td>
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
