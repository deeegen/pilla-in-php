<?php

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

        $callback = $this->routes[$method][$requestPath] ?? null;

        if ($callback) {
            $this->executeCallback($callback);
            return;
        }

        // Match dynamic routes
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

        // If same path exists under other method, return 405
        $otherMethod = $method === 'GET' ? 'POST' : 'GET';
        if (isset($this->routes[$otherMethod][$requestPath])) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            return;
        }

        http_response_code(404);
        echo "404 Not Found";
    }

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
