<?php
session_start();
require 'config.php';

// Проверка, передан ли user_id
if (!isset($_GET['user_id'])) {
    echo "Пользователь не найден!";
    exit;
}

$user_id = $_GET['user_id'];

// Получение информации о пользователе
$stmt = $db->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь не найден!";
    exit;
}

// Получение постов пользователя
$stmt = $db->prepare("SELECT content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .post {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
        }
        .post small {
            color: #666;
        }
    </style>
</head>
<body>

<h1><?php echo htmlspecialchars($user['name']); ?></h1>

<h2>Посты пользователя</h2>

<!-- Вывод постов пользователя -->
<?php foreach ($posts as $post): ?>
    <div class="post">
        <p><?php echo htmlspecialchars($post['content']); ?></p>
        <small>Опубликовано: <?php echo $post['created_at']; ?></small>
    </div>
<?php endforeach; ?>

</body>
</html>