<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'utilisateur') {
    header("Location: login.php");
    exit();
}

require_once '../config/configbdd.php';

// Récupération des bureaux de vote
$bureaux_query = $pdo->query("SELECT id_bureau, nom_bureau FROM bureaux");
$bureaux = $bureaux_query->fetchAll(PDO::FETCH_ASSOC);

// Récupération des candidats
$candidats_query = $pdo->query("SELECT id_candidat, nom_candidat, prenom_candidat, parti FROM candidat");
$candidats = $candidats_query->fetchAll(PDO::FETCH_ASSOC);

// Vérification et ajout des candidats pour votes blancs et nuls si nécessaire
if (empty($candidats)) {
    $pdo->exec("INSERT INTO candidat (id_candidat, nom_candidat, prenom_candidat, parti) 
                VALUES (0, 'Vote Blanc', '', 'Vote Blanc'), (-1, 'Vote Nul', '', 'Vote Nul')");
    $candidats_query = $pdo->query("SELECT id_candidat, nom_candidat, prenom_candidat, parti FROM candidat");
    $candidats = $candidats_query->fetchAll(PDO::FETCH_ASSOC);
}

$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des entrées utilisateur
    $id_bureau = intval($_POST['id_bureau'] ?? 0);
    $resultats = $_POST['resultats'] ?? [];
    $votes_blancs = intval($_POST['votes_blancs'] ?? 0);
    $votes_nuls = intval($_POST['votes_nuls'] ?? 0);

    // Vérification de l'existence du bureau
    $query_bureau = $pdo->prepare("SELECT nb_inscrits FROM bureaux WHERE id_bureau = :id_bureau");
    $query_bureau->execute([':id_bureau' => $id_bureau]);
    $bureau = $query_bureau->fetch(PDO::FETCH_ASSOC);

    if ($bureau) {
        $nb_inscrits = intval($bureau['nb_inscrits']);
        $total_voix = array_sum(array_map('intval', $resultats)) + $votes_blancs + $votes_nuls;

        if ($total_voix > $nb_inscrits) {
            $message = "Erreur : Le nombre total de voix dépasse le nombre d'inscrits dans le bureau.";
        } else {
            try {
                // Début de la transaction
                $pdo->beginTransaction();

                // Insertion ou mise à jour des résultats pour chaque candidat
                foreach ($resultats as $id_candidat => $nombre_voix) {
                    $nombre_voix = intval($nombre_voix);
                    $query_resultat = $pdo->prepare("
                        INSERT INTO resultats (id_bureau, id_candidat, nombre_voix)
                        VALUES (:id_bureau, :id_candidat, :nombre_voix)
                        ON DUPLICATE KEY UPDATE nombre_voix = :nombre_voix
                    ");
                    $query_resultat->execute([
                        ':id_bureau' => $id_bureau,
                        ':id_candidat' => $id_candidat,
                        ':nombre_voix' => $nombre_voix
                    ]);
                }

                // Insertion des votes blancs et nuls
                $query_votes = $pdo->prepare("
                    INSERT INTO resultats (id_bureau, id_candidat, nombre_voix)
                    VALUES (:id_bureau, 0, :votes_blancs), (:id_bureau, -1, :votes_nuls)
                    ON DUPLICATE KEY UPDATE nombre_voix = VALUES(nombre_voix)
                ");
                $query_votes->execute([
                    ':id_bureau' => $id_bureau,
                    ':votes_blancs' => $votes_blancs,
                    ':votes_nuls' => $votes_nuls
                ]);

                // Validation de la transaction
                $pdo->commit();
                $message = "Résultats enregistrés avec succès.";
            } catch (Exception $e) {
                $pdo->rollBack(); // Annulation de la transaction en cas d'erreur
                $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    } else {
        $message = "Erreur : Le bureau de vote sélectionné n'existe pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie des résultats</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Styles CSS */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc); /* Violet -> Bleu intense */
            color: #ffffff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #ff416c; /* Rouge vibrant */
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .logout-btn:hover {
            background: #ff4f81;
            transform: scale(1.05);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.4);
            max-width: 600px;
            text-align: center;
        }

        h1 {
            color: #ffffff;
            font-size: 2em;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        p {
            color: #ffcccb;
            font-size: 1.1em;
        }

        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #d1e8ff;
            margin-bottom: 10px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: none;
            font-size: 1em;
            background: rgba(255, 255, 255, 0.8);
            color: #2e3192; /* Bleu profond */
        }

        /* Couleurs flashy pour les candidats */
        .form-group label {
            color: #FFA500; /* Orange vif */
            font-weight: bold;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: none;
            font-size: 1em;
            background: #28a745; /* Vert vibrant */
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: transform 0.3s, background 0.3s;
        }

        button:hover {
            background: #218838; /* Vert légèrement plus foncé */
            transform: scale(1.05);
        }

        .status {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #6a11cb; /* Violet */
            color: #ffffff;
            border-radius: 5px;
        }

        a {
            color: #ffcccb;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Saisie des résultats</h1>
        <p><?php echo htmlspecialchars($message); ?></p>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_bureau">Bureau de vote :</label>
                <select name="id_bureau" id="id_bureau" required>
                    <?php foreach ($bureaux as $bureau): ?>
                        <option value="<?php echo $bureau['id_bureau']; ?>">
                            <?php echo htmlspecialchars($bureau['nom_bureau']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="resultats">Résultats par candidat :</label>
                <?php foreach ($candidats as $candidat): ?>
                    <label>
                        <?php echo htmlspecialchars($candidat['nom_candidat']) . " (" . htmlspecialchars($candidat['parti']) . ")"; ?>
                    </label>
                    <input type="number" name="resultats[<?php echo $candidat['id_candidat']; ?>]" min="0" value="0" required>
                <?php endforeach; ?>
            </div>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
</html>
