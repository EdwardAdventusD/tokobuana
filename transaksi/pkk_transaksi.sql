-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2024 at 10:31 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkk_transaksi`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id_user` int NOT NULL,
  `username` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','Kasir','User') COLLATE utf8mb4_general_ci NOT NULL,
  `id_member` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id_user`, `username`, `password`, `role`, `id_member`) VALUES
(1, 'bryan', '$2y$10$scqSM1aqumJhFI9yEwxfD.ciOLbHXwr1Z6Rq71xHdZ0LSPiv6x/vi', 'Admin', 1),
(2, 'edward', '$2y$10$IACcEBoSqBHoJrcmIClrluj5UmTsuPBiJQjb8PAedN2r5KYSa8Uxy', 'User', 2),
(3, 'cris', '$2y$10$VZvNCo7cjrV9iRFlsU6vEu8yqvX1a7/iZdMH8X7.IqIOcqxQXcpo2', 'Kasir', 3);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id_member` int NOT NULL,
  `nama_member` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_member` varchar(99) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telepon_member` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_member` varchar(99) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id_member`, `nama_member`, `alamat_member`, `telepon_member`, `email_member`) VALUES
(1, 'Bryan Eka Santoso', 'Kedamean', '081225022214', 'bryaneka349@gmail.com'),
(2, 'Edward Adventus Dembo', 'Krian', '081', 'edward@gmail.com'),
(3, 'Christoper Angel', 'Krian', '0812', 'christoper@gmail.com'),
(4, 'Akbar Mustofa ', 'Sidomojo', '0896', 'akbar@gmail.com'),
(5, 'Akbar', 'Krian', '08419', 'akbar@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `id_toko` int NOT NULL,
  `nama_toko` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_toko` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `telepon_toko` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_pemilik` varchar(99) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`id_toko`, `nama_toko`, `alamat_toko`, `telepon_toko`, `nama_pemilik`) VALUES
(1, 'Toko Bu Ana', 'Mojosari', '081', 'Bu Ana');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_member` int NOT NULL,
  `id_toko` int NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_harga` int NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `keterangan` varchar(99) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Lunas','Belum Lunas') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_member`, `id_toko`, `tanggal_transaksi`, `total_harga`, `tanggal_bayar`, `keterangan`, `status`) VALUES
(1, 2, 1, '2024-02-07', 100000, NULL, NULL, 'Belum Lunas'),
(2, 1, 1, '2024-02-07', 20000, '2024-02-07', NULL, 'Lunas'),
(3, 3, 1, '2024-02-06', 20000, '2024-02-09', 'Beras', 'Lunas'),
(4, 1, 1, '2024-02-16', 20000, '2024-02-16', NULL, 'Lunas'),
(5, 4, 1, '2024-02-16', 55000, NULL, 'sudah membayar ', 'Lunas'),
(6, 5, 1, '2024-02-17', 10000, '2024-02-16', NULL, 'Lunas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_member` (`id_member`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_member`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id_toko`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_member` (`id_member`),
  ADD KEY `id_toko` (`id_toko`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id_member` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `toko`
--
ALTER TABLE `toko`
  MODIFY `id_toko` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `member` (`id_member`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `member` (`id_member`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
