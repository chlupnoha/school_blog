-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picture_blog_id` int(11) DEFAULT NULL,
  `picture_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `most_readed` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `dont_publish_detail` tinyint(4) NOT NULL,
  `published_on` date DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `click` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_23A0E66EE45BDBF` (`picture_id`),
  UNIQUE KEY `UNIQ_23A0E66CDE708B6` (`picture_blog_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `article_ibfk_2` FOREIGN KEY (`picture_blog_id`) REFERENCES `picture` (`id`),
  CONSTRAINT `FK_23A0E66EE45BDBF` FOREIGN KEY (`picture_id`) REFERENCES `picture` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `article` (`id`, `picture_blog_id`, `picture_id`, `title`, `content`, `description`, `category_id`, `created_at`, `url`, `meta_title`, `meta_description`, `meta_keywords`, `most_readed`, `published`, `dont_publish_detail`, `published_on`, `sort`, `click`) VALUES
  (59,	NULL,	NULL,	'test test',	'test test',	'test test',	NULL,	'2018-01-11 20:08:17',	'test',	'test',	'test',	'test',	0,	1,	0,	NULL,	0,	75);

DROP TABLE IF EXISTS `article_tag`;
CREATE TABLE `article_tag` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `article_tag_ibfk_3` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_tag_ibfk_4` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `category` (`id`, `name`) VALUES
  (2,	'PPC seriál'),
  (3,	'SEO seriál');

DROP TABLE IF EXISTS `email`;
CREATE TABLE `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `picture`;
CREATE TABLE `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `tag` (`id`, `name`) VALUES
  (2,	'FACEBOOK'),
  (4,	'SEO'),
  (6,	'WEB'),
  (8,	'PPC'),
  (9,	'Vše'),
  (10,	'test2');

DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `user` (`id`, `name`, `description`, `password`) VALUES
  (7,	'admin',	'',	'$2y$10$w/jXAeOizsCSRMHdtxjPde8.exNPj6lTovppujbJ7JOAKLfo3fdye');

-- 2018-01-11 20:24:54