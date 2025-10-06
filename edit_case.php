<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Vérifier si l'utilisateur est connecté
if (!is_logged_in()) redirect('../auth/login.php');

// Vérifier si l'ID du dossier est fourni
if (!isset($_GET['id'])) {
    redirect('mes_dossiers.php'); // ou admin/cases.php si c'est pour l'admin
}

$case_id = $_GET['id'];

// Récupérer le dossier existant
$stmt = $pdo->prepare("SELECT * FROM cases WHERE id = ?");
$stmt->execute([$case_id]);
$case = $stmt->fetch();

if (!$case) {
    die("Dossier introuvable !");
}

// Mettre à jour le dossier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    $stmt_update = $pdo->prepare("UPDATE cases SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt_update->execute([$title, $description, $status, $case_id]);

    $message = "Dossier mis à jour avec succès !";

    // Recharger les données
    $stmt = $pdo->prepare("SELECT * FROM cases WHERE id = ?");
    $stmt->execute([$case_id]);
    $case = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Dossier - Mediateur Project</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
        body{background:#f4f7f8;color:#333;display:flex;justify-content:center;align-items:flex-start;min-height:100vh;padding-top:50px;}
        .container{background:#fff;padding:40px 30px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.2);width:100%;max-width:500px;}
        h2{text-align:center;margin-bottom:30px;color:#2575fc;}
        form label{display:block;margin-bottom:5px;font-weight:bold;}
        form input, form select, form textarea{width:100%;padding:12px 15px;margin-bottom:20px;border:1px solid #ccc;border-radius:8px;transition:0.3s;}
        form input:focus, form select:focus, form textarea:focus{border-color:#2575fc;box-shadow:0 0 5px rgba(37,117,252,0.5);outline:none;}
        button{width:100%;padding:12px;background:#2575fc;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:16px;transition:0.3s;}
        button:hover{background:#6a11cb;}
        .message{text-align:center;margin-bottom:20px;font-weight:bold;color:green;}
        .back{display:block;text-align:center;margin-top:20px;text-decoration:none;color:#2575fc;font-weight:bold;}
        .back:hover{text-decoration:underline;}
        textarea{resize:vertical;}
    </style>
</head>
<body>

<div class="container">
    <h2>Modifier Dossier</h2>

    <?php if(isset($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Titre :</label>
        <input type="text" name="title" value="<?= htmlspecialchars($case['title']) ?>" required>

        <label>Description :</label>
        <textarea name="description" rows="5" required><?= htmlspecialchars($case['description']) ?></textarea>

        <label>Statut :</label>
        <select name="status" required>
            <option value="En cours" <?= $case['status']=='En cours' ? 'selected' : '' ?>>En cours</option>
            <option value="Terminé" <?= $case['status']=='Terminé' ? 'selected' : '' ?>>Terminé</option>
            <option value="En attente" <?= $case['status']=='En attente' ? 'selected' : '' ?>>En attente</option>
        </select>

        <button type="submit">Mettre à jour</button>
    </form>

    <a href="cases.php" class="back">← Retour à Mes Dossiers</a>
</div>

</body>
</html>
