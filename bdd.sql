-- Créer la base de données
CREATE DATABASE IF NOT EXISTS boutique CHARACTER SET utf8mb4 ;
USE boutique;

-- Table utilisateurs
CREATE TABLE utilisateurs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('client','admin') DEFAULT 'client'
);

-- Table produits
CREATE TABLE produits (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  prix DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  image VARCHAR(255) NOT NULL,
  categorie VARCHAR(50) NOT NULL
);

-- Table commandes
CREATE TABLE commandes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2) NOT NULL,
  statut ENUM('en cours','validee') DEFAULT 'en cours',
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Table détails des commandes
CREATE TABLE details_commandes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  commande_id INT NOT NULL,
  produit_id INT NOT NULL,
  quantite INT NOT NULL,
  prix_unitaire DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (commande_id) REFERENCES commandes(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id)
);


