/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: photorama
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `background`
--

DROP TABLE IF EXISTS `background`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `background` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display` varchar(255) NOT NULL,
  `file` varchar(500) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_background_cabang` (`cabang_id`),
  CONSTRAINT `fk_background_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `background`
--

LOCK TABLES `background` WRITE;
/*!40000 ALTER TABLE `background` DISABLE KEYS */;
INSERT INTO `background` VALUES
(55,'screen_start','background/screen_start_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(56,'screen_order','background/screen_order_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(57,'screen_payment','background/screen_payment_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(58,'screen_frame','background/screen_frame_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(59,'screen_select_camera','background/screen_select_camera_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(60,'screen_capture_photo','background/screen_capture_photo_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(61,'screen_filter','background/screen_filter_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(62,'screen_print','background/screen_print_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(63,'screen_finish','background/screen_finish_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(64,'container_frame','background/container_frame_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(65,'container_filter','background/container_filter_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57'),
(66,'container_print','background/container_print_2.png',2,'2025-03-03 22:22:57','2025-03-03 22:22:57');
/*!40000 ALTER TABLE `background` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cabang`
--

DROP TABLE IF EXISTS `cabang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cabang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_cabang` varchar(255) NOT NULL,
  `lokasi` text NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `is_event` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `payment_status` tinyint(1) NOT NULL DEFAULT 1,
  `retake_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cabang`
--

LOCK TABLES `cabang` WRITE;
/*!40000 ALTER TABLE `cabang` DISABLE KEYS */;
INSERT INTO `cabang` VALUES
(2,'Photo Uye','Jl. Cempaka Putih',0,0,'2025-02-10 09:33:23','2025-03-12 07:12:09','user','95c946bf622ef93b0a211cd0fd028dfdfcf7e39e','user',0,1),
(3,'Aku Admin','Jl. Jayabana',0,0,'2025-02-10 09:33:23','2025-03-04 14:55:38','admin','f865b53623b121fd34ee5426c792e5c33af8c227','admin',0,1),
(5,'Kodak Modern','Jl. Tegal Ampel',0,0,'2025-02-10 09:33:23','2025-03-05 04:28:50','user2','95c946bf622ef93b0a211cd0fd028dfdfcf7e39e','user',0,1),
(11,'Event Cukimay','Jl. Tegal Ampel',0,1,'2025-02-10 09:33:23','2025-03-05 04:30:40','user3','95c946bf622ef93b0a211cd0fd028dfdfcf7e39e','user',0,1),
(14,'Event Wedding','Bondowoso',0,1,'2025-03-05 06:33:52','2025-03-05 06:40:52','user4','7b21848ac9af35be0ddb2d6b9fc3851934db8420','user',0,0);
/*!40000 ALTER TABLE `cabang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frame`
--

DROP TABLE IF EXISTS `frame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `frame` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cabang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_frame_cabang` (`cabang_id`),
  CONSTRAINT `fk_frame_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frame`
--

LOCK TABLES `frame` WRITE;
/*!40000 ALTER TABLE `frame` DISABLE KEYS */;
INSERT INTO `frame` VALUES
(32,'musix1','frame/musix1738915735.png','2025-02-07 15:08:55','2025-03-12 21:22:37',2),
(33,'Frame cloud','frame/Frame cloud1740634806.png','2025-02-27 12:40:06','2025-03-12 21:16:43',2);
/*!40000 ALTER TABLE `frame` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frame_koordinat`
--

DROP TABLE IF EXISTS `frame_koordinat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `frame_koordinat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame_id` int(11) DEFAULT NULL,
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `width` double DEFAULT NULL,
  `height` double DEFAULT NULL,
  `rotation` double DEFAULT 0,
  `index` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `frame_id` (`frame_id`),
  CONSTRAINT `frame_koordinat_ibfk_1` FOREIGN KEY (`frame_id`) REFERENCES `frame` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frame_koordinat`
--

LOCK TABLES `frame_koordinat` WRITE;
/*!40000 ALTER TABLE `frame_koordinat` DISABLE KEYS */;
INSERT INTO `frame_koordinat` VALUES
(41,32,9.1685393258427,25.9675,183.37078651685,143.585,0,1),
(42,32,13.752808988764,181.7725,175.73033707865,128.31,0,1),
(43,32,16.808988764045,323.83,171.14606741573,122.2,0,1),
(44,32,213.93258426966,45.825,180.31460674157,120.6725,0,1),
(45,32,212.40449438202,181.7725,178.78651685393,126.7825,0,1),
(46,32,213.93258426966,322.3025,177.25842696629,128.31,0,1),
(49,33,229.32,87.36,346.71,273,NULL,1),
(50,33,237.51,622.44,338.52,273,NULL,2),
(51,33,289.38,357.63,221.13,273,45.637830437395,1);
/*!40000 ALTER TABLE `frame_koordinat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `harga`
--

DROP TABLE IF EXISTS `harga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `harga` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `harga` decimal(10,2) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cabang` (`cabang_id`),
  CONSTRAINT `fk_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `harga`
--

LOCK TABLES `harga` WRITE;
/*!40000 ALTER TABLE `harga` DISABLE KEYS */;
INSERT INTO `harga` VALUES
(4,8000.00,5,'2025-02-10 11:46:18','2025-02-10 12:03:07'),
(6,5500.00,2,'2025-02-10 12:52:17','2025-02-10 13:12:41');
/*!40000 ALTER TABLE `harga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(50) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `cabang` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES
(123,'457304',4500.00,'2025-02-27','pending','-','2025-02-27 06:32:16'),
(124,'829013',5000.00,'2025-02-27','pending','-','2025-02-27 06:33:45'),
(125,'373153',5500.00,'2025-02-27','pending','-','2025-02-27 06:44:07'),
(126,'852690',5000.00,'2025-02-27','pending','-','2025-02-27 06:44:30'),
(127,'186987',4500.00,'2025-02-27','pending','-','2025-02-27 06:45:39'),
(128,'297216',5000.00,'2025-02-27','pending','-','2025-02-27 06:46:08'),
(129,'901475',4500.00,'2025-02-27','pending','-','2025-02-27 06:46:53'),
(130,'659312',5500.00,'2025-02-27','pending','-','2025-02-27 07:15:47'),
(131,'852372',5496.00,'2025-02-27','pending','-','2025-02-27 07:16:08'),
(132,'523608',4500.00,'2025-02-27','pending','-','2025-02-27 07:16:34'),
(133,'329461',4500.00,'2025-02-27','pending','-','2025-02-27 07:16:39'),
(134,'589165',5500.00,'2025-02-28','pending','-','2025-02-28 06:54:09'),
(135,'283159',5500.00,'2025-02-28','pending','-','2025-02-28 06:54:38'),
(136,'356936',5500.00,'2025-02-28','pending','-','2025-02-28 06:54:50'),
(137,'878296',5500.00,'2025-02-28','pending','-','2025-02-28 08:50:26'),
(138,'724262',5500.00,'2025-02-28','pending','-','2025-02-28 09:36:33'),
(139,'470134',5500.00,'2025-02-28','pending','-','2025-02-28 09:36:59'),
(140,'649532',5500.00,'2025-02-28','paid','-','2025-02-28 09:37:34'),
(141,'278529',5500.00,'2025-03-03','pending','-','2025-03-03 15:13:01'),
(142,'782035',11000.00,'2025-03-04','pending','-','2025-03-04 16:38:39'),
(143,'770119',16500.00,'2025-03-07','pending','-','2025-03-07 05:27:57'),
(144,'110645',16500.00,'2025-03-07','pending','-','2025-03-07 05:31:14'),
(145,'653163',16500.00,'2025-03-07','pending','-','2025-03-07 05:43:57'),
(146,'882718',11000.00,'2025-03-07','pending','-','2025-03-07 05:45:25');
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qris`
--

DROP TABLE IF EXISTS `qris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `qris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `background` varchar(255) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cabang_id` (`cabang_id`),
  CONSTRAINT `qris_ibfk_1` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qris`
--

LOCK TABLES `qris` WRITE;
/*!40000 ALTER TABLE `qris` DISABLE KEYS */;
INSERT INTO `qris` VALUES
(5,'background-qris/Qris-1739250546.png',2,'2025-02-11 05:09:06','2025-02-11 05:09:06'),
(6,'background-qris/Qris-1739250592.png',5,'2025-02-11 05:09:52','2025-02-11 05:09:52');
/*!40000 ALTER TABLE `qris` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `name_2` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES
(24,'auto_print','0'),
(59,'disable_payment','1');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timer`
--

DROP TABLE IF EXISTS `timer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display` varchar(255) NOT NULL,
  `time` bigint(20) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cabang_id` (`cabang_id`),
  CONSTRAINT `timer_ibfk_1` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timer`
--

LOCK TABLES `timer` WRITE;
/*!40000 ALTER TABLE `timer` DISABLE KEYS */;
INSERT INTO `timer` VALUES
(4,'screen_order',1000,2,'2025-02-10 13:35:26','2025-03-02 14:00:57'),
(5,'screen_frame',31,2,'2025-02-10 13:35:26','2025-03-07 03:12:58'),
(6,'screen_payment',50,2,'2025-02-10 13:35:26','2025-02-28 09:37:24'),
(8,'screen_select_camera',1000,2,'2025-02-10 13:35:26','2025-03-02 14:00:57'),
(9,'screen_capture_photo',200,2,'2025-02-10 13:35:26','2025-03-04 16:48:43'),
(10,'screen_filter',3000,2,'2025-02-10 13:35:26','2025-03-02 14:00:57'),
(11,'screen_print',800,2,'2025-02-10 13:35:26','2025-03-07 03:58:17'),
(12,'screen_order',20,5,'2025-02-10 15:26:15','2025-02-10 16:17:45'),
(13,'screen_payment',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(14,'screen_frame',31,5,'2025-02-10 15:26:15','2025-03-07 03:12:48'),
(15,'screen_select_camera',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(16,'screen_capture_photo',2000,5,'2025-02-10 15:26:15','2025-03-04 15:12:08'),
(17,'screen_filter',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(18,'screen_print',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:12'),
(19,'screen_finish',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(28,'screen_finish',5,2,'2025-02-10 15:26:15','2025-02-10 16:18:31');
/*!40000 ALTER TABLE `timer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher`
--

DROP TABLE IF EXISTS `voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `voucher` (
  `kode_voucher` varchar(10) NOT NULL,
  `expired` date NOT NULL,
  `potongan_harga` decimal(10,0) NOT NULL,
  `cabang_id` int(11) DEFAULT NULL,
  UNIQUE KEY `kode_voucher` (`kode_voucher`),
  KEY `fk_voucher_cabang` (`cabang_id`),
  CONSTRAINT `fk_voucher_cabang` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher`
--

LOCK TABLES `voucher` WRITE;
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
INSERT INTO `voucher` VALUES
('3Y4NGZHJ','2025-02-27',500,NULL),
('6JB9H4KO','2026-02-27',4,2),
('Y0QIZBOD','2026-02-27',2,3);
/*!40000 ALTER TABLE `voucher` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-03-12 23:02:17
