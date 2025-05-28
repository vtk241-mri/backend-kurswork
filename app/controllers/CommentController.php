<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Збереження нового коментаря для треку.
     * Приймає POST запит і перевіряє чи користувач авторизований.
     * Повертає користувача на сторінку треку після збереження.
     *
     * @return void
     */
    public function store()
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        (new Comment())->create([
            'track_id' => (int) $_POST['track_id'],
            'user_id' => Auth::user()['id'],
            'content' => trim($_POST['content'] ?? '')
        ]);

        header('Location: /tracks/' . (int) $_POST['track_id']);
        exit;
    }

    /**
     * Отримання всіх коментарів для певного треку у форматі JSON.
     *
     * @param int $trackId Ідентифікатор треку
     * @return void
     */
    public function getByTrackJson(int $trackId)
    {
        header('Content-Type: application/json');
        echo json_encode((new Comment())->getByTrackId($trackId));
        exit;
    }

    /**
     * Збереження нового коментаря через API для певного треку.
     * Приймає FormData (content), перевіряє валідність.
     * Повертає оновлений список коментарів у форматі JSON.
     *
     * @param int $trackId Ідентифікатор треку
     * @return void
     */
    public function storeJson(int $trackId): void
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $content = trim($_POST['content'] ?? '');
        if ($content === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Empty']);
            exit;
        }

        $model = new \App\Models\Comment();
        $model->create([
            'track_id' => $trackId,
            'user_id' => Auth::user()['id'],
            'content' => $content,
        ]);

        $comments = $model->getByTrackId($trackId);
        echo json_encode($comments);
        exit;
    }
}
