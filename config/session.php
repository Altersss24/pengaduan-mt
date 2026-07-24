<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Wajib login. Jika belum login, lempar ke halaman login.
 */
function cekLogin() {
    if (!isset($_SESSION['id_user'])) {
        header('Location: /pengaduan_masyarakat/auth/login.php');
        exit;
    }
}

/**
 * Batasi akses halaman hanya untuk role tertentu.
 * Contoh: cekRole('admin');  atau  cekRole(['admin','petugas']);
 */
function cekRole($role) {
    cekLogin();
    $roleUser = $_SESSION['role'];
    $izin = is_array($role) ? in_array($roleUser, $role) : $roleUser === $role;
    if (!$izin) {
        header('Location: /pengaduan_masyarakat/index.php');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['id_user']);
}
