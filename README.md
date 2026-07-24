# SIGAP Warga — Aplikasi Pengaduan Masyarakat

Aplikasi pengaduan masyarakat berbasis web, dibangun dengan **Native HTML, CSS, dan PHP** (tanpa framework) dengan database **MySQL**. Tema desain: elegan, nuansa biru & putih.

## Fitur Utama

**Masyarakat**: registrasi & login, buat pengaduan + upload bukti, lihat status real-time, riwayat pengaduan, tanggapan/balasan, kelola profil.

**Admin**: dashboard statistik, kelola kategori, kelola pengguna (admin/petugas), verifikasi pengaduan (setuju/tolak), teruskan ke petugas, grafik & cetak laporan.

**Petugas**: dashboard tugas, daftar pengaduan yang ditugaskan, tindak lanjut & tanggapan, upload bukti penyelesaian, ubah status menjadi selesai.

## Struktur Folder

Struktur mengikuti rancangan cheatsheet: `assets/`, `config/`, `auth/`, `masyarakat/`, `admin/`, `petugas/`, `include/`, `proses/`, `laporan/`, `database/`.

## Cara Menjalankan (XAMPP / Laragon)

1. Salin folder `pengaduan_masyarakat/` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka **phpMyAdmin**, lalu import file `database/pengaduan.sql`. Ini akan otomatis membuat database `pengaduan_masyarakat` beserta seluruh tabel dan data awal (kategori & akun contoh).
3. Cek konfigurasi koneksi di `config/koneksi.php` — secara default menggunakan `localhost`, user `root`, tanpa password (standar XAMPP/Laragon).
4. Pastikan folder `assets/img/upload/pengaduan/` dan `assets/img/upload/profil/` memiliki izin **write**.
5. Jalankan browser dan akses: `http://localhost/pengaduan_masyarakat/`

## Akun Contoh (Seed Data)

Setelah import `database/pengaduan.sql`, tiga akun contoh (admin, petugas1, siti) dibuat namun **password belum aktif**. Aktifkan dengan membuka file berikut sekali lewat browser:

```
http://localhost/pengaduan_masyarakat/database/setup_password.php
```

Script ini akan mengatur password ketiga akun tersebut menjadi `password123` menggunakan `password_hash()` yang valid. Setelah berhasil, sebaiknya hapus file `setup_password.php` agar tidak dapat dijalankan ulang oleh orang lain.

| Username | Role       | Password    |
|----------|-----------|-------------|
| admin    | admin     | password123 |
| petugas1 | petugas   | password123 |
| siti     | masyarakat| password123 |

## Status Pengaduan

| Status     | Keterangan                                  |
|------------|----------------------------------------------|
| menunggu   | Pengaduan baru, menunggu verifikasi admin    |
| diproses   | Disetujui admin, sedang ditangani petugas    |
| selesai    | Telah selesai ditangani petugas               |
| ditolak    | Ditolak admin beserta alasan                  |

## Catatan

- Dibangun tanpa framework (native PHP + MySQLi, prepared statements untuk keamanan query).
- Password disimpan ter-hash dengan `password_hash()`.
- Desain menggunakan satu file `assets/css/style.css` bertema biru & putih yang dipakai di seluruh halaman (landing page, autentikasi, dan dashboard tiga role).
- Fitur cetak laporan (`laporan/pdf.php`) berupa halaman print-friendly yang dapat disimpan sebagai PDF melalui dialog cetak browser.
