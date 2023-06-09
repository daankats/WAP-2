<?php

namespace app\core\Middlewares;

use app\core\Auth;
use app\core\middlewares\BaseMiddleware;
use app\core\Request;
use app\core\Response;
use app\core\exception\ForbiddenException;

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
        }else{
            throw new ForbiddenException();
        }  

    }
}
