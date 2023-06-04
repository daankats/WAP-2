<?php

namespace app\core;

use app\controllers\HomeController;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            // Pagina niet gevonden
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
            exit;
        }

        if (is_string($callback)) {
            // Statische methode oproepen op een controllerklasse
            $callback = [new HomeController(), $callback];
        }

        // Callback uitvoeren
        return call_user_func($callback);
    }
}
