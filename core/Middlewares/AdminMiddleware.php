<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;
use app\core\Request;
use app\core\Response;

class AdminMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (App::isGuest() || !App::isAdmin()) {
            throw new ForbiddenException();
        }
    }

    public function handle(Request $request, Response $response, callable $next)
    {
        $this->execute();
        return $next($request, $response);
    }
}
