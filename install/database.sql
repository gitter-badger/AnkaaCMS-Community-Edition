-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 24 apr 2015 om 23:21
-- Serverversie: 5.6.17
-- PHP-versie: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `ankaacms`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `subtitle` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `template` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`subtitle`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `articles`
--

INSERT INTO `articles` (`id`, `title`, `subtitle`, `content`, `template`) VALUES
(1, 'article title', 'article sub title', 'this is an article', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `article_templates`
--

CREATE TABLE IF NOT EXISTS `article_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `file` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file` (`file`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `article_templates`
--

INSERT INTO `article_templates` (`id`, `name`, `file`) VALUES
(1, 'Default', 'default.tpl');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `extensions`
--

CREATE TABLE IF NOT EXISTS `extensions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `class` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class` (`class`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Gegevens worden geëxporteerd voor tabel `extensions`
--

INSERT INTO `extensions` (`id`, `name`, `class`, `description`, `enabled`) VALUES
(1, 'Site', 'site', 'This extension makes your website work.', 1),
(2, 'Page', 'page', 'Each website needs a page to show its data. This extension makes sure the right page is to be shown at the right place.', 1),
(3, 'Menu', 'menu', 'Create simple recursive menus for your website.', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `menus`
--

INSERT INTO `menus` (`id`, `name`, `description`, `status`) VALUES
(1, 'top', 'Create a top menu for your website.', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `menus_items`
--

CREATE TABLE IF NOT EXISTS `menus_items` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parentid` bigint(20) NOT NULL,
  `menuid` int(11) NOT NULL,
  `href` varchar(500) NOT NULL,
  `name` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `menus_items`
--

INSERT INTO `menus_items` (`id`, `parentid`, `menuid`, `href`, `name`, `title`, `status`) VALUES
(1, 0, 1, 'index.php/Home', 'Home', 'Homepage', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `title`, `description`, `name`, `status`) VALUES
(1, 'Articles', 'Articles are a great way to fill your website with textual content.', 'article', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `subtitle` varchar(500) NOT NULL,
  `author` bigint(20) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author` (`author`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `pages`
--

INSERT INTO `pages` (`id`, `title`, `subtitle`, `author`, `default`, `enabled`) VALUES
(1, 'Home', 'Home sweet home', 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pages_blocks`
--

CREATE TABLE IF NOT EXISTS `pages_blocks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `location` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`location`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `pages_blocks`
--

INSERT INTO `pages_blocks` (`id`, `location`, `status`) VALUES
(1, 'block1', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pages_content`
--

CREATE TABLE IF NOT EXISTS `pages_content` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pageid` bigint(20) NOT NULL,
  `blockid` bigint(20) NOT NULL,
  `order` bigint(20) NOT NULL,
  `module` bigint(20) NOT NULL,
  `data` text NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageid` (`pageid`,`order`,`enabled`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `pages_content`
--

INSERT INTO `pages_content` (`id`, `pageid`, `blockid`, `order`, `module`, `data`, `enabled`) VALUES
(1, 1, 1, 1, 1, '{"id":1}', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_name` varchar(250) NOT NULL,
  `settings_value` varchar(500) NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Gegevens worden geëxporteerd voor tabel `settings`
--

INSERT INTO `settings` (`settings_id`, `settings_name`, `settings_value`) VALUES
(1, 'site_url', 'http://localhost/'),
(2, 'site_name', 'AnkaaCMS'),
(3, 'language_default', 'nl'),
(4, 'domain_default', 'localhost'),
(5, 'error_display', 'false'),
(6, 'error_level', 'debug'),
(7, 'error_logging', 'true'),
(8, 'error_logmethod', 'file'),
(57, 'site_logo', '/images/logo.gif');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `widgets`
--

CREATE TABLE IF NOT EXISTS `widgets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `folder` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `widgets`
--

INSERT INTO `widgets` (`id`, `name`, `folder`, `description`, `status`) VALUES
(1, 'Header imageSlider', 'header_imageSlider', 'With Header imageSlider you can create an image slider in your page header', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `widget_header_imageslider`
--

CREATE TABLE IF NOT EXISTS `widget_header_imageslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` int(250) NOT NULL,
  `value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
