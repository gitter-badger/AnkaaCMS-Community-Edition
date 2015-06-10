-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 10 jun 2015 om 21:47
-- Serverversie: 5.6.13
-- PHP-versie: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `ankaacms`
--
CREATE DATABASE IF NOT EXISTS `ankaacms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ankaacms`;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `articles`
--

INSERT INTO `articles` (`id`, `title`, `subtitle`, `content`, `template`) VALUES
(1, 'article title', 'article sub title', 'this is an article', 1),
(2, 'article title', 'article sub title', 'this is an article', 2),
(3, 'article title', 'article sub title', 'this is an article', 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `article_templates`
--

CREATE TABLE IF NOT EXISTS `article_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `file` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file` (`file`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `article_templates`
--

INSERT INTO `article_templates` (`id`, `template_id`, `name`, `file`) VALUES
(1, 1, 'Default', 'default.tpl'),
(2, 1, 'Left Wide', 'left_block_wide.tpl'),
(3, 1, 'Right', 'right_block.tpl');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `extensions`
--

INSERT INTO `extensions` (`id`, `name`, `class`, `description`, `enabled`) VALUES
(1, 'Site', 'site', 'This extension makes your website work.', 1),
(2, 'Page', 'page', 'Each website needs a page to show its data. This extension makes sure the right page is to be shown at the right place.', 1),
(3, 'Menu', 'menu', 'Create simple recursive menus for your website.', 1),
(4, 'User', 'user', 'This extension takes care of the user registration and admin login. This extension can be reused for other user-purposes.', 1),
(5, 'Administration Panel', 'adminpanel', 'This extension gives an adminpanel to administrate this application.', 1);

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
-- Gegevens worden uitgevoerd voor tabel `menus`
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
-- Gegevens worden uitgevoerd voor tabel `menus_items`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `title`, `description`, `name`, `status`) VALUES
(1, 'Articles', 'Articles are a great way to fill your website with textual content.', 'article', 1),
(2, 'Newsletter', 'Create your own newsletters and keep your customers up to date', 'newsletter', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `output_supported`
--

CREATE TABLE IF NOT EXISTS `output_supported` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `extension_class` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `extension_class` (`extension_class`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `output_supported`
--

INSERT INTO `output_supported` (`id`, `extension_class`, `name`, `type`, `enabled`) VALUES
(1, 'html', 'html', 'text/html', 1),
(2, 'json', 'json', 'application/json', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `pages`
--

INSERT INTO `pages` (`id`, `title`, `subtitle`, `author`, `default`, `enabled`) VALUES
(1, 'Home', 'Home sweet home', 1, 1, 1),
(2, 'About', 'About us', 1, 1, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `pages_blocks`
--

INSERT INTO `pages_blocks` (`id`, `location`, `status`) VALUES
(1, 'block1', 1),
(2, 'block2', 1),
(3, 'block3', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `pages_content`
--

INSERT INTO `pages_content` (`id`, `pageid`, `blockid`, `order`, `module`, `data`, `enabled`) VALUES
(1, 1, 1, 1, 1, '{"id":1}', 1),
(2, 1, 2, 1, 1, '{"id":2}', 1),
(3, 1, 3, 1, 1, '{"id":3}', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_name` varchar(250) NOT NULL,
  `settings_value` varchar(500) NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Gegevens worden uitgevoerd voor tabel `settings`
--

INSERT INTO `settings` (`settings_id`, `settings_name`, `settings_value`) VALUES
(1, 'site_url', 'http://localhost:8080/'),
(2, 'site_name', 'AnkaaCMS'),
(3, 'language_default', 'nl'),
(4, 'domain_default', 'localhost'),
(5, 'error_display', 'false'),
(6, 'error_level', 'debug'),
(7, 'error_logging', 'true'),
(8, 'error_logmethod', 'file'),
(57, 'site_logo', '/images/logo.gif'),
(58, 'site_footer', '<div class="copyright"><a href="http://csstemplatesmarket.com/" target="_blank">Free CSS Templates</a> | <a href="http://csstemplatesmarket.com/" target="_blank">by CssTemplatesMarket</a></div>'),
(59, 'page_template', 'acalia'),
(60, 'admin_template', 'admin_default'),
(61, 'default_module_name', 'page'),
(62, 'default_module_value', 'Home'),
(63, 'user_timeout', '1440');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `status`, `created`) VALUES
(1, 'admin', '$2y$10$f3d1bd14dfafe7e6b716eewvijYudvKBZLZB5.lPDp5izJjEla5Yq', 1, '2015-06-10 13:55:36');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_secval`
--

CREATE TABLE IF NOT EXISTS `user_secval` (
  `uid` bigint(20) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `user_secval`
--

INSERT INTO `user_secval` (`uid`, `value`) VALUES
(1, 'f3d1bd14dfafe7e6b716ef05e80cf8f7154bf6e247516296420285f3400bfd2b17c2a61fe8de541612326968b876577084f0e6945ad6e374736f94c8560d4c0039238872f624f7868053a2cf32374fd031b0c3bdc32f85f7fe4412ff7903d67402426e587be41c5069f6d1e8b00d08b2507dcc0d23d4bcf514e5a4e957b9b0d7');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_session`
--

CREATE TABLE IF NOT EXISTS `user_session` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `hash` varchar(1024) NOT NULL,
  `ip` varchar(512) NOT NULL,
  `logintime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastaction` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`(767))
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=114 ;

--
-- Gegevens worden uitgevoerd voor tabel `user_session`
--

INSERT INTO `user_session` (`id`, `user_id`, `hash`, `ip`, `logintime`, `lastaction`) VALUES
(85, 1, 'c27800986925f1678150c876c6207c73ea212ef8a6d5f5d50a68ba6e3895b28f', '::1', '2015-06-10 14:47:25', '2015-06-10 14:47:25'),
(86, 1, '8104cbfc62193bc8ba30e15f69705e81cb8eee27cce4e5c63e714205e6fe5300', '::1', '2015-06-10 14:47:25', '2015-06-10 14:47:25'),
(87, 1, 'be42c8850db031a7be839d4bfeb8db56eb1f608855be5eaa99955e7190b162b3', '::1', '2015-06-10 14:49:07', '2015-06-10 14:49:07'),
(88, 1, 'd7306cce247e9d994d94c1d51502f781bd0c7f431ffba026a0315c1f2fd9a860', '::1', '2015-06-10 14:49:07', '2015-06-10 14:49:07'),
(89, 1, 'e76fb95c77de1d540e8ed3239106204ec71780f3b1df1ffbe7c5b7fe50cd6274', '::1', '2015-06-10 14:49:50', '2015-06-10 14:49:50'),
(90, 1, '5b994afe8442683442447e3a31895b5193d1a1b8f916c4e5640bef1f38b5c7e1', '::1', '2015-06-10 14:49:50', '2015-06-10 14:49:50'),
(91, 1, 'aa31697c597bab6dedbd2e3242fe92a6adae3771bae797ed2cb2bab739867c41', '::1', '2015-06-10 14:50:01', '2015-06-10 14:50:01'),
(92, 1, 'd89492c0e41e702a8ca9a5493b7013067bbc5a4fe2c8219d1977081b5557535f', '::1', '2015-06-10 14:50:01', '2015-06-10 14:50:01'),
(93, 1, 'e64104a2a70c991e4f91b22c5cacbbb4f14d0e4339c9bd25537d92810cf69d04', '::1', '2015-06-10 14:59:04', '2015-06-10 14:59:04'),
(94, 1, 'ff2f08bfc214996e6951f81cdc4d2841f5cb0a2f606df843f5a7a0b55a63468b', '::1', '2015-06-10 14:59:37', '2015-06-10 14:59:37'),
(95, 1, 'd1c5dfbc34d5f621fd5430aa6147fc98bf3cf3c60981d90f36155ce816efd94b', '::1', '2015-06-10 14:59:53', '2015-06-10 14:59:53'),
(96, 1, 'c5ec0399d5f64fd8ea669ad0ecc4ed6c2881b08a985f965ab08020778d975c9b', '::1', '2015-06-10 15:00:12', '2015-06-10 15:00:12'),
(97, 1, '3850203ea5354c1565a8222ea3be619da80d491da8392ed078e272b71cf74eb1', '::1', '2015-06-10 15:02:40', '2015-06-10 15:02:40'),
(98, 1, '1db6505257ca12a8c2e7cf55ac0b600003c77e8df2829e3de998795d8cd5d1ab', '::1', '2015-06-10 15:03:27', '2015-06-10 15:03:27'),
(99, 1, '1cb3820f3cdffda8d567af33752195f0095c502e4ec1b72431e755b3a81dc2bd', '::1', '2015-06-10 15:05:51', '2015-06-10 15:05:51'),
(100, 1, '601f0c3975decf5f3e0658b0fa6c9d1e07af88b2c2e961c2bb83d4d0049ea067', '::1', '2015-06-10 15:07:22', '2015-06-10 15:07:22'),
(101, 1, 'b6aba2a7da76a189f5792481c50996992a026ff2c5649de3a2a1c462fce2eaa9', '::1', '2015-06-10 15:09:07', '2015-06-10 15:09:07'),
(106, 1, 'b3b040d69feda4961d89a17609ec3079c5336f7fe098349d22cc6bae2a1c936e', '::1', '2015-06-10 18:15:11', '2015-06-10 18:15:11'),
(111, 1, '37fdd3828d669e1d95273b86d4d94d3ecd5523d60215fc8e8f38b5697a261e86', '::1', '2015-06-10 19:10:13', '2015-06-10 19:10:13'),
(113, 1, '4b76f62874874c97256ab00b82de5293a44aaf5d408cc67cb856db799af8cf9e', '::1', '2015-06-10 19:40:54', '2015-06-10 19:40:54');

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
-- Gegevens worden uitgevoerd voor tabel `widgets`
--

INSERT INTO `widgets` (`id`, `name`, `folder`, `description`, `status`) VALUES
(1, 'Header imageSlider', 'header_imageSlider', 'With Header imageSlider you can create an image slider in your page header', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `widget_header_imageslider`
--

CREATE TABLE IF NOT EXISTS `widget_header_imageslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(250) NOT NULL,
  `width` bigint(20) NOT NULL,
  `height` bigint(20) NOT NULL,
  `alt` varchar(250) NOT NULL,
  `title` varchar(500) NOT NULL,
  `text` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `widget_header_imageslider`
--

INSERT INTO `widget_header_imageslider` (`id`, `img`, `width`, `height`, `alt`, `title`, `text`, `status`) VALUES
(1, 'templates/acalia/images/slider_photo.jpg', 965, 280, '', '', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 1),
(2, 'templates/acalia/images/slider_photo2.jpg', 965, 280, '', '', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 1),
(3, 'templates/acalia/images/slider_photo3.jpg', 965, 280, '', '', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 0),
(4, 'templates/acalia/images/slider_photo2.jpg', 965, 280, '', '', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
