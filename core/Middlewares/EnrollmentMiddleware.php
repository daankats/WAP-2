<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;

class EnrollmentMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (App::isGuest()) {
            throw new ForbiddenException();
        }
    }
}
