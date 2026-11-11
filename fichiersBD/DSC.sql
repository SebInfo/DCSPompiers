-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 11 nov. 2026 à 09:29
-- Version du serveur : 5.7.44
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `DSC`
--

-- --------------------------------------------------------

--
-- Structure de la table `Affectation`
--

CREATE TABLE `Affectation` (
  `Matricule` int(6) NOT NULL,
  `DateAff` date NOT NULL,
  `IdCaserne` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Affectation`
--

INSERT INTO `Affectation` (`Matricule`, `DateAff`, `IdCaserne`) VALUES
(0, '2025-10-31', 1),
(111111, '2025-10-31', 1),
(121332, '2025-10-31', 1),
(122131, '2025-10-31', 1),
(123456, '2022-10-20', 1),
(696969, '2025-10-31', 1),
(786572, '1997-02-12', 1),
(788099, '2025-10-31', 1),
(871892, '2025-10-31', 1),
(876543, '1972-08-11', 1),
(876543, '2022-10-20', 1),
(888888, '2025-10-31', 1),
(920372, '2022-10-03', 1),
(920379, '2022-10-03', 1),
(986995, '2005-05-14', 1),
(999999, '2025-10-31', 1),
(112233, '2025-10-31', 2),
(121334, '2025-10-31', 2),
(555514, '2025-10-31', 2),
(654352, '2010-06-12', 2),
(782312, '2015-05-10', 2),
(782312, '2017-08-17', 2),
(786572, '1987-10-12', 2),
(876543, '2022-11-12', 2),
(982726, '2012-08-10', 2),
(986995, '1984-03-10', 2),
(986995, '1980-02-12', 3),
(986995, '2001-07-12', 3),
(987654, '2025-10-31', 3),
(112255, '2025-10-31', 4);

-- --------------------------------------------------------

--
-- Structure de la table `Caserne`
--

CREATE TABLE `Caserne` (
  `idCaserne` int(11) NOT NULL,
  `NomCaserne` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Caserne`
--

INSERT INTO `Caserne` (`idCaserne`, `NomCaserne`) VALUES
(1, 'Ouessant'),
(2, 'Carcassonne'),
(3, 'lille'),
(4, 'Narbonne');

-- --------------------------------------------------------

--
-- Structure de la table `Employeur`
--

CREATE TABLE `Employeur` (
  `idEmployeur` int(11) NOT NULL,
  `NomEmployeur` varchar(40) NOT NULL,
  `PrenomEmployeur` varchar(40) NOT NULL,
  `TelEmployeur` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Employeur`
--

INSERT INTO `Employeur` (`idEmployeur`, `NomEmployeur`, `PrenomEmployeur`, `TelEmployeur`) VALUES
(3, 'VERSE', 'Alain', '0676542431'),
(4, 'NALINE', 'André', '0454245142'),
(5, 'ZOLE', 'Camille', '0676524156');

-- --------------------------------------------------------

--
-- Structure de la table `Engin`
--

CREATE TABLE `Engin` (
  `Numéro` tinyint(2) NOT NULL,
  `IdCaserne` int(11) NOT NULL,
  `IdTypeEngin` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Engin`
--

INSERT INTO `Engin` (`Numéro`, `IdCaserne`, `IdTypeEngin`) VALUES
(1, 1, 'EPA'),
(2, 1, 'EPA');

-- --------------------------------------------------------

--
-- Structure de la table `Exercer`
--

CREATE TABLE `Exercer` (
  `Matricule` int(6) NOT NULL,
  `IdHabilitation` int(11) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Exercer`
--

INSERT INTO `Exercer` (`Matricule`, `IdHabilitation`, `Date`) VALUES
(654352, 3, '2001-08-10'),
(782312, 3, '2019-10-12'),
(986995, 1, '1980-12-03'),
(986995, 2, '1982-08-12'),
(992312, 1, '2012-12-12'),
(992312, 2, '2020-12-12'),
(992312, 3, '2020-12-12'),
(992312, 4, '2020-12-12');

-- --------------------------------------------------------

--
-- Structure de la table `Grade`
--

CREATE TABLE `Grade` (
  `idGrade` int(11) NOT NULL,
  `LblGrade` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Grade`
--

INSERT INTO `Grade` (`idGrade`, `LblGrade`) VALUES
(1, 'auxiliaire'),
(2, 'sapeur 2ème classe'),
(3, 'sapeur 1ère classe'),
(4, 'caporal'),
(5, 'caporal-chef'),
(6, 'sergent'),
(7, 'sergent-chef'),
(8, 'adjudant'),
(9, 'adjudant-chef'),
(10, 'lieutenant'),
(11, 'capitaine'),
(12, 'commandant'),
(13, 'lieutenant-colonel');

-- --------------------------------------------------------

--
-- Structure de la table `Habilitation`
--

CREATE TABLE `Habilitation` (
  `idHabilitation` int(11) NOT NULL,
  `LblHabilitation` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Habilitation`
--

INSERT INTO `Habilitation` (`idHabilitation`, `LblHabilitation`) VALUES
(1, 'conducteur de véhicule de secours routier (VSR)'),
(2, 'chef d\'agrès fourgon pompe-tonne (FPT)'),
(3, 'équipier incendie'),
(4, 'équipier échelle pivotante automatique'),
(5, 'conducteur de véhicule fourgon pompe-tonne (FPT)'),
(6, 'conducteur échelle pivotante automatique');

-- --------------------------------------------------------

--
-- Structure de la table `NatureSInistre`
--

CREATE TABLE `NatureSInistre` (
  `idNatureSInistre` int(11) NOT NULL,
  `LblNatureSinistre` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table des sinistres';

--
-- Déchargement des données de la table `NatureSInistre`
--

INSERT INTO `NatureSInistre` (`idNatureSInistre`, `LblNatureSinistre`) VALUES
(1, 'feu dans un appartement'),
(2, 'feu de brousailles'),
(3, 'ascenseur bloqué');

-- --------------------------------------------------------

--
-- Structure de la table `Pompier`
--

CREATE TABLE `Pompier` (
  `Matricule` int(6) NOT NULL,
  `NomPompier` varchar(45) NOT NULL,
  `PrenomPompier` varchar(45) NOT NULL,
  `DateNaissPompier` date NOT NULL,
  `TelPompier` varchar(15) DEFAULT NULL,
  `SexePompier` enum('féminin','masculin') NOT NULL,
  `IdGrade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Pompier`
--

INSERT INTO `Pompier` (`Matricule`, `NomPompier`, `PrenomPompier`, `DateNaissPompier`, `TelPompier`, `SexePompier`, `IdGrade`) VALUES
(0, 'Sébastien', 'INION', '2025-10-04', '0765451422', 'masculin', 2),
(111111, 'toto', 'tata', '2025-10-05', '0765451422', 'féminin', 1),
(112233, 'Sébastien', 'INION', '2025-10-04', '0765451422', 'masculin', 13),
(112255, 'ALEX', 'JEAN', '2025-10-11', '0765451422', 'masculin', 2),
(121332, 'Sébastien', 'INION', '2025-10-04', '0765451422', 'masculin', 4),
(121334, 'Sbastien', 'INION', '2025-10-04', '0765451422', 'masculin', 13),
(122131, 'Sébastien', 'INION', '2025-10-06', '0765451422', 'masculin', 3),
(123456, 'SQZ', 'SZSZS', '2022-10-08', '2323233232', 'féminin', 2),
(555514, 'Sbastien', 'INION', '2025-10-04', '0765451422', 'masculin', 13),
(654352, 'Clette', 'lara', '1987-03-11', '0642786352', 'féminin', 3),
(696969, 'Sbastien', 'INION', '2025-10-04', '0765451422', 'féminin', 13),
(782312, 'Esur', 'janette', '1982-02-11', '0627371273', 'féminin', 3),
(786572, 'Abri', 'gauthier', '1972-05-12', '0676542532', 'masculin', 10),
(788099, 'Sbastien', 'INION', '2025-10-03', '0765451422', 'masculin', 1),
(871892, 'Sbastien', 'INION', '1982-08-12', '0765451422', 'masculin', 4),
(876543, 'TEST', 'TEST', '2022-10-18', '0872615253', 'masculin', 2),
(887769, 'Etaitsur', 'syLVain', '2022-09-30', '0676256352', 'masculin', 11),
(887799, 'Inion', 'sébastien', '2022-09-30', '0676256352', 'masculin', 11),
(888888, 'Sébastien', 'INION', '2025-10-04', '0765451422', 'féminin', 3),
(898989, 'Douard', 'JEAN', '1986-09-15', '0676256352', 'masculin', 9),
(920372, 'Lutin', 'Michel', '2022-09-30', '0676256352', 'masculin', 1),
(920379, 'Quenouille', 'Marc', '2022-09-30', '0676256352', 'masculin', 1),
(982726, 'Milou', 'Tintin', '1970-10-10', '99878998', 'masculin', 10),
(986995, 'Dumontel', 'Robert', '1969-10-10', '0298568542', 'masculin', 11),
(987654, 'Titia', 'Yayas', '2025-10-05', '0765451422', 'masculin', 2),
(992312, 'Balle', 'Jean', '1982-07-12', '0678652354', 'masculin', 2),
(999999, 'qsddqs', 'dqsdqsdqssd', '1972-05-14', '9999999999', 'masculin', 1);

-- --------------------------------------------------------

--
-- Structure de la table `Prevoir`
--

CREATE TABLE `Prevoir` (
  `idTypeEngin` varchar(4) NOT NULL,
  `IdNatSinistre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Prevoir`
--

INSERT INTO `Prevoir` (`idTypeEngin`, `IdNatSinistre`) VALUES
('EPA', 1),
('FPT', 1),
('VSAV', 1),
('VSAV', 2),
('VSAV', 3);

-- --------------------------------------------------------

--
-- Structure de la table `Professionnel`
--

CREATE TABLE `Professionnel` (
  `MatPro` int(6) NOT NULL,
  `DateEmbauche` date NOT NULL,
  `Indice` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Professionnel`
--

INSERT INTO `Professionnel` (`MatPro`, `DateEmbauche`, `Indice`) VALUES
(111111, '1213-09-12', 121),
(654352, '2010-03-03', 120),
(887769, '2022-09-30', 132),
(887799, '2022-09-30', 132),
(986995, '2000-05-04', 200);

-- --------------------------------------------------------

--
-- Structure de la table `Reclamer`
--

CREATE TABLE `Reclamer` (
  `idTypeEngin` varchar(4) NOT NULL,
  `IdHabilitation` int(11) NOT NULL,
  `Nbr` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Reclamer`
--

INSERT INTO `Reclamer` (`idTypeEngin`, `IdHabilitation`, `Nbr`) VALUES
('FPT', 2, 1),
('FPT', 3, 2),
('FPT', 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `TypeEngin`
--

CREATE TABLE `TypeEngin` (
  `idTypeEngin` varchar(4) NOT NULL,
  `LblEngin` varchar(45) DEFAULT NULL,
  `image` varchar(100) DEFAULT 'vehiculePompier.jpeg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `TypeEngin`
--

INSERT INTO `TypeEngin` (`idTypeEngin`, `LblEngin`, `image`) VALUES
('EPA', 'échelle Pivotante Automatique !', 'images.jpeg'),
('FPT', 'fourgon pompe-tonne', 'fpt.jpeg'),
('VSAV', 'véhicule de secours aux victimes', 'vsav.jpeg'),
('VSR', 'Véhicule Secours Routier', 'maxresdefault.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `Volontaire`
--

CREATE TABLE `Volontaire` (
  `MatVolontaire` int(6) NOT NULL,
  `Bip` varchar(3) NOT NULL,
  `IdEmployeur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Volontaire`
--

INSERT INTO `Volontaire` (`MatVolontaire`, `Bip`, `IdEmployeur`) VALUES
(0, '', 3),
(112233, '12', 3),
(112255, '112', 3),
(121332, '122', 3),
(121334, '12', 3),
(122131, '112', 3),
(123456, '', 3),
(555514, '12', 3),
(696969, '122', 3),
(782312, '176', 3),
(788099, '', 3),
(871892, '123', 3),
(876543, '', 3),
(888888, '', 3),
(898989, '652', 4),
(920372, '132', 4),
(920379, '132', 4),
(986995, '15', 3),
(987654, '122', 3),
(992312, '12', 3),
(999999, '111', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Affectation`
--
ALTER TABLE `Affectation`
  ADD PRIMARY KEY (`Matricule`,`DateAff`,`IdCaserne`),
  ADD KEY `Caserne_idx` (`IdCaserne`);

--
-- Index pour la table `Caserne`
--
ALTER TABLE `Caserne`
  ADD PRIMARY KEY (`idCaserne`);

--
-- Index pour la table `Employeur`
--
ALTER TABLE `Employeur`
  ADD PRIMARY KEY (`idEmployeur`);

--
-- Index pour la table `Engin`
--
ALTER TABLE `Engin`
  ADD PRIMARY KEY (`Numéro`,`IdCaserne`,`IdTypeEngin`),
  ADD KEY `Caserne_idx` (`IdCaserne`),
  ADD KEY `CaserneEngin_idx` (`IdCaserne`),
  ADD KEY `LeType_idx` (`IdTypeEngin`);

--
-- Index pour la table `Exercer`
--
ALTER TABLE `Exercer`
  ADD PRIMARY KEY (`Matricule`,`IdHabilitation`),
  ADD KEY `IdHabilation_idx` (`IdHabilitation`);

--
-- Index pour la table `Grade`
--
ALTER TABLE `Grade`
  ADD PRIMARY KEY (`idGrade`);

--
-- Index pour la table `Habilitation`
--
ALTER TABLE `Habilitation`
  ADD PRIMARY KEY (`idHabilitation`);

--
-- Index pour la table `NatureSInistre`
--
ALTER TABLE `NatureSInistre`
  ADD PRIMARY KEY (`idNatureSInistre`);

--
-- Index pour la table `Pompier`
--
ALTER TABLE `Pompier`
  ADD PRIMARY KEY (`Matricule`),
  ADD KEY `Grade_idx` (`IdGrade`);

--
-- Index pour la table `Prevoir`
--
ALTER TABLE `Prevoir`
  ADD PRIMARY KEY (`idTypeEngin`,`IdNatSinistre`),
  ADD KEY `Sinistre_idx` (`IdNatSinistre`);

--
-- Index pour la table `Professionnel`
--
ALTER TABLE `Professionnel`
  ADD PRIMARY KEY (`MatPro`);

--
-- Index pour la table `Reclamer`
--
ALTER TABLE `Reclamer`
  ADD PRIMARY KEY (`idTypeEngin`,`IdHabilitation`),
  ADD KEY `Habili_idx` (`IdHabilitation`);

--
-- Index pour la table `TypeEngin`
--
ALTER TABLE `TypeEngin`
  ADD PRIMARY KEY (`idTypeEngin`);

--
-- Index pour la table `Volontaire`
--
ALTER TABLE `Volontaire`
  ADD PRIMARY KEY (`MatVolontaire`),
  ADD KEY `Employeur_idx` (`IdEmployeur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Caserne`
--
ALTER TABLE `Caserne`
  MODIFY `idCaserne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `Employeur`
--
ALTER TABLE `Employeur`
  MODIFY `idEmployeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Grade`
--
ALTER TABLE `Grade`
  MODIFY `idGrade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `Habilitation`
--
ALTER TABLE `Habilitation`
  MODIFY `idHabilitation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `NatureSInistre`
--
ALTER TABLE `NatureSInistre`
  MODIFY `idNatureSInistre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Affectation`
--
ALTER TABLE `Affectation`
  ADD CONSTRAINT `CaserneToto` FOREIGN KEY (`IdCaserne`) REFERENCES `Caserne` (`idCaserne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Pompiers` FOREIGN KEY (`Matricule`) REFERENCES `Pompier` (`Matricule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Engin`
--
ALTER TABLE `Engin`
  ADD CONSTRAINT `CaserneEngin` FOREIGN KEY (`IdCaserne`) REFERENCES `Caserne` (`idCaserne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `LeType` FOREIGN KEY (`IdTypeEngin`) REFERENCES `TypeEngin` (`idTypeEngin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Exercer`
--
ALTER TABLE `Exercer`
  ADD CONSTRAINT `IdHabilation` FOREIGN KEY (`IdHabilitation`) REFERENCES `Habilitation` (`idHabilitation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Pompier3` FOREIGN KEY (`Matricule`) REFERENCES `Pompier` (`Matricule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Pompier`
--
ALTER TABLE `Pompier`
  ADD CONSTRAINT `Grade` FOREIGN KEY (`IdGrade`) REFERENCES `Grade` (`idGrade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Prevoir`
--
ALTER TABLE `Prevoir`
  ADD CONSTRAINT `Engins` FOREIGN KEY (`idTypeEngin`) REFERENCES `TypeEngin` (`idTypeEngin`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Sinistre` FOREIGN KEY (`IdNatSinistre`) REFERENCES `NatureSInistre` (`idNatureSInistre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Professionnel`
--
ALTER TABLE `Professionnel`
  ADD CONSTRAINT `Pompier` FOREIGN KEY (`MatPro`) REFERENCES `Pompier` (`Matricule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Reclamer`
--
ALTER TABLE `Reclamer`
  ADD CONSTRAINT `Engin` FOREIGN KEY (`idTypeEngin`) REFERENCES `TypeEngin` (`idTypeEngin`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Habili` FOREIGN KEY (`IdHabilitation`) REFERENCES `Habilitation` (`idHabilitation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Volontaire`
--
ALTER TABLE `Volontaire`
  ADD CONSTRAINT `Employeur` FOREIGN KEY (`IdEmployeur`) REFERENCES `Employeur` (`idEmployeur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Pompier2` FOREIGN KEY (`MatVolontaire`) REFERENCES `Pompier` (`Matricule`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
