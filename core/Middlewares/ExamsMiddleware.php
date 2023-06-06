<?php

namespace app\core\Middlewares;

use app\core\Auth;
use app\core\exception\ForbiddenException;

class ExamsMiddleware extends BaseMiddleware
{
    public function handle($request, $response)
    {
        $uri = $request->getUri();

        if ($uri === '/exams' || $uri === '/exams/registerexam' || $uri === '/exams/unregisterexam' && Auth::isStudent()) {
            return;
        } elseif (Auth::isAdmin() || Auth::isTeacher()) {
            return;
        }

        throw new ForbiddenException();
    }
}
