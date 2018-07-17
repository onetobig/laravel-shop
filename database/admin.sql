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
INSERT INTO `admin_menu` VALUES (1,0,1,'首页','fa-bar-chart','/',NULL,'2018-07-16 09:06:53'),(2,0,6,'系统管理','fa-tasks',NULL,NULL,'2018-07-17 03:32:32'),(3,2,7,'管理员','fa-users','auth/users',NULL,'2018-07-17 03:32:32'),(4,2,8,'角色','fa-user','auth/roles',NULL,'2018-07-17 03:32:32'),(5,2,9,'权限','fa-ban','auth/permissions',NULL,'2018-07-17 03:32:32'),(6,2,10,'菜单','fa-bars','auth/menu',NULL,'2018-07-17 03:32:32'),(7,2,11,'操作日志','fa-history','auth/logs',NULL,'2018-07-17 03:32:32'),(8,0,2,'用户管理','fa-users','/users','2018-07-16 09:20:10','2018-07-16 09:47:12'),(9,0,3,'商品管理','fa-cubes','/products','2018-07-16 09:47:02','2018-07-16 09:47:12'),(10,0,4,'订单管理','fa-rmb','/orders','2018-07-17 01:38:50','2018-07-17 01:39:04'),(11,0,12,'系统配置','fa-toggle-on','configs','2018-07-17 02:22:33','2018-07-17 06:31:57'),(12,0,5,'优惠券管理','fa-tags','/coupon_codes','2018-07-17 03:32:15','2018-07-17 03:32:32');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'所有权限','*','','*',NULL,'2018-07-16 09:35:00'),(2,'首页面板','dashboard','GET','/',NULL,'2018-07-16 09:34:47'),(3,'登录','auth.login','','/auth/login\r\n/auth/logout',NULL,'2018-07-16 09:34:33'),(4,'用户设置','auth.setting','GET,PUT','/auth/setting',NULL,'2018-07-16 09:33:54'),(5,'权限管理','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,'2018-07-16 09:34:14'),(6,'用户管理','users','','/users*','2018-07-16 09:31:02','2018-07-16 09:31:02'),(7,'系统配置','ext.config','','/config*','2018-07-17 02:22:33','2018-07-17 05:52:01'),(8,'商品管理','products','','/products*','2018-07-17 05:47:36','2018-07-17 05:47:36'),(9,'订单管理','orders','','/orders*','2018-07-17 05:50:43','2018-07-17 05:50:43'),(10,'优惠券管理','coupon_codes','','/coupon_codes*','2018-07-17 05:51:16','2018-07-17 05:51:16');
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
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL),(2,10,NULL,NULL);
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
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2018-07-16 09:02:15','2018-07-16 09:02:15'),(2,'运营','operator','2018-07-16 09:33:24','2018-07-16 09:33:24');
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
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$KM3MIwuvM2swOCgq591.QOmgpkxPBajRyKOq0DBZcA0curqp/tvMW','Administrator',NULL,'lKeh579UAZHrH8sxal35akv0UapqeoJPTPxmsJsiHMutigNy4n9YJBTKRzVe','2018-07-16 09:02:15','2018-07-16 09:02:15'),(2,'operator','$2y$10$zmeB6Wp39sVzdZI9JLERZeFtDW1tj3AIN4Evks6EWZ0jLAJIedUeO','运营','images/9358d109b3de9c821370156d6081800a19d8433f.jpg','qKdoQNWkZPhnj9xWzYcqocitk5JmcCy90mUssb1GaR8lFXe6vFKPxPe9tdHW','2018-07-16 09:36:37','2018-07-16 09:40:07');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_config`
--

LOCK TABLES `admin_config` WRITE;
/*!40000 ALTER TABLE `admin_config` DISABLE KEYS */;
INSERT INTO `admin_config` VALUES (1,'next_bonus_time','never','never：从不\r\nalways：一直','2018-07-17 06:29:47','2018-07-17 06:29:47');
/*!40000 ALTER TABLE `admin_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-17  6:32:16
