<?php
require_once __DIR__ . '/koneksi.php';

/** Bersihkan input dari karakter berbahaya */
function bersihkan($data) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, trim(htmlspecialchars($data)));
}

/** Format tanggal Indonesia: 22 Juli 2026, 14:30 */
function formatTanggal($tanggal) {
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($tanggal);
    return date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y, H:i', $ts);
}

/** Badge HTML untuk status pengaduan */
function badgeStatus($status) {
    $map = [
        'menunggu' => ['label' => 'Menunggu',  'class' => 'badge-menunggu'],
        'diproses' => ['label' => 'Diproses',  'class' => 'badge-diproses'],
        'selesai'  => ['label' => 'Selesai',   'class' => 'badge-selesai'],
        'ditolak'  => ['label' => 'Ditolak',   'class' => 'badge-ditolak'],
    ];
    $s = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'badge-default'];
    return '<span class="badge-status ' . $s['class'] . '">' . $s['label'] . '</span>';
}

/** Catat log aktivitas pengguna */
function catatLog($id_user, $aktivitas) {
    global $koneksi;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '-';
    $stmt = mysqli_prepare($koneksi, "INSERT INTO log_aktivitas (id_user, aktivitas, ip_address) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iss', $id_user, $aktivitas, $ip);
    mysqli_stmt_execute($stmt);
}

/** Upload file gambar (bukti pengaduan / foto profil) */
function uploadFile($file, $folderTujuan, $allowed = ['jpg','jpeg','png','pdf']) {
    $namaAsli = $file['name'];
    $ext = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return ['sukses' => false, 'pesan' => 'Tipe file tidak diizinkan.'];
    }
    if ($file['size'] > 3 * 1024 * 1024) {
        return ['sukses' => false, 'pesan' => 'Ukuran file maksimal 3MB.'];
    }
    $namaBaru = uniqid('bukti_') . '.' . $ext;
    $tujuan = rtrim($folderTujuan, '/') . '/' . $namaBaru;
    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        return ['sukses' => true, 'nama_asli' => $namaAsli, 'nama_simpan' => $namaBaru, 'tipe' => $ext, 'ukuran' => $file['size']];
    }
    return ['sukses' => false, 'pesan' => 'Gagal mengunggah file.'];
}

/** Redirect dengan pesan flash */
function redirect($url, $pesan = null, $tipe = 'success') {
    if ($pesan) {
        $_SESSION['flash'] = ['pesan' => $pesan, 'tipe' => $tipe];
    }
    header('Location: ' . $url);
    exit;
}

/** Tampilkan pesan flash (dipanggil di header) */
function tampilkanFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        $icon = $f['tipe'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        echo '<div class="alert-flash alert-' . $f['tipe'] . '"><i class="bi ' . $icon . '"></i> ' . $f['pesan'] . '</div>';
        unset($_SESSION['flash']);
    }
}
