<?php

use Core\Router;
use App\Controllers\TrackController;
use App\Controllers\GenreController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Controllers\CommentController;
use App\Controllers\ArtistController;

$router = new Router();

/**
 * --------------------------------------------------------------------------
 * Публічні маршрути
 * --------------------------------------------------------------------------
 */

// Треки
$router->get('/', [TrackController::class, 'index']);       // Головна: список треків
$router->get('/api/search', [TrackController::class, 'searchJson']); // Пошук треків через JSON

// Завантаження та редагування треків
$router->get('/tracks/upload', [TrackController::class, 'upload']);      // Форма завантаження
$router->post('/tracks/upload', [TrackController::class, 'uploadPost']);  // Обробка завантаження
$router->get('/tracks/{id}', [TrackController::class, 'show']);        // Перегляд одного треку

// Жанри
$router->get('/genres', [GenreController::class, 'index']);    // Список жанрів
$router->get('/genres/{id}', [GenreController::class, 'show']);     // Перегляд жанру
$router->get('/api/genres', [GenreController::class, 'searchJson']);

// Виконавці
$router->get('/artists', [ArtistController::class, 'index']);      // Список виконавців
$router->get('/api/artists', [ArtistController::class, 'searchJson']); // Пошук виконавців через JSON
$router->get('/artists/{id}', [ArtistController::class, 'show']);       // Перегляд виконавця

// Коментарі
$router->post('/comments', [CommentController::class, 'store']);       // Додавання коментаря
$router->get('/api/comments/{trackId}', [CommentController::class, 'getByTrackJson']); // Отримання коментарів треку через JSON
$router->post('/api/comments/{trackId}', [CommentController::class, 'storeJson']);   // Додавання через API і повернення списку

/**
 * --------------------------------------------------------------------------
 * Аутентифікація
 * --------------------------------------------------------------------------
 */
$router->get('/login', [AuthController::class, 'login']);      // Форма входу
$router->post('/login', [AuthController::class, 'loginPost']);  // Обробка входу
$router->get('/register', [AuthController::class, 'register']);   // Форма реєстрації
$router->post('/register', [AuthController::class, 'registerPost']); // Обробка реєстрації
$router->get('/logout', [AuthController::class, 'logout']);     // Вихід

/**
 * --------------------------------------------------------------------------
 * Профіль користувача
 * --------------------------------------------------------------------------
 */
$router->get('/profile', [UserController::class, 'profile']);      // Перегляд профілю
$router->get('/profile/edit', [UserController::class, 'editProfile']);  // Форма редагування профілю
$router->post('/profile/update', [UserController::class, 'updateProfile']); // Оновлення профілю

/**
 * --------------------------------------------------------------------------
 * Адмін-панель
 * --------------------------------------------------------------------------
 */

// Дашборд
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);     // Головна адмін-панелі

// Треки
$router->get('/admin/tracks', [AdminController::class, 'tracks']);       // Список треків
$router->get('/admin/tracks/{id}/edit', [AdminController::class, 'editTrack']);    // Форма редагування
$router->post('/admin/tracks', [AdminController::class, 'storeTrack']);   // Збереження
$router->post('/admin/tracks/{id}/update', [AdminController::class, 'updateTrack']);  // Оновлення
$router->post('/admin/tracks/{id}/delete', [AdminController::class, 'deleteTrack']);  // Видалення

$router->get('/admin/tracks/pending', [AdminController::class, 'pendingTracks']);
$router->post('/admin/tracks/{id}/approve', [AdminController::class, 'approveTrack']);
$router->post('/admin/tracks/{id}/reject', [AdminController::class, 'rejectTrack']);

// Користувачі
$router->get('/admin/users', [AdminController::class, 'users']);       // Список користувачів
$router->get('/admin/users/create', [AdminController::class, 'createUser']);  // Форма створення
$router->post('/admin/users', [AdminController::class, 'storeUser']);   // Збереження нового
$router->get('/admin/users/{id}/edit', [AdminController::class, 'editUser']);    // Форма редагування
$router->post('/admin/users/{id}/update', [AdminController::class, 'updateUser']);  // Оновлення
$router->post('/admin/users/{id}/delete', [AdminController::class, 'deleteUser']);  // Видалення

// Жанри
$router->get('/admin/genres', [AdminController::class, 'indexGenre']);  // Список жанрів
$router->get('/admin/genres/create', [AdminController::class, 'createGenre']); // Форма створення
$router->post('/admin/genres', [AdminController::class, 'storeGenre']);  // Збереження нового
$router->get('/admin/genres/{id}/edit', [AdminController::class, 'editGenre']);   // Форма редагування
$router->post('/admin/genres/{id}/update', [AdminController::class, 'updateGenre']); // Оновлення
$router->post('/admin/genres/{id}/delete', [AdminController::class, 'deleteGenre']); // Видалення

// Коментарі
$router->get('/admin/comments', [AdminController::class, 'comments']);      // Список коментарів
$router->post('/admin/comments/{id}/delete', [AdminController::class, 'deleteComment']); // Видалення

// Виконавці
$router->get('/admin/artists', [AdminController::class, 'artists']);       // Список виконавців
$router->get('/admin/artists/create', [AdminController::class, 'createArtist']);  // Форма створення
$router->post('/admin/artists', [AdminController::class, 'storeArtist']);   // Збереження
$router->get('/admin/artists/{id}/edit', [AdminController::class, 'editArtist']);    // Форма редагування
$router->post('/admin/artists/{id}/update', [AdminController::class, 'updateArtist']);  // Оновлення
$router->post('/admin/artists/{id}/delete', [AdminController::class, 'deleteArtist']);  // Видалення
