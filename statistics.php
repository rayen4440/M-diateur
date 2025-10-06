<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in() || !is_admin()) redirect('../auth/login.php');

// Statistiques de base
$total_cases = $pdo->query("SELECT COUNT(*) FROM cases")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Statistiques pour graphiques
$stmt = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM cases GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC");
$cases_by_date = $stmt->fetchAll(PDO::FETCH_ASSOC);
$dates = array_column($cases_by_date, 'date');
$counts = array_column($cases_by_date, 'count');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Mediateur Project</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:#f4f7f8; color:#333; }
        header { background:#2575fc; color:#fff; padding:20px 40px; text-align:center; font-size:24px; font-weight:bold; }
        .container { max-width:1200px; margin:40px auto; padding:0 20px; }
        h1 { margin-bottom:20px; color:#2575fc; text-align:center; }
        .stats { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; margin-bottom:40px; }
        .stat-card { background:#fff; padding:30px 20px; border-radius:12px; width:200px; text-align:center; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        .stat-card h2 { color:#2575fc; margin-bottom:10px; }
        canvas { background:#fff; border-radius:12px; padding:20px; box-shadow:0 8px 20px rgba(0,0,0,0.1); width:100%; max-width:800px; margin:0 auto; display:block; }
        @media(max-width:600px){ .stats { flex-direction:column; align-items:center; } }
    </style>
</head>
<body>
    <header>Statistiques</header>
    <div class="container">
        <h1>Vue d'ensemble</h1>
        <div class="stats">
            <div class="stat-card">
                <h2><?= $total_cases ?></h2>
                <p>Dossiers totaux</p>
            </div>
            <div class="stat-card">
                <h2><?= $total_users ?></h2>
                <p>Utilisateurs totaux</p>
            </div>
        </div>

        <h1>Évolution des dossiers</h1>
        <canvas id="casesChart"></canvas>
                <a href="dashboard.php" class="back">← Retour à Dashboard</a>

    </div>

    <script>
        const ctx = document.getElementById('casesChart').getContext('2d');
        const casesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($dates) ?>,
                datasets: [{
                    label: 'Nombre de dossiers par jour',
                    data: <?= json_encode($counts) ?>,
                    backgroundColor: 'rgba(37,117,252,0.2)',
                    borderColor: 'rgba(37,117,252,1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Dossiers créés au fil du temps' }
                },
                scales: {
                    y: { beginAtZero: true, precision:0 }
                }
            }
        });
    </script>
    
</body>
</html>
