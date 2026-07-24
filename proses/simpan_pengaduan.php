<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../masyarakat/tambah_pengaduan.php');
}

$id_user      = $_SESSION['id_user'];
$judul        = bersihkan($_POST['judul']);
$id_kategori  = (int)$_POST['id_kategori'];
$isi          = bersihkan($_POST['isi_pengaduan']);
$lokasi       = bersihkan($_POST['lokasi'] ?? '');

$stmt = mysqli_prepare($koneksi, "INSERT INTO pengaduan (id_user, id_kategori, judul, isi_pengaduan, lokasi, status) VALUES (?, ?, ?, ?, ?, 'menunggu')");
mysqli_stmt_bind_param($stmt, 'iisss', $id_user, $id_kategori, $judul, $isi, $lokasi);

if (!mysqli_stmt_execute($stmt)) {
    redirect('../masyarakat/tambah_pengaduan.php', 'Gagal menyimpan pengaduan. Coba lagi.', 'danger');
}
$id_pengaduan = mysqli_insert_id($koneksi);

// Upload lampiran jika ada
if (!empty($_FILES['bukti']['name'])) {
    $folder = __DIR__ . '/../assets/img/upload/pengaduan';
    $hasil = uploadFile($_FILES['bukti'], $folder);
    if ($hasil['sukses']) {
        $stmtL = mysqli_prepare($koneksi, "INSERT INTO lampiran (id_pengaduan, id_user, nama_file, nama_simpan, path_file, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $path = 'assets/img/upload/pengaduan/' . $hasil['nama_simpan'];
        mysqli_stmt_bind_param($stmtL, 'iisssss', $id_pengaduan, $id_user, $hasil['nama_asli'], $hasil['nama_simpan'], $path, $hasil['tipe'], $hasil['ukuran']);
        mysqli_stmt_execute($stmtL);
    }
}

catatLog($id_user, 'Membuat pengaduan baru: ' . $judul);
redirect('../masyarakat/detail.php?id=' . $id_pengaduan, 'Pengaduan berhasil dikirim dan sedang menunggu verifikasi admin.');
