<?php
$host = 'localhost';
$db   = ''; // Замените на имя базы данных
$user = '';  // Замените на ваше имя пользователя
$pass = '';      // Замените на ваш пароль

try {
    $db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>
