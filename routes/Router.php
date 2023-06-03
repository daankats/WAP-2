<?php

namespace app\core;

use app\core\exception\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

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
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            [$controller, $action] = explode('@', $callback);
            $controller = "app\\controllers\\$controller";
            $controllerInstance = new $controller();
            $controllerInstance->$action($this->request, $this->response);
            return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $action = $callback[1];
            $controller->$action($this->request, $this->response);
            return;
        }

        if (is_callable($callback)) {
            call_user_func($callback, $this->request, $this->response);
            return;
        }

        throw new \Exception('Invalid route callback.');
    }

    public function delete($path, $callback)
    {
        $this->routes['DELETE'][$path] = $callback;
    }
}
