<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) redirect('../auth/login.php');

// Récupérer les dossiers assignés à l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM cases WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Dossiers - Mediateur Project</title>
    <style>
        * {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
        body {background:#f4f7f8;color:#333;}
        header {background:#2575fc;color:#fff;padding:20px;text-align:center;font-size:24px;font-weight:bold;}
        .container {max-width:1000px;margin:40px auto;padding:0 20px;}
        h1 {text-align:center;color:#2575fc;margin-bottom:30px;}
        table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        th, td { padding:12px 15px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#2575fc; color:#fff; }
        tr:hover { background:#f1f1f1; }
        .status {padding:5px 10px;border-radius:6px;color:#fff;font-weight:bold;text-align:center;}
        .en-cours {background:#ffc107;}
        .termine {background:#28a745;}
        .en-attente {background:#17a2b8;}
        .back {display:block;margin-top:20px;text-align:center;text-decoration:none;color:#2575fc;font-weight:bold;}
        .back:hover {text-decoration:underline;}
        @media(max-width:600px){
            table,thead,tbody,th,td,tr {display:block;}
            th {display:none;}
            td {padding:10px; border:none; border-bottom:1px solid #ddd; position:relative; padding-left:50%;}
            td::before {content:attr(data-label);position:absolute;left:15px;font-weight:bold;}
        }
    </style>
</head>
<body>
    <header>Mes Dossiers</header>
    <div class="container">
        <h1>Liste de mes dossiers</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if($cases): ?>
                    <?php foreach($cases as $case): ?>
                        <?php
                        // Normaliser le statut pour la classe CSS
                        $statusClass = str_replace(' ', '-', strtolower($case['status']));
                        ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($case['id']) ?></td>
                            <td data-label="Titre"><?= htmlspecialchars($case['title']) ?></td>
                            <td data-label="Description"><?= htmlspecialchars($case['description']) ?></td>
                            <td data-label="Statut"><span class="status <?= $statusClass ?>"><?= htmlspecialchars($case['status']) ?></span></td>
                            <td data-label="Date"><?= htmlspecialchars($case['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">Aucun dossier trouvé</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="back">← Retour au Dashboard</a>
    </div>
</body>
</html>
