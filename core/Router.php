<?php
namespace Core;

class Router
{
    protected array $routes = [];

    public function get(string $route, array $action): void
    {
        $this->routes['GET'][$route] = $action;
    }

    public function post(string $route, array $action): void
    {
        $this->routes['POST'][$route] = $action;
    }

    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$method] as $route => $action) {
            $pattern = preg_replace(
                '#\{[a-zA-Z_][a-zA-Z0-9_]*\}#',
                '([^/]+)',
                $route
            );
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->callAction($action, $matches);
                return;
            }
        }

        $this->sendNotFound();
    }

    protected function callAction(array $action, array $params): void
    {
        [$controllerClass, $method] = $action;
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller $controllerClass не знайдено");
        }
        $controller = new $controllerClass;
        if (!method_exists($controller, $method)) {
            throw new \Exception("Метод $method не знайдено в контролері $controllerClass");
        }
        call_user_func_array([$controller, $method], $params);
    }

    protected function sendNotFound(): void
    {
        http_response_code(404);
        echo '404 – Сторінку не знайдено';
        exit;
    }
}
