<?php

namespace app\core\middlewares;

use app\core\Auth;
use app\core\exception\ForbiddenException;
use app\core\Request;
use app\core\Response;

class ExamsMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Response $response)
    {
        $uri = $request->getUri();

        if (($uri === '/exams' || $uri === '/exams/registerexam' || $uri === '/exams/unregisterexam') && Auth::isStudent()) {
            return null;
        } elseif (Auth::isAdmin() || Auth::isTeacher()) {
            return null;
        } elseif (Auth::isGuest()) {
            throw new ForbiddenException();
        } else {
            throw new ForbiddenException();
        }

    }
}
