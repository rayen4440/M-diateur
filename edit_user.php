<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Vérifier si l'utilisateur est connecté et admin
if (!is_logged_in() || !is_admin()) {
    redirect('../auth/login.php');
}

// Vérifier si l'ID de l'utilisateur à modifier est fourni
if (!isset($_GET['id'])) {
    redirect('users.php');
}

$user_id = $_GET['id'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    redirect('users.php');
}

// Mise à jour des informations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Vérifier si l'email n'est pas déjà utilisé par un autre utilisateur
    $stmt_check = $pdo->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
    $stmt_check->execute([$email, $user_id]);
    if ($stmt_check->fetch()) {
        $error = "Cet email est déjà utilisé par un autre utilisateur.";
    } else {
        $stmt_update = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt_update->execute([$name, $email, $role, $user_id]);
        $message = "Utilisateur mis à jour avec succès !";

        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:#f4f7f8; color:#333; display:flex; justify-content:center; align-items:flex-start; padding:40px 20px; min-height:100vh; }
        .container { background:#fff; padding:40px 30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.2); width:100%; max-width:500px; }
        h1 { text-align:center; margin-bottom:30px; color:#2575fc; }
        form label { display:block; margin-bottom:5px; font-weight:bold; }
        form input, form select { width:100%; padding:12px 15px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; transition:0.3s; }
        form input:focus, form select:focus { border-color:#2575fc; box-shadow:0 0 5px rgba(37,117,252,0.5); outline:none; }
        button { width:100%; padding:12px; background:#2575fc; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:16px; transition:0.3s; }
        button:hover { background:#6a11cb; }
        .message { text-align:center; margin-bottom:20px; font-weight:bold; color:green; }
        .error { text-align:center; margin-bottom:20px; font-weight:bold; color:red; }
        a.back { display:block; text-align:center; margin-top:20px; text-decoration:none; color:#2575fc; font-weight:bold; }
        a.back:hover { text-decoration:underline; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier Utilisateur</h1>

    <?php if(isset($message)) echo "<div class='message'>$message</div>"; ?>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Rôle :</label>
        <select name="role" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit">Mettre à jour</button>
    </form>

    <a href="users.php" class="back">← Retour à la liste des utilisateurs</a>
</div>

</body>
</html>
