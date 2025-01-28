<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des Votes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .stat-summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .stat-summary div {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 8px;
            width: 22%;
        }
        canvas {
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Résultats des Votes</h1>

        <!-- Statistiques Clés -->
        <div class="stat-summary">
            <div>
                <h3>Total des Votes</h3>
                <p>1200</p>
            </div>
            <div>
                <h3>Participation</h3>
                <p>75%</p>
            </div>
            <div>
                <h3>Candidat en Tête</h3>
                <p>Jean Dupont</p>
            </div>
            <div>
                <h3>Parti Dominant</h3>
                <p>Parti A</p>
            </div>
        </div>

        <!-- Graphique des Résultats (par Candidat) -->
        <canvas id="graphiqueCandidats"></canvas>

        <!-- Tableau des Résultats -->
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
                <tr>
                    <td>Jean Dupont</td>
                    <td>Parti A</td>
                    <td>450</td>
                    <td>37.5%</td>
                </tr>
                <tr>
                    <td>Marie Durand</td>
                    <td>Parti B</td>
                    <td>300</td>
                    <td>25%</td>
                </tr>
                <tr>
                    <td>Paul Martin</td>
                    <td>Parti C</td>
                    <td>450</td>
                    <td>37.5%</td>
                </tr>
            </tbody>
        </table>

        <!-- Graphique d'Évolution Temporelle -->
        <canvas id="graphiqueEvolution"></canvas>
    </div>

    <script>
        // Graphique des Résultats par Candidat
        const ctxCandidats = document.getElementById('graphiqueCandidats').getContext('2d');
        new Chart(ctxCandidats, {
            type: 'bar',
            data: {
                labels: ['Jean Dupont', 'Marie Durand', 'Paul Martin'],
                datasets: [{
                    label: 'Nombre de voix',
                    data: [450, 300, 450],
                    backgroundColor: ['#4caf50', '#2196f3', '#f44336'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                }
            }
        });

        // Graphique d'Évolution Temporelle (Simulé)
        const ctxEvolution = document.getElementById('graphiqueEvolution').getContext('2d');
        new Chart(ctxEvolution, {
            type: 'line',
            data: {
                labels: ['9h', '10h', '11h', '12h', '13h', '14h'],
                datasets: [
                    {
                        label: 'Jean Dupont',
                        data: [50, 150, 250, 350, 400, 450],
                        borderColor: '#4caf50',
                        fill: false,
                    },
                    {
                        label: 'Marie Durand',
                        data: [30, 100, 150, 200, 250, 300],
                        borderColor: '#2196f3',
                        fill: false,
                    },
                    {
                        label: 'Paul Martin',
                        data: [20, 120, 220, 300, 400, 450],
                        borderColor: '#f44336',
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    x: { title: { display: true, text: 'Heures' } },
                    y: { title: { display: true, text: 'Nombre de voix' } }
                }
            }
        });
    </script>
</body>
</html>
