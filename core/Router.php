<?php

class Router
{
    // store req
    // $routes => [
    //    "GET" => "uri",
    // ]
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    // req handler (strip qString, isFileExist, DB injection)
    public function dispatch($uri, $method, $db)
    {
        $uri = strtok($uri, '?');
        try {
            if (!isset($this->routes[$method][$uri])) {
                http_response_code(404);
                echo '404 - Route not found';
                return;
            }

            $handler = $this->routes[$method][$uri];
            [$controllerName, $methodName] = explode('@', $handler);

            $controllerPath = __DIR__ . "/../app/Controllers/{$controllerName}.php";

            if (!file_exists($controllerPath)) {
                throw new Exception("Controller file not found: $controllerName");
            }

            require_once $controllerPath;

            if (!class_exists($controllerName)) {
                throw new Exception("Controller class not found: $controllerName");
            }

            $controller = new $controllerName($db);

            if (!method_exists($controller, $methodName)) {
                throw new Exception("Method not found: $methodName");
            }

            $controller->$methodName();
        } catch (Exception $e) {
            http_response_code(500);
            echo 'Error: ' . $e->getMessage();
        }
    }

}
