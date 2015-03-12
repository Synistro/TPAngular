-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 10 Décembre 2014 à 18:25
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `form_album`
--
CREATE DATABASE IF NOT EXISTS `form_album` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `form_album`;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dateMaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id`, `titre`, `dateMaj`) VALUES
(1, 'Le photographe à l''aéroport.', '2013-07-08 20:38:48'),
(2, 'L''accordéoniste au musée.', '2013-07-08 20:38:48'),
(3, 'Tanguy Flot, gros plan.', '2013-07-08 20:39:46'),
(4, 'Tanguy Flot dans son atelier.', '2013-07-08 20:39:46'),
(5, 'Tanguy Flot, plan moyen.', '2013-07-08 20:40:20'),
(6, 'Sanseverino à Jazz in Marciac.', '2013-07-08 20:40:20'),
(7, 'Spectateurs à Jazz in Marciac.', '2013-07-08 20:41:04'),
(8, 'La fille au musée.', '2013-07-08 20:41:04');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
