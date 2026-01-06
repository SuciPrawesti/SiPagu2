-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jan 2026 pada 03.40
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
-- Database: `db_sistem_honor_udinus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_jadwal`
--

CREATE TABLE `t_jadwal` (
  `id_jdwl` int(11) NOT NULL,
  `semester` varchar(5) NOT NULL,
  `kode_matkul` varchar(7) NOT NULL,
  `nama_matkul` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jml_mhs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_panitia`
--

CREATE TABLE `t_panitia` (
  `id_pnt` int(11) NOT NULL,
  `jbtn_pnt` varchar(100) NOT NULL,
  `honor_std` int(11) NOT NULL,
  `honor_p1` int(11) DEFAULT NULL,
  `honor_p2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_transaksi_honor_dosen`
--

CREATE TABLE `t_transaksi_honor_dosen` (
  `id_thd` int(11) NOT NULL,
  `semester` varchar(5) NOT NULL,
  `bulan` enum('januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember') NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `jml_tm` int(2) NOT NULL,
  `sks_tempuh` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_transaksi_pa_ta`
--

CREATE TABLE `t_transaksi_pa_ta` (
  `id_tpt` int(11) NOT NULL,
  `semester` varchar(5) NOT NULL,
  `periode_wisuda` enum('januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember') NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_panitia` int(11) NOT NULL,
  `jml_mhs_prodi` int(11) NOT NULL,
  `jml_mhs_bimbingan` int(11) NOT NULL,
  `prodi` varchar(10) NOT NULL,
  `jml_pgji_1` int(11) NOT NULL,
  `jml_pgji_2` int(11) NOT NULL,
  `ketua_pgji` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_transaksi_ujian`
--

CREATE TABLE `t_transaksi_ujian` (
  `id_tu` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `id_panitia` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jml_mhs_prodi` int(11) NOT NULL,
  `jml_mhs` int(11) NOT NULL,
  `jml_koreksi` int(11) NOT NULL,
  `jml_matkul` int(11) NOT NULL,
  `jml_pgws_pagi` int(11) NOT NULL,
  `jml_pgws_sore` int(11) NOT NULL,
  `jml_koor_pagi` int(11) NOT NULL,
  `jml_koor_sore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_user`
--

CREATE TABLE `t_user` (
  `id_user` int(11) NOT NULL,
  `npp_user` varchar(20) NOT NULL,
  `nik_user` char(16) NOT NULL,
  `npwp_user` varchar(20) NOT NULL,
  `norek_user` varchar(30) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `nohp_user` varchar(20) NOT NULL,
  `pw_user` varchar(32) NOT NULL,
  `role_user` enum('koordinator','admin','staff') NOT NULL,
  `honor_persks` int(8) NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_user`
--

INSERT INTO `t_user` (`id_user`, `npp_user`, `nik_user`, `npwp_user`, `norek_user`, `nama_user`, `nohp_user`, `pw_user`, `role_user`, `honor_persks`, `remember_token`) VALUES
(1, '0686.11.1995.071', '3374010101950001', '12.345.678.9-012.000', '1410001234567', 'Dr. Andi Prasetyo, M.Kom', '081234567801', 'f006bbb5314696993ac4b77c9eabc7e1', 'admin', 0, NULL),
(2, '0721.12.1998.034', '3374010202980002', '23.456.789.0-123.000', '1410002345678', 'Siti Rahmawati, M.T', '081234567802', '740b977c51fa5bfd17f8f23f809ee6d5', 'koordinator', 0, ''),
(3, '0815.10.2001.112', '3374010303010003', '34.567.890.1-234.000', ' 1410003456789', ' Budi Santoso, S.Kom ', ' 081234567803', 'd9dcc4af188a7a70fbd1d0294223cb06', 'staff', 0, 'c013f0511c3f7952c73d1f52da5fde7b7980a1edfe274c16e266f2c4298a16c2');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_jadwal`
--
ALTER TABLE `t_jadwal`
  ADD PRIMARY KEY (`id_jdwl`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `t_panitia`
--
ALTER TABLE `t_panitia`
  ADD PRIMARY KEY (`id_pnt`) USING BTREE;

--
-- Indeks untuk tabel `t_transaksi_honor_dosen`
--
ALTER TABLE `t_transaksi_honor_dosen`
  ADD PRIMARY KEY (`id_thd`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `t_transaksi_pa_ta`
--
ALTER TABLE `t_transaksi_pa_ta`
  ADD PRIMARY KEY (`id_tpt`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_panitia` (`id_panitia`);

--
-- Indeks untuk tabel `t_transaksi_ujian`
--
ALTER TABLE `t_transaksi_ujian`
  ADD PRIMARY KEY (`id_tu`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_panitia` (`id_panitia`);

--
-- Indeks untuk tabel `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_jadwal`
--
ALTER TABLE `t_jadwal`
  MODIFY `id_jdwl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_panitia`
--
ALTER TABLE `t_panitia`
  MODIFY `id_pnt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_transaksi_honor_dosen`
--
ALTER TABLE `t_transaksi_honor_dosen`
  MODIFY `id_thd` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_transaksi_pa_ta`
--
ALTER TABLE `t_transaksi_pa_ta`
  MODIFY `id_tpt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_transaksi_ujian`
--
ALTER TABLE `t_transaksi_ujian`
  MODIFY `id_tu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_jadwal`
--
ALTER TABLE `t_jadwal`
  ADD CONSTRAINT `t_jadwal_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `t_transaksi_honor_dosen`
--
ALTER TABLE `t_transaksi_honor_dosen`
  ADD CONSTRAINT `t_transaksi_honor_dosen_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `t_jadwal` (`id_jdwl`);

--
-- Ketidakleluasaan untuk tabel `t_transaksi_pa_ta`
--
ALTER TABLE `t_transaksi_pa_ta`
  ADD CONSTRAINT `t_transaksi_pa_ta_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`),
  ADD CONSTRAINT `t_transaksi_pa_ta_ibfk_3` FOREIGN KEY (`id_panitia`) REFERENCES `t_panitia` (`id_pnt`);

--
-- Ketidakleluasaan untuk tabel `t_transaksi_ujian`
--
ALTER TABLE `t_transaksi_ujian`
  ADD CONSTRAINT `t_transaksi_ujian_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`),
  ADD CONSTRAINT `t_transaksi_ujian_ibfk_4` FOREIGN KEY (`id_panitia`) REFERENCES `t_panitia` (`id_pnt`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
