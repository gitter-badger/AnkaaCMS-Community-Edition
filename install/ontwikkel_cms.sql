-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 25 aug 2014 om 22:33
-- Serverversie: 5.6.17
-- PHP-versie: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `ontwikkel_cms`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `extensions`
--

CREATE TABLE IF NOT EXISTS `extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `class` varchar(64) NOT NULL,
  `file` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden geëxporteerd voor tabel `extensions`
--

INSERT INTO `extensions` (`id`, `name`, `class`, `file`, `status`) VALUES
(1, 'page', 'PageExt', 'page.ext.php', 1),
(2, 'menu', 'MenuExt', 'menu.ext.php', 1),
(3, 'language', 'LangExt', 'lang.ext.php', 1),
(4, 'auth', 'AuthExt', 'auth.ext.php', 1),
(5, 'admin', 'AdminExt', 'admin.ext.php', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_menu`
--

CREATE TABLE IF NOT EXISTS `ext_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `url` text NOT NULL,
  `status` int(11) NOT NULL,
  `siteprofile_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden geëxporteerd voor tabel `ext_menu`
--

INSERT INTO `ext_menu` (`id`, `menuid`, `itemid`, `parentid`, `type`, `typeid`, `url`, `status`, `siteprofile_id`) VALUES
(1, 1, 1, 0, 1, 1, './About', 1, 1),
(2, 1, 2, 0, 1, 2, './Home', 1, 1),
(3, 1, 3, 0, 1, 3, './Contact', 1, 2),
(4, 1, 1, 0, 1, 1, './Home', 1, 2),
(5, 1, 6, 0, 1, 3, './Contact', 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_menu_site`
--

CREATE TABLE IF NOT EXISTS `ext_menu_site` (
  `menuid` int(11) NOT NULL,
  `siteprofile_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `ext_menu_site`
--

INSERT INTO `ext_menu_site` (`menuid`, `siteprofile_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_page`
--

CREATE TABLE IF NOT EXISTS `ext_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteprofile_id` int(11) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `parent` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `template` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden geëxporteerd voor tabel `ext_page`
--

INSERT INTO `ext_page` (`id`, `siteprofile_id`, `slug`, `parent`, `moduleid`, `template`, `status`) VALUES
(0, 0, 'admin', 0, 0, 'default', 1),
(1, 1, 'Home', 0, 1, 'default', 1),
(2, 1, 'About', 0, 1, 'default', 1),
(3, 1, 'Contact', 0, 0, 'default', 1),
(5, 2, 'Home', 0, 1, 'default', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_page_module`
--

CREATE TABLE IF NOT EXISTS `ext_page_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden geëxporteerd voor tabel `ext_page_module`
--

INSERT INTO `ext_page_module` (`id`, `position_id`, `location_id`, `module_id`, `page_id`, `active`) VALUES
(0, 1, 1, 0, 0, 1),
(1, 1, 1, 1, 3, 1),
(2, 1, 2, 2, 3, 1),
(3, 1, 1, 1, 2, 1),
(4, 1, 1, 1, 1, 1),
(5, 1, 1, 1, 5, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_page_module_attributes`
--

CREATE TABLE IF NOT EXISTS `ext_page_module_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagemodule_id` int(11) NOT NULL,
  `attribute` varchar(256) NOT NULL,
  `string` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Gegevens worden geëxporteerd voor tabel `ext_page_module_attributes`
--

INSERT INTO `ext_page_module_attributes` (`id`, `pagemodule_id`, `attribute`, `string`) VALUES
(1, 1, 'article_id', '1'),
(2, 2, 'contact_id', '1'),
(3, 3, 'article_id', '2'),
(4, 4, 'article_id', '3'),
(5, 5, 'article_id', '4'),
(6, 0, 'admin_id', '0');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden geëxporteerd voor tabel `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `status`) VALUES
(1, 'en', 'English', 1),
(2, 'nl', 'Nederlands', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `folder` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden geëxporteerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `name`, `description`, `folder`) VALUES
(0, 'Auth', 'Authentication module', 'auth'),
(1, 'Article', 'Create articles for your website.', 'article'),
(2, 'Contact', 'Make your own contact form', 'contact');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_article`
--

CREATE TABLE IF NOT EXISTS `mod_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `subtitle` varchar(512) NOT NULL,
  `content` text NOT NULL,
  `siteprofile_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden geëxporteerd voor tabel `mod_article`
--

INSERT INTO `mod_article` (`id`, `title`, `subtitle`, `content`, `siteprofile_id`) VALUES
(1, 'Contact Us', 'Please contact us for questions e.d.', 'Please use the contactform on this page to contact us.<br />\r\n<br />\r\nWe appreciate it if you would fill in the form as much as possible.<br />', 2),
(2, 'Dit is de About pagina', 'About pagina voor deze website', 'Dit is een voorbeeldtekst voor de pagina in het menu met het kopje ''about''.', 1),
(3, 'Dit is de Home pagina', 'Home pagina voor deze website', 'Dit is een voorbeeldtekst voor de pagina in het menu met het kopje ''Home''.', 1),
(4, 'Home', 'This is the homepage', 'This is the english Homepage for TheWayIM.com', 2),
(5, 'Contact Us', 'Gebruik het contactformulier om contact met ons op te nemen.', 'Onderstaand contactformulier gelieve zo volledig mogelijk in te vullen zodat wij u zo goed als mogelijk kunnen helpen.\n', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_article_categories`
--

CREATE TABLE IF NOT EXISTS `mod_article_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `parent` int(11) NOT NULL,
  `siteprofile_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_article_item_category`
--

CREATE TABLE IF NOT EXISTS `mod_article_item_category` (
  `itemid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_contact`
--

CREATE TABLE IF NOT EXISTS `mod_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_contact_data`
--

CREATE TABLE IF NOT EXISTS `mod_contact_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formid` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `string` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_contact_fields`
--

CREATE TABLE IF NOT EXISTS `mod_contact_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `label` varchar(512) NOT NULL,
  `attributes` varchar(1024) NOT NULL,
  `placeholder` varchar(512) NOT NULL,
  `data-mask` varchar(512) NOT NULL,
  `value` varchar(512) NOT NULL,
  `string` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_contact_receivers`
--

CREATE TABLE IF NOT EXISTS `mod_contact_receivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formid` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `mail` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mod_landingpage`
--

CREATE TABLE IF NOT EXISTS `mod_landingpage` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `siteprofile_id` int(11) NOT NULL,
  `template` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sys_sessions`
--

CREATE TABLE IF NOT EXISTS `sys_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `timestamp` varchar(128) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `session` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session` (`session`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sys_settings`
--

CREATE TABLE IF NOT EXISTS `sys_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteprofile_id` int(11) NOT NULL,
  `attribute` varchar(128) NOT NULL,
  `string` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Gegevens worden geëxporteerd voor tabel `sys_settings`
--

INSERT INTO `sys_settings` (`id`, `siteprofile_id`, `attribute`, `string`) VALUES
(1, 1, 'site_title', 'title'),
(2, 1, 'site_slogan', 'slogan'),
(3, 1, 'site_theme', 'default'),
(4, 1, 'site_frontpage', 'Home'),
(5, 1, 'http_connection', 'http'),
(6, 1, 'site_domain', 'localhost'),
(7, 1, 'site_footer', '&copy; 2014<span style="float:right; margin-right: 15px;">Powered by <a href="https://dienstkoning.nl/" target="_blank">Dienstkoning.nl</a></span>');
-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sys_siteprofile`
--

CREATE TABLE IF NOT EXISTS `sys_siteprofile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `language` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden geëxporteerd voor tabel `sys_siteprofile`
--

INSERT INTO `sys_siteprofile` (`id`, `siteid`, `domain`, `language`) VALUES
(1, 1, 'localhost', 'nl');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sys_siteprofile_settings`
--

CREATE TABLE IF NOT EXISTS `sys_siteprofile_settings` (
  `siteprofile_id` int(11) NOT NULL,
  `settings_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `sys_siteprofile_settings`
--

INSERT INTO `sys_siteprofile_settings` (`siteprofile_id`, `settings_id`) VALUES
(1, 1),
(1, 6),
(1, 3),
(1, 4),
(1, 5),
(1, 2),
(1, 7);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sys_sites`
--

CREATE TABLE IF NOT EXISTS `sys_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `sys_sites`
--

INSERT INTO `sys_sites` (`id`, `title`) VALUES
(1, 'Website');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sys_users`
--

CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(512) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `salt` varchar(600) NOT NULL,
  `profileid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `sys_users`
--

INSERT INTO `sys_users` (`id`, `username`, `password`, `salt`, `profileid`, `roleid`, `enabled`) VALUES

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
