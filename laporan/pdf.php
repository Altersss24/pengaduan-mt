<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$periode = $_GET['periode'] ?? '';
$where = '1=1';
if ($periode === '30hari') { $where = "p.tgl_pengaduan >= DATE_SUB(NOW(), INTERVAL 30 DAY)"; }

$data = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori, u.nama AS pelapor FROM pengaduan p
    JOIN kategori k ON k.id_kategori = p.id_kategori
    JOIN user u ON u.id_user = p.id_user
    WHERE $where ORDER BY p.tgl_pengaduan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pengaduan Masyarakat</title>
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; color: #101B33; padding: 30px; }
    h1 { color: #0E2A52; margin-bottom: 4px; }
    .sub { color: #5C6B8A; margin-bottom: 24px; font-size: 13px; }
    table { width: 100%; border-collapse: collapse; font-size: 12px; }
    th, td { border: 1px solid #E1E8F7; padding: 8px 10px; text-align: left; }
    th { background: #EAF1FE; color: #16376B; }
    .status { text-transform: capitalize; }
    @media print { .no-print { display: none; } }
</style>
</head>
<body>
<button class="no-print" onclick="window.print()" style="margin-bottom:16px; padding:8px 16px;">Cetak / Simpan sebagai PDF</button>
<h1>Laporan Pengaduan Masyarakat</h1>
<div class="sub">Dicetak pada <?= date('d F Y, H:i') ?> WIB oleh <?= htmlspecialchars($_SESSION['nama']) ?></div>
<table>
    <thead><tr><th>No</th><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Tanggal</th><th>Status</th></tr></thead>
    <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= htmlspecialchars($row['pelapor']) ?></td>
            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
            <td><?= formatTanggal($row['tgl_pengaduan']) ?></td>
            <td class="status"><?= $row['status'] ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
