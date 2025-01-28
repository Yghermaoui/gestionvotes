<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Admin - DigiVote</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
            grid-template-columns: 1fr 1fr; /* Deux colonnes */
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
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .options {
            display: flex;
            gap: 20px; /* Espacement entre les boutons */
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
            max-width: 100%;
            max-height: 300px;
            display: block;
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
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</h1>
            <p>
                Vous pouvez configurer les élections en cours ou reprendre une ancienne configuration.
            </p>
            <div class="options">
            <a href="admin.php" class="btn">Nouvelle configuration</a>
            <a href="consultation.php" class="btn">Voir une ancienne configuration</a>
            </div>
        </div>

        <!-- Contenu image -->
        <div class="image-container">
            <img src="../assets/images/choix.png" alt="Illustration admin">
        </div>
    </div>
</body>
</html>
