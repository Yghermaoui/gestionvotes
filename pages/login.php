<?php
session_start(); // Démarrage de la session

// Inclusion du fichier de configuration de la base de données
require_once '../config/configbdd.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($username) && !empty($password)) {
        // Préparation de la requête SQL pour récupérer l'utilisateur
        $query = $pdo->prepare("SELECT * FROM user WHERE nom_user = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Comparaison du mot de passe
        if ($user && password_verify($password, $user['mot_de_passe'])) { // Utilisation de password_verify pour plus de sécurité
            // Stockage des informations de l'utilisateur en session
            $_SESSION['user_id'] = $user['iduser'];
            $_SESSION['user_name'] = $user['nom_user'];
            $_SESSION['role'] = $user['role_user'];

            // Redirection en fonction du rôle
            if ($user['role_user'] === 'admin') {  // Correction du nom de colonne (role_user au lieu de role)
                header("Location: accueil_admin.php");
            } elseif ($user['role_user'] === 'utilisateur') {
                header("Location: utilisateur.php");  // Corrigé cette ligne pour enlever le tiret en trop
            } else {
                $error = "Accès non autorisé.";
            }
            exit();
        } else {
            $error = "Identifiant ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Favicon -->
    <link rel="icon" href="../assets/images/digivote.png" type="image/png">
</head>
<body>
    <div class="login-container">
        <!-- Formulaire de connexion -->
        <form action="traitement_login.php" method="post" class="login-form">
            <h1>Connexion</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre identifiant" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div> 
            <button type="submit" class="btn-login">Se connecter</button>
        </form>
    </div>
</body>
</html>
