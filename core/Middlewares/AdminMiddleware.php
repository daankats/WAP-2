<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (App::isGuest() || !App::isAdmin()) {
            throw new ForbiddenException();
        }
    }
}
