-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: sala_informatica
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `devolucao`
--

DROP TABLE IF EXISTS `devolucao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devolucao` (
  `id_devolucao` int NOT NULL AUTO_INCREMENT,
  `id_retirada` int DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL,
  `hora_devolucao` time DEFAULT NULL,
  `estadoposuso` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_devolucao`),
  KEY `devolucao_ibfk_1` (`id_retirada`),
  CONSTRAINT `devolucao_ibfk_1` FOREIGN KEY (`id_retirada`) REFERENCES `retirada` (`id_retirada`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devolucao`
--

LOCK TABLES `devolucao` WRITE;
/*!40000 ALTER TABLE `devolucao` DISABLE KEYS */;
/*!40000 ALTER TABLE `devolucao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipamento`
--

DROP TABLE IF EXISTS `equipamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipamento` (
  `id_equipamento` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `numero_patrimonio` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `estado_conservacao` enum('novo','usado','em_manutencao') COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('disponivel','em_uso','em_manutencao') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_equipamento`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipamento`
--

LOCK TABLES `equipamento` WRITE;
/*!40000 ALTER TABLE `equipamento` DISABLE KEYS */;
INSERT INTO `equipamento` VALUES (132,'Tablet','P01','2023-01-10','novo','disponivel'),(133,'Tablet','P02','2023-01-15','novo','disponivel'),(134,'Tablet','P03','2023-01-20','novo','disponivel'),(135,'Tablet','P04','2023-01-25','novo','disponivel'),(136,'Tablet','P05','2023-02-01','novo','disponivel'),(137,'Tablet','P06','2023-02-05','novo','disponivel'),(138,'Tablet','P07','2023-02-10','novo','disponivel'),(139,'Tablet','P08','2023-02-15','novo','disponivel'),(140,'Tablet','P09','2023-02-20','novo','disponivel'),(141,'Tablet','P10','2023-02-25','novo','disponivel'),(142,'Tablet','P11','2023-03-01','novo','disponivel'),(143,'Tablet','P12','2023-03-05','novo','disponivel'),(144,'Tablet','P13','2023-03-10','novo','disponivel'),(145,'Tablet','P14','2023-03-15','novo','disponivel'),(146,'Tablet','P15','2023-03-20','novo','disponivel'),(147,'Tablet','P16','2023-03-25','novo','disponivel'),(148,'Tablet','P17','2023-04-01','novo','disponivel'),(149,'Tablet','P18','2023-04-05','novo','disponivel'),(150,'Tablet','P19','2023-04-10','novo','disponivel'),(151,'Tablet','P20','2023-04-15','novo','disponivel'),(152,'Tablet','P21','2023-04-20','novo','disponivel'),(153,'Tablet','P22','2023-04-25','novo','disponivel'),(154,'Tablet','P23','2023-05-01','novo','disponivel'),(155,'Tablet','P24','2023-05-05','novo','disponivel'),(156,'Tablet','P25','2023-05-10','novo','disponivel'),(157,'Tablet','P26','2023-05-15','novo','disponivel'),(158,'Tablet','P27','2023-05-20','novo','disponivel'),(159,'Tablet','P28','2023-05-25','novo','disponivel'),(160,'Tablet','P29','2023-06-01','novo','disponivel'),(161,'Tablet','P30','2023-06-05','novo','disponivel'),(162,'Tablet','P31','2023-06-10','novo','disponivel'),(163,'Tablet','P32','2023-06-15','novo','disponivel'),(164,'Tablet','P33','2023-06-20','novo','disponivel'),(165,'Tablet','P34','2023-06-25','novo','disponivel'),(166,'Tablet','P35','2023-07-01','novo','disponivel'),(167,'Tablet','P36','2023-07-05','novo','disponivel'),(168,'Tablet','P37','2023-07-10','novo','disponivel'),(169,'Tablet','P38','2023-07-15','novo','disponivel'),(170,'Tablet','P39','2023-07-20','novo','disponivel'),(171,'Tablet','P40','2023-07-25','novo','disponivel'),(172,'Tablet','P41','2023-08-01','novo','disponivel'),(173,'Tablet','P42','2023-08-05','novo','disponivel'),(174,'Tablet','P43','2023-08-10','novo','disponivel'),(175,'Tablet','P44','2023-08-15','novo','disponivel'),(176,'Tablet','P45','2023-08-20','novo','disponivel'),(177,'Tablet','P46','2023-08-25','novo','disponivel'),(178,'Tablet','P47','2023-09-01','novo','disponivel'),(179,'Tablet','P48','2023-09-05','novo','disponivel'),(180,'Notebook','C01','2022-01-10','novo','disponivel'),(181,'Notebook','C02','2022-01-20','novo','disponivel'),(182,'Notebook','C03','2022-01-30','novo','disponivel'),(183,'Notebook','C04','2022-02-10','novo','disponivel'),(184,'Notebook','C05','2022-02-20','novo','disponivel'),(185,'Notebook','C06','2022-02-28','novo','disponivel'),(186,'Notebook','C07','2022-03-10','novo','disponivel'),(187,'Notebook','C08','2022-03-20','novo','disponivel'),(188,'Notebook','C09','2022-03-30','novo','disponivel'),(189,'Notebook','C10','2022-04-10','novo','disponivel'),(190,'Notebook','C11','2022-04-20','novo','disponivel'),(191,'Notebook','C12','2022-04-30','novo','disponivel'),(192,'Notebook','C13','2022-05-10','novo','disponivel'),(193,'Notebook','C14','2022-05-20','novo','disponivel'),(194,'Notebook','C15','2022-05-30','novo','disponivel'),(195,'Notebook','C16','2022-06-10','novo','disponivel'),(196,'Notebook','C17','2022-06-20','novo','disponivel'),(197,'Notebook','C18','2022-06-30','novo','disponivel'),(198,'Notebook','C19','2022-07-10','novo','disponivel'),(199,'Notebook','C20','2022-07-20','novo','disponivel'),(200,'Notebook','C21','2022-07-30','novo','disponivel'),(201,'Notebook','C22','2022-08-10','novo','disponivel'),(202,'Notebook','C23','2022-08-20','novo','disponivel'),(203,'Notebook','C24','2022-08-30','novo','disponivel'),(204,'Notebook','C25','2022-09-10','novo','disponivel'),(205,'Notebook','C26','2022-09-20','novo','disponivel'),(206,'Notebook','C27','2022-09-30','novo','disponivel'),(207,'Notebook','C28','2022-10-10','novo','disponivel'),(208,'Notebook','C29','2022-10-20','novo','disponivel'),(209,'Notebook','C30','2022-10-30','novo','disponivel'),(210,'Notebook','C31','2022-11-10','novo','disponivel'),(211,'Notebook','C32','2022-11-20','novo','disponivel'),(212,'Notebook','C33','2022-11-30','novo','disponivel'),(213,'Notebook','C34','2022-12-10','novo','disponivel'),(214,'Notebook','C35','2022-12-20','novo','disponivel'),(215,'Notebook','C36','2022-12-30','novo','disponivel'),(216,'Notebook','C37','2023-01-10','novo','disponivel'),(217,'Notebook','C38','2023-01-20','novo','disponivel'),(218,'Notebook','C39','2023-01-30','novo','disponivel'),(219,'Notebook','C40','2023-02-10','novo','disponivel');
/*!40000 ALTER TABLE `equipamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipamento_retirado`
--

DROP TABLE IF EXISTS `equipamento_retirado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipamento_retirado` (
  `id_retirada` int NOT NULL,
  `id_equipamento` int NOT NULL,
  PRIMARY KEY (`id_retirada`,`id_equipamento`),
  KEY `id_equipamento` (`id_equipamento`),
  CONSTRAINT `equipamento_retirado_ibfk_1` FOREIGN KEY (`id_retirada`) REFERENCES `retirada` (`id_retirada`) ON DELETE CASCADE,
  CONSTRAINT `equipamento_retirado_ibfk_2` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id_equipamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipamento_retirado`
--

LOCK TABLES `equipamento_retirado` WRITE;
/*!40000 ALTER TABLE `equipamento_retirado` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipamento_retirado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historico_manutencao`
--

DROP TABLE IF EXISTS `historico_manutencao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historico_manutencao` (
  `id_manutencao` int NOT NULL AUTO_INCREMENT,
  `data_inicio` date NOT NULL,
  `descricao` text COLLATE utf8mb4_general_ci,
  `id_equipamento` int DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  PRIMARY KEY (`id_manutencao`),
  KEY `id_equipamento` (`id_equipamento`),
  CONSTRAINT `historico_manutencao_ibfk_1` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id_equipamento`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico_manutencao`
--

LOCK TABLES `historico_manutencao` WRITE;
/*!40000 ALTER TABLE `historico_manutencao` DISABLE KEYS */;
/*!40000 ALTER TABLE `historico_manutencao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itens_reserva`
--

DROP TABLE IF EXISTS `itens_reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `itens_reserva` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_reserva` int NOT NULL,
  `id_equipamento` int NOT NULL,
  `status` enum('aguardando','retirado','devolvido') NOT NULL DEFAULT 'aguardando',
  PRIMARY KEY (`id`),
  KEY `id_reserva` (`id_reserva`),
  KEY `id_equipamento` (`id_equipamento`),
  CONSTRAINT `itens_reserva_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `itens_reserva_ibfk_2` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id_equipamento`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itens_reserva`
--

LOCK TABLES `itens_reserva` WRITE;
/*!40000 ALTER TABLE `itens_reserva` DISABLE KEYS */;
INSERT INTO `itens_reserva` VALUES (1,1,156,'aguardando'),(2,2,132,'aguardando'),(3,2,133,'aguardando'),(4,2,134,'aguardando'),(5,3,132,'aguardando'),(6,3,133,'aguardando'),(7,3,134,'aguardando'),(8,4,132,'aguardando'),(9,4,133,'aguardando'),(10,4,134,'aguardando');
/*!40000 ALTER TABLE `itens_reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login` (
  `usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` VALUES ('antony','123','antony'),('pedro','pedro2005p','Pedro'),('Proatec','jfs2025','Proatec');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professor`
--

DROP TABLE IF EXISTS `professor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professor` (
  `id_professor` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professor`
--

LOCK TABLES `professor` WRITE;
/*!40000 ALTER TABLE `professor` DISABLE KEYS */;
INSERT INTO `professor` VALUES (30,'Rose Freitas'),(31,'Rose Cleide'),(32,'Morena'),(33,'Rodrigo'),(34,'Naira'),(35,'Matheus'),(36,'Gislaine'),(37,'Arlete'),(38,'Fabiana');
/*!40000 ALTER TABLE `professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_professor` int NOT NULL,
  `data_reserva` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `id_sala` int NOT NULL,
  `disciplina` varchar(100) NOT NULL,
  `status` enum('pendente','confirmada','cancelada','concluida') NOT NULL DEFAULT 'pendente',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `observacoes` text,
  PRIMARY KEY (`id`),
  KEY `id_professor` (`id_professor`),
  KEY `id_sala` (`id_sala`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` VALUES (1,34,'2025-04-25','09:45:00','11:00:00',9,'Historia','cancelada','2025-04-25 12:38:01','efsdfd'),(2,35,'2025-04-28','08:45:00','10:00:00',10,'Tecnologia','cancelada','2025-04-28 11:38:18',''),(3,32,'2025-04-28','08:45:00','12:00:00',6,'Historia','cancelada','2025-04-28 11:44:56',''),(4,33,'2025-05-06','10:00:00','12:00:00',8,'Tecnologia','cancelada','2025-05-06 12:26:04','Ta daora');
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retirada`
--

DROP TABLE IF EXISTS `retirada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `retirada` (
  `id_retirada` int NOT NULL AUTO_INCREMENT,
  `data_retirada` date NOT NULL,
  `hora_retirada` time NOT NULL,
  `id_professor` int DEFAULT NULL,
  `id_sala` int DEFAULT NULL,
  PRIMARY KEY (`id_retirada`),
  KEY `id_professor` (`id_professor`),
  KEY `id_sala` (`id_sala`),
  CONSTRAINT `retirada_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `retirada_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retirada`
--

LOCK TABLES `retirada` WRITE;
/*!40000 ALTER TABLE `retirada` DISABLE KEYS */;
/*!40000 ALTER TABLE `retirada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sala`
--

DROP TABLE IF EXISTS `sala`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sala` (
  `id_sala` int NOT NULL AUTO_INCREMENT,
  `serie` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_sala`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sala`
--

LOCK TABLES `sala` WRITE;
/*!40000 ALTER TABLE `sala` DISABLE KEYS */;
INSERT INTO `sala` VALUES (1,'6°A','Ensino Fundamental'),(2,'6°B','Ensino Fundamental'),(3,'6°C','Ensino Fundamental'),(4,'7°A','Ensino Fundamental'),(5,'7°B','Ensino Fundamental'),(6,'7°C','Ensino Fundamental'),(7,'8°A','Ensino Fundamental'),(8,'8°B','Ensino Fundamental'),(9,'8°C','Ensino Fundamental'),(10,'9°A','Ensino Fundamental'),(11,'9°B','Ensino Fundamental'),(12,'9°C','Ensino Fundamental'),(13,'1°A','Ensino Médio'),(14,'1°B','Ensino Médio'),(15,'1°C','Ensino Médio'),(16,'2°A','Ensino Médio'),(17,'2°B','Ensino Médio'),(18,'2°C','Ensino Médio'),(19,'3°A','Ensino Médio'),(20,'3°B','Ensino Médio'),(21,'3°C','Ensino Médio');
/*!40000 ALTER TABLE `sala` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-12 10:04:01
