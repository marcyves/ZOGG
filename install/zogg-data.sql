# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table sk_configuration
# ------------------------------------------------------------

LOCK TABLES `sk_configuration` WRITE;
/*!40000 ALTER TABLE `sk_configuration` DISABLE KEYS */;

INSERT INTO `sk_configuration` (`id`, `name`, `value`)
VALUES
	(1,'website_name','This is Group Grading'),
	(2,'website_url',''),
	(3,'email','contact@xdm-consulting.com'),
	(4,'activation','false'),
	(5,'resend_activation_threshold','0'),
	(6,'language','models/languages/en.php'),
	(7,'template','models/site-templates/six.css'),
	(8,'website_description','Group Grading Made Easy'),
	(9,'platform','gr2me'),
	(10,'lang','en'),
	(11,'init_bank','500'),
	(12,'theme','default');

/*!40000 ALTER TABLE `sk_configuration` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sk_pages
# ------------------------------------------------------------

LOCK TABLES `sk_pages` WRITE;
/*!40000 ALTER TABLE `sk_pages` DISABLE KEYS */;

INSERT INTO `sk_pages` (`id`, `page`, `private`)
VALUES
	(1,'account.php',1),
	(2,'activate-account.php',0),
	(3,'admin_configuration.php',3),
	(4,'admin_page.php',3),
	(5,'admin_pages.php',3),
	(6,'admin_permission.php',3),
	(7,'admin_permissions.php',3),
	(8,'admin_user.php',3),
	(9,'admin_users.php',3),
	(10,'forgot-password.php',0),
	(11,'index.php',0),
	(14,'logout.php',1),
	(15,'register.php',0),
	(16,'resend-activation.php',0),
	(17,'user_settings.php',1),
	(18,'admin_init.php',0);

/*!40000 ALTER TABLE `sk_pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sk_permission_page_matches
# ------------------------------------------------------------

LOCK TABLES `sk_permission_page_matches` WRITE;
/*!40000 ALTER TABLE `sk_permission_page_matches` DISABLE KEYS */;

INSERT INTO `sk_permission_page_matches` (`id`, `permission_id`, `page_id`)
VALUES
	(1,1,1),
	(2,1,14),
	(3,1,17),
	(4,2,1),
	(5,2,3),
	(6,3,4),
	(7,3,5),
	(8,3,6),
	(9,3,7),
	(10,3,8),
	(11,3,9),
	(12,2,14),
	(13,2,17),
	(14,3,14),
	(15,3,1),
	(16,2,1),
	(17,1,16),
	(18,2,16);

/*!40000 ALTER TABLE `sk_permission_page_matches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sk_permissions
# ------------------------------------------------------------



# Dump of table sk_user_permission_matches
# ------------------------------------------------------------

LOCK TABLES `sk_user_permission_matches` WRITE;
/*!40000 ALTER TABLE `sk_user_permission_matches` DISABLE KEYS */;

INSERT INTO `sk_user_permission_matches` (`id`, `user_id`, `permission_id`)
VALUES
	(1,1,3),
	(2,2,1),
	(3,3,2);

/*!40000 ALTER TABLE `sk_user_permission_matches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sk_users
# ------------------------------------------------------------

LOCK TABLES `sk_users` WRITE;
/*!40000 ALTER TABLE `sk_users` DISABLE KEYS */;

INSERT INTO `sk_users` (`id`, `user_name`, `display_name`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `active`, `title`, `roleId`, `campusId`, `groupId`, `teamId`, `sign_up_stamp`, `last_sign_in_stamp`, `disciplineId`)
VALUES
	(1,'admin','Big Honcho','9051a509f95691159c7ed617fd884f29af9213d747b13b6c7860fff6fb40cb24d','admin@mail.com','7aced716cf2be504db66150bf0d0e0f0',1410376902,0,1,'New Member',1,3,NULL,999,1410376902,1496422608,NULL),
	(2,'student','John Doe','9051a509f95691159c7ed617fd884f29af9213d747b13b6c7860fff6fb40cb24d','student@mail.com','b3f4ed2c42cc370d457f9caa201617a8',1377894239,0,1,'Student',3,1,2,8,1377894239,1440599326,NULL),
	(3,'professor','Dr Prof','9051a509f95691159c7ed617fd884f29af9213d747b13b6c7860fff6fb40cb24d','professor@mail.com','b3f4ed2c42cc370d457f9caa201617a8',1377894239,0,1,'Professor',3,1,NULL,1,1377894239,1524936981,NULL);

/*!40000 ALTER TABLE `sk_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
