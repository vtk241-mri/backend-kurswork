<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

spl_autoload_register(function (string $class): void {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

\Core\App::run();
