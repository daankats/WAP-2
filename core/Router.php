<?php

namespace app\core;

class Router
{
    protected array $routes = [];

    public function get($path, $callback, $middleware = null)
    {
        $this->routes['get'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    public function post($path, $callback, $middleware = null)
    {
        $this->routes['post'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    public function resolve(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            // Handle route not found
            return;
        }

        $middlewares = $this->getMiddlewares($route['middleware']);
        $resolvedMiddlewares = $this->resolveMiddlewares($middlewares);

        $response = $this->executeMiddlewares($resolvedMiddlewares, $request, new Response());

        if ($response !== null) {
            // Middleware returned a response
            $response->send();
            return;
        }

        $callback = $route['callback'];

        // Call the callback
        if ($callback instanceof \Closure) {
            $callback($request);
        } else {
            $controller = $callback[0];
            $method = $callback[1];

            // Check if the controller is a string (class name) or an object
            if (is_string($controller)) {
                // Create an instance of the controller class
                $controller = new $controller;
            }

            // Call the method with the $request as argument
            $controller->$method($request);
        }
    }

    public function getMiddlewares($middleware): array
    {
        if (is_array($middleware)) {
            return $middleware;
        } elseif ($middleware !== null) {
            return [$middleware];
        }

        return [];
    }

    private function resolveMiddlewares(array $middlewares): array
    {
        $resolvedMiddlewares = [];

        foreach ($middlewares as $middleware) {
            $resolvedMiddlewares[] = new $middleware();
        }

        return $resolvedMiddlewares;
    }

    public function executeMiddlewares(array $middlewares, Request $request, Response $response): ?Response
    {
        foreach ($middlewares as $middleware) {
            $response = $middleware->handle($request, $response);

            if ($response !== null) {
                return $response;
            }
        }

        return null;
    }
}
