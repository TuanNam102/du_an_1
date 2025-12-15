<?php
class Router {
    private array $routes = [];

    public function add(string $path, callable $handler): void {
        $this->routes[$path] = $handler;
    }

    public function dispatch(): void {
        $route = isset($_GET['r']) ? $_GET['r'] : (isset($_GET['route']) ? $_GET['route'] : 'home');
        if (isset($this->routes[$route])) {
            ($this->routes[$route])();
            return;
        }
        http_response_code(404);
        echo '404 - Route not found';
    }
}

