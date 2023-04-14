<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;

class CourseMiddleware extends BaseMiddleware
{
    public function execute()
    {
        $request = App::$app->request;
        $uri = $request->getUri();

        if ($uri === '/courses' || $uri === '/courses/enroll' || $uri === '/courses/leave' && App::isStudent()) {
            return;
        }elseif(App::isAdmin() || App::isDocent()){
            return;
        }

        throw new ForbiddenException();
    }
}
