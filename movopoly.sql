CREATE DATABASE  IF NOT EXISTS `movopoly` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `movopoly`;
-- MySQL dump 10.13  Distrib 8.0.22, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: movopoly
-- ------------------------------------------------------
-- Server version	8.0.22-0ubuntu0.20.04.3

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
-- Table structure for table `chancecards`
--

DROP TABLE IF EXISTS `chancecards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chancecards` (
  `id` char(36) NOT NULL,
  `title` varchar(45) NOT NULL,
  `text` varchar(145) NOT NULL,
  `imgfilename` varchar(45) NOT NULL,
  `bank` int NOT NULL,
  `opponents` int NOT NULL,
  `loseturn` tinyint NOT NULL,
  `rollagain` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chancecards`
--

LOCK TABLES `chancecards` WRITE;
/*!40000 ALTER TABLE `chancecards` DISABLE KEYS */;
INSERT INTO `chancecards` VALUES ('18b19d1a-8921-40a6-98bb-d3b23bc3cf4b','Go shopping','Pay $15','shopping.png',-15,0,0,0),('4a00c64a-4352-4f34-9d65-faed0944e310','Go buy a Christmas tree','Pay $50','xmastree.png',-50,0,0,0),('5c53fa0f-ea70-417c-9f01-c931abe973a5','Lunch Hour','Skip 1 Turn','lunch.png',0,0,1,0),('98e4f350-5895-4a22-a0a4-87b7f4c40bd5','Win art contest','Earn $50','artcontest.png',50,0,0,0),('ca15757e-0f04-408e-898e-54aefe3561ad','Mow the lawn','Earn $5','mowlawn.png',5,0,0,0),('e03e9a0c-71ed-49aa-a20b-0a37a0dc741e','Good grades','Roll Again','goodgrades.png',0,0,0,1),('e64aed2f-5913-4e26-b5dc-0ab24086f543','Take a dog on a walk','Lose a Turn','dogwalk.png',0,0,1,0),('f4b72558-ef27-4821-97fa-8ec1f9672201','Happy Birthday!','Collect $10 from each player','birthday.png',0,10,0,0);
/*!40000 ALTER TABLE `chancecards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deeds`
--

DROP TABLE IF EXISTS `deeds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deeds` (
  `id` char(36) NOT NULL,
  `player` char(36) NOT NULL,
  `property` char(36) NOT NULL,
  `house` tinyint NOT NULL DEFAULT '0',
  `mortgage` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deeds`
--

LOCK TABLES `deeds` WRITE;
/*!40000 ALTER TABLE `deeds` DISABLE KEYS */;
/*!40000 ALTER TABLE `deeds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `games` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `started` datetime DEFAULT NULL,
  `name` varchar(25) NOT NULL,
  `current` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `players` (
  `id` char(36) NOT NULL,
  `name` varchar(12) NOT NULL,
  `game` char(36) NOT NULL,
  `money` int NOT NULL DEFAULT '250',
  `next` char(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `space` char(36) NOT NULL,
  `price` int NOT NULL,
  `rent` int NOT NULL,
  `houseprice` int NOT NULL,
  `houserent` int NOT NULL,
  `group` int NOT NULL,
  PRIMARY KEY (`space`),
  UNIQUE KEY `space_UNIQUE` (`space`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES ('32bba774-7ed3-4064-8e07-df53620dc0fb',350,35,150,110,3),('3a980054-ff63-4050-8567-7837bc5f52e7',200,20,100,70,2),('3c762da4-6f49-4a1e-94ec-2ebd1b042dfe',300,30,100,80,2),('5349321e-8fe6-4119-ad72-db7baeb4ba36',150,15,50,40,1),('90dbce43-8bfb-4fda-bbdb-66f1c9585ae3',400,40,150,115,3),('9b5267ff-3397-47ea-bdf5-568d6d6e3b76',100,10,50,35,1),('cf1eb92a-b9b6-413f-8f4c-2e7b89ea5b2c',250,25,100,75,2);
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spaces`
--

DROP TABLE IF EXISTS `spaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spaces` (
  `id` char(36) NOT NULL,
  `type` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `next` char(36) NOT NULL,
  `imgfilename` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spaces`
--

LOCK TABLES `spaces` WRITE;
/*!40000 ALTER TABLE `spaces` DISABLE KEYS */;
INSERT INTO `spaces` VALUES ('32bba774-7ed3-4064-8e07-df53620dc0fb',1,'Dining Room','90dbce43-8bfb-4fda-bbdb-66f1c9585ae3','dining.png'),('3a980054-ff63-4050-8567-7837bc5f52e7',1,'Office','cf1eb92a-b9b6-413f-8f4c-2e7b89ea5b2c','office.png'),('3c762da4-6f49-4a1e-94ec-2ebd1b042dfe',1,'Play Room','4e18970a-49a1-4dc7-a6e8-681d006ab7c2','playroom.png'),('4e18970a-49a1-4dc7-a6e8-681d006ab7c2',2,'Play Room Bathroom','32bba774-7ed3-4064-8e07-df53620dc0fb','chance.png'),('5349321e-8fe6-4119-ad72-db7baeb4ba36',1,'Dressing Room','78d1f3ac-e3fa-4df8-a435-e5c6ba53d7d5','dressing.png'),('78d1f3ac-e3fa-4df8-a435-e5c6ba53d7d5',2,'Middle Bathroom','3a980054-ff63-4050-8567-7837bc5f52e7','chance.png'),('90dbce43-8bfb-4fda-bbdb-66f1c9585ae3',1,'Kitchen','f66486eb-92b7-4da2-a294-b18c0bb8a6e2','kitchen.png'),('9b5267ff-3397-47ea-bdf5-568d6d6e3b76',1,'Mom and Dad\'s Bedroom','b87f91a5-eead-45cb-9ac2-0782086e6924','momdad.png'),('b87f91a5-eead-45cb-9ac2-0782086e6924',2,'Mom and Dad\'s Bathroom','5349321e-8fe6-4119-ad72-db7baeb4ba36','chance.png'),('cf1eb92a-b9b6-413f-8f4c-2e7b89ea5b2c',1,'Living Room','3c762da4-6f49-4a1e-94ec-2ebd1b042dfe','living.png'),('f66486eb-92b7-4da2-a294-b18c0bb8a6e2',0,'Kids\' Bedroom','9b5267ff-3397-47ea-bdf5-568d6d6e3b76','go.png');
/*!40000 ALTER TABLE `spaces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spacetypes`
--

DROP TABLE IF EXISTS `spacetypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spacetypes` (
  `name` varchar(45) NOT NULL,
  `id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spacetypes`
--

LOCK TABLES `spacetypes` WRITE;
/*!40000 ALTER TABLE `spacetypes` DISABLE KEYS */;
INSERT INTO `spacetypes` VALUES ('chance',2),('go',0),('property',1);
/*!40000 ALTER TABLE `spacetypes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-26 22:57:38
