<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Vérifier si l'utilisateur est connecté et admin
if (!is_logged_in() || !is_admin()) {
    redirect('../auth/login.php');
}

// Supprimer un utilisateur si demandé
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Utilisateur supprimé avec succès !";
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:#f4f7f8; color:#333; }
        header { background:#2575fc; color:#fff; padding:20px 40px; text-align:center; font-size:24px; font-weight:bold; }
        .container { max-width:1200px; margin:40px auto; padding:0 20px; }
        h1 { text-align:center; margin-bottom:30px; color:#2575fc; }
        table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        th, td { padding:12px 15px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#2575fc; color:#fff; }
        tr:hover { background:#f1f1f1; }
        a.action { text-decoration:none; margin-right:8px; color:#2575fc; font-weight:bold; }
        a.action:hover { text-decoration:underline; }
        .message { text-align:center; margin-bottom:20px; color:green; font-weight:bold; }
        .back { display:block; margin-top:30px; text-align:center; text-decoration:none; color:#2575fc; font-weight:bold; }
        .back:hover { text-decoration:underline; }
        @media(max-width:768px){
            table, thead, tbody, th, td, tr { display:block; }
            th { display:none; }
            td { position:relative; padding-left:50%; border:none; border-bottom:1px solid #ddd; }
            td::before { content: attr(data-label); position:absolute; left:15px; font-weight:bold; }
        }
    </style>
</head>
<body>

<header>Gestion des utilisateurs</header>

<div class="container">

    <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td data-label="ID"><?= $user['id'] ?></td>
                <td data-label="Nom"><?= htmlspecialchars($user['name']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
                <td data-label="Rôle"><?= $user['role'] ?></td>
                <td data-label="Actions">
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="action">Modifier</a>
                    <a href="users.php?delete=<?= $user['id'] ?>" class="action" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">Supprimer</a>
                    <a href="print_user.php?id=<?= $user['id'] ?>" class="action">Imprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back">← Retour au Dashboard</a>
</div>

</body>
</html>
