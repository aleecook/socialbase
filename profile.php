<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

echo "<h1>Профиль пользователя {$user['name']}</h1>";
echo "<p>Email: {$user['email']}</p>";
?>

<a href="post.php">Создать пост</a>
<a href="logout.php">Выйти</a>