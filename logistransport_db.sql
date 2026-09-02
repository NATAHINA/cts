-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 02 sep. 2026 à 13:26
-- Version du serveur : 5.7.44
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `logistransport_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `assurances`
--

DROP TABLE IF EXISTS `assurances`;
CREATE TABLE IF NOT EXISTS `assurances` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adresse` text,
  `site_web` varchar(255) DEFAULT NULL,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `assurances`
--

INSERT INTO `assurances` (`id`, `tenant_id`, `nom`, `description`, `telephone`, `email`, `adresse`, `site_web`, `statut`, `created_at`, `updated_at`) VALUES
(2, 1, 'Assurance ARO', '', '0380000212', '', '', '', 'actif', '2026-09-02 12:43:41', '2026-09-02 12:43:41');

-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

DROP TABLE IF EXISTS `circuits`;
CREATE TABLE IF NOT EXISTS `circuits` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(180) NOT NULL,
  `description` text,
  `duree_jours` int(10) UNSIGNED DEFAULT NULL,
  `prix` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_adulte` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_enfant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_groupe` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `disponibilite` varchar(50) DEFAULT NULL,
  `devise_id` int(10) UNSIGNED DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `circuits`
--

INSERT INTO `circuits` (`id`, `tenant_id`, `nom`, `description`, `duree_jours`, `prix`, `prix_adulte`, `prix_enfant`, `prix_groupe`, `fournisseur_id`, `disponibilite`, `devise_id`, `statut`, `created_at`, `updated_at`) VALUES
(1, 7, 'Circuit du Sud', '', 7, 152000.00, 120000.00, 80000.00, 100000.00, 1, 'disponible', 13, 'actif', '2026-09-02 12:46:54', '2026-09-02 12:46:54');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `type_client` varchar(30) DEFAULT NULL,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `entreprise` varchar(150) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `adresse` text,
  `ville` varchar(100) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `nationalite` varchar(100) DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `tenant_id`, `type_client`, `nom`, `prenom`, `entreprise`, `telephone`, `email`, `adresse`, `ville`, `pays`, `nationalite`, `statut`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 'Particulier', 'Dada', '', 'CP-Company', '0324588897', '', '', '', '', '', 'actif', '', '2026-09-02 12:34:56', '2026-09-02 12:38:04');

-- --------------------------------------------------------

--
-- Structure de la table `cotations`
--

DROP TABLE IF EXISTS `cotations`;
CREATE TABLE IF NOT EXISTS `cotations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(50) NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `demande_id` int(10) UNSIGNED DEFAULT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `nb_adultes` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `nb_enfants` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `nb_bebes` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `devise` varchar(10) DEFAULT NULL,
  `taux_change` decimal(15,6) NOT NULL DEFAULT '1.000000',
  `cout_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `marge_montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `marge_pourcentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `reduction_montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reduction_pourcentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `taxe_montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_par_personne` decimal(15,2) NOT NULL DEFAULT '0.00',
  `statut` varchar(30) NOT NULL DEFAULT 'brouillon',
  `date_validite` date DEFAULT NULL,
  `notes_client` text,
  `notes_interne` text,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `validated_by` int(10) UNSIGNED DEFAULT NULL,
  `validated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `client_id` (`client_id`),
  KEY `demande_id` (`demande_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cotations`
--

INSERT INTO `cotations` (`id`, `tenant_id`, `numero`, `client_id`, `demande_id`, `destination_id`, `date_depart`, `date_retour`, `nb_adultes`, `nb_enfants`, `nb_bebes`, `devise`, `taux_change`, `cout_total`, `marge_montant`, `marge_pourcentage`, `reduction_montant`, `reduction_pourcentage`, `taxe_montant`, `prix_total`, `prix_par_personne`, `statut`, `date_validite`, `notes_client`, `notes_interne`, `created_by`, `validated_by`, `validated_at`, `created_at`, `updated_at`) VALUES
(1, 7, 'DEV-2026-00001', 1, 1, 1, '2026-09-03', '2026-09-06', 4, 1, 0, 'MGA', 1.000000, 152000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 152000.00, 30400.00, 'acceptee', NULL, '', '', 8, NULL, NULL, '2026-09-02 12:58:23', '2026-09-02 13:03:57');

-- --------------------------------------------------------

--
-- Structure de la table `cotation_lignes`
--

DROP TABLE IF EXISTS `cotation_lignes`;
CREATE TABLE IF NOT EXISTS `cotation_lignes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `cotation_id` int(10) UNSIGNED NOT NULL,
  `type_prestation` varchar(50) DEFAULT NULL,
  `prestation_id` int(10) UNSIGNED DEFAULT NULL,
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `description` text,
  `quantite` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `cout_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cout_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `marge_pourcentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `marge_montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `devise` varchar(10) DEFAULT NULL,
  `ordre` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `cotation_id` (`cotation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cotation_lignes`
--

INSERT INTO `cotation_lignes` (`id`, `tenant_id`, `cotation_id`, `type_prestation`, `prestation_id`, `fournisseur_id`, `designation`, `description`, `quantite`, `cout_unitaire`, `cout_total`, `marge_pourcentage`, `marge_montant`, `prix_unitaire`, `prix_total`, `devise`, `ordre`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'circuit', 1, NULL, 'Circuit du Sud', '', 1, 152000.00, 152000.00, 0.00, 0.00, 152000.00, 152000.00, 'MGA', 1, '2026-09-02 12:58:23', '2026-09-02 12:58:23');

-- --------------------------------------------------------

--
-- Structure de la table `croisieres`
--

DROP TABLE IF EXISTS `croisieres`;
CREATE TABLE IF NOT EXISTS `croisieres` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `compagnie` varchar(150) DEFAULT NULL,
  `nom` varchar(180) NOT NULL,
  `itineraire` text,
  `duree_jours` int(10) UNSIGNED DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `prix` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_adulte` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_enfant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_groupe` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `disponibilite` varchar(50) DEFAULT NULL,
  `devise_id` int(10) UNSIGNED DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `numero` varchar(50) NOT NULL,
  `date_demande` date DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `nb_adultes` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `nb_enfants` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `nb_bebes` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `budget` decimal(15,2) DEFAULT NULL,
  `mode_tarif` enum('lignes','forfait') NOT NULL DEFAULT 'lignes',
  `prix_forfait` decimal(15,2) DEFAULT NULL,
  `devise` varchar(10) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'nouvelle',
  `notes_client` text,
  `notes_interne` text,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `client_id` (`client_id`),
  KEY `fk_demandes_destination` (`destination_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `tenant_id`, `client_id`, `destination_id`, `numero`, `date_demande`, `date_depart`, `date_retour`, `nb_adultes`, `nb_enfants`, `nb_bebes`, `budget`, `mode_tarif`, `prix_forfait`, `devise`, `source`, `statut`, `notes_client`, `notes_interne`, `created_by`, `created_at`, `updated_at`, `destination`) VALUES
(1, 7, 1, 1, 'DEM-2026-00001', '2026-09-02', '2026-09-03', '2026-09-06', 4, 1, 0, 250000.00, 'lignes', NULL, 'MGA', 'Téléphone', 'convertie', '', '', 8, '2026-09-02 12:35:24', '2026-09-02 12:58:23', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `demande_lignes`
--

DROP TABLE IF EXISTS `demande_lignes`;
CREATE TABLE IF NOT EXISTS `demande_lignes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `demande_id` int(10) UNSIGNED NOT NULL,
  `type_prestation` varchar(50) NOT NULL,
  `prestation_id` int(10) UNSIGNED DEFAULT NULL,
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `description` text,
  `quantite` decimal(10,2) NOT NULL DEFAULT '1.00',
  `cout_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cout_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `marge_pourcentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `marge_montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `devise` varchar(10) DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_demande` (`demande_id`),
  KEY `idx_tenant` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demande_lignes`
--

INSERT INTO `demande_lignes` (`id`, `tenant_id`, `demande_id`, `type_prestation`, `prestation_id`, `fournisseur_id`, `designation`, `description`, `quantite`, `cout_unitaire`, `cout_total`, `marge_pourcentage`, `marge_montant`, `prix_unitaire`, `prix_total`, `devise`, `ordre`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'circuit', 1, NULL, 'Circuit du Sud', '', 1.00, 152000.00, 152000.00, 0.00, 0.00, 152000.00, 152000.00, 'MGA', 1, '2026-09-02 12:58:03', '2026-09-02 12:58:03');

-- --------------------------------------------------------

--
-- Structure de la table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(150) NOT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `destinations`
--

INSERT INTO `destinations` (`id`, `tenant_id`, `nom`, `pays`, `region`, `statut`, `description`, `created_at`, `updated_at`) VALUES
(1, 7, 'Nosy Be', NULL, NULL, 'actif', NULL, '2026-09-02 12:35:04', '2026-09-02 12:35:04');

-- --------------------------------------------------------

--
-- Structure de la table `devises`
--

DROP TABLE IF EXISTS `devises`;
CREATE TABLE IF NOT EXISTS `devises` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(5) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `symbole` varchar(10) DEFAULT NULL,
  `taux_change` int(11) DEFAULT '1',
  `is_default` int(11) DEFAULT '0',
  `actif` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `FK_devises_tenants` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `devises`
--

INSERT INTO `devises` (`id`, `tenant_id`, `code`, `nom`, `symbole`, `taux_change`, `is_default`, `actif`) VALUES
(13, 7, 'MGA', 'Ariary', 'Ar', 1, 1, 1),
(14, 7, 'EUR', 'Euro', '€', 4800, 0, 1),
(15, 7, 'USD', 'Dollar US', '$', 4500, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `excursions`
--

DROP TABLE IF EXISTS `excursions`;
CREATE TABLE IF NOT EXISTS `excursions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `nom` varchar(180) NOT NULL,
  `description` text,
  `duree_heures` decimal(6,2) DEFAULT NULL,
  `prix` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_adulte` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_enfant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_groupe` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `disponibilite` varchar(50) DEFAULT NULL,
  `devise_id` int(10) UNSIGNED DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(50) NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `reservation_id` int(10) UNSIGNED DEFAULT NULL,
  `cotation_id` int(10) UNSIGNED DEFAULT NULL,
  `date_facture` date NOT NULL,
  `date_echeance` date DEFAULT NULL,
  `devise` varchar(10) NOT NULL DEFAULT 'MGA',
  `montant_ht` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant_tva` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant_ttc` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant_paye` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant_restant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `statut` enum('brouillon','emise','partiellement_payee','payee','annulee','en_retard') NOT NULL DEFAULT 'brouillon',
  `notes_client` text,
  `notes_interne` text,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_facture_tenant_numero` (`tenant_id`,`numero`),
  KEY `idx_facture_tenant` (`tenant_id`),
  KEY `idx_facture_client` (`client_id`),
  KEY `idx_facture_reservation` (`reservation_id`),
  KEY `idx_facture_statut` (`statut`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `tenant_id`, `numero`, `client_id`, `reservation_id`, `cotation_id`, `date_facture`, `date_echeance`, `devise`, `montant_ht`, `montant_tva`, `montant_ttc`, `montant_paye`, `montant_restant`, `statut`, `notes_client`, `notes_interne`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 7, 'FAC-2026-00001', 1, 1, 1, '2026-09-02', '2026-09-17', 'MGA', 152000.00, 0.00, 152000.00, 152000.00, 0.00, 'payee', '', NULL, 8, '2026-09-02 13:07:44', '2026-09-02 13:12:27');

-- --------------------------------------------------------

--
-- Structure de la table `facture_lignes`
--

DROP TABLE IF EXISTS `facture_lignes`;
CREATE TABLE IF NOT EXISTS `facture_lignes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `facture_id` int(10) UNSIGNED NOT NULL,
  `designation` varchar(255) NOT NULL,
  `description` text,
  `quantite` decimal(10,2) NOT NULL DEFAULT '1.00',
  `prix_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_facture_ligne` (`facture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `facture_lignes`
--

INSERT INTO `facture_lignes` (`id`, `tenant_id`, `facture_id`, `designation`, `description`, `quantite`, `prix_unitaire`, `montant`, `ordre`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'Circuit du Sud', '', 1.00, 152000.00, 152000.00, 1, '2026-09-02 13:07:44', '2026-09-02 13:07:44');

-- --------------------------------------------------------

--
-- Structure de la table `forfaits`
--

DROP TABLE IF EXISTS `forfaits`;
CREATE TABLE IF NOT EXISTS `forfaits` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `nom` varchar(180) NOT NULL,
  `description` text,
  `duree_jours` int(10) UNSIGNED DEFAULT NULL,
  `duree_nuits` int(10) UNSIGNED DEFAULT NULL,
  `prix` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_adulte` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_enfant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_groupe` decimal(15,2) NOT NULL DEFAULT '0.00',
  `devise_id` int(10) UNSIGNED DEFAULT NULL,
  `commission_pourcentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `disponibilite` varchar(50) DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `contact_nom` varchar(100) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `adresse` text,
  `ville` varchar(100) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `devise` varchar(10) DEFAULT NULL,
  `conditions_paiement` varchar(100) DEFAULT NULL,
  `delai_paiement` int(11) DEFAULT NULL,
  `commission_pourcentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `tenant_id`, `type`, `nom`, `contact_nom`, `telephone`, `email`, `adresse`, `ville`, `pays`, `devise`, `conditions_paiement`, `delai_paiement`, `commission_pourcentage`, `statut`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 'transport', 'DB Trans', 'Babag', '0357855542', '', '', '', '', 'MGA', '', NULL, 0.00, 'actif', '', '2026-09-02 12:46:12', '2026-09-02 12:46:12');

-- --------------------------------------------------------

--
-- Structure de la table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `fournisseur_id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(150) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `adresse` varchar(150) NOT NULL,
  `prix_nuit` decimal(10,2) NOT NULL,
  `prix_adulte` decimal(10,2) NOT NULL,
  `prix_enfant` decimal(10,2) NOT NULL,
  `prix_groupe` decimal(10,2) NOT NULL,
  `disponibilite` varchar(50) NOT NULL DEFAULT 'disponible',
  `devise_id` int(10) UNSIGNED NOT NULL,
  `statut` varchar(50) NOT NULL DEFAULT 'actif',
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-08-28-112802', 'App\\Database\\Migrations\\CreateTenantsTable', 'default', 'App', 1787923519, 1),
(2, '2026-08-28-112837', 'App\\Database\\Migrations\\CreateRolesAndUsersTables', 'default', 'App', 1787923519, 1),
(3, '2026-08-28-112918', 'App\\Database\\Migrations\\CreateDevisesTable', 'default', 'App', 1787923519, 1),
(4, '2026-08-28-112945', 'App\\Database\\Migrations\\CreateClientsTable', 'default', 'App', 1787923519, 1),
(5, '2026-08-28-113014', 'App\\Database\\Migrations\\CreateFournisseursTable', 'default', 'App', 1787923519, 1),
(6, '2026-08-28-113107', 'App\\Database\\Migrations\\CreateDestinationsTable', 'default', 'App', 1787923519, 1),
(7, '2026-08-28-113348', 'App\\Database\\Migrations\\CreateHotelsTable', 'default', 'App', 1787923519, 1),
(8, '2026-08-28-113919', 'App\\Database\\Migrations\\CreateVolsTable', 'default', 'App', 1787923519, 1),
(9, '2026-08-28-122121', 'App\\Database\\Migrations\\CreateExcursionsTable', 'default', 'App', 1787923519, 1),
(10, '2026-08-28-122204', 'App\\Database\\Migrations\\CreateTransfertsTable', 'default', 'App', 1787923519, 1),
(11, '2026-08-28-122240', 'App\\Database\\Migrations\\CreateCroisieresTable', 'default', 'App', 1787923519, 1),
(12, '2026-08-28-122313', 'App\\Database\\Migrations\\CreateRestaurantsTable', 'default', 'App', 1787923519, 1),
(13, '2026-08-28-122341', 'App\\Database\\Migrations\\CreateForfaitsTable', 'default', 'App', 1787923520, 1),
(14, '2026-08-28-122413', 'App\\Database\\Migrations\\CreateCircuitsTable', 'default', 'App', 1787923520, 1),
(15, '2026-08-28-122813', 'App\\Database\\Migrations\\CreateDemandesTable', 'default', 'App', 1787923520, 1),
(16, '2026-08-28-130653', 'App\\Database\\Migrations\\CreateCotationsTable', 'default', 'App', 1787923520, 1),
(17, '2026-08-28-130739', 'App\\Database\\Migrations\\CreateCotationLignesTable', 'default', 'App', 1787923520, 1),
(18, '2026-08-28-130808', 'App\\Database\\Migrations\\CreateReservationsTable', 'default', 'App', 1787923520, 1),
(19, '2026-08-28-130834', 'App\\Database\\Migrations\\CreateReservationLignesTable', 'default', 'App', 1787923520, 1),
(20, '2026-08-28-130902', 'App\\Database\\Migrations\\CreateFacturesTable', 'default', 'App', 1787923520, 1),
(21, '2026-08-28-130926', 'App\\Database\\Migrations\\CreatePaiementsTable', 'default', 'App', 1787923520, 1),
(22, '2026-08-28-130952', 'App\\Database\\Migrations\\CreatePasswordResetsTable', 'default', 'App', 1787923520, 1),
(23, '2026-08-28-140040', 'App\\Database\\Migrations\\CreateAssurancesTable', 'default', 'App', 1787925697, 2);

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(50) NOT NULL,
  `facture_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `date_paiement` date NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `devise` varchar(10) NOT NULL DEFAULT 'MGA',
  `mode_paiement` enum('especes','virement','cheque','carte','mobile_money','autre') NOT NULL DEFAULT 'virement',
  `reference_externe` varchar(100) DEFAULT NULL,
  `notes` text,
  `statut` enum('valide','annule') NOT NULL DEFAULT 'valide',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_paiement_tenant_numero` (`tenant_id`,`numero`),
  KEY `idx_paiement_tenant` (`tenant_id`),
  KEY `idx_paiement_facture` (`facture_id`),
  KEY `idx_paiement_date` (`date_paiement`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id`, `tenant_id`, `numero`, `facture_id`, `client_id`, `date_paiement`, `montant`, `devise`, `mode_paiement`, `reference_externe`, `notes`, `statut`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 7, 'PAY-2026-00001', 1, 1, '2026-09-02', 152000.00, 'MGA', 'especes', '', '', 'valide', 8, '2026-09-02 13:12:27', '2026-09-02 13:12:27');

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `code`, `libelle`, `module`, `action`, `description`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'Voir le tableau de bord', 'dashboard', 'view', 'Permet d\'accéder au tableau de bord.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(2, 'demandes.view', 'Voir les demandes', 'demandes', 'view', 'Voir les demandes.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(3, 'demandes.create', 'Créer une demande', 'demandes', 'create', 'Créer une demande.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(4, 'demandes.edit', 'Modifier une demande', 'demandes', 'edit', 'Modifier une demande.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(5, 'demandes.delete', 'Supprimer une demande', 'demandes', 'delete', 'Supprimer une demande.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(6, 'demandes.print', 'Imprimer une demande', 'demandes', 'print', 'Imprimer une demande.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(7, 'cotations.view', 'Voir les cotations', 'cotations', 'view', 'Voir les cotations.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(8, 'cotations.create', 'Créer une cotation', 'cotations', 'create', 'Créer une cotation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(9, 'cotations.edit', 'Modifier une cotation', 'cotations', 'edit', 'Modifier une cotation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(10, 'cotations.delete', 'Supprimer une cotation', 'cotations', 'delete', 'Supprimer une cotation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(11, 'cotations.print', 'Imprimer une cotation', 'cotations', 'print', 'Imprimer une cotation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(12, 'cotations.send', 'Envoyer une cotation', 'cotations', 'send', 'Envoyer une cotation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(13, 'reservations.view', 'Voir les réservations', 'reservations', 'view', 'Voir les réservations.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(14, 'reservations.create', 'Créer une réservation', 'reservations', 'create', 'Créer une réservation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(15, 'reservations.edit', 'Modifier une réservation', 'reservations', 'edit', 'Modifier une réservation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(16, 'reservations.delete', 'Supprimer une réservation', 'reservations', 'delete', 'Supprimer une réservation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(17, 'reservations.print', 'Imprimer une réservation', 'reservations', 'print', 'Imprimer une réservation.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(18, 'plannings.view', 'Voir les plannings', 'plannings', 'view', 'Voir les plannings.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(19, 'plannings.create', 'Créer un planning', 'plannings', 'create', 'Créer un planning.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(20, 'plannings.edit', 'Modifier un planning', 'plannings', 'edit', 'Modifier un planning.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(21, 'plannings.delete', 'Supprimer un planning', 'plannings', 'delete', 'Supprimer un planning.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(22, 'clients.view', 'Voir les clients', 'clients', 'view', 'Voir les clients.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(23, 'clients.create', 'Créer un client', 'clients', 'create', 'Créer un client.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(24, 'clients.edit', 'Modifier un client', 'clients', 'edit', 'Modifier un client.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(25, 'clients.delete', 'Supprimer un client', 'clients', 'delete', 'Supprimer un client.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(26, 'clients.print', 'Imprimer les informations d\'un client', 'clients', 'print', 'Imprimer les informations d\'un client.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(27, 'fournisseurs.view', 'Voir les fournisseurs', 'fournisseurs', 'view', 'Voir les fournisseurs.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(28, 'fournisseurs.create', 'Créer un fournisseur', 'fournisseurs', 'create', 'Créer un fournisseur.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(29, 'fournisseurs.edit', 'Modifier un fournisseur', 'fournisseurs', 'edit', 'Modifier un fournisseur.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(30, 'fournisseurs.delete', 'Supprimer un fournisseur', 'fournisseurs', 'delete', 'Supprimer un fournisseur.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(31, 'destinations.view', 'Voir les destinations', 'destinations', 'view', 'Voir les destinations.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(32, 'destinations.create', 'Créer une destination', 'destinations', 'create', 'Créer une destination.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(33, 'destinations.edit', 'Modifier une destination', 'destinations', 'edit', 'Modifier une destination.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(34, 'destinations.delete', 'Supprimer une destination', 'destinations', 'delete', 'Supprimer une destination.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(35, 'hotels.view', 'Voir les hôtels', 'hotels', 'view', 'Voir les hôtels.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(36, 'hotels.create', 'Créer un hôtel', 'hotels', 'create', 'Créer un hôtel.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(37, 'hotels.edit', 'Modifier un hôtel', 'hotels', 'edit', 'Modifier un hôtel.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(38, 'hotels.delete', 'Supprimer un hôtel', 'hotels', 'delete', 'Supprimer un hôtel.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(39, 'vols.view', 'Voir les vols', 'vols', 'view', 'Voir les vols.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(40, 'vols.create', 'Créer un vol', 'vols', 'create', 'Créer un vol.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(41, 'vols.edit', 'Modifier un vol', 'vols', 'edit', 'Modifier un vol.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(42, 'vols.delete', 'Supprimer un vol', 'vols', 'delete', 'Supprimer un vol.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(43, 'excursions.view', 'Voir les excursions', 'excursions', 'view', 'Voir les excursions.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(44, 'excursions.create', 'Créer une excursion', 'excursions', 'create', 'Créer une excursion.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(45, 'excursions.edit', 'Modifier une excursion', 'excursions', 'edit', 'Modifier une excursion.', '2026-09-02 11:03:36', '2026-09-02 11:03:36'),
(46, 'excursions.delete', 'Supprimer une excursion', 'excursions', 'delete', 'Supprimer une excursion.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(47, 'transferts.view', 'Voir les transferts', 'transferts', 'view', 'Voir les transferts.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(48, 'transferts.create', 'Créer un transfert', 'transferts', 'create', 'Créer un transfert.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(49, 'transferts.edit', 'Modifier un transfert', 'transferts', 'edit', 'Modifier un transfert.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(50, 'transferts.delete', 'Supprimer un transfert', 'transferts', 'delete', 'Supprimer un transfert.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(51, 'croisieres.view', 'Voir les croisières', 'croisieres', 'view', 'Voir les croisières.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(52, 'croisieres.create', 'Créer une croisière', 'croisieres', 'create', 'Créer une croisière.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(53, 'croisieres.edit', 'Modifier une croisière', 'croisieres', 'edit', 'Modifier une croisière.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(54, 'croisieres.delete', 'Supprimer une croisière', 'croisieres', 'delete', 'Supprimer une croisière.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(55, 'restaurants.view', 'Voir les restaurants', 'restaurants', 'view', 'Voir les restaurants.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(56, 'restaurants.create', 'Créer un restaurant', 'restaurants', 'create', 'Créer un restaurant.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(57, 'restaurants.edit', 'Modifier un restaurant', 'restaurants', 'edit', 'Modifier un restaurant.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(58, 'restaurants.delete', 'Supprimer un restaurant', 'restaurants', 'delete', 'Supprimer un restaurant.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(59, 'forfaits.view', 'Voir les forfaits', 'forfaits', 'view', 'Voir les forfaits.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(60, 'forfaits.create', 'Créer un forfait', 'forfaits', 'create', 'Créer un forfait.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(61, 'forfaits.edit', 'Modifier un forfait', 'forfaits', 'edit', 'Modifier un forfait.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(62, 'forfaits.delete', 'Supprimer un forfait', 'forfaits', 'delete', 'Supprimer un forfait.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(63, 'circuits.view', 'Voir les circuits', 'circuits', 'view', 'Voir les circuits.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(64, 'circuits.create', 'Créer un circuit', 'circuits', 'create', 'Créer un circuit.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(65, 'circuits.edit', 'Modifier un circuit', 'circuits', 'edit', 'Modifier un circuit.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(66, 'circuits.delete', 'Supprimer un circuit', 'circuits', 'delete', 'Supprimer un circuit.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(67, 'assurances.view', 'Voir les assurances', 'assurances', 'view', 'Voir les assurances.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(68, 'assurances.create', 'Créer une assurance', 'assurances', 'create', 'Créer une assurance.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(69, 'assurances.edit', 'Modifier une assurance', 'assurances', 'edit', 'Modifier une assurance.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(70, 'assurances.delete', 'Supprimer une assurance', 'assurances', 'delete', 'Supprimer une assurance.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(71, 'factures.view', 'Voir les factures', 'factures', 'view', 'Voir les factures.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(72, 'factures.create', 'Créer une facture', 'factures', 'create', 'Créer une facture.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(73, 'factures.edit', 'Modifier une facture', 'factures', 'edit', 'Modifier une facture.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(74, 'factures.delete', 'Supprimer une facture', 'factures', 'delete', 'Supprimer une facture.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(75, 'factures.print', 'Imprimer une facture', 'factures', 'print', 'Imprimer une facture.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(76, 'factures.export', 'Exporter les factures', 'factures', 'export', 'Exporter les factures.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(77, 'paiements.view', 'Voir les paiements', 'paiements', 'view', 'Voir les paiements.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(78, 'paiements.create', 'Enregistrer un paiement', 'paiements', 'create', 'Enregistrer un paiement.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(79, 'paiements.edit', 'Modifier un paiement', 'paiements', 'edit', 'Modifier un paiement.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(80, 'paiements.delete', 'Supprimer ou annuler un paiement', 'paiements', 'delete', 'Supprimer ou annuler un paiement.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(81, 'paiements.print', 'Imprimer un paiement', 'paiements', 'print', 'Imprimer un paiement.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(82, 'rapports.view', 'Voir les rapports', 'rapports', 'view', 'Voir les rapports.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(83, 'rapports.export', 'Exporter les rapports', 'rapports', 'export', 'Exporter les rapports.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(84, 'utilisateurs.view', 'Voir les utilisateurs', 'utilisateurs', 'view', 'Voir les utilisateurs.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(85, 'utilisateurs.create', 'Créer un utilisateur', 'utilisateurs', 'create', 'Créer un utilisateur.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(86, 'utilisateurs.edit', 'Modifier un utilisateur', 'utilisateurs', 'edit', 'Modifier un utilisateur.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(87, 'utilisateurs.delete', 'Supprimer un utilisateur', 'utilisateurs', 'delete', 'Supprimer un utilisateur.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(88, 'utilisateurs.permissions', 'Gérer les permissions', 'utilisateurs', 'permissions', 'Gérer les permissions.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(89, 'devises.view', 'Voir les devises', 'devises', 'view', 'Voir les devises.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(90, 'devises.create', 'Créer une devise', 'devises', 'create', 'Créer une devise.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(91, 'devises.edit', 'Modifier une devise', 'devises', 'edit', 'Modifier une devise.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(92, 'devises.delete', 'Supprimer une devise', 'devises', 'delete', 'Supprimer une devise.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(93, 'devises.default', 'Définir la devise par défaut', 'devises', 'default', 'Définir la devise par défaut.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(94, 'parametres.view', 'Voir les paramètres', 'parametres', 'view', 'Voir les paramètres.', '2026-09-02 11:03:37', '2026-09-02 11:03:37'),
(95, 'parametres.edit', 'Modifier les paramètres', 'parametres', 'edit', 'Modifier les paramètres.', '2026-09-02 11:03:37', '2026-09-02 11:03:37');

-- --------------------------------------------------------

--
-- Structure de la table `plannings_depart`
--

DROP TABLE IF EXISTS `plannings_depart`;
CREATE TABLE IF NOT EXISTS `plannings_depart` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `date_depart` date NOT NULL,
  `date_retour` date DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `heure_retour` time DEFAULT NULL,
  `capacite` int(11) NOT NULL DEFAULT '0',
  `places_vendues` int(11) NOT NULL DEFAULT '0',
  `prix` decimal(15,2) DEFAULT NULL,
  `devise` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MGA',
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planifie',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_planning_tenant` (`tenant_id`),
  KEY `idx_planning_destination` (`destination_id`),
  KEY `idx_planning_date_depart` (`date_depart`),
  KEY `idx_planning_statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(50) NOT NULL,
  `cotation_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `planning_id` int(10) UNSIGNED DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `heure_retour` time DEFAULT NULL,
  `nb_adultes` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `nb_enfants` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `nb_bebes` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `devise` varchar(10) DEFAULT NULL,
  `montant_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `statut` varchar(30) NOT NULL DEFAULT 'en_attente',
  `notes_client` text,
  `notes_interne` text,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `client_id` (`client_id`),
  KEY `cotation_id` (`cotation_id`),
  KEY `idx_reservations_planning` (`planning_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `tenant_id`, `numero`, `cotation_id`, `client_id`, `destination_id`, `planning_id`, `date_depart`, `heure_depart`, `date_retour`, `heure_retour`, `nb_adultes`, `nb_enfants`, `nb_bebes`, `devise`, `montant_total`, `statut`, `notes_client`, `notes_interne`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 7, 'RES-2026-00001', 1, 1, 1, NULL, '2026-09-03', NULL, '2026-09-06', NULL, 4, 1, 0, 'MGA', 152000.00, 'confirmee', '', '', 8, '2026-09-02 13:04:13', '2026-09-02 13:04:13');

-- --------------------------------------------------------

--
-- Structure de la table `reservation_lignes`
--

DROP TABLE IF EXISTS `reservation_lignes`;
CREATE TABLE IF NOT EXISTS `reservation_lignes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `reservation_id` int(10) UNSIGNED NOT NULL,
  `type_prestation` varchar(50) DEFAULT NULL,
  `prestation_id` int(10) UNSIGNED DEFAULT NULL,
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `description` text,
  `quantite` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `cout_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cout_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_unitaire` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `devise` varchar(10) DEFAULT NULL,
  `ordre` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `reservation_id` (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservation_lignes`
--

INSERT INTO `reservation_lignes` (`id`, `tenant_id`, `reservation_id`, `type_prestation`, `prestation_id`, `fournisseur_id`, `designation`, `description`, `quantite`, `cout_unitaire`, `cout_total`, `prix_unitaire`, `prix_total`, `devise`, `ordre`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'circuit', 1, NULL, 'Circuit du Sud', '', 1, 152000.00, 152000.00, 152000.00, 152000.00, 'MGA', 1, '2026-09-02 13:04:13', '2026-09-02 13:04:13');

-- --------------------------------------------------------

--
-- Structure de la table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `nom` varchar(180) NOT NULL,
  `type_cuisine` varchar(100) DEFAULT NULL,
  `adresse` text,
  `telephone` varchar(40) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `description` text,
  `prix_moyen` decimal(15,2) NOT NULL DEFAULT '0.00',
  `devise` varchar(5) NOT NULL DEFAULT 'MGA',
  `note` decimal(2,1) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tenant_code` (`tenant_id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `tenant_id`, `code`, `libelle`, `description`, `created_at`, `updated_at`) VALUES
(10, 7, 'admin', 'Administrateur', 'Administrateur de l’agence', '2026-09-02 11:04:45', '2026-09-02 11:04:45'),
(11, 7, 'commercial', 'Commercial', NULL, '2026-09-02 12:02:11', '2026-09-02 12:02:11');

-- --------------------------------------------------------

--
-- Structure de la table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int(11) UNSIGNED NOT NULL,
  `permission_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(9, 6),
(9, 7),
(9, 8),
(9, 9),
(9, 10),
(9, 11),
(9, 12),
(9, 13),
(9, 14),
(9, 15),
(9, 16),
(9, 17),
(9, 18),
(9, 19),
(9, 20),
(9, 21),
(9, 22),
(9, 23),
(9, 24),
(9, 25),
(9, 26),
(9, 27),
(9, 28),
(9, 29),
(9, 30),
(9, 31),
(9, 32),
(9, 33),
(9, 34),
(9, 35),
(9, 36),
(9, 37),
(9, 38),
(9, 39),
(9, 40),
(9, 41),
(9, 42),
(9, 43),
(9, 44),
(9, 45),
(9, 46),
(9, 47),
(9, 48),
(9, 49),
(9, 50),
(9, 51),
(9, 52),
(9, 53),
(9, 54),
(9, 55),
(9, 56),
(9, 57),
(9, 58),
(9, 59),
(9, 60),
(9, 61),
(9, 62),
(9, 63),
(9, 64),
(9, 65),
(9, 66),
(9, 67),
(9, 68),
(9, 69),
(9, 70),
(9, 71),
(9, 72),
(9, 73),
(9, 74),
(9, 75),
(9, 76),
(9, 77),
(9, 78),
(9, 79),
(9, 80),
(9, 81),
(9, 82),
(9, 83),
(9, 84),
(9, 85),
(9, 86),
(9, 87),
(9, 88),
(9, 89),
(9, 90),
(9, 91),
(9, 92),
(9, 93),
(9, 94),
(9, 95),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(10, 8),
(10, 9),
(10, 10),
(10, 11),
(10, 12),
(10, 13),
(10, 14),
(10, 15),
(10, 16),
(10, 17),
(10, 18),
(10, 19),
(10, 20),
(10, 21),
(10, 22),
(10, 23),
(10, 24),
(10, 25),
(10, 26),
(10, 27),
(10, 28),
(10, 29),
(10, 30),
(10, 31),
(10, 32),
(10, 33),
(10, 34),
(10, 35),
(10, 36),
(10, 37),
(10, 38),
(10, 39),
(10, 40),
(10, 41),
(10, 42),
(10, 43),
(10, 44),
(10, 45),
(10, 46),
(10, 47),
(10, 48),
(10, 49),
(10, 50),
(10, 51),
(10, 52),
(10, 53),
(10, 54),
(10, 55),
(10, 56),
(10, 57),
(10, 58),
(10, 59),
(10, 60),
(10, 61),
(10, 62),
(10, 63),
(10, 64),
(10, 65),
(10, 66),
(10, 67),
(10, 68),
(10, 69),
(10, 70),
(10, 71),
(10, 72),
(10, 73),
(10, 74),
(10, 75),
(10, 76),
(10, 77),
(10, 78),
(10, 79),
(10, 80),
(10, 81),
(10, 82),
(10, 83),
(10, 84),
(10, 85),
(10, 86),
(10, 87),
(10, 88),
(10, 89),
(10, 90),
(10, 91),
(10, 92),
(10, 93),
(10, 94),
(10, 95),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 6),
(11, 7),
(11, 8),
(11, 9),
(11, 10),
(11, 11),
(11, 12),
(11, 13),
(11, 14),
(11, 15),
(11, 17),
(11, 22),
(11, 23),
(11, 24),
(11, 26),
(11, 27),
(11, 31),
(11, 35),
(11, 39),
(11, 43),
(11, 47);

-- --------------------------------------------------------

--
-- Structure de la table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_agence` varchar(150) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `email_contact` varchar(150) DEFAULT NULL,
  `telephone` varchar(40) DEFAULT NULL,
  `adresse` text,
  `logo` varchar(255) DEFAULT NULL,
  `nif` varchar(50) DEFAULT NULL,
  `stat` varchar(50) DEFAULT NULL,
  `rcs` varchar(50) DEFAULT NULL,
  `site_web` varchar(180) DEFAULT NULL,
  `devise_defaut` varchar(5) NOT NULL DEFAULT 'MGA',
  `tva` decimal(5,2) NOT NULL DEFAULT '0.00',
  `prefixe_cotation` varchar(20) NOT NULL DEFAULT 'COT',
  `prefixe_reservation` varchar(20) NOT NULL DEFAULT 'RES',
  `prefixe_facture` varchar(20) NOT NULL DEFAULT 'FAC',
  `conditions_generales` text,
  `pied_page_document` text,
  `plan` varchar(50) NOT NULL DEFAULT 'basic',
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tenants`
--

INSERT INTO `tenants` (`id`, `nom_agence`, `slug`, `email_contact`, `telephone`, `adresse`, `logo`, `nif`, `stat`, `rcs`, `site_web`, `devise_defaut`, `tva`, `prefixe_cotation`, `prefixe_reservation`, `prefixe_facture`, `conditions_generales`, `pied_page_document`, `plan`, `statut`, `created_at`, `updated_at`) VALUES
(7, 'TONGA', 'tonga', 'natahina.rochaya@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MGA', 0.00, 'COT', 'RES', 'FAC', NULL, NULL, 'essai', 'actif', '2026-09-02 11:04:45', '2026-09-02 11:04:45');

-- --------------------------------------------------------

--
-- Structure de la table `transferts`
--

DROP TABLE IF EXISTS `transferts`;
CREATE TABLE IF NOT EXISTS `transferts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `nom` varchar(180) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `vehicule` varchar(100) DEFAULT NULL,
  `capacite` int(10) UNSIGNED DEFAULT NULL,
  `prix` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_adulte` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_enfant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `prix_groupe` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fournisseur_id` int(10) UNSIGNED DEFAULT NULL,
  `disponibilite` varchar(50) DEFAULT NULL,
  `devise_id` int(10) UNSIGNED DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(40) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `dernier_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_id_email` (`tenant_id`,`email`),
  KEY `tenant_id` (`tenant_id`),
  KEY `FK_users_roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `tenant_id`, `role_id`, `nom`, `prenom`, `email`, `telephone`, `password`, `actif`, `dernier_login`, `created_at`, `updated_at`) VALUES
(8, 7, 10, 'natahina', 'rochaya', 'natahina.rochaya@gmail.com', NULL, '$2y$10$4x0ycxAGeQhctPDgkyIMMO28rrAdMuKAxq.nfjGnKlUby9nrTMH4q', 1, '2026-09-02 13:15:43', '2026-09-02 11:04:45', '2026-09-02 13:15:43'),
(9, 7, 11, 'Jean', 'Bah', 'bah@gmail.com', NULL, '$2y$10$G6YQK1Ih8dxmMEZ7jSRHsupWFBVBEgosjkF67C0MWznsys.Kq3ppm', 1, '2026-09-02 13:16:47', '2026-09-02 12:17:59', '2026-09-02 13:16:47');

-- --------------------------------------------------------

--
-- Structure de la table `vols`
--

DROP TABLE IF EXISTS `vols`;
CREATE TABLE IF NOT EXISTS `vols` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `compagnie` varchar(100) NOT NULL,
  `num_vol` varchar(100) NOT NULL,
  `aeroport_depart` varchar(150) NOT NULL,
  `aeroport_arrivee` varchar(100) NOT NULL,
  `date_depart` date DEFAULT NULL,
  `date_arrivee` date DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `prix_adulte` decimal(10,2) NOT NULL,
  `prix_enfant` decimal(10,2) NOT NULL,
  `prix_groupe` decimal(10,2) NOT NULL,
  `id_fournisseur` int(10) UNSIGNED NOT NULL,
  `disponibilite` varchar(50) NOT NULL DEFAULT 'disponible',
  `devise_id` int(10) UNSIGNED NOT NULL,
  `statut` varchar(50) NOT NULL DEFAULT 'actif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cotation_lignes`
--
ALTER TABLE `cotation_lignes`
  ADD CONSTRAINT `FK_cotation_lignes_cotation_lignes` FOREIGN KEY (`cotation_id`) REFERENCES `cotation_lignes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_cotation_lignes_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `fk_demandes_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `demande_lignes`
--
ALTER TABLE `demande_lignes`
  ADD CONSTRAINT `fk_demande_lignes_demande` FOREIGN KEY (`demande_id`) REFERENCES `demandes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devises`
--
ALTER TABLE `devises`
  ADD CONSTRAINT `FK_devises_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `facture_lignes`
--
ALTER TABLE `facture_lignes`
  ADD CONSTRAINT `fk_facture_lignes_facture` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `fk_paiements_facture` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`);

--
-- Contraintes pour la table `plannings_depart`
--
ALTER TABLE `plannings_depart`
  ADD CONSTRAINT `fk_planning_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `FK_reservations_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservations_planning` FOREIGN KEY (`planning_id`) REFERENCES `plannings_depart` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservation_lignes`
--
ALTER TABLE `reservation_lignes`
  ADD CONSTRAINT `FK_reservation_lignes_reservations` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `FK_restaurants_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `FK_roles_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transferts`
--
ALTER TABLE `transferts`
  ADD CONSTRAINT `FK_transferts_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `FK_users_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
