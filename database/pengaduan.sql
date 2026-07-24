-- =========================================================
-- Database: pengaduan_masyarakat
-- Aplikasi Pengaduan Masyarakat Berbasis Web (Native PHP)
-- =========================================================

CREATE DATABASE IF NOT EXISTS pengaduan_masyarakat
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE pengaduan_masyarakat;

-- 1. TABEL USER -------------------------------------------
CREATE TABLE user (
  id_user     INT(11) AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100)        NOT NULL,
  username    VARCHAR(50)         NOT NULL UNIQUE,
  password    VARCHAR(255)        NOT NULL,
  email       VARCHAR(100)        UNIQUE,
  no_hp       VARCHAR(20)         UNIQUE,
  alamat      TEXT,
  role        ENUM('admin','petugas','masyarakat') NOT NULL DEFAULT 'masyarakat',
  foto        VARCHAR(255)        DEFAULT 'default.png',
  created_at  DATETIME            DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. TABEL KATEGORI ----------------------------------------
CREATE TABLE kategori (
  id_kategori   INT(11) AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL,
  deskripsi     TEXT,
  icon          VARCHAR(100) DEFAULT 'bi-folder',
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. TABEL PENGADUAN -----------------------------------------
CREATE TABLE pengaduan (
  id_pengaduan  INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_user       INT(11) NOT NULL,
  id_kategori   INT(11) NOT NULL,
  judul         VARCHAR(150) NOT NULL,
  isi_pengaduan TEXT NOT NULL,
  lokasi        VARCHAR(200),
  latitude      VARCHAR(50),
  longitude     VARCHAR(50),
  status        ENUM('menunggu','diproses','selesai','ditolak') NOT NULL DEFAULT 'menunggu',
  alasan_tolak  VARCHAR(255) DEFAULT NULL,
  id_petugas    INT(11) DEFAULT NULL,
  tgl_pengaduan DATETIME DEFAULT CURRENT_TIMESTAMP,
  tgl_update    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
  FOREIGN KEY (id_petugas) REFERENCES user(id_user)
) ENGINE=InnoDB;

-- 4. TABEL TANGGAPAN ------------------------------------------
CREATE TABLE tanggapan (
  id_tanggapan     INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_pengaduan     INT(11) NOT NULL,
  id_user          INT(11) NOT NULL,
  isi_tanggapan    TEXT NOT NULL,
  status_tanggapan ENUM('informasi','jawaban') NOT NULL DEFAULT 'informasi',
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pengaduan) REFERENCES pengaduan(id_pengaduan) ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES user(id_user)
) ENGINE=InnoDB;

-- 5. TABEL LAMPIRAN ---------------------------------------------
CREATE TABLE lampiran (
  id_lampiran  INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_pengaduan INT(11) NOT NULL,
  id_user      INT(11) NOT NULL,
  nama_file    VARCHAR(255) NOT NULL,
  nama_simpan  VARCHAR(255) NOT NULL,
  path_file    VARCHAR(255) NOT NULL,
  tipe_file    VARCHAR(50),
  ukuran_file  INT(11),
  keterangan   VARCHAR(255),
  created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pengaduan) REFERENCES pengaduan(id_pengaduan) ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES user(id_user)
) ENGINE=InnoDB;

-- 6. TABEL LOG AKTIVITAS -----------------------------------------
CREATE TABLE log_aktivitas (
  id_log     INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_user    INT(11) NOT NULL,
  aktivitas  VARCHAR(255) NOT NULL,
  ip_address VARCHAR(45),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES user(id_user)
) ENGINE=InnoDB;

-- =========================================================
-- DATA AWAL (SEED)
-- =========================================================

-- Password akun contoh di bawah ini BELUM di-hash secara valid (kolom 'password'
-- diisi teks 'BELUM_DIATUR'). Setelah import selesai, jalankan sekali file
-- database/setup_password.php lewat browser untuk mengatur password ketiga
-- akun ini menjadi "password123" dengan hash yang benar (password_hash()).
INSERT INTO user (nama, username, password, email, role) VALUES
('Administrator', 'admin', 'BELUM_DIATUR', 'admin@pengaduan.go.id', 'admin'),
('Budi Petugas', 'petugas1', 'BELUM_DIATUR', 'petugas1@pengaduan.go.id', 'petugas'),
('Siti Warga', 'siti', 'BELUM_DIATUR', 'siti@gmail.com', 'masyarakat');

INSERT INTO kategori (nama_kategori, deskripsi, icon) VALUES
('Infrastruktur', 'Jalan rusak, jembatan, drainase, dan fasilitas umum lainnya', 'bi-cone-striped'),
('Kebersihan', 'Sampah menumpuk, saluran tersumbat, lingkungan kotor', 'bi-trash'),
('Keamanan', 'Gangguan keamanan dan ketertiban lingkungan', 'bi-shield-exclamation'),
('Pelayanan Publik', 'Keluhan terhadap pelayanan instansi pemerintah', 'bi-people'),
('Lainnya', 'Pengaduan di luar kategori yang tersedia', 'bi-three-dots');
