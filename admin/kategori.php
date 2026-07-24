<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$judul_halaman = 'Kelola Kategori';
$subjudul_halaman = 'Atur kategori jenis pengaduan';
$halaman_aktif = 'kategori';

$list = mysqli_query($koneksi, "SELECT k.*, (SELECT COUNT(*) FROM pengaduan p WHERE p.id_kategori = k.id_kategori) AS jml FROM kategori k ORDER BY k.nama_kategori");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-folder-plus"></i> Tambah Kategori</h2></div>
                <div class="card-body">
                    <form action="../proses/simpan_kategori.php" method="POST" style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
                        <div class="form-group" style="flex:1; min-width:200px; margin-bottom:0;">
                            <label>Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" placeholder="Contoh: Infrastruktur" required>
                        </div>
                        <div class="form-group" style="flex:2; min-width:240px; margin-bottom:0;">
                            <label>Deskripsi</label>
                            <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi singkat kategori">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-folder2-open"></i> Daftar Kategori</h2></div>
                <div class="card-body" style="padding:0;">
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Pengaduan</th><th style="width:160px;">Aksi</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($list)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['nama_kategori']) ?></strong></td>
                                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                <td><?= $row['jml'] ?> pengaduan</td>
                                <td>
                                    <a href="../proses/hapus_kategori.php?id=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm" data-confirm="Hapus kategori ini?"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
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
