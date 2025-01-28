<?php

// Inclusion de la configuration de la base de données
require_once 'config/configbdd.php';

// Récupération des résultats depuis la base de données
$query = $pdo->prepare("SELECT * FROM resultats"); 
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - DigiVote</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1E90FF, #FF4500); /* Dégradé bleu à rouge */
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Deux colonnes : texte et image */
            align-items: center;
            width: 90%;
            max-width: 1200px;
            background: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .content {
            padding: 40px;
            text-align: left;
        }

        h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .options {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 30px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px); /* Légère élévation */
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.1); /* Fond clair */
            height: 100%;
        }

        .image-container img {
            max-width: 100%; /* L'image prend toute la largeur disponible */
            max-height: 300px;
            border: none; /* Supprime la bordure de l'image */
            margin: 0; /* Supprime la marge autour de l'image */
            display: block; /* Enlève l'espace blanc sous l'image */
            border-radius: 0; /* Pas de bordure arrondie */
            box-shadow: none; /* Supprime l'ombre */
        }

        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr; /* Une seule colonne sur mobile */
            }

            .content {
                text-align: center;
            }

            .options {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Contenu texte -->
        <div class="content">
            <h1>Bienvenue sur DigiVote</h1>
            <p>
                Explorez notre plateforme moderne de gestion des élections. 
                Consultez les résultats en temps réel ou connectez-vous pour gérer vos paramètres électoraux.
            </p> 
            <div class="options">
                <a href="pages/login.php" class="btn">Connexion</a>
                <a href="pages/accueil_resultats.php" class="btn">Voir les Résultats</a>
            </div>
        </div>

        <!-- Contenu image -->
        <div class="image-container">
            <img src="assets/images/41200.png" alt="Illustration des élections">
        </div>
    </div>
</body>
</html>
