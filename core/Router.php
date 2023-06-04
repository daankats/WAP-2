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
        $this->initializeRoutes();
    }

    private function initializeRoutes()
    {
        $this->routes = [
            'GET' => [],
            'POST' => [],
            // Voeg hier andere requestmethoden toe indien nodig
        ];
    }

    public function get(string $path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    // Voeg hier andere methoden toe voor andere requestmethoden indien nodig

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = null;

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
        }

        if ($callback === null) {
            // Route not found
            $this->response->setStatusCode(404);
            return "404 Not Found";
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $method = $callback[1];
            return call_user_func([$controller, $method]);
        }

        return null;
    }

    private function renderView(string $view)
    {
        // Render the "home" view
        return View::render($view);

    }
}

