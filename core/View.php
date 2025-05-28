<?php
namespace Core;

class View
{
    public static function render(
        string $view,
        array $data = [],
        string $header = 'layouts/header',
        string $footer = 'layouts/footer'
    ) {
        require __DIR__ . "/../app/views/{$header}.php";

        extract($data);

        require __DIR__ . "/../app/views/{$view}.php";

        require __DIR__ . "/../app/views/{$footer}.php";
    }
}
