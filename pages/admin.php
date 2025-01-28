<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Connexion à la base de données
require_once '../config/configbdd.php';

// Gestion des formulaires
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_bureau'])) {
        $nom_bureau = $_POST['nom_bureau'];
        $nb_inscrits = $_POST['nb_inscrits'];
        $stmt = $pdo->prepare("INSERT INTO bureaux (nom_bureau, nb_inscrits) VALUES (?, ?)");
        $stmt->execute([$nom_bureau, $nb_inscrits]);
    }

    if (isset($_POST['add_election'])) {
        $type_election = $_POST['type_election'];
        $date_election = $_POST['date_election'];
        $date_fin = $_POST['date_fin'];
        $stmt = $pdo->prepare("INSERT INTO parametre (type_election, date_election, date_fin) VALUES (?, ?, ?)");
        $stmt->execute([$type_election, $date_election, $date_fin]);
    }

    if (isset($_POST['add_candidate'])) {
        $nom_candidat = $_POST['nom_candidat'];
        $prenom_candidat = $_POST['prenom_candidat'];
        $parti = $_POST['parti'];
    
        $stmt = $pdo->prepare("INSERT INTO candidat (nom_candidat, prenom_candidat, parti) VALUES (?, ?, ?)");
        $stmt->execute([$nom_candidat, $prenom_candidat, $parti]);
    }

    if (isset($_POST['save_candidate'])) {
        $id_candidat = intval($_POST['id_candidat']);
        $id_election = intval($_POST['election_id']);
    
        $stmt = $pdo->prepare("UPDATE candidat SET id_election = ? WHERE id_candidat = ?");
        $stmt->execute([$id_election, $id_candidat]);
    
        $success_message = "Le candidat a été assigné à l'élection avec succès.";
    }
    
    

    if (isset($_POST['save_selection'])) {
        $election_id = $_POST['election_id'];
        $bureaux_ids = $_POST['bureaux_ids'];

        foreach ($bureaux_ids as $id_bureau) {
            $nb_electeurs = $_POST["nb_electeurs_$id_bureau"] ?? 0; // Valeur par défaut si non définie
            $stmt = $pdo->prepare("INSERT INTO sauvegardes (id_election, id_bureau, nb_electeurs) VALUES (?, ?, ?)");
            $stmt->execute([$election_id, $id_bureau, $nb_electeurs]);
        }

        $success_message = "Configuration sauvegardée avec succès.";
    }

    if (isset($_POST['delete_bureau_id'])) {
        $delete_bureau_id = intval($_POST['delete_bureau_id']);
        $stmt = $pdo->prepare("DELETE FROM bureaux WHERE id_bureau = ?");
        $stmt->execute([$delete_bureau_id]);
    }

    if (isset($_POST['delete_candidat_id'])) {
        $delete_candidat_id = intval($_POST['delete_candidat_id']);
        $stmt = $pdo->prepare("DELETE FROM candidat WHERE id_candidat = ?");
        $stmt->execute([$delete_candidat_id]);
        $success_message = "Candidat supprimé avec succès.";
    }
    


    if (isset($_POST['delete_election_id'])) {
        $delete_election_id = intval($_POST['delete_election_id']);
        $stmt = $pdo->prepare("DELETE FROM parametre WHERE id_election = ?");
        $stmt->execute([$delete_election_id]);
    }
}

// Récupération des données
$bureaux = $pdo->query("SELECT * FROM bureaux")->fetchAll();
$elections = $pdo->query("SELECT * FROM parametre")->fetchAll();
$configurations = $pdo->query("SELECT * FROM sauvegardes")->fetchAll();
$candidats = $pdo->query("SELECT * FROM candidat")->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - DigiVote</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 90%;
            margin: auto;
            overflow: auto;
        }
        .page-title {
            text-align: center;
            margin: 20px 0;
            font-size: 2em;
            color: #333;
        }
        .form-section, .table-section {
            margin: 20px 0;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .table-section h2, .form-section h2 {
            margin-bottom: 10px;
            font-size: 1.5em;
            color: #555;
        }
        .form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .input-field {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-delete {
            padding: 5px 10px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .table th {
            background: #f4f4f9;
        }
        .scrollable {
            max-height: 300px;
            overflow-y: auto;
        }
        .success-message {
            padding: 10px;
            margin: 10px 0;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="page-title">Administration - DigiVote</h1>

    <?php if (isset($success_message)): ?>
        <div class="success-message"><?= $success_message ?></div>
    <?php endif; ?>

    <!-- Ajouter un bureau -->
    <section class="form-section">
        <h2>Ajouter un bureau de vote</h2>
        <form action="" method="POST" class="form">
            <input type="hidden" name="add_bureau">
            <input type="text" name="nom_bureau" placeholder="Nom du bureau" class="input-field" required>
            <input type="number" name="nb_inscrits" placeholder="Nombre d'inscrits" class="input-field" required>
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </section>

    <!-- Liste des bureaux -->
    <section class="table-section scrollable">
        <h2>Bureaux de vote</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Nombre d'inscrits</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bureaux as $bureau): ?>
                <tr>
                    <td><?= htmlspecialchars($bureau['nom_bureau']) ?></td>
                    <td><?= htmlspecialchars($bureau['nb_inscrits']) ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="delete_bureau_id" value="<?= $bureau['id_bureau'] ?>">
                            <button type="submit" class="btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Ajouter une élection -->
    <section class="form-section">
        <h2>Ajouter une élection</h2>
        <form action="" method="POST" class="form">
            <input type="hidden" name="add_election">
            <select name="type_election" class="input-field" required>
                <option value="">-- Sélectionnez un type --</option>
                <option value="Présidentielle">Présidentielle</option>
                <option value="Législative">Législative</option>
                <option value="Municipale">Municipale</option>
                <option value="Régionale">Régionale</option>
                <option value="Européenne">Européenne</option>
            </select>
            <input type="date" name="date_election" class="input-field" required>
            <input type="date" name="date_fin" class="input-field" required>
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </section>

    <!-- Liste des élections -->
    <section class="table-section scrollable">
        <h2>Élections paramétrées</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Type</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($elections as $election): ?>
                <tr>
                    <td><?= htmlspecialchars($election['type_election']) ?></td>
                    <td><?= htmlspecialchars($election['date_election']) ?></td>
                    <td><?= htmlspecialchars($election['date_fin']) ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="delete_election_id" value="<?= $election['id_election'] ?>">
                            <button type="submit" class="btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

   <!-- Formulaire pour ajouter un candidat -->
   <section class="form-section">
        <h2>Ajouter un candidat</h2>
        <form action="" method="POST" class="form">
            <input type="hidden" name="add_candidate">
            <input type="text" name="nom_candidat" placeholder="Nom du candidat" class="input-field" required>
            <input type="text" name="prenom_candidat" placeholder="Prénom du candidat" class="input-field" required>
            <input type="text" name="parti" placeholder="Parti politique" class="input-field" required>
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </section>

<!-- Liste des candidats -->
<section class="table-section scrollable">
    <h2>Liste des candidats</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Parti</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($candidats as $candidat): ?>
            <tr>
                <td><?= htmlspecialchars($candidat['Nom_candidat']) ?></td>
                <td><?= htmlspecialchars($candidat['prenom_candidat']) ?></td>
                <td><?= htmlspecialchars($candidat['parti']) ?></td>
                <td>
                    <!-- Formulaire de sauvegarde -->
                    <form action="" method="POST" style="display: inline-block;">
                        <input type="hidden" name="save_candidate">
                        <input type="hidden" name="id_candidat" value="<?= $candidat['id_candidat'] ?>">
                        <select name="election_id" class="input-field" required>
                            <option value="">-- Sélectionnez une élection --</option>
                            <?php foreach ($elections as $election): ?>
                                <option value="<?= $election['id_election'] ?>">
                                    <?= htmlspecialchars($election['type_election']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn">Sauvegarder</button>
                    </form>
                    <!-- Formulaire de suppression -->
                    <form action="" method="POST" style="display: inline-block;">
                        <input type="hidden" name="delete_candidat_id" value="<?= $candidat['id_candidat'] ?>">
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>



    <!-- Sauvegarder une configuration -->
    <section class="form-section">
        <h2>Sauvegarder une configuration</h2>
        <form action="" method="POST" class="form">
            <input type="hidden" name="save_selection">
            <select name="election_id" class="input-field" required>
                <option value="">-- Sélectionnez une élection --</option>
                <?php foreach ($elections as $election): ?>
                    <option value="<?= $election['id_election'] ?>">
                        <?= htmlspecialchars($election['type_election']) ?> (<?= htmlspecialchars($election['date_election']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="bureaux_ids[]" class="input-field" multiple required>
                <?php foreach ($bureaux as $bureau): ?>
                    <option value="<?= $bureau['id_bureau'] ?>">
                        <?= htmlspecialchars($bureau['nom_bureau']) ?> 
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Sauvegarder</button>
        </form>
    </section>
    
</div>
</body>
</html>
