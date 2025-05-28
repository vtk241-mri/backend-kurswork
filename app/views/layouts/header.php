<?php use Core\Auth; ?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Музичний портал</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/assets/images/icons/music.svg" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
        <header class="header">
            <nav class="header__nav nav-header">
                <a href="/" class="header__logo nav-header__link">Music Portal</a>
                <ul class="nav-header__list">
                    <li><a class="nav-header__link" href="/">Головна</a></li>
                    <li><a class="nav-header__link" href="/tracks/upload">Завантажити трек</a></li>
                    <li><a class="nav-header__link" href="/genres">Жанри</a></li>
                    <li><a class="nav-header__link" href="/artists">Виконавці</a></li>
                    <?php if (Auth::check()): ?>
                        <li><a class="nav-header__link" href="/profile">Профіль</a></li>
                        <?php if (Auth::user()['role'] === 'admin'): ?>
                            <li><a class="nav-header__link" href="/admin/dashboard">Адмінка</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <div class="nav-header__actions">
                    <?php if (Auth::check()): ?>
                        <a class="nav-header__btn button" href="/logout">Вихід</a>
                    <?php else: ?>
                        <a class="nav-header__btn button" href="/login">Логін</a>
                        <a class="nav-header__btn button" href="/register">Реєстрація</a>
                    <?php endif; ?>

                    <button class="header__icon-menu">
                        <i class="bx bx-menu menu"></i>
                        <i class="bx bx-x exit"></i>
                    </button>
                </div>
            </nav>
        </header>
        <main class="main">