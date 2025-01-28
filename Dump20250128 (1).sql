-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: outilelection
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bureaux`
--

DROP TABLE IF EXISTS `bureaux`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bureaux` (
  `id_bureau` int(11) NOT NULL AUTO_INCREMENT,
  `nom_bureau` varchar(45) NOT NULL,
  `nb_inscrits` int(11) NOT NULL,
  PRIMARY KEY (`id_bureau`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bureaux`
--

LOCK TABLES `bureaux` WRITE;
/*!40000 ALTER TABLE `bureaux` DISABLE KEYS */;
INSERT INTO `bureaux` VALUES (76,'vicoigne',1010),(77,'bureau1',2200),(78,'bureau2',500),(79,'trith',1500),(80,'test',1000);
/*!40000 ALTER TABLE `bureaux` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidat`
--

DROP TABLE IF EXISTS `candidat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidat` (
  `id_candidat` int(11) NOT NULL AUTO_INCREMENT,
  `Nom_candidat` varchar(100) DEFAULT NULL,
  `prenom_candidat` varchar(100) DEFAULT NULL,
  `parti` varchar(100) DEFAULT NULL,
  `id_election` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_candidat`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidat`
--

LOCK TABLES `candidat` WRITE;
/*!40000 ALTER TABLE `candidat` DISABLE KEYS */;
INSERT INTO `candidat` VALUES (-1,'Vote Nul','','',32),(0,'Vote Blanc','','',32),(28,'michel','bertrand','les vieux',35),(29,'zidane','zizou','les footeux',35),(30,'mbappe','kyky','les nulos',35),(31,'pitt','brad','les acteurs',35);
/*!40000 ALTER TABLE `candidat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametre`
--

DROP TABLE IF EXISTS `parametre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametre` (
  `id_election` int(11) NOT NULL AUTO_INCREMENT,
  `type_election` varchar(255) NOT NULL,
  `date_election` date NOT NULL,
  `date_fin` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_config` int(11) NOT NULL,
  PRIMARY KEY (`id_election`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametre`
--

LOCK TABLES `parametre` WRITE;
/*!40000 ALTER TABLE `parametre` DISABLE KEYS */;
INSERT INTO `parametre` VALUES (35,'Législative','2025-01-15','2025-02-02','2024-12-20 08:12:06','2024-12-20 08:12:06',0);
/*!40000 ALTER TABLE `parametre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resultats`
--

DROP TABLE IF EXISTS `resultats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resultats` (
  `id_resultat` int(11) NOT NULL AUTO_INCREMENT,
  `id_bureau` int(11) NOT NULL,
  `id_candidat` int(11) DEFAULT NULL,
  `nombre_voix` int(11) DEFAULT 0,
  PRIMARY KEY (`id_resultat`),
  KEY `id_bureau` (`id_bureau`),
  KEY `id_candidat` (`id_candidat`),
  CONSTRAINT `resultats_ibfk_1` FOREIGN KEY (`id_bureau`) REFERENCES `bureaux` (`id_bureau`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resultats_ibfk_2` FOREIGN KEY (`id_candidat`) REFERENCES `candidat` (`id_candidat`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resultats`
--

LOCK TABLES `resultats` WRITE;
/*!40000 ALTER TABLE `resultats` DISABLE KEYS */;
INSERT INTO `resultats` VALUES (110,76,-1,5),(111,76,0,15),(112,76,28,20),(113,76,29,300),(114,76,30,120),(115,76,31,154),(116,76,0,0),(117,76,-1,0),(118,77,-1,0),(119,77,0,100),(120,77,28,54),(121,77,29,36),(122,77,30,256),(123,77,31,452),(124,77,0,0),(125,77,-1,0),(126,78,-1,5),(127,78,0,100),(128,78,28,42),(129,78,29,26),(130,78,30,12),(131,78,31,65),(132,78,0,0),(133,78,-1,0),(134,79,-1,5),(135,79,0,100),(136,79,28,152),(137,79,29,562),(138,79,30,14),(139,79,31,23),(140,79,0,0),(141,79,-1,0),(142,80,-1,5),(143,80,0,15),(144,80,28,123),(145,80,29,346),(146,80,30,128),(147,80,31,256),(148,80,0,0),(149,80,-1,0);
/*!40000 ALTER TABLE `resultats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sauvegardes`
--

DROP TABLE IF EXISTS `sauvegardes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sauvegardes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_election` int(11) NOT NULL,
  `id_bureau` int(11) NOT NULL,
  `nb_electeurs` int(11) NOT NULL,
  `date_election` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_election` (`id_election`),
  KEY `id_bureau` (`id_bureau`),
  CONSTRAINT `sauvegardes_ibfk_1` FOREIGN KEY (`id_election`) REFERENCES `parametre` (`id_election`),
  CONSTRAINT `sauvegardes_ibfk_2` FOREIGN KEY (`id_bureau`) REFERENCES `bureaux` (`id_bureau`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sauvegardes`
--

LOCK TABLES `sauvegardes` WRITE;
/*!40000 ALTER TABLE `sauvegardes` DISABLE KEYS */;
INSERT INTO `sauvegardes` VALUES (40,35,76,0,NULL),(41,35,77,0,NULL),(42,35,78,0,NULL),(43,35,79,0,NULL),(44,35,80,0,NULL);
/*!40000 ALTER TABLE `sauvegardes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(45) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role_user` varchar(45) NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'VincentB','vincent123','admin'),(3,'aze','123','utilisateur'),(4,'test','test1','admin');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-28 13:27:38
