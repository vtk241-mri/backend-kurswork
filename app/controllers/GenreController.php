<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Auth;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * Виводить список всіх жанрів.
     * Повертає сторінку з переліком жанрів.
     *
     * @return void
     */
    public function index()
    {
        $genres = (new Genre())->all();
        View::render(
            'genres/index',
            compact('genres'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Виводить інформацію про конкретний жанр за його ідентифікатором.
     * Повертає сторінку з інформацією про жанр та треками, що до нього належать.
     *
     * @param int $id Ідентифікатор жанру
     * @return void
     */
    public function show($id)
    {
        $genre = (new Genre())->find((int) $id);
        if (!$genre) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        $tracks = (new Genre())->getTracksByGenreId((int) $id);
        View::render(
            'genres/show',
            compact('genre', 'tracks'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * GET /api/genres?q=…
     * Повертає JSON-масив жанрів за частковим співпадінням назви
     */
    public function searchJson(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $q = trim($_GET['q'] ?? '');
        $model = new Genre();

        if ($q === '') {
            $res = [];
        } else {
            $res = $model->searchByName($q);
        }

        echo json_encode($res);
        exit;
    }
}
