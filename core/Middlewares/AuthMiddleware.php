<?php

namespace app\core\Middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;
use app\core\Request;
use app\core\Response;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function handle(Request $request, Response $response, callable $next)
    {
        $controller = $this->getController();
        if ($controller->action === 'logout') {
            return $next($request, $response);
        }

        if (App::isGuest()) {
            if (empty($this->actions) || in_array($controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        } else {
            if (!App::isAdmin()) {
                throw new ForbiddenException();
            }
        }

        return $next($request, $response);
    }


}
