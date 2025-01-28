<?php
require_once '../config/configbdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_bureau = htmlspecialchars($_POST['nom_bureau']);
    $localisation = htmlspecialchars($_POST['localisation']);
    $nb_inscrits = (int) $_POST['nb_inscrits'];

    $stmt = $pdo->prepare("INSERT INTO bureaux (nom_bureau, localisation, nb_inscrits) VALUES (?, ?, ?)");
    $stmt->execute([$nom_bureau, $localisation, $nb_inscrits]);

    header("Location: admin.php");
    exit();
}
