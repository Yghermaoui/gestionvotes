<?php
session_start();

// Connexion à la base de données
require_once '../config/configbdd.php';

// Récupérer les détails de la sauvegarde
if (isset($_GET['id_sauvegarde'])) {
    $id_sauvegarde = intval($_GET['id_sauvegarde']);
    $stmt = $pdo->prepare("SELECT s.*, p.type_election, p.date_election, p.date_fin, b.nom_bureau, b.localisation
        FROM sauvegardes s
        JOIN parametre p ON s.id_election = p.id_election
        JOIN bureaux b ON s.id_bureau = b.id_bureau
        WHERE s.id_sauvegarde = ?");
    $stmt->execute([$id_sauvegarde]);
    $details = $stmt->fetch();
} else {
    header("Location: consultation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la configuration</title>
</head>
<body>
    <h1>Détails de la configuration</h1>
    <p><strong>Élection :</strong> <?= htmlspecialchars($details['type_election']) ?></p>
    <p><strong>Date de début :</strong> <?= htmlspecialchars($details['date_election']) ?></p>
    <p><strong>Date de fin :</strong> <?= htmlspecialchars($details['date_fin']) ?></p>
    <p><strong>Bureau :</strong> <?= htmlspecialchars($details['nom_bureau']) ?> (<?= htmlspecialchars($details['localisation']) ?>)</p>
    <p><strong>Nombre d'électeurs :</strong> <?= htmlspecialchars($details['nb_electeurs']) ?></p>
</body>
</html>
