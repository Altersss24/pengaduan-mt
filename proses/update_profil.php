<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../masyarakat/profil.php');
}

$id_user = $_SESSION['id_user'];
$nama = bersihkan($_POST['nama']);
$email = bersihkan($_POST['email']);
$no_hp = bersihkan($_POST['no_hp']);
$alamat = bersihkan($_POST['alamat'] ?? '');

$fotoQuery = '';
$params = [$nama, $email, $no_hp, $alamat];
$types = 'ssss';

if (!empty($_FILES['foto']['name'])) {
    $folder = __DIR__ . '/../assets/img/upload/profil';
    $hasil = uploadFile($_FILES['foto'], $folder, ['jpg','jpeg','png']);
    if ($hasil['sukses']) {
        $fotoQuery = ', foto = ?';
        $params[] = $hasil['nama_simpan'];
        $types .= 's';
        $_SESSION['foto'] = $hasil['nama_simpan'];
    }
}

$params[] = $id_user;
$types .= 'i';

$sql = "UPDATE user SET nama=?, email=?, no_hp=?, alamat=?$fotoQuery WHERE id_user=?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['nama'] = $nama;
    catatLog($id_user, 'Memperbarui profil');
    redirect('../masyarakat/profil.php', 'Profil berhasil diperbarui.');
} else {
    redirect('../masyarakat/profil.php', 'Gagal memperbarui profil.', 'danger');
}
