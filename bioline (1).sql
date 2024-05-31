-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 06 mars 2023 à 13:05
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bioline`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie_produit`
--

CREATE TABLE `categorie_produit` (
  `id` int(11) NOT NULL,
  `description_cat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_cat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_produit`
--

INSERT INTO `categorie_produit` (`id`, `description_cat`, `nom_cat`) VALUES
(1, 'fruit', 'fruit');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_creation` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc_last` int(11) DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `user_id`, `date_creation`, `status`, `payment_method`, `payment_brand`, `cc_last`, `payment_status`) VALUES
(2, 1, '2023-03-04', 'Commande Annuléefffffbbbbbbbbbbbddbbbbbb', 'card', 'Visa', 4242, 'succeeded'),
(3, 1, '2023-03-04', 'Commande Initialisée', 'card', 'Visa', 4242, 'succeeded'),
(4, 1, '2023-03-04', 'Commande Confirmée', 'card', 'Visa', 4242, 'succeeded'),
(5, 1, '2023-03-04', 'Commande Confirmée', 'card', 'Visa', 4242, 'succeeded'),
(6, 1, '2023-03-04', 'Commande_Annulée', 'card', 'Visa', 4242, 'succeeded'),
(7, 1, '2023-03-05', 'Commande Confirmée', 'card', 'Visa', 4242, 'succeeded'),
(8, 1, '2023-03-05', 'Commande Confirmée', 'card', 'Visa', 4242, 'succeeded');

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

CREATE TABLE `detail_commande` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `qte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `detail_commande`
--

INSERT INTO `detail_commande` (`id`, `commande_id`, `produit_id`, `qte`) VALUES
(4, 2, 4, 2),
(5, 2, 3, 1),
(6, 3, 1, 2),
(7, 3, 3, 2),
(8, 3, 4, 2),
(9, 4, 1, 1),
(10, 4, 3, 1),
(11, 4, 4, 1),
(12, 5, 1, 1),
(13, 5, 3, 1),
(14, 6, 1, 1),
(15, 6, 3, 1),
(16, 6, 4, 1),
(17, 7, 1, 2),
(18, 7, 3, 2),
(19, 8, 1, 1),
(20, 8, 3, 1),
(21, 8, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230304012400', '2023-03-04 02:24:10', 5294);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom_cat_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categ` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix` double DEFAULT NULL,
  `taux_remise` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `img2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom_cat_id`, `nom`, `img`, `descr`, `categ`, `marque`, `model`, `prix`, `taux_remise`, `stock`, `img2`) VALUES
(1, 1, 'fraise', 'product-1.png', 'fruit', 'fruit', 'fruit', 'fruit', 25, 12, 15, NULL),
(3, 1, 'pomme', 'product-2.png', 'fruit', 'fruit', 'fruit', 'fruit', 35, 12, 15, NULL),
(4, 1, 'poire', 'product-3.png', 'fruit', 'fruit', 'fruit', 'fruit', 5, 12, 15, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`) VALUES
(1, 'sayda', 'bhl');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie_produit`
--
ALTER TABLE `categorie_produit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67DA76ED395` (`user_id`);

--
-- Index pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_98344FA682EA2E54` (`commande_id`),
  ADD KEY `IDX_98344FA6F347EFB` (`produit_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC2766623614` (`nom_cat_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie_produit`
--
ALTER TABLE `categorie_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD CONSTRAINT `FK_98344FA682EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_98344FA6F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC2766623614` FOREIGN KEY (`nom_cat_id`) REFERENCES `categorie_produit` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
