<?php

class Router
{
    protected array $routes = [];

    public function get(string $uri, string $controller): void
    {
        $this->routes['GET'][$this->normalizeUri($uri)] = $controller;
    }

    public function post(string $uri, string $controller): void
    {
        $this->routes['POST'][$this->normalizeUri($uri)] = $controller;
    }

    public function dispatch(string $uri, string $method, PDO $db): void
    {
        try {
            $uri = $this->normalizeUri($uri);

            $handler = $this->routes[$method][$uri] ?? null;

            if (!$handler) {
                http_response_code(404);
                echo "404 - Route not found for {$uri}";
                return;
            }

            $parts = explode('@', $handler);
            $controllerName = $parts[0];
            $methodName = $parts[1] ?? 'index';

            $controllerPath = __DIR__ . "/../app/Controllers/{$controllerName}.php";

            if (!file_exists($controllerPath)) {
                throw new Exception("Controller {$controllerName} not found.");
            }

            require_once $controllerPath;

            if (!class_exists($controllerName)) {
                throw new Exception("Class {$controllerName} not found.");
            }

            $controller = new $controllerName($db);

            if (!method_exists($controller, $methodName)) {
                throw new Exception("Method {$methodName} not found in {$controllerName}.");
            }

            $controller->$methodName();

        } catch (Throwable $e) {
            http_response_code(500);

            echo "Application Error: " . $e->getMessage();

            // error_log($e);
        }
    }

    private function normalizeUri(string $uri): string
    {
        $uri = strtok($uri, '?');     // rem query string
        $uri = rtrim($uri, '/');      // rem trailing slash
        return $uri === '' ? '/' : $uri;
    }
}
