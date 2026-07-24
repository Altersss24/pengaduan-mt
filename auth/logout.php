<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';

if (isset($_SESSION['id_user'])) {
    catatLog($_SESSION['id_user'], 'Logout dari sistem');
}

session_unset();
session_destroy();

header('Location: /pengaduan_masyarakat/auth/login.php');
exit;
