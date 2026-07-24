<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

$judul_halaman = 'Buat Pengaduan';
$subjudul_halaman = 'Sampaikan keluhan atau laporan Anda';
$halaman_aktif = 'tambah';

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>
            <div class="card">
                <div class="card-header"><h2><i class="bi bi-pencil-square"></i> Form Pengaduan Baru</h2></div>
                <div class="card-body">
                    <form action="../proses/simpan_pengaduan.php" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="judul">Judul Pengaduan</label>
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Ringkasan singkat masalah" required>
                            </div>
                            <div class="form-group">
                                <label for="id_kategori">Kategori</label>
                                <select class="form-control" id="id_kategori" name="id_kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                                        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="isi_pengaduan">Detail Pengaduan</label>
                            <textarea class="form-control" id="isi_pengaduan" name="isi_pengaduan" rows="5" placeholder="Jelaskan kronologi dan detail masalah Anda selengkap mungkin" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="lokasi">Lokasi Kejadian</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Contoh: Jl. Merdeka No. 10, RT 03/RW 05, Kel. Sukamaju">
                            <div class="form-hint"><i class="bi bi-geo-alt"></i> Semakin detail lokasi, semakin cepat petugas menindaklanjuti.</div>
                        </div>

                        <div class="form-group">
                            <label for="bukti">Upload Foto / Bukti Pendukung</label>
                            <input type="file" class="form-control" id="bukti" name="bukti" accept=".jpg,.jpeg,.png,.pdf" data-preview="#preview-img">
                            <div class="form-hint">Format JPG, PNG, atau PDF. Maksimal 3MB.</div>
                            <img id="preview-img" style="display:none; margin-top:.8rem; max-width:220px; border-radius:10px; border:1px solid var(--border);">
                        </div>

                        <div style="display:flex; gap:1rem; margin-top:1.5rem;">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i> Kirim Pengaduan</button>
                            <a href="dashboard.php" class="btn btn-outline">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
