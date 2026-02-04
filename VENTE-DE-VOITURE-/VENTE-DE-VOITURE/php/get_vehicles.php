<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $pdo = getDBConnection();
    
    // Récupérer les paramètres de recherche
    $search = $_GET['search'] ?? '';
    $marque = $_GET['marque'] ?? '';
    $minPrice = $_GET['min_price'] ?? 0;
    $maxPrice = $_GET['max_price'] ?? 999999;
    $type_consommation = $_GET['type_consommation'] ?? '';
    $disponible_vente = isset($_GET['disponible_vente']) ? 1 : null;
    $disponible_location = isset($_GET['disponible_location']) ? 1 : null;
    $featured = isset($_GET['featured']) ? true : false;
    $limit = $_GET['limit'] ?? 50;
    $vehicleId = $_GET['id'] ?? null;
    
    // Construction de la requête SQL
    $sql = "SELECT v.*, u.nom as vendeur_nom, u.prenom as vendeur_prenom, u.email as vendeur_email, u.telephone as vendeur_telephone 
            FROM vehicules v 
            LEFT JOIN utilisateurs u ON v.vendeur_id = u.id 
            WHERE v.statut = 'disponible'";
    
    $params = [];
    
    // Recherche par ID spécifique
    if ($vehicleId) {
        $sql .= " AND v.id = ?";
        $params[] = $vehicleId;
    } else {
        // Filtres multiples
        if ($search) {
            $sql .= " AND (v.marque LIKE ? OR v.modele LIKE ? OR v.description LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if ($marque) {
            $sql .= " AND v.marque = ?";
            $params[] = $marque;
        }
        
        if ($type_consommation) {
            $sql .= " AND v.type_consommation = ?";
            $params[] = $type_consommation;
        }
        
        if ($disponible_vente !== null) {
            $sql .= " AND v.disponible_vente = 1 AND v.prix_vente BETWEEN ? AND ?";
            $params[] = $minPrice;
            $params[] = $maxPrice;
        }
        
        if ($disponible_location !== null) {
            $sql .= " AND v.disponible_location = 1";
        }
        
        // Si featured, prendre les plus récents
        if ($featured) {
            $sql .= " ORDER BY v.date_ajout DESC";
        } else {
            $sql .= " ORDER BY v.date_ajout DESC";
        }
        
        $sql .= " LIMIT ?";
        $params[] = (int)$limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $vehicles = $stmt->fetchAll();
    
    // Si recherche par ID, retourner un seul véhicule
    if ($vehicleId) {
        if (count($vehicles) > 0) {
            echo json_encode([
                'success' => true,
                'vehicle' => $vehicles[0]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Véhicule non trouvé'
            ]);
        }
    } else {
        // Retourner la liste de véhicules
        echo json_encode([
            'success' => true,
            'vehicles' => $vehicles,
            'count' => count($vehicles)
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
