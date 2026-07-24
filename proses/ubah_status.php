<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole(['admin', 'petugas']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$id_pengaduan = (int)$_POST['id_pengaduan'];
$aksi = $_POST['aksi'];
$id_user = $_SESSION['id_user'];

if ($aksi === 'setujui' && $_SESSION['role'] === 'admin') {
    $id_petugas = (int)$_POST['id_petugas'];
    $stmt = mysqli_prepare($koneksi, "UPDATE pengaduan SET status='diproses', id_petugas=? WHERE id_pengaduan=?");
    mysqli_stmt_bind_param($stmt, 'ii', $id_petugas, $id_pengaduan);
    mysqli_stmt_execute($stmt);
    catatLog($id_user, "Menyetujui & meneruskan pengaduan #$id_pengaduan ke petugas");
    redirect('../admin/verifikasi.php?id=' . $id_pengaduan, 'Pengaduan disetujui dan diteruskan ke petugas.');

} elseif ($aksi === 'tolak' && $_SESSION['role'] === 'admin') {
    $alasan = bersihkan($_POST['alasan_tolak']);
    $stmt = mysqli_prepare($koneksi, "UPDATE pengaduan SET status='ditolak', alasan_tolak=? WHERE id_pengaduan=?");
    mysqli_stmt_bind_param($stmt, 'si', $alasan, $id_pengaduan);
    mysqli_stmt_execute($stmt);
    catatLog($id_user, "Menolak pengaduan #$id_pengaduan");
    redirect('../admin/verifikasi.php?id=' . $id_pengaduan, 'Pengaduan telah ditolak.');

} elseif ($aksi === 'selesai' && $_SESSION['role'] === 'petugas') {
    // Verifikasi petugas memang pemegang pengaduan ini
    $cek = mysqli_prepare($koneksi, "SELECT id_petugas FROM pengaduan WHERE id_pengaduan=?");
    mysqli_stmt_bind_param($cek, 'i', $id_pengaduan);
    mysqli_stmt_execute($cek);
    $row = mysqli_stmt_get_result($cek)->fetch_assoc();
    if (!$row || (int)$row['id_petugas'] !== (int)$id_user) {
        redirect('../petugas/pengaduan.php', 'Anda tidak berhak mengubah pengaduan ini.', 'danger');
    }

    $stmt = mysqli_prepare($koneksi, "UPDATE pengaduan SET status='selesai' WHERE id_pengaduan=?");
    mysqli_stmt_bind_param($stmt, 'i', $id_pengaduan);
    mysqli_stmt_execute($stmt);

    // Upload bukti penyelesaian jika ada
    if (!empty($_FILES['bukti']['name'])) {
        $folder = __DIR__ . '/../assets/img/upload/pengaduan';
        $hasil = uploadFile($_FILES['bukti'], $folder);
        if ($hasil['sukses']) {
            $stmtL = mysqli_prepare($koneksi, "INSERT INTO lampiran (id_pengaduan, id_user, nama_file, nama_simpan, path_file, tipe_file, ukuran_file, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, 'Bukti Penyelesaian')");
            $path = 'assets/img/upload/pengaduan/' . $hasil['nama_simpan'];
            mysqli_stmt_bind_param($stmtL, 'iisssss', $id_pengaduan, $id_user, $hasil['nama_asli'], $hasil['nama_simpan'], $path, $hasil['tipe'], $hasil['ukuran']);
            mysqli_stmt_execute($stmtL);
        }
    }
    // Catatan penyelesaian sebagai tanggapan
    $catatan = bersihkan($_POST['catatan_selesai'] ?? '');
    if ($catatan !== '') {
        $stmtT = mysqli_prepare($koneksi, "INSERT INTO tanggapan (id_pengaduan, id_user, isi_tanggapan, status_tanggapan) VALUES (?, ?, ?, 'jawaban')");
        $isi = "Pengaduan selesai ditangani. Catatan: $catatan";
        mysqli_stmt_bind_param($stmtT, 'iis', $id_pengaduan, $id_user, $isi);
        mysqli_stmt_execute($stmtT);
    }

    catatLog($id_user, "Menyelesaikan pengaduan #$id_pengaduan");
    redirect('../petugas/tindak_lanjut.php?id=' . $id_pengaduan, 'Pengaduan berhasil ditandai selesai.');
}

redirect('../index.php');
