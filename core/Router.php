<?php

namespace app\core;

class Router
{
    protected array $routes = [];

    public function get($path, $callback, $middleware = null): void
    {
        $this->routes['get'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    public function post($path, $callback, $middleware = null): void
    {
        $this->routes['post'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    public function resolve(Request $request): void
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            if ($path === '/404') {
                $view = new View();
                if (App::$app->user) {
                    echo $view->render('404', [], 'auth');
                } else {
                    echo $view->render('404', [], 'main');
                }
            } else {
                App::$app->response->redirect('/404');
            }
            return;
        }

        $middlewares = $this->getMiddlewares($route['middleware']);
        $resolvedMiddlewares = $this->resolveMiddlewares($middlewares);

        $response = $this->executeMiddlewares($resolvedMiddlewares, $request, new Response());

        if ($response !== null) {
            $response->send();
            return;
        }

        $callback = $route['callback'];

        if ($callback instanceof \Closure) {
            $callback($request, new Response());
        } else {
            $controller = $callback[0];
            $method = $callback[1];

            if (is_string($controller)) {
                $controller = new $controller;
            }
            $controller->$method($request, new Response());
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
