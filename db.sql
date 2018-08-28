-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: agridata
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.16.04.1

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
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_520_ci NOT NULL,
  `sent_invite` datetime NOT NULL,
  `accepted_invite` datetime NOT NULL,
  `signed_up` datetime NOT NULL,
  `responses` int(100) NOT NULL,
  `project_name` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_id` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_owner` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `market_prices`
--

DROP TABLE IF EXISTS `market_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market_prices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `market_name` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `market_location_lga` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `market_days` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `produce_price` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `user` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_key` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_name` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_id` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `no_of_responses` int(100) NOT NULL,
  `status` text COLLATE utf8_unicode_520_ci NOT NULL,
  `collaborators` varchar(500) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_owner` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `present_user` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_key` (`project_key`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `register_farmers`
--

DROP TABLE IF EXISTS `register_farmers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `register_farmers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `farmer_pic` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `phone_primary` varchar(14) COLLATE utf8_unicode_520_ci NOT NULL,
  `phone_secondary` varchar(14) COLLATE utf8_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `date_of_registration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gender` text COLLATE utf8_unicode_520_ci NOT NULL,
  `education` text COLLATE utf8_unicode_520_ci NOT NULL,
  `family_size` int(10) NOT NULL,
  `income` int(100) NOT NULL,
  `state` text COLLATE utf8_unicode_520_ci NOT NULL,
  `lga` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `town` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `latitude` int(10) NOT NULL,
  `longitude` int(10) NOT NULL,
  `land_area` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `farm_pic` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `crops` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `produce_volume` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `farm_labour` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `user` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `profile_pic` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `phone` varchar(14) COLLATE utf8_unicode_520_ci NOT NULL,
  `gender` text COLLATE utf8_unicode_520_ci NOT NULL,
  `user_type` text COLLATE utf8_unicode_520_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `password_reset_code` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_name` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `project_id` varchar(500) COLLATE utf8_unicode_520_ci NOT NULL,
  `verify_code` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `verified` int(10) NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `bio` varchar(500) COLLATE utf8_unicode_520_ci NOT NULL,
  `education` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `degree` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `state` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `lga` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `town` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `signed_up` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-27 15:57:43
