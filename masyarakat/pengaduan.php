<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

$id_user = $_SESSION['id_user'];
$judul_halaman = 'Status Pengaduan';
$subjudul_halaman = 'Pantau pengaduan Anda secara real-time';
$halaman_aktif = 'pengaduan';

$status_filter = $_GET['status'] ?? '';
$keyword = bersihkan($_GET['q'] ?? '');

$where = "WHERE p.id_user = $id_user";
if ($status_filter !== '') { $where .= " AND p.status = '" . mysqli_real_escape_string($koneksi, $status_filter) . "'"; }
if ($keyword !== '') { $where .= " AND p.judul LIKE '%$keyword%'"; }

$list = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
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
                <div class="card-header">
                    <h2><i class="bi bi-card-checklist"></i> Daftar Pengaduan Saya</h2>
                    <a href="tambah_pengaduan.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Buat Baru</a>
                </div>
                <div class="card-body">
                    <form method="GET" style="display:flex; gap:.8rem; margin-bottom:1.3rem; flex-wrap:wrap;">
                        <input type="text" name="q" class="form-control" style="max-width:260px;" placeholder="Cari judul pengaduan..." value="<?= htmlspecialchars($keyword) ?>">
                        <select name="status" class="form-control" style="max-width:180px;" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <?php foreach (['menunggu','diproses','selesai','ditolak'] as $s): ?>
                                <option value="<?= $s ?>" <?= $status_filter === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline"><i class="bi bi-search"></i> Cari</button>
                    </form>

                    <?php if (mysqli_num_rows($list) === 0): ?>
                        <div class="empty-state"><i class="bi bi-inbox"></i>Tidak ada pengaduan yang cocok.</div>
                    <?php else: ?>
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Judul</th><th>Kategori</th><th>Lokasi</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($list)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= htmlspecialchars($row['lokasi'] ?: '-') ?></td>
                                <td><?= formatTanggal($row['tgl_pengaduan']) ?></td>
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
