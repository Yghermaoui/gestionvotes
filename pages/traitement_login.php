<?php
session_start(); // Démarrage des sessions

// Inclusion de la configuration de la base de données
require_once '../config/configbdd.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($username) && !empty($password)) {
        // Requête pour récupérer l'utilisateur correspondant
        $query = $pdo->prepare("SELECT * FROM user WHERE nom_user = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Vérification des identifiants
        if ($user && $password === $user['mot_de_passe']) {
            // Stockage des informations dans la session
            $_SESSION['user_id'] = $user['idUser'];
            $_SESSION['user_name'] = $user['nom_user'];
            $_SESSION['role'] = $user['role_user'];

            // Redirection selon le rôle
            switch ($user['role_user']) {
                case 'admin':
                    header("Location: accueil_admin.php");
                    break;
                case 'utilisateur':
                    header("Location: utilisateur.php");
                    break;
                case 'administre':
                    header("Location: results.php");
                    break;
                default:
                    $error = "Rôle inconnu.";
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
    <title>Traitement</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Traitement</h1>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <p class="success-message">Données traitées avec succès !</p>
        <?php endif; ?>
        <a href="utilisateur.php" class="btn">Retour</a>
    </div>
</body>
</html>

