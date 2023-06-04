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
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    protected function addRoute($method, $path, $callback)
    {
        $this->routes[$method][$path] = $callback;
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
            return App::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            $controllerClass = $callback[0];
            $action = $callback[1];

            $controller = new $controllerClass();
            App::$app->controller = $controller;
            $controller->action = $action;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }

            return $controller->{$action}($this->request, $this->response);
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}
