<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in() || !is_admin()) {
    redirect('../auth/login.php');
}

if (!isset($_GET['id'])) {
    redirect('users.php');
}

$user_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    redirect('users.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur - Impression</title>
    <style>
        body { font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f4f7f8; color:#333; padding:20px; }
        .container { max-width:600px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        h2 { text-align:center; margin-bottom:30px; color:#2575fc; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:12px 15px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#2575fc; color:#fff; }
        tr:hover { background:#f1f1f1; }
        @media print {
            body { background:#fff; }
            .container { box-shadow:none; border-radius:0; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Profil Utilisateur</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    window.print(); // Ouvre la fenêtre d'impression automatiquement
</script>

</body>
</html>
