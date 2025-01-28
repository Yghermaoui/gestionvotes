<?php
session_start();

// Connexion à la base de données
require_once '../config/configbdd.php';

// Récupérer les sauvegardes et les informations liées
$stmt = $pdo->query("SELECT s.id_election, s.id_bureau, s.nb_electeurs, p.type_election, b.nom_bureau, s.date_election
                     FROM sauvegardes s
                     JOIN parametre p ON s.id_election = p.id_election
                     JOIN bureaux b ON s.id_bureau = b.id_bureau");
$sauvegardes = $stmt->fetchAll();

// Supprimer une sauvegarde si le bouton de suppression est cliqué
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteStmt = $pdo->prepare("DELETE FROM sauvegardes WHERE id_election = ?");
    $deleteStmt->execute([$deleteId]);

    // Rediriger pour éviter de resoumettre le formulaire si l'utilisateur actualise la page
    header('Location: consultation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation des sauvegardes - DigiVote</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .page-title {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .table-section {
            margin-top: 40px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #2c3e50;
            color: #fff;
        }

        .table td {
            background-color: #f9f9f9;
        }

        .table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        .table tr:hover td {
            background-color: #e7e7e7;
        }

        .no-records {
            text-align: center;
            font-size: 1.2em;
            color: #e74c3c;
            padding: 20px;
        }

        .btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="page-title">Consultation des sauvegardes - DigiVote</h1>

    <section class="table-section">
        <h2>Informations sur les élections</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Nom de l'élection</th>
                <th>Période (dates)</th>
                <th>Nom du bureau</th>
                <th>Nombre d'électeurs inscrits</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Requête SQL pour la table des élections
            $query = "SELECT 
                          p.type_election AS nom_election,
                          CONCAT('(', p.date_election, ' - ', p.date_fin, ')') AS periodes,
                          b.nom_bureau,
                          b.nb_inscrits AS nombre_electeurs,
                          p.id_election
                      FROM 
                          parametre p
                      LEFT JOIN 
                          sauvegardes s ON p.id_election = s.id_election
                      LEFT JOIN 
                          bureaux b ON s.id_bureau = b.id_bureau;";
            $result = $pdo->query($query);

            // Affichage des résultats des élections
            while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nom_election']) ?></td>
                    <td><?= htmlspecialchars($row['periodes']) ?></td>
                    <td><?= htmlspecialchars($row['nom_bureau']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_electeurs']) ?></td>
                    <td>
                        <!-- Bouton supprimer -->
                        <a href="?delete=<?= htmlspecialchars($row['id_election']) ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <section class="table-section">
        <h2>Liste des candidats</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Nom du candidat</th>
                <th>Prénom du candidat</th>
                <th>Parti politique</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Requête SQL pour la table des candidats
            $queryCandidats = "SELECT 
                                   c.nom_candidat,
                                   c.prenom_candidat,
                                   c.parti,
                                   c.id_candidat
                               FROM 
                                   candidat c
                               LEFT JOIN 
                                   parametre p ON c.id_election = p.id_election
                               ORDER BY c.Nom_candidat, c.prenom_candidat";
            $resultCandidats = $pdo->query($queryCandidats);

            // Affichage des résultats des candidats
            while ($rowCandidat = $resultCandidats->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($rowCandidat['nom_candidat']) ?></td>
                    <td><?= htmlspecialchars($rowCandidat['prenom_candidat']) ?></td>
                    <td><?= htmlspecialchars($rowCandidat['parti']) ?></td>
                    <td>
                        <!-- Bouton supprimer (ou autre action) -->
                        <a href="?delete=<?= htmlspecialchars($rowCandidat['id_candidat']) ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>
