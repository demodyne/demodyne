-- MySQL dump 10.13  Distrib 5.7.12, for linux-glibc2.5 (x86_64)
--
-- Host: demodyne.org    Database: demodyne_prod
-- ------------------------------------------------------
-- Server version	5.6.35

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Table for administration account ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_articles`
--

DROP TABLE IF EXISTS `dgi_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_articles` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `article_title` text NOT NULL,
  `article_description` text NOT NULL,
  `article_created_date` datetime DEFAULT NULL,
  `article_published_date` datetime DEFAULT NULL,
  `article_category` int(11) DEFAULT NULL,
  `article_image` varchar(500) DEFAULT NULL,
  `article_views` int(11) DEFAULT '0',
  `article_slug` varchar(1024) DEFAULT NULL,
  `article_uuid` varchar(36) DEFAULT NULL,
  `article_featured` int(11) DEFAULT '0',
  PRIMARY KEY (`article_id`),
  KEY `fk_dgi_articles_1` (`usr_id`),
  KEY `fk_dgi_articles_2_idx` (`country_id`),
  CONSTRAINT `fk_dgi_articles_1` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_articles_2` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='this table contains the articles from the blog';
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
  `city_id` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_blocked_users`
--

DROP TABLE IF EXISTS `dgi_blocked_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_blocked_users` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `entity_uuid` varchar(36) NOT NULL,
  `entity_type` varchar(45) DEFAULT NULL,
  `block_created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`block_id`),
  KEY `fk_blocked_users_1_idx` (`usr_id`),
  CONSTRAINT `fk_blocked_users_1` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=482 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_chat_messages`
--

DROP TABLE IF EXISTS `dgi_chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_chat_messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `msg_text` text,
  `msg_date_time` datetime DEFAULT NULL,
  `msg_entity_uuid` varchar(36) DEFAULT NULL,
  `blocked_usr_id` int(11) DEFAULT NULL,
  `msg_entity_name` varchar(100) DEFAULT '',
  PRIMARY KEY (`msg_id`),
  KEY `fk_dgi_chat_messages_1_idx` (`chat_id`),
  KEY `fk_dgi_chat_messages_2_idx` (`usr_id`),
  KEY `fk_dgi_chat_messages_3_idx` (`blocked_usr_id`),
  CONSTRAINT `fk_dgi_chat_messages_1` FOREIGN KEY (`chat_id`) REFERENCES `dgi_chats` (`chat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_chat_messages_2` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_chat_messages_3` FOREIGN KEY (`blocked_usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_chats`
--

DROP TABLE IF EXISTS `dgi_chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_chats` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_entity_type` varchar(45) DEFAULT NULL,
  `chat_entity_uuid` varchar(36) DEFAULT NULL,
  `chat_uuid` varchar(36) DEFAULT NULL,
  `chat_start_date` datetime DEFAULT NULL,
  `chat_end_date` datetime DEFAULT NULL,
  `chat_opened` tinyint(1) DEFAULT '0',
  `usr_id` int(11) DEFAULT NULL,
  `chat_title` varchar(500) DEFAULT '',
  PRIMARY KEY (`chat_id`),
  KEY `fk_dgi_chats_1_idx` (`usr_id`),
  CONSTRAINT `fk_dgi_chats_1` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `dep_id` int(11) DEFAULT NULL,
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
  `country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`city_id`),
  KEY `city_country_fk_idx` (`country_id`),
  KEY `city_full_city_idx` (`full_city_id`),
  KEY `city_region_id_idx` (`region_id`),
  KEY `fk_dgi_cities_1_idx` (`dep_id`),
  CONSTRAINT `city_country_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `city_full_city` FOREIGN KEY (`full_city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `city_region_id` FOREIGN KEY (`region_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_cities_1` FOREIGN KEY (`dep_id`) REFERENCES `dgi_departments` (`dep_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=105720 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;
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
  `article_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`com_id`),
  KEY `com_com_fk_idx` (`com_com`),
  KEY `com_usr_fk_idx` (`com_usr`),
  KEY `com_prop_fk_idx` (`prop_id`),
  KEY `com_scn_fk_idx` (`prog_id`),
  KEY `fk_dgi_comments_1_idx` (`article_id`),
  CONSTRAINT `com_com_fk` FOREIGN KEY (`com_com`) REFERENCES `dgi_comments` (`com_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_prop_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_scn_fk` FOREIGN KEY (`prog_id`) REFERENCES `dgi_programs` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_usr_fk` FOREIGN KEY (`com_usr`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_comments_1` FOREIGN KEY (`article_id`) REFERENCES `dgi_articles` (`article_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=402 DEFAULT CHARSET=utf8 COMMENT='Used to manage user counters';
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `country_region_name` varchar(45) DEFAULT NULL,
  `country_format` varchar(5) DEFAULT '',
  `country_postalcode` int(11) DEFAULT '5',
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
  `country_id` int(11) NOT NULL,
  `reg_id` int(11) NOT NULL,
  `dep_code` varchar(3) NOT NULL,
  `dep_name` varchar(250) NOT NULL,
  `region_name` varchar(100) DEFAULT '',
  PRIMARY KEY (`dep_id`),
  KEY `code` (`dep_code`),
  KEY `region_fk_idx` (`reg_id`),
  KEY `country_fk_idx` (`country_id`),
  CONSTRAINT `country_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `region_fk` FOREIGN KEY (`reg_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1437 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=8150 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
  `event_end_date` datetime DEFAULT NULL,
  `event_created_date` datetime DEFAULT NULL,
  `event_published_date` datetime DEFAULT NULL,
  `event_canceled_date` datetime DEFAULT NULL,
  `event_uuid` varchar(36) DEFAULT NULL,
  `event_location` varchar(500) DEFAULT NULL,
  `event_relevant_region` int(11) DEFAULT '0',
  `event_relevant_country` int(11) DEFAULT '0',
  `event_level` int(11) DEFAULT '1',
  `event_full_city` int(11) DEFAULT '0',
  `event_session` tinyint(1) DEFAULT '0',
  `event_session_completed` tinyint(1) DEFAULT '0',
  `chat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `ev_usr_id_idx` (`usr_id`),
  KEY `ev_city_id_idx` (`city_id`),
  KEY `ev_region_id_idx` (`region_id`),
  KEY `ev_country_id_idx` (`country_id`),
  KEY `fk_dgi_events_1_idx` (`chat_id`),
  CONSTRAINT `ev_city_id` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_country_id` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_region_id` FOREIGN KEY (`region_id`) REFERENCES `dgi_regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_events_1` FOREIGN KEY (`chat_id`) REFERENCES `dgi_chats` (`chat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_events_invitations`
--

DROP TABLE IF EXISTS `dgi_events_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_events_invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_usr_fk_idx` (`event_id`),
  KEY `usr_event_fk_idx` (`usr_id`),
  CONSTRAINT `inv_event_usr_fk` FOREIGN KEY (`event_id`) REFERENCES `dgi_events` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `inv_usr_event_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_idea_proposals`
--

DROP TABLE IF EXISTS `dgi_idea_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_idea_proposals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idea_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_new_table_1_idx` (`idea_id`),
  KEY `fk_new_table_2_idx` (`prop_id`),
  CONSTRAINT `fk_new_table_1` FOREIGN KEY (`idea_id`) REFERENCES `dgi_ideas` (`idea_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_new_table_2` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_ideas`
--

DROP TABLE IF EXISTS `dgi_ideas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_ideas` (
  `idea_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `idea_name` varchar(100) DEFAULT '',
  `idea_description` text,
  `idea_uuid` varchar(36) DEFAULT NULL,
  `idea_created_date` datetime DEFAULT NULL,
  `idea_status` int(11) DEFAULT '0',
  `idea_position` int(11) DEFAULT '0',
  `idea_validated` tinyint(1) DEFAULT '0',
  `prop_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idea_id`),
  KEY `fk_dgi_ideas_1_idx` (`usr_id`),
  KEY `fk_dgi_ideas_2_idx` (`cat_id`),
  KEY `fk_dgi_ideas_3_idx` (`event_id`),
  KEY `fk_dgi_ideas_4_idx` (`prop_id`),
  CONSTRAINT `fk_dgi_ideas_1` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_ideas_2` FOREIGN KEY (`cat_id`) REFERENCES `dgi_categories` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_ideas_3` FOREIGN KEY (`event_id`) REFERENCES `dgi_events` (`event_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_dgi_ideas_4` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;
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
  `event_id` int(11) DEFAULT NULL,
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
  KEY `fk_dgi_inbox_1_idx` (`event_id`),
  CONSTRAINT `com_id` FOREIGN KEY (`com_id`) REFERENCES `dgi_comments` (`com_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_inbox_1` FOREIGN KEY (`event_id`) REFERENCES `dgi_events` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `from_usr_id` FOREIGN KEY (`from_usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ibx_prop_id` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inbox_nl_fk` FOREIGN KEY (`nl_id`) REFERENCES `dgi_newsletters` (`nl_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scn_jd` FOREIGN KEY (`prog_id`) REFERENCES `dgi_programs` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `to_usr_id` FOREIGN KEY (`to_usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=370 DEFAULT CHARSET=utf8 COMMENT='Table used for emailing in Demodyne application';
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;
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
  `event_id` int(11) DEFAULT NULL,
  `news_level` int(11) DEFAULT '1',
  PRIMARY KEY (`news_id`),
  KEY `city_id_idx` (`city_id`),
  KEY `prop_id_idx` (`prop_id`),
  KEY `news_usr_fk_idx` (`usr_id`),
  KEY `fk_dgi_news_1_idx` (`event_id`),
  CONSTRAINT `city_id` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_news_1` FOREIGN KEY (`event_id`) REFERENCES `dgi_events` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `news_usr_fk` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prop_id_fk` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=latin1 COMMENT='Table used for recording all new concerning a city';
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_proposal_links`
--

DROP TABLE IF EXISTS `dgi_proposal_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_proposal_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dgi_proposal_links_1_idx` (`prop_id`),
  KEY `fk_dgi_proposal_links_2_idx` (`link_id`),
  CONSTRAINT `fk_dgi_proposal_links_1` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_proposal_links_2` FOREIGN KEY (`link_id`) REFERENCES `dgi_links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `prop_status_change_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `prop_views` int(11) DEFAULT '0',
  PRIMARY KEY (`prop_id`),
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
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=388 DEFAULT CHARSET=utf8;
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
  `region_code` varchar(20) DEFAULT NULL,
  `region_population` int(11) DEFAULT '0',
  `region_sub` varchar(500) DEFAULT NULL,
  `region_timezone` varchar(45) DEFAULT 'UTC',
  PRIMARY KEY (`region_id`),
  KEY `id_country_reg_fk_idx` (`country_id`),
  CONSTRAINT `id_country_reg_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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

--
-- Table structure for table `dgi_user_digest`
--

DROP TABLE IF EXISTS `dgi_user_digest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_user_digest` (
  `digest_id` int(11) NOT NULL AUTO_INCREMENT,
  `digest_frequency` int(11) DEFAULT '2' COMMENT '0 - never\n1 - daily\n2 - weekly\n3 - monthly',
  `digest_highlights` int(11) DEFAULT '1' COMMENT 'if the user receives the highlights (aka blog articles)',
  `digest_prop_prog` int(11) DEFAULT '1' COMMENT 'if user receives new proposals, measures and programs',
  `digest_academy` int(11) DEFAULT '1' COMMENT 'if the user receives infos about academy program',
  `digest_event` int(11) DEFAULT '1' COMMENT 'if user receives new events and sessions',
  `digest_sent_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `digest_alert_comments` int(11) DEFAULT '2',
  `digest_alert_status` int(11) DEFAULT '2',
  `digest_alert_event` int(11) DEFAULT '2',
  `digest_alert_private` int(11) DEFAULT '2',
  `digest_alert_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `digest_alert_updates` int(11) DEFAULT '1' COMMENT 'Proposals and Measures updates: status change, prolong debate, modifications in name/description/category',
  PRIMARY KEY (`digest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=466 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dgi_users`
--

DROP TABLE IF EXISTS `dgi_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dgi_users` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_name` varchar(100) NOT NULL,
  `usr_password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usr_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usrl_id` int(11) DEFAULT NULL COMMENT 'User Type',
  `lang_id` varchar(2) NOT NULL,
  `usr_active` tinyint(1) NOT NULL,
  `usr_question` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_answer` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_picture` varchar(255) DEFAULT '/img/avatar/avatar.png',
  `usr_password_salt` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'dynamicSalt',
  `usr_registration_date` datetime DEFAULT NULL COMMENT 'Registration moment',
  `usr_registration_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'unique id sent by e-mail',
  `usr_email_confirmed` tinyint(1) NOT NULL COMMENT 'e-mail confirmed by user',
  `usr_firstname` varchar(50) DEFAULT NULL,
  `usr_lastname` varchar(50) DEFAULT NULL,
  `usr_country` varchar(2) DEFAULT NULL,
  `usr_postal_code` varchar(10) DEFAULT NULL,
  `usr_city` varchar(50) DEFAULT '',
  `city_id` int(11) DEFAULT NULL,
  `usr_phone` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usr_birthday` int(11) DEFAULT NULL,
  `usr_gendre` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `usr_change_city_date` datetime DEFAULT NULL,
  `usr_deleted` int(11) DEFAULT '0',
  `usr_deleted_date` datetime DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `usr_uuid` varchar(36) DEFAULT NULL,
  `usr_presentation` varchar(1000) DEFAULT '',
  `usr_last_login_date` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `cnt_id` int(11) NOT NULL,
  `usr_current_login_date` datetime DEFAULT NULL,
  `fb_id` bigint(20) unsigned DEFAULT NULL,
  `fb_access_token` varchar(255) DEFAULT NULL,
  `digest_id` int(11) DEFAULT NULL,
  `usr_nir` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`usr_id`),
  KEY `city_usr_fk_idx` (`city_id`),
  KEY `country_usr_fk_idx` (`country_id`),
  KEY `part_id_idx` (`part_id`),
  KEY `usr_part_id_idx` (`part_id`),
  KEY `fk_user_admin_idx` (`admin_id`),
  KEY `usr_lang_fk_idx` (`lang_id`),
  KEY `usr_cnt_fk_idx` (`cnt_id`),
  KEY `fk_dgi_users_1_idx` (`digest_id`),
  CONSTRAINT `city_usr_fk` FOREIGN KEY (`city_id`) REFERENCES `dgi_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `country_usr_fk` FOREIGN KEY (`country_id`) REFERENCES `dgi_countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dgi_users_1` FOREIGN KEY (`digest_id`) REFERENCES `dgi_user_digest` (`digest_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_admin` FOREIGN KEY (`admin_id`) REFERENCES `dgi_administration` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usr_cnt_fk` FOREIGN KEY (`cnt_id`) REFERENCES `dgi_counters` (`cnt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `usr_lang_fk` FOREIGN KEY (`lang_id`) REFERENCES `dgi_languages` (`lang_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `usr_part_id` FOREIGN KEY (`part_id`) REFERENCES `dgi_partners` (`part_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=466 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
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
  KEY `id_user_fk_idx` (`usr_id`),
  KEY `id_proposition_fk_idx` (`prop_id`),
  CONSTRAINT `id_proposition_fk_votes` FOREIGN KEY (`prop_id`) REFERENCES `dgi_proposals` (`prop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_user_fk_votes` FOREIGN KEY (`usr_id`) REFERENCES `dgi_users` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-30 23:14:26
