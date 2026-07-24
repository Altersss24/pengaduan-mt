<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

$id_user = $_SESSION['id_user'];
$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, k.nama_kategori FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    WHERE p.id_pengaduan = ? AND p.id_user = ?");
mysqli_stmt_bind_param($stmt, 'ii', $id, $id_user);
mysqli_stmt_execute($stmt);
$pengaduan = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$pengaduan) {
    redirect('pengaduan.php', 'Pengaduan tidak ditemukan.', 'danger');
}

$lampiran = mysqli_query($koneksi, "SELECT * FROM lampiran WHERE id_pengaduan = $id");
$tanggapan = mysqli_query($koneksi, "
    SELECT t.*, u.nama, u.role FROM tanggapan t
    JOIN user u ON u.id_user = t.id_user
    WHERE t.id_pengaduan = $id ORDER BY t.created_at ASC");

$judul_halaman = 'Detail Pengaduan';
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
                        <i class="bi bi-folder2"></i> <?= htmlspecialchars($pengaduan['nama_kategori']) ?>
                        &nbsp;·&nbsp; <i class="bi bi-calendar3"></i> <?= formatTanggal($pengaduan['tgl_pengaduan']) ?>
                        <?php if ($pengaduan['lokasi']): ?>&nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($pengaduan['lokasi']) ?><?php endif; ?>
                    </p>
                    <p><?= nl2br(htmlspecialchars($pengaduan['isi_pengaduan'])) ?></p>

                    <?php if ($pengaduan['status'] === 'ditolak' && $pengaduan['alasan_tolak']): ?>
                        <div class="alert-flash alert-danger" style="margin-top:1.2rem;"><i class="bi bi-x-circle-fill"></i> Alasan penolakan: <?= htmlspecialchars($pengaduan['alasan_tolak']) ?></div>
                    <?php endif; ?>

                    <?php if (mysqli_num_rows($lampiran) > 0): ?>
                        <h4 style="margin-top:1.5rem; font-size:.9rem;">Lampiran Bukti</h4>
                        <div style="display:flex; gap:.8rem; flex-wrap:wrap; margin-top:.6rem;">
                            <?php while ($f = mysqli_fetch_assoc($lampiran)): ?>
                                <a href="/pengaduan_masyarakat/assets/img/upload/pengaduan/<?= $f['nama_simpan'] ?>" target="_blank" class="btn btn-outline btn-sm"><i class="bi bi-paperclip"></i> <?= htmlspecialchars($f['nama_file']) ?></a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-chat-left-text"></i> Tanggapan &amp; Riwayat</h2></div>
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
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
