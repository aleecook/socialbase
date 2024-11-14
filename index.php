// Created by aleecook! (github - https://github.com/aleecook)
<?php
session_start();
require 'config.php';

// Получаем все посты с именами пользователей
$stmt = $db->prepare("SELECT * FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll();

// Обработка формы добавления комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'], $_POST['post_id'])) {
    $commentContent = $_POST['comment_content'];
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'] ?? null;

    if ($userId && $commentContent) {
        $stmt = $db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $userId, $commentContent]);
        header("Location: index.php");
        exit;
    } else {
        echo "Пожалуйста, войдите, чтобы оставлять комментарии.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .post {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
        }
        .post h3 {
            margin: 0;
        }
        .post small {
            color: #666;
        }
        .comments {
            margin-top: 10px;
            padding-left: 20px;
        }
        .comment {
            border-top: 1px solid #eee;
            padding: 5px 0;
        }
    </style>
</head>
<body>

<h1>Все посты</h1>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Вы вошли как <?php echo $_SESSION['name']; ?> | <a href="profile.php">Профиль</a> | <a href="logout.php">Выйти</a></p>
<?php else: ?>
    <p><a href="login.php">Войти</a> или <a href="register.php">Зарегистрироваться</a></p>
<?php endif; ?>

<!-- Вывод всех постов -->
<?php foreach ($posts as $post): ?>
    <div class="post">
        <h3><a href="user_profile.php?user_id=
        <?php echo $post['user_id']; ?>">
            <?php echo htmlspecialchars($post['name']); ?></a>
            </h3>
        <p><?php echo htmlspecialchars($post['content']); ?></p>
        <small>Опубликовано: <?php echo $post['created_at']; ?></small>

        <!-- Вывод комментариев к посту -->
        <div class="comments">
            <h4>Комментарии:</h4>
            <?php
            // Получение комментариев для текущего поста
            $stmt = $db->prepare("SELECT * FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC");
            $stmt->execute([$post['id']]);
            $comments = $stmt->fetchAll();
            ?>

            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['name']); ?>:</strong>
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    <small><?php echo $comment['created_at']; ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Форма добавления комментария -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="index.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <textarea name="comment_content" placeholder="Оставьте комментарий" required></textarea>
                <button type="submit">Отправить</button>
            </form>
        <?php else: ?>
            <p>Пожалуйста, <a href="login.php">войдите</a>, чтобы оставить комментарий.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</body>
</html>
