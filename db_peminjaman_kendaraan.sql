-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jun 2026 pada 10.10
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_peminjam`
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
-- Dumping data untuk tabel `data_peminjam`
--

INSERT INTO `data_peminjam` (`id`, `nama_peminjam`, `unit`, `no_telepon`, `status`, `created_at`) VALUES
(1, 'Narson', 'FST', '089876543210', 'aktif', '2026-06-22 07:49:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id_kendaraan` int(11) NOT NULL,
  `merk_jenis` varchar(100) NOT NULL,
  `tahun` int(11) NOT NULL,
  `no_polisi` varchar(15) NOT NULL,
  `tgl_pajak_stnk` varchar(30) DEFAULT NULL,
  `jenis` enum('Mobil','Motor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id_kendaraan`, `merk_jenis`, `tahun`, `no_polisi`, `tgl_pajak_stnk`, `jenis`) VALUES
(1, 'DAIHATSU GRANMAX', 2009, 'B 1597 CFF', '19 OKTOBER', 'Mobil'),
(2, 'TOYOTA AVANZA', 2012, 'B 1051 CFY', '12 DESEMBER', 'Mobil'),
(3, 'SUZUKI ERTIGA', 2014, 'B 1432 CKI', '02 APRIL', 'Mobil'),
(4, 'SUZUKI ERTIGA', 2014, 'B 1573 CKI', '07 APRIL', 'Mobil'),
(5, 'TOYOTA VIOS', 2015, 'B 1924 CAA', '18 AGUSTUS', 'Mobil'),
(6, 'TOYOTA HI-ACE', 2017, 'B 7290 CDA', '06 NOVEMBER', 'Mobil'),
(7, 'YAMAHA VEGA-R (PURCHASING)', 2004, 'B 6335 CAX', '29 JULI', 'Motor'),
(8, 'HONDA REVO (TOTO)', 2013, 'B 6157 CXJ', '22 OKTOBER', 'Motor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat`
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
-- Dumping data untuk tabel `riwayat`
--

INSERT INTO `riwayat` (`id`, `nama_peminjam`, `kendaraan`, `pengemudi`, `tanggal_pinjam`, `jam_berangkat`, `tanggal_kembali`, `jam_kembali`, `keperluan`, `tujuan`, `status`, `created_at`) VALUES
(1, 'Narson', 'B 1597 CFF', 'Uang Ali', '2026-06-22', '20:00:00', '2026-06-24', '07:00:00', 'tes', 'universitas buddhi dharma', 'pending', '2026-06-22 07:49:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supir`
--

CREATE TABLE `supir` (
  `id` int(11) NOT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `no_polisi` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supir`
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
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `data_peminjam`
--
ALTER TABLE `data_peminjam`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD UNIQUE KEY `no_polisi` (`no_polisi`);

--
-- Indeks untuk tabel `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `supir`
--
ALTER TABLE `supir`
  ADD PRIMARY KEY (`id`),
  ADD KEY `no_polisi` (`no_polisi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `data_peminjam`
--
ALTER TABLE `data_peminjam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `supir`
--
ALTER TABLE `supir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `supir`
--
ALTER TABLE `supir`
  ADD CONSTRAINT `supir_ibfk_1` FOREIGN KEY (`no_polisi`) REFERENCES `kendaraan` (`no_polisi`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
