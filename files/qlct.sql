-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 23, 2021 lúc 07:44 PM
-- Phiên bản máy phục vụ: 10.4.18-MariaDB
-- Phiên bản PHP: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlct`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dayoff`
--

CREATE TABLE `dayoff` (
  `id` int(11) NOT NULL,
  `startday` date NOT NULL,
  `endday` date NOT NULL,
  `iduser` int(11) NOT NULL,
  `reson` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `department`
--

CREATE TABLE `department` (
  `idpb` int(11) NOT NULL,
  `namepb` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idtp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `history`
--

CREATE TABLE `history` (
  `idhis` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `idnv` int(11) NOT NULL,
  `idtp` int(11) NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `task`
--

CREATE TABLE `task` (
  `idtask` int(11) NOT NULL,
  `nametask` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idtp` int(11) NOT NULL,
  `idnv` int(11) NOT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `progress` int(11) NOT NULL,
  `deadline` date NOT NULL,
  `filedelivered` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `filesubmission` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `avatar` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idpb` int(11) NOT NULL,
  `numofdaysoff` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `dayoff`
--
ALTER TABLE `dayoff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ngaynghi` (`iduser`);

--
-- Chỉ mục cho bảng `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`idpb`),
  ADD KEY `truongphong` (`idtp`);

--
-- Chỉ mục cho bảng `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`idhis`),
  ADD KEY `idtask` (`idtask`),
  ADD KEY `nvhis` (`idnv`),
  ADD KEY `tphis` (`idtp`);

--
-- Chỉ mục cho bảng `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`idtask`),
  ADD KEY `idtp` (`idtp`),
  ADD KEY `idnv` (`idnv`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idpb` (`idpb`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `dayoff`
--
ALTER TABLE `dayoff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `department`
--
ALTER TABLE `department`
  MODIFY `idpb` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `history`
--
ALTER TABLE `history`
  MODIFY `idhis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `task`
--
ALTER TABLE `task`
  MODIFY `idtask` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `dayoff`
--
ALTER TABLE `dayoff`
  ADD CONSTRAINT `ngaynghi` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `truongphong` FOREIGN KEY (`idtp`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `idtask` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`),
  ADD CONSTRAINT `nvhis` FOREIGN KEY (`idnv`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `tphis` FOREIGN KEY (`idtp`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `idnv` FOREIGN KEY (`idnv`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `idtp` FOREIGN KEY (`idtp`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `idpb` FOREIGN KEY (`idpb`) REFERENCES `department` (`idpb`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
