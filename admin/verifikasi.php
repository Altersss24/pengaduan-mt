<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, k.nama_kategori, u.nama AS pelapor, u.no_hp, u.alamat AS alamat_pelapor
    FROM pengaduan p JOIN kategori k ON k.id_kategori=p.id_kategori JOIN user u ON u.id_user=p.id_user
    WHERE p.id_pengaduan = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$pengaduan = mysqli_stmt_get_result($stmt)->fetch_assoc();
if (!$pengaduan) { redirect('pengaduan.php', 'Pengaduan tidak ditemukan.', 'danger'); }

$petugasList = mysqli_query($koneksi, "SELECT id_user, nama FROM user WHERE role = 'petugas' ORDER BY nama");
$lampiran = mysqli_query($koneksi, "SELECT * FROM lampiran WHERE id_pengaduan = $id");
$tanggapan = mysqli_query($koneksi, "SELECT t.*, u.nama, u.role FROM tanggapan t JOIN user u ON u.id_user=t.id_user WHERE t.id_pengaduan=$id ORDER BY t.created_at ASC");

$judul_halaman = 'Verifikasi Pengaduan';
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
                        &nbsp;·&nbsp; <i class="bi bi-calendar3"></i> <?= formatTanggal($pengaduan['tgl_pengaduan']) ?>
                    </p>
                    <p><?= nl2br(htmlspecialchars($pengaduan['isi_pengaduan'])) ?></p>
                    <?php if ($pengaduan['lokasi']): ?><p style="margin-top:.7rem;"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($pengaduan['lokasi']) ?></p><?php endif; ?>

                    <?php if (mysqli_num_rows($lampiran) > 0): ?>
                        <h4 style="margin-top:1.3rem; font-size:.9rem;">Lampiran Bukti</h4>
                        <div style="display:flex; gap:.8rem; flex-wrap:wrap; margin-top:.6rem;">
                            <?php while ($f = mysqli_fetch_assoc($lampiran)): ?>
                                <a href="/pengaduan_masyarakat/assets/img/upload/pengaduan/<?= $f['nama_simpan'] ?>" target="_blank" class="btn btn-outline btn-sm"><i class="bi bi-paperclip"></i> <?= htmlspecialchars($f['nama_file']) ?></a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($pengaduan['status'] === 'menunggu'): ?>
            <div class="card">
                <div class="card-header"><h2><i class="bi bi-check2-square"></i> Verifikasi Pengaduan</h2></div>
                <div class="card-body">
                    <form action="../proses/ubah_status.php" method="POST" style="margin-bottom:1.5rem;">
                        <input type="hidden" name="id_pengaduan" value="<?= $id ?>">
                        <input type="hidden" name="aksi" value="setujui">
                        <div class="form-group">
                            <label>Teruskan ke Petugas</label>
                            <select name="id_petugas" class="form-control" required>
                                <option value="">-- Pilih Petugas --</option>
                                <?php while ($p = mysqli_fetch_assoc($petugasList)): ?>
                                    <option value="<?= $p['id_user'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Setujui &amp; Teruskan</button>
                    </form>

                    <form action="../proses/ubah_status.php" method="POST">
                        <input type="hidden" name="id_pengaduan" value="<?= $id ?>">
                        <input type="hidden" name="aksi" value="tolak">
                        <div class="form-group">
                            <label>Alasan Penolakan</label>
                            <textarea name="alasan_tolak" class="form-control" rows="2" placeholder="Jelaskan alasan pengaduan ini ditolak" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger" data-confirm="Tolak pengaduan ini?"><i class="bi bi-x-lg"></i> Tolak Pengaduan</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-chat-left-text"></i> Riwayat Tanggapan</h2></div>
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
                        <div class="form-group">
                            <label>Tambah Tanggapan / Informasi</label>
                            <textarea name="isi_tanggapan" class="form-control" rows="2" placeholder="Tulis tanggapan untuk warga..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline"><i class="bi bi-send"></i> Kirim Tanggapan</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
