<?php

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Register a GET route
     */
    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * Register a POST route
     */
    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * Resolve the current request
     */
    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        $callback = $this->routes[$method][$requestPath] ?? null;

        if ($callback) {
            $this->executeCallback($callback);
            return;
        }

        foreach ($this->routes[$method] as $route => $cb) {
            $pattern = preg_replace('#\{([\w]+)\}#', '(?P<\1>[^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $requestPath, $matches)) {
                $params = array_filter(
                    $matches,
                    'is_string',
                    ARRAY_FILTER_USE_KEY
                );
                $this->executeCallback($cb, $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    /**
     * Execute a controller method or closure
     */
    private function executeCallback(callable|array $callback, array $params = []): void
    {
        if (is_array($callback)) {
            [$class, $method] = $callback;
            if (class_exists($class)) {
                $instance = new $class();
                if (method_exists($instance, $method)) {
                    call_user_func_array([$instance, $method], $params);
                    return;
                }
            }
            http_response_code(500);
            echo "500 Internal Server Error: Controller method not found.";
            return;
        }

        call_user_func_array($callback, $params);
    }
}
