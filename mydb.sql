-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 06, 2025 at 12:26 AM
-- Server version: 5.7.43-log
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `background`
--

CREATE TABLE `background` (
  `id` int(11) NOT NULL,
  `display` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `background`
--

INSERT INTO `background` (`id`, `display`, `file`, `cabang_id`, `created_at`, `updated_at`) VALUES
(55, 'screen_start', 'background/screen_start_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(56, 'screen_order', 'background/screen_order_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(57, 'screen_payment', 'background/screen_payment_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(58, 'screen_frame', 'background/screen_frame_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(59, 'screen_select_camera', 'background/screen_select_camera_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(60, 'screen_capture_photo', 'background/screen_capture_photo_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(61, 'screen_filter', 'background/screen_filter_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(62, 'screen_print', 'background/screen_print_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(63, 'screen_finish', 'background/screen_finish_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(64, 'container_frame', 'background/container_frame_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(65, 'container_filter', 'background/container_filter_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(66, 'container_print', 'background/container_print_2.png', 2, '2025-03-03 22:22:57', '2025-03-03 22:22:57'),
(76, 'screen_start', 'background/screen_start_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(77, 'screen_order', 'background/screen_order_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(78, 'screen_payment', 'background/screen_payment_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(79, 'screen_frame', 'background/screen_frame_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(80, 'screen_select_camera', 'background/screen_select_camera_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(81, 'screen_capture_photo', 'background/screen_capture_photo_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(82, 'screen_filter', 'background/screen_filter_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(83, 'screen_print', 'background/screen_print_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09'),
(84, 'screen_finish', 'background/screen_finish_15.png', 15, '2025-03-14 05:14:09', '2025-03-14 05:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `id` int(11) NOT NULL,
  `nama_cabang` varchar(255) NOT NULL,
  `lokasi` text NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_event` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `payment_status` tinyint(1) NOT NULL DEFAULT '1',
  `retake_status` tinyint(1) NOT NULL DEFAULT '1',
  `print_status` tinyint(1) NOT NULL DEFAULT '0',
  `printer_name` varchar(255) NOT NULL,
  `private_key` varchar(255) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`id`, `nama_cabang`, `lokasi`, `is_delete`, `is_event`, `created_at`, `updated_at`, `username`, `password`, `role`, `payment_status`, `retake_status`, `print_status`, `printer_name`, `private_key`, `certificate`) VALUES
(2, 'Photo Uye', 'Jl. Cempaka Putih', 0, 0, '2025-02-10 09:33:23', '2025-03-12 07:12:09', 'user', '95c946bf622ef93b0a211cd0fd028dfdfcf7e39e', 'user', 0, 1, 0, '', NULL, NULL),
(3, 'admin', 'Jl. Jayabana', 0, 0, '2025-02-10 09:33:23', '2025-03-14 04:44:23', 'admin', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'admin', 0, 1, 0, '', NULL, NULL),
(5, 'Kodak Modern', 'Jl. Tegal Ampel', 0, 0, '2025-02-10 09:33:23', '2025-03-15 14:33:29', 'user2', '159bfb45f49f236a21c96d3306023a1a4a2bc231', 'user', 0, 1, 0, '', NULL, NULL),
(11, 'Event Cukimay', 'Jl. Tegal Ampel', 0, 1, '2025-02-10 09:33:23', '2025-03-05 04:30:40', 'user3', '95c946bf622ef93b0a211cd0fd028dfdfcf7e39e', 'user', 0, 1, 0, '', NULL, NULL),
(14, 'Event Wedding', 'Bondowoso', 0, 1, '2025-03-05 06:33:52', '2025-03-16 13:24:15', 'user4', '7c222fb2927d828af22f592134e8932480637c0d', 'user', 0, 0, 0, '', NULL, NULL),
(15, 'TSM', 'Jl. Imambonjol', 0, 0, '2025-03-14 03:44:45', '2025-04-05 07:20:54', 'TSM', '7c222fb2927d828af22f592134e8932480637c0d', 'user', 0, 1, 0, 'DS-RX1 (Copy 1)', 'certs/private-key-1743762558.pem', 'certs/digital-cert-1743762558.txt');

-- --------------------------------------------------------

--
-- Table structure for table `camera`
--

CREATE TABLE `camera` (
  `id` bigint(20) NOT NULL,
  `camera1` int(11) NOT NULL,
  `camera2` int(11) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `camera`
--

INSERT INTO `camera` (`id`, `camera1`, `camera2`, `cabang_id`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 15, '2025-03-17 07:17:21', '2025-04-05 07:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `frame`
--

CREATE TABLE `frame` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cabang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frame`
--

INSERT INTO `frame` (`id`, `name`, `file`, `created_at`, `updated_at`, `cabang_id`) VALUES
(32, 'musix1', 'frame/musix1738915735.png', '2025-02-07 15:08:55', '2025-03-12 21:22:37', 2),
(33, 'Frame cloud', 'frame/Frame cloud1740634806.png', '2025-02-27 12:40:06', '2025-03-12 21:16:43', 2),
(39, 'beach', 'frame/beach1741924889.png', '2025-03-14 05:01:29', '2025-03-14 05:01:29', 15),
(40, 'test 123', 'frame/test 1231742131897.png', '2025-03-16 14:31:37', '2025-03-16 14:31:37', 14),
(41, 'pink', 'frame/pink1742436585.png', '2025-03-20 03:09:45', '2025-03-20 03:09:45', 15),
(42, 'camera', 'frame/camera1742436720.png', '2025-03-20 03:12:00', '2025-03-20 03:12:00', 15),
(43, 'biru', 'frame/biru1742969927.png', '2025-03-26 07:18:47', '2025-03-26 07:18:47', 2),
(44, 'biru', 'frame/biru1742969927.png', '2025-03-26 07:18:47', '2025-03-26 07:18:47', 15);

-- --------------------------------------------------------

--
-- Table structure for table `frame_koordinat`
--

CREATE TABLE `frame_koordinat` (
  `id` int(11) NOT NULL,
  `frame_id` int(11) DEFAULT NULL,
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `width` double DEFAULT NULL,
  `height` double DEFAULT NULL,
  `rotation` double DEFAULT '0',
  `index` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frame_koordinat`
--

INSERT INTO `frame_koordinat` (`id`, `frame_id`, `x`, `y`, `width`, `height`, `rotation`, `index`) VALUES
(41, 32, 9.1685393258427, 25.9675, 183.37078651685, 143.585, 0, 1),
(42, 32, 13.752808988764, 181.7725, 175.73033707865, 128.31, 0, 1),
(43, 32, 16.808988764045, 323.83, 171.14606741573, 122.2, 0, 1),
(44, 32, 213.93258426966, 45.825, 180.31460674157, 120.6725, 0, 1),
(45, 32, 212.40449438202, 181.7725, 178.78651685393, 126.7825, 0, 1),
(46, 32, 213.93258426966, 322.3025, 177.25842696629, 128.31, 0, 1),
(49, 33, 229.32, 87.36, 346.71, 273, NULL, 1),
(50, 33, 237.51, 622.44, 338.52, 273, NULL, 2),
(51, 33, 289.38, 357.63, 221.13, 273, 45.637830437395, 1),
(52, 39, 93.302325581395, 455.67357512953, 1119.6279069767, 958.0829015544, -5.723386174574, 1),
(53, 39, 0, 1904.481865285, 1096.3023255814, 852.9274611399, 2.9136789464045, 2),
(54, 39, 279.90697674419, 3189.7150259067, 746.41860465116, 747.77202072539, 17.539314002976, 3),
(55, 39, 1597.8023255814, 432.30569948187, 1119.6279069767, 946.39896373057, -7.4634767143317, 1),
(56, 39, 1469.511627907, 1904.481865285, 1119.6279069767, 829.55958549223, 3.9129895250708, 2),
(57, 39, 1784.4069767442, 3213.0829015544, 758.08139534884, 759.45595854922, 17.143593853435, 3),
(58, 40, 241.21232876712, 103.6, 333.77054794521, 243.6, NULL, 1),
(59, 40, 244.01712328767, 375.2, 330.96575342466, 229.6, NULL, 2),
(60, 40, 246.82191780822, 635.6, 322.55136986301, 238, NULL, 3),
(61, 41, 105.33852140078, 163.78181818182, 1416.2178988327, 959.29350649351, NULL, 1),
(62, 41, 117.04280155642, 1146.4727272727, 1404.513618677, 970.99220779221, NULL, 2),
(63, 41, 1650.3035019455, 175.48051948052, 1228.9494163424, 1591.0233766234, NULL, 3),
(64, 41, 128.74708171206, 2491.8233766234, 1228.9494163424, 1591.0233766234, NULL, 4),
(65, 41, 1638.5992217899, 2468.425974026, 1228.9494163424, 1591.0233766234, NULL, 4),
(66, 42, 108.34364261168, 224.32133676093, 1454.9003436426, 1144.8123393316, NULL, 1),
(67, 42, 77.388316151203, 1717.2185089974, 1454.9003436426, 1144.8123393316, NULL, 2),
(68, 42, 2368.0824742268, 1717.2185089974, 1408.4673539519, 1152.5475578406, NULL, 3),
(69, 42, 2383.560137457, 216.58611825193, 1408.4673539519, 1152.5475578406, NULL, 4),
(70, 43, 117.04280155642, 233.97402597403, 1275.766536965, 1602.7220779221, NULL, 1),
(71, 43, 1603.486381323, 222.27532467532, 1275.766536965, 1602.7220779221, NULL, 2),
(72, 43, 105.33852140078, 2480.1246753247, 1275.766536965, 1602.7220779221, NULL, 1),
(73, 43, 1615.1906614786, 2480.1246753247, 1275.766536965, 1602.7220779221, NULL, 2),
(74, 44, 117.04280155642, 233.97402597403, 1275.766536965, 1602.7220779221, NULL, 1),
(75, 44, 1603.486381323, 222.27532467532, 1275.766536965, 1602.7220779221, NULL, 2),
(76, 44, 105.33852140078, 2480.1246753247, 1275.766536965, 1602.7220779221, NULL, 1),
(77, 44, 1615.1906614786, 2480.1246753247, 1275.766536965, 1602.7220779221, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `harga`
--

CREATE TABLE `harga` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `harga`
--

INSERT INTO `harga` (`id`, `harga`, `cabang_id`, `created_at`, `updated_at`) VALUES
(4, 8000.00, 5, '2025-02-10 11:46:18', '2025-02-10 12:03:07'),
(6, 5500.00, 2, '2025-02-10 12:52:17', '2025-02-10 13:12:41'),
(7, 2.00, 15, '2025-03-14 04:02:43', '2025-03-20 02:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `invoice` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cabang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `invoice`, `amount`, `tanggal`, `status`, `cabang`, `created_at`) VALUES
(123, '457304', 4500.00, '2025-02-27', 'pending', '-', '2025-02-27 06:32:16'),
(124, '829013', 5000.00, '2025-02-27', 'pending', '-', '2025-02-27 06:33:45'),
(125, '373153', 5500.00, '2025-02-27', 'pending', '-', '2025-02-27 06:44:07'),
(126, '852690', 5000.00, '2025-02-27', 'pending', '-', '2025-02-27 06:44:30'),
(127, '186987', 4500.00, '2025-02-27', 'pending', '-', '2025-02-27 06:45:39'),
(128, '297216', 5000.00, '2025-02-27', 'pending', '-', '2025-02-27 06:46:08'),
(129, '901475', 4500.00, '2025-02-27', 'pending', '-', '2025-02-27 06:46:53'),
(130, '659312', 5500.00, '2025-02-27', 'pending', '-', '2025-02-27 07:15:47'),
(131, '852372', 5496.00, '2025-02-27', 'pending', '-', '2025-02-27 07:16:08'),
(132, '523608', 4500.00, '2025-02-27', 'pending', '-', '2025-02-27 07:16:34'),
(133, '329461', 4500.00, '2025-02-27', 'pending', '-', '2025-02-27 07:16:39'),
(134, '589165', 5500.00, '2025-02-28', 'pending', '-', '2025-02-28 06:54:09'),
(135, '283159', 5500.00, '2025-02-28', 'pending', '-', '2025-02-28 06:54:38'),
(136, '356936', 5500.00, '2025-02-28', 'pending', '-', '2025-02-28 06:54:50'),
(137, '878296', 5500.00, '2025-02-28', 'pending', '-', '2025-02-28 08:50:26'),
(138, '724262', 5500.00, '2025-02-28', 'pending', '-', '2025-02-28 09:36:33'),
(139, '470134', 5500.00, '2025-02-28', 'pending', '-', '2025-02-28 09:36:59'),
(140, '649532', 5500.00, '2025-02-28', 'paid', '-', '2025-02-28 09:37:34'),
(141, '278529', 5500.00, '2025-03-03', 'pending', '-', '2025-03-03 15:13:01'),
(142, '782035', 11000.00, '2025-03-04', 'pending', '-', '2025-03-04 16:38:39'),
(143, '770119', 16500.00, '2025-03-07', 'pending', '-', '2025-03-07 05:27:57'),
(144, '110645', 16500.00, '2025-03-07', 'pending', '-', '2025-03-07 05:31:14'),
(145, '653163', 16500.00, '2025-03-07', 'pending', '-', '2025-03-07 05:43:57'),
(146, '882718', 11000.00, '2025-03-07', 'pending', '-', '2025-03-07 05:45:25'),
(147, '820374', 999999.00, '2025-03-14', 'pending', '-', '2025-03-14 03:45:25'),
(148, '809232', 2.00, '2025-03-14', 'paid', '-', '2025-03-14 04:19:02'),
(149, '538633', 2.00, '2025-03-14', 'pending', '-', '2025-03-14 04:36:32'),
(150, '806885', 2.00, '2025-03-14', 'paid', '-', '2025-03-14 04:39:27'),
(151, '317107', 2.00, '2025-03-14', 'pending', '-', '2025-03-14 05:20:55'),
(152, '763759', 2.00, '2025-03-14', 'paid', '-', '2025-03-14 05:20:57'),
(153, '971703', 2.00, '2025-03-14', 'paid', '-', '2025-03-14 05:52:49'),
(154, '954198', 35000.00, '2025-03-17', 'pending', '-', '2025-03-17 00:28:39'),
(155, '212436', 35000.00, '2025-03-17', 'pending', '-', '2025-03-17 00:32:49'),
(156, '657955', 3.00, '2025-03-20', 'paid', '-', '2025-03-20 02:50:28'),
(157, '214586', 4.00, '2025-03-20', 'paid', '-', '2025-03-20 03:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `qris`
--

CREATE TABLE `qris` (
  `id` int(11) NOT NULL,
  `background` varchar(255) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `qris`
--

INSERT INTO `qris` (`id`, `background`, `cabang_id`, `created_at`, `updated_at`) VALUES
(5, 'background-qris/Qris-1739250546.png', 2, '2025-02-11 05:09:06', '2025-02-11 05:09:06'),
(6, 'background-qris/Qris-1739250592.png', 5, '2025-02-11 05:09:52', '2025-02-11 05:09:52'),
(7, 'background-qris/Qris-1741925144.png', 15, '2025-03-14 04:05:44', '2025-03-14 04:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`) VALUES
(24, 'auto_print', '0'),
(59, 'disable_payment', '1'),
(60, 'img_order', 'order/image_order.png');

-- --------------------------------------------------------

--
-- Table structure for table `timer`
--

CREATE TABLE `timer` (
  `id` int(11) NOT NULL,
  `display` varchar(255) NOT NULL,
  `time` bigint(20) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timer`
--

INSERT INTO `timer` (`id`, `display`, `time`, `cabang_id`, `created_at`, `updated_at`) VALUES
(4, 'screen_order', 1000, 2, '2025-02-10 13:35:26', '2025-03-02 14:00:57'),
(5, 'screen_frame', 31, 2, '2025-02-10 13:35:26', '2025-03-07 03:12:58'),
(6, 'screen_payment', 50, 2, '2025-02-10 13:35:26', '2025-02-28 09:37:24'),
(8, 'screen_select_camera', 1000, 2, '2025-02-10 13:35:26', '2025-03-02 14:00:57'),
(9, 'screen_capture_photo', 200, 2, '2025-02-10 13:35:26', '2025-03-04 16:48:43'),
(10, 'screen_filter', 3000, 2, '2025-02-10 13:35:26', '2025-03-02 14:00:57'),
(11, 'screen_print', 800, 2, '2025-02-10 13:35:26', '2025-03-07 03:58:17'),
(12, 'screen_order', 20, 5, '2025-02-10 15:26:15', '2025-02-10 16:17:45'),
(13, 'screen_payment', 20, 5, '2025-02-10 15:26:15', '2025-02-10 16:18:31'),
(14, 'screen_frame', 31, 5, '2025-02-10 15:26:15', '2025-03-07 03:12:48'),
(15, 'screen_select_camera', 20, 5, '2025-02-10 15:26:15', '2025-02-10 16:18:31'),
(16, 'screen_capture_photo', 2000, 5, '2025-02-10 15:26:15', '2025-03-04 15:12:08'),
(17, 'screen_filter', 20, 5, '2025-02-10 15:26:15', '2025-02-10 16:18:31'),
(18, 'screen_print', 20, 5, '2025-02-10 15:26:15', '2025-02-10 16:18:12'),
(19, 'screen_finish', 20, 5, '2025-02-10 15:26:15', '2025-02-10 16:18:31'),
(28, 'screen_finish', 5, 2, '2025-02-10 15:26:15', '2025-02-10 16:18:31'),
(29, 'screen_order', 60, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(30, 'screen_payment', 60, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(31, 'screen_frame', 120, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(32, 'screen_select_camera', 60, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(33, 'screen_capture_photo', 600, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(34, 'screen_filter', 60, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(35, 'screen_print', 200, 15, '2025-03-14 04:07:06', '2025-04-05 06:12:32'),
(36, 'screen_finish', 30, 15, '2025-03-14 04:07:06', '2025-03-14 04:08:12'),
(37, 'screen_order', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(38, 'screen_payment', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(39, 'screen_frame', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(40, 'screen_select_camera', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(41, 'screen_capture_photo', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(42, 'screen_filter', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(43, 'screen_print', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(44, 'screen_finish', 200, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:01'),
(45, 'countdown', 6, 14, '2025-03-15 14:31:01', '2025-03-15 14:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `kode_voucher` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` date NOT NULL,
  `potongan_harga` decimal(10,0) NOT NULL,
  `cabang_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`kode_voucher`, `expired`, `potongan_harga`, `cabang_id`) VALUES
('3V06T7L2', '2025-03-21', 1, 15),
('3Y4NGZHJ', '2025-02-27', 500, NULL),
('6JB9H4KO', '2026-02-27', 4, 2),
('G2FTDUAH', '2025-03-20', 1, 15),
('PHTMBFIL', '2025-03-15', 34998, 15),
('Y0QIZBOD', '2026-02-27', 2, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `background`
--
ALTER TABLE `background`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_background_cabang` (`cabang_id`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `camera`
--
ALTER TABLE `camera`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frame`
--
ALTER TABLE `frame`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_frame_cabang` (`cabang_id`);

--
-- Indexes for table `frame_koordinat`
--
ALTER TABLE `frame_koordinat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `frame_id` (`frame_id`);

--
-- Indexes for table `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cabang` (`cabang_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qris`
--
ALTER TABLE `qris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cabang_id` (`cabang_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `name_2` (`name`);

--
-- Indexes for table `timer`
--
ALTER TABLE `timer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cabang_id` (`cabang_id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD UNIQUE KEY `kode_voucher` (`kode_voucher`),
  ADD KEY `fk_voucher_cabang` (`cabang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `background`
--
ALTER TABLE `background`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `cabang`
--
ALTER TABLE `cabang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `camera`
--
ALTER TABLE `camera`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `frame`
--
ALTER TABLE `frame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `frame_koordinat`
--
ALTER TABLE `frame_koordinat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `qris`
--
ALTER TABLE `qris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `timer`
--
ALTER TABLE `timer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `background`
--
ALTER TABLE `background`
  ADD CONSTRAINT `fk_background_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `frame`
--
ALTER TABLE `frame`
  ADD CONSTRAINT `fk_frame_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `frame_koordinat`
--
ALTER TABLE `frame_koordinat`
  ADD CONSTRAINT `frame_koordinat_ibfk_1` FOREIGN KEY (`frame_id`) REFERENCES `frame` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `harga`
--
ALTER TABLE `harga`
  ADD CONSTRAINT `fk_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qris`
--
ALTER TABLE `qris`
  ADD CONSTRAINT `qris_ibfk_1` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timer`
--
ALTER TABLE `timer`
  ADD CONSTRAINT `timer_ibfk_1` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `fk_voucher_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
