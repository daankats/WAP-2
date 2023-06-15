<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;


class Controller
{
    protected array $middlewares = [];
    protected ?View $view = null;

    public function __construct()
    {
        $this->view = new View();
    }

    public function registerMiddleware(BaseMiddleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
