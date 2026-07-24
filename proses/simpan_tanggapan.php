<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole(['admin', 'petugas']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$id_user = $_SESSION['id_user'];
$id_pengaduan = (int)$_POST['id_pengaduan'];
$isi = bersihkan($_POST['isi_tanggapan']);
$status_tanggapan = ($_POST['status_tanggapan'] ?? 'informasi') === 'jawaban' ? 'jawaban' : 'informasi';

$stmt = mysqli_prepare($koneksi, "INSERT INTO tanggapan (id_pengaduan, id_user, isi_tanggapan, status_tanggapan) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'iiss', $id_pengaduan, $id_user, $isi, $status_tanggapan);
mysqli_stmt_execute($stmt);

catatLog($id_user, "Memberi tanggapan pada pengaduan #$id_pengaduan");

$tujuan = $_SESSION['role'] === 'admin'
    ? '../admin/verifikasi.php?id=' . $id_pengaduan
    : '../petugas/tindak_lanjut.php?id=' . $id_pengaduan;

redirect($tujuan, 'Tanggapan berhasil dikirim.');
