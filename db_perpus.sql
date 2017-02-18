-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2016 at 05:48 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpus`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_anggota`
--

CREATE TABLE `tbl_anggota` (
  `nim` int(11) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tgl_lahir` varchar(15) NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `thn_masuk` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_anggota`
--

INSERT INTO `tbl_anggota` (`nim`, `nama`, `tempat_lahir`, `tgl_lahir`, `jk`, `prodi`, `thn_masuk`) VALUES
(2015804045, 'Wewen', 'Indramayu', '2015-11-01', 'P', 'Komputer Akuntansi', '2014'),
(2015804065, 'Iqbal Rizqi Purnama', 'Ciamis', '1996-05-03', 'L', 'Sistem Informasi', '2015');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_buku`
--

CREATE TABLE `tbl_buku` (
  `id` int(5) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(150) NOT NULL,
  `thn_terbit` varchar(4) NOT NULL,
  `isbn` varchar(25) NOT NULL,
  `jumlah_buku` int(3) NOT NULL,
  `lokasi` enum('rak1','rak2','rak3') NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_buku`
--

INSERT INTO `tbl_buku` (`id`, `judul`, `pengarang`, `penerbit`, `thn_terbit`, `isbn`, `jumlah_buku`, `lokasi`, `tgl_input`) VALUES
(7, 'matematika', 'kokom', 'Airlangga', '2002', '7502983457a2', 4, 'rak2', '2015-11-28 08:46:15'),
(8, 'PHP Dasar', 'Arfandi', 'Lokomedia', '2002', '813957108531', 2, 'rak3', '2015-12-02 02:41:35'),
(9, 'Samudra PHP', 'Solihin', 'Hamzah', '2010', 'ajfdklajs98345', 2, 'rak3', '2015-12-02 04:55:13'),
(10, 'JQuery Mudah', 'Habib Nas', 'Aerlangga', '2008', '12365480B', 2, 'rak1', '2016-10-24 05:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaksi`
--

CREATE TABLE `tbl_transaksi` (
  `id` int(5) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `nim` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tgl_pinjam` varchar(15) NOT NULL,
  `tgl_kembali` varchar(15) NOT NULL,
  `status` varchar(10) NOT NULL,
  `ket` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_transaksi`
--

INSERT INTO `tbl_transaksi` (`id`, `judul`, `nim`, `nama`, `tgl_pinjam`, `tgl_kembali`, `status`, `ket`) VALUES
(6, 'matematika', 2015804045, 'Wewen Nurwendi', '01-12-2015', '08-12-2015', 'Kembali', ''),
(7, 'matematika', 2015804045, 'Wewen Nurwendi', '01-11-2015', '08-11-2015', 'Pinjam', ''),
(8, 'matematika', 2015804045, 'Wewen Nurwendi', '01-12-2015', '15-12-2015', 'Kembali', ''),
(9, 'PHP Dasar', 2015804065, 'Iqbal Rizqi Purnama', '24-10-2016', '31-10-2016', 'Pinjam', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(3) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `level` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `nama`, `username`, `password`, `email`, `foto`, `level`) VALUES
(1, 'Iqbal Rizqi Purnama', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'iqbalrizqipurnama@gmail.com', 'avatar5.png', 'admin'),
(2, 'Wewen Nurwendi', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'wewen@gmail.com', 'avatar5.png', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `tbl_buku`
--
ALTER TABLE `tbl_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_buku`
--
ALTER TABLE `tbl_buku`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
