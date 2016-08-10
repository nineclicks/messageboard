-- MySQL dump 10.13  Distrib 5.5.50, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: board
-- ------------------------------------------------------
-- Server version	5.5.50-0ubuntu0.14.04.1

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
-- Table structure for table `board`
--

DROP TABLE IF EXISTS `board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `board` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `boarddesc`
--

DROP TABLE IF EXISTS `boarddesc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boarddesc` (
  `id` int(10) unsigned NOT NULL,
  `text` varchar(10000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_boarddesc_1` FOREIGN KEY (`id`) REFERENCES `board` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `boardpost`
--

DROP TABLE IF EXISTS `boardpost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boardpost` (
  `boardid` int(10) unsigned NOT NULL,
  `postid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `title` varchar(300) NOT NULL,
  `img` varchar(8) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`boardid`,`postid`),
  KEY `fk_boardpost_1_idx` (`postid`),
  CONSTRAINT `fk_boardpost_1` FOREIGN KEY (`postid`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_boardpost_2` FOREIGN KEY (`boardid`) REFERENCES `board` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email` (
  `id` int(10) unsigned NOT NULL,
  `address` varchar(256) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  CONSTRAINT `fk_email_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) unsigned NOT NULL,
  `message` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password`
--

DROP TABLE IF EXISTS `password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password` (
  `id` int(11) unsigned NOT NULL,
  `hash` binary(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  CONSTRAINT `fk_password_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(16) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '1',
  `date` int(10) unsigned NOT NULL,
  `author` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent` int(10) unsigned DEFAULT NULL,
  `content` varchar(10000) DEFAULT NULL,
  `root` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `fk_post_1_idx` (`author`),
  KEY `fk_post_2_idx` (`parent`),
  CONSTRAINT `fk_post_1` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_2` FOREIGN KEY (`parent`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` binary(60) NOT NULL,
  `expiration` int(10) unsigned NOT NULL,
  `stay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `hash_UNIQUE` (`hash`),
  KEY `fk_session_1_idx` (`userid`),
  CONSTRAINT `fk_session_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verify`
--

DROP TABLE IF EXISTS `verify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` binary(60) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `hash_UNIQUE` (`hash`),
  CONSTRAINT `fk_verify_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote` (
  `userid` int(10) unsigned NOT NULL,
  `post` int(10) unsigned NOT NULL,
  `vote` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`userid`,`post`),
  KEY `fk_vote_2_idx` (`post`),
  CONSTRAINT `fk_vote_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vote_2` FOREIGN KEY (`post`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'board'
--
/*!50003 DROP PROCEDURE IF EXISTS `createuser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`board`@`localhost` PROCEDURE `createuser`(
IN name_in VARCHAR(20),
IN hash_in BINARY(60)
)
BEGIN
	INSERT INTO user(`name`, `date`)
	VALUES(name_in, UNIX_TIMESTAMP());

	INSERT INTO `password`(id, `hash`)
	VALUES(LAST_INSERT_ID(), hash_in);

	SELECT LAST_INSERT_ID() as id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getposts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`board`@`localhost` PROCEDURE `getposts`(
IN code_in VARCHAR(16),
IN uid_in INT(10) UNSIGNED,
IN mod_in TINYINT
)
BEGIN
SELECT id INTO @pid FROM post WHERE code = code_in;
SELECT code, (IF(status <> 2,content,'[deleted]')) as 'content', parentcode as 'parent', name, score, date, status
FROM (SELECT post.*, user.name, p2.code as 'parentcode' FROM post
	INNER JOIN user ON post.author = user.id
    LEFT JOIN post p2 ON post.parent = p2.id
	ORDER BY parent, id) post_sorted,
	(SELECT @pv := @pid) initialisation
WHERE ((find_in_set(parent, @pv) > 0
AND @pv := concat(@pv, ',', id))
OR (id = @pid))
-- only show post if it is approved or by the person requesting it
AND (status > 0 OR author = uid_in OR mod_in = 1);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `postreply` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`board`@`localhost` PROCEDURE `postreply`(
content_in VARCHAR(10000),
code_in VARCHAR(16),
parent_in VARCHAR(16),
date_in int(10),
author_in VARCHAR(20)
)
BEGIN
DECLARE parentid INT(10);
DECLARE userid INT(11);
DECLARE parentroot INT(10);
DECLARE usertype TINYINT(3);
DECLARE poststatus TINYINT(3);

SELECT id, root INTO parentid, parentroot FROM post WHERE code = parent_in;
SELECT id, status INTO userid, usertype FROM user WHERE name = author_in;

-- if parentroot is null then use parentid (parent is the root)
SET parentroot = IFNULL(parentroot, parentid);

-- Auto approve comment if user is mod/admin
SET poststatus = IF(usertype > 1, 1, 0);

INSERT INTO post(code, date, author, parent, content, root, status)
	VALUES (code_in, date_in, userid, parentid, content_in, parentroot, poststatus);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `verifytoken` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`board`@`localhost` PROCEDURE `verifytoken`(
IN hash_in BINARY(60)
)
BEGIN
	DECLARE id INT UNSIGNED;
	DECLARE name VARCHAR(20);
	DECLARE date INT UNSIGNED;
	DECLARE score INT;
	DECLARE status TINYINT UNSIGNED;
	DECLARE sid INT UNSIGNED;
	DECLARE stay TINYINT UNSIGNED;
	DECLARE address VARCHAR(256);

	SELECT u.id, u.name, u.date, u.score, u.status, s.id AS sid, s.stay, e.address
	INTO id, name, date, score, status, sid, stay, address
	FROM session s
	INNER JOIN user u ON u.id = s.userid
	LEFT JOIN email e ON e.id = u.id
	WHERE s.hash = hash_in
		AND s.expiration > UNIX_TIMESTAMP()
	LIMIT 1;

	IF stay = 0 THEN
		UPDATE session s
		SET s.expiration = UNIX_TIMESTAMP() + 3600
		WHERE s.id = sid;
	END IF;

	SELECT id, name, date, score, status, stay, address;

END ;;
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

-- Dump completed on 2016-08-10 19:32:37
