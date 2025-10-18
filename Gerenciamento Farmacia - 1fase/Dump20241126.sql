CREATE DATABASE  IF NOT EXISTS `farmacia` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `farmacia`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: farmacia
-- ------------------------------------------------------
-- Server version	5.5.20-log

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
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estoque` (
  `ID_produto` int(11) NOT NULL,
  `Nome_produto` varchar(45) NOT NULL,
  `Categoria` varchar(45) NOT NULL,
  `Tarja_remedio` varchar(45) DEFAULT NULL,
  `Fabricante_Marca` varchar(45) NOT NULL,
  `Valor_produto` float NOT NULL,
  `Quantidade_estoque` int(11) NOT NULL,
  PRIMARY KEY (`ID_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estoque`
--

LOCK TABLES `estoque` WRITE;
/*!40000 ALTER TABLE `estoque` DISABLE KEYS */;
INSERT INTO `estoque` VALUES (1,'dipirona','remedio','','generico',7,16),(2,'Fralda','Higiene','','Pampers',50,200),(10,'Baton','Cosmetico','N/A','Avon',10,21);
/*!40000 ALTER TABLE `estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funcionários`
--

DROP TABLE IF EXISTS `funcionários`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funcionários` (
  `ID_funcionário` int(11) NOT NULL,
  `Nome_funcionário` varchar(45) NOT NULL,
  `Cargo_funcionário` varchar(45) NOT NULL,
  `Vendas_totais_funcionários` int(11) NOT NULL,
  `Salário_funcionário` float NOT NULL,
  PRIMARY KEY (`ID_funcionário`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcionários`
--

LOCK TABLES `funcionários` WRITE;
/*!40000 ALTER TABLE `funcionários` DISABLE KEYS */;
INSERT INTO `funcionários` VALUES (2,'Joao Vitor Benedet Machado','Atendente',22,4000),(3,'Matheus Donadel Marques','Farmaceutico',6,3000);
/*!40000 ALTER TABLE `funcionários` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendas`
--

DROP TABLE IF EXISTS `vendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendas` (
  `ID_vendas` int(11) NOT NULL,
  `codproduto` int(11) NOT NULL,
  `codfuncionario` int(11) NOT NULL,
  `Quantidade_produto` varchar(45) NOT NULL,
  `Valor_unitário` float NOT NULL,
  `Valor_total` float NOT NULL,
  `Data_da_venda` date NOT NULL,
  PRIMARY KEY (`ID_vendas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendas`
--

LOCK TABLES `vendas` WRITE;
/*!40000 ALTER TABLE `vendas` DISABLE KEYS */;
INSERT INTO `vendas` VALUES (1,1234,8,'8.0',19,152,'2024-11-12'),(5,421,3,'5',5,25,'2024-11-12'),(7,244,2,'5.0',10,50,'2024-11-12'),(22,1,3,'3',5,15,'2024-10-11'),(45,2,3,'4',5,20,'2024-11-26'),(66,1,2,'1',8,8,'2024-11-26'),(76,1,2,'1',7,7,'2024-11-26'),(78,1,2,'5',8,40,'2024-11-26'),(123,1,3,'4',8,32,'2024-11-26'),(9237237,3,3,'2',5,10,'2025-11-19');
/*!40000 ALTER TABLE `vendas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'farmacia'
--

--
-- Dumping routines for database 'farmacia'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-26 16:58:16
