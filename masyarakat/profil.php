<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('masyarakat');

$id_user = $_SESSION['id_user'];
$stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();

$judul_halaman = 'Profil Saya';
$subjudul_halaman = 'Kelola data pribadi Anda';
$halaman_aktif = 'profil';

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>
            <div class="card">
                <div class="card-header"><h2><i class="bi bi-person-circle"></i> Data Profil</h2></div>
                <div class="card-body">
                    <form action="../proses/update_profil.php" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2"><?= htmlspecialchars($user['alamat']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto Profil</label>
                            <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-key"></i> Ganti Password</h2></div>
                <div class="card-body">
                    <form action="../proses/ubah_password.php" method="POST">
                        <div class="form-row">
                            <div class="form-group"><label>Password Lama</label><input type="password" class="form-control" name="password_lama" required></div>
                            <div class="form-group"><label>Password Baru</label><input type="password" class="form-control" name="password_baru" required minlength="6"></div>
                        </div>
                        <button type="submit" class="btn btn-outline"><i class="bi bi-shield-lock"></i> Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
