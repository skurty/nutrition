SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS `nutrition_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `nutrition_test`;


DROP TABLE IF EXISTS `diaries`;
DROP TABLE IF EXISTS `food_recipes`;
DROP TABLE IF EXISTS `foods`;
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `brands`;
DROP TABLE IF EXISTS `weights`;
DROP TABLE IF EXISTS `goals`;
DROP TABLE IF EXISTS `calories`;
DROP TABLE IF EXISTS `meals`;



-- --------------------------------------------------------

--
-- Structure de la table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Contenu de la table `brands`
--

INSERT INTO `brands` (`id`, `name`, `created`, `updated`, `status`) VALUES
(1, 'Carrefour', '2014-01-01 00:00:00', NULL, 1),
(2, 'D''aucy', '2014-01-01 00:00:00', NULL, 1),
(3, 'Panzani', '2014-01-01 00:00:00', NULL, 1);



-- --------------------------------------------------------

--
-- Structure de la table `foods`
--

CREATE TABLE IF NOT EXISTS `foods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `quantity` decimal(5,2) NOT NULL DEFAULT '1.00',
  `unit` varchar(15) DEFAULT NULL,
  `calories` decimal(6,2) NOT NULL DEFAULT '0.00',
  `proteins` decimal(6,2) NOT NULL DEFAULT '0.00',
  `carbohydrates` decimal(6,2) NOT NULL DEFAULT '0.00',
  `lipids` decimal(6,2) NOT NULL DEFAULT '0.00',
  `count` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `brand_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_foods_brands1_idx` (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Contenu de la table `foods`
--

INSERT INTO `foods` (`id`, `name`, `quantity`, `unit`, `calories`, `proteins`, `carbohydrates`, `lipids`, `count`, `created`, `updated`, `status`, `brand_id`) VALUES
(1, 'Amandes', '100.00', 'g', '578.00', '19.00', '4.00', '54.00', 683, '2014-01-01 00:00:00', '2014-08-07 14:29:02', 1, NULL),
(2, 'Blanc de poulet', '1.00', 'tranche', '32.00', '6.30', '0.30', '0.60', 175, '2014-01-01 00:00:00', '2014-08-05 08:55:58', 1, 1);

--
-- Contraintes pour la table `foods`
--

ALTER TABLE `foods`
  ADD CONSTRAINT `fk_foods_brands1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;



-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `calories` decimal(6,2) NOT NULL,
  `proteins` decimal(6,2) NOT NULL,
  `carbohydrates` decimal(6,2) NOT NULL,
  `lipids` decimal(6,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Contenu de la table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `calories`, `proteins`, `carbohydrates`, `lipids`, `created`, `updated`, `status`) VALUES
(1, 'Crêpe', '342.15', '28.22', '27.27', '12.98', '2014-01-01 00:00:00', '2014-02-02 09:44:50', 1),
(2, 'Pancake au sarrasin', '966.40', '45.60', '146.00', '20.50', '2014-01-01 00:00:00', '2014-02-02 09:47:08', 1);



-- --------------------------------------------------------

--
-- Structure de la table `weights`
--

CREATE TABLE IF NOT EXISTS `weights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `weight` decimal(3,1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `weights`
--

INSERT INTO `weights` (`id`, `date`, `weight`, `created`, `updated`, `status`) VALUES
(1, '2014-07-06', 72.8, '2014-07-06 11:16:08', NULL, 1),
(2, '2014-08-05', 74.8, '2014-08-05 23:53:03', NULL, 1);



-- --------------------------------------------------------

--
-- Structure de la table `goals`
--

CREATE TABLE IF NOT EXISTS `goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `calories` decimal(6,1) NOT NULL,
  `proteins` decimal(6,1) NOT NULL,
  `carbohydrates` decimal(6,1) NOT NULL,
  `lipids` decimal(6,1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `goals`
--

INSERT INTO `goals` (`id`, `date`, `calories`, `proteins`, `carbohydrates`, `lipids`, `created`, `updated`, `status`) VALUES
(1, '2014-01-13', 3375.0, 250.0, 408.0, 81.0, '2014-00-01 00:00:00', '2014-00-01 00:00:00', 1),
(2, '2014-01-27', 3800.0, 245.0, 476.0, 100.0, '2014-00-01 00:00:00', '2014-00-01 00:00:00', 1);



-- --------------------------------------------------------

--
-- Structure de la table `food_recipes`
--

CREATE TABLE IF NOT EXISTS `food_recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` decimal(5,2) NOT NULL,
  `calories` decimal(6,2) NOT NULL DEFAULT '0.00',
  `proteins` decimal(6,2) NOT NULL DEFAULT '0.00',
  `carbohydrates` decimal(6,2) NOT NULL DEFAULT '0.00',
  `lipids` decimal(6,2) NOT NULL DEFAULT '0.00',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `food_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`food_id`,`recipe_id`),
  KEY `fk_foods_has_recipes_recipes1_idx` (`recipe_id`),
  KEY `fk_foods_has_recipes_foods1_idx` (`food_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `food_recipes`
--

INSERT INTO `food_recipes` (`id`, `quantity`, `calories`, `proteins`, `carbohydrates`, `lipids`, `created`, `updated`, `status`, `food_id`, `recipe_id`) VALUES
(1, 25.00, 93.00, 3.38, 14.68, 1.75, '2014-02-02 09:43:09', '2014-02-02 09:43:09', 1, 1, 1),
(2, 4.00, 26.80, 0.80, 0.36, 2.32, '2014-02-02 09:43:16', '2014-02-02 09:43:16', 1, 2, 1);

--
-- Contraintes pour la table `food_recipes`
--

ALTER TABLE `food_recipes`
  ADD CONSTRAINT `fk_foods_has_recipes_foods1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_foods_has_recipes_recipes1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;



-- --------------------------------------------------------

--
-- Structure de la table `meals`
--

CREATE TABLE IF NOT EXISTS `meals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `meals`
--

INSERT INTO `meals` (`id`, `name`, `created`, `updated`, `status`) VALUES
(1, 'Petit déjeuner', NULL, NULL, 1),
(2, 'Déjeuner', NULL, NULL, 1),
(3, 'Diner', NULL, NULL, 1),
(4, 'Collation 1', NULL, NULL, 1),
(5, 'Collation 2', NULL, NULL, 1),
(6, 'Collation 3', NULL, NULL, 1);



-- --------------------------------------------------------

--
-- Structure de la table `diaries`
--

CREATE TABLE IF NOT EXISTS `diaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `quantity` decimal(5,2) NOT NULL,
  `unit` varchar(15) DEFAULT NULL,
  `date` date NOT NULL,
  `calories` decimal(6,2) NOT NULL,
  `proteins` decimal(6,2) NOT NULL,
  `carbohydrates` decimal(6,2) NOT NULL,
  `lipids` decimal(6,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `meal_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `food_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`meal_id`),
  KEY `fk_journals_meals1_idx` (`meal_id`),
  KEY `fk_journals_brands1_idx` (`brand_id`),
  KEY `fk_diaries_recipes1_idx` (`recipe_id`),
  KEY `fk_diaries_foods1_idx` (`food_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `diaries`
--

INSERT INTO `diaries` (`id`, `name`, `quantity`, `unit`, `date`, `calories`, `proteins`, `carbohydrates`, `lipids`, `created`, `updated`, `status`, `meal_id`, `brand_id`, `food_id`, `recipe_id`) VALUES
(1, 'Amandes', '100.00', 'g', '2014-08-11', '578.00', '19.00', '4.00', '54.00', '2014-08-11 00:00:00', NULL, 1, 1, NULL, 1, NULL),
(2, 'Blanc de poulet', '1.00', 'tranche', '2014-08-11', '32.00', '6.30', '0.30', '0.60', '2014-08-11 00:00:00', NULL, 1, 2, 1, 2, NULL);

--
-- Contraintes pour la table `diaries`
--

ALTER TABLE `diaries`
  ADD CONSTRAINT `fk_diaries_foods1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_diaries_recipes1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_journals_brands1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_journals_meals1` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;



-- --------------------------------------------------------

--
-- Structure de la table `calories`
--

CREATE TABLE IF NOT EXISTS `calories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `calories` int(5) NOT NULL,
  `proteins` int(3) NOT NULL,
  `carbohydrates` int(3) NOT NULL,
  `lipids` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `calories`
--

INSERT INTO `calories` (`id`, `date`, `calories`, `proteins`, `carbohydrates`, `lipids`) VALUES
(1, '2014-08-09', 1912, 186, 91, 84),
(2, '2014-08-10', 2012, 189, 97, 92);
