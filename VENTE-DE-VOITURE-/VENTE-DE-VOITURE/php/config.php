<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'location_achat_vehicules');
define('DB_USER', 'root'); // Modifier selon votre configuration
define('DB_PASS', ''); // Modifier selon votre configuration
define('DB_CHARSET', 'utf8mb4');

// Fonction de connexion à la base de données
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonctions utilitaires

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier si l'utilisateur est un vendeur
function isVendeur() {
    return isset($_SESSION['type_utilisateur']) && $_SESSION['type_utilisateur'] === 'vendeur';
}

// Obtenir l'ID de l'utilisateur connecté
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Obtenir le nom de l'utilisateur connecté
function getUserName() {
    return $_SESSION['user_nom'] ?? 'Invité';
}

// Rediriger vers une page
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sécuriser les sorties HTML
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Formater le prix
function formatPrice($price) {
    return number_format($price, 0, ',', ' ') . ' €';
}

// URL de base du site
define('BASE_URL', '/VENTE-DE-VOITURE/');
?>
