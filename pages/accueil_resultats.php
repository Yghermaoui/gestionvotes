
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil des Résultats - DigiVote</title>
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

        .btn {
            padding: 12px 30px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
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
            max-height: 400px; /* Limite la hauteur de l'image */
            border-radius: 5px;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr; /* Une seule colonne sur mobile */
            }

            .content {
                text-align: center;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Texte de bienvenue -->
        <div class="content">
            <h1>Consulter les Résultats</h1>
            <p>
                Découvrez les résultats des élections en temps réel grâce à notre plateforme intuitive. Cliquez sur le bouton ci-dessous pour commencer !
            </p>
            <a href="resultats.php" class="btn">C'est parti !</a>
        </div>

        <!-- Image d'illustration -->
        <div class="image-container">
            <img src="../assets/images/resultats.png" alt="Illustration des résultats">
        </div>
    </div>
</body>
</html>
