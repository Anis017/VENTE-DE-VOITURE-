<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et est un vendeur
if (!isLoggedIn() || !isVendeur()) {
    $_SESSION['error'] = "Vous devez être connecté en tant que vendeur pour ajouter un véhicule.";
    redirect('../Pages/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendeur_id = getUserId();
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $couleur = trim($_POST['couleur']);
    $annee = intval($_POST['annee']);
    $kilometrage = intval($_POST['kilometrage']);
    $vitesse_max = intval($_POST['vitesse_max']);
    $consommation = floatval($_POST['consommation']);
    $type_consommation = $_POST['type_consommation'];
    $description = trim($_POST['description']);
    $disponible_vente = isset($_POST['disponible_vente']) ? 1 : 0;
    $disponible_location = isset($_POST['disponible_location']) ? 1 : 0;
    $prix_vente = isset($_POST['prix_vente']) && $disponible_vente ? floatval($_POST['prix_vente']) : null;
    $prix_location_jour = isset($_POST['prix_location_jour']) && $disponible_location ? floatval($_POST['prix_location_jour']) : null;
    
    $errors = [];
    
    // Validation
    if (empty($marque) || empty($modele) || empty($couleur)) {
        $errors[] = "Tous les champs obligatoires doivent être remplis.";
    }
    
    if ($annee < 1990 || $annee > 2025) {
        $errors[] = "Année invalide.";
    }
    
    if (!$disponible_vente && !$disponible_location) {
        $errors[] = "Vous devez proposer le véhicule à la vente ou en location.";
    }
    
    // Gestion de l'upload d'image
    $image_path = null;
    if (isset($_FILES['vehicle-image']) && $_FILES['vehicle-image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/vehicles/';
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['vehicle-image']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('vehicle_') . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES['vehicle-image']['tmp_name'], $upload_path)) {
                $image_path = 'images/vehicles/' . $new_filename;
            } else {
                $errors[] = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $errors[] = "Type de fichier non autorisé pour l'image.";
        }
    }
    
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            $sql = "INSERT INTO vehicules (vendeur_id, marque, modele, couleur, annee, kilometrage, vitesse_max, 
                    consommation, type_consommation, description, disponible_vente, disponible_location, 
                    prix_vente, prix_location_jour, image_principale) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $vendeur_id, $marque, $modele, $couleur, $annee, $kilometrage, $vitesse_max,
                $consommation, $type_consommation, $description, $disponible_vente, $disponible_location,
                $prix_vente, $prix_location_jour, $image_path
            ]);
            
            $_SESSION['success_message'] = "Votre annonce a été publiée avec succès !";
            redirect('../Pages/profile.html?tab=mes-annonces');
            
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'ajout du véhicule : " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        redirect('../Pages/vente.html');
    }
}
?>
