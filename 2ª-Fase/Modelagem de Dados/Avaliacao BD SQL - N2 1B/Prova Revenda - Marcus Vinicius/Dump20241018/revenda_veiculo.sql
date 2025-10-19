-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: revenda
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
-- Table structure for table `veiculo`
--

DROP TABLE IF EXISTS `veiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `veiculo` (
  `codigo` int(5) NOT NULL,
  `placa` varchar(8) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  `ano` int(4) NOT NULL,
  `cor` varchar(15) NOT NULL,
  `combustivel` varchar(20) NOT NULL,
  `acessorios` varchar(100) NOT NULL,
  `codmarca` int(5) NOT NULL,
  `codcategoria` int(5) NOT NULL,
  `cpfcliente` int(11) NOT NULL,
  `codmodelo` int(5) NOT NULL,
  `valor` float(10,2) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `codmarca` (`codmarca`),
  KEY `codcategoria` (`codcategoria`),
  KEY `cpfcliente` (`cpfcliente`),
  KEY `codmodelo` (`codmodelo`),
  CONSTRAINT `veiculo_ibfk_1` FOREIGN KEY (`codmarca`) REFERENCES `marca` (`codigo`),
  CONSTRAINT `veiculo_ibfk_2` FOREIGN KEY (`codcategoria`) REFERENCES `categoria` (`codigo`),
  CONSTRAINT `veiculo_ibfk_3` FOREIGN KEY (`cpfcliente`) REFERENCES `cliente` (`cpf`),
  CONSTRAINT `veiculo_ibfk_4` FOREIGN KEY (`codmodelo`) REFERENCES `modelo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `veiculo`
--

LOCK TABLES `veiculo` WRITE;
/*!40000 ALTER TABLE `veiculo` DISABLE KEYS */;
INSERT INTO `veiculo` VALUES (11,'BYE 0008','Velocidade maxima: 473km/h, Cavalaria: 1300HP',2023,'dourado','gasolina','Não possui',1,3,11,1,35000000.00),(22,'CSS 0300','Velocidade maxima: 490km/h, Cavalaria: 1450HP',2023,'Fibra de carbon','gasolina','Não possui',2,3,11,2,41000000.00),(33,'WWW 1100','Velocidade maxima: Indefinida, Cavalaria: 120',2024,'Laranja','gasolina','Não possui',3,3,11,3,21000000.00),(44,'FFF 5555','Velocidade maxima: 500km/h, Cavalaria: 1750HP',2020,'Amarelo','gasolina','Não possui',4,3,11,4,28000000.00),(55,'ZND 0350','Velocidade maxima: 350km/h, Cavalaria: 790HP',2020,'Fibra de carbon','gasolina','Não possui',5,3,11,5,100000000.00);
/*!40000 ALTER TABLE `veiculo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-18 15:21:26
