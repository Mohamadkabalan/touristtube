
DROP TABLE IF EXISTS `360_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `360_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `phone1` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `main_contact_person` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accounting_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `main_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `360_app_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `360_app_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(10) NOT NULL,
  `os` varchar(20) DEFAULT NULL,
  `version_date` date NOT NULL,
  `force_update` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `360_hotel_to_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `360_hotel_to_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotel_account_id` (`hotel_id`,`account_id`),
  KEY `fk_accId_idx_idx` (`account_id`),
  CONSTRAINT `fk_accId_idx` FOREIGN KEY (`account_id`) REFERENCES `360_accounts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_hotelId_idx` FOREIGN KEY (`hotel_id`) REFERENCES `cms_hotel` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;






DROP TABLE IF EXISTS `backend_admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `depth` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `backend_pages`
--

DROP TABLE IF EXISTS `backend_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `depth` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2074E575989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `backend_users_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_users_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



--
-- Table structure for table `backend_users`
--

DROP TABLE IF EXISTS `backend_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `users_group_id` int(3) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_account_fk` (`account_id`),
  KEY `fk_users_group_id` (`users_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;




--
-- Table structure for table `360_renewal_history`
--

DROP TABLE IF EXISTS `360_renewal_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `360_renewal_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `renewal_date` datetime NOT NULL,
  `expiry_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `renewal_user_fk` (`user_id`),
  KEY `renewal_account_fk` (`account_id`),
  KEY `currency` (`currency`),
  CONSTRAINT `renewal_account_fk` FOREIGN KEY (`account_id`) REFERENCES `360_accounts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `renewal_user_fk` FOREIGN KEY (`user_id`) REFERENCES `backend_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `360_users_excluded_hotels`
--

DROP TABLE IF EXISTS `360_users_excluded_hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `360_users_excluded_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_to_users_fk1` (`user_id`),
  KEY `hotel_to_users_fk2` (`hotel_id`),
  CONSTRAINT `fk_htu_hotelId` FOREIGN KEY (`hotel_id`) REFERENCES `cms_hotel` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_htu_userId` FOREIGN KEY (`user_id`) REFERENCES `backend_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;




INSERT INTO `360_accounts` VALUES (1,'Tourist Tube','000000','000000','000000','000000','Michel Sfeir','accounting@touristtube.com','contact@touristtube.com',1,0,'2020-01-08');
INSERT INTO `360_app_version` VALUES (1,'1.2','ios','2019-02-05',1),(2,'1.0','android','2019-02-07',1);
INSERT INTO `backend_admin_menu` VALUES (1,'Dashboard','dashboard','fa fa-dashboard',0,'1'),(2,'Manage Pages','pages','fa fa-edit',0,'2'),(5,'Manage Sub Pages','pages','fa fa-circle-o',2,'2,5'),(6,'Manage Sub Pages two','pages','fa fa-circle-o',5,'2,5,6'),(8,'Divisions Categories','divisionscategories','fa fa-circle-o',0,'1'),(9,'Categories Groups','divisionscategoriesgroups','fa fa-circle-o',0,'1'),(10,'Hotels','hotels','fa fa-circle-o',0,'1'),(11,'Divisions','divisions','fa fa-circle-o',0,'1'),(12,'Hotel Divisions','hotelhoteldivs','fa fa-circle-o',0,'1'),(13,'Accounts','accounts','fa fa-circle-o',0,'1'),(14,'Users','users','fa fa-circle-o',0,'1'),(15,'Hotels Allowed Categories','hotelhoteldivscats','fa fa-circle-o',0,'1');
INSERT INTO `backend_users_group` VALUES (1,'API','ROLE_API');
INSERT INTO `backend_users` VALUES (1,'touristtube','touristtube','touristtube@touristtube.com','touristtube','e0aed9ca3e4efbaeb716bfdf9c209a320b8850d4','di3cmy2kHlkiRVYb8a2GPiIfEwsbFqMndndCvcgoIN1EQBTEYnF0KzRedDXW9i56',1,1,0,1,0);
