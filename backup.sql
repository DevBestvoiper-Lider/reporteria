-- MySQL dump 10.11
--
-- Host: localhost    Database: vos3000
-- ------------------------------------------------------
-- Server version	5.0.96-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `e_usuariosclientes`
--

DROP TABLE IF EXISTS `e_usuariosclientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `e_usuariosclientes` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` text collate utf8_spanish_ci NOT NULL,
  `usuario` text collate utf8_spanish_ci NOT NULL,
  `password` text collate utf8_spanish_ci NOT NULL,
  `perfil` text collate utf8_spanish_ci NOT NULL,
  `foto` text collate utf8_spanish_ci NOT NULL,
  `ultimo_login` datetime NOT NULL,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `customeraccountname` varchar(255) collate utf8_spanish_ci NOT NULL,
  `estado` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `e_usuariosclientes`
--

LOCK TABLES `e_usuariosclientes` WRITE;
/*!40000 ALTER TABLE `e_usuariosclientes` DISABLE KEYS */;
INSERT INTO `e_usuariosclientes` VALUES (1,'luis sandon','admin','$2a$07$asxx54ahjppf45sd87a5auyWiZzr3J7TelQvPQeFNfvAYT1FGAEdu','Administrador','','2023-10-17 12:17:24','2023-10-17 17:17:24','Bestvoiper',0),(149,'COGUASIMALES','COGUASIMALES','$2a$07$asxx54ahjppf45sd87a5au.d1lbh3cLP8.RmsoYXtIA5Xj7Y3I7Ay','Cliente','','2023-10-27 09:56:53','2023-10-27 14:56:53','COGUASIMALES',0),(150,'ALDEAMO','ALDEAMO','$2a$07$asxx54ahjppf45sd87a5auGeU7V3PJ0JYiCFMBzheu66mPrkpxnO6','Cliente','','2023-09-28 08:06:08','2023-09-28 13:06:37','ALDEAMO',0),(151,'AlexDistribuidor','AlexDistribuidor','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-03 10:59:42','2023-10-03 16:01:18','AlexDistribuidor',0),(152,'TMIk','TMIk','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','TMIk',1),(153,'MiguelDistribuidor','MiguelDistribuidor','$2a$07$asxx54ahjppf45sd87a5aulfxlFG8C9FSo4mefdE025X3MbfKWqjK','Cliente','','2023-10-25 11:47:57','2023-10-25 16:47:57','MiguelDistribuidor',0),(154,'NAzit Moreno','NAzit Moreno','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-03 14:36:06','2023-10-03 19:36:06','NAzit Moreno',0),(155,'CONINSA','CONINSA','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-09-26 15:35:58','2023-09-26 20:35:58','CONINSA',1),(156,'Eduardo','Eduardo','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Eduardo',1),(157,'EMA-RE-CLI-PREMIUM','EMA-RE-CLI-PREMIUM','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','EMA-RE-CLI-PREMIUM',1),(158,'Alex2Directo','Alex2Directo','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Alex2Directo',1),(159,'INT-MAX-CLI-PREMIUM','INT-MAX-CLI-PREMIUM','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','INT-MAX-CLI-PREMIUM',1),(160,'INTERAC-EMA-CLI-PREMIUM','INTERAC-EMA-CLI-PREMIUM','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','INTERAC-EMA-CLI-PREMIUM',1),(161,'SERV-CLI-COL','SERV-CLI-COL','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','SERV-CLI-COL',1),(162,'CHK-USA','CHK-USA','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','CHK-USA',1),(163,'Mobilitech','Mobilitech','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Mobilitech',1),(164,'TelenetPeru','TelenetPeru','$2a$07$asxx54ahjppf45sd87a5auNgX8R54LAyYV50Ys4aHsYI7jTc/yMea','Cliente','','2023-09-26 10:18:43','2023-09-26 15:20:41','TelenetPeru',0),(165,'Aztel','Aztel','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-04 18:03:06','2023-10-04 23:03:06','Aztel',0),(166,'Mobilitech-USA-NOUSAR','Mobilitech-USA-NOUSAR','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Mobilitech-USA-NOUSAR',1),(167,'Mobilitech-Inbound','Mobilitech-Inbound','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Mobilitech-Inbound',1),(168,'FirmaDidMobiitech','FirmaDidMobiitech','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','FirmaDidMobiitech',1),(169,'Atek','Atek','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-09-26 15:56:32','2023-09-26 21:31:36','Atek',0),(170,'Telonate.co.uk','Telonate.co.uk','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Telonate.co.uk',1),(171,'Lider Company Audifarma','Lider Company Audifarma','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-12 17:09:40','2023-10-12 22:09:40','Lider Company Audifarma',0),(172,'Bestvoiper','Bestvoiper','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-09-26 15:13:30','2023-09-26 20:13:47','Bestvoiper',0),(173,'Lider Company 12de12','Lider Company 12de12','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','Lider Company 12de12',1),(174,'HEMERSON HULLOA','HEMERSON HULLOA','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','HEMERSON HULLOA',1),(175,'BPOGLOBALSERVICES','BPOGLOBALSERVICES','$2a$07$asxx54ahjppf45sd87a5auC3M2oNlGAY/Z/L2ILZKyLZIe5NLhSZO','Cliente','','2023-10-18 08:22:56','2023-10-18 13:22:56','BPOGLOBALSERVICES',0),(176,'NetAllCorrect','NetAllCorrect','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','NetAllCorrect',1),(177,'DANY GALDON','DANY GALDON','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','DANY GALDON',1),(178,'MENDOBER','MENDOBER','$2a$07$asxx54ahjppf45sd87a5auTrHy3pgpPtWp9CG6XAzp4aCT5jxclvS','Cliente','','2023-09-26 15:36:29','2023-09-26 20:36:39','MENDOBER',0),(179,'NGSO','NGSO','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-09-26 15:39:10','2023-09-26 20:39:10','NGSO',1),(180,'UTP','UTP','$2a$07$asxx54ahjppf45sd87a5auNbtQ1HimZY0m81XOshV4D/Y.ih20YoS','Cliente','','2023-10-13 19:30:27','2023-10-14 00:30:27','UTP',0),(181,'Lina Coca','Lina Coca','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-16 12:41:37','2023-10-16 17:41:37','Lina Coca',0),(182,'distrilibros','distrilibros','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-25 22:42:45','distrilibros',1),(183,'Digimotos','Digimotos','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-26 21:04:05','Digimotos',0),(184,'voipserver5','voipserver5','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-27 14:25:37','voipserver5',0),(185,'Exequial','Exequial','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-27 20:33:43','Exequial',0),(186,'MoraCero','MoraCero','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-09-27 22:00:21','MoraCero',0),(187,'Pruebas Tecnicas','Pruebas Tecnicas','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-06 16:24:32','Pruebas Tecnicas',0),(188,'Dondoctor','Dondoctor','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-10 23:49:00','Dondoctor',0),(189,'DATAUNIITYCENTER','DATAUNIITYCENTER','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-12 14:11:40','2023-10-12 19:11:40','DATAUNIITYCENTER',0),(190,'LiderCompany12de12','LiderCompany12de12','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-17 12:03:43','2023-10-17 17:03:43','LiderCompany12de12',0),(191,'LiderCompany','LiderCompany','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-30 17:30:40','2023-10-30 22:30:40','LiderCompany',0),(192,'Diego Rodriguez','Diego Rodriguez','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-18 17:43:50','2023-10-18 22:43:50','Diego Rodriguez',0),(193,'Asistencia Latinna','Asistencia Latinna','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-17 14:47:18','Asistencia Latinna',0),(194,'Fia Funeral Exequial','Fia Funeral Exequial','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-18 15:16:03','Fia Funeral Exequial',0),(196,'PBX alex','PBX alex','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-19 21:33:35','PBX alex',0),(197,'INBOUNDDIDCOLOMBIA333','INBOUNDDIDCOLOMBIA333','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','2023-10-21 10:36:06','2023-10-21 15:36:06','INBOUNDDIDCOLOMBIA333',0),(198,'CarlosRedondo','CarlosRedondo','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-25 18:59:58','CarlosRedondo',0),(199,'Voipserver1','Voipserver1','$2a$07$asxx54ahjppf45sd87a5au8WBy88ML0Fxj0rqYWk6epKEwi.IB7yK','Cliente','','0000-00-00 00:00:00','2023-10-25 20:38:18','Voipserver1',0);
/*!40000 ALTER TABLE `e_usuariosclientes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-30 18:26:55
