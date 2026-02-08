-- Database: `quanlydatvesukien`
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Cấu trúc bảng `loaisukien`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `loaisukien`;
CREATE TABLE IF NOT EXISTS `LoaiSuKien` (
  `MaLoai` int(11) NOT NULL AUTO_INCREMENT,
  `TenLoai` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MoTa` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`MaLoai`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DỮ LIỆU DUY NHẤT ĐƯỢC GIỮ LẠI
INSERT INTO `LoaiSuKien` (`MaLoai`, `TenLoai`, `MoTa`) VALUES
(1, 'Âm nhạc', 'Các sự kiện liên quan đến âm nhạc, biểu diễn ca nhạc.'),
(2, 'Thể thao', 'Các sự kiện thi đấu, giải đấu thể thao.'),
(3, 'Nghệ thuật', 'Các sự kiện triển lãm, biểu diễn nghệ thuật, sân khấu.');

-- --------------------------------------------------------
-- Cấu trúc bảng `nguoidung`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `nguoidung`;
CREATE TABLE IF NOT EXISTS `NguoiDung` (
  `MaNguoiDung` int(11) NOT NULL AUTO_INCREMENT,
  `HoTen` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MatKhau` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SoDienThoai` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `VaiTro` enum('khachhang','admin') DEFAULT 'khachhang',
  `NgayTao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`MaNguoiDung`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `sukien`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `SuKien`;
CREATE TABLE IF NOT EXISTS `SuKien` (
  `MaSuKien` int(11) NOT NULL AUTO_INCREMENT,
  `TenSuKien` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MoTa` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DiaDiem` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `NgayGio` datetime NOT NULL,
  `HinhAnh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `MaLoai` int(11) DEFAULT NULL,
  `NgayTao` datetime DEFAULT current_timestamp(),
  `NgayBatDau` datetime DEFAULT NULL,
  `NgayKetThuc` datetime DEFAULT NULL,
  PRIMARY KEY (`MaSuKien`),
  KEY `MaLoai` (`MaLoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `ghe`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Ghe`;
CREATE TABLE IF NOT EXISTS `Ghe` (
  `MaGhe` int(11) NOT NULL AUTO_INCREMENT,
  `MaSuKien` int(11) NOT NULL,
  `DayGhe` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SoGhe` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `TrangThai` enum('trong','dat') DEFAULT 'trong',
  PRIMARY KEY (`MaGhe`),
  KEY `MaSuKien` (`MaSuKien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `ve`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Ve`;
CREATE TABLE IF NOT EXISTS `Ve` (
  `MaVe` int(11) NOT NULL AUTO_INCREMENT,
  `MaSuKien` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `MaGhe` int(11) NOT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `TrangThai` enum('chuathanhtoan','dathanhtoan','huy') DEFAULT 'chuathanhtoan',
  `SoTienThanhToan` decimal(10,2) DEFAULT NULL,
  `PhuongThuc` enum('momo','vnpay','paypal','thetinDung') DEFAULT NULL,
  `MaThanhToanGateway` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgayThanhToan` datetime DEFAULT NULL,
  `NgayDat` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`MaVe`),
  KEY `MaSuKien` (`MaSuKien`),
  KEY `MaNguoiDung` (`MaNguoiDung`),
  KEY `MaGhe` (`MaGhe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Ràng buộc (Constraints)
-- --------------------------------------------------------
ALTER TABLE `Ghe`
  ADD CONSTRAINT `ghe_ibfk_1` FOREIGN KEY (`MaSuKien`) REFERENCES `SuKien` (`MaSuKien`);

ALTER TABLE `SuKien`
  ADD CONSTRAINT `sukien_ibfk_1` FOREIGN KEY (`MaLoai`) REFERENCES `LoaiSuKien` (`MaLoai`);

ALTER TABLE `Ve`
  ADD CONSTRAINT `ve_ibfk_1` FOREIGN KEY (`MaSuKien`) REFERENCES `SuKien` (`MaSuKien`),
  ADD CONSTRAINT `ve_ibfk_2` FOREIGN KEY (`MaNguoiDung`) REFERENCES `NguoiDung` (`MaNguoiDung`),
  ADD CONSTRAINT `ve_ibfk_3` FOREIGN KEY (`MaGhe`) REFERENCES `Ghe` (`MaGhe`);

COMMIT;