-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 129.211.90.48    Database: al_peixun
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.16.04.1

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
-- Table structure for table `mrhu_access_log`
--

DROP TABLE IF EXISTS `mrhu_access_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mrhu_access_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `ip` varchar(255) NOT NULL COMMENT 'ip',
  `machine` varchar(255) NOT NULL COMMENT '机器',
  `browser` varchar(255) NOT NULL COMMENT '浏览器',
  `user_id` varchar(255) NOT NULL COMMENT '用户id',
  `action` varchar(255) NOT NULL COMMENT '模块',
  `model` varchar(255) NOT NULL COMMENT '方法',
  `page` varchar(255) NOT NULL COMMENT '页面',
  `page_url` varchar(255) NOT NULL COMMENT '页面url',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `channel_type` varchar(255) DEFAULT NULL COMMENT '渠道code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=237080 DEFAULT CHARSET=utf8 COMMENT='访问记录表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-30 12:31:36
