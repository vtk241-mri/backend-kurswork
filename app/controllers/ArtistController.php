<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Auth;
use App\Models\Artist;

class ArtistController extends Controller
{
    /**
     * Відображення списку артистів з пагінацією.
     *
     * @return void
     */
    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 16;
        $offset = ($page - 1) * $perPage;

        $artistModel = new Artist();

        $total = $artistModel->countAll();
        $totalPages = (int) ceil($total / $perPage);

        $artists = $artistModel->getPaginated($perPage, $offset);

        View::render(
            'artists/index',
            compact('artists', 'page', 'totalPages'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Пошук артистів за запитом у форматі JSON.
     *
     * @return void
     */
    public function searchJson(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $q = trim($_GET['q'] ?? '');
        $artistModel = new Artist();

        if ($q !== '') {
            $artists = $artistModel->searchByName($q);
        } else {
            $artists = $artistModel->all();
        }

        echo json_encode($artists, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Перегляд інформації про артиста.
     *
     * @param int $id Ідентифікатор артиста.
     * @return void
     */
    public function show($id)
    {
        $artist = (new Artist())->find((int) $id);
        if (!$artist) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }

        $tracks = (new Artist())->getTracksByArtistId((int) $id);
        View::render(
            'artists/show',
            compact('artist', 'tracks'),
            'layouts/header',
            'layouts/footer'
        );
    }
}
