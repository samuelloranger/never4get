-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 22, 2018 at 01:42 PM
-- Server version: 5.5.60-MariaDB
-- PHP Version: 7.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `18_pni1_tofu_tom`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_couleur`
--

CREATE TABLE IF NOT EXISTS `t_couleur` (
  `id_couleur` tinyint(3) unsigned NOT NULL,
  `nom_couleur_fr` varchar(25) NOT NULL,
  `nom_couleur_en` varchar(25) NOT NULL,
  `hexadecimale` varchar(6) NOT NULL,
  `rgb` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_couleur`
--

INSERT INTO `t_couleur` (`id_couleur`, `nom_couleur_fr`, `nom_couleur_en`, `hexadecimale`, `rgb`) VALUES
(1, 'Tofu', 'Tofu', 'FAF8EF', '250, 248, 239'),
(2, 'Écureuil', 'Squirrel', '8F8176', '143, 129, 118'),
(3, 'Topaze', 'Topaz', '7C778A', '124, 119, 138'),
(4, 'Salem', 'Salem', '0B8436', '21, 80, 213'),
(5, 'Wasabi', 'Wasabi', '788A25', '120, 138, 37'),
(6, 'Trèfle', 'Shamrock', '36D965', '54, 217, 101'),
(7, 'Tournesol', 'Sunflower', 'E2BD28', '226, 189, 40'),
(8, 'Tango', 'Tango', 'ED7A1C', '237, 122, 28'),
(9, 'Vermillon', 'Vermilion', 'FF4D00', '255, 77, 0'),
(10, 'Écarlate', 'Scarlet ', 'F5200A', '245, 32, 10'),
(11, 'Vieille brique', 'Old Brick', '941E1E', '148, 30, 30'),
(12, 'Disco', 'Disco', '871550', '135, 21, 80'),
(13, 'Héliotrope', 'Heliotrope', 'DF73FF', '223, 115, 255'),
(14, 'Lavande', 'Lavender', 'B57EDC', '181, 126, 220'),
(15, 'Violet', 'Purple ', '660099', '102, 0, 153'),
(16, 'Indigo perse', 'Persian Indigo', '2A1587', '42,21,135'),
(17, 'Denim', 'Denim', '1550D5', '21, 80, 213'),
(18, 'Turquoise', 'Turquoise', '36D5D9', '54, 213, 217'),
(19, 'Muesli', 'Muesli', 'AA8B5B', '170, 139, 91'),
(20, 'Cannelle intense', 'Hot Cinnamon', 'E3671C', '227, 103, 28');

-- --------------------------------------------------------

--
-- Table structure for table `t_item`
--

CREATE TABLE IF NOT EXISTS `t_item` (
  `id_item` int(10) unsigned NOT NULL,
  `nom_item` varchar(55) NOT NULL,
  `echeance` datetime DEFAULT NULL,
  `est_complete` tinyint(1) NOT NULL DEFAULT '1',
  `id_liste` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_item`
--

INSERT INTO `t_item` (`id_item`, `nom_item`, `echeance`, `est_complete`, `id_liste`) VALUES
(1, 'rigatoni', NULL, 0, 1),
(2, 'coriandre', NULL, 0, 1),
(3, 'Dissertation sur Zarathoustra', '2018-12-04 00:00:00', 0, 2),
(4, 'Exercice #4.5 Anglais', '2018-12-02 00:00:00', 0, 2),
(5, 'Citizen Kane ', NULL, 0, 5),
(6, 'The Wizard of Oz', NULL, 0, 5),
(7, 'Metropolis', NULL, 0, 5),
(8, 'Singin'' in the Rain ', NULL, 0, 5),
(9, 'piments jalapeno', NULL, 0, 1),
(10, 'lait de coco', NULL, 0, 1),
(11, 'persil frisé', NULL, 0, 1),
(13, 'Douze hommes en colère', NULL, 0, 5),
(14, 'Le parrain', NULL, 0, 5),
(15, 'Psychose', NULL, 0, 5),
(16, 'Il était une fois dans l''Ouest', NULL, 0, 5),
(17, 'Pulp Fiction', NULL, 0, 5),
(18, 'Le dictateur', NULL, 0, 5),
(19, 'Vol au-dessus d''un nid de coucou', NULL, 0, 5),
(20, 'Le Tombeau des lucioles', NULL, 0, 5),
(21, 'Taxi Driver', NULL, 0, 5),
(22, 'Orange Mécanique', NULL, 0, 5),
(23, 'Trilogie le seigneur des anneaux', NULL, 0, 5),
(24, 'Full Metal Jacket', NULL, 0, 5),
(25, 'Mommy', NULL, 0, 5),
(26, '2 bobettes', NULL, 0, 4),
(27, '2 paires de chaussettes en mérinos', NULL, 0, 4),
(28, '1 pantalon de trecking', NULL, 0, 4),
(29, '1 paire de jeans', NULL, 0, 4),
(30, '1 coton ouaté', NULL, 0, 4),
(31, '1 coupe-vent', NULL, 0, 4),
(32, '1 paire de bottes de marche', NULL, 0, 4),
(33, '1 paire de mocassins', NULL, 0, 4),
(34, '2 t-shirts', NULL, 0, 4),
(35, '1 chandail en laine mérinos', NULL, 0, 4),
(36, '1 sleepin', NULL, 0, 4),
(37, '1 pyjama', NULL, 0, 4),
(38, 'lampe de poche', NULL, 0, 4),
(39, 'gamelle', NULL, 0, 4),
(40, 'couteau suisse', NULL, 0, 4),
(41, 'allumettes', NULL, 0, 4),
(42, 'réchaud', NULL, 0, 4),
(43, 'kraft dinner', NULL, 0, 4),
(44, '2 sachets repas', NULL, 0, 4),
(45, '2 sachets de soupe', NULL, 0, 4),
(46, '1 pain tranché', NULL, 0, 4),
(47, '1 sac de mélange du randonneur', NULL, 0, 4),
(48, 'café', NULL, 0, 4),
(49, 'cafetière', NULL, 0, 4),
(50, '1 boite de sardines', NULL, 0, 4),
(51, '1 pot de beurre de peanut', NULL, 0, 4),
(52, '1 ouvre-boite', NULL, 0, 4),
(53, 'un jeu de tric trac', NULL, 0, 4),
(54, 'un cherche-étoiles', NULL, 0, 4),
(55, 'Les Quatre cents coups', NULL, 0, 5),
(56, 'une boussole', NULL, 0, 4),
(57, 'Intégration les formulaires ', '2019-11-10 00:00:00', 0, 2),
(58, 'Faire les esquisses fonctionnelles', '2019-11-15 00:00:00', 0, 2),
(59, 'Examen de programmation', '2019-12-06 00:00:00', 1, 2),
(60, 'amiral de bateau-lavoir', NULL, 0, 3),
(61, 'amphitryon', NULL, 0, 3),
(62, 'anacoluthe', NULL, 0, 3),
(63, 'analphabète diplômé', NULL, 0, 3),
(64, 'animal', NULL, 0, 3),
(65, 'anthropophage', NULL, 0, 3),
(66, 'anthropopithèque', NULL, 0, 3),
(67, 'apprenti-dictateur à la noix de coco', NULL, 0, 3),
(68, 'apophtegme', NULL, 0, 3),
(69, 'babouin', NULL, 0, 3),
(70, 'bachi-bouzouk de tonnerre de Brest', NULL, 0, 3),
(71, 'bande d''ectoplasmes', NULL, 0, 3),
(72, 'bande d''emplâtres', NULL, 0, 3),
(73, 'bande d''hurluberlus', NULL, 0, 3),
(74, 'bande de zapotèques', NULL, 0, 3),
(75, 'bayadère de carnaval', NULL, 0, 3),
(76, 'boit-sans-soif', NULL, 0, 3),
(77, 'bougre d''ectoplasme de moule à gaufres', NULL, 0, 3),
(78, 'bougre d''extrait de cornichon', NULL, 0, 3),
(79, 'bougre de faux jeton à la sauce tartare', NULL, 0, 3),
(80, 'bougre de papou des Carpathes', NULL, 0, 3),
(81, 'brontosaure', NULL, 0, 3),
(82, 'casse-pieds', NULL, 0, 3),
(83, 'catachrèse', NULL, 0, 3),
(84, 'cercopithèque', NULL, 0, 3),
(85, 'petit choléra', NULL, 0, 3),
(86, 'chouette mal empaillée', NULL, 0, 3),
(87, 'cloporte', NULL, 0, 3),
(88, 'cornichon de zouave', NULL, 0, 3),
(89, 'coupe-jarret', NULL, 0, 3),
(90, 'crème d''emplâtre à la graisse de hérisson', NULL, 0, 3),
(91, 'cromagnon', NULL, 0, 3),
(92, 'diable de zouave', NULL, 0, 3),
(93, 'doryphore', NULL, 0, 3),
(94, 'écornifleur', NULL, 0, 3),
(95, 'escogriffe', NULL, 0, 3),
(96, 'épouvantail', NULL, 0, 3),
(97, 'espèce de porc-épic mal embouché', NULL, 0, 3),
(98, 'grotesque polichinelle', NULL, 0, 3),
(99, 'gyroscope', NULL, 0, 3),
(100, 'mille milliards de mille sabords de tonnerre de Brest', NULL, 0, 3),
(101, 'L''alchimiste de Paulo Coelho', '2050-12-31 00:00:00', 0, 7),
(102, 'Alice au pays des merveilles de Lewis Carroll', '2050-12-31 00:00:00', 0, 7),
(103, 'La ferme des animaux de George Orwell', '2050-12-31 00:00:00', 0, 7),
(104, 'Anna Karenina de Léon Tolstoï', '2050-12-31 00:00:00', 0, 7),
(105, 'Une amie d''enfer de Jacqueline Wilson', '2050-12-31 00:00:00', 0, 7),
(106, 'La plage de Alex Garland', '2050-12-31 00:00:00', 0, 7),
(107, 'Le meilleur des mondes de Aldous Huxley', '2050-12-31 00:00:00', 0, 7),
(108, 'La  couleur pourpre de Alice Walker', '2050-12-31 00:00:00', 0, 7),
(109, 'Le Comte de Monte-Cristo de Alexandre Dumas', '2050-12-31 00:00:00', 0, 7),
(110, 'Crime et Châtiment de Fiodor Dostoïevski', '2050-12-31 00:00:00', 0, 7),
(111, 'David Copperfield de Charles Dickens', '2050-12-31 00:00:00', 0, 7),
(112, 'Dune de Frank Herbert', '2050-12-31 00:00:00', 0, 7),
(113, 'Emma de Jane Austen', '2050-12-31 00:00:00', 0, 7),
(114, 'Frankenstein de Mary Shelley', '2050-12-31 00:00:00', 0, 7),
(115, 'Le parrain de Mario Puzo', '2050-12-31 00:00:00', 0, 7),
(116, 'Le dieu des petites choses de Arundhati Roy', '2050-12-31 00:00:00', 0, 7),
(117, 'Autant en emporte le vent de Margaret Mitchell', '2050-12-31 00:00:00', 0, 7),
(118, 'Jonathan Livingstone le goéland de Richard Bach', '2050-12-31 00:00:00', 0, 7),
(119, 'Macchu Pichu, Pérou', NULL, 0, 6),
(120, 'Taj Mahal, Agra, Inde', NULL, 0, 6),
(121, 'Uluru, Australie', NULL, 0, 6),
(122, 'Le pont de Londres', NULL, 0, 6),
(123, 'Le grand Canyon en Arizona', NULL, 0, 6),
(124, 'Stonhenge, Angleterre', NULL, 0, 6),
(125, 'Les îles Galapagos', NULL, 0, 6),
(126, 'L''Île de Pâques, Chili', NULL, 0, 6),
(127, 'Chichen Itza, Mexique', NULL, 0, 6),
(128, 'La Patagonie en Argentine', NULL, 0, 6),
(129, 'Château Frontenac à Québec', NULL, 0, 6),
(130, 'La grande pyramide de Gizeh en Égypte', NULL, 0, 6),
(131, 'Persépolis en Iran', NULL, 0, 6),
(132, 'La place rouge à Moscou', NULL, 0, 6),
(133, 'Le Mont Fuji au Japon', NULL, 0, 6),
(134, 'Les misérables de Victor Hugo', '2050-12-31 00:00:00', 0, 6),
(135, 'Le petit prince de Antoine De Saint-Exupery', '2050-12-31 00:00:00', 0, 7),
(136, 'Lolita de Vladimir Nabokov', '2050-12-31 00:00:00', 0, 7),
(137, 'Le seigneur des anneaux de J.R.R. Tolkien', '2050-12-31 00:00:00', 0, 7),
(138, 'L''amour au temps du choléra de Gabriel García Márquez', '2050-12-31 00:00:00', 0, 7),
(139, 'Au nom de la rose de Umberto Eco', '2050-12-31 00:00:00', 0, 7),
(140, 'Le vieil homme et la mer de Ernest Hemingway', '2050-12-31 00:00:00', 0, 7),
(141, 'Sur la route de Jack Kerouac', '2050-12-31 00:00:00', 0, 7),
(142, 'Le parfum de Patrick Süskind', '2050-12-31 00:00:00', 0, 7),
(143, 'Le portrait de Dorian Gray par Oscar Wilde', '2050-12-31 00:00:00', 0, 7),
(144, 'Ulysse de James Joyce', '2050-12-31 00:00:00', 0, 7),
(145, 'La guerre des mondes de H.G. Wells', '2050-12-31 00:00:00', 0, 7),
(146, 'Mémoire d''Adrien de Marguerite Yourcenar', '2050-12-31 00:00:00', 0, 7),
(147, 'Moderato cantabile de Marguerite Duras', '2050-12-31 00:00:00', 0, 7),
(148, 'L''insoutenable légèreté de l''être de Milan Kundera', '2050-12-31 00:00:00', 0, 7),
(149, 'Kamouraska de Anne Hébert', '2050-12-31 00:00:00', 0, 7),
(150, 'Prochain épisode de Hubert Aquin', '2050-12-31 00:00:00', 0, 7),
(151, 'Salut Galarneau de Jacques Godbout', '2050-12-31 00:00:00', 0, 7),
(152, 'Maria Chapdelaine de Louis Hémon', '2050-12-31 00:00:00', 0, 7),
(153, 'Lécume des jorus de Boris Vian', '2050-12-31 00:00:00', 0, 7),
(154, 'Volskwagen blues de Jacques Poulin', '2050-12-31 00:00:00', 0, 7),
(155, 'Le livre de Pi de Yann Martel', '2050-12-31 00:00:00', 0, 7),
(156, 'papaye', NULL, 0, 1),
(157, 'test-item-1', NULL, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `t_liste`
--

CREATE TABLE IF NOT EXISTS `t_liste` (
  `id_liste` int(10) unsigned NOT NULL,
  `nom_liste` varchar(50) NOT NULL COMMENT 'limite de 25 caractères',
  `id_couleur` tinyint(3) unsigned NOT NULL,
  `id_utilisateur` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `t_utilisateur`
--

CREATE TABLE IF NOT EXISTS `t_utilisateur` (
  `id_utilisateur` int(10) unsigned NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `courriel` varchar(50) NOT NULL,
  `mot_de_passe` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_utilisateur`
--

INSERT INTO `t_utilisateur` (`id_utilisateur`, `prenom`, `nom`, `courriel`, `mot_de_passe`) VALUES
(1, 'Tim', 'CSF', 'timcsf@gmail.com', 'm0td3pass3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_couleur`
--
ALTER TABLE `t_couleur`
  ADD PRIMARY KEY (`id_couleur`);

--
-- Indexes for table `t_item`
--
ALTER TABLE `t_item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_liste` (`id_liste`);

--
-- Indexes for table `t_liste`
--
ALTER TABLE `t_liste`
  ADD PRIMARY KEY (`id_liste`),
  ADD KEY `id_couleur` (`id_couleur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `t_utilisateur`
--
ALTER TABLE `t_utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_couleur`
--
ALTER TABLE `t_couleur`
  MODIFY `id_couleur` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `t_item`
--
ALTER TABLE `t_item`
  MODIFY `id_item` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=158;
--
-- AUTO_INCREMENT for table `t_liste`
--
ALTER TABLE `t_liste`
  MODIFY `id_liste` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `t_utilisateur`
--
ALTER TABLE `t_utilisateur`
  MODIFY `id_utilisateur` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_item`
--
ALTER TABLE `t_item`
  ADD CONSTRAINT `t_item_ibfk_1` FOREIGN KEY (`id_liste`) REFERENCES `t_liste` (`id_liste`) ON DELETE CASCADE;

--
-- Constraints for table `t_liste`
--
ALTER TABLE `t_liste`
  ADD CONSTRAINT `t_liste_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `t_utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_liste_ibfk_1` FOREIGN KEY (`id_couleur`) REFERENCES `t_couleur` (`id_couleur`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
