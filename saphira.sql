-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th12 01, 2025 lúc 12:17 PM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `saphira`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the_nuoc_hoa`
--

CREATE TABLE `bien_the_nuoc_hoa` (
  `ma_bien_the` int NOT NULL,
  `ma_nuoc_hoa` int NOT NULL,
  `dung_tich_ml` int NOT NULL,
  `gia_niem_yet` decimal(10,2) DEFAULT NULL,
  `gia_khuyen_mai` decimal(10,2) DEFAULT NULL,
  `so_luong_ton` int DEFAULT '0',
  `ma_sku` varchar(100) DEFAULT NULL,
  `trang_thai` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the_nuoc_hoa`
--

INSERT INTO `bien_the_nuoc_hoa` (`ma_bien_the`, `ma_nuoc_hoa`, `dung_tich_ml`, `gia_niem_yet`, `gia_khuyen_mai`, `so_luong_ton`, `ma_sku`, `trang_thai`) VALUES
(1, 1, 10, 550000.00, NULL, 50, NULL, 1),
(2, 1, 30, 1650000.00, NULL, 30, NULL, 1),
(3, 1, 80, 3800000.00, NULL, 20, NULL, 1),
(4, 2, 10, 480000.00, NULL, 50, NULL, 1),
(5, 2, 30, 1450000.00, NULL, 30, NULL, 1),
(6, 2, 80, 3500000.00, NULL, 20, NULL, 1),
(7, 3, 10, 350000.00, NULL, 60, NULL, 1),
(8, 3, 30, 950000.00, NULL, 40, NULL, 1),
(9, 3, 80, 2100000.00, NULL, 25, NULL, 1),
(10, 4, 10, 520000.00, NULL, 50, NULL, 1),
(11, 4, 30, 1550000.00, NULL, 30, NULL, 1),
(12, 4, 80, 3600000.00, NULL, 20, NULL, 1),
(13, 5, 10, 420000.00, NULL, 50, NULL, 1),
(14, 5, 30, 1250000.00, NULL, 35, NULL, 1),
(15, 5, 80, 2900000.00, NULL, 20, NULL, 1),
(16, 6, 10, 450000.00, NULL, 45, NULL, 1),
(17, 6, 30, 1350000.00, NULL, 30, NULL, 1),
(18, 6, 80, 3100000.00, NULL, 15, NULL, 1),
(19, 7, 10, 250000.00, NULL, 60, NULL, 1),
(20, 7, 30, 750000.00, NULL, 45, NULL, 1),
(21, 7, 80, 1800000.00, NULL, 30, NULL, 1),
(22, 8, 10, 150000.00, NULL, 100, NULL, 1),
(23, 8, 30, 450000.00, NULL, 80, NULL, 1),
(24, 8, 80, 950000.00, NULL, 50, NULL, 1),
(25, 9, 10, 650000.00, NULL, 40, NULL, 1),
(26, 9, 30, 1900000.00, NULL, 25, NULL, 1),
(27, 9, 80, 4500000.00, NULL, 10, NULL, 1),
(28, 10, 10, 320000.00, NULL, 50, NULL, 1),
(29, 10, 30, 920000.00, NULL, 35, NULL, 1),
(30, 10, 80, 2200000.00, NULL, 20, NULL, 1),
(31, 11, 10, 120000.00, NULL, 50, NULL, 1),
(32, 11, 30, 350000.00, NULL, 30, NULL, 1),
(33, 11, 80, 800000.00, NULL, 20, NULL, 1),
(34, 12, 10, 120000.00, NULL, 50, NULL, 1),
(35, 12, 30, 350000.00, NULL, 30, NULL, 1),
(36, 12, 80, 800000.00, NULL, 20, NULL, 1),
(37, 13, 10, 400000.00, NULL, 55, NULL, 1),
(38, 13, 30, 1200000.00, NULL, 35, NULL, 1),
(39, 13, 80, 2800000.00, NULL, 25, NULL, 1),
(40, 14, 10, 410000.00, NULL, 50, NULL, 1),
(41, 14, 30, 1220000.00, NULL, 30, NULL, 1),
(42, 14, 80, 2850000.00, NULL, 20, NULL, 1),
(43, 15, 10, 500000.00, NULL, 50, NULL, 1),
(44, 15, 30, 1500000.00, NULL, 30, NULL, 1),
(45, 15, 80, 3500000.00, NULL, 20, NULL, 1),
(46, 16, 10, 850000.00, NULL, 50, NULL, 1),
(47, 16, 30, 2450000.00, NULL, 30, NULL, 1),
(48, 16, 100, 7200000.00, NULL, 10, NULL, 1),
(49, 17, 10, 950000.00, NULL, 40, NULL, 1),
(50, 17, 30, 2800000.00, NULL, 20, NULL, 1),
(51, 17, 70, 6800000.00, NULL, 10, NULL, 1),
(52, 18, 10, 380000.00, NULL, 60, NULL, 1),
(53, 18, 30, 1100000.00, NULL, 40, NULL, 1),
(54, 18, 100, 2600000.00, NULL, 25, NULL, 1),
(55, 19, 10, 900000.00, NULL, 30, NULL, 1),
(56, 19, 30, 2600000.00, NULL, 20, NULL, 1),
(57, 19, 50, 5500000.00, NULL, 10, NULL, 1),
(58, 20, 10, 420000.00, NULL, 50, NULL, 1),
(59, 20, 30, 1250000.00, NULL, 35, NULL, 1),
(60, 20, 125, 3200000.00, NULL, 20, NULL, 1),
(61, 21, 10, 250000.00, NULL, 100, NULL, 1),
(62, 21, 30, 750000.00, NULL, 60, NULL, 1),
(63, 21, 100, 1900000.00, NULL, 50, NULL, 1),
(64, 22, 10, 750000.00, NULL, 40, NULL, 1),
(65, 22, 30, 2100000.00, NULL, 25, NULL, 1),
(66, 22, 100, 6200000.00, NULL, 15, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_chi_tiet` int NOT NULL,
  `ma_don_hang` int NOT NULL,
  `ma_bien_the` int NOT NULL,
  `so_luong` int NOT NULL,
  `don_gia` decimal(10,2) DEFAULT NULL,
  `ten_nuoc_hoa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_chi_tiet`, `ma_don_hang`, `ma_bien_the`, `so_luong`, `don_gia`, `ten_nuoc_hoa`) VALUES
(1, 1, 1, 1, 3200000.00, 'Chanel No.5 Eau De Parfum'),
(2, 1, 7, 2, 600000.00, 'Bleu De Chanel Eau De Parfum'),
(3, 2, 31, 1, 800000.00, 'Perfume A Sample'),
(4, 3, 10, 1, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(5, 4, 10, 2, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(6, 5, 10, 1, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(7, 6, 10, 1, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(8, 7, 19, 3, 250000.00, 'Versace Eros Flame'),
(9, 7, 10, 2, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(10, 8, 10, 3, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(11, 9, 11, 3, 1550000.00, 'Bleu De Chanel Eau De Parfum'),
(12, 9, 7, 2, 350000.00, 'Gucci Guilty Pour Homme'),
(13, 9, 2, 2, 1650000.00, 'Chanel No.5 Eau De Parfum'),
(14, 10, 10, 24, 520000.00, 'Bleu De Chanel Eau De Parfum'),
(15, 11, 10, 1, 520000.00, 'Bleu De Chanel Eau De Parfum');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ma_danh_gia` int NOT NULL,
  `ma_nguoi_dung` int NOT NULL,
  `ma_nuoc_hoa` int NOT NULL,
  `so_sao` int DEFAULT NULL,
  `tieu_de` varchar(255) DEFAULT NULL,
  `noi_dung` text,
  `do_ben` int DEFAULT NULL,
  `do_toa_huong` int DEFAULT NULL,
  `trang_thai` tinyint DEFAULT '1',
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `duong_dan` varchar(255) DEFAULT NULL,
  `ma_danh_muc_cha` int DEFAULT NULL,
  `mo_ta` text,
  `hinh_anh` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `duong_dan`, `ma_danh_muc_cha`, `mo_ta`, `hinh_anh`) VALUES
(1, 'Nước hoa Nam', 'nuoc-hoa-nam', NULL, NULL, NULL),
(2, 'Nước hoa Nữ', 'nuoc-hoa-nu', NULL, NULL, NULL),
(3, 'Unisex', 'unisex', NULL, NULL, NULL),
(4, 'Set quà tặng', 'set-qua', NULL, NULL, NULL),
(5, 'Mini / Sample', 'sample', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_sach_yeu_thich`
--

CREATE TABLE `danh_sach_yeu_thich` (
  `ma_yeu_thich` int NOT NULL,
  `ma_nguoi_dung` int NOT NULL,
  `ma_nuoc_hoa` int NOT NULL,
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_giao_hang`
--

CREATE TABLE `dia_chi_giao_hang` (
  `ma_dia_chi` int NOT NULL,
  `ma_nguoi_dung` int NOT NULL,
  `ho_ten_nguoi_nhan` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi_chi_tiet` text,
  `thanh_pho` varchar(100) DEFAULT NULL,
  `quan_huyen` varchar(100) DEFAULT NULL,
  `phuong_xa` varchar(100) DEFAULT NULL,
  `mac_dinh` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `dia_chi_giao_hang`
--

INSERT INTO `dia_chi_giao_hang` (`ma_dia_chi`, `ma_nguoi_dung`, `ho_ten_nguoi_nhan`, `so_dien_thoai`, `dia_chi_chi_tiet`, `thanh_pho`, `quan_huyen`, `phuong_xa`, `mac_dinh`) VALUES
(7, 2, 'Nguyễn Văn A', '0901234567', '123 Đường Láng, Phường Láng Thượng', 'Hà Nội', 'Quận Đống Đa', 'Phường Láng Thượng', 1),
(8, 2, 'Nguyễn Văn A', '0901234567', '56 Nguyễn Trãi, Phường Thượng Đình', 'Hà Nội', 'Quận Thanh Xuân', 'Phường Thượng Đình', 0),
(9, 2, 'Nguyễn Thị Hương', '0901234568', 'Ký túc xá Mỹ Đình, Phường Mỹ Đình 1', 'Hà Nội', 'Quận Nam Từ Liêm', 'Phường Mỹ Đình 1', 0),
(10, 3, 'Trần Thị B', '0912345678', '289A Khuất Duy Tiến, Phường Nhân Chính', 'Hà Nội', 'Quận Thanh Xuân', 'Phường Nhân Chính', 1),
(11, 3, 'Trần Thị B', '0912345678', 'Tòa nhà Keangnam Landmark 72, Phạm Hùng', 'Hà Nội', 'Quận Nam Từ Liêm', 'Phường Mễ Trì', 0),
(12, 2, 'Lê Văn C', '0923456789', '180 Cầu Giấy, Phường Quan Hoa', 'Hà Nội', 'Quận Cầu Giấy', 'Phường Quan Hoa', 0),
(13, 3, 'Phạm Thị D', '0934567890', 'Số 10 Lê Văn Lương, Phường Nhân Chính', 'Hà Nội', 'Quận Thanh Xuân', 'Phường Nhân Chính', 0),
(14, 2, 'Nguyễn Văn Nam', '0909876543', '475A Điện Biên Phủ, Phường 25', 'TP. Hồ Chí Minh', 'Quận Bình Thạnh', 'Phường 25', 0),
(15, 3, 'Trần Ngọc Ánh', '0919876543', '123 Nguyễn Thị Minh Khai, Phường 6', 'TP. Hồ Chí Minh', 'Quận 3', 'Phường 6', 0),
(16, 2, 'Hoàng Thị Mai', '0905555666', 'Khu dân cư An Hải Bắc, Phường An Hải Bắc', 'Đà Nẵng', 'Quận Sơn Trà', 'Phường An Hải Bắc', 0),
(17, 4, 'Trong Trần', '0358791554', 'Xã Bình Hưng', NULL, NULL, NULL, 0),
(18, 4, 'Trong Trần', '0358791554', 'Xã Bình Hưng', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` int NOT NULL,
  `ma_nguoi_dung` int NOT NULL,
  `trang_thai_don` enum('cho_xac_nhan','da_xac_nhan','dang_giao','da_giao','da_huy') DEFAULT 'cho_xac_nhan',
  `tong_tien` decimal(10,2) NOT NULL,
  `ma_phuong_thuc_thanh_toan` int DEFAULT NULL,
  `ma_dia_chi_giao_hang` int DEFAULT NULL,
  `ma_giam_gia` int DEFAULT NULL,
  `ghi_chu` text,
  `thoi_gian_dat` datetime DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_nguoi_dung`, `trang_thai_don`, `tong_tien`, `ma_phuong_thuc_thanh_toan`, `ma_dia_chi_giao_hang`, `ma_giam_gia`, `ghi_chu`, `thoi_gian_dat`, `thoi_gian_cap_nhat`) VALUES
(1, 2, 'da_giao', 7600000.00, 1, NULL, NULL, NULL, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(2, 3, 'cho_xac_nhan', 900000.00, 1, NULL, NULL, NULL, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(3, 4, 'cho_xac_nhan', 520000.00, 1, NULL, NULL, '', '2025-11-21 23:19:03', '2025-11-21 23:19:03'),
(4, 4, 'cho_xac_nhan', 1040000.00, 1, NULL, NULL, '', '2025-11-22 14:16:02', '2025-11-22 14:16:02'),
(5, 4, 'cho_xac_nhan', 520000.00, 1, NULL, NULL, '', '2025-11-22 14:19:30', '2025-11-22 14:19:30'),
(6, 4, 'cho_xac_nhan', 520000.00, 1, NULL, NULL, '', '2025-11-22 14:36:21', '2025-11-22 14:36:21'),
(7, 4, 'cho_xac_nhan', 1790000.00, 1, NULL, NULL, '', '2025-11-22 14:40:39', '2025-11-22 14:40:39'),
(8, 4, 'cho_xac_nhan', 1560000.00, 1, NULL, NULL, '', '2025-11-22 15:31:31', '2025-11-22 15:31:31'),
(9, 4, 'cho_xac_nhan', 8650000.00, 1, 17, NULL, '', '2025-12-01 19:01:31', '2025-12-01 19:01:31'),
(10, 4, 'cho_xac_nhan', 12480000.00, 1, 18, NULL, '', '2025-12-01 19:07:26', '2025-12-01 19:07:26'),
(11, 4, 'cho_xac_nhan', 520000.00, 1, 18, NULL, '', '2025-12-01 19:13:28', '2025-12-01 19:13:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dung_tich_loc`
--

CREATE TABLE `dung_tich_loc` (
  `ma_dung_tich` int NOT NULL,
  `dung_tich_ml` int NOT NULL,
  `hien_thi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `dung_tich_loc`
--

INSERT INTO `dung_tich_loc` (`ma_dung_tich`, `dung_tich_ml`, `hien_thi`) VALUES
(1, 10, '10ml (Chiết)'),
(2, 30, '30ml'),
(3, 50, '50ml'),
(4, 75, '75ml'),
(5, 100, '100ml'),
(6, 150, '150ml+');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gioi_tinh`
--

CREATE TABLE `gioi_tinh` (
  `ma_gioi_tinh` int NOT NULL,
  `ten_gioi_tinh` varchar(50) NOT NULL,
  `duong_dan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `gioi_tinh`
--

INSERT INTO `gioi_tinh` (`ma_gioi_tinh`, `ten_gioi_tinh`, `duong_dan`) VALUES
(1, 'Nam', 'nam'),
(2, 'Nữ', 'nu'),
(3, 'Unisex', 'unisex');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `ma_gio_hang` int NOT NULL,
  `ma_nguoi_dung` int NOT NULL,
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `gio_hang`
--

INSERT INTO `gio_hang` (`ma_gio_hang`, `ma_nguoi_dung`, `thoi_gian_tao`, `thoi_gian_cap_nhat`) VALUES
(1, 2, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(2, 3, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(7, 4, '2025-11-21 23:18:47', '2025-11-21 23:18:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hinh_anh_nuoc_hoa`
--

CREATE TABLE `hinh_anh_nuoc_hoa` (
  `ma_hinh_anh` int NOT NULL,
  `ma_nuoc_hoa` int NOT NULL,
  `duong_dan_hinh` varchar(500) NOT NULL,
  `van_ban_thay_the` varchar(255) DEFAULT NULL,
  `loai_hinh_anh` enum('anh_nho','anh_lon','anh_chai') DEFAULT 'anh_lon',
  `thu_tu` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `hinh_anh_nuoc_hoa`
--

INSERT INTO `hinh_anh_nuoc_hoa` (`ma_hinh_anh`, `ma_nuoc_hoa`, `duong_dan_hinh`, `van_ban_thay_the`, `loai_hinh_anh`, `thu_tu`) VALUES
(1, 1, '/duan1/img/chanel_no5.jpg', NULL, 'anh_lon', 0),
(2, 2, '/duan1/img/dior_jadore.jpg', NULL, 'anh_lon', 0),
(3, 3, '/duan1/img/gucci_guilty.jpg', NULL, 'anh_lon', 0),
(4, 4, '/duan1/img/bleu_de_chanel.jpg', NULL, 'anh_lon', 0),
(5, 5, '/duan1/img/lancome_lveb.jpg', NULL, 'anh_lon', 0),
(6, 6, '/duan1/img/ysl_libre.jpg', NULL, 'anh_lon', 0),
(7, 7, '/duan1/img/versace_eros_flame.jpg', NULL, 'anh_lon', 0),
(8, 8, '/duan1/img/ck_one.jpg', NULL, 'anh_lon', 0),
(9, 9, '/duan1/img/tomford_black_orchid.jpg', NULL, 'anh_lon', 0),
(10, 10, '/duan1/img/prada_luna_rossa.jpg', NULL, 'anh_lon', 0),
(11, 16, '/duan1/img/creed_aventus.jpg', NULL, 'anh_lon', 0),
(12, 17, '/duan1/img/mfk_br540.jpg', NULL, 'anh_lon', 0),
(13, 18, '/duan1/img/narciso_h.jpg', NULL, 'anh_lon', 0),
(14, 19, '/duan1/img/kilian_gggb.jpg', NULL, 'anh_lon', 0),
(15, 20, '/duan1/img/jpg_elixir.jpg', NULL, 'anh_lon', 0),
(16, 21, '/duan1/img/gio_trang.jpg', NULL, 'anh_lon', 0),
(17, 22, '/duan1/img/lelabo_s33.jpg', NULL, 'anh_lon', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoang_gia`
--

CREATE TABLE `khoang_gia` (
  `ma_khoang` int NOT NULL,
  `ten_khoang` varchar(100) NOT NULL,
  `gia_tu` decimal(12,0) DEFAULT '0',
  `gia_den` decimal(12,0) DEFAULT NULL,
  `thu_tu` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `khoang_gia`
--

INSERT INTO `khoang_gia` (`ma_khoang`, `ten_khoang`, `gia_tu`, `gia_den`, `thu_tu`) VALUES
(1, 'Dưới 500.000đ', 0, 500000, 1),
(2, '500.000đ - 1 triệu', 500000, 1000000, 2),
(3, '1 - 2 triệu', 1000000, 2000000, 3),
(4, '2 - 5 triệu', 2000000, 5000000, 4),
(5, 'Trên 5 triệu', 5000000, NULL, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mat_hang_gio_hang`
--

CREATE TABLE `mat_hang_gio_hang` (
  `ma_mat_hang` int NOT NULL,
  `ma_gio_hang` int NOT NULL,
  `ma_bien_the` int NOT NULL,
  `so_luong` int NOT NULL DEFAULT '1',
  `gia` decimal(10,2) DEFAULT NULL,
  `thoi_gian_them` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `mat_hang_gio_hang`
--

INSERT INTO `mat_hang_gio_hang` (`ma_mat_hang`, `ma_gio_hang`, `ma_bien_the`, `so_luong`, `gia`, `thoi_gian_them`) VALUES
(1, 1, 1, 1, 3200000.00, '2025-11-21 23:10:27'),
(2, 1, 7, 2, 600000.00, '2025-11-21 23:10:27'),
(3, 2, 31, 1, 800000.00, '2025-11-21 23:10:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ma_giam_gia`
--

CREATE TABLE `ma_giam_gia` (
  `ma_giam_gia_id` int NOT NULL,
  `ma_giam_gia` varchar(100) NOT NULL,
  `loai_giam_gia` enum('phan_tram','tien_mat') NOT NULL,
  `gia_tri_giam` decimal(10,2) NOT NULL,
  `ngay_bat_dau` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `gioi_han_su_dung` int DEFAULT NULL,
  `so_lan_da_su_dung` int DEFAULT '0',
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `ma_nguoi_dung` int NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `vai_tro` enum('quan_tri','khach_hang','nhan_vien') DEFAULT 'khach_hang',
  `trang_thai` tinyint DEFAULT '1',
  `anh_dai_dien` varchar(500) DEFAULT NULL,
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `vai_tro`, `trang_thai`, `anh_dai_dien`, `thoi_gian_tao`, `thoi_gian_cap_nhat`) VALUES
(1, 'Quản Trị', 'admin@saphira.com', '202cb962ac59075b964b07152d234b70', NULL, 'quan_tri', 1, NULL, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(2, 'Nguyễn Văn A', 'user1@saphira.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'khach_hang', 1, NULL, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(3, 'Trần Thị B', 'user2@saphira.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'khach_hang', 1, NULL, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(4, 'Trong Trần', 'bpham9641@gmail.com', '202cb962ac59075b964b07152d234b70', '0358791554', 'quan_tri', 1, NULL, '2025-11-21 23:18:41', '2025-11-25 13:42:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nuoc_hoa`
--

CREATE TABLE `nuoc_hoa` (
  `ma_nuoc_hoa` int NOT NULL,
  `ten_nuoc_hoa` varchar(255) NOT NULL,
  `duong_dan` varchar(255) DEFAULT NULL,
  `mo_ta_chi_tiet` text,
  `mo_ta_ngan` text,
  `nong_do` enum('EDT','EDP','Parfum','Cologne') DEFAULT 'EDT',
  `dung_tich_ml` int DEFAULT NULL,
  `xuat_xu` varchar(100) DEFAULT NULL,
  `nam_phat_hanh` year DEFAULT NULL,
  `gia_niem_yet` decimal(10,2) DEFAULT NULL,
  `gia_khuyen_mai` decimal(10,2) DEFAULT NULL,
  `ma_danh_muc` int DEFAULT NULL,
  `ma_thuong_hieu` int DEFAULT NULL,
  `ma_gioi_tinh` int DEFAULT NULL,
  `trang_thai` tinyint DEFAULT '1',
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nuoc_hoa`
--

INSERT INTO `nuoc_hoa` (`ma_nuoc_hoa`, `ten_nuoc_hoa`, `duong_dan`, `mo_ta_chi_tiet`, `mo_ta_ngan`, `nong_do`, `dung_tich_ml`, `xuat_xu`, `nam_phat_hanh`, `gia_niem_yet`, `gia_khuyen_mai`, `ma_danh_muc`, `ma_thuong_hieu`, `ma_gioi_tinh`, `trang_thai`, `thoi_gian_tao`, `thoi_gian_cap_nhat`) VALUES
(1, 'Chanel No.5 Eau De Parfum', 'chanel-no5', 'Chanel No.5 là biểu tượng...', 'Chanel No.5 - hương thơm kinh điển...', 'EDP', NULL, 'Pháp', '1921', 550000.00, 440000.00, 2, 1, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:49:00'),
(2, 'Dior J\'adore Eau De Parfum', 'dior-jadore', 'J\'adore là biểu tượng...', 'Dior J\'adore - hương ngọt ngào...', 'EDP', NULL, 'Pháp', '1999', 480000.00, 384000.00, 2, 2, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:49:00'),
(3, 'Gucci Guilty Pour Homme', 'gucci-guilty-pour-homme', 'Hương gỗ phương Đông...', 'Gucci Guilty - nam tính...', 'EDP', NULL, 'Ý', '2011', 350000.00, 280000.00, 1, 3, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:49:00'),
(4, 'Bleu De Chanel Eau De Parfum', 'bleu-de-chanel', 'Hương tươi mát...', 'Bleu De Chanel - tươi mát...', 'EDP', NULL, 'Pháp', '2014', 520000.00, 416000.00, 1, 1, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:49:00'),
(5, 'Lancôme La Vie Est Belle', 'lancome-la-vie-est-belle', 'Hương vani...', 'La Vie Est Belle - hương ngọt ngào...', 'EDP', NULL, 'Pháp', '2012', 420000.00, 420000.00, 2, 4, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(6, 'YSL Libre Eau De Parfum', 'ysl-libre', 'Kết hợp hoa và cam quýt...', 'YSL Libre - mạnh mẽ...', 'EDP', NULL, 'Pháp', '2019', 450000.00, 450000.00, 3, 5, 3, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(7, 'Versace Eros Flame', 'versace-eros-flame', 'Phiên bản ấm áp...', 'Versace Eros Flame - nam tính...', 'EDP', NULL, 'Ý', '2018', 250000.00, 250000.00, 1, 6, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(8, 'Calvin Klein CK One', 'ck-one', 'Biểu tượng unisex...', 'CK One - unisex tươi mát...', 'EDT', NULL, 'Mỹ', '1994', 150000.00, 150000.00, 3, 7, 3, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(9, 'Tom Ford Black Orchid', 'tomford-black-orchid', 'Mùi hương độc đáo...', 'Tom Ford Black Orchid - sang trọng...', 'EDP', NULL, 'Mỹ', '2006', 650000.00, 650000.00, 2, 8, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(10, 'Prada Luna Rossa', 'prada-luna-rossa', 'Hương thảo mộc...', 'Prada Luna Rossa - thể thao...', 'EDT', NULL, 'Ý', '2012', 320000.00, 320000.00, 1, 9, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(11, 'Perfume A Sample', 'perfume-a', 'Sản phẩm mẫu.', 'Hàng mẫu Perfume A.', 'EDT', NULL, 'Pháp', '2025', 120000.00, 120000.00, 3, 1, 3, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(12, 'Perfume B Sample', 'perfume-b', 'Sản phẩm mẫu.', 'Hàng mẫu Perfume B.', 'EDT', NULL, 'Pháp', '2025', 120000.00, 120000.00, 2, 2, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(13, 'Dior Sauvage Eau De Toilette', 'dior-sauvage-edt', 'Tươi mát pha cay nồng.', 'Sauvage - hương nam tính...', 'EDT', NULL, 'Pháp', '2015', 400000.00, 400000.00, 1, 2, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(14, 'Miss Dior Blooming Bouquet', 'miss-dior', 'Hương hoa tươi mát...', 'Miss Dior - nữ tính...', 'EDT', NULL, 'Pháp', '2014', 410000.00, 410000.00, 2, 2, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(15, 'Set Dior Mini Collection', 'dior-mini-set', 'Bộ quà mini sang trọng.', 'Set quà Dior gồm 3 mini.', 'Parfum', NULL, 'Pháp', '2024', 500000.00, 500000.00, 4, 2, 3, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(16, 'Creed Aventus', 'creed-aventus', 'Hương dứa nướng khói...', 'Ông vua của nước hoa nam...', 'EDP', NULL, 'Pháp', '2010', 850000.00, 850000.00, 1, 10, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(17, 'Maison Francis Kurkdjian Baccarat Rouge 540', 'br540', 'Nghệ tây, long diên hương...', 'Hương thơm nhà giàu...', 'EDP', NULL, 'Pháp', '2015', 950000.00, 950000.00, 3, 11, 3, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(18, 'Narciso Rodriguez For Her EDP', 'narciso-for-her-edp', 'Hương hoa hồng và xạ hương...', 'Vũ khí gợi cảm...', 'EDP', NULL, 'Mỹ', '2006', 380000.00, 380000.00, 2, 12, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(19, 'Kilian Good Girl Gone Bad', 'kilian-gggb', 'Hương hoa huệ trắng...', 'Gái ngoan hóa hư...', 'EDP', NULL, 'Pháp', '2012', 900000.00, 900000.00, 2, 13, 2, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(20, 'Jean Paul Gaultier Le Male Elixir', 'jpg-le-male-elixir', 'Hương mật ong...', 'Chàng thủy thủ nóng bỏng...', 'Parfum', NULL, 'Pháp', '2023', 420000.00, 420000.00, 1, 14, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(21, 'Giorgio Armani Acqua Di Gio', 'acqua-di-gio', 'Mùi hương của sự tự do...', 'Huyền thoại biển cả...', 'EDT', NULL, 'Ý', '1996', 250000.00, 250000.00, 1, 15, 1, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27'),
(22, 'Le Labo Santal 33', 'santal-33', 'Hương thơm độc bản...', 'Mùi hương gỗ đàn hương...', 'EDP', NULL, 'Mỹ', '2011', 750000.00, 750000.00, 3, 16, 3, 1, '2025-11-21 23:10:27', '2025-11-21 23:10:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nuoc_hoa_tang_huong`
--

CREATE TABLE `nuoc_hoa_tang_huong` (
  `ma_lien_ket` int NOT NULL,
  `ma_nuoc_hoa` int NOT NULL,
  `ma_tang_huong` int NOT NULL,
  `loai_huong` enum('huong_dau','huong_giua','huong_co') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phuong_thuc_thanh_toan`
--

CREATE TABLE `phuong_thuc_thanh_toan` (
  `ma_phuong_thuc` int NOT NULL,
  `ten_phuong_thuc` varchar(100) NOT NULL,
  `mo_ta` text,
  `dang_hoat_dong` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `phuong_thuc_thanh_toan`
--

INSERT INTO `phuong_thuc_thanh_toan` (`ma_phuong_thuc`, `ten_phuong_thuc`, `mo_ta`, `dang_hoat_dong`) VALUES
(1, 'COD', 'Thanh toán khi nhận hàng', 1),
(2, 'Chuyển khoản', 'Chuyển khoản ngân hàng', 1),
(3, 'Momo', 'Ví điện tử Momo', 1),
(4, 'ZaloPay', 'Ví điện tử ZaloPay', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slider`
--

CREATE TABLE `slider` (
  `SliderID` int NOT NULL,
  `SliderName` varchar(255) NOT NULL,
  `Thumbnail` varchar(255) NOT NULL,
  `Active` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `slider`
--

INSERT INTO `slider` (`SliderID`, `SliderName`, `Thumbnail`, `Active`) VALUES
(1, 'Mùa lễ hội - Giảm 30%', '/img/banner1.webp', 1),
(2, 'Bộ sưu tập mới - Spring', '/img/banner2.png', 1),
(3, 'Ưa thích nhất - Bestseller', '/img/banner3.webp', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tang_huong`
--

CREATE TABLE `tang_huong` (
  `ma_tang_huong` int NOT NULL,
  `ten_huong` varchar(255) NOT NULL,
  `loai_huong` enum('huong_dau','huong_giua','huong_co') NOT NULL,
  `mo_ta` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tang_huong`
--

INSERT INTO `tang_huong` (`ma_tang_huong`, `ten_huong`, `loai_huong`, `mo_ta`) VALUES
(1, 'Cam Bergamot', 'huong_dau', 'Hương cam tươi mát'),
(2, 'Hoa Nhài', 'huong_giua', 'Hương hoa nhài quyến rũ'),
(3, 'Gỗ Đàn Hương', 'huong_co', 'Hương gỗ ấm áp'),
(4, 'Vanilla', 'huong_co', 'Hương vani ngọt ngào');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `ma_thuong_hieu` int NOT NULL,
  `ten_thuong_hieu` varchar(255) NOT NULL,
  `duong_dan` varchar(255) DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `mo_ta` text,
  `quoc_gia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`ma_thuong_hieu`, `ten_thuong_hieu`, `duong_dan`, `logo`, `mo_ta`, `quoc_gia`) VALUES
(1, 'Chanel', 'chanel', NULL, NULL, 'Pháp'),
(2, 'Dior', 'dior', NULL, NULL, 'Pháp'),
(3, 'Gucci', 'gucci', NULL, NULL, 'Ý'),
(4, 'Lancôme', 'lancome', NULL, NULL, 'Pháp'),
(5, 'YSL', 'ysl', NULL, NULL, 'Pháp'),
(6, 'Versace', 'versace', NULL, NULL, 'Ý'),
(7, 'Calvin Klein', 'ck', NULL, NULL, 'Mỹ'),
(8, 'Tom Ford', 'tom-ford', NULL, NULL, 'Mỹ'),
(9, 'Prada', 'prada', NULL, NULL, 'Ý'),
(10, 'Creed', 'creed', NULL, 'Thương hiệu Niche hoàng gia lâu đời.', 'Pháp'),
(11, 'Maison Francis Kurkdjian', 'mfk', NULL, 'Đỉnh cao nghệ thuật nước hoa đương đại.', 'Pháp'),
(12, 'Narciso Rodriguez', 'narciso', NULL, 'Biểu tượng của xạ hương quyến rũ.', 'Mỹ'),
(13, 'Kilian', 'kilian', NULL, 'Sang trọng, đẳng cấp và đầy cám dỗ.', 'Pháp'),
(14, 'Jean Paul Gaultier', 'jpg', NULL, 'Phóng khoáng, gợi cảm và nổi loạn.', 'Pháp'),
(15, 'Giorgio Armani', 'armani', NULL, 'Tinh tế, lịch lãm và kinh điển.', 'Ý'),
(16, 'Le Labo', 'le-labo', NULL, 'Thương hiệu Niche mang phong cách phòng thí nghiệm.', 'Mỹ');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bien_the_nuoc_hoa`
--
ALTER TABLE `bien_the_nuoc_hoa`
  ADD PRIMARY KEY (`ma_bien_the`),
  ADD UNIQUE KEY `ma_sku` (`ma_sku`),
  ADD KEY `fk_bienthe_nuoc_hoa` (`ma_nuoc_hoa`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_chi_tiet`),
  ADD KEY `fk_ctdh_donhang` (`ma_don_hang`),
  ADD KEY `fk_ctdh_bienthe` (`ma_bien_the`);

--
-- Chỉ mục cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`ma_danh_gia`),
  ADD KEY `fk_danhgia_user` (`ma_nguoi_dung`),
  ADD KEY `fk_danhgia_nuoc_hoa` (`ma_nuoc_hoa`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`),
  ADD UNIQUE KEY `duong_dan` (`duong_dan`),
  ADD KEY `fk_danhmuc_cha` (`ma_danh_muc_cha`);

--
-- Chỉ mục cho bảng `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD PRIMARY KEY (`ma_yeu_thich`),
  ADD KEY `fk_yt_user` (`ma_nguoi_dung`),
  ADD KEY `fk_yt_nh` (`ma_nuoc_hoa`);

--
-- Chỉ mục cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`ma_dia_chi`),
  ADD KEY `fk_diachi_user` (`ma_nguoi_dung`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `fk_donhang_user` (`ma_nguoi_dung`),
  ADD KEY `fk_donhang_pttt` (`ma_phuong_thuc_thanh_toan`),
  ADD KEY `fk_donhang_diachi` (`ma_dia_chi_giao_hang`),
  ADD KEY `fk_donhang_magiamgia` (`ma_giam_gia`);

--
-- Chỉ mục cho bảng `dung_tich_loc`
--
ALTER TABLE `dung_tich_loc`
  ADD PRIMARY KEY (`ma_dung_tich`);

--
-- Chỉ mục cho bảng `gioi_tinh`
--
ALTER TABLE `gioi_tinh`
  ADD PRIMARY KEY (`ma_gioi_tinh`),
  ADD UNIQUE KEY `duong_dan` (`duong_dan`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`),
  ADD KEY `fk_giohang_user` (`ma_nguoi_dung`);

--
-- Chỉ mục cho bảng `hinh_anh_nuoc_hoa`
--
ALTER TABLE `hinh_anh_nuoc_hoa`
  ADD PRIMARY KEY (`ma_hinh_anh`),
  ADD KEY `fk_hinhanh_nuoc_hoa` (`ma_nuoc_hoa`);

--
-- Chỉ mục cho bảng `khoang_gia`
--
ALTER TABLE `khoang_gia`
  ADD PRIMARY KEY (`ma_khoang`);

--
-- Chỉ mục cho bảng `mat_hang_gio_hang`
--
ALTER TABLE `mat_hang_gio_hang`
  ADD PRIMARY KEY (`ma_mat_hang`),
  ADD KEY `fk_mhgh_giohang` (`ma_gio_hang`),
  ADD KEY `fk_mhgh_bienthe` (`ma_bien_the`);

--
-- Chỉ mục cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  ADD PRIMARY KEY (`ma_giam_gia_id`),
  ADD UNIQUE KEY `ma_giam_gia` (`ma_giam_gia`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_nguoi_dung`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `nuoc_hoa`
--
ALTER TABLE `nuoc_hoa`
  ADD PRIMARY KEY (`ma_nuoc_hoa`),
  ADD UNIQUE KEY `duong_dan` (`duong_dan`),
  ADD KEY `fk_nuoc_hoa_danhmuc` (`ma_danh_muc`),
  ADD KEY `fk_nuoc_hoa_thuong_hieu` (`ma_thuong_hieu`),
  ADD KEY `fk_nuoc_hoa_gioi_tinh` (`ma_gioi_tinh`);

--
-- Chỉ mục cho bảng `nuoc_hoa_tang_huong`
--
ALTER TABLE `nuoc_hoa_tang_huong`
  ADD PRIMARY KEY (`ma_lien_ket`),
  ADD KEY `fk_nh_th_nuoc_hoa` (`ma_nuoc_hoa`),
  ADD KEY `fk_nh_th_tang_huong` (`ma_tang_huong`);

--
-- Chỉ mục cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  ADD PRIMARY KEY (`ma_phuong_thuc`);

--
-- Chỉ mục cho bảng `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`SliderID`);

--
-- Chỉ mục cho bảng `tang_huong`
--
ALTER TABLE `tang_huong`
  ADD PRIMARY KEY (`ma_tang_huong`);

--
-- Chỉ mục cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`ma_thuong_hieu`),
  ADD UNIQUE KEY `duong_dan` (`duong_dan`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bien_the_nuoc_hoa`
--
ALTER TABLE `bien_the_nuoc_hoa`
  MODIFY `ma_bien_the` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `ma_chi_tiet` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `ma_danh_gia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  MODIFY `ma_yeu_thich` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  MODIFY `ma_dia_chi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ma_don_hang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `dung_tich_loc`
--
ALTER TABLE `dung_tich_loc`
  MODIFY `ma_dung_tich` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `gioi_tinh`
--
ALTER TABLE `gioi_tinh`
  MODIFY `ma_gioi_tinh` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `ma_gio_hang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hinh_anh_nuoc_hoa`
--
ALTER TABLE `hinh_anh_nuoc_hoa`
  MODIFY `ma_hinh_anh` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `khoang_gia`
--
ALTER TABLE `khoang_gia`
  MODIFY `ma_khoang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `mat_hang_gio_hang`
--
ALTER TABLE `mat_hang_gio_hang`
  MODIFY `ma_mat_hang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  MODIFY `ma_giam_gia_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `nuoc_hoa`
--
ALTER TABLE `nuoc_hoa`
  MODIFY `ma_nuoc_hoa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `nuoc_hoa_tang_huong`
--
ALTER TABLE `nuoc_hoa_tang_huong`
  MODIFY `ma_lien_ket` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  MODIFY `ma_phuong_thuc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `slider`
--
ALTER TABLE `slider`
  MODIFY `SliderID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tang_huong`
--
ALTER TABLE `tang_huong`
  MODIFY `ma_tang_huong` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `ma_thuong_hieu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `bien_the_nuoc_hoa`
--
ALTER TABLE `bien_the_nuoc_hoa`
  ADD CONSTRAINT `fk_bienthe_nuoc_hoa` FOREIGN KEY (`ma_nuoc_hoa`) REFERENCES `nuoc_hoa` (`ma_nuoc_hoa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_bienthe` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the_nuoc_hoa` (`ma_bien_the`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctdh_donhang` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `fk_danhgia_nuoc_hoa` FOREIGN KEY (`ma_nuoc_hoa`) REFERENCES `nuoc_hoa` (`ma_nuoc_hoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_danhgia_user` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD CONSTRAINT `fk_danhmuc_cha` FOREIGN KEY (`ma_danh_muc_cha`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD CONSTRAINT `fk_yt_nh` FOREIGN KEY (`ma_nuoc_hoa`) REFERENCES `nuoc_hoa` (`ma_nuoc_hoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_yt_user` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD CONSTRAINT `fk_diachi_user` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_donhang_diachi` FOREIGN KEY (`ma_dia_chi_giao_hang`) REFERENCES `dia_chi_giao_hang` (`ma_dia_chi`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donhang_magiamgia` FOREIGN KEY (`ma_giam_gia`) REFERENCES `ma_giam_gia` (`ma_giam_gia_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donhang_pttt` FOREIGN KEY (`ma_phuong_thuc_thanh_toan`) REFERENCES `phuong_thuc_thanh_toan` (`ma_phuong_thuc`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donhang_user` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `fk_giohang_user` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `hinh_anh_nuoc_hoa`
--
ALTER TABLE `hinh_anh_nuoc_hoa`
  ADD CONSTRAINT `fk_hinhanh_nuoc_hoa` FOREIGN KEY (`ma_nuoc_hoa`) REFERENCES `nuoc_hoa` (`ma_nuoc_hoa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `mat_hang_gio_hang`
--
ALTER TABLE `mat_hang_gio_hang`
  ADD CONSTRAINT `fk_mhgh_bienthe` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the_nuoc_hoa` (`ma_bien_the`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mhgh_giohang` FOREIGN KEY (`ma_gio_hang`) REFERENCES `gio_hang` (`ma_gio_hang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `nuoc_hoa`
--
ALTER TABLE `nuoc_hoa`
  ADD CONSTRAINT `fk_nuoc_hoa_danhmuc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nuoc_hoa_gioi_tinh` FOREIGN KEY (`ma_gioi_tinh`) REFERENCES `gioi_tinh` (`ma_gioi_tinh`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nuoc_hoa_thuong_hieu` FOREIGN KEY (`ma_thuong_hieu`) REFERENCES `thuong_hieu` (`ma_thuong_hieu`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `nuoc_hoa_tang_huong`
--
ALTER TABLE `nuoc_hoa_tang_huong`
  ADD CONSTRAINT `fk_nh_th_nuoc_hoa` FOREIGN KEY (`ma_nuoc_hoa`) REFERENCES `nuoc_hoa` (`ma_nuoc_hoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nh_th_tang_huong` FOREIGN KEY (`ma_tang_huong`) REFERENCES `tang_huong` (`ma_tang_huong`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `dia_chi_giao_hang`
  MODIFY `ho_ten_nguoi_nhan` varchar(255) NOT NULL,
  MODIFY `so_dien_thoai` varchar(20) NOT NULL,
  MODIFY `dia_chi_chi_tiet` text NOT NULL;
CREATE INDEX `idx_user_default_address` ON `dia_chi_giao_hang` (`ma_nguoi_dung`, `mac_dinh`, `ma_dia_chi`);
ALTER TABLE `gio_hang`
  ADD UNIQUE KEY `uniq_cart_user` (`ma_nguoi_dung`);
ALTER TABLE `mat_hang_gio_hang`
  ADD CONSTRAINT `chk_mhgh_qty` CHECK (`so_luong` > 0),
  ADD CONSTRAINT `chk_mhgh_price` CHECK (`gia` IS NULL OR `gia` >= 0);
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chk_ctdh_qty` CHECK (`so_luong` > 0),
  ADD CONSTRAINT `chk_ctdh_price` CHECK (`don_gia` >= 0);
DROP INDEX IF EXISTS `idx_image_type_order` ON `hinh_anh_nuoc_hoa`;
CREATE INDEX `idx_image_type_order` ON `hinh_anh_nuoc_hoa` (`ma_nuoc_hoa`, `loai_hinh_anh`, `thu_tu`);
COMMIT;
