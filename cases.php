<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in() || !is_admin()) redirect('../auth/login.php');

// Gestion de suppression
if (isset($_GET['delete'])) {
    $case_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM cases WHERE id = ?");
    $stmt->execute([$case_id]);
    header("Location: cases.php");
    exit();
}

// Récupération des dossiers
$stmt = $pdo->query("SELECT * FROM cases ORDER BY created_at DESC");
$cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer Dossiers - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:#f4f7f8; color:#333; }
        header { background:#2575fc; color:#fff; padding:20px 40px; text-align:center; font-size:24px; font-weight:bold; }
        .container { max-width:1200px; margin:40px auto; padding:0 20px; }
        h1 { margin-bottom:20px; color:#2575fc; text-align:center; }
        .add-btn { display:inline-block; margin-bottom:20px; text-decoration:none; background:#28a745; color:#fff; padding:10px 20px; border-radius:8px; transition:0.3s; }
        .add-btn:hover { background:#218838; }
        table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        th, td { padding:12px 15px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#2575fc; color:#fff; }
        tr:hover { background:#f1f1f1; }
        .status { padding:5px 10px; border-radius:6px; color:#fff; font-weight:bold; text-align:center; }
        .en-cours { background:#ffc107; }      /* Jaune */
        .Terminé { background:#17a2b8; }       /* Bleu */
        .en-attente { background:#17a2b8; }    /* Cyan */
        .action-btn { padding:6px 12px; border:none; border-radius:6px; cursor:pointer; color:#fff; transition:0.3s; text-decoration:none; }
        .edit-btn { background:#ffc107; }
        .edit-btn:hover { background:#e0a800; }
        .delete-btn { background:#dc3545; }
        .delete-btn:hover { background:#c82333; }
        .back { display:block; margin-top:20px; text-align:center; text-decoration:none; color:#2575fc; font-weight:bold; }
        .back:hover { text-decoration:underline; }
        @media (max-width:600px) {
            table, thead, tbody, th, td, tr { display:block; }
            th { display:none; }
            td { padding:10px; border:none; border-bottom:1px solid #ddd; position:relative; padding-left:50%; }
            td::before { content:attr(data-label); position:absolute; left:15px; font-weight:bold; }
        }
    </style>
</head>
<body>
    <header>Gérer Dossiers</header>
    <div class="container">
        <a href="add_case.php" class="add-btn">Ajouter un dossier</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Date Création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cases): ?>
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
                            <td data-label="Actions">
                                <a href="edit_case.php?id=<?= $case['id'] ?>" class="action-btn edit-btn">Modifier</a>
                                <a href="cases.php?delete=<?= $case['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Voulez-vous vraiment supprimer ce dossier ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">Aucun dossier trouvé</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="back">← Retour à Dashboard</a>
    </div>
</body>
</html>
