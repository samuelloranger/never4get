-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2018 at 01:39 PM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `18_pni1_tofu`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_liste`
--

CREATE TABLE `t_liste` (
  `id_liste` int(10) UNSIGNED NOT NULL,
  `nom_liste` varchar(50) NOT NULL COMMENT 'limite de 25 caractères',
  `id_couleur` tinyint(3) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_liste`
--

INSERT INTO `t_liste` (`id_liste`, `nom_liste`, `id_couleur`, `id_utilisateur`) VALUES
(1, 'Épicerie', 3, 1),
(2, 'Devoirs', 12, 1),
(3, 'les jurons de Haddock', 4, 1),
(4, 'Excursions camping WeekEnd', 8, 1),
(5, 'Films à voir', 13, 1),
(6, 'Lieux à visiter', 7, 1),
(7, 'Livres à lire', 19, 1),
(8, 'test', 8, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_liste`
--
ALTER TABLE `t_liste`
  ADD PRIMARY KEY (`id_liste`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_couleur` (`id_couleur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_liste`
--
ALTER TABLE `t_liste`
  MODIFY `id_liste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_liste`
--
ALTER TABLE `t_liste`
  ADD CONSTRAINT `Clé étrangère couleur` FOREIGN KEY (`id_couleur`) REFERENCES `t_couleur` (`id_couleur`),
  ADD CONSTRAINT `Clé étrangère utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `t_utilisateur` (`id_utilisateur`);
