<?php

namespace Core;

use Core\View;

class App
{
    /**
     * Запускає додаток.
     *
     * @return void
     */
    public static function run(): void
    {
        Session::start();

        $router = new Router();
        require __DIR__ . '/../routes/web.php';

        try {
            $router->handle();
        } catch (\Throwable $e) {
            http_response_code(500);
            View::render('errors/500', [], 'layouts/header', 'layouts/footer');
            exit;
        }
    }
}
