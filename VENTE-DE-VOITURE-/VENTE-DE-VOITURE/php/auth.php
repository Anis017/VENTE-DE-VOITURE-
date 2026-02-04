<?php
require_once '../php/config.php';

// Traitement de l'inscription
if (isset($_POST['register'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $type_utilisateur = $_POST['type_utilisateur'];
    
    $errors = [];
    
    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $errors[] = "Tous les champs obligatoires doivent être remplis.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "Cet email est déjà utilisé.";
            } else {
                // Hasher le mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insérer le nouvel utilisateur
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, type_utilisateur) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $hashed_password, $telephone, $type_utilisateur]);
                
                $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                redirect('../Pages/login.html');
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        redirect('../Pages/register.html');
    }
}

// Traitement de la connexion
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $errors = [];
    
    if (empty($email) || empty($password)) {
        $errors[] = "Email et mot de passe requis.";
    }
    
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND actif = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['prenom'] . ' ' . $user['nom'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['type_utilisateur'] = $user['type_utilisateur'];
                
                redirect('../Pages/home.html');
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de connexion : " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect('../Pages/login.html');
    }
}

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    redirect('../index.html');
}
?>
