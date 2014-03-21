-- MySQL dump 10.11
--
-- Host: localhost    Database: cwebftp
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny4-log

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
-- Current Database: `cwebftp`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cwebftp` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cwebftp`;

--
-- Table structure for table `ftpgroup`
--

DROP TABLE IF EXISTS `ftpgroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ftpgroup` (
  `groupname` varchar(16) NOT NULL default '',
  `gid` smallint(6) NOT NULL default '5500',
  `members` varchar(16) NOT NULL default '',
  KEY `groupname` (`groupname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ProFTP group table';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ftpgroup`
--

LOCK TABLES `ftpgroup` WRITE;
/*!40000 ALTER TABLE `ftpgroup` DISABLE KEYS */;
INSERT INTO `ftpgroup` VALUES ('ftpgroup',2001,'ftpuser');
/*!40000 ALTER TABLE `ftpgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ftpquotalimits`
--

DROP TABLE IF EXISTS `ftpquotalimits`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ftpquotalimits` (
  `name` varchar(30) default NULL,
  `quota_type` enum('user','group','class','all') NOT NULL default 'user',
  `per_session` enum('false','true') NOT NULL default 'false',
  `limit_type` enum('soft','hard') NOT NULL default 'soft',
  `bytes_in_avail` bigint(20) unsigned NOT NULL default '0',
  `bytes_out_avail` bigint(20) unsigned NOT NULL default '0',
  `bytes_xfer_avail` bigint(20) unsigned NOT NULL default '0',
  `files_in_avail` int(10) unsigned NOT NULL default '0',
  `files_out_avail` int(10) unsigned NOT NULL default '0',
  `files_xfer_avail` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ftpquotalimits`
--

LOCK TABLES `ftpquotalimits` WRITE;
/*!40000 ALTER TABLE `ftpquotalimits` DISABLE KEYS */;
INSERT INTO `ftpquotalimits` VALUES ('exampleuser','user','true','hard',15728640,0,0,0,0,0);
/*!40000 ALTER TABLE `ftpquotalimits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ftpquotatallies`
--

DROP TABLE IF EXISTS `ftpquotatallies`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ftpquotatallies` (
  `name` varchar(30) NOT NULL default '',
  `quota_type` enum('user','group','class','all') NOT NULL default 'user',
  `bytes_in_used` bigint(20) unsigned NOT NULL default '0',
  `bytes_out_used` bigint(20) unsigned NOT NULL default '0',
  `bytes_xfer_used` bigint(20) unsigned NOT NULL default '0',
  `files_in_used` int(10) unsigned NOT NULL default '0',
  `files_out_used` int(10) unsigned NOT NULL default '0',
  `files_xfer_used` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ftpquotatallies`
--

LOCK TABLES `ftpquotatallies` WRITE;
/*!40000 ALTER TABLE `ftpquotatallies` DISABLE KEYS */;
INSERT INTO `ftpquotatallies` VALUES ('exampleuser','user',0,0,0,0,0,0);
/*!40000 ALTER TABLE `ftpquotatallies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ftpuser`
--

DROP TABLE IF EXISTS `ftpuser`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ftpuser` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` varchar(32) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `uid` smallint(6) NOT NULL default '5500',
  `gid` smallint(6) NOT NULL default '5500',
  `homedir` varchar(255) NOT NULL default '',
  `shell` varchar(16) NOT NULL default '/sbin/nologin',
  `count` int(11) NOT NULL default '0',
  `accessed` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `enabled` enum('Y','N') character set utf8 collate utf8_bin NOT NULL default 'N',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='ProFTP user table';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ftpuser`
--

LOCK TABLES `ftpuser` WRITE;
/*!40000 ALTER TABLE `ftpuser` DISABLE KEYS */;
INSERT INTO `ftpuser` VALUES (1,'exampleuser','secret',2001,2001,'/home/FTP/exampleuser','/sbin/nologin',6,'2010-06-21 02:34:23','0000-00-00 00:00:00','N');
/*!40000 ALTER TABLE `ftpuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webuser`
--

DROP TABLE IF EXISTS `webuser`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `webuser` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(32) character set utf8 collate utf8_bin NOT NULL,
  `password` varchar(32) character set utf8 collate utf8_bin NOT NULL,
  `enabled` enum('Y','N') character set utf8 collate utf8_bin NOT NULL,
  `role` enum('admin','superuser','user','nobody') character set utf8 collate utf8_bin NOT NULL default 'nobody',
  `fullname` varchar(128) character set utf8 collate utf8_bin NOT NULL,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `who` varchar(32) character set utf8 collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `webuser`
--

LOCK TABLES `webuser` WRITE;
/*!40000 ALTER TABLE `webuser` DISABLE KEYS */;
INSERT INTO `webuser` VALUES (1,'admin','f9620b2992d9134421fd292f8834af3b','Y','admin','Pawel Biel','2010-06-12 08:22:01','l4vim'),(57,'user','6ad14ba9986e3615423dfca256d04e3f','Y','','jakis user','2010-06-21 01:08:00','admin'),(58,'super','367ce54e4f4c30e695e39ed946abb0bd','Y','superuser','szefu','2010-06-21 01:08:07','admin');
/*!40000 ALTER TABLE `webuser` ENABLE KEYS */;
UNLOCK TABLES;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `webuser_insert_log_t` AFTER INSERT ON `webuser` FOR EACH ROW INSERT INTO webuser_log values ('',new.username,'-supressed-',new.enabled,new.role,new.fullname,new.modified,new.who,'i') */;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `webuser_update_log_t` AFTER UPDATE ON `webuser` FOR EACH ROW INSERT INTO webuser_log values ('',new.username,'-supressed-',new.enabled,new.role,new.fullname,new.modified,new.who,'u') */;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `webuser_delete_log_t` AFTER DELETE ON `webuser` FOR EACH ROW INSERT INTO webuser_log values ('',old.username,'-supressed-',old.enabled,old.role,old.fullname,old.modified,old.who,'d') */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `webuser_log`
--

DROP TABLE IF EXISTS `webuser_log`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `webuser_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(32) character set utf8 collate utf8_bin NOT NULL,
  `password` varchar(32) character set utf8 collate utf8_bin NOT NULL,
  `enabled` enum('Y','N') character set utf8 collate utf8_bin NOT NULL,
  `role` enum('admin','superuser','user','nobody') character set utf8 collate utf8_bin NOT NULL default 'nobody',
  `fullname` varchar(128) character set utf8 collate utf8_bin NOT NULL,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `who` varchar(32) character set utf8 collate utf8_bin NOT NULL,
  `operation` enum('i','u','d') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1168 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `webuser_log`
--

LOCK TABLES `webuser_log` WRITE;
/*!40000 ALTER TABLE `webuser_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `webuser_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-06-21 17:22:41
