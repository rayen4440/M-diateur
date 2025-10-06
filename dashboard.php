<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
if (!is_logged_in()) redirect('../auth/login.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utilisateur - Mediateur Project</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f8; color:#333; }
        header { background-color:#2575fc; color:#fff; padding:20px 40px; text-align:center; font-size:24px; font-weight:bold; }
        .container { max-width:800px; margin:40px auto; padding:0 20px; }
        h1 { margin-bottom:30px; color:#2575fc; text-align:center; }
        .card-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; }
        .card { background:#fff; flex:1 1 200px; max-width:250px; padding:30px 20px; border-radius:12px; text-align:center; box-shadow:0 8px 20px rgba(0,0,0,0.1); text-decoration:none; color:#333; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow:0 12px 25px rgba(0,0,0,0.2); }
        .card h2 { margin-bottom:10px; color:#2575fc; }
        .card p { color:#666; font-size:14px; }
        .logout { display:block; text-align:center; margin:40px 0; text-decoration:none; color:#fff; background-color:#ff4b5c; padding:12px 25px; border-radius:8px; transition:0.3s; }
        .logout:hover { background-color:#ff1f3a; }
        @media(max-width:600px){ .card-container { flex-direction:column; align-items:center; } }
    </style>
</head>
<body>

    <header>Dashboard Utilisateur</header>

    <div class="container">
        <div class="card-container">
            <a href="profile.php" class="card">
                <h2>Profil</h2>
                <p>Voir et modifier vos informations personnelles</p>
            </a>

            <a href="dossiers.php" class="card">
                <h2>Mes Dossiers</h2>
                <p>Consulter vos dossiers de médiation</p>
            </a>

            <a href="statistiques.php" class="card">
                <h2>Statistiques</h2>
                <p>Voir vos statistiques personnelles</p>
            </a>
        </div>

        <a href="../auth/logout.php" class="logout">Déconnexion</a>
    </div>

</body>
</html>
