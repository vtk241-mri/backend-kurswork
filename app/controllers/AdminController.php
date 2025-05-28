<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Auth;
use App\Models\Track;
use App\Models\User;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Comment;

class AdminController extends Controller
{
    /**
     * Перевіряє, що поточний користувач — адміністратор.
     */
    public function __construct()
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            View::render('errors/403', [], 'layouts/header', 'layouts/footer');
            exit;
        }
    }

    /**
     * Відображає панель адміністратора з базовою статистикою.
     *
     * @return void
     */
    public function dashboard(): void
    {
        $trackModel = new Track();
        $userModel = new \App\Models\User();
        $commentModel = new \App\Models\Comment();

        $stats = [
            'tracks' => $trackModel->count(),
            'users' => $userModel->count(),
            'new_comments' => $commentModel->count(),
            'new_regs' => $userModel->count(),
        ];

        $recentTracks = $trackModel->recent(5);
        $recentUsers = $userModel->recent(5);
        $recentComments = $commentModel->recentWithMeta(5);

        View::render(
            'admin/dashboard',
            compact('stats', 'recentTracks', 'recentUsers', 'recentComments'),
            'layouts/header',
            'layouts/footer'
        );
    }

    // ------------------------------------------------
    //  Методи для управління треками
    // ------------------------------------------------

    /**
     * Відображає список треків із пошуком та пагінацією.
     *
     * @return void
     */
    public function tracks(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $model = new Track();
        $total = $model->countFiltered($q);
        $totalPages = (int) ceil($total / $perPage);
        $tracks = $model->getFiltered($perPage, $offset, $q);

        View::render(
            'admin/tracks',
            compact('tracks', 'q', 'page', 'totalPages'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Форма редагування треку.
     *
     * @param int $id Ідентифікатор треку
     * @return void
     */
    public function editTrack(int $id): void
    {
        $trackModel = new Track();
        $track = $trackModel->findById($id);
        $artists = (new Artist())->all();
        $genres = (new \App\Models\Genre())->all();
        $selectedArtistIds = array_column($trackModel->getArtists($id), 'id');
        $selectedGenreIds = array_column($trackModel->getGenres($id), 'id');

        View::render(
            'admin/tracks_edit',
            compact('track', 'artists', 'genres', 'selectedArtistIds', 'selectedGenreIds'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробка оновлення треку.
     *
     * @param int $id Ідентифікатор треку
     * @return void
     */
    public function updateTrack(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::check() || Auth::user()['role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $trackModel = new Track();
        $track = $trackModel->findById($id);
        if (!$track) {
            http_response_code(404);
            echo "Трек не знайдено.";
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? $track['title']),
            'description' => trim($_POST['description'] ?? $track['description']),
            'artist_ids' => $_POST['artist_ids'] ?? [],
            'genre_ids' => $_POST['genre_ids'] ?? [],
        ];

        // Завантаження нового аудіофайлу
        if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fn = uniqid('track_') . '_' . basename($_FILES['file']['name']);
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/tracks';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['file']['tmp_name'], "$dir/$fn");
            $data['file_path'] = '/uploads/tracks/' . $fn;
        }

        // Завантаження нової обкладинки
        if (!empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $fn = uniqid('cover_') . '_' . basename($_FILES['cover_image']['name']);
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/covers';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['cover_image']['tmp_name'], "$dir/$fn");
            $data['cover_image'] = '/uploads/covers/' . $fn;
        }

        $trackModel->update($id, $data);
        $this->redirect('/admin/tracks');
    }

    /**
     * Видалення треку.
     *
     * @param int $id Ідентифікатор треку
     * @return void
     */
    public function deleteTrack(int $id): void
    {
        (new Track())->delete($id);
        $this->redirect('/admin/tracks');
    }

    /**
     * GET /admin/tracks/pending
     * Список нових треків для підтвердження
     */
    public function pendingTracks(): void
    {
        $pending = (new Track())->getPending();
        View::render(
            'admin/tracks_pending',
            compact('pending'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * POST /admin/tracks/{id}/approve
     */
    public function approveTrack(int $id): void
    {
        (new Track())->approve($id);
        $this->redirect('/admin/tracks/pending');
    }

    /**
     * POST /admin/tracks/{id}/reject
     */
    public function rejectTrack(int $id): void
    {
        (new Track())->reject($id);
        $this->redirect('/admin/tracks/pending');
    }

    // ------------------------------------------------
    //  Методи для управління користувачами
    // ------------------------------------------------

    /**
     * Відображає список користувачів із пошуком та пагінацією.
     *
     * @return void
     */
    public function users(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $model = new User();
        $total = $model->countFiltered($q);
        $totalPages = (int) ceil($total / $perPage);
        $users = $model->getFiltered($perPage, $offset, $q);

        View::render(
            'admin/users',
            compact('users', 'q', 'page', 'totalPages'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Показує форму для створення нового користувача.
     *
     * @return void
     */
    public function createUser(): void
    {
        View::render(
            'admin/users_create',
            [],
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробляє створення нового користувача.
     *
     * @return void
     */
    public function storeUser(): void
    {
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            'role' => $_POST['role'] ?? 'user',
        ];

        (new User())->create($data);
        $this->redirect('/admin/users');
    }

    /**
     * Показує форму редагування існуючого користувача.
     *
     * @param int $id Ідентифікатор користувача
     * @return void
     */
    public function editUser(int $id): void
    {
        $user = (new User())->find($id);
        View::render(
            'admin/users_edit',
            compact('user'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробляє оновлення даних користувача.
     *
     * @param int $id Ідентифікатор користувача
     * @return void
     */
    public function updateUser(int $id): void
    {
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
        ];

        (new User())->update($id, $data);
        $this->redirect('/admin/users');
    }

    /**
     * Видаляє користувача з бази.
     *
     * @param int $id Ідентифікатор користувача
     * @return void
     */
    public function deleteUser(int $id): void
    {
        (new User())->delete($id);
        $this->redirect('/admin/users');
    }


    // ------------------------------------------------
    //  Методи для управління жанрами
    // ------------------------------------------------

    /**
     * Відображає список жанрів.
     *
     * @return void
     */
    public function indexGenre(): void
    {
        $genres = (new Genre())->all();
        View::render(
            'admin/genres',
            compact('genres'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Показує форму створення нового жанру.
     *
     * @return void
     */
    public function createGenre(): void
    {
        View::render(
            'admin/genres_create',
            [],
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробляє створення нового жанру.
     *
     * @return void
     */
    public function storeGenre(): void
    {
        (new Genre())->create(['name' => trim($_POST['name'] ?? '')]);
        $this->redirect('/admin/genres');
    }

    /**
     * Показує форму редагування жанру.
     *
     * @param int $id Ідентифікатор жанру
     * @return void
     */
    public function editGenre(int $id): void
    {
        $genre = (new Genre())->find($id);
        View::render(
            'admin/genres_edit',
            compact('genre'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробляє оновлення жанру.
     *
     * @param int $id Ідентифікатор жанру
     * @return void
     */
    public function updateGenre(int $id): void
    {
        (new Genre())->update($id, ['name' => trim($_POST['name'] ?? '')]);
        $this->redirect('/admin/genres');
    }

    /**
     * Видаляє жанр.
     *
     * @param int $id Ідентифікатор жанру
     * @return void
     */
    public function deleteGenre(int $id): void
    {
        (new Genre())->delete($id);
        $this->redirect('/admin/genres');
    }

    // ------------------------------------------------
    //  Методи для управління коментарями
    // ------------------------------------------------

    /**
     * Відображає список коментарів із пошуком та пагінацією.
     *
     * @return void
     */
    public function comments(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $model = new Comment();
        $total = $model->countFiltered($q);
        $totalPages = (int) ceil($total / $perPage);
        $comments = $model->getFiltered($perPage, $offset, $q);

        View::render(
            'admin/comments',
            compact('comments', 'q', 'page', 'totalPages'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Видаляє коментар.
     *
     * @param int $id Ідентифікатор коментаря
     * @return void
     */
    public function deleteComment(int $id): void
    {
        (new Comment())->delete($id);
        $this->redirect('/admin/comments');
    }

    // ------------------------------------------------
    //  Методи для управління артистами
    // ------------------------------------------------

    /**
     * Відображає список артистів із пошуком та пагінацією.
     *
     * @return void
     */
    public function artists(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 14;
        $offset = ($page - 1) * $perPage;

        $model = new Artist();
        $total = $model->countFiltered($q);
        $totalPages = (int) ceil($total / $perPage);
        $artists = $model->getFiltered($perPage, $offset, $q);

        View::render(
            'admin/artists',
            compact('artists', 'q', 'page', 'totalPages'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Показує форму створення нового артиста.
     *
     * @return void
     */
    public function createArtist(): void
    {
        View::render(
            'admin/artists_create',
            [],
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Створює нового артиста.
     *
     * @return void
     */
    public function storeArtist(): void
    {
        $imagePath = null;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $name = uniqid('artist_') . '.' . $ext;
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/artists';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $dest = "$dir/$name";
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imagePath = '/uploads/artists/' . $name;
            }
        }
        (new Artist())->create([
            'name' => trim($_POST['name'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'image' => $imagePath,
        ]);
        $this->redirect('/admin/artists');
    }

    /**
     * Показує форму редагування артиста.
     *
     * @param int $id Ідентифікатор артиста
     * @return void
     */
    public function editArtist(int $id): void
    {
        $artist = (new Artist())->find($id);
        View::render(
            'admin/artists_edit',
            compact('artist'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Оновлює дані артиста.
     *
     * @param int $id Ідентифікатор артиста
     * @return void
     */
    public function updateArtist(int $id): void
    {
        $artistModel = new Artist();
        $artist = $artistModel->find($id);

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $name = uniqid('artist_') . '.' . $ext;
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/artists';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $dest = "$dir/$name";
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imagePath = '/uploads/artists/' . $name;
            }
        } else {
            $imagePath = $artist['image'] ?? null;
        }

        $artistModel->update($id, [
            'name' => trim($_POST['name'] ?? $artist['name']),
            'bio' => trim($_POST['bio'] ?? $artist['bio']),
            'image' => $imagePath,
        ]);

        $this->redirect('/admin/artists');
    }

    /**
     * Видаляє артиста.
     *
     * @param int $id Ідентифікатор артиста
     * @return void
     */
    public function deleteArtist(int $id): void
    {
        (new Artist())->delete($id);
        $this->redirect('/admin/artists');
    }

}
