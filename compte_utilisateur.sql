-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 20 juil. 2025 à 17:21
-- Version du serveur : 8.3.0
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jeu_devinette`
--

-- --------------------------------------------------------

--
-- Structure de la table `compte_utilisateur`
--

DROP TABLE IF EXISTS `compte_utilisateur`;
CREATE TABLE IF NOT EXISTS `compte_utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_utilisateur` (`nom_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte_utilisateur`
--

INSERT INTO `compte_utilisateur` (`id`, `nom_utilisateur`, `mot_de_passe`, `date_creation`) VALUES
(1, 'sonic12345', '$2y$10$AMyb4cbGSWSvEcQxt91ZVu5r5OV7/3mMZl7tn8wnZrJ1ddidYfVYW', '2025-07-16 23:18:27'),
(2, 'asterix2023', '$2y$10$Lpd3JsgFW9.x2ft6Qo9h..xmtm82lmSuv/vaQKs9xPJ4rhKlMJAF.', '2025-07-16 23:18:27'),
(3, 'pokemon527', '$2y$10$FRAyAIK6.TYEEmbOHF4JfeiBCdWFHcqRTILM7nF/7CPjE3dNEWj3W', '2025-07-16 23:18:27'),
(4, 'eric00', '$2y$10$8iMizjSEZgx5tLbA.XiuEeuZGItUS8a4IKuSiWu9i7uHG2jMhllSm', '2025-07-17 02:34:44'),
(5, 'Aqwet1234', '$2y$10$O.ER3ium86rtd6a2/kX/sOGEU/Yw4oKUeYnozNsK1UXYDMp3bLLz6', '2025-07-17 05:17:38');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
