<?php

namespace app\core\Middlewares;

use app\core\Auth;
use app\core\exception\ForbiddenException;

class EnrollmentMiddleware extends BaseMiddleware
{
    public function handle($request, $response)
    {
        if (Auth::isGuest()) {
            throw new ForbiddenException();
        }
    }
}
