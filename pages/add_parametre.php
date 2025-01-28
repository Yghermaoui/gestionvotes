<?php
require_once '../config/configbdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_election = htmlspecialchars($_POST['type_election']);
    $date_election = htmlspecialchars($_POST['date_election']);
    $description = htmlspecialchars($_POST['description']);

    $stmt = $pdo->prepare("INSERT INTO parametre (type_election, date_election, description) VALUES (?, ?, ?)");
    $stmt->execute([$type_election, $date_election, $description]);

    header("Location: admin.php");
    exit();
}
