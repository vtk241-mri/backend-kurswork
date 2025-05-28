<?php
namespace Core;

class Controller
{
    protected function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}
