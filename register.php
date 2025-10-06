<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'user'; // Rôle par défaut

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Email déjà utilisé";
    } else {
        // Insérer l'utilisateur (mot de passe en clair pour l'instant)
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $stmt->execute([$name, $email, $password, $role]);
        $message = "Inscription réussie ! Vous pouvez vous connecter.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #6a11cb, #2575fc); color:#333; display:flex; justify-content:center; align-items:center; height:100vh; }
        .container { background:#fff; padding:40px 50px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.2); width:100%; max-width:400px; text-align:center; }
        h2 { margin-bottom:30px; color:#2575fc; }
        form input { width:100%; padding:12px 15px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; transition:0.3s; }
        form input:focus { border-color:#2575fc; box-shadow:0 0 5px rgba(37,117,252,0.5); outline:none; }
        button { width:100%; padding:12px; background-color:#2575fc; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:16px; transition:0.3s; }
        button:hover { background-color:#6a11cb; }
        .message { margin-bottom:20px; font-weight:bold; }
        .error { color:red; }
        .success { color:green; }
        p { margin-top:15px; }
        a { color:#2575fc; text-decoration:none; font-weight:bold; }
        a:hover { text-decoration:underline; }
        @media(max-width:500px){ .container { padding:30px 20px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>

        <?php if (isset($message)): ?>
            <div class="message success"><?= $message ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
        <p><a href="../index.php">← Retour à l'accueil</a></p>
    </div>
</body>
</html>
