<?php

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    public function resolve(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_array($callback)) {
            [$class, $method] = $callback;
            call_user_func([new $class, $method]);
        } else {
            call_user_func($callback);
        }
    }
}
