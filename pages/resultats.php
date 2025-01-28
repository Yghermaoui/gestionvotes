<?php
require_once '../config/configbdd.php';

// Récupération des bureaux de vote
$bureaux_query = $pdo->query("SELECT id_bureau, nom_bureau FROM bureaux");
$bureaux = $bureaux_query->fetchAll(PDO::FETCH_ASSOC);

// Initialisation des variables
$data_candidats = [];
$data_votes = [];
$data_percentages = [];
$total_voix = 0;
$nombre_inscrits = 0;
$taux_abstention = 0;
$titre_graphique = "Résultats globaux";
$resultats = [];
$votes_blanc_et_nul = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bureau = intval($_POST['id_bureau']);

    // Récupération des résultats pour le bureau sélectionné
    $query = $pdo->prepare("
        SELECT c.nom_candidat, c.parti, SUM(r.nombre_voix) AS nombre_voix 
        FROM resultats r 
        JOIN candidat c ON r.id_candidat = c.id_candidat 
        WHERE r.id_bureau = :id_bureau 
        AND c.nom_candidat NOT IN ('Vote Blanc', 'Vote Nul') 
        GROUP BY c.nom_candidat, c.parti");
    $query->execute([':id_bureau' => $id_bureau]);
    $resultats = $query->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des votes blancs et nuls pour le bureau sélectionné
    $query_blanc_nul = $pdo->prepare("
        SELECT SUM(r.nombre_voix) AS total_blanc_nul 
        FROM resultats r 
        JOIN candidat c ON r.id_candidat = c.id_candidat 
        WHERE r.id_bureau = :id_bureau 
        AND c.nom_candidat IN ('Vote Blanc', 'Vote Nul')");
    $query_blanc_nul->execute([':id_bureau' => $id_bureau]);
    $votes_blanc_et_nul = $query_blanc_nul->fetchColumn();

    // Récupération du nombre d'inscrits pour le bureau sélectionné
    $query_inscrits = $pdo->prepare("SELECT nb_inscrits FROM bureaux WHERE id_bureau = :id_bureau");
    $query_inscrits->execute([':id_bureau' => $id_bureau]);
    $nombre_inscrits = $query_inscrits->fetchColumn();

    if ($resultats || $votes_blanc_et_nul) {
        $total_voix = array_sum(array_column($resultats, 'nombre_voix')) + $votes_blanc_et_nul;
        $taux_abstention = $nombre_inscrits > 0 ? round(100 * (1 - ($total_voix / $nombre_inscrits)), 2) : 0;

        // Préparation des données pour le graphique et le tableau
        $candidats_uniques = [];
        foreach ($resultats as $resultat) {
            $nom_candidat = $resultat['nom_candidat'];

            if (!isset($candidats_uniques[$nom_candidat])) {
                $voix = intval($resultat['nombre_voix']);
                $pourcentage = $total_voix > 0 ? round(($voix / $total_voix) * 100, 2) : 0;

                // Ajouter les données pour le graphique
                $data_candidats[] = $nom_candidat . " (" . $resultat['parti'] . ")";
                $data_votes[] = $voix;

                // Ajouter le pourcentage et stocker dans la liste unique
                $resultat['pourcentage'] = $pourcentage . "%";
                $candidats_uniques[$nom_candidat] = $resultat;
            }
        }

        // Remplacer $resultats par la version unique
        $resultats = array_values($candidats_uniques);

        // Mise à jour du titre pour le bureau sélectionné
        $titre_graphique = "Résultats pour le bureau : " . htmlspecialchars($_POST['nom_bureau']);
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des votes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2c2c2c, #000000);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #1c1c1c;
            border-radius: 8px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
            color: #ffffff;
            flex-grow: 1;
            border-left: 5px solid #ff0000;
        }

        h1, h2 {
            text-align: center;
            color: #ff4500;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        select, button {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 2px solid #ff4500;
            width: 100%;
            max-width: 300px; 
            background-color: #333333;
            color: #ffffff;
            font-weight: bold;
        }

        button:hover {
            background: linear-gradient(135deg, #ff4500, #006400);
            transform: scale(1.05);
        }

        .chart-container {
            margin: 20px 0;
        }

        .info {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #333333;
            border-radius: 5px;
            border: 1px solid #444;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #333333;
            color: #ffffff;
            text-align: center;
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border: 1px solid #444;
        }

        th {
            background-color: #ff4500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Résultats des votes</h1>
        <form method="POST">
            <select name="id_bureau" required onchange="updateNomBureau(this)">
                <option value="" disabled selected>Sélectionnez un bureau de vote</option>
                <?php foreach ($bureaux as $bureau): ?>
                    <option value="<?php echo $bureau['id_bureau']; ?>">
                        <?php echo htmlspecialchars($bureau['nom_bureau']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="nom_bureau" id="nom_bureau" value="">
            <button type="submit">Afficher les résultats</button>
        </form>

        <?php if (!empty($data_candidats)): ?>
            <div class="info">
                <p><strong>Total des votants :</strong> <?php echo $total_voix; ?></p>
                <p><strong>Taux d'abstention :</strong> <?php echo $taux_abstention; ?>%</p>
            </div>

            <!-- Diagramme circulaire -->
            <div class="chart-container">
                <canvas id="chartPie"></canvas>
            </div>

            <!-- Tableau des résultats -->
            <h2>Classement des Candidats</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom du Candidat</th>
                        <th>Parti</th>
                        <th>Nombre de Voix</th>
                        <th>Pourcentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultats as $resultat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resultat['nom_candidat']); ?></td>
                            <td><?php echo htmlspecialchars($resultat['parti']); ?></td>
                            <td><?php echo $resultat['nombre_voix']; ?></td>
                            <td><?php echo $resultat['pourcentage']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun résultat à afficher pour le bureau sélectionné.</p>
        <?php endif; ?>
    </div>

    <script>
        function updateNomBureau(select) {
            const nomBureau = select.options[select.selectedIndex].text;
            document.getElementById('nom_bureau').value = nomBureau;
        }

        const labels = <?php echo json_encode($data_candidats); ?>;
        const dataVotes = <?php echo json_encode($data_votes); ?>;

        if (labels.length > 0) {
            new Chart(document.getElementById('chartPie'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataVotes,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9f40'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false 
                        }
                    },
                    responsive: true
                }
            });
        }
    </script>
</body>
</html>
 