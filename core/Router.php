<?php

namespace app\core;

class Router
{
    protected array $routes = [];
    protected array $middlewares = [];

    public function get($path, $callback, $middleware = null)
    {
        $this->routes['get'][$path] = $callback;

        if ($middleware !== null) {
            $this->middlewares['get'][$path] = $middleware;
        }
    }

    public function post($path, $callback, $middleware = null)
    {
        $this->routes['post'][$path] = $callback;

        if ($middleware !== null) {
            $this->middlewares['post'][$path] = $middleware;
        }
    }

    public function resolve(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            // Handle route not found
            return;
        }

        $middlewares = $this->middlewares[$method][$path] ?? [];
        $resolvedMiddlewares = $this->resolveMiddlewares($middlewares);

        $response = $this->executeMiddlewares($resolvedMiddlewares, $request, new Response());

        if ($response !== null) {
            // Middleware returned a response
            $response->send();
            return;
        }

        // Call the callback
        // $callback is either a closure or an array containing the controller class name or object and the method name
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

    public function getMiddlewares(string $route): array
    {
        $middlewares = [];

        foreach ($this->middlewares as $method => $routes) {
            foreach ($routes as $routePattern => $callback) {
                if ($this->matchRoute($routePattern, $route)) {
                    if (isset($callback) && is_array($callback)) {
                        $middlewares = array_merge($middlewares, $callback);
                    }
                }
            }
        }

        return $middlewares;
    }

    private function matchRoute(string $routePattern, string $route): bool
    {
        $pattern = '#^' . rtrim($routePattern, '/') . '/?$#i';
        return preg_match($pattern, $route);
    }

    public function resolveMiddlewares(array $middlewares): array
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
