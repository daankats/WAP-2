<?php

namespace app\core;

class Router
{
    private Request $request;
    private Response $response;
    private array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    private function addRoute(string $method, string $path, $callback)
    {
        $this->routes[$method][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        echo "Request Method: $method<br>";
        echo "Request Path: $path<br>";

        $callback = $this->findRoute($method, $path);

        if ($callback === null) {
            $this->response->setStatusCode(404);
            echo "Route not found<br>";
            return "404 Not Found";
        }

        if (is_string($callback)) {
            echo "Rendering view: $callback<br>";
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            [$controllerClass, $method] = $callback;

            if (!class_exists($controllerClass)) {
                echo "Controller class $controllerClass not found<br>";
                throw new \Exception("Controller class $controllerClass not found");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                echo "Method $method not found in controller $controllerClass<br>";
                throw new \Exception("Method $method not found in controller $controllerClass");
            }

            return $controller->$method();
        }

        return null;
    }



    private function findRoute(string $method, string $path)
    {
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                if ($this->matchRoute($route, $path)) {
                    return $callback;
                }
            }
        }

        return null;
    }

    private function matchRoute(string $route, string $path)
    {
        $pattern = preg_replace('/\//', '\\/', $route);
        $pattern = '/^' . $pattern . '$/';

        return preg_match($pattern, $path);
    }

    private function renderView(string $view)
    {
        return View::render($view);
    }

    public function getRoutes() {
        return $this->routes;
    }
}
