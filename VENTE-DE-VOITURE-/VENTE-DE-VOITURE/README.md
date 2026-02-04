# AutoMarket - Plateforme de Vente et Location de Véhicules

## 📋 Description du Projet

AutoMarket est une plateforme web complète permettant la mise en relation entre vendeurs et acheteurs/locataires dans le domaine automobile. Le projet a été développé dans le cadre du TD Programmation Web (Année universitaire 2025-2026) en suivant strictement le cahier des charges fourni.

## 🎯 Fonctionnalités Principales

### Pour les Clients
- ✅ Consultation et recherche de véhicules
- ✅ Recherche avancée multicritères (marque, prix, kilométrage, carburant, etc.)
- ✅ Tri des résultats (prix, kilométrage, année, date)
- ✅ Comparaison côte-à-côte de 2 à 4 véhicules
- ✅ Système de favoris
- ✅ Processus d'achat sécurisé
- ✅ Système de location avec calcul automatique du prix

### Pour les Vendeurs
- ✅ Ajout d'annonces (vente et/ou location)
- ✅ Modification et suppression d'annonces
- ✅ Gestion des annonces depuis l'espace personnel
- ✅ Upload d'images de véhicules

### Fonctionnalités Générales
- ✅ Inscription (client ou vendeur)
- ✅ Connexion/Déconnexion sécurisée
- ✅ Interface responsive (mobile, tablette, desktop)
- ✅ Barre de navigation fixe
- ✅ Système de recherche instantanée
- ✅ Pages de détails complètes pour chaque véhicule

## 🛠️ Technologies Utilisées

### Front-end
- **HTML5** - Structure des pages
- **CSS3** - Styles et mise en page responsive
- **JavaScript (Vanilla)** - Interactions côté client

### Back-end
- **PHP 7.4+** - Logique serveur
- **PDO** - Accès sécurisé à la base de données

### Base de données
- **MySQL 5.7+** - Stockage des données

## 📁 Structure du Projet

```
VENTE-DE-VOITURE/
│
├── index.html              # Page d'accueil
├── database.sql            # Script de création de la BDD
│
├── css/
│   └── style.css          # Feuille de styles principale
│
├── js/
│   └── main.js            # JavaScript principal
│
├── php/
│   ├── config.php         # Configuration BDD et fonctions utilitaires
│   ├── auth.php           # Gestion authentification
│   ├── add_vehicle.php    # Ajout de véhicules
│   └── get_vehicles.php   # Récupération de véhicules
│
├── images/
│   └── vehicles/          # Images des véhicules (créé automatiquement)
│
└── Pages/
    ├── Catalogue.html     # Catalogue avec filtres
    ├── detailCar.html     # Détails d'un véhicule
    ├── login.html         # Connexion
    ├── register.html      # Inscription
    ├── vente.html         # Ajout d'annonce
    ├── location.html      # Location de véhicule
    ├── paiement.html      # Page de paiement
    ├── profile.html       # Profil utilisateur
    ├── Contact.html       # Contact
    ├── search.html        # Recherche
    ├── fichedescriptive.html  # Comparaison
    └── ... (autres pages)
```

## 🚀 Installation

### Prérequis
- **XAMPP**, **WAMP**, **MAMP** ou équivalent (Apache + MySQL + PHP)
- **PHP 7.4** ou supérieur
- **MySQL 5.7** ou supérieur
- Navigateur web moderne (Chrome, Firefox, Safari, Edge)

### Étapes d'Installation

#### 1. Cloner ou télécharger le projet
```bash
# Placer le dossier VENTE-DE-VOITURE dans le répertoire htdocs (XAMPP)
# ou www (WAMP)
```

#### 2. Créer la base de données
1. Ouvrir phpMyAdmin (`http://localhost/phpmyadmin`)
2. Créer une nouvelle base de données nommée `location_achat_vehicules`
3. Importer le fichier `database.sql`
   - Aller dans l'onglet "Importer"
   - Sélectionner le fichier `database.sql`
   - Cliquer sur "Exécuter"

#### 3. Configurer la connexion à la base de données
Ouvrir le fichier `php/config.php` et modifier si nécessaire :

```php
define('DB_HOST', 'localhost');      // Hôte de la BDD
define('DB_NAME', 'location_achat_vehicules');  // Nom de la BDD
define('DB_USER', 'root');           // Utilisateur (par défaut: root)
define('DB_PASS', '');               // Mot de passe (vide par défaut)
```

#### 4. Créer le dossier pour les images
```bash
# Créer le dossier images/vehicles/ s'il n'existe pas
mkdir -p images/vehicles
chmod 777 images/vehicles  # Linux/Mac
```

#### 5. Démarrer les serveurs
- Démarrer Apache et MySQL depuis le panneau de contrôle XAMPP/WAMP

#### 6. Accéder à l'application
Ouvrir le navigateur et aller sur :
```
http://localhost/VENTE-DE-VOITURE/
```

## 👤 Comptes de Test

Des comptes de test ont été créés automatiquement lors de l'import de la base de données :

### Compte Vendeur
- **Email**: jean.dupont@email.com
- **Mot de passe**: password123

### Compte Client
- **Email**: sophie.martin@email.com
- **Mot de passe**: password123

## 📊 Schéma de la Base de Données

### Tables Principales

#### `utilisateurs`
- Stocke les informations des clients et vendeurs
- Champs: id, nom, prenom, email, mot_de_passe, telephone, type_utilisateur, date_inscription, actif

#### `vehicules`
- Stocke toutes les annonces de véhicules
- Champs: id, vendeur_id, marque, modele, couleur, vitesse_max, kilometrage, consommation, type_consommation, prix_vente, prix_location_jour, disponible_vente, disponible_location, annee, description, image_principale, statut

#### `locations`
- Enregistre les locations en cours et l'historique
- Champs: id, vehicule_id, client_id, date_debut, date_fin, prix_total, statut

#### `transactions`
- Enregistre les achats de véhicules
- Champs: id, vehicule_id, acheteur_id, vendeur_id, prix, date_transaction, statut

#### `favoris` (optionnel)
- Système de favoris pour les utilisateurs
- Champs: id, utilisateur_id, vehicule_id, date_ajout

#### `messages` (optionnel)
- Messagerie entre utilisateurs
- Champs: id, expediteur_id, destinataire_id, vehicule_id, sujet, message, lu, date_envoi

## 🎨 Caractéristiques de Design

- **Design moderne et épuré** avec palette de couleurs cohérente
- **Totalement responsive** - fonctionne sur mobile, tablette et desktop
- **Interface intuitive** avec navigation claire
- **Animations fluides** pour une meilleure expérience utilisateur
- **Indicateurs visuels** (badges, icônes, couleurs)
- **Formulaires validés** côté client et serveur

## 🔒 Sécurité

- ✅ Mots de passe hashés avec `password_hash()` (bcrypt)
- ✅ Requêtes préparées (PDO) pour prévenir les injections SQL
- ✅ Validation des données côté serveur
- ✅ Protection CSRF avec sessions PHP
- ✅ Échappement des sorties HTML pour prévenir les attaques XSS
- ✅ Validation des types de fichiers uploadés

## 📱 Pages Disponibles

### Pages Publiques
- Accueil (index.html)
- Catalogue avec filtres (Catalogue.html)
- Détails véhicule (detailCar.html)
- Recherche (search.html)
- Comparaison (fichedescriptive.html)
- Contact (Contact.html)
- Comment ça marche (fonc.html)
- Avis clients (Avis.html)

### Pages Authentification
- Connexion (login.html)
- Inscription (register.html)
- Mot de passe oublié (passwordchange.html)
- Déconnexion (logout.html)

### Pages Utilisateur Connecté
- Profil (profile.html)
- Mes annonces (dans profile.html)
- Mes favoris (dans profile.html)
- Historique (dans profile.html)

### Pages Vendeur
- Ajouter une annonce (vente.html)
- Gérer les annonces (profile.html)

### Pages Transaction
- Location (location.html)
- Paiement (paiement.html)
- Confirmation (venteConfirme.html)

## 🔧 Fonctions JavaScript Principales

### Gestion des Comparaisons
```javascript
vehicleApp.addToComparison(vehicleId)    // Ajouter à la comparaison
vehicleApp.removeFromComparison(vehicleId) // Retirer de la comparaison
```

### Gestion des Favoris
```javascript
vehicleApp.toggleFavorite(vehicleId)     // Ajouter/retirer des favoris
```

### Utilitaires
```javascript
vehicleApp.formatPrice(price)            // Formater les prix
vehicleApp.showAlert(message, type)      // Afficher des alertes
vehicleApp.validateForm(formId)          // Valider un formulaire
vehicleApp.sortVehicles(criteria)        // Trier les véhicules
```

## 📞 Support et Contact

Pour toute question ou problème concernant le projet, veuillez contacter l'équipe de développement.

## 📝 Notes Importantes

1. **Images par défaut** : Si aucune image n'est uploadée, une image par défaut est utilisée
2. **Sessions PHP** : Assurez-vous que les sessions PHP sont correctement configurées
3. **Permissions** : Le dossier `images/vehicles/` doit avoir les permissions d'écriture
4. **URL rewriting** : Aucune configuration Apache spéciale n'est requise
5. **Compatibilité** : Testé sur Chrome, Firefox, Safari et Edge

## 🚦 Statuts du Projet

- ✅ Toutes les fonctionnalités du cahier des charges sont implémentées
- ✅ Base de données complète avec données de test
- ✅ Interface utilisateur responsive et moderne
- ✅ Système d'authentification sécurisé
- ✅ Gestion complète des véhicules (CRUD)
- ✅ Système de recherche et filtres avancés
- ✅ Comparaison de véhicules
- ✅ Processus d'achat et de location

## 📅 Année Universitaire

**2025-2026** - TD Programmation Web

## 📄 Licence

Ce projet a été développé dans un cadre académique pour le TD Programmation Web.

---

**Développé avec ❤️ pour le projet TD Programmation Web**
