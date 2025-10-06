<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in() || !is_admin()) redirect('../auth/login.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (!$title || !$description) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO cases (title, description, status) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $description, $status])) {
            $success = "Dossier ajouté avec succès !";
        } else {
            $error = "Erreur lors de l'ajout du dossier.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un dossier - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:#f4f7f8; color:#333; }
        header { background:#2575fc; color:#fff; padding:20px 40px; text-align:center; font-size:24px; font-weight:bold; }
        .container { max-width:600px; margin:40px auto; padding:0 20px; }
        h1 { margin-bottom:20px; color:#2575fc; text-align:center; }
        form { background:#fff; padding:30px 20px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        input[type="text"], textarea, select { width:100%; padding:12px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; }
        input[type="text"]:focus, textarea:focus, select:focus { border-color:#2575fc; outline:none; box-shadow:0 0 5px rgba(37,117,252,0.5); }
        button { width:100%; padding:12px; background:#2575fc; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:16px; transition:0.3s; }
        button:hover { background:#6a11cb; }
        .message { text-align:center; margin-bottom:20px; font-weight:bold; }
        .error { color:red; }
        .success { color:green; }
        a.back { display:block; margin-top:20px; text-align:center; text-decoration:none; color:#2575fc; font-weight:bold; }
        a.back:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <header>Ajouter un dossier</header>
    <div class="container">
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="title" placeholder="Titre du dossier" required>
            <textarea name="description" placeholder="Description du dossier" rows="5" required></textarea>
            <select name="status" required>
                <option value="En cours">En cours</option>
                <option value="Terminé">Terminé</option>
                <option value="En attente">En attente</option>
            </select>
            <button type="submit">Ajouter le dossier</button>
        </form>

        <a href="cases.php" class="back">← Retour à la liste des dossiers</a>
    </div>
</body>
</html>
