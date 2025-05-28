<?php use Core\Auth; ?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <nav class="admin-nav">
        <div>
            <h3>Адмінпанель</h3>
        </div>

        <ul class="admin-nav__list">
            <li><a class="admin-nav__link" href="/admin/dashboard">Дашборд</a></li>
            <li><a class="admin-nav__link" href="/admin/tracks">Треки</a></li>
            <li><a class="admin-nav__link" href="/admin/users">Користувачі</a></li>
            <li><a class="admin-nav__link" href="/admin/comments">Коментарі</a></li>
            <li><a class="admin-nav__link" href="/admin/genres">Жанри</a></li>
            <li><a class="admin-nav__link" href="/admin/artists">Виконавці</a></li>
            <li><a class="admin-nav__link" href="/admin/tracks/pending">Підтвердження треків</a></li>
        </ul>
    </nav>
</body>

</html>