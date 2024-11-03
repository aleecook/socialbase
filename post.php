<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $stmt = $db->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->execute([$user_id, $content]);
    echo "Пост опубликован!";
}
?>

<form method="POST" action="post.php">
    <textarea name="content" placeholder="Ваш пост" required></textarea>
    <button type="submit">Опубликовать</button>
</form>