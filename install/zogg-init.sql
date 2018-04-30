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


# Dump of table Campus
# ------------------------------------------------------------

CREATE TABLE `Campus` (
  `CampusId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `CampusName` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`CampusId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Course
# ------------------------------------------------------------

CREATE TABLE `Course` (
  `CourseId` int(11) NOT NULL AUTO_INCREMENT,
  `CourseName` varchar(45) DEFAULT NULL,
  `CourseProgramId` int(11) DEFAULT NULL,
  `CourseYear` int(11) DEFAULT NULL,
  `CourseSemester` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`CourseId`),
  KEY `FK_Course_ProgramId` (`CourseProgramId`),
  KEY `CourseProgramId` (`CourseProgramId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table Current_Group
# ------------------------------------------------------------

CREATE TABLE `Current_Group` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `GroupId` int(11) unsigned NOT NULL,
  `GroupName` varchar(40) DEFAULT NULL,
  `CourseId` int(11) unsigned DEFAULT NULL,
  `CourseName` varchar(45) DEFAULT NULL,
  `ProgramId` int(11) NOT NULL,
  `ProgramName` varchar(80) DEFAULT NULL,
  `CampusId` int(11) NOT NULL,
  `CampusName` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table Group
# ------------------------------------------------------------

CREATE TABLE `Group` (
  `GroupId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(40) DEFAULT NULL,
  `GroupCourseId` int(11) DEFAULT NULL,
  PRIMARY KEY (`GroupId`),
  KEY `GroupCourseId` (`GroupCourseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Job
# ------------------------------------------------------------

CREATE TABLE `Job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `CourseId` int(11) DEFAULT NULL,
  `sortOrder` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table Program
# ------------------------------------------------------------

CREATE TABLE `Program` (
  `ProgramId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ProgramName` varchar(80) DEFAULT NULL,
  `ProgramCampusId` int(11) DEFAULT NULL,
  PRIMARY KEY (`ProgramId`),
  KEY `ProgramCampusId` (`ProgramCampusId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table sk_configuration
# ------------------------------------------------------------

CREATE TABLE `sk_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sk_pages
# ------------------------------------------------------------

CREATE TABLE `sk_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sk_permission_page_matches
# ------------------------------------------------------------

CREATE TABLE `sk_permission_page_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sk_permissions
# ------------------------------------------------------------

CREATE TABLE `sk_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sk_user_permission_matches
# ------------------------------------------------------------

CREATE TABLE `sk_user_permission_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sk_users
# ------------------------------------------------------------

CREATE TABLE `sk_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` int(11) NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `title` varchar(150) NOT NULL,
  `roleId` int(11) DEFAULT NULL,
  `campusId` int(11) DEFAULT NULL,
  `groupId` int(11) DEFAULT NULL,
  `teamId` int(11) DEFAULT NULL,
  `sign_up_stamp` int(11) NOT NULL,
  `last_sign_in_stamp` int(11) NOT NULL,
  `disciplineId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table Student
# ------------------------------------------------------------

CREATE TABLE `Student` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Genre` varchar(255) DEFAULT NULL,
  `NOM` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `StudentEmail` varchar(255) DEFAULT NULL,
  `StudentGroupId` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_Student_GroupId` (`StudentGroupId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table StudentTeam
# ------------------------------------------------------------

CREATE TABLE `StudentTeam` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IdStudent` int(11) DEFAULT NULL,
  `IdTeam` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `idx_StudentTeam_IdStudent_IdTeam` (`IdStudent`,`IdTeam`),
  KEY `SYS_FK_93` (`IdTeam`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table Team
# ------------------------------------------------------------

CREATE TABLE `Team` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TeamName` varchar(100) NOT NULL DEFAULT '',
  `groupId` int(11) NOT NULL,
  `JobId` int(11) NOT NULL,
  `Grade` float DEFAULT NULL,
  `Comment` text,
  PRIMARY KEY (`ID`),
  KEY `Team4Job_idx` (`JobId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
