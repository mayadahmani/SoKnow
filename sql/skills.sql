-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 27 mars 2026 à 17:04
-- Version du serveur : 10.11.14-MariaDB-0+deb12u2
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `12400095_Soknow`
--

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name_fr` varchar(100) NOT NULL,
  `name_al` varchar(100) NOT NULL,
  `name_vi` varchar(100) NOT NULL,
  `category` enum('modern','legacy','general') DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `name_fr`, `name_al`, `name_vi`, `category`) VALUES
(1, 'Smartphone', 'Celular', 'Điện thoại thông minh', 'general'),
(2, 'Ordinateur', 'Kompjuter', 'Máy tính', 'general'),
(3, 'Internet', 'Internet', 'Internet', 'general'),
(4, 'Tablette', 'Tablet', 'Máy tính bảng', 'general'),
(5, 'Emails', 'Emails', 'Emails', 'general'),
(6, 'Sécurité', 'Sécurité', 'Sécurité', 'general'),
(7, 'Developpement Web', 'Developpement Web', 'Developpement Web', 'general'),
(8, 'PHP', 'PHP', 'PHP', 'general');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
