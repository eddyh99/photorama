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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `background`
--

LOCK TABLES `background` WRITE;
/*!40000 ALTER TABLE `background` DISABLE KEYS */;
INSERT INTO `background` VALUES
(46,'screen_order','background/screen_order_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(47,'screen_payment','background/screen_payment_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(48,'screen_frame','background/screen_frame_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(49,'screen_select_camera','background/screen_select_camera_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(50,'screen_capture_photo','background/screen_capture_photo_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(51,'screen_filter','background/screen_filter_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(52,'screen_print','background/screen_print_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02'),
(53,'screen_finish','background/screen_finish_2.png',2,'2025-02-19 12:09:02','2025-02-19 12:09:02');
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cabang`
--

LOCK TABLES `cabang` WRITE;
/*!40000 ALTER TABLE `cabang` DISABLE KEYS */;
INSERT INTO `cabang` VALUES
(2,'Photo Uye','Jl. Cempaka Putih',0,'2025-02-10 09:33:23','2025-02-10 11:11:09','user','95c946bf622ef93b0a211cd0fd028dfdfcf7e39e','user'),
(3,'Aku Admin','Jl. Jayabana',0,'2025-02-10 09:33:23','2025-02-10 09:33:23','admin','f865b53623b121fd34ee5426c792e5c33af8c227','admin'),
(5,'Kodak Modern','Jl. Tegal Ampel',0,'2025-02-10 09:33:23','2025-02-10 09:33:23','user2','95c946bf622ef93b0a211cd0fd028dfdfcf7e39e','user');
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frame`
--

LOCK TABLES `frame` WRITE;
/*!40000 ALTER TABLE `frame` DISABLE KEYS */;
INSERT INTO `frame` VALUES
(22,'nataru','frame/nataru1738035603.png','2025-01-28 10:40:03','2025-01-28 10:40:03'),
(32,'musix','frame/musix1738915735.png','2025-02-07 15:08:55','2025-02-07 15:08:55');
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
  PRIMARY KEY (`id`),
  KEY `frame_id` (`frame_id`),
  CONSTRAINT `frame_koordinat_ibfk_1` FOREIGN KEY (`frame_id`) REFERENCES `frame` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frame_koordinat`
--

LOCK TABLES `frame_koordinat` WRITE;
/*!40000 ALTER TABLE `frame_koordinat` DISABLE KEYS */;
INSERT INTO `frame_koordinat` VALUES
(5,22,150.29,146.57500000000002,483.075,454.02500000000003),
(6,22,1495.7433333333333,128.70000000000002,511.70166666666665,500.5),
(7,22,125.24166666666666,840.125,458.02666666666664,421.85),
(8,22,1502.8999999999999,832.975,493.80999999999995,446.875),
(15,32,15.280898876404,45.825,175.73033707865,116.09),
(16,32,15.280898876404,184.8275,177.25842696629,114.5625),
(17,32,13.752808988764,328.4125,178.78651685393,116.09),
(18,32,215.4606741573,47.3525,174.20224719101,117.6175),
(19,32,220.04494382022,187.8825,174.20224719101,116.09),
(20,32,216.98876404494,323.83,172.67415730337,117.6175);
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
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES
(31,'133509',4.00,'2025-01-31','paid','-','2025-01-31 11:23:26'),
(32,'613323',4.00,'2025-01-31','paid','-','2025-01-31 11:24:28'),
(33,'276933',4.00,'2025-02-02','pending','-','2025-02-02 04:39:08'),
(34,'206938',1.00,'2025-02-05','pending','-','2025-02-05 07:37:55'),
(35,'448113',2.00,'2025-02-05','pending','-','2025-02-05 07:43:17'),
(36,'129708',2.00,'2025-02-05','pending','-','2025-02-05 07:58:27'),
(37,'830939',4.00,'2025-02-05','pending','-','2025-02-05 07:58:34'),
(38,'823585',4.00,'2025-02-05','pending','-','2025-02-05 07:59:53'),
(39,'737609',4.00,'2025-02-05','pending','-','2025-02-05 08:00:35'),
(40,'735079',4.00,'2025-02-05','pending','-','2025-02-05 08:03:07'),
(41,'840055',4.00,'2025-02-05','pending','-','2025-02-05 08:03:22'),
(42,'279266',4.00,'2025-02-05','pending','-','2025-02-05 08:03:29'),
(43,'476040',4.00,'2025-02-05','pending','-','2025-02-05 08:03:34'),
(44,'822759',400.00,'2025-02-05','paid','-','2025-02-05 08:03:59'),
(45,'351150',200.00,'2025-02-05','paid','-','2025-02-05 08:05:34'),
(46,'534954',200.00,'2025-02-05','pending','-','2025-02-05 08:08:00'),
(47,'623395',200.00,'2025-02-05','pending','-','2025-02-05 08:09:54'),
(48,'416431',200.00,'2025-02-05','pending','-','2025-02-05 08:10:26'),
(49,'176527',300.00,'2025-02-05','pending','-','2025-02-05 08:11:05'),
(50,'580871',300.00,'2025-02-05','paid','-','2025-02-05 08:13:11'),
(51,'645474',300.00,'2025-02-05','paid','-','2025-02-05 08:45:00'),
(52,'625426',999999.00,'2025-02-10','pending','-','2025-02-10 12:05:28'),
(53,'614670',5500.00,'2025-02-10','pending','-','2025-02-10 14:11:21'),
(54,'627018',5500.00,'2025-02-10','pending','-','2025-02-10 14:11:47'),
(55,'778414',16000.00,'2025-02-10','pending','-','2025-02-10 15:27:34'),
(56,'876864',5500.00,'2025-02-11','pending','-','2025-02-11 03:03:55'),
(57,'233348',5500.00,'2025-02-11','pending','-','2025-02-11 03:07:02'),
(58,'260092',5500.00,'2025-02-11','pending','-','2025-02-11 03:09:20'),
(59,'601618',5500.00,'2025-02-11','pending','-','2025-02-11 03:09:48'),
(60,'262712',5500.00,'2025-02-11','pending','-','2025-02-11 03:18:02'),
(61,'678266',5500.00,'2025-02-11','pending','-','2025-02-11 03:18:16'),
(62,'152657',5500.00,'2025-02-11','pending','-','2025-02-11 03:18:38'),
(63,'754186',5500.00,'2025-02-11','pending','-','2025-02-11 03:18:49'),
(64,'412173',5500.00,'2025-02-11','pending','-','2025-02-11 03:19:12'),
(65,'972976',5500.00,'2025-02-11','pending','-','2025-02-11 03:19:35'),
(66,'913984',5500.00,'2025-02-11','pending','-','2025-02-11 03:20:26'),
(67,'355723',5500.00,'2025-02-11','pending','-','2025-02-11 03:20:43'),
(68,'367065',5500.00,'2025-02-11','pending','-','2025-02-11 03:28:50'),
(69,'800756',5500.00,'2025-02-11','pending','-','2025-02-11 03:29:10'),
(70,'141430',5500.00,'2025-02-11','pending','-','2025-02-11 03:29:37'),
(71,'791490',5500.00,'2025-02-11','pending','-','2025-02-11 03:30:07'),
(72,'684484',5500.00,'2025-02-11','pending','-','2025-02-11 03:30:31'),
(73,'501868',5500.00,'2025-02-11','pending','-','2025-02-11 03:31:01'),
(74,'900686',5500.00,'2025-02-11','pending','-','2025-02-11 03:31:30'),
(75,'744595',5500.00,'2025-02-11','pending','-','2025-02-11 03:32:13'),
(76,'290522',5500.00,'2025-02-11','pending','-','2025-02-11 03:32:49'),
(77,'361651',5500.00,'2025-02-11','pending','-','2025-02-11 03:33:03'),
(78,'581192',5500.00,'2025-02-11','pending','-','2025-02-11 03:35:05'),
(79,'920140',5500.00,'2025-02-11','pending','-','2025-02-11 03:35:18'),
(80,'192461',5500.00,'2025-02-11','pending','-','2025-02-11 03:35:32'),
(81,'692841',5500.00,'2025-02-11','pending','-','2025-02-11 03:35:58'),
(82,'287328',5500.00,'2025-02-11','pending','-','2025-02-11 03:36:20'),
(83,'733297',5500.00,'2025-02-11','pending','-','2025-02-11 03:36:33'),
(84,'781801',5500.00,'2025-02-11','pending','-','2025-02-11 03:37:15'),
(85,'738806',5500.00,'2025-02-11','pending','-','2025-02-11 03:39:44'),
(86,'276400',5500.00,'2025-02-11','pending','-','2025-02-11 03:40:01'),
(87,'429759',5500.00,'2025-02-11','pending','-','2025-02-11 03:40:13'),
(88,'212686',5500.00,'2025-02-11','pending','-','2025-02-11 03:40:40'),
(89,'106933',5500.00,'2025-02-11','pending','-','2025-02-11 03:41:03'),
(90,'264551',5500.00,'2025-02-11','pending','-','2025-02-11 03:41:22'),
(91,'147934',5500.00,'2025-02-11','pending','-','2025-02-11 03:41:37'),
(92,'625569',5500.00,'2025-02-11','pending','-','2025-02-11 03:42:34'),
(93,'541701',5500.00,'2025-02-11','pending','-','2025-02-11 03:42:45'),
(94,'199671',5500.00,'2025-02-11','pending','-','2025-02-11 03:42:57'),
(95,'286652',5500.00,'2025-02-11','pending','-','2025-02-11 03:43:30'),
(96,'238303',5500.00,'2025-02-11','pending','-','2025-02-11 03:43:51'),
(97,'120802',5500.00,'2025-02-11','pending','-','2025-02-11 03:48:49'),
(98,'340074',5500.00,'2025-02-11','pending','-','2025-02-11 03:49:16'),
(99,'468511',5500.00,'2025-02-11','pending','-','2025-02-11 03:51:56'),
(100,'457727',5500.00,'2025-02-11','pending','-','2025-02-11 04:40:54'),
(101,'544827',5500.00,'2025-02-11','pending','-','2025-02-11 04:45:13'),
(102,'187013',8000.00,'2025-02-11','pending','-','2025-02-11 04:45:56'),
(103,'891936',16000.00,'2025-02-11','pending','-','2025-02-11 04:47:22'),
(104,'557087',8000.00,'2025-02-11','pending','-','2025-02-11 05:10:19'),
(105,'258957',8000.00,'2025-02-11','pending','-','2025-02-11 05:10:49'),
(106,'974442',999999.00,'2025-02-11','pending','-','2025-02-11 05:52:38'),
(107,'905361',8000.00,'2025-02-11','pending','-','2025-02-11 05:53:19'),
(108,'372945',8000.00,'2025-02-11','pending','-','2025-02-11 12:34:14'),
(109,'761641',5500.00,'2025-02-18','pending','-','2025-02-18 11:56:53');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES
(11,'timer_payment','1000'),
(12,'timer_frame','20'),
(13,'timer_order','10');
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timer`
--

LOCK TABLES `timer` WRITE;
/*!40000 ALTER TABLE `timer` DISABLE KEYS */;
INSERT INTO `timer` VALUES
(4,'screen_order',30,2,'2025-02-10 13:35:26','2025-02-10 16:21:19'),
(5,'screen_frame',30,2,'2025-02-10 13:35:26','2025-02-10 16:21:19'),
(6,'screen_payment',2000,2,'2025-02-10 13:35:26','2025-02-11 03:04:41'),
(8,'screen_select_camera',30,2,'2025-02-10 13:35:26','2025-02-10 16:21:19'),
(9,'screen_capture_photo',3000,2,'2025-02-10 13:35:26','2025-02-19 01:32:05'),
(10,'screen_filter',30,2,'2025-02-10 13:35:26','2025-02-10 16:21:19'),
(11,'screen_print',30,2,'2025-02-10 13:35:26','2025-02-10 16:21:19'),
(12,'screen_order',20,5,'2025-02-10 15:26:15','2025-02-10 16:17:45'),
(13,'screen_payment',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(14,'screen_frame',20,5,'2025-02-10 15:26:15','2025-02-10 16:17:45'),
(15,'screen_select_camera',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(16,'screen_capture_photo',20,5,'2025-02-10 15:26:15','2025-02-10 16:17:45'),
(17,'screen_filter',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31'),
(18,'screen_print',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:12'),
(19,'screen_finish',20,5,'2025-02-10 15:26:15','2025-02-10 16:18:31');
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
  UNIQUE KEY `kode_voucher` (`kode_voucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher`
--

LOCK TABLES `voucher` WRITE;
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
INSERT INTO `voucher` VALUES
('0A64DZHL','2025-02-01',7000),
('4CRLDWB6','2025-01-01',7000);
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

-- Dump completed on 2025-02-19 12:27:07
