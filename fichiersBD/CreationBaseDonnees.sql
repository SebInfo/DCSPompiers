-- MySQL dump 10.13  Distrib 8.0.19, for macos10.15 (x86_64)
--
-- Host: localhost    Database: DSC
-- ------------------------------------------------------
-- Server version	5.7.25

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
-- Table structure for table `Affectation`
--

DROP TABLE IF EXISTS `Affectation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Affectation` (
  `Matricule` int(6) NOT NULL,
  `Date` date NOT NULL,
  `IdCaserne` int(11) NOT NULL,
  PRIMARY KEY (`Matricule`,`Date`,`IdCaserne`),
  KEY `Caserne_idx` (`IdCaserne`),
  CONSTRAINT `CaserneToto` FOREIGN KEY (`IdCaserne`) REFERENCES `Caserne` (`idCaserne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Pompiers` FOREIGN KEY (`Matricule`) REFERENCES `Pompier` (`Matricule`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Affectation`
--

LOCK TABLES `Affectation` WRITE;
/*!40000 ALTER TABLE `Affectation` DISABLE KEYS */;
INSERT INTO `Affectation` VALUES (786572,'1997-02-12',1),(986995,'2005-05-14',1),(654352,'2010-06-12',2),(782312,'2015-05-10',2),(782312,'2017-08-17',2),(786572,'1987-10-12',2),(982726,'2012-08-10',2),(986995,'1984-03-10',2),(986995,'1980-02-12',3),(986995,'2001-07-12',3);
/*!40000 ALTER TABLE `Affectation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Caserne`
--

DROP TABLE IF EXISTS `Caserne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Caserne` (
  `idCaserne` int(11) NOT NULL AUTO_INCREMENT,
  `NomCaserne` varchar(45) NOT NULL,
  PRIMARY KEY (`idCaserne`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Caserne`
--

LOCK TABLES `Caserne` WRITE;
/*!40000 ALTER TABLE `Caserne` DISABLE KEYS */;
INSERT INTO `Caserne` VALUES (1,'ouessant'),(2,'Carcassonne'),(3,'lille'),(4,'Narbonne');
/*!40000 ALTER TABLE `Caserne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Employeur`
--

DROP TABLE IF EXISTS `Employeur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Employeur` (
  `idEmployeur` int(11) NOT NULL AUTO_INCREMENT,
  `NomEmployeur` varchar(40) NOT NULL,
  `PrenomEmployeur` varchar(40) NOT NULL,
  `TelEmployeur` varchar(15) NOT NULL,
  PRIMARY KEY (`idEmployeur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employeur`
--

LOCK TABLES `Employeur` WRITE;
/*!40000 ALTER TABLE `Employeur` DISABLE KEYS */;
INSERT INTO `Employeur` VALUES (3,'VERSE','Alain','0676542431'),(4,'NALINE','André','0454245142'),(5,'ZOLE','Camille','0676524156');
/*!40000 ALTER TABLE `Employeur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Engin`
--

DROP TABLE IF EXISTS `Engin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Engin` (
  `Numéro` tinyint(2) NOT NULL,
  `IdCaserne` int(11) NOT NULL,
  `IdTypeEngin` varchar(4) NOT NULL,
  PRIMARY KEY (`Numéro`,`IdCaserne`,`IdTypeEngin`),
  KEY `Caserne_idx` (`IdCaserne`),
  KEY `CaserneEngin_idx` (`IdCaserne`),
  KEY `LeType_idx` (`IdTypeEngin`),
  CONSTRAINT `CaserneEngin` FOREIGN KEY (`IdCaserne`) REFERENCES `Caserne` (`idCaserne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `LeType` FOREIGN KEY (`IdTypeEngin`) REFERENCES `TypeEngin` (`idTypeEngin`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Engin`
--

LOCK TABLES `Engin` WRITE;
/*!40000 ALTER TABLE `Engin` DISABLE KEYS */;
INSERT INTO `Engin` VALUES (1,1,'EPA'),(2,1,'EPA');
/*!40000 ALTER TABLE `Engin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Exercer`
--

DROP TABLE IF EXISTS `Exercer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Exercer` (
  `Matricule` int(6) NOT NULL,
  `IdHabilitation` int(11) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`Matricule`,`IdHabilitation`),
  KEY `IdHabilation_idx` (`IdHabilitation`),
  CONSTRAINT `IdHabilation` FOREIGN KEY (`IdHabilitation`) REFERENCES `Habilitation` (`idHabilitation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Exercer`
--

LOCK TABLES `Exercer` WRITE;
/*!40000 ALTER TABLE `Exercer` DISABLE KEYS */;
INSERT INTO `Exercer` VALUES (654352,3,'2001-08-10'),(654353,1,'2002-10-07'),(782312,3,'2019-10-12'),(986995,1,'1980-12-03'),(986995,2,'1982-08-12'),(992312,1,'2012-12-12'),(992312,2,'2020-12-12'),(992312,3,'2020-12-12'),(992312,4,'2020-12-12');
/*!40000 ALTER TABLE `Exercer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Grade`
--

DROP TABLE IF EXISTS `Grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Grade` (
  `idGrade` int(11) NOT NULL AUTO_INCREMENT,
  `LblGrade` varchar(45) NOT NULL,
  PRIMARY KEY (`idGrade`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Grade`
--

LOCK TABLES `Grade` WRITE;
/*!40000 ALTER TABLE `Grade` DISABLE KEYS */;
INSERT INTO `Grade` VALUES (1,'auxiliaire'),(2,'sapeur 2ème classe'),(3,'sapeur 1ère classe'),(4,'caporal'),(5,'caporal-chef'),(6,'sergent'),(7,'sergent-chef'),(8,'adjudant'),(9,'adjudant-chef'),(10,'lieutenant'),(11,'capitaine'),(12,'commandant'),(13,'lieutenant-colonel');
/*!40000 ALTER TABLE `Grade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Habilitation`
--

DROP TABLE IF EXISTS `Habilitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Habilitation` (
  `idHabilitation` int(11) NOT NULL AUTO_INCREMENT,
  `LblHabilitation` varchar(60) NOT NULL,
  PRIMARY KEY (`idHabilitation`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Habilitation`
--

LOCK TABLES `Habilitation` WRITE;
/*!40000 ALTER TABLE `Habilitation` DISABLE KEYS */;
INSERT INTO `Habilitation` VALUES (1,'conducteur de véhicule de secours routier (VSR)'),(2,'chef d\'agrès fourgon pompe-tonne (FPT)'),(3,'équipier incendie'),(4,'équipier échelle pivotante automatique'),(5,'conducteur de véhicule fourgon pompe-tonne (FPT)'),(6,'conducteur échelle pivotante automatique');
/*!40000 ALTER TABLE `Habilitation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NatureSInistre`
--

DROP TABLE IF EXISTS `NatureSInistre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `NatureSInistre` (
  `idNatureSInistre` int(11) NOT NULL AUTO_INCREMENT,
  `LblNatureSinistre` mediumtext NOT NULL,
  PRIMARY KEY (`idNatureSInistre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Table des sinistres';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NatureSInistre`
--

LOCK TABLES `NatureSInistre` WRITE;
/*!40000 ALTER TABLE `NatureSInistre` DISABLE KEYS */;
INSERT INTO `NatureSInistre` VALUES (1,'feu dans un appartement'),(2,'feu de brousailles'),(3,'ascenseur bloqué');
/*!40000 ALTER TABLE `NatureSInistre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pompier`
--

DROP TABLE IF EXISTS `Pompier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Pompier` (
  `Matricule` int(6) NOT NULL,
  `NomPompier` varchar(45) NOT NULL,
  `PrenomPompier` varchar(45) NOT NULL,
  `DateNaissPompier` date NOT NULL,
  `TelPompier` varchar(15) DEFAULT NULL,
  `SexePompier` enum('féminin','masculin') NOT NULL,
  `IdGrade` int(11) NOT NULL,
  PRIMARY KEY (`Matricule`),
  KEY `Grade_idx` (`IdGrade`),
  CONSTRAINT `Grade` FOREIGN KEY (`IdGrade`) REFERENCES `Grade` (`idGrade`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pompier`
--

LOCK TABLES `Pompier` WRITE;
/*!40000 ALTER TABLE `Pompier` DISABLE KEYS */;
INSERT INTO `Pompier` VALUES (555555,'DURAND','Lucien','2022-09-11','9790908808','masculin',4),(622897,'cs','c','2022-09-07','','masculin',1),(654352,'Clette','Lara','1987-03-11','0642786352','féminin',3),(666666,'TRYC','MUCHE','2022-09-03','0685101893','masculin',1),(666667,'TOTO','TATA','2022-09-07','0676547872','masculin',4),(696969,'Durand','Dupont','2020-09-30','992923839','masculin',1),(782312,'Esur','Janette','1982-02-11','0627371273','féminin',3),(786572,'Abri','Gauthier','1972-05-12','0676542532','masculin',10),(982726,'Inion','Seb','1970-10-10','99878998','masculin',10),(986995,'Dumontel','Robert','1969-10-10','0298568542','masculin',11),(992312,'Balle','Jean','1982-07-12','0678652354','masculin',2);
/*!40000 ALTER TABLE `Pompier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Prevoir`
--

DROP TABLE IF EXISTS `Prevoir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Prevoir` (
  `idTypeEngin` varchar(4) NOT NULL,
  `IdNatSinistre` int(11) NOT NULL,
  PRIMARY KEY (`idTypeEngin`,`IdNatSinistre`),
  KEY `Sinistre_idx` (`IdNatSinistre`),
  CONSTRAINT `Engins` FOREIGN KEY (`idTypeEngin`) REFERENCES `TypeEngin` (`idTypeEngin`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Sinistre` FOREIGN KEY (`IdNatSinistre`) REFERENCES `NatureSInistre` (`idNatureSInistre`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Prevoir`
--

LOCK TABLES `Prevoir` WRITE;
/*!40000 ALTER TABLE `Prevoir` DISABLE KEYS */;
INSERT INTO `Prevoir` VALUES ('EPA',1),('FPT',1),('VSAV',1),('VSAV',2),('VSAV',3);
/*!40000 ALTER TABLE `Prevoir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Professionnel`
--

DROP TABLE IF EXISTS `Professionnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Professionnel` (
  `MatPro` int(6) NOT NULL,
  `DateEmbauche` date NOT NULL,
  `Indice` int(3) NOT NULL,
  PRIMARY KEY (`MatPro`),
  CONSTRAINT `Pompier` FOREIGN KEY (`MatPro`) REFERENCES `Pompier` (`Matricule`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Professionnel`
--

LOCK TABLES `Professionnel` WRITE;
/*!40000 ALTER TABLE `Professionnel` DISABLE KEYS */;
INSERT INTO `Professionnel` VALUES (786572,'1997-06-05',300),(986995,'2000-05-04',200);
/*!40000 ALTER TABLE `Professionnel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Reclamer`
--

DROP TABLE IF EXISTS `Reclamer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Reclamer` (
  `idTypeEngin` varchar(4) NOT NULL,
  `IdHabilitation` int(11) NOT NULL,
  `Nbr` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`idTypeEngin`,`IdHabilitation`),
  KEY `Habili_idx` (`IdHabilitation`),
  CONSTRAINT `Engin` FOREIGN KEY (`idTypeEngin`) REFERENCES `TypeEngin` (`idTypeEngin`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Habili` FOREIGN KEY (`IdHabilitation`) REFERENCES `Habilitation` (`idHabilitation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Reclamer`
--

LOCK TABLES `Reclamer` WRITE;
/*!40000 ALTER TABLE `Reclamer` DISABLE KEYS */;
INSERT INTO `Reclamer` VALUES ('FPT',2,1),('FPT',3,2),('FPT',5,1);
/*!40000 ALTER TABLE `Reclamer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TypeEngin`
--

DROP TABLE IF EXISTS `TypeEngin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TypeEngin` (
  `idTypeEngin` varchar(4) NOT NULL,
  `LblEngincol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idTypeEngin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TypeEngin`
--

LOCK TABLES `TypeEngin` WRITE;
/*!40000 ALTER TABLE `TypeEngin` DISABLE KEYS */;
INSERT INTO `TypeEngin` VALUES ('EPA','échelle pivotante automatique'),('FPT','fourgon pompe-tonne'),('VSAV','véhicule de secours aux victimes');
/*!40000 ALTER TABLE `TypeEngin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Volontaire`
--

DROP TABLE IF EXISTS `Volontaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Volontaire` (
  `MatVolontaire` int(6) NOT NULL,
  `Bip` varchar(3) DEFAULT NULL,
  `IdEmployeur` int(11) DEFAULT NULL,
  PRIMARY KEY (`MatVolontaire`),
  KEY `Employeur_idx` (`IdEmployeur`),
  CONSTRAINT `Employeur` FOREIGN KEY (`IdEmployeur`) REFERENCES `Employeur` (`idEmployeur`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Volontaire`
--

LOCK TABLES `Volontaire` WRITE;
/*!40000 ALTER TABLE `Volontaire` DISABLE KEYS */;
INSERT INTO `Volontaire` VALUES (899789,'17',4),(986995,'15',3),(992312,'12',3);
/*!40000 ALTER TABLE `Volontaire` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-27 16:45:43
