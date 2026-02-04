-- Base de données pour le projet de vente et location de véhicules
-- Nom: location_achat_vehicules

CREATE DATABASE IF NOT EXISTS location_achat_vehicules CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE location_achat_vehicules;

-- Table des utilisateurs (clients et vendeurs)
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    type_utilisateur ENUM('client', 'vendeur') NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actif BOOLEAN DEFAULT TRUE
);

-- Table des véhicules / annonces
CREATE TABLE vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendeur_id INT NOT NULL,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    couleur VARCHAR(30) NOT NULL,
    vitesse_max INT NOT NULL COMMENT 'en km/h',
    kilometrage INT NOT NULL,
    consommation DECIMAL(4,2) NOT NULL COMMENT 'L/100km ou kWh/100km',
    type_consommation ENUM('essence', 'diesel', 'electrique', 'hybride') DEFAULT 'essence',
    prix_vente DECIMAL(10,2) DEFAULT NULL,
    prix_location_jour DECIMAL(8,2) DEFAULT NULL,
    disponible_vente BOOLEAN DEFAULT FALSE,
    disponible_location BOOLEAN DEFAULT FALSE,
    annee INT NOT NULL,
    description TEXT,
    image_principale VARCHAR(255),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    statut ENUM('disponible', 'vendu', 'loue', 'reserve') DEFAULT 'disponible',
    FOREIGN KEY (vendeur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des locations
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicule_id INT NOT NULL,
    client_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_cours', 'termine', 'annule') DEFAULT 'en_cours',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des transactions (achats)
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicule_id INT NOT NULL,
    acheteur_id INT NOT NULL,
    vendeur_id INT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'complete', 'annule') DEFAULT 'en_attente',
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (acheteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (vendeur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des favoris (optionnel)
CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    vehicule_id INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favori (utilisateur_id, vehicule_id)
);

-- Table des messages (optionnel)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT NOT NULL,
    destinataire_id INT NOT NULL,
    vehicule_id INT DEFAULT NULL,
    sujet VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE SET NULL
);

-- Insertion de données de test
-- Mot de passe: "password123" hashé avec PASSWORD_DEFAULT
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, type_utilisateur) VALUES
('Dupont', 'Jean', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0601020304', 'vendeur'),
('Martin', 'Sophie', 'sophie.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0605060708', 'client'),
('Bernard', 'Luc', 'luc.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0612131415', 'vendeur');

-- Insertion de véhicules de test
INSERT INTO vehicules (vendeur_id, marque, modele, couleur, vitesse_max, kilometrage, consommation, type_consommation, prix_vente, prix_location_jour, disponible_vente, disponible_location, annee, description) VALUES
(1, 'Peugeot', '208', 'Blanc', 180, 15000, 5.2, 'essence', 18000, 45, TRUE, TRUE, 2022, 'Peugeot 208 en excellent état, entretien complet réalisé.'),
(1, 'Renault', 'Clio', 'Noir', 175, 25000, 4.8, 'diesel', 15500, 40, TRUE, FALSE, 2021, 'Renault Clio 5 diesel, économique et fiable.'),
(3, 'Tesla', 'Model 3', 'Bleu', 225, 8000, 15.5, 'electrique', 42000, 120, TRUE, TRUE, 2023, 'Tesla Model 3 autopilot, autonomie 500km.'),
(3, 'BMW', 'Serie 3', 'Gris', 240, 45000, 6.5, 'diesel', 28000, 80, TRUE, TRUE, 2020, 'BMW Serie 3 tout équipée, GPS intégré.'),
(1, 'Volkswagen', 'Golf', 'Rouge', 210, 32000, 5.8, 'essence', 22000, 55, TRUE, FALSE, 2021, 'Volkswagen Golf GTI sportive.'),
(3, 'Audi', 'A4', 'Noir', 230, 18000, 5.5, 'hybride', 38000, 95, TRUE, TRUE, 2022, 'Audi A4 hybride, luxe et performance.');
