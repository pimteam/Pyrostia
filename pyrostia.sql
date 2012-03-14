-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 13, 2012 at 07:43 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sdm_calendar`
--

-- --------------------------------------------------------

--
-- Table structure for table `pyr_cols`
--

DROP TABLE IF EXISTS `pyr_cols`;
CREATE TABLE IF NOT EXISTS `pyr_cols` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `date_from` date NOT NULL,
  `date_to` int(11) NOT NULL,
  `keep_going` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='custom columns (in addition to lunch, dinner etc)' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pyr_groceries`
--

DROP TABLE IF EXISTS `pyr_groceries`;
CREATE TABLE IF NOT EXISTS `pyr_groceries` (
  `id` int(11) NOT NULL auto_increment,
  `item` varchar(100) collate utf8_unicode_ci NOT NULL,
  `qty` int(10) unsigned NOT NULL,
  `unit` varchar(100) collate utf8_unicode_ci NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `week` tinyint(3) unsigned NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `meal_id` int(10) unsigned NOT NULL,
  `is_available` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `pyr_meals`
--

DROP TABLE IF EXISTS `pyr_meals`;
CREATE TABLE IF NOT EXISTS `pyr_meals` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `col` varchar(100) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `recipe` text collate utf8_unicode_ci NOT NULL,
  `link` varchar(255) collate utf8_unicode_ci NOT NULL,
  `calories` int(11) NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `ingredients` text collate utf8_unicode_ci NOT NULL,
  `in_grocery` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

--
-- Table structure for table `pyr_settings`
--

DROP TABLE IF EXISTS `pyr_settings`;
CREATE TABLE IF NOT EXISTS `pyr_settings` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `pyr_settings`
--

INSERT INTO `pyr_settings` (`id`, `name`, `value`) VALUES (1, 'page_limit', '10');

-- --------------------------------------------------------

--
-- Table structure for table `pyr_users`
--

DROP TABLE IF EXISTS `pyr_users`;
CREATE TABLE IF NOT EXISTS `pyr_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `pass` varchar(100) collate utf8_unicode_ci NOT NULL,
  `regdate` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=113 ;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
