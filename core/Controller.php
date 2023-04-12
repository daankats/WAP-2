<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

/**
 * Summary of Controller
 */
class Controller
{

    public string $layout = 'main';
    public string $action = '';
    /**
     * Summary of middlewares
     * @var \app\core\middlewares\BaseMiddleware[]
     */
    protected array $middlewares = [];
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = [], $exception = null)
    {
        $params['exception'] = $exception;
        return App::$app->view->renderView($view, $params);
    }
    

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }    

    /**
     * Summary of getMiddlewares
     * @return \app\core\middlewares\BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }


}