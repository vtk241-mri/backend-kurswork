<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Auth;
use App\Models\Track;
use App\Models\Artist;
use App\Models\Genre;

class TrackController extends Controller
{
    /**
     * Показує список всіх треків з фільтрацією за запитом і жанром.
     * Включає пагінацію.
     *
     * @return void
     */
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $selectedGenre = (int) ($_GET['genre'] ?? 0);
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 18;
        $offset = ($page - 1) * $perPage;

        $trackModel = new Track();
        $total = $trackModel->countFiltered($q, $selectedGenre);
        $totalPages = (int) ceil($total / $perPage);
        $tracks = $trackModel->getFiltered($perPage, $offset, $q, $selectedGenre);
        $genres = (new Genre())->all();

        View::render(
            'tracks/index',
            compact('tracks', 'genres', 'q', 'selectedGenre', 'page', 'totalPages'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Повертає результати пошуку треків у форматі JSON.
     * Пошук по назві треку та артисту, з можливістю фільтрації за жанром.
     *
     * @return void
     */
    public function searchJson(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $q = trim($_GET['q'] ?? '');
        $selectedGenre = (int) ($_GET['genre'] ?? 0);
        $tm = new Track();

        // логіка пошуку, аналогічна index(), але повертається JSON
        if ($selectedGenre > 0 && $q === '') {
            $raw = (new Genre())->getTracksByGenreId($selectedGenre);
        } elseif ($selectedGenre > 0 && $q !== '') {
            $base = (new Genre())->getTracksByGenreId($selectedGenre);
            $raw = [];
            foreach ($base as $t) {
                if (stripos($t['title'], $q) !== false) {
                    $raw[] = $t;
                    continue;
                }
                foreach ($tm->getArtists((int) $t['id']) as $a) {
                    if (stripos($a['name'], $q) !== false) {
                        $raw[] = $t;
                        break;
                    }
                }
            }
        } elseif ($q !== '') {
            $raw = $tm->search($q);
        } else {
            $raw = $tm->all();
        }

        // форматування для клієнта
        $out = [];
        foreach ($raw as $t) {
            $artists = $tm->getArtists((int) $t['id']);
            $genres = $tm->getGenres((int) $t['id']);

            $out[] = [
                'id' => $t['id'],
                'title' => $t['title'],
                'cover_image' => $t['cover_image'],
                'artists' => implode(', ', array_column($artists, 'name')),
                'genres' => implode(', ', array_column($genres, 'name')),
            ];
        }

        echo json_encode($out);
        exit;
    }

    /**
     * Показує форму для завантаження нового треку.
     * Доступно лише авторизованим користувачам.
     *
     * @return void
     */
    public function upload(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $artists = (new Artist())->all();
        $genres = (new Genre())->all();
        View::render('tracks/upload', compact('artists', 'genres'));
    }

    /**
     * Обробляє завантаження нового треку.
     * Завантажує аудіофайл і обкладинку, а потім зберігає дані треку.
     *
     * @return void
     */
    public function uploadPost(): void
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        // 1) Завантаження аудіофайлу
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            die('Файл треку не завантажено.');
        }
        $trackName = uniqid('track_') . '_' . basename($_FILES['file']['name']);
        $trackDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/tracks';
        if (!is_dir($trackDir)) {
            mkdir($trackDir, 0755, true);
        }
        if (!move_uploaded_file($_FILES['file']['tmp_name'], "$trackDir/$trackName")) {
            die('Не вдалося зберегти файл треку.');
        }
        $trackPath = '/uploads/tracks/' . $trackName;

        // 2) Завантаження обкладинки
        $coverPath = null;
        if (!empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverName = uniqid('cover_') . '_' . basename($_FILES['cover_image']['name']);
            $coverDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/covers';
            if (!is_dir($coverDir)) {
                mkdir($coverDir, 0755, true);
            }
            if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], "$coverDir/$coverName")) {
                die('Не вдалося зберегти обкладинку.');
            }
            $coverPath = '/uploads/covers/' . $coverName;
        }

        // 3) Збір даних і створення
        $data = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'file_path' => $trackPath,
            'cover_image' => $coverPath,
            'uploaded_by' => Auth::user()['id'],
            'artist_ids' => $_POST['artist_ids'] ?? [],
            'genre_ids' => $_POST['genre_ids'] ?? [],
        ];

        (new Track())->createPending($data);

        header('Location: /');
        exit;
    }

    /**
     * Показує один трек за його ідентифікатором.
     * Повертає сторінку з інформацією про трек, виконавців і жанри.
     *
     * @param int|string $id Ідентифікатор або назва треку
     * @return void
     */
    public function show($id): void
    {
        $track = (new Track())->findById((int) $id);
        $artists = (new Track())->getArtists((int) $id);
        $genres = (new Track())->getGenres((int) $id);

        if (!$track) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }

        View::render('tracks/show', compact('track', 'artists', 'genres'));
    }


}
