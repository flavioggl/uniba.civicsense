-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: civicsense
-- ------------------------------------------------------
-- Server version	5.7.20-log

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_admins_users` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1);
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `cap` varchar(6) NOT NULL,
  `city_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_city_admins_users1_idx` (`id`),
  CONSTRAINT `fk_city_admins_users1` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (22,'70021','Acquaviva delle Fonti'),(23,'70121','Bari');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
  `institution_id` int(11) NOT NULL,
  `trouble_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`institution_id`,`trouble_id`,`city_id`),
  KEY `fk_institutions_has_troubles_troubles1_idx` (`trouble_id`),
  KEY `fk_institutions_has_troubles_institutions1_idx` (`institution_id`),
  KEY `fk_contracts_cities1_idx` (`city_id`),
  CONSTRAINT `fk_contracts_cities1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `fk_institutions_has_troubles_institutions1` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`),
  CONSTRAINT `fk_institutions_has_troubles_troubles1` FOREIGN KEY (`trouble_id`) REFERENCES `troubles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts`
--

LOCK TABLES `contracts` WRITE;
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
INSERT INTO `contracts` VALUES (3,1,22),(3,2,22),(2,3,22),(2,3,23),(4,7,22),(2,8,22),(2,8,23);
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institutions`
--

DROP TABLE IF EXISTS `institutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `institutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `vat` varchar(15) NOT NULL,
  `service_description` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institutions`
--

LOCK TABLES `institutions` WRITE;
/*!40000 ALTER TABLE `institutions` DISABLE KEYS */;
INSERT INTO `institutions` VALUES (2,'Acquedotto Pugliese','00347000721','Acqua potabile'),(3,'Stradale srl','90982120893','Manutenzione stradale'),(4,'Rifiuti','259286098590','raccogliamo rifiuti'),(5,'Enel Italia S.r.l.','06655971007','Energia, Luce e Gas');
/*!40000 ALTER TABLE `institutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issues`
--

DROP TABLE IF EXISTS `issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) NOT NULL,
  `status` enum('PROCESSING','SOLVED','FAILED') NOT NULL,
  `creation_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `closing_detail` varchar(128) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `closing_datetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_issues_teams1_idx` (`team_id`),
  KEY `fk_issues_institutions1_idx` (`institution_id`),
  CONSTRAINT `fk_issues_institutions1` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`),
  CONSTRAINT `fk_issues_teams1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issues`
--

LOCK TABLES `issues` WRITE;
/*!40000 ALTER TABLE `issues` DISABLE KEYS */;
INSERT INTO `issues` VALUES (1,2,'PROCESSING','2019-02-15 18:55:58',NULL,2,NULL),(2,3,'FAILED','2019-02-15 10:36:46','Chiamare guardia nazionale',4,'2019-02-17 16:30:32'),(4,3,'PROCESSING','2019-02-18 16:29:45',NULL,NULL,NULL);
/*!40000 ALTER TABLE `issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `managers`
--

DROP TABLE IF EXISTS `managers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `tax_code` varchar(24) NOT NULL,
  `birthday` date NOT NULL,
  `institution_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_managers_institutions1_idx` (`institution_id`),
  CONSTRAINT `fk_managers_institutions1` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`),
  CONSTRAINT `fk_managers_users1` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `managers`
--

LOCK TABLES `managers` WRITE;
/*!40000 ALTER TABLE `managers` DISABLE KEYS */;
INSERT INTO `managers` VALUES (2,'gnnacq90u2edadsk','1990-01-31',2),(3,'CCHJLASF89ASF00','1982-11-12',3),(21,'cdcmtikj4i234','1990-02-02',4),(24,'RMZN976ASTDF9','1972-09-21',5);
/*!40000 ALTER TABLE `managers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialists`
--

DROP TABLE IF EXISTS `specialists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialists` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_specialists_teams1_idx` (`team_id`),
  CONSTRAINT `fk_specialists_teams1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  CONSTRAINT `fk_specialists_users1` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialists`
--

LOCK TABLES `specialists` WRITE;
/*!40000 ALTER TABLE `specialists` DISABLE KEYS */;
INSERT INTO `specialists` VALUES (7,1),(14,1),(15,1),(16,2),(17,2),(19,4),(20,4);
/*!40000 ALTER TABLE `specialists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_teams_institutions1_idx` (`institution_id`),
  CONSTRAINT `fk_teams_institutions1` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (2,2),(1,3),(4,3);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `code` varchar(8) NOT NULL,
  `creation_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(128) NOT NULL,
  `priority` enum('GREEN','YELLOW','RED') NOT NULL DEFAULT 'GREEN',
  `photo_src` varchar(128) DEFAULT NULL,
  `video_src` varchar(128) DEFAULT NULL,
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `city_cap` varchar(6) DEFAULT NULL,
  `trouble_id` int(11) NOT NULL,
  `issue_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`code`),
  KEY `fk_tickets_issues1_idx` (`issue_id`),
  KEY `fk_tickets_troubles1_idx` (`trouble_id`),
  CONSTRAINT `fk_tickets_issues1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`),
  CONSTRAINT `fk_tickets_troubles1` FOREIGN KEY (`trouble_id`) REFERENCES `troubles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES ('BHY546F1','2019-02-18 16:50:32','buca fastidiosa','YELLOW','/resources/img/tickets/BHY546F1.jpg',NULL,40.8967993,16.8458194,'70021',1,4),('GGV344E5','2019-01-18 12:12:03','Perdita acqua dall\'asfalto','RED','/resources/img/tickets/GGV344E5.jpg',NULL,40.8950842,16.8473492,'70021',3,1),('KGU146H6','2019-02-18 16:29:45','bucooo','YELLOW','/resources/img/tickets/KGU146H6.jpg',NULL,40.8967998,16.845838,'70021',1,4),('PYI862S8','2019-01-21 13:31:43','strada allagata','YELLOW','/resources/img/tickets/PYI862S8.jpg',NULL,40.88,16.8473492,'70021',1,NULL),('QLP340R6','2019-01-14 16:26:59','fontana arrugginita','GREEN','/resources/img/tickets/QLP340R6.jpg',NULL,40.8909,16.8437,'70021',8,NULL),('TAN413V9','2019-01-21 15:08:01','ponte crollato','RED','/resources/img/tickets/TAN413V9.jpg',NULL,40.8909432,16.8437567,'70021',2,NULL),('TIE533Z8','2019-01-18 13:17:49','perdita tubo dell\'acqua (a spruzzo fooorte)','YELLOW','/resources/img/tickets/TIE533Z8.jpg',NULL,40.8950842,16.8473492,'70021',3,1),('TKA990J5','2019-01-10 23:04:18','buco enorme','GREEN','/resources/img/tickets/TKA990J5.jpg',NULL,40.9007377,16.8493163,'70021',1,2),('WQE665P8','2019-01-12 16:11:13','segnale rotto','GREEN','/resources/img/tickets/WQE665P8.jpg',NULL,40.8909,16.8437,'70021',2,NULL),('XBG978V8','2019-01-21 13:14:14','illuminazione strada non funzionante','YELLOW','/resources/img/tickets/XBG978V8.jpg',NULL,40.8950842,16.8473492,'70021',5,NULL),('ZJT346Y6','2019-01-18 13:21:02','Strada crepata','GREEN','/resources/img/tickets/ZJT346Y6.jpg',NULL,40.891605,16.844505,'70021',1,NULL);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `troubles`
--

DROP TABLE IF EXISTS `troubles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `troubles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `troubles`
--

LOCK TABLES `troubles` WRITE;
/*!40000 ALTER TABLE `troubles` DISABLE KEYS */;
INSERT INTO `troubles` VALUES (1,'Buche stradali'),(2,'Segnalazione stradale mancante/guasta'),(3,'Tubature rotte'),(5,'Lampione fulminato'),(6,'Mancanza enegia elettrica'),(7,'Mancato ritiro spazzatura'),(8,'Fontana guasta'),(9,'Strada inagibile');
/*!40000 ALTER TABLE `troubles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `surname` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_uindex` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@admin.com','90add5e42b9b65ed38e25de6677ca1a4','Davide','Acquaviva'),(2,'acquedotto@com.com','e0eeaef2adf38d231ca03ef0180f2bb9','Gianni','Acqua'),(3,'strada@com.com','54f787fde73e1abfeea4422c7dcc29f8','Ciccio','Stradone'),(7,'strada.spec3@com.com','54f787fde73e1abfeea4422c7dcc29f8','Ciccio','Capuito'),(14,'strada.spec1@com.com','54f787fde73e1abfeea4422c7dcc29f8','Maria','Fosfati'),(15,'strada.spec2@com.com','54f787fde73e1abfeea4422c7dcc29f8','Giulio','Impero'),(16,'acquedotto.spec1@com.com','e0eeaef2adf38d231ca03ef0180f2bb9','Gino','Water'),(17,'acquedotto.spec2@com.com','e0eeaef2adf38d231ca03ef0180f2bb9','Gina','Ice'),(18,'strada.tecnico@com.com','54f787fde73e1abfeea4422c7dcc29f8','mario','gialli'),(19,'strada.spec6@com.com','54f787fde73e1abfeea4422c7dcc29f8','Mina','Vagante'),(20,'strada.spec7@com.com','54f787fde73e1abfeea4422c7dcc29f8','Luca','Medici'),(21,'rifiuti@com.com','c687ac01c8f8d5f2ed1cda2c622e14b0','Scarto','Monnezza'),(22,'70021@city.com','215a27555d3da243f67da115b2a0a5a5','David','Carlucci'),(23,'70121@city.com','2564d6960f296a4027f6ac4dd2338a4d','Nicola','Vendola'),(24,'enel@com.com','70764b86123d85b64b39da8125474b0b','Lino','Romanazzi');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-18 20:22:05
