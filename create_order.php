<?php
require_once __DIR__ . '/includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: auth_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $product_link = $_POST['product_link'] ?? '';
    $user_id = $_SESSION['user_id'];
    $image_path = null;

    if (!empty($_FILES['product_image']['tmp_name'])) {
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $image_path = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path);
    }

    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO products (title, product_link, image_path) VALUES (?,?,?)');
    $stmt->execute([$title, $product_link, $image_path]);
    $product_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare('INSERT INTO orders (customer_id, product_id, status) VALUES (?,?,?)');
    $stmt->execute([$user_id, $product_id, 'pending']);
    $pdo->commit();

    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء طلب - Mediateur</title>
    <style>
        * {margin:0; padding:0; box-sizing:border-box; font-family:'Cairo', sans-serif;}
        body {
            background:#f4f7f8;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            min-height:100vh;
        }

        header {
            background:#2575fc;
            color:#fff;
            text-align:center;
            width:100%;
            padding:20px;
            font-size:24px;
            font-weight:bold;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }

        .form-container {
            background:#fff;
            padding:30px 40px;
            border-radius:12px;
            box-shadow:0 8px 25px rgba(0,0,0,0.1);
            max-width:500px;
            width:90%;
            margin-top:30px;
        }

        h2 {
            color:#2575fc;
            margin-bottom:20px;
            text-align:center;
        }

        form {
            display:flex;
            flex-direction:column;
            gap:15px;
        }

        input[type="text"], input[type="url"], input[type="file"] {
            padding:12px;
            border:1px solid #ccc;
            border-radius:8px;
            outline:none;
            transition:0.3s;
            font-size:16px;
        }

        input:focus {
            border-color:#2575fc;
            box-shadow:0 0 5px rgba(37,117,252,0.3);
        }

        label {
            font-weight:bold;
            color:#333;
        }

        button {
            background:#2575fc;
            color:#fff;
            border:none;
            padding:12px;
            font-size:16px;
            border-radius:8px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover {
            background:#1d5fd8;
        }

        .back {
            display:block;
            margin-top:15px;
            text-align:center;
            color:#2575fc;
            text-decoration:none;
            font-weight:bold;
        }

        .back:hover {text-decoration:underline;}

        @media (max-width:600px) {
            .form-container {padding:20px;}
            h2 {font-size:20px;}
        }
    </style>
</head>
<body>
    <header>منصة الوسيط - Mediateur</header>

    <div class="form-container">
        <h2>إنشاء طلب جديد</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="title">عنوان المنتج</label>
            <input type="text" id="title" name="title" placeholder="اكتب عنوان المنتج" required>

            <label for="product_link">رابط المنتج (إن وجد)</label>
            <input type="url" id="product_link" name="product_link" placeholder="مثال: https://example.com">

            <label for="product_image">أو رفع صورة المنتج</label>
            <input type="file" id="product_image" name="product_image" accept="image/*">

            <button type="submit">إنشاء الطلب</button>
        </form>

        <a href="profile.php" class="back">← العودة إلى الصفحة الشخصية</a>
    </div>
</body>
</html>
