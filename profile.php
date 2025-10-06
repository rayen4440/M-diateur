<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt_update = $pdo->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $stmt_update->execute([$name, $hashed_password, $_SESSION['user_id']]);
        $message = "Profil mis à jour avec succès !";
    } else {
        $stmt_update = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt_update->execute([$name, $_SESSION['user_id']]);
        $message = "Nom mis à jour avec succès !";
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f8; color:#333; display:flex; justify-content:center; align-items:flex-start; min-height:100vh; padding-top:50px; }
        .container { background:#fff; padding:40px 30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.2); width:100%; max-width:500px; }
        h2 { text-align:center; margin-bottom:30px; color:#2575fc; }
        form label { display:block; margin-bottom:5px; font-weight:bold; }
        form input { width:100%; padding:12px 15px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; transition:0.3s; }
        form input:focus { border-color:#2575fc; box-shadow:0 0 5px rgba(37,117,252,0.5); outline:none; }
        button { width:100%; padding:12px; background:#2575fc; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:16px; transition:0.3s; }
        button:hover { background:#6a11cb; }
        .message { text-align:center; margin-bottom:20px; font-weight:bold; }
        .success { color:green; }
        .back { display:block; text-align:center; margin-top:20px; text-decoration:none; color:#2575fc; font-weight:bold; }
        .back:hover { text-decoration:underline; }
        @media(max-width:500px){ .container { padding:30px 20px; } }
    </style>
</head>
<body>

    <div class="container">
        <h2>Mon Profil</h2>

        <?php if (isset($message)): ?>
            <div class="message success"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nom :</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label>Email :</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

            <label>Nouveau mot de passe (laisser vide pour garder le mot de passe actuel) :</label>
            <input type="password" name="password">

            <button type="submit">Mettre à jour</button>
        </form>

        <a href="<?= $_SESSION['role'] === 'admin' ? 'dashboard.php' : '../user/dashboard.php' ?>" class="back">← Retour au Dashboard</a>
    </div>

</body>
</html>
