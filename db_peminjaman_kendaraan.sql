-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 05:15 PM
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
-- Database: `db_peminjaman_kendaraan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `email`, `password`) VALUES
(1, 'admin', 'narson.nov@gmail.com', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `data_peminjam`
--

CREATE TABLE `data_peminjam` (
  `id` int(11) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_peminjam`
--

INSERT INTO `data_peminjam` (`id`, `nama_peminjam`, `unit`, `no_telepon`, `status`, `created_at`) VALUES
(5, 'Narson', 'FST', '089876543210', 'aktif', '2026-06-22 12:21:40'),
(6, 'Budi', 'FSH', '087654321098', 'aktif', '2026-06-22 12:28:57'),
(7, 'ibra', 'fst', '08976542321', 'aktif', '2026-06-23 04:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_ganti_oli_kendaraan_operasional`
--

CREATE TABLE `jadwal_ganti_oli_kendaraan_operasional` (
  `id` int(11) NOT NULL,
  `id_kendaraan` int(11) NOT NULL,
  `no_polisi` varchar(20) NOT NULL,
  `tanggal_service` date DEFAULT NULL,
  `tanggal_spk` date DEFAULT NULL,
  `no_spk` varchar(50) DEFAULT NULL,
  `tanggal_lpj` date DEFAULT NULL,
  `no_lpj` varchar(50) DEFAULT NULL,
  `km_ganti_oli` int(11) NOT NULL,
  `km_ganti_oli_selanjutnya` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_ganti_oli_kendaraan_operasional`
--

INSERT INTO `jadwal_ganti_oli_kendaraan_operasional` (`id`, `id_kendaraan`, `no_polisi`, `tanggal_service`, `tanggal_spk`, `no_spk`, `tanggal_lpj`, `no_lpj`, `km_ganti_oli`, `km_ganti_oli_selanjutnya`, `keterangan`, `status`) VALUES
(1, 4, 'B 1573 CKI', '2026-05-29', '2026-04-30', '041/um-6a/iu/26', '2026-06-04', '027/umum-lpj/ui/2026', 206438, 211438, 'oli stelix Rp 350.000\nfilter oli Rp 30.000\ncarb cleaner Rp 30.000\nfilter udara Rp 130.000\nliak stabil Rp 210.000 2pcs Rp 420.000\nboss sayap Rp 100.000 2pcs Rp 200.000\nrock and Rp 150.000\npress boss lower arm Rp 100.000\nkaret boot rak sht Rp 45.000\ntune up + jasa Rp 300.000\nstiker ubd Rp 200.000\ntotal Rp 1.955.000', 'Maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id_kendaraan` int(11) NOT NULL,
  `merk_jenis` varchar(100) NOT NULL,
  `tahun` int(11) NOT NULL,
  `no_polisi` varchar(15) NOT NULL,
  `tgl_pajak_stnk` varchar(30) DEFAULT NULL,
  `jenis` enum('Mobil','Motor') NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'siap'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id_kendaraan`, `merk_jenis`, `tahun`, `no_polisi`, `tgl_pajak_stnk`, `jenis`, `status`) VALUES
(1, 'DAIHATSU GRANMAX', 2009, 'B 1597 CFF', '19 OKTOBER', 'Mobil', 'siap'),
(2, 'TOYOTA AVANZA', 2012, 'B 1051 CFY', '12 DESEMBER', 'Mobil', 'siap'),
(3, 'SUZUKI ERTIGA', 2014, 'B 1432 CKI', '02 APRIL', 'Mobil', 'siap'),
(4, 'SUZUKI ERTIGA', 2014, 'B 1573 CKI', '07 APRIL', 'Mobil', 'siap'),
(5, 'TOYOTA VIOS', 2015, 'B 1924 CAA', '18 AGUSTUS', 'Mobil', 'siap'),
(6, 'TOYOTA HI-ACE', 2017, 'B 7290 CDA', '06 NOVEMBER', 'Mobil', 'siap'),
(7, 'YAMAHA VEGA-R (PURCHASING)', 2004, 'B 6335 CAX', '29 JULI', 'Motor', 'siap'),
(8, 'HONDA REVO (TOTO)', 2013, 'B 6157 CXJ', '22 OKTOBER', 'Motor', 'siap');

-- --------------------------------------------------------

--
-- Table structure for table `pengisian_bbm`
--

CREATE TABLE `pengisian_bbm` (
  `id` int(11) NOT NULL,
  `id_kendaraan` int(11) NOT NULL,
  `id_supir` int(11) NOT NULL,
  `tanggal_pengisian` date NOT NULL,
  `km_sebelum` int(11) NOT NULL,
  `km_sesudah` int(11) NOT NULL,
  `km_terpakai` int(11) NOT NULL,
  `jumlah_pengisian` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengisian_bbm`
--

INSERT INTO `pengisian_bbm` (`id`, `id_kendaraan`, `id_supir`, `tanggal_pengisian`, `km_sebelum`, `km_sesudah`, `km_terpakai`, `jumlah_pengisian`) VALUES
(3, 5, 5, '2026-05-25', 174960, 175257, 297, 300000.00);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `id` int(11) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `kendaraan` varchar(100) NOT NULL,
  `pengemudi` varchar(100) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `jam_berangkat` time NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `jam_kembali` time NOT NULL,
  `keperluan` text NOT NULL,
  `tujuan` text NOT NULL,
  `status` enum('disetujui','pending','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat`
--

INSERT INTO `riwayat` (`id`, `nama_peminjam`, `kendaraan`, `pengemudi`, `tanggal_pinjam`, `jam_berangkat`, `tanggal_kembali`, `jam_kembali`, `keperluan`, `tujuan`, `status`, `created_at`) VALUES
(5, 'Narson', 'B 1573 CKI', 'Tohir', '2026-06-22', '19:24:00', '2026-06-22', '04:21:00', 'tes', 'ubd', 'ditolak', '2026-06-22 12:21:40'),
(6, 'Budi', 'B 7290 CDA', 'Opsional', '2026-06-25', '19:33:00', '2026-06-27', '01:28:00', 'yolo', 'gbc', 'disetujui', '2026-06-22 12:28:57'),
(7, 'ibra', 'B 1432 CKI', 'Uang Ali', '2026-06-22', '13:29:00', '2026-06-23', '11:30:00', 'seminar', 'seminar', 'disetujui', '2026-06-23 04:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `supir`
--

CREATE TABLE `supir` (
  `id` int(11) NOT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `no_polisi` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supir`
--

INSERT INTO `supir` (`id`, `nama_supir`, `no_polisi`) VALUES
(1, 'Uang Ali', 'B 1597 CFF'),
(2, 'Hendrik', 'B 1051 CFY'),
(3, 'Mayor', 'B 1432 CKI'),
(4, 'Tohir', 'B 1573 CKI'),
(5, 'Kurniawan', 'B 1924 CAA'),
(6, 'Opsional', 'B 7290 CDA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `data_peminjam`
--
ALTER TABLE `data_peminjam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_ganti_oli_kendaraan_operasional`
--
ALTER TABLE `jadwal_ganti_oli_kendaraan_operasional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gantioli_kendaraan` (`id_kendaraan`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD UNIQUE KEY `no_polisi` (`no_polisi`);

--
-- Indexes for table `pengisian_bbm`
--
ALTER TABLE `pengisian_bbm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bbm_mobil` (`id_kendaraan`),
  ADD KEY `fk_bbm_supir` (`id_supir`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supir`
--
ALTER TABLE `supir`
  ADD PRIMARY KEY (`id`),
  ADD KEY `no_polisi` (`no_polisi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_peminjam`
--
ALTER TABLE `data_peminjam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jadwal_ganti_oli_kendaraan_operasional`
--
ALTER TABLE `jadwal_ganti_oli_kendaraan_operasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengisian_bbm`
--
ALTER TABLE `pengisian_bbm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `supir`
--
ALTER TABLE `supir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_ganti_oli_kendaraan_operasional`
--
ALTER TABLE `jadwal_ganti_oli_kendaraan_operasional`
  ADD CONSTRAINT `fk_gantioli_kendaraan` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id_kendaraan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengisian_bbm`
--
ALTER TABLE `pengisian_bbm`
  ADD CONSTRAINT `fk_bbm_mobil` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id_kendaraan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bbm_supir` FOREIGN KEY (`id_supir`) REFERENCES `supir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supir`
--
ALTER TABLE `supir`
  ADD CONSTRAINT `supir_ibfk_1` FOREIGN KEY (`no_polisi`) REFERENCES `kendaraan` (`no_polisi`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
