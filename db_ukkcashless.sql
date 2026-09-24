-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2026 at 04:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ukkcashless`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_akun`
--

CREATE TABLE `tb_akun` (
  `id_akun` int(11) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `uid_kartu` varchar(50) DEFAULT NULL,
  `saldo` decimal(12,2) DEFAULT 0.00,
  `shift` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_akun`
--

INSERT INTO `tb_akun` (`id_akun`, `role`, `nama`, `nis`, `uid_kartu`, `saldo`, `shift`) VALUES
(1, 'admin', 'Budi Santoso', NULL, NULL, 0.00, 'Pagi'),
(2, 'admin', 'Siti Aminah', NULL, NULL, 0.00, 'Siang'),
(3, 'user', 'Andi Wijaya', '2024001', '2783949049', 70000.00, NULL),
(4, 'user', 'Dewi Lestari', '2024002', '0297264271', 109000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jadwal_angsuran`
--

CREATE TABLE `tb_jadwal_angsuran` (
  `id_jadwal` int(11) NOT NULL,
  `id_piutang` int(11) NOT NULL,
  `angsuran_ke` int(11) NOT NULL,
  `nominal_angsuran` decimal(12,2) NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `tanggal_dibayar` datetime DEFAULT NULL,
  `status` enum('belum_dibayar','sudah_dibayar','telat') NOT NULL DEFAULT 'belum_dibayar'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_jadwal_angsuran`
--

INSERT INTO `tb_jadwal_angsuran` (`id_jadwal`, `id_piutang`, `angsuran_ke`, `nominal_angsuran`, `tanggal_jatuh_tempo`, `tanggal_dibayar`, `status`) VALUES
(1, 1, 1, 101000.00, '2026-10-24', NULL, 'belum_dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `tb_login`
--

CREATE TABLE `tb_login` (
  `id_login` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('super_admin','kasir','user') NOT NULL,
  `id_akun` int(11) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_login`
--

INSERT INTO `tb_login` (`id_login`, `username`, `password`, `level`, `id_akun`, `status`, `last_login`) VALUES
(1, 'superadmin1', '$2y$10$t.un78e7cfQmDwehi/rud.jTVKhfN0QhEezdMHotQDIl.MmLYnEhW', 'super_admin', 1, 'aktif', '2026-08-20 08:55:27'),
(2, 'kasir1', '$2y$10$qaOqCL.j9eI8mlY9M22.muMv19tGg8Mx0ZdaT.UBhE73GgzGHMkwO', 'kasir', 2, 'aktif', '2026-09-07 07:39:24'),
(3, 'andi.w', '$2y$10$hashcontohUser', 'user', 3, 'aktif', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_menu`
--

CREATE TABLE `tb_menu` (
  `id_menu` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar_menu` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_menu`
--

INSERT INTO `tb_menu` (`id_menu`, `nama`, `harga_jual`, `stok`, `gambar_menu`, `status`) VALUES
(1, 'Nasi Goreng', 12000.00, 16, 'nasi_goreng.jpg', 'aktif'),
(2, 'Teh Manis', 4000.00, 47, 'es_teh.jpg', 'aktif'),
(3, 'Roti Bakar', 8000.00, 13, 'roti_bakar.jpg', 'aktif'),
(5, 'es jeruk', 5000.00, 4, NULL, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tb_piutang`
--

CREATE TABLE `tb_piutang` (
  `id_piutang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_teller` int(11) DEFAULT NULL,
  `id_approver` int(11) DEFAULT NULL,
  `plafon_pinjaman` decimal(12,2) NOT NULL,
  `bunga_persen` decimal(5,2) NOT NULL,
  `total_harus_dibayar` decimal(12,2) NOT NULL,
  `sisa_piutang` decimal(12,2) NOT NULL,
  `tenor_bulan` int(11) NOT NULL,
  `status` enum('diajukan','ditolak','disetujui','berjalan','lunas') NOT NULL DEFAULT 'diajukan',
  `tanggal_pengajuan` datetime NOT NULL DEFAULT current_timestamp(),
  `tanggal_disetujui` datetime DEFAULT NULL,
  `tanggal_pencairan` datetime DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_piutang`
--

INSERT INTO `tb_piutang` (`id_piutang`, `id_user`, `id_teller`, `id_approver`, `plafon_pinjaman`, `bunga_persen`, `total_harus_dibayar`, `sisa_piutang`, `tenor_bulan`, `status`, `tanggal_pengajuan`, `tanggal_disetujui`, `tanggal_pencairan`, `keterangan`) VALUES
(1, 4, NULL, NULL, 100000.00, 1.00, 101000.00, 101000.00, 1, 'berjalan', '2026-09-24 08:22:29', '2026-09-24 08:30:56', '2026-09-24 08:30:56', NULL),
(2, 4, NULL, NULL, 100000.00, 1.00, 101000.00, 101000.00, 1, 'ditolak', '2026-09-24 08:29:51', NULL, NULL, NULL),
(3, 3, NULL, NULL, 100000.00, 0.50, 100500.00, 100500.00, 10, 'diajukan', '2026-09-24 09:07:35', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jenis_transaksi` enum('topup','bayar','tarik','pinjaman','angsuran') NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga_satuan` decimal(12,2) DEFAULT NULL,
  `total_bayar` decimal(12,2) NOT NULL,
  `saldo_awal` decimal(12,2) NOT NULL,
  `saldo_akhir` decimal(12,2) NOT NULL,
  `status` enum('berhasil','dibatalkan') NOT NULL DEFAULT 'berhasil',
  `keterangan` varchar(255) DEFAULT NULL,
  `waktu_transaksi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_user`, `id_admin`, `id_menu`, `jenis_transaksi`, `qty`, `harga_satuan`, `total_bayar`, `saldo_awal`, `saldo_akhir`, `status`, `keterangan`, `waktu_transaksi`) VALUES
(2, 3, 2, 1, 'bayar', 1, 12000.00, 12000.00, 50000.00, 38000.00, 'berhasil', 'Transaksi berhasil', '2026-08-05 10:32:39'),
(3, 3, 2, 1, 'bayar', 1, 12000.00, 12000.00, 50000.00, 38000.00, 'berhasil', 'Transaksi berhasil', '2026-08-05 12:51:02'),
(4, 3, 2, 2, 'bayar', 1, 4000.00, 4000.00, 38000.00, 34000.00, 'berhasil', 'Transaksi berhasil', '2026-08-05 12:51:02'),
(5, 3, 2, 1, 'bayar', 1, 12000.00, 12000.00, 34000.00, 22000.00, 'berhasil', 'Transaksi berhasil', '2026-08-05 13:26:46'),
(6, 3, 2, 2, 'bayar', 1, 4000.00, 4000.00, 22000.00, 18000.00, 'dibatalkan', 'Dibatalkan oleh kasir', '2026-08-05 13:26:46'),
(7, 3, 2, 1, 'bayar', 1, 12000.00, 12000.00, 22000.00, 10000.00, 'berhasil', 'Transaksi berhasil', '2026-08-05 13:42:31'),
(8, 3, 2, 3, 'bayar', 1, 8000.00, 8000.00, 10000.00, 2000.00, 'berhasil', 'Transaksi berhasil', '2026-08-05 13:42:31'),
(9, 4, 2, 3, 'bayar', 1, 8000.00, 8000.00, 25000.00, 17000.00, 'dibatalkan', 'Dibatalkan oleh kasir', '2026-08-06 14:41:48'),
(10, 4, 2, 2, 'bayar', 1, 4000.00, 4000.00, 25000.00, 21000.00, 'berhasil', 'Transaksi berhasil', '2026-08-07 09:54:56'),
(11, 4, 2, 3, 'bayar', 1, 8000.00, 8000.00, 21000.00, 13000.00, 'berhasil', 'Transaksi berhasil', '2026-08-07 09:54:56'),
(12, 4, 2, 2, 'bayar', 1, 4000.00, 4000.00, 13000.00, 9000.00, 'berhasil', 'Transaksi berhasil', '2026-08-10 07:34:53'),
(13, 4, 2, 3, 'bayar', 1, 8000.00, 8000.00, 9000.00, 1000.00, 'dibatalkan', 'Dibatalkan oleh kasir', '2026-08-10 07:34:53'),
(14, 3, 2, 1, 'bayar', 1, 12000.00, 12000.00, 100000.00, 88000.00, 'berhasil', 'Transaksi berhasil', '2026-08-13 09:46:18'),
(15, 3, 2, 5, 'bayar', 1, 5000.00, 5000.00, 88000.00, 83000.00, 'berhasil', 'Transaksi berhasil', '2026-08-13 09:46:18'),
(16, 3, NULL, NULL, 'topup', NULL, NULL, 2000.00, 83000.00, 85000.00, 'berhasil', 'Top up melalui sistemMiska', '2026-09-24 07:41:07'),
(17, 3, NULL, NULL, '', NULL, NULL, 15000.00, 85000.00, 70000.00, 'berhasil', 'Tarik dana melalui sistemMiska', '2026-09-24 07:41:15'),
(18, 4, NULL, NULL, 'pinjaman', NULL, NULL, 100000.00, 9000.00, 109000.00, 'berhasil', 'Pencairan pinjaman', '2026-09-24 08:30:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_akun`
--
ALTER TABLE `tb_akun`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `uid_kartu` (`uid_kartu`),
  ADD KEY `idx_akun_role` (`role`);

--
-- Indexes for table `tb_jadwal_angsuran`
--
ALTER TABLE `tb_jadwal_angsuran`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `idx_jadwal_piutang` (`id_piutang`);

--
-- Indexes for table `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`id_login`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_login_akun` (`id_akun`);

--
-- Indexes for table `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `tb_piutang`
--
ALTER TABLE `tb_piutang`
  ADD PRIMARY KEY (`id_piutang`),
  ADD KEY `fk_piutang_teller` (`id_teller`),
  ADD KEY `fk_piutang_approver` (`id_approver`),
  ADD KEY `idx_piutang_user` (`id_user`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_transaksi_admin` (`id_admin`),
  ADD KEY `fk_transaksi_menu` (`id_menu`),
  ADD KEY `idx_transaksi_user` (`id_user`),
  ADD KEY `idx_transaksi_waktu` (`waktu_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_akun`
--
ALTER TABLE `tb_akun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_jadwal_angsuran`
--
ALTER TABLE `tb_jadwal_angsuran`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_piutang`
--
ALTER TABLE `tb_piutang`
  MODIFY `id_piutang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_jadwal_angsuran`
--
ALTER TABLE `tb_jadwal_angsuran`
  ADD CONSTRAINT `fk_jadwal_piutang` FOREIGN KEY (`id_piutang`) REFERENCES `tb_piutang` (`id_piutang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_login`
--
ALTER TABLE `tb_login`
  ADD CONSTRAINT `fk_login_akun` FOREIGN KEY (`id_akun`) REFERENCES `tb_akun` (`id_akun`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_piutang`
--
ALTER TABLE `tb_piutang`
  ADD CONSTRAINT `fk_piutang_approver` FOREIGN KEY (`id_approver`) REFERENCES `tb_akun` (`id_akun`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_piutang_teller` FOREIGN KEY (`id_teller`) REFERENCES `tb_akun` (`id_akun`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_piutang_user` FOREIGN KEY (`id_user`) REFERENCES `tb_akun` (`id_akun`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `fk_transaksi_admin` FOREIGN KEY (`id_admin`) REFERENCES `tb_akun` (`id_akun`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_menu` FOREIGN KEY (`id_menu`) REFERENCES `tb_menu` (`id_menu`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`id_user`) REFERENCES `tb_akun` (`id_akun`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
