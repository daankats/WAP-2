<?php

namespace app\core;

class Router
{
    protected array $routes = [];
    protected array $controllers = [];

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            throw new \Exception('Page not found', 404);
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $controllerClass = $callback[0];
            $action = $callback[1];

            if (!isset($this->controllers[$controllerClass])) {
                $this->controllers[$controllerClass] = new $controllerClass();
            }

            $controller = $this->controllers[$controllerClass];

            return call_user_func([$controller, $action], $request);
        }

        return call_user_func($callback, $request);
    }

    protected function renderView($view)
    {
        ob_start();
        include_once App::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}

