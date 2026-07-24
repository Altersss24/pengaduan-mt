<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helper.php';
cekRole('admin');

$judul_halaman = 'Kelola Pengguna';
$subjudul_halaman = 'Data masyarakat, petugas, dan admin';
$halaman_aktif = 'pengguna';

$role_filter = $_GET['role'] ?? '';
$where = $role_filter ? "WHERE role = '" . mysqli_real_escape_string($koneksi, $role_filter) . "'" : '';
$list = mysqli_query($koneksi, "SELECT * FROM user $where ORDER BY role, nama");

require_once __DIR__ . '/../include/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../include/sidebar.php'; ?>
    <main class="main-content">
        <?php require __DIR__ . '/../include/navbar.php'; ?>
        <div class="page-body">
            <?php tampilkanFlash(); ?>

            <div class="card">
                <div class="card-header"><h2><i class="bi bi-person-plus"></i> Tambah Pengguna (Admin/Petugas)</h2></div>
                <div class="card-body">
                    <form action="../proses/simpan_pengguna.php" method="POST">
                        <div class="form-row">
                            <div class="form-group"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama" required></div>
                            <div class="form-group"><label>Username</label><input type="text" class="form-control" name="username" required></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" required></div>
                            <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" required minlength="6"></div>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role" required>
                                <option value="petugas">Petugas</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pengguna</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-people-fill"></i> Daftar Pengguna</h2>
                    <form method="GET">
                        <select name="role" class="form-control btn-sm" style="min-width:160px;" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            <option value="masyarakat" <?= $role_filter==='masyarakat'?'selected':'' ?>>Masyarakat</option>
                            <option value="petugas" <?= $role_filter==='petugas'?'selected':'' ?>>Petugas</option>
                            <option value="admin" <?= $role_filter==='admin'?'selected':'' ?>>Admin</option>
                        </select>
                    </form>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Nama</th><th>Username</th><th>Email</th><th>No. HP</th><th>Role</th><th style="width:180px;">Aksi</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($list)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['no_hp'] ?: '-') ?></td>
                                <td><span class="badge-role <?= $row['role'] ?>"><?= $row['role'] ?></span></td>
                                <td>
                                    <a href="../proses/reset_password.php?id=<?= $row['id_user'] ?>" class="btn btn-outline btn-sm" data-confirm="Reset password pengguna ini ke default (password123)?"><i class="bi bi-key"></i></a>
                                    <?php if ($row['id_user'] != $_SESSION['id_user']): ?>
                                    <a href="../proses/hapus_pengguna.php?id=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm" data-confirm="Hapus pengguna ini secara permanen?"><i class="bi bi-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require __DIR__ . '/../include/footer.php'; ?>
