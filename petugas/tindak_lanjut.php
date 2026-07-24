<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('petugas');

$id_user = $_SESSION['id_user'];
$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, k.nama_kategori, u.nama AS pelapor FROM pengaduan p
    JOIN kategori k ON k.id_kategori=p.id_kategori JOIN user u ON u.id_user=p.id_user
    WHERE p.id_pengaduan = ? AND p.id_petugas = ?");
mysqli_stmt_bind_param($stmt, 'ii', $id, $id_user);
mysqli_stmt_execute($stmt);
$pengaduan = mysqli_stmt_get_result($stmt)->fetch_assoc();
if (!$pengaduan) { redirect('pengaduan.php', 'Pengaduan tidak ditemukan atau bukan tanggung jawab Anda.', 'danger'); }

$lampiran = mysqli_query($koneksi, "SELECT * FROM lampiran WHERE id_pengaduan = $id");
$tanggapan = mysqli_query($koneksi, "SELECT t.*, u.nama, u.role FROM tanggapan t JOIN user u ON u.id_user=t.id_user WHERE t.id_pengaduan=$id ORDER BY t.created_at ASC");

$judul_halaman = 'Tindak Lanjut Pengaduan';
$subjudul_halaman = $pengaduan['judul'];
$halaman_aktif = 'pengaduan';

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
                    <h2><?= htmlspecialchars($pengaduan['judul']) ?></h2>
                    <?= badgeStatus($pengaduan['status']) ?>
                </div>
                <div class="card-body">
                    <p style="color:var(--text-muted); font-size:.85rem; margin-bottom:1rem;">
                        <i class="bi bi-person"></i> <?= htmlspecialchars($pengaduan['pelapor']) ?>
                        &nbsp;·&nbsp; <i class="bi bi-folder2"></i> <?= htmlspecialchars($pengaduan['nama_kategori']) ?>
                        <?php if ($pengaduan['lokasi']): ?>&nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($pengaduan['lokasi']) ?><?php endif; ?>
                    </p>
                    <p><?= nl2br(htmlspecialchars($pengaduan['isi_pengaduan'])) ?></p>
                    <?php if (mysqli_num_rows($lampiran) > 0): ?>
                        <div style="display:flex; gap:.8rem; flex-wrap:wrap; margin-top:1rem;">
                            <?php while ($f = mysqli_fetch_assoc($lampiran)): ?>
                                <a href="/pengaduan_masyarakat/assets/img/upload/pengaduan/<?= $f['nama_simpan'] ?>" target="_blank" class="btn btn-outline btn-sm"><i class="bi bi-paperclip"></i> <?= htmlspecialchars($f['nama_file']) ?></a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-chat-left-text"></i> Tanggapan / Jawaban</h2></div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($tanggapan) === 0): ?>
                        <div class="empty-state"><i class="bi bi-chat-square"></i>Belum ada tanggapan.</div>
                    <?php else: ?>
                    <div class="timeline">
                        <?php while ($t = mysqli_fetch_assoc($tanggapan)): ?>
                            <div class="timeline-item">
                                <div class="t-meta"><strong><?= htmlspecialchars($t['nama']) ?></strong> (<?= htmlspecialchars($t['role']) ?>) · <?= formatTanggal($t['created_at']) ?></div>
                                <div class="t-body"><?= nl2br(htmlspecialchars($t['isi_tanggapan'])) ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>

                    <form action="../proses/simpan_tanggapan.php" method="POST" style="margin-top:1.3rem;">
                        <input type="hidden" name="id_pengaduan" value="<?= $id ?>">
                        <input type="hidden" name="status_tanggapan" value="jawaban">
                        <div class="form-group">
                            <label>Tulis Tanggapan / Proses Penanganan</label>
                            <textarea name="isi_tanggapan" class="form-control" rows="3" placeholder="Jelaskan tindakan yang dilakukan..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline"><i class="bi bi-send"></i> Kirim Tanggapan</button>
                    </form>
                </div>
            </div>

            <?php if ($pengaduan['status'] === 'diproses'): ?>
            <div class="card">
                <div class="card-header"><h2><i class="bi bi-flag-fill"></i> Selesaikan Pengaduan</h2></div>
                <div class="card-body">
                    <form action="../proses/ubah_status.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_pengaduan" value="<?= $id ?>">
                        <input type="hidden" name="aksi" value="selesai">
                        <div class="form-group">
                            <label>Upload Bukti Penyelesaian (opsional)</label>
                            <input type="file" class="form-control" name="bukti" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        <div class="form-group">
                            <label>Catatan Penyelesaian</label>
                            <textarea name="catatan_selesai" class="form-control" rows="2" placeholder="Ringkasan hasil penanganan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" data-confirm="Tandai pengaduan ini sebagai selesai?"><i class="bi bi-check-lg"></i> Tandai Selesai</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
