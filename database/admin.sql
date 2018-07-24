-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: laravel-shop
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'首页','fa-bar-chart','/',NULL,'2018-07-18 13:54:12'),(2,0,6,'系统管理','fa-tasks',NULL,NULL,'2018-07-24 10:27:46'),(3,2,7,'管理员','fa-users','auth/users',NULL,'2018-07-24 10:27:46'),(4,2,8,'角色','fa-user','auth/roles',NULL,'2018-07-24 10:27:46'),(5,2,9,'权限','fa-ban','auth/permissions',NULL,'2018-07-24 10:27:46'),(6,2,10,'菜单','fa-bars','auth/menu',NULL,'2018-07-24 10:27:46'),(7,2,11,'操作日志','fa-history','auth/logs',NULL,'2018-07-24 10:27:46'),(8,0,2,'用户管理','fa-users','users','2018-07-18 13:57:24','2018-07-18 13:57:35'),(9,0,3,'商品管理','fa-cubes','/products','2018-07-18 14:33:12','2018-07-18 14:33:31'),(10,0,4,'订单管理','fa-rmb','/orders','2018-07-23 15:19:53','2018-07-23 15:20:14'),(11,0,5,'优惠券管理','fa-dollar','/coupon_codes','2018-07-24 10:27:41','2018-07-24 10:27:46');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'所有权限','*','','*',NULL,'2018-07-18 14:04:12'),(2,'后台首页','dashboard','GET','/',NULL,'2018-07-18 14:03:56'),(3,'登录','auth.login','','/auth/login\r\n/auth/logout',NULL,'2018-07-18 14:03:37'),(4,'用户设置','auth.setting','GET,PUT','/auth/setting',NULL,'2018-07-18 14:03:29'),(5,'权限管理','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,'2018-07-18 14:03:16'),(6,'用户管理','users','','/users*','2018-07-18 14:02:58','2018-07-18 14:02:58'),(7,'产品管理','products','','/products*','2018-07-24 16:07:52','2018-07-24 16:07:52'),(8,'订单管理','orders','','/orders*','2018-07-24 16:08:18','2018-07-24 16:08:18'),(9,'优惠券管理','coupon_codes','','/coupon_codes*','2018-07-24 16:08:48','2018-07-24 16:08:48');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,7,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'管理员','administrator','2018-07-18 13:46:02','2018-07-18 14:06:15'),(2,'运营','operator','2018-07-18 14:05:36','2018-07-18 14:05:36');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$wFMLHNLV/KZox39Bvjjlau19.VcNb2bTGJhsq9WvufbA1Km2YPk06','管理员',NULL,'0UVz5kGiJiPOZEvPYtw4vcktKPjhyheYNPsD3ylLr48VeCsUrnUQjoyD7LFB','2018-07-18 13:46:02','2018-07-18 14:06:42'),(2,'operator','$2y$10$IOpxb9TGAQz82B3EI/vLa.sAA9L4cf830bxmM4CNcsmZoi5I74CL.','运营',NULL,'TwsAm8CbIZaTxnuykLfIsLify695MwQ0gQAx1zxJQjUI9tTjpaHU8mqBkDkR','2018-07-18 14:05:01','2018-07-18 14:05:01');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-24  8:10:47
