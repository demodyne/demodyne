-- MySQL dump 10.13  Distrib 5.6.30, for debian-linux-gnu (x86_64)
--
-- Host: demodyne.org    Database: demodyne_prod
-- ------------------------------------------------------
-- Server version	5.6.30

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
-- Table structure for table `dgi_administration`
--

DROP TABLE IF EXISTS `dgi_administration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_administration` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) NOT NULL,
  `admin_presentation` varchar(1000) NOT NULL,
  `admin_level` int(11) NOT NULL,
  `admin_city` int(11) DEFAULT NULL,
  `admin_region` int(11) DEFAULT NULL,
  `admin_address` varchar(200) NOT NULL,
  `admin_fax` varchar(12) DEFAULT '',
  `admin_website` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`admin_id`),
  KEY `fk_admin_city_idx` (`admin_city`),
  KEY `fk_admin_region_idx` (`admin_region`),
  CONSTRAINT `fk_admin_city` FOREIGN KEY (`admin_city`) REFERENCES `dgi_cities` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_admin_region` FOREIGN KEY (`admin_region`) REFERENCES `dgi_regions` (`region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Table for administration account ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_banners`
--

DROP TABLE IF EXISTS `dgi_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_banners` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `banner_name` varchar(100) NOT NULL,
  `banner_description` varchar(1000) DEFAULT '',
  `banner_image` varchar(200) DEFAULT '',
  `banner_created_date` datetime DEFAULT NULL,
  `banner_published` int(11) DEFAULT '0',
  `banner_url` varchar(2000) DEFAULT '',
  `banner_position` int(11) DEFAULT '-1',
  `banner_uuid` varchar(36) DEFAULT NULL,
  `banner_level` int(11) DEFAULT '1',
  `banner_full_city` int(11) DEFAULT '0',
  PRIMARY KEY (`banner_id`),
  KEY `fk_admin_banner_idx` (`admin_id`),
  KEY `banner_city_id_fk_idx` (`city_id`),
  KEY `fk_dgi_banners_1_idx` (`country_id`),
  CONSTRAINT `banner_city_id_fk` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_admin_banner` FOREIGN KEY (`admin_id`) REFERENCES `dgi_administration` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_banners_1` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_banners_BEFORE_INSERT` BEFORE INSERT ON `dgi_banners` FOR EACH ROW
    SET new.banner_created_date = now(), new.banner_uuid = uuid() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_bugs`
--

DROP TABLE IF EXISTS `dgi_bugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_bugs` (
  `bug_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `bug_title` varchar(100) NOT NULL,
  `bug_description` varchar(5000) NOT NULL,
  `bug_image` varchar(200) DEFAULT NULL,
  `bug_created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`bug_id`),
  KEY `bug_usr_fk_idx` (`usr_id`),
  CONSTRAINT `bug_usr_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_bugs_BEFORE_INSERT` BEFORE INSERT ON `dgi_bugs` FOR EACH ROW
        SET new.bug_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_categories`
--

DROP TABLE IF EXISTS `dgi_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id_cat` int(11) DEFAULT NULL,
  `cat_name` varchar(50) NOT NULL,
  `cat_description` varchar(2000) NOT NULL,
  `cat_image` varchar(256) NOT NULL,
  `country_id` int(11) NOT NULL,
  `cat_city` int(11) DEFAULT '1',
  `cat_region` int(11) DEFAULT '1',
  `cat_country` int(11) DEFAULT '1',
  PRIMARY KEY (`cat_id`),
  KEY `id_category_fk_cat_idx` (`cat_id_cat`),
  KEY `country_category_fk_idx` (`country_id`),
  CONSTRAINT `country_category_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `id_category_fk_cat` FOREIGN KEY (`cat_id_cat`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_categories_BEFORE_INSERT` BEFORE INSERT ON `dgi_categories` FOR EACH ROW
    BEGIN
        IF NEW.cat_id_cat = '0' THEN
            SET NEW.cat_id_cat = NULL;
        END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_cities`
--

DROP TABLE IF EXISTS `dgi_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_cities` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL COMMENT 'iso country code, 2 characters\nFor Saint-Louis: FR',
  `region_id` int(11) DEFAULT NULL,
  `city_postalcode` varchar(20) NOT NULL COMMENT 'varchar(20)\nFor Saint-Louis: 68300',
  `city_name` varchar(180) NOT NULL COMMENT 'varchar(180)\nFor Saint-Louis: Saint-Louis',
  `state_name` varchar(100) DEFAULT NULL COMMENT '1. order subdivision (state) varchar(100)\nFor Saint-Louis: Alsace',
  `state_code` varchar(20) DEFAULT '0' COMMENT '1. order subdivision (state) varchar(20)\nFor Saint-Louis: C1',
  `county_name` varchar(100) DEFAULT NULL COMMENT '2. order subdivision (county/province) varchar(100)\nFor Saint-Louis: Haut-Rhin',
  `county_code` varchar(20) DEFAULT '0' COMMENT '2. order subdivision (county/province) varchar(20)\nFor Saint-Louis: 68',
  `community_name` varchar(100) DEFAULT NULL COMMENT '3. order subdivision (community) varchar(100)\nFor Saint-Louis: Arrondissement de Mulhouse',
  `community_code` varchar(20) DEFAULT '0' COMMENT '3. order subdivision (community) varchar(20)\nFor Saint-Louis: 684',
  `city_latitude` decimal(10,7) DEFAULT '0.0000000' COMMENT 'estimated latitude (wgs84)\nFor Saint-Louis: 47.5884',
  `city_longitude` decimal(10,7) DEFAULT '0.0000000' COMMENT 'estimated longitude (wgs84)\nFor Saint-Louis: 7.5625',
  `accuracy` int(11) DEFAULT '1' COMMENT 'accuracy of lat/lng from 1=estimated to 6=centroid\nFor Saint-Louis: 5',
  `city_population` int(11) DEFAULT '0',
  `district_name` varchar(100) DEFAULT ' ',
  `district_code` int(11) DEFAULT '0',
  `full_city_id` int(11) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`city_id`),
  KEY `city_country_fk_idx` (`country_id`),
  KEY `city_full_city_idx` (`full_city_id`),
  KEY `city_region_id_idx` (`region_id`),
  CONSTRAINT `city_country_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `city_full_city` FOREIGN KEY (`full_city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `city_region_id` FOREIGN KEY (`region_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=65648 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_comment_thumb`
--

DROP TABLE IF EXISTS `dgi_comment_thumb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_comment_thumb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `com_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `up` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `thumb_usr_id_idx` (`usr_id`),
  KEY `thumb_com_id_idx` (`com_id`),
  CONSTRAINT `thumb_com_id` FOREIGN KEY (`com_id`) REFERENCES `dgi_comments` (`com_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `thumb_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_comments`
--

DROP TABLE IF EXISTS `dgi_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_comments` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_usr` int(11) NOT NULL,
  `com_text` text NOT NULL,
  `com_created_date` datetime DEFAULT NULL,
  `com_com` int(11) DEFAULT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `prog_id` int(11) DEFAULT NULL,
  `com_uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`com_id`),
  KEY `com_com_fk_idx` (`com_com`),
  KEY `com_usr_fk_idx` (`com_usr`),
  KEY `com_prop_fk_idx` (`prop_id`),
  KEY `com_scn_fk_idx` (`prog_id`),
  CONSTRAINT `com_com_fk` FOREIGN KEY (`com_com`) REFERENCES `dgi_comments` (`com_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_prop_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_scn_fk` FOREIGN KEY (`prog_id`) REFERENCES `dgi_programs` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_usr_fk` FOREIGN KEY (`com_usr`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_comments_BEFORE_INSERT` BEFORE INSERT ON `dgi_comments` FOR EACH ROW
    SET new.com_uuid=uuid(), new.com_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_counters`
--

DROP TABLE IF EXISTS `dgi_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_counters` (
  `cnt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cnt_updated_date` datetime DEFAULT NULL,
  `cnt_total` int(11) DEFAULT '100',
  `cnt_prop` int(11) DEFAULT '0',
  `cnt_vote` int(11) DEFAULT '0',
  `cnt_com` int(11) DEFAULT '0',
  PRIMARY KEY (`cnt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8 COMMENT='Used to manage user counters';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_counters_BEFORE_INSERT` BEFORE INSERT ON `dgi_counters` FOR EACH ROW
    SET new.cnt_updated_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_countries`
--

DROP TABLE IF EXISTS `dgi_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  `country_currency` varchar(4) DEFAULT '',
  `country_other_category` varchar(100) DEFAULT '',
  `country_activated` int(11) DEFAULT '0',
  `country_population` int(11) DEFAULT '0',
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_departments`
--

DROP TABLE IF EXISTS `dgi_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_departments` (
  `dep_id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_id` int(11) NOT NULL,
  `dep_code` varchar(3) NOT NULL,
  `dep_name` varchar(250) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`dep_id`),
  KEY `code` (`dep_code`),
  KEY `region_fk_idx` (`reg_id`),
  KEY `country_fk_idx` (`country_id`),
  CONSTRAINT `country_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `region_fk` FOREIGN KEY (`reg_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=978 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_error_log`
--

DROP TABLE IF EXISTS `dgi_error_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_error_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `priority` varchar(12) COLLATE utf8_unicode_ci DEFAULT 'DEBUG',
  `message` text COLLATE utf8_unicode_ci,
  `file` text COLLATE utf8_unicode_ci,
  `line` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trace` text COLLATE utf8_unicode_ci,
  `xdebug` text COLLATE utf8_unicode_ci,
  `uri` text COLLATE utf8_unicode_ci,
  `ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2982 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_events`
--

DROP TABLE IF EXISTS `dgi_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `event_name` varchar(200) NOT NULL,
  `event_description` text NOT NULL,
  `event_link` varchar(2000) DEFAULT NULL,
  `event_image` varchar(100) DEFAULT NULL,
  `event_start_date` datetime NOT NULL,
  `event_end_date` datetime NOT NULL,
  `event_created_date` datetime DEFAULT NULL,
  `event_published_date` datetime DEFAULT NULL,
  `event_canceled_date` datetime DEFAULT NULL,
  `event_uuid` varchar(36) DEFAULT NULL,
  `event_location` varchar(500) DEFAULT NULL,
  `event_relevant_region` int(11) DEFAULT '0',
  `event_relevant_country` int(11) DEFAULT '0',
  `event_level` int(11) DEFAULT '1',
  `event_full_city` int(11) DEFAULT '0',
  PRIMARY KEY (`event_id`),
  KEY `ev_usr_id_idx` (`usr_id`),
  KEY `ev_city_id_idx` (`city_id`),
  KEY `ev_region_id_idx` (`region_id`),
  KEY `ev_country_id_idx` (`country_id`),
  CONSTRAINT `ev_city_id` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_country_id` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_region_id` FOREIGN KEY (`region_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_events_BEFORE_INSERT` BEFORE INSERT ON `dgi_events` FOR EACH ROW
    SET new.event_uuid=uuid(), new.event_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_events_users`
--

DROP TABLE IF EXISTS `dgi_events_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_events_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_usr_fk_idx` (`event_id`),
  KEY `usr_event_fk_idx` (`usr_id`),
  CONSTRAINT `event_usr_fk` FOREIGN KEY (`event_id`) REFERENCES `dgi_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usr_event_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_favorites`
--

DROP TABLE IF EXISTS `dgi_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_favorites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `last_check_date` datetime DEFAULT NULL,
  PRIMARY KEY (`fav_id`),
  KEY `prop_id_idx` (`prop_id`),
  KEY `usr_id_idx` (`usr_id`),
  CONSTRAINT `prop_id` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usr_id` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_favorites_BEFORE_INSERT` BEFORE INSERT ON `dgi_favorites` FOR EACH ROW
    SET new.last_check_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_history`
--

DROP TABLE IF EXISTS `dgi_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_history` (
  `his_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `his_created_date` datetime DEFAULT NULL,
  `his_name` varchar(100) DEFAULT NULL,
  `his_description` varchar(2000) DEFAULT NULL,
  `his_start_date` datetime DEFAULT NULL,
  `his_end_date` datetime DEFAULT NULL,
  `his_cost` int(11) DEFAULT NULL,
  `his_category` int(11) DEFAULT NULL,
  `his_type` int(11) DEFAULT '1' COMMENT '0 - measure added\n1 - measure edited\n2 - measure claimed by administration',
  PRIMARY KEY (`his_id`),
  KEY `his_usr_fk_idx` (`usr_id`),
  KEY `his_cat_fk_idx` (`his_category`),
  CONSTRAINT `his_cat_fk` FOREIGN KEY (`his_category`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `his_usr_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_history_BEFORE_INSERT` BEFORE INSERT ON `dgi_history` FOR EACH ROW
    SET new.his_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_history_links`
--

DROP TABLE IF EXISTS `dgi_history_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_history_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `his_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `his_link_fk_idx` (`link_id`),
  KEY `link_his_fk_idx` (`his_id`),
  CONSTRAINT `his_link_fk` FOREIGN KEY (`link_id`) REFERENCES `dgi_links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `link_his_fk` FOREIGN KEY (`his_id`) REFERENCES `dgi_history` (`his_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_inbox`
--

DROP TABLE IF EXISTS `dgi_inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_inbox` (
  `ibx_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_usr_id` int(11) DEFAULT NULL,
  `to_usr_id` int(11) NOT NULL,
  `ibx_type` int(11) NOT NULL,
  `prog_id` int(11) DEFAULT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `com_id` int(11) DEFAULT NULL,
  `nl_id` int(11) DEFAULT NULL,
  `ibx_title` varchar(50) DEFAULT '',
  `ibx_text` mediumtext,
  `ibx_created_date` datetime DEFAULT NULL,
  `ibx_viewed` int(11) DEFAULT '0',
  `ibx_uuid` varchar(36) DEFAULT NULL,
  `ibx_group` varchar(23) DEFAULT NULL,
  `ibx_from_trash_date` datetime DEFAULT NULL,
  `ibx_from_deleted_date` datetime DEFAULT NULL,
  `ibx_to_trash_date` datetime DEFAULT NULL,
  `ibx_to_deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`ibx_id`),
  KEY `from_usr_id_idx` (`from_usr_id`),
  KEY `to_usr_id_idx` (`to_usr_id`),
  KEY `scn_jd_idx` (`prog_id`),
  KEY `com_id_idx` (`com_id`),
  KEY `ibx_prop_id_idx` (`prop_id`),
  KEY `inbox_nl_fk_idx` (`nl_id`),
  CONSTRAINT `com_id` FOREIGN KEY (`com_id`) REFERENCES `dgi_comments` (`com_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `from_usr_id` FOREIGN KEY (`from_usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ibx_prop_id` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inbox_nl_fk` FOREIGN KEY (`nl_id`) REFERENCES `dgi_newsletters` (`nl_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scn_jd` FOREIGN KEY (`prog_id`) REFERENCES `dgi_programs` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `to_usr_id` FOREIGN KEY (`to_usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=utf8 COMMENT='Table used for emailing in Demodyne application';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_inbox_BEFORE_INSERT` BEFORE INSERT ON `dgi_inbox` FOR EACH ROW
    SET new.ibx_uuid=uuid(), new.ibx_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_languages`
--

DROP TABLE IF EXISTS `dgi_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_languages` (
  `lang_id` varchar(2) NOT NULL,
  `lang_name` varchar(45) NOT NULL,
  `lang_flag` varchar(45) NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_links`
--

DROP TABLE IF EXISTS `dgi_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(200) NOT NULL DEFAULT '',
  `link_added` int(11) DEFAULT '1',
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_mesures`
--

DROP TABLE IF EXISTS `dgi_mesures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_mesures` (
  `mes_id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_start_date` datetime DEFAULT NULL,
  `mes_end_date` datetime DEFAULT NULL,
  `mes_cost` int(11) DEFAULT '0',
  PRIMARY KEY (`mes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_mesures_history`
--

DROP TABLE IF EXISTS `dgi_mesures_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_mesures_history` (
  `in` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id` int(11) NOT NULL,
  `his_id` int(11) NOT NULL,
  PRIMARY KEY (`in`),
  KEY `his_mes_fk_idx` (`mes_id`),
  KEY `mes_his_fk_idx` (`his_id`),
  CONSTRAINT `his_mes_fk` FOREIGN KEY (`mes_id`) REFERENCES `dgi_mesures` (`mes_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mes_his_fk` FOREIGN KEY (`his_id`) REFERENCES `dgi_history` (`his_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_mesures_links`
--

DROP TABLE IF EXISTS `dgi_mesures_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_mesures_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `link_mes_fk_idx` (`mes_id`),
  KEY `mes_link_fk_idx` (`link_id`),
  CONSTRAINT `link_mes_fk` FOREIGN KEY (`mes_id`) REFERENCES `dgi_mesures` (`mes_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mes_link_fk` FOREIGN KEY (`link_id`) REFERENCES `dgi_links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_news`
--

DROP TABLE IF EXISTS `dgi_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `news_type` int(11) NOT NULL,
  `news_created_date` datetime DEFAULT NULL,
  `news_uuid` varchar(36) DEFAULT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `usr_id` int(11) DEFAULT NULL,
  `scn_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`news_id`),
  KEY `city_id_idx` (`city_id`),
  KEY `prop_id_idx` (`prop_id`),
  KEY `news_usr_fk_idx` (`usr_id`),
  CONSTRAINT `city_id` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_usr_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prop_id_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1 COMMENT='Table used for recording all new concerning a city';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_news_BEFORE_INSERT` BEFORE INSERT ON `dgi_news` FOR EACH ROW
    SET new.news_created_date=now(), new.news_uuid=uuid() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_newsletters`
--

DROP TABLE IF EXISTS `dgi_newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_newsletters` (
  `nl_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `nl_name` varchar(100) NOT NULL,
  `nl_description` varchar(1000) DEFAULT '',
  `nl_header_image` varchar(200) DEFAULT '',
  `nl_send_to` int(11) DEFAULT '0',
  `nl_subject` varchar(200) DEFAULT '',
  `nl_contact` varchar(200) DEFAULT '',
  `nl_message` text,
  `nl_created_date` datetime DEFAULT NULL,
  `nl_sent_date` datetime DEFAULT NULL,
  `nl_uuid` varchar(36) DEFAULT NULL,
  `nl_reply` int(11) DEFAULT '0',
  `nl_url` varchar(200) DEFAULT '',
  `nl_is_sent` int(11) DEFAULT '0',
  PRIMARY KEY (`nl_id`),
  KEY `fk_admin_nl_idx` (`admin_id`),
  KEY `nl_city_fk_idx` (`city_id`),
  KEY `nl_region_fk_idx` (`region_id`),
  KEY `nl_country_fk_idx` (`country_id`),
  CONSTRAINT `fk_admin_nl` FOREIGN KEY (`admin_id`) REFERENCES `dgi_administration` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nl_city_fk` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nl_country_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nl_region_fk` FOREIGN KEY (`region_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_newsletters_BEFORE_INSERT` BEFORE INSERT ON `dgi_newsletters` FOR EACH ROW
    SET new.nl_created_date=now(), new.nl_uuid=uuid() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_newsletters_categories`
--

DROP TABLE IF EXISTS `dgi_newsletters_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_newsletters_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nl_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_cat_fk_idx` (`cat_id`),
  KEY `news_cl_fk_idx` (`nl_id`),
  CONSTRAINT `news_cat_fk` FOREIGN KEY (`cat_id`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_cl_fk` FOREIGN KEY (`nl_id`) REFERENCES `dgi_newsletters` (`nl_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_partners`
--

DROP TABLE IF EXISTS `dgi_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_partners` (
  `part_id` int(11) NOT NULL AUTO_INCREMENT,
  `part_name` varchar(100) NOT NULL,
  `part_siret` varchar(50) NOT NULL,
  `part_address` varchar(100) NOT NULL,
  `part_employees` int(11) DEFAULT NULL COMMENT '1 - range: 1-10; \n2 - 11-50; \n3 - 51-500; \n4 - >500',
  `part_presentation` varchar(1000) NOT NULL,
  `part_gain` int(11) NOT NULL,
  `part_website` varchar(500) NOT NULL,
  `part_fax` varchar(12) DEFAULT NULL,
  `part_keywords` varchar(1000) DEFAULT '',
  PRIMARY KEY (`part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_partners_categories`
--

DROP TABLE IF EXISTS `dgi_partners_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_partners_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_id_fk_idx` (`part_id`),
  KEY `cat_id_fk_idx` (`cat_id`),
  CONSTRAINT `cat_id_fk` FOREIGN KEY (`cat_id`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `part_id_fk` FOREIGN KEY (`part_id`) REFERENCES `dgi_partners` (`part_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_partners_departments`
--

DROP TABLE IF EXISTS `dgi_partners_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_partners_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_id` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_id_idx` (`part_id`),
  KEY `part_id_fk_idx` (`part_id`),
  KEY `part_dep_part_id_fk_idx` (`part_id`),
  KEY `part_dep_dep_id_fk_idx` (`dep_id`),
  CONSTRAINT `part_dep_dep_id_fk` FOREIGN KEY (`dep_id`) REFERENCES `dgi_departments` (`dep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `part_dep_part_id_fk` FOREIGN KEY (`part_id`) REFERENCES `dgi_partners` (`part_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_programs`
--

DROP TABLE IF EXISTS `dgi_programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_programs` (
  `prog_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `prog_level` int(11) NOT NULL,
  `prog_name` varchar(50) DEFAULT NULL,
  `prog_description` text,
  `prog_created_date` datetime NOT NULL,
  `prog_uuid` varchar(36) DEFAULT NULL,
  `prog_saved` int(11) DEFAULT '1',
  `prog_saved_name` varchar(50) DEFAULT NULL,
  `prog_saved_date` datetime DEFAULT NULL,
  `prog_deleted_date` datetime DEFAULT NULL,
  `prog_deleted_usr` int(11) DEFAULT NULL,
  PRIMARY KEY (`prog_id`),
  KEY `id_user_idx1` (`usr_id`),
  KEY `id_city_idx1` (`city_id`),
  CONSTRAINT `id_city1` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `id_user1` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `dgi_programs_BINS` BEFORE INSERT ON `dgi_programs` FOR EACH ROW
SET new.prog_uuid=uuid() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_proposals`
--

DROP TABLE IF EXISTS `dgi_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_proposals` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `prop_published` int(11) DEFAULT '0',
  `prop_name` varchar(100) DEFAULT '',
  `prop_description` text,
  `prop_published_date` datetime DEFAULT NULL,
  `prop_priority` int(11) DEFAULT '0',
  `prop_uuid` varchar(36) DEFAULT NULL,
  `prop_created_date` datetime NOT NULL,
  `prop_saved_name` varchar(100) NOT NULL,
  `prop_saved_date` datetime DEFAULT NULL,
  `prop_execution` int(11) NOT NULL DEFAULT '0',
  `prop_status` int(11) DEFAULT '0',
  `prop_deleted_date` datetime DEFAULT NULL,
  `prop_deleted_usr` int(11) DEFAULT NULL COMMENT 'id of the user who deleted the proposal',
  `mes_id` int(11) DEFAULT NULL,
  `prop_image1` varchar(100) DEFAULT NULL,
  `prop_image2` varchar(100) DEFAULT NULL,
  `prop_image3` varchar(100) DEFAULT NULL,
  `prop_full_city` int(11) DEFAULT '0',
  `prop_check` int(11) DEFAULT '0',
  `prop_check_timer` datetime DEFAULT NULL,
  `prop_level` int(11) DEFAULT '1',
  `prop_debate_period` int(11) DEFAULT '14',
  `prop_aggregated_score` float DEFAULT '0',
  PRIMARY KEY (`prop_id`),
  UNIQUE KEY `proposition_unique` (`city_id`,`prop_name`),
  KEY `id_category_fk_scn_idx` (`cat_id`),
  KEY `city_prop_fk_idx` (`city_id`),
  KEY `usr_id_fk_idx` (`usr_id`),
  KEY `deleted_usr_fk_idx` (`prop_deleted_usr`),
  KEY `prop_mes_fk_idx` (`mes_id`),
  CONSTRAINT `city_prop_fk` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `deleted_usr_fk` FOREIGN KEY (`prop_deleted_usr`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `id_category_fk_scn` FOREIGN KEY (`cat_id`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_mes_fk` FOREIGN KEY (`mes_id`) REFERENCES `dgi_mesures` (`mes_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `usr_id_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_proposals_BEFORE_INSERT` BEFORE INSERT ON `dgi_proposals` FOR EACH ROW
    SET new.prop_uuid = uuid(), new.prop_created_date=now(), new.prop_name=new.prop_uuid, new.prop_saved_date=new.prop_created_date */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_proposals_programs`
--

DROP TABLE IF EXISTS `dgi_proposals_programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_proposals_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `prog_id` int(11) NOT NULL,
  `sort_position` int(11) DEFAULT '1000',
  PRIMARY KEY (`id`),
  KEY `scn_prop_id_fk1` (`prop_id`),
  KEY `prop_scn_id_fk1_idx` (`prog_id`),
  CONSTRAINT `prop_scn_id_fk1` FOREIGN KEY (`prog_id`) REFERENCES `dgi_programs` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scn_prop_id_fk1` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=371 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_proposition_administrations`
--

DROP TABLE IF EXISTS `dgi_proposition_administrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_proposition_administrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prop_admin_fk_idx` (`prop_id`),
  KEY `usr_admin_fk_idx` (`usr_id`),
  CONSTRAINT `prop_admin_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usr_admin_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_proposition_champions`
--

DROP TABLE IF EXISTS `dgi_proposition_champions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_proposition_champions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prop_champ_fk_idx` (`prop_id`),
  KEY `usr_champ_fk_idx` (`usr_id`),
  CONSTRAINT `prop_champ_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usr_champ_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_proposition_partners`
--

DROP TABLE IF EXISTS `dgi_proposition_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_proposition_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prop_champ_id_idx` (`prop_id`),
  KEY `part_part_fk_idx` (`usr_id`),
  CONSTRAINT `part_part_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prop_part_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_regions`
--

DROP TABLE IF EXISTS `dgi_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_regions` (
  `region_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `region_name` varchar(250) DEFAULT '',
  `region_code` varchar(20) DEFAULT '',
  `region_population` int(11) DEFAULT '0',
  PRIMARY KEY (`region_id`),
  KEY `id_country_reg_fk_idx` (`country_id`),
  CONSTRAINT `id_country_reg_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_report`
--

DROP TABLE IF EXISTS `dgi_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_report` (
  `rep_id` int(11) NOT NULL AUTO_INCREMENT,
  `rep_type` varchar(45) NOT NULL COMMENT 'type=(program|proposition|comment|inbox)',
  `rep_uuid` varchar(36) NOT NULL,
  `rep_created_date` datetime DEFAULT NULL,
  `rep_reason` varchar(300) DEFAULT '',
  `rep_description` varchar(5000) DEFAULT '',
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`rep_id`),
  KEY `rep_usr_fk_idx` (`usr_id`),
  CONSTRAINT `rep_usr_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_report_BEFORE_INSERT` BEFORE INSERT ON `dgi_report` FOR EACH ROW
    SET new.rep_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_scenarios`
--

DROP TABLE IF EXISTS `dgi_scenarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_scenarios` (
  `scn_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `scn_name` varchar(50) DEFAULT NULL,
  `scn_description` varchar(2000) DEFAULT NULL,
  `scn_created_date` datetime NOT NULL,
  `scn_uuid` varchar(36) DEFAULT NULL,
  `scn_main` int(11) DEFAULT '0',
  `scn_saved` int(11) DEFAULT '1',
  `scn_saved_name` varchar(50) DEFAULT NULL,
  `scn_saved_date` datetime NOT NULL,
  `scn_deleted_date` datetime DEFAULT NULL,
  `scn_deleted_usr` int(11) DEFAULT NULL,
  PRIMARY KEY (`scn_id`),
  UNIQUE KEY `scenario_unique` (`scn_name`,`city_id`),
  KEY `id_user_idx` (`usr_id`),
  KEY `id_city_idx` (`city_id`),
  CONSTRAINT `id_city` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_user` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_scenarios_BEFORE_INSERT` BEFORE INSERT ON `dgi_scenarios` FOR EACH ROW
     SET new.scn_uuid=uuid(), new.scn_created_date=now(), new.scn_saved_date=new.scn_created_date */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_search_terms`
--

DROP TABLE IF EXISTS `dgi_search_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_search_terms` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL COMMENT 'User ID',
  `dep_id` int(11) DEFAULT NULL COMMENT 'Department ID',
  `cat_id` int(11) DEFAULT NULL COMMENT 'Category ID',
  `search_keywords` varchar(1000) DEFAULT NULL COMMENT 'Search keywords separated by ''|''',
  `search_name` varchar(100) DEFAULT '',
  PRIMARY KEY (`search_id`),
  KEY `src_fk_usr_idx` (`usr_id`),
  KEY `src_fk_dep_idx` (`dep_id`),
  KEY `src_fk_cat_idx` (`cat_id`),
  CONSTRAINT `src_fk_cat` FOREIGN KEY (`cat_id`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `src_fk_dep` FOREIGN KEY (`dep_id`) REFERENCES `dgi_departments` (`dep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `src_fk_usr` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table stores the saved terms of search in partner opportunities ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_steps`
--

DROP TABLE IF EXISTS `dgi_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_steps` (
  `step_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `step_name` varchar(100) NOT NULL,
  `step_description` varchar(1000) DEFAULT NULL,
  `step_type` int(11) NOT NULL COMMENT '0 - planning\n1 - execution\n2 - follow-up',
  `step_start_date` datetime DEFAULT NULL,
  `step_end_date` datetime DEFAULT NULL,
  `step_cost` float DEFAULT '0',
  `step_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 - recomended',
  `step_created_date` datetime DEFAULT NULL,
  `responsable` int(11) DEFAULT NULL,
  `step_uuid` varchar(36) DEFAULT NULL,
  `prop_id` int(11) NOT NULL,
  PRIMARY KEY (`step_id`),
  UNIQUE KEY `step_unique` (`prop_id`,`step_name`),
  KEY `time_usr_id_fk_idx` (`usr_id`),
  KEY `time_prop_id_fk_idx` (`prop_id`),
  KEY `time_resp_id_fk_idx` (`responsable`),
  CONSTRAINT `step_prop_id` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `step_resp_id` FOREIGN KEY (`responsable`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `step_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_steps_BEFORE_INSERT` BEFORE INSERT ON `dgi_steps` FOR EACH ROW
    SET new.step_uuid = uuid(), new.step_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_users`
--

DROP TABLE IF EXISTS `dgi_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_users` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usr_password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usr_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usrl_id` int(11) DEFAULT NULL COMMENT 'User Type',
  `lang_id` varchar(2) NOT NULL,
  `usr_active` tinyint(1) NOT NULL,
  `usr_question` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_answer` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_picture` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '/img/avatar/avatar.png',
  `usr_password_salt` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'dynamicSalt',
  `usr_registration_date` datetime DEFAULT NULL COMMENT 'Registration moment',
  `usr_registration_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'unique id sent by e-mail',
  `usr_email_confirmed` tinyint(1) NOT NULL COMMENT 'e-mail confirmed by user',
  `usr_firstname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_lastname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_country` varchar(2) DEFAULT NULL,
  `usr_postal_code` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usr_city` varchar(50) DEFAULT '',
  `city_id` int(11) NOT NULL,
  `usr_phone` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_birthday` int(11) DEFAULT NULL,
  `usr_gendre` int(11) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `usr_change_city_date` datetime NOT NULL,
  `usr_deleted` int(11) DEFAULT '0',
  `usr_deleted_date` datetime DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `usr_uuid` varchar(36) DEFAULT NULL,
  `usr_presentation` varchar(1000) DEFAULT '',
  `usr_last_login_date` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `cnt_id` int(11) NOT NULL,
  `usr_current_login_date` datetime DEFAULT NULL,
  PRIMARY KEY (`usr_id`),
  KEY `city_usr_fk_idx` (`city_id`),
  KEY `country_usr_fk_idx` (`country_id`),
  KEY `part_id_idx` (`part_id`),
  KEY `usr_part_id_idx` (`part_id`),
  KEY `fk_user_admin_idx` (`admin_id`),
  KEY `usr_lang_fk_idx` (`lang_id`),
  KEY `usr_cnt_fk_idx` (`cnt_id`),
  CONSTRAINT `city_usr_fk` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `country_usr_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_admin` FOREIGN KEY (`admin_id`) REFERENCES `dgi_administration` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usr_cnt_fk` FOREIGN KEY (`cnt_id`) REFERENCES `dgi_counters` (`cnt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `usr_lang_fk` FOREIGN KEY (`lang_id`) REFERENCES `dgi_languages` (`lang_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `usr_part_id` FOREIGN KEY (`part_id`) REFERENCES `dgi_partners` (`part_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_users_BEFORE_INSERT` BEFORE INSERT ON `dgi_users` FOR EACH ROW
    SET new.usr_uuid=uuid() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `dgi_users_contacts`
--

DROP TABLE IF EXISTS `dgi_users_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_users_contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `contact_usr_id` int(11) NOT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `fk_dgi_users_contacts_1_idx` (`usr_id`),
  KEY `fk_dgi_users_contacts_2_idx` (`contact_usr_id`),
  CONSTRAINT `fk_dgi_users_contacts_1` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_users_contacts_2` FOREIGN KEY (`contact_usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_votes`
--

DROP TABLE IF EXISTS `dgi_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `vote_vote` int(11) NOT NULL,
  `vote_priority` int(11) DEFAULT '0',
  `vote_uuid` varchar(36) DEFAULT NULL,
  `vote_created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`vote_id`),
  UNIQUE KEY `vote_unique` (`usr_id`,`prop_id`),
  KEY `id_user_fk_idx` (`usr_id`),
  KEY `id_proposition_fk_idx` (`prop_id`),
  CONSTRAINT `id_proposition_fk_votes` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_user_fk_votes` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE TRIGGER `demodyne_prod`.`dgi_votes_BEFORE_INSERT` BEFORE INSERT ON `dgi_votes` FOR EACH ROW
    SET new.vote_uuid = uuid(), new.vote_created_date=now() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-21 15:46:19
