-- --------------------------------------------------------
-- Host:                           127.0.0.1
-- Server version:               5.5.53 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL:                  9.5.0.5355
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table thinksaas-dev.ts_anti_email
DROP TABLE IF EXISTS `ts_anti_email`;
CREATE TABLE IF NOT EXISTS `ts_anti_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT 'Email',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_anti_email: 0 rows
DELETE FROM `ts_anti_email`;
/*!40000 ALTER TABLE `ts_anti_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_email` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_ip
DROP TABLE IF EXISTS `ts_anti_ip`;
CREATE TABLE IF NOT EXISTS `ts_anti_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) NOT NULL DEFAULT '' COMMENT 'IP',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_anti_ip: 0 rows
DELETE FROM `ts_anti_ip`;
/*!40000 ALTER TABLE `ts_anti_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_ip` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_report
DROP TABLE IF EXISTS `ts_anti_report`;
CREATE TABLE IF NOT EXISTS `ts_anti_report` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(128) NOT NULL DEFAULT '',
  `content` varchar(512) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`reportid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_anti_report: 0 rows
DELETE FROM `ts_anti_report`;
/*!40000 ALTER TABLE `ts_anti_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_report` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_user
DROP TABLE IF EXISTS `ts_anti_user`;
CREATE TABLE IF NOT EXISTS `ts_anti_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_anti_user: 0 rows
DELETE FROM `ts_anti_user`;
/*!40000 ALTER TABLE `ts_anti_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_word
DROP TABLE IF EXISTS `ts_anti_word`;
CREATE TABLE IF NOT EXISTS `ts_anti_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(64) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=535 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_anti_word: 0 rows
DELETE FROM `ts_anti_word`;
/*!40000 ALTER TABLE `ts_anti_word` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_word` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article
DROP TABLE IF EXISTS `ts_article`;
CREATE TABLE IF NOT EXISTS `ts_article` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `locationid` int(11) NOT NULL DEFAULT '0',
  `cateid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `tags` varchar(128) NOT NULL DEFAULT '',
  `gaiyao` varchar(128) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '',
  `photo` char(32) NOT NULL DEFAULT '',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0',
  `count_comment` int(11) NOT NULL DEFAULT '0',
  `count_recommend` int(11) NOT NULL DEFAULT '0',
  `count_view` int(11) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`articleid`),
  UNIQUE KEY `title_2` (`title`),
  KEY `addtime` (`addtime`),
  KEY `cateid` (`cateid`),
  KEY `isrecommend` (`isrecommend`),
  KEY `count_recommend` (`count_recommend`,`addtime`),
  KEY `title` (`title`),
  KEY `count_view` (`count_view`),
  KEY `count_view_2` (`count_view`,`addtime`),
  KEY `locationid` (`locationid`),
  KEY `tags` (`tags`,`isaudit`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_article: 0 rows
DELETE FROM `ts_article`;
/*!40000 ALTER TABLE `ts_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_cate
DROP TABLE IF EXISTS `ts_article_cate`;
CREATE TABLE IF NOT EXISTS `ts_article_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT,
  `referid` int(11) NOT NULL DEFAULT '0',
  `catename` varchar(64) NOT NULL DEFAULT '',
  `orderid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_article_cate: 0 rows
DELETE FROM `ts_article_cate`;
/*!40000 ALTER TABLE `ts_article_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_cate` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_comment
DROP TABLE IF EXISTS `ts_article_comment`;
CREATE TABLE IF NOT EXISTS `ts_article_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_article_comment: 0 rows
DELETE FROM `ts_article_comment`;
/*!40000 ALTER TABLE `ts_article_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_options
DROP TABLE IF EXISTS `ts_article_options`;
CREATE TABLE IF NOT EXISTS `ts_article_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_article_options: 5 rows
DELETE FROM `ts_article_options`;
/*!40000 ALTER TABLE `ts_article_options` DISABLE KEYS */;
INSERT INTO `ts_article_options` (`optionname`, `optionvalue`) VALUES
	('appname', 'Статьи'),
	('appdesc', 'Статьи'),
	('appkey', 'Статьи'),
	('allowpost', '1'),
	('isaudit', '0');
/*!40000 ALTER TABLE `ts_article_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_recommend
DROP TABLE IF EXISTS `ts_article_recommend`;
CREATE TABLE IF NOT EXISTS `ts_article_recommend` (
  `articleid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `articleid` (`articleid`,`userid`),
  KEY `articleid_2` (`articleid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_article_recommend: 0 rows
DELETE FROM `ts_article_recommend`;
/*!40000 ALTER TABLE `ts_article_recommend` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_recommend` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_cache
DROP TABLE IF EXISTS `ts_cache`;
CREATE TABLE IF NOT EXISTS `ts_cache` (
  `cacheid` int(11) NOT NULL AUTO_INCREMENT,
  `cachename` varchar(64) NOT NULL DEFAULT '',
  `cachevalue` text NOT NULL,
  PRIMARY KEY (`cacheid`),
  UNIQUE KEY `cachename` (`cachename`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_cache: 19 rows
DELETE FROM `ts_cache`;
/*!40000 ALTER TABLE `ts_cache` DISABLE KEYS */;
INSERT INTO `ts_cache` (`cacheid`, `cachename`, `cachevalue`) VALUES
	(1, 'pubs_plugins', '1532102706a:16:{i:9;s:10:"floatlayer";i:19;s:8:"customer";i:20;s:7:"counter";i:21;s:6:"douban";i:22;s:8:"feedback";i:24;s:7:"gonggao";i:25;s:5:"gotop";i:26;s:4:"navs";i:27;s:2:"qq";i:29;s:5:"weibo";i:30;s:6:"wordad";i:31;s:9:"footertip";i:32;s:8:"leftuser";i:33;s:7:"ueditor";i:34;s:5:"gobad";i:35;s:10:"wangeditor";}'),
	(2, 'home_plugins', '1406904279a:13:{i:11;s:9:"newtopics";i:12;s:5:"slide";i:13;s:8:"signuser";i:14;s:14:"recommendgroup";i:15;s:3:"tag";i:16;s:8:"newtopic";i:17;s:5:"login";i:18;s:5:"weibo";i:19;s:8:"newgroup";i:20;s:7:"article";i:21;s:8:"hottopic";i:22;s:5:"photo";i:23;s:5:"links";}'),
	(3, 'system_options', '1540469862a:29:{s:10:"site_title";s:9:"ThinkSAAS RUS";s:13:"site_subtitle";s:24:"ThinkSAAS RUS";s:8:"site_key";s:9:"thinksaas";s:9:"site_desc";s:9:"thinksaas";s:8:"site_url";s:17:"http://127.0.0.1/";s:8:"link_url";s:17:"http://127.0.0.1/";s:9:"site_pkey";s:32:"1649f854581e9c03bc2c4e06023c5b99";s:10:"site_email";s:15:"admin@site.ru";s:8:"site_icp";s:20:"ICP00000000";s:6:"isface";s:1:"0";s:8:"isinvite";s:1:"0";s:8:"isverify";s:1:"0";s:6:"istomy";s:1:"0";s:10:"isauthcode";s:1:"0";s:7:"istoken";s:1:"0";s:6:"isgzip";s:1:"0";s:8:"timezone";s:14:"Europe/Moscow";s:7:"visitor";s:1:"0";s:9:"publisher";s:1:"0";s:11:"isallowedit";s:1:"0";s:13:"isallowdelete";s:1:"0";s:10:"site_theme";s:6:"sample";s:12:"site_urltype";s:1:"1";s:10:"photo_size";s:1:"2";s:10:"photo_type";s:16:"jpg,gif,png,jpeg";s:11:"attach_size";s:1:"2";s:11:"attach_type";s:19:"zip,rar,doc,txt,ppt";s:11:"dayscoretop";s:2:"10";s:4:"logo";s:8:"logo.png";}'),
	(4, 'system_appnav', '1532102633a:9:{s:4:"home";s:6:"Главная";s:5:"group";s:6:"Группы";s:7:"article";s:6:"Статьи";s:5:"photo";s:6:"Альбомы";s:5:"weibo";s:6:"Микроблог";s:4:"user";s:6:"Пользователи";s:6:"search";s:6:"Поиск";s:8:"location";s:6:"Расположение";s:2:"my";s:12:"Мой кабинет";}'),
	(5, 'system_anti_word', '1547640746s:0:"";'),
	(6, 'user_options', '1532102646a:6:{s:7:"appname";s:6:"Пользователи";s:7:"appdesc";s:12:"Кабинет пользователя";s:6:"appkey";s:6:"пользователи";s:8:"isenable";s:1:"0";s:7:"isgroup";s:0:"";s:7:"banuser";s:25:"пользователь|администратор";}'),
	(7, 'mail_options', '1532102620a:8:{s:7:"appname";s:6:"Почта";s:7:"appdesc";s:15:"ThinkSAAS RUS";s:8:"isenable";s:1:"0";s:8:"mailhost";s:18:"smtp.exmail.qq.com";s:3:"ssl";s:1:"1";s:8:"mailport";s:3:"587";s:8:"mailuser";s:23:"postmaster@site.ru";s:7:"mailpwd";s:0:"";}'),
	(8, 'article_options', '1532095494a:5:{s:7:"appname";s:6:"Статьи";s:7:"appdesc";s:6:"Статьи";s:6:"appkey";s:6:"Статьи";s:9:"allowpost";s:1:"1";s:7:"isaudit";s:1:"0";}'),
	(9, 'group_options', '1532102579a:9:{s:7:"appname";s:6:"Группы";s:7:"appdesc";s:15:"ThinkSAAS RUS";s:6:"appkey";s:6:"Группы";s:8:"iscreate";s:1:"0";s:7:"isaudit";s:1:"0";s:7:"joinnum";s:2:"20";s:11:"isallowpost";s:1:"0";s:13:"istopicattach";s:1:"0";s:9:"ispayjoin";s:1:"0";}'),
	(10, 'photo_options', '1532102633a:4:{s:7:"appname";s:6:"Альбомы";s:7:"appdesc";s:6:"Альбомы";s:6:"appkey";s:6:"Альбомы";s:7:"isaudit";s:1:"0";}'),
	(11, 'weibo_options', '1532102652a:3:{s:7:"appname";s:6:"Микроблог";s:7:"appdesc";s:6:"Микроблог";s:6:"appkey";s:6:"Микроблог";}'),
	(12, 'plugins_pubs_wordad', '1400602928a:4:{i:0;a:2:{s:5:"title";s:22:"ThinkSAAS RUS 1";s:3:"url";s:23:"https://www.thinksaas.cn";}i:1;a:2:{s:5:"title";s:22:"ThinkSAAS RUS 2";s:3:"url";s:23:"https://www.thinksaas.cn";}i:2;a:2:{s:5:"title";s:22:"ThinkSAAS RUS 3";s:3:"url";s:23:"https://www.thinksaas.cn";}i:3;a:2:{s:5:"title";s:22:"ThinkSAAS RUS 4";s:3:"url";s:23:"https://www.thinksaas.cn";}}'),
	(13, 'user_role', '1547451683a:8:{i:0;a:3:{s:8:"rolename";s:16:"читатель";s:11:"score_start";s:1:"0";s:9:"score_end";s:4:"5000";}i:1;a:3:{s:8:"rolename";s:24:"пользователь";s:11:"score_start";s:4:"5000";s:9:"score_end";s:5:"20000";}i:2;a:3:{s:8:"rolename";s:18:"постоялец";s:11:"score_start";s:5:"20000";s:9:"score_end";s:5:"40000";}i:3;a:3:{s:8:"rolename";s:16:"старожил";s:11:"score_start";s:5:"40000";s:9:"score_end";s:5:"80000";}i:4;a:3:{s:8:"rolename";s:12:"мастер";s:11:"score_start";s:5:"80000";s:9:"score_end";s:6:"160000";}i:5;a:3:{s:8:"rolename";s:10:"профи";s:11:"score_start";s:6:"160000";s:9:"score_end";s:6:"320000";}i:6;a:3:{s:8:"rolename";s:16:"писатель";s:11:"score_start";s:6:"320000";s:9:"score_end";s:6:"640000";}i:7;a:3:{s:8:"rolename";s:23:"гигант мысли";s:11:"score_start";s:6:"640000";s:9:"score_end";s:7:"1280000";}}'),
	(14, 'plugins_pubs_gobad', '1547548352a:10:{s:10:"pub_header";s:0:"";i:300;s:50:"Ширина рекламного места 300px";i:468;s:50:"Ширина рекламного места 468px";i:640;s:0:"";i:960;s:50:"Ширина рекламного места 960px";s:15:"topic_right_top";s:0:"";s:11:"home_left_1";s:51:"Объявление на главной слева";s:11:"content_top";s:0:"";s:11:"home_left_2";s:0:"";s:10:"pub_footer";s:0:"";}'),
	(15, 'plugins_pubs_feedback', '1406109222s:52:"<a href=\\"https://www.thinksaas.cn\\">П О М О Щ Ь</a>";'),
	(16, 'plugins_pubs_counter', '1540470205s:113:"<!--Код счетчика-->";'),
	(18, 'plugins_home_links', '1540469938a:2:{i:0;a:2:{s:8:"linkname";s:9:"ThinkSAAS RUS";s:7:"linkurl";s:25:"https://www.thinksaas.cn/";}i:1;a:2:{s:8:"linkname";s:12:"ThinkSAAS RUS";s:7:"linkurl";s:25:"https://www.thinksaas.cn/";}}'),
	(17, 'plugins_pubs_navs', '1532088590a:2:{i:0;a:2:{s:7:"navname";s:15:"ThinkSAAS RUS";s:6:"navurl";s:25:"https://www.thinksaas.cn/";}i:1;a:2:{s:7:"navname";s:21:"ThinkSAAS RUS";s:6:"navurl";s:38:"https://www.thinksaas.cn/service/down/";}}'),
	(24, 'system_mynav', '1531722577a:5:{s:5:"group";s:6:"Группы";s:7:"article";s:6:"Статьи";s:5:"weibo";s:6:"Микроблог";s:5:"photo";s:6:"Альбомы";s:8:"location";s:6:"Локация";}');
/*!40000 ALTER TABLE `ts_cache` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_editor
DROP TABLE IF EXISTS `ts_editor`;
CREATE TABLE IF NOT EXISTS `ts_editor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `type` char(32) NOT NULL DEFAULT 'photo',
  `title` varchar(64) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '',
  `url` char(32) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_editor: 0 rows
DELETE FROM `ts_editor`;
/*!40000 ALTER TABLE `ts_editor` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_editor` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group
DROP TABLE IF EXISTS `ts_group`;
CREATE TABLE IF NOT EXISTS `ts_group` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `cateid` int(11) NOT NULL DEFAULT '0',
  `cateid2` int(11) NOT NULL DEFAULT '0',
  `cateid3` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `groupname` varchar(32) NOT NULL DEFAULT '',
  `groupdesc` text NOT NULL,
  `path` char(32) NOT NULL DEFAULT '',
  `photo` char(32) NOT NULL DEFAULT '',
  `bgphoto` char(32) NOT NULL DEFAULT '',
  `count_topic` int(11) NOT NULL DEFAULT '0',
  `count_topic_today` int(11) NOT NULL DEFAULT '0',
  `count_user` int(11) NOT NULL DEFAULT '0',
  `count_topic_audit` int(11) NOT NULL DEFAULT '0',
  `joinway` tinyint(1) NOT NULL DEFAULT '0',
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `role_leader` char(32) NOT NULL DEFAULT 'Главный',
  `role_admin` char(32) NOT NULL DEFAULT 'Админ',
  `role_user` char(32) NOT NULL DEFAULT 'Участник',
  `addtime` int(11) DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0',
  `isopen` tinyint(1) NOT NULL DEFAULT '0',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0',
  `ispost` tinyint(1) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '0',
  `ispostaudit` tinyint(1) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`),
  KEY `userid` (`userid`),
  KEY `isshow` (`isshow`),
  KEY `groupname` (`groupname`),
  KEY `cateid` (`cateid`),
  KEY `isaudit` (`isaudit`),
  KEY `addtime` (`addtime`),
  KEY `isrecommend` (`isrecommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group: 0 rows
DELETE FROM `ts_group`;
/*!40000 ALTER TABLE `ts_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_album
DROP TABLE IF EXISTS `ts_group_album`;
CREATE TABLE IF NOT EXISTS `ts_group_album` (
  `albumid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `albumname` varchar(64) NOT NULL DEFAULT '',
  `albumdesc` text NOT NULL,
  `count_topic` int(11) NOT NULL DEFAULT '0',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`albumid`),
  KEY `userid` (`userid`),
  KEY `count_topic` (`count_topic`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_album: 0 rows
DELETE FROM `ts_group_album`;
/*!40000 ALTER TABLE `ts_group_album` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_album` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_album_topic
DROP TABLE IF EXISTS `ts_group_album_topic`;
CREATE TABLE IF NOT EXISTS `ts_group_album_topic` (
  `albumid` int(11) NOT NULL DEFAULT '0',
  `topicid` int(11) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  UNIQUE KEY `albumid_2` (`albumid`,`topicid`),
  KEY `albumid` (`albumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_album_topic: 0 rows
DELETE FROM `ts_group_album_topic`;
/*!40000 ALTER TABLE `ts_group_album_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_album_topic` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_cate
DROP TABLE IF EXISTS `ts_group_cate`;
CREATE TABLE IF NOT EXISTS `ts_group_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT,
  `catename` varchar(64) NOT NULL DEFAULT '',
  `referid` int(11) NOT NULL DEFAULT '0',
  `count_group` int(11) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cateid`),
  KEY `referid` (`referid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_cate: 0 rows
DELETE FROM `ts_group_cate`;
/*!40000 ALTER TABLE `ts_group_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_cate` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_options
DROP TABLE IF EXISTS `ts_group_options`;
CREATE TABLE IF NOT EXISTS `ts_group_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_options: 9 rows
DELETE FROM `ts_group_options`;
/*!40000 ALTER TABLE `ts_group_options` DISABLE KEYS */;
INSERT INTO `ts_group_options` (`optionname`, `optionvalue`) VALUES
	('appname', 'Группы'),
	('appdesc', 'Группы'),
	('appkey', 'Группы'),
	('iscreate', '0'),
	('isaudit', '0'),
	('joinnum', '20'),
	('isallowpost', '0'),
	('istopicattach', '0'),
	('ispayjoin', '0');
/*!40000 ALTER TABLE `ts_group_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic
DROP TABLE IF EXISTS `ts_group_topic`;
CREATE TABLE IF NOT EXISTS `ts_group_topic` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `locationid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `label` varchar(64) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `count_comment` int(11) NOT NULL DEFAULT '0',
  `count_view` int(11) NOT NULL DEFAULT '0',
  `count_love` int(11) NOT NULL DEFAULT '0',
  `istop` tinyint(1) NOT NULL DEFAULT '0',
  `isclose` int(4) NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0',
  `iscommentshow` tinyint(1) NOT NULL DEFAULT '0',
  `isposts` tinyint(1) NOT NULL DEFAULT '0',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0',
  `isdelete` tinyint(1) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topicid`),
  KEY `groupid` (`groupid`),
  KEY `userid` (`userid`),
  KEY `title` (`title`),
  KEY `groupid_2` (`groupid`),
  KEY `typeid` (`typeid`),
  KEY `addtime` (`addtime`),
  KEY `count_comment` (`count_comment`),
  KEY `count_view` (`count_view`),
  KEY `count_love` (`count_love`),
  KEY `count_view_2` (`count_view`,`addtime`),
  KEY `isshow` (`isaudit`,`uptime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_topic: 0 rows
DELETE FROM `ts_group_topic`;
/*!40000 ALTER TABLE `ts_group_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_collect
DROP TABLE IF EXISTS `ts_group_topic_collect`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_collect` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `topicid` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid_2` (`userid`,`topicid`),
  KEY `userid` (`userid`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_topic_collect: 0 rows
DELETE FROM `ts_group_topic_collect`;
/*!40000 ALTER TABLE `ts_group_topic_collect` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_collect` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_comment
DROP TABLE IF EXISTS `ts_group_topic_comment`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `referid` int(11) NOT NULL DEFAULT '0',
  `topicid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `ispublic` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`),
  KEY `referid` (`referid`,`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_topic_comment: 0 rows
DELETE FROM `ts_group_topic_comment`;
/*!40000 ALTER TABLE `ts_group_topic_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_edit
DROP TABLE IF EXISTS `ts_group_topic_edit`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_edit` (
  `editid` int(11) NOT NULL AUTO_INCREMENT,
  `topicid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(128) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `isupdate` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`editid`),
  UNIQUE KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_topic_edit: 0 rows
DELETE FROM `ts_group_topic_edit`;
/*!40000 ALTER TABLE `ts_group_topic_edit` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_edit` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_type
DROP TABLE IF EXISTS `ts_group_topic_type`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL DEFAULT '0',
  `typename` varchar(64) NOT NULL DEFAULT '',
  `count_topic` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_topic_type: 0 rows
DELETE FROM `ts_group_topic_type`;
/*!40000 ALTER TABLE `ts_group_topic_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_type` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_user
DROP TABLE IF EXISTS `ts_group_user`;
CREATE TABLE IF NOT EXISTS `ts_group_user` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `isadmin` int(11) NOT NULL DEFAULT '0',
  `isfounder` tinyint(1) NOT NULL DEFAULT '0',
  `endtime` date NOT NULL DEFAULT '1970-01-01',
  `addtime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid_2` (`userid`,`groupid`),
  KEY `userid` (`userid`),
  KEY `groupid` (`groupid`),
  KEY `groupid_2` (`groupid`,`isadmin`,`isfounder`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_user: 0 rows
DELETE FROM `ts_group_user`;
/*!40000 ALTER TABLE `ts_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_user_isaudit
DROP TABLE IF EXISTS `ts_group_user_isaudit`;
CREATE TABLE IF NOT EXISTS `ts_group_user_isaudit` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid` (`userid`,`groupid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_group_user_isaudit: 0 rows
DELETE FROM `ts_group_user_isaudit`;
/*!40000 ALTER TABLE `ts_group_user_isaudit` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_user_isaudit` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_home_info
DROP TABLE IF EXISTS `ts_home_info`;
CREATE TABLE IF NOT EXISTS `ts_home_info` (
  `infoid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`infoid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_home_info: 5 rows
DELETE FROM `ts_home_info`;
/*!40000 ALTER TABLE `ts_home_info` DISABLE KEYS */;
INSERT INTO `ts_home_info` (`infoid`, `orderid`, `title`, `content`) VALUES
	(1, 0, 'О нас', '\n&lt;p&gt;О нас&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;\n'),
	(2, 0, 'Свяжитесь с нами', '\n&lt;p&gt;Свяжитесь с нами&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;\n'),
	(3, 0, 'Пользовательские условия', '\n&lt;p&gt;Это соглашение распространяется на все версии программ и коды, выпущенные ThinkSAAS, далее все версии будут выполняться в соответствии с последними опубликованными Условиями пользователя.&lt;br&gt;1. Официально ThinkSAAS относится к сообществу thinksaas.cn и разработчику системы Цю Юнь.&lt;br&gt;2. ThinkSAAS запрещает пользователям нарушать какие-либо юридические положения в рамках китайского законодательства.&lt;br&gt;3. ThinkSAAS и ее основатель Цю Юнь владеют собственностью ThinkSAAS, и никакое физическое лицо, компания или организация не может нарушать авторские права и авторские права ThinkSAAS в любой форме или для любой цели.&lt;br&gt;4. ThinkSAAS официально владеет абсолютным авторским правом и авторскими правами на программное обеспечение сообщества ThinkSAAS.&lt;br&gt;5. Программный код ThinkSAAS является полностью открытым исходным кодом и не закодирован. ThinkSAAS позволяет [самостоятельно] пользователям выполнять вторичную разработку программного кода, но необходимо соблюдать положения пунктов 6, 7, 8 и 9 настоящих Условий.&lt;br&gt;6. Все пользователи ThinkSAAS могут использовать ThinkSAAS бесплатно, если они сохраняют нижнюю текстовую ссылку или логотип Powered by ThinkSAAS.&lt;br&gt;7. Пользователи могут удалить нижнюю текстовую ссылку или логотип Powered by ThinkSAAS после приобретения коммерческой лицензии ThinkSAAS.&lt;br&gt;8. ThinkSAAS не контролирует информацию о веб-сайте пользователей, но имеет право знать email пользователя или другую контактную информацию, а также имеет право использовать веб-сайт пользователя в качестве демонстрации ThinkSAAS.&lt;br&gt;9. Без официального письменного разрешения ThinkSAAS ни одно физическое лицо, компания или организация не могут в одностороннем порядке публиковать и продавать любое программное обеспечение или продукты, разработанные на основе ThinkSAAS, за исключением [самостоятельной работы], в противном случае это будет рассматриваться как нарушение. Расследовать его юридическую ответственность будут в соответствии с законами КНР&lt;br&gt;10. Организации, и компании должны использовать программное обеспечение ThinkSAAS после приобретения коммерческого лицензионного соглашения ThinkSAAS.&lt;br&gt;11. ThinkSAAS пересматривает и постоянно улучшает это соглашение.&lt;br&gt;&lt;br&gt;[Собственная разработка] Объяснение: пользователь использует ThinkSAAS, но не продает его разработки на основе ThinkSAAS, а использует только как сайт для самостоятельного обучения и ведения бизнеса.&lt;br&gt;&lt;br&gt;[Пользовательские условия] URL: https://www.thinksaas.cn/home/info/key/agreement/&lt;br&gt;[Официальный сайт] URL: https://www.thinksaas.cn/&lt;br&gt;[Демо-сайт] URL: https://demo.thinksaas.cn/&lt;/p&gt;\n'),
	(4, 0, 'Конфиденциальность', '\n&lt;p&gt;Конфиденциальность&lt;/p&gt;\n'),
	(5, 0, 'Присоединиться', '\n&lt;p&gt;Присоединиться&lt;/p&gt;\n');
/*!40000 ALTER TABLE `ts_home_info` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_location
DROP TABLE IF EXISTS `ts_location`;
CREATE TABLE IF NOT EXISTS `ts_location` (
  `locationid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `path` char(32) NOT NULL DEFAULT '',
  `photo` char(32) NOT NULL DEFAULT '',
  `orderid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`locationid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_location: 0 rows
DELETE FROM `ts_location`;
/*!40000 ALTER TABLE `ts_location` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_location` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_mail_options
DROP TABLE IF EXISTS `ts_mail_options`;
CREATE TABLE IF NOT EXISTS `ts_mail_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT,
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_mail_options: 8 rows
DELETE FROM `ts_mail_options`;
/*!40000 ALTER TABLE `ts_mail_options` DISABLE KEYS */;
INSERT INTO `ts_mail_options` (`optionid`, `optionname`, `optionvalue`) VALUES
	(1, 'appname', 'Почта'),
	(2, 'appdesc', 'Почта'),
	(3, 'isenable', '0'),
	(4, 'mailhost', 'smtp.yandex.ru'),
	(5, 'ssl', '1'),
	(6, 'mailport', '587'),
	(7, 'mailuser', 'postmaster@yandex.ru'),
	(8, 'mailpwd', '');
/*!40000 ALTER TABLE `ts_mail_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_message
DROP TABLE IF EXISTS `ts_message`;
CREATE TABLE IF NOT EXISTS `ts_message` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `touserid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `tourl` varchar(255) NOT NULL DEFAULT '',
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageid`),
  KEY `touserid` (`touserid`,`isread`),
  KEY `userid` (`userid`,`touserid`,`isread`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_message: 0 rows
DELETE FROM `ts_message`;
/*!40000 ALTER TABLE `ts_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_message` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo
DROP TABLE IF EXISTS `ts_photo`;
CREATE TABLE IF NOT EXISTS `ts_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT,
  `albumid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `locationid` int(11) NOT NULL DEFAULT '0',
  `photoname` varchar(64) NOT NULL DEFAULT '',
  `phototype` char(32) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '',
  `photourl` varchar(64) NOT NULL DEFAULT '',
  `photosize` char(32) NOT NULL DEFAULT '',
  `photodesc` char(120) NOT NULL DEFAULT '',
  `count_view` int(11) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_photo: 0 rows
DELETE FROM `ts_photo`;
/*!40000 ALTER TABLE `ts_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo_album
DROP TABLE IF EXISTS `ts_photo_album`;
CREATE TABLE IF NOT EXISTS `ts_photo_album` (
  `albumid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `path` char(32) NOT NULL DEFAULT '',
  `albumface` varchar(64) NOT NULL DEFAULT '',
  `albumname` varchar(64) NOT NULL DEFAULT '',
  `albumdesc` varchar(512) NOT NULL DEFAULT '',
  `count_photo` int(11) NOT NULL DEFAULT '0',
  `count_view` int(11) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `uptime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`albumid`),
  KEY `userid` (`userid`),
  KEY `isrecommend` (`isrecommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_photo_album: 0 rows
DELETE FROM `ts_photo_album`;
/*!40000 ALTER TABLE `ts_photo_album` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo_album` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo_comment
DROP TABLE IF EXISTS `ts_photo_comment`;
CREATE TABLE IF NOT EXISTS `ts_photo_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `referid` int(11) NOT NULL DEFAULT '0',
  `photoid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `content` varchar(512) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`),
  KEY `userid` (`userid`),
  KEY `referid` (`referid`,`photoid`),
  KEY `photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_photo_comment: 0 rows
DELETE FROM `ts_photo_comment`;
/*!40000 ALTER TABLE `ts_photo_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo_options
DROP TABLE IF EXISTS `ts_photo_options`;
CREATE TABLE IF NOT EXISTS `ts_photo_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT,
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_photo_options: 4 rows
DELETE FROM `ts_photo_options`;
/*!40000 ALTER TABLE `ts_photo_options` DISABLE KEYS */;
INSERT INTO `ts_photo_options` (`optionid`, `optionname`, `optionvalue`) VALUES
	(1, 'appname', 'Альбомы'),
	(2, 'appdesc', 'Альбомы'),
	(3, 'appkey', 'Альбомы'),
	(4, 'isaudit', '0');
/*!40000 ALTER TABLE `ts_photo_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_session
DROP TABLE IF EXISTS `ts_session`;
CREATE TABLE IF NOT EXISTS `ts_session` (
  `session` varchar(64) NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL DEFAULT '0',
  `session_expires` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` char(32) NOT NULL DEFAULT '' COMMENT 'IP',
  `session_data` varchar(512) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `session` (`session`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_session: 0 rows
DELETE FROM `ts_session`;
/*!40000 ALTER TABLE `ts_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_session` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_slide
DROP TABLE IF EXISTS `ts_slide`;
CREATE TABLE IF NOT EXISTS `ts_slide` (
  `slideid` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `info` varchar(128) NOT NULL DEFAULT '',
  `url` varchar(64) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '',
  `photo` char(32) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`slideid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_slide: 1 rows
DELETE FROM `ts_slide`;
/*!40000 ALTER TABLE `ts_slide` DISABLE KEYS */;
INSERT INTO `ts_slide` (`slideid`, `typeid`, `title`, `info`, `url`, `path`, `photo`, `addtime`) VALUES
	(1, 0, 'ThinkSAAS RUS', 'социальная сеть по интересам', 'https://www.thinksaas.cn', '0/0', '0/0/1.jpg', 1416533676);
/*!40000 ALTER TABLE `ts_slide` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_system_options
DROP TABLE IF EXISTS `ts_system_options`;
CREATE TABLE IF NOT EXISTS `ts_system_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_system_options: 29 rows
DELETE FROM `ts_system_options`;
/*!40000 ALTER TABLE `ts_system_options` DISABLE KEYS */;
INSERT INTO `ts_system_options` (`optionname`, `optionvalue`) VALUES
	('site_title', 'ThinkSAAS RUS'),
	('site_subtitle', 'социальная сеть'),
	('site_key', 'thinksaas'),
	('site_desc', 'thinksaas'),
	('site_url', 'http://dev.thinksaas.cn/'),
	('link_url', 'http://dev.thinksaas.cn/'),
	('site_pkey', '1282aeb75058638dae3e1565f7c1f51a'),
	('site_email', 'admin@site.ru'),
	('site_icp', 'ICP00000000'),
	('isface', '0'),
	('isinvite', '0'),
	('isverify', '0'),
	('istomy', '0'),
	('isauthcode', '0'),
	('istoken', '0'),
	('isgzip', '0'),
	('timezone', 'Europe/Moscow'),
	('visitor', '0'),
	('publisher', '0'),
	('isallowedit', '0'),
	('isallowdelete', '0'),
	('site_theme', 'sample'),
	('site_urltype', '1'),
	('photo_size', '2'),
	('photo_type', 'jpg,gif,png,jpeg'),
	('attach_size', '2'),
	('attach_type', 'zip,rar,doc,txt,ppt'),
	('dayscoretop', '10'),
	('logo', 'logo.png');
/*!40000 ALTER TABLE `ts_system_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag
DROP TABLE IF EXISTS `ts_tag`;
CREATE TABLE IF NOT EXISTS `ts_tag` (
  `tagid` int(11) NOT NULL AUTO_INCREMENT,
  `tagname` varchar(32) NOT NULL DEFAULT '',
  `count_user` int(11) NOT NULL DEFAULT '0',
  `count_group` int(11) NOT NULL DEFAULT '0',
  `count_topic` int(11) NOT NULL DEFAULT '0',
  `count_article` int(11) NOT NULL DEFAULT '0',
  `count_photo` int(11) NOT NULL DEFAULT '0',
  `isenable` tinyint(1) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_tag: 0 rows
DELETE FROM `ts_tag`;
/*!40000 ALTER TABLE `ts_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_article_index
DROP TABLE IF EXISTS `ts_tag_article_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_article_index` (
  `articleid` int(11) NOT NULL DEFAULT '0',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `articleid_2` (`articleid`,`tagid`),
  KEY `articleid` (`articleid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_tag_article_index: 0 rows
DELETE FROM `ts_tag_article_index`;
/*!40000 ALTER TABLE `ts_tag_article_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_article_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_group_index
DROP TABLE IF EXISTS `ts_tag_group_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_group_index` (
  `groupid` int(11) NOT NULL DEFAULT '0',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `groupid_2` (`groupid`,`tagid`),
  KEY `groupid` (`groupid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_tag_group_index: 0 rows
DELETE FROM `ts_tag_group_index`;
/*!40000 ALTER TABLE `ts_tag_group_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_group_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_photo_index
DROP TABLE IF EXISTS `ts_tag_photo_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_photo_index` (
  `photoid` int(11) NOT NULL DEFAULT '0',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `photoid_2` (`photoid`,`tagid`),
  KEY `tagid` (`tagid`),
  KEY `photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_tag_photo_index: 0 rows
DELETE FROM `ts_tag_photo_index`;
/*!40000 ALTER TABLE `ts_tag_photo_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_photo_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_topic_index
DROP TABLE IF EXISTS `ts_tag_topic_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_topic_index` (
  `topicid` int(11) NOT NULL DEFAULT '0',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `topicid_2` (`topicid`,`tagid`),
  KEY `topicid` (`topicid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_tag_topic_index: 0 rows
DELETE FROM `ts_tag_topic_index`;
/*!40000 ALTER TABLE `ts_tag_topic_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_topic_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_user_index
DROP TABLE IF EXISTS `ts_tag_user_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_user_index` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid_2` (`userid`,`tagid`),
  KEY `userid` (`userid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_tag_user_index: 0 rows
DELETE FROM `ts_tag_user_index`;
/*!40000 ALTER TABLE `ts_tag_user_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_user_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_task
DROP TABLE IF EXISTS `ts_task`;
CREATE TABLE IF NOT EXISTS `ts_task` (
  `taskid` int(11) NOT NULL AUTO_INCREMENT,
  `taskkey` char(32) NOT NULL DEFAULT '',
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`taskid`),
  UNIQUE KEY `taskkey` (`taskkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_task: 0 rows
DELETE FROM `ts_task`;
/*!40000 ALTER TABLE `ts_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_task` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_task_user
DROP TABLE IF EXISTS `ts_task_user`;
CREATE TABLE IF NOT EXISTS `ts_task_user` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `taskkey` char(32) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  UNIQUE KEY `userid` (`userid`,`taskkey`),
  KEY `taskkey` (`taskkey`),
  KEY `userid_2` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_task_user: 0 rows
DELETE FROM `ts_task_user`;
/*!40000 ALTER TABLE `ts_task_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_task_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user
DROP TABLE IF EXISTS `ts_user`;
CREATE TABLE IF NOT EXISTS `ts_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `pwd` char(32) NOT NULL DEFAULT '',
  `salt` char(32) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `resetpwd` char(32) NOT NULL DEFAULT '',
  `code` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pwd` (`pwd`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user: 0 rows
DELETE FROM `ts_user`;
/*!40000 ALTER TABLE `ts_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_follow
DROP TABLE IF EXISTS `ts_user_follow`;
CREATE TABLE IF NOT EXISTS `ts_user_follow` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `userid_follow` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid_2` (`userid`,`userid_follow`),
  KEY `userid` (`userid`),
  KEY `userid_follow` (`userid_follow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_follow: 0 rows
DELETE FROM `ts_user_follow`;
/*!40000 ALTER TABLE `ts_user_follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_follow` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_gb
DROP TABLE IF EXISTS `ts_user_gb`;
CREATE TABLE IF NOT EXISTS `ts_user_gb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `touserid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `touserid` (`touserid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_gb: 0 rows
DELETE FROM `ts_user_gb`;
/*!40000 ALTER TABLE `ts_user_gb` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_gb` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_group
DROP TABLE IF EXISTS `ts_user_group`;
CREATE TABLE IF NOT EXISTS `ts_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` char(32) NOT NULL DEFAULT '',
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `create` tinyint(1) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_group: 0 rows
DELETE FROM `ts_user_group`;
/*!40000 ALTER TABLE `ts_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_group` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_info
DROP TABLE IF EXISTS `ts_user_info`;
CREATE TABLE IF NOT EXISTS `ts_user_info` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `locationid` int(11) NOT NULL DEFAULT '0',
  `fuserid` int(11) NOT NULL DEFAULT '0',
  `username` char(32) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `sex` char(32) NOT NULL DEFAULT 'не указан',
  `phone` char(16) NOT NULL DEFAULT '',
  `roleid` int(11) NOT NULL DEFAULT '1',
  `province` varchar(64) NOT NULL DEFAULT '',
  `city` varchar(64) NOT NULL DEFAULT '',
  `district` varchar(64) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '',
  `face` char(64) NOT NULL DEFAULT '',
  `signed` varchar(64) NOT NULL DEFAULT '',
  `blog` char(32) NOT NULL DEFAULT '',
  `about` varchar(255) NOT NULL DEFAULT '',
  `ip` char(32) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `comefrom` tinyint(1) NOT NULL DEFAULT '0',
  `allscore` int(11) NOT NULL DEFAULT '0',
  `count_score` int(11) NOT NULL DEFAULT '0',
  `count_follow` int(11) NOT NULL DEFAULT '0',
  `count_followed` int(11) NOT NULL DEFAULT '0',
  `count_group` int(11) NOT NULL DEFAULT '0',
  `count_topic` int(11) NOT NULL DEFAULT '0',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `isenable` tinyint(1) NOT NULL DEFAULT '0',
  `isverify` tinyint(1) NOT NULL DEFAULT '0',
  `isrenzheng` tinyint(1) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0',
  `verifycode` char(11) NOT NULL DEFAULT '',
  `autologin` char(128) NOT NULL DEFAULT '',
  `signin` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `email_2` (`email`,`autologin`),
  KEY `fuserid` (`fuserid`),
  KEY `isrecommend` (`isrecommend`),
  KEY `isrenzheng` (`isrenzheng`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_info: 0 rows
DELETE FROM `ts_user_info`;
/*!40000 ALTER TABLE `ts_user_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_info` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_invites
DROP TABLE IF EXISTS `ts_user_invites`;
CREATE TABLE IF NOT EXISTS `ts_user_invites` (
  `inviteid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `invitecode` char(32) NOT NULL DEFAULT '',
  `isused` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`inviteid`),
  UNIQUE KEY `invitecode` (`invitecode`),
  KEY `isused` (`isused`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_invites: 0 rows
DELETE FROM `ts_user_invites`;
/*!40000 ALTER TABLE `ts_user_invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_invites` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_open
DROP TABLE IF EXISTS `ts_user_open`;
CREATE TABLE IF NOT EXISTS `ts_user_open` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `sitename` varchar(64) NOT NULL DEFAULT '',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `access_token` varchar(128) NOT NULL DEFAULT '',
  `uptime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid_2` (`userid`,`sitename`),
  UNIQUE KEY `sitename` (`sitename`,`openid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_open: 0 rows
DELETE FROM `ts_user_open`;
/*!40000 ALTER TABLE `ts_user_open` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_open` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_options
DROP TABLE IF EXISTS `ts_user_options`;
CREATE TABLE IF NOT EXISTS `ts_user_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_options: 6 rows
DELETE FROM `ts_user_options`;
/*!40000 ALTER TABLE `ts_user_options` DISABLE KEYS */;
INSERT INTO `ts_user_options` (`optionname`, `optionvalue`) VALUES
	('appname', 'Пользователи'),
	('appdesc', 'Пользователи'),
	('appkey', 'Пользователи'),
	('isenable', '0'),
	('isgroup', ''),
	('banuser', 'пользователь|администратор');
/*!40000 ALTER TABLE `ts_user_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_role
DROP TABLE IF EXISTS `ts_user_role`;
CREATE TABLE IF NOT EXISTS `ts_user_role` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` char(32) NOT NULL DEFAULT '',
  `score_start` int(11) NOT NULL DEFAULT '0',
  `score_end` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_role: 17 rows
DELETE FROM `ts_user_role`;
/*!40000 ALTER TABLE `ts_user_role` DISABLE KEYS */;
INSERT INTO `ts_user_role` (`roleid`, `rolename`, `score_start`, `score_end`) VALUES
(1, 'читатель', 0, 5000),
(2, 'пользователь', 5000, 20000),
(3, 'постоялец', 20000, 40000),
(4, 'старожил', 40000, 80000),
(5, 'мастер', 80000, 160000),
(6, 'профи', 160000, 320000),
(7, 'писатель', 320000, 640000),
(8, 'гигант мысли', 640000, 1280000);
/*!40000 ALTER TABLE `ts_user_role` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_score
DROP TABLE IF EXISTS `ts_user_score`;
CREATE TABLE IF NOT EXISTS `ts_user_score` (
  `scoreid` int(11) NOT NULL AUTO_INCREMENT,
  `scorekey` varchar(64) NOT NULL DEFAULT '',
  `scorename` varchar(64) NOT NULL DEFAULT '',
  `app` char(32) NOT NULL DEFAULT '',
  `action` char(32) NOT NULL DEFAULT '',
  `mg` char(32) NOT NULL DEFAULT '',
  `ts` char(32) NOT NULL DEFAULT '',
  `score` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`scoreid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_score: 16 rows
DELETE FROM `ts_user_score`;
/*!40000 ALTER TABLE `ts_user_score` DISABLE KEYS */;
INSERT INTO `ts_user_score` (`scoreid`, `scorekey`, `scorename`, `app`, `action`, `mg`, `ts`, `score`, `status`) VALUES
	(1, 'user_register', 'Регистрация', 'user', 'register', '', 'do', 10, 0),
	(2, 'user_login', 'Авторизация', 'user', 'login', '', 'do', 5, 0),
	(3, 'group_topic_add', 'Запись в группе', 'group', 'add', '', 'do', 10, 0),
	(4, 'group_topic_comment', 'Комментарий в группе', 'group', 'comment', '', 'do', 5, 0),
	(5, 'attach_upload', 'Загрузка', 'attach', 'upload', '', 'do', 10, 0),
	(6, 'user_signin', 'Вход', 'user', 'signin', '', '', 5, 0),
	(7, 'group_topic_delete', 'Удаление записи в группе', 'group', 'do', '', 'deltopic', 5, 1),
	(8, 'article_add', 'Добавление статьи', 'article', 'add', '', 'do', 5, 0),
	(9, 'article_delete', 'Удаление статьи', 'article', 'delete', '', '', 5, 1),
	(11, 'article_admin_post_isaudit0', 'Одобрение статьи', 'article', 'admin', 'post', 'isaudit0', 5, 0),
	(12, 'article_admin_post_isaudit1', 'Не одобрение статьи', 'article', 'admin', 'post', 'isaudit1', 5, 1),
	(13, 'ask_admin_topic_isaudit0', 'Одобрено админом', 'ask', 'admin', 'topic', 'isaudit0', 5, 0),
	(14, 'ask_admin_topic_isaudit1', 'Не одобрено админом', 'ask', 'admin', 'topic', 'isaudit1', 5, 1),
	(15, 'ask_new_do', 'Вопрос регистрации', 'ask', 'new', '', 'do', 5, 0),
	(16, 'ask_ajax_ask2commentid', 'Вопрос комментариев', 'ask', 'ajax', '', 'ask2commentid', 5, 0),
	(17, 'article_admin_post_delete', 'Удаление статьи админом', 'article', 'admin', 'post', 'delete', 5, 1);
/*!40000 ALTER TABLE `ts_user_score` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_score_log
DROP TABLE IF EXISTS `ts_user_score_log`;
CREATE TABLE IF NOT EXISTS `ts_user_score_log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `scorename` varchar(64) NOT NULL DEFAULT '',
  `score` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`logid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_user_score_log: 0 rows
DELETE FROM `ts_user_score_log`;
/*!40000 ALTER TABLE `ts_user_score_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_score_log` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_weibo
DROP TABLE IF EXISTS `ts_weibo`;
CREATE TABLE IF NOT EXISTS `ts_weibo` (
  `weiboid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `locationid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `count_comment` int(11) NOT NULL DEFAULT '0',
  `path` char(32) NOT NULL DEFAULT '',
  `photo` char(32) NOT NULL DEFAULT '',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `uptime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`weiboid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_weibo: 0 rows
DELETE FROM `ts_weibo`;
/*!40000 ALTER TABLE `ts_weibo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_weibo` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_weibo_comment
DROP TABLE IF EXISTS `ts_weibo_comment`;
CREATE TABLE IF NOT EXISTS `ts_weibo_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `weiboid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `touserid` int(11) NOT NULL DEFAULT '0',
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`commentid`),
  KEY `touserid` (`touserid`,`isread`),
  KEY `noteid` (`weiboid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_weibo_comment: 0 rows
DELETE FROM `ts_weibo_comment`;
/*!40000 ALTER TABLE `ts_weibo_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_weibo_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_weibo_options
DROP TABLE IF EXISTS `ts_weibo_options`;
CREATE TABLE IF NOT EXISTS `ts_weibo_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '',
  `optionvalue` varchar(512) NOT NULL DEFAULT '',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table thinksaas-dev.ts_weibo_options: 3 rows
DELETE FROM `ts_weibo_options`;
/*!40000 ALTER TABLE `ts_weibo_options` DISABLE KEYS */;
INSERT INTO `ts_weibo_options` (`optionname`, `optionvalue`) VALUES
	('appname', 'Микроблог'),
	('appdesc', 'Микроблог'),
	('appkey', 'микроблог');
/*!40000 ALTER TABLE `ts_weibo_options` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
