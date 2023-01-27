-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2023 at 09:21 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pcs_0574`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `nama`) VALUES
(64, 'Bayu@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Bayu'),
(65, 'sinang@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'sinang');

-- --------------------------------------------------------

--
-- Table structure for table `item_transaksi`
--

CREATE TABLE `item_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_saat_transaksi` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_transaksi`
--

INSERT INTO `item_transaksi` (`id`, `transaksi_id`, `produk_id`, `qty`, `harga_saat_transaksi`, `sub_total`) VALUES
(7, 19, 26, 2, 2000, 4000),
(9, 19, 26, 2, 2000, 4000),
(10, 29, 25, 2, 18000, 36000),
(11, 30, 25, 3, 18000, 54000),
(12, 31, 25, 2, 18000, 36000),
(13, 32, 25, 2, 18000, 36000),
(14, 33, 25, 2, 18000, 36000),
(15, 33, 24, 1, 18000, 18000),
(25, 39, 24, 1, 18000, 18000),
(26, 39, 26, 1, 13000, 13000),
(27, 39, 28, 1, 500, 500),
(28, 39, 32, 1, 1000, 1000),
(29, 40, 26, 1, 13000, 13000),
(30, 40, 32, 1, 1000, 1000),
(31, 40, 25, 1, 18000, 18000),
(32, 40, 28, 1, 500, 500),
(33, 41, 25, 1, 18000, 18000),
(34, 41, 26, 1, 13000, 13000),
(35, 41, 28, 1, 500, 500),
(36, 42, 24, 1, 18000, 18000),
(37, 42, 25, 1, 18000, 18000),
(38, 42, 32, 1, 1000, 1000),
(39, 42, 26, 1, 13000, 13000),
(40, 42, 28, 1, 500, 500);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `admin_id`, `nama`, `harga`, `stok`) VALUES
(24, 65, 'soto', 18000, 3),
(25, 65, 'bakso', 18000, -11),
(26, 65, 'nasgor', 13000, 16),
(28, 65, 'kerupuk', 500, 4),
(32, 65, 'Gorengan', 1000, 5);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `admin_id`, `tanggal`, `total`) VALUES
(18, 64, '2022-11-24 09:09:09', 200000),
(19, 64, '2022-11-24 09:09:18', 20000),
(20, 65, '2022-11-24 09:09:22', 20000),
(39, 65, '2023-01-27 08:11:35', 32500),
(40, 65, '2023-01-27 08:11:53', 32500),
(41, 65, '2023-01-27 08:44:02', 31500),
(42, 65, '2023-01-27 08:44:16', 50500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_transaksi_produk_id` (`produk_id`),
  ADD KEY `fk_transaksi_id` (`transaksi_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id` (`admin_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaksi_admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD CONSTRAINT `fk_item_transaksi_produk_id` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`),
  ADD CONSTRAINT `fk_transaksi_id` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
